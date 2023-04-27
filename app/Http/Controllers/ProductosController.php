<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Foto;
use App\Models\Producto;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductoResource;
use App\Http\Requests\CreateProductoRequest;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', 15);
        $page = Arr::get($searchParams, 'page', 1);
        $keyword = Arr::get($searchParams, 'keyword', null);
        $categoria_id = Arr::get($searchParams, 'categoria_id', 0);

        $productos = Producto::with(["fotos", "categoria"])->where("tienda_id", $request->tienda_id);

        if ($keyword != null) {
            $productos = $productos->where("nombre", "like", "%".$keyword."%")
            ->orWhere("descripcion", "like", "%".$keyword."%");
        }

        if ($categoria_id > 0) {
            $productos = $productos->where("categoria_id", $categoria_id);
        }

        $productos = $productos->paginate($limit, ['*'], '', $page);

        return ProductoResource::collection($productos);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductoRequest $request)
    {
        // Creando datos del producto
        $producto = null;

        if ($request->has("id") && $request->id > 0) {
            $producto = Producto::find($request->id);
            $producto->nombre = $request->nombre;
            $producto->precio = $request->precio;
            $producto->descripcion = $request->descripcion;
            $producto->tienda_id = $request->tienda_id;
            $producto->categoria_id = $request->categoria_id;
            $producto->save();
        } else {
            $producto = Producto::create($request->all());
        }


        // Guardando fotos
        if ($request->totalFiles > 0) {

            for ($i=1; $i <= $request->totalFiles; $i++) {

                if ($request->hasFile('files'.$i)) {

                    $file = $request->file('files'.$i);
                    $extension = $file->getClientOriginalExtension();
                    $nuevoNombre = "prod".$producto->id."_".$this->getStringRandom().".".strtolower( $extension );

                    $ruta = Storage::putFileAs('public/productos/fotos', $request->file('files'.$i), $nuevoNombre );

                    Foto::create([
                        'producto_id' => $producto->id,
                        'ruta' => $ruta,
                    ]);

                }

            }
        }

        $producto = Producto::with(["fotos"])->find($producto->id);

        $response = [
            "state" => "success",
            "message" => "Datos del producto registrados",
            "data" => new ProductoResource($producto)
        ];

        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::with(["fotos"])->find($id);

        $response = [
            "state" => "success",
            "message" => "",
            "data" => new ProductoResource($producto)
        ];

        return response()->json($response, Response::HTTP_OK);

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $producto = Producto::with("fotos")->find($id);

            if ($producto != null) {
                foreach ($producto->fotos as $foto) {
                    $foto->delete();
                }

                $producto->delete();
            }

            $response = [
                "state" => "success",
                "message" => "El registro ha sido eliminado"
            ];

            DB::commit();

            return response()->json($response, Response::HTTP_OK);


        } catch (Exception $ex) {
            DB::rollBack();

            $response = [
                "state" => "error",
                "message" => $ex->getMessage()
            ];
            return response()->json($response, Response::HTTP_OK);
        }

    }


    private function getStringRandom()
    {
        $letters = 'BCDFGHJKLMNPQRSTVWXYZAEIU';
        $code = '';
        for ($i = 0; $i < 7; $i++) {
            $code  = $code . $letters[rand(0, strlen($letters) - 1)];
        }
        return $code;
    }
}

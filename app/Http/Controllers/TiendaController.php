<?php

namespace App\Http\Controllers;

use App\Http\Resources\Opciones\TiendaResource as OpcionesTiendaResource;
use App\Http\Resources\TiendaResource;
use App\Models\Tienda;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TiendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $limit = Arr::get($request->all(), 'limit', 15);
        $keyword = Arr::get($request->all(), 'keyword', null);
        $categoria_id = Arr::get($request->all(), 'categoria_id', null);

        $tiendas = Tienda::with('categoria');

        if($keyword != null) {
            $tiendas = $tiendas->where(function($query) use ($keyword) {
                $query->where('razonsocial','like','%'.$keyword.'%')
                ->orWhere("ruc", $keyword);
            });
        }

        if($categoria_id != null) {
            $tiendas = $tiendas->where('categoria_id', $categoria_id);
        }

        return TiendaResource::collection($tiendas->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // @audit falta colocar permiso
        $request->validate([
            'ruc' => 'required',
            'razonsocial' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
            'categoria_id' => 'required',
        ]);

        

        if ($request->has('id') && $request->id > 0) {
            $tienda = Tienda::find($request->id);
            $tienda->ruc = $request->ruc;
            $tienda->razonsocial = $request->razonsocial;
            $tienda->latitud = (float)$request->latitud;
            $tienda->longitud = $request->longitud;
            $tienda->categoria_id = $request->categoria_id;
            $tienda->save();


            $logo = null;
            if( $request->hasFile('file')) {
                $extension = $request->file->getClientOriginalExtension();
                Storage::putFileAs('public/tiendas', $request->file('file'),$request->razonsocial.time().'.'.$extension);
                $logo = $request->razonsocial.time().'.' .$extension;

                $tienda->logo = $logo;
                $tienda->save();
            }
            return response()->json(['message' => 'Datos actualizados'],Response::HTTP_OK);

        } else {

            $logo = null;
            if( $request->hasFile('file')) {
                $extension = $request->file->getClientOriginalExtension();
                Storage::putFileAs('public/tiendas', $request->file('file'),$request->razonsocial.time().'.'.$extension);
                $logo = $request->razonsocial.time().'.' .$extension;
            }

            Tienda::create([
                'ruc' => $request->ruc,
                'razonsocial' => $request->razonsocial,
                'logo' => $logo,
                'latitud' => (float)$request->latitud,
                'longitud' => $request->longitud,
                'categoria_id' => $request->categoria_id,
                'calificacion_promedio' => 0
            ]);

            return response()->json(['message' => 'Tienda registrada.'],Response::HTTP_OK);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $tienda = Tienda::find($id);
        return new TiendaResource($tienda);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function listaopciones(Request $request)
    {
        $keyword = Arr::get($request->all(),'keyword',null);

        $listaTiendas = Tienda::query();
        if($keyword != null){
            $listaTiendas = $listaTiendas->where('razonsocial','like','%'.$keyword.'%')
                ->orWhere('ruc','like','%'.$keyword.'%');
        }

        return OpcionesTiendaResource::collection($listaTiendas->take(10)->get());
    }
}

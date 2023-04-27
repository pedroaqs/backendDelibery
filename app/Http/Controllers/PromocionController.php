<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromocionCreateRequest;
use App\Http\Resources\GlobalResource;
use App\Http\Resources\PromocionResource;
use App\Models\Promocion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = $request->all();
        $limit = Arr::get($parametros,'limit',14);
        // $keyword = Arr::get($parametros,'fecha_desde',null);

        $promociones = Promocion::query()->with(['producto','tienda']);

        // if ($keyword != null && $keyword != '') {
        //     $promociones = $promociones->where('nro_cama','like','%'.$keyword.'%');
        // }

        return GlobalResource::collection($promociones->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromocionCreateRequest $request)
    {
        //
        try {

            $nuevaPromocion = Promocion::create([
                'fecha_inicial' => $request->fecha_inicial,
                'fecha_final' => $request->fecha_final,
                'tienda_id' => $request->tienda_id,
                'producto_id' => $request->producto_id,
                'porcentaje_descuento' => $request->porcentaje_descuento,
                'precio_promocion' => $request->precio_promocion,
                'activo' => true
            ]);

            return response()->json(['message'=> 'Promocion registrada','type'=>'success'],Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error'=> 'INTERNAL SERVER ERROR AGREGAR PROMOCION'],Response::HTTP_INTERNAL_SERVER_ERROR);
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
        return new PromocionResource(Promocion::with('tienda')->findOrFail($id));
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
        try {

            $promocion = Promocion::find($id);

            $promocion->fecha_inicial = $request->fecha_inicial;
            $promocion->fecha_final = $request->fecha_final;
            if($request->porcentaje_descuento != null) {

                $promocion->porcentaje_descuento = $request->porcentaje_descuento;
                $promocion->precio_promocion = null;
            }else {
                $promocion->porcentaje_descuento = null;
                $promocion->precio_promocion = $request->precio_promocion;
            }
            $promocion->save();

            return response()->json(['message'=> 'Promocion actualizada','type'=>'success'],Response::HTTP_OK);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error'=> 'INTERNAL SERVER ERROR EDITAR PROMOCION'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {
            $promocion = Promocion::find($id);

            $promocion->activo = false;
            $promocion->save();

            return response()->json(['message'=> 'Promocion desactivada','type'=>'info'],Response::HTTP_OK);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error'=> 'INTERNAL SERVER ERROR DESACTIVAR PROMOCION'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

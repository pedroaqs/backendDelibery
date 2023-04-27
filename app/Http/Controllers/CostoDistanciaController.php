<?php

namespace App\Http\Controllers;

use App\Http\Requests\CostoDistanciaCreateRequest;
use App\Http\Resources\GlobalResource;
use App\Models\CostoDistancia;
use App\Rules\CostoDistanciaRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CostoDistanciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return GlobalResource::collection(CostoDistancia::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostoDistanciaCreateRequest $request)
    {
        //
        try {
            //code...
            $registrosantiguos = CostoDistancia::all();

            foreach ($registrosantiguos as $registro) {
                # code...
                $registro->delete();
            }

            foreach ($request->costos['data'] as $value) {
                CostoDistancia::create([
                    'distancia_inicial' => $value['distancia_inicial'],
                    'distancia_final' => $value['distancia_final'],
                    'costo' => $value['costo']
                ]);
            }
            return response()->json(['message' => 'Costos por distancia actualizada.','state' => 'success'],Response::HTTP_OK);
        } catch (Exception $e) {
           Log::error($e);
           return response()->json(['error' => 'INTERNAL SERVER ERROR'],Response::HTTP_INTERNAL_SERVER_ERROR);
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
}

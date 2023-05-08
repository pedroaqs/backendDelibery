<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DirireccionesResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Direccion;
class PruebaDireccionesController extends Controller
{
    public function prueba(Request $request, $user_id){

        $validator = Validator::make($request->all(), [
          
            'direccion' => 'required|string',
            'referencia' => 'nullable|string',
            'latitud' => 'nullable|string',
            'longitud' => 'nullable|string',
        ]);
                
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
    
        $nuevaDireccion = new Direccion;
        $nuevaDireccion->direccion = $request->direccion;
        $nuevaDireccion->referencia = $request->referencia;
        $nuevaDireccion->latitud = $request->latitud;
        $nuevaDireccion->longitud = $request->longitud;
        $nuevaDireccion->user_id = $user_id;
        $nuevaDireccion->save();
       
        return response()->json(['message' => 'Dirección registrada correctamente.','state' => 'success'],200);
    }

    public function listarDirecciones(Request $request, $user_id){
        $direcciones = Direccion::where('user_id', $user_id)->get();
        return DirireccionesResource::collection($direcciones);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\PedidosUsuariosResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DetallepedidoController extends Controller
{
    public function listaPedidos(Request $request)
    {

        try {

            // Obtener el usuario autenticado
            $user = Auth::user();
            Log::info($user);
            // Obtener los pedidos del usuario
            $pedidos = $user->pedidos;

            // Retornar los pedidos en formato JSON
            return PedidosUsuariosResource::collection($pedidos);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        // Verificar si el usuario está autenticado


        // Retornar una respuesta de error si el usuario no está autenticado

    }
}

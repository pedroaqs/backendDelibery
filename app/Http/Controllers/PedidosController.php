<?php

namespace App\Http\Controllers;

use App\Events\PedidoEntregado;
use App\Events\PedidosReload;
use App\Http\Requests\CreatePedidoRequest;
use App\Http\Resources\GlobalResource;
use App\Http\Resources\ListaPedidosResource;
use App\Http\Resources\PedidoResource;
use App\Models\DetallePedido;
use App\Models\EstadosPedido;
use App\Models\PedidoEstado;
use App\Models\Pedidos;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $limit = Arr::get($request->all(),'limit',15);
        $pedidosList = Pedidos::whereYear('fecha',Carbon::now()->year)
            ->with('estadoactual.estado')
            ->orderBy('fecha','asc');
        // $pedidosList = Pedidos::join('pedido_estado','pedido_estado.pedido_id','pedidos.id')
        // ->join('estados_pedido','estados_pedido.id','pedido_estado.estado_id')
        // ->orderBy('estados_pedido.id','desc')
        // ->orderBy('pedidos.fecha','desc');
        return ListaPedidosResource::collection($pedidosList->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePedidoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePedidoRequest $request)
    {
        //
        Log::info($request);
        DB::beginTransaction();
        try {
            //code...
            $fechaActual = now();
            $pedido = Pedidos::create([
                'codigo' => $this->getCodigo($fechaActual),
                'fecha' => $fechaActual,
                'total' => 0.0,
                'cliente_id' => $request->tipo == 'CLIENTE'? Auth::user()->id : null,
                'repartidor_id' => $request->repartidor_id,
                'tienda_id' => $request->tienda_id,
                'cliente_sin_registro' => $request->cliente_sin_registro,
                'telefono_cliente_sin_registro' => $request->telefono_cliente_sin_registro,
            ]);

            Log::info($pedido);

            $total = 0.0;

            foreach ($request->detalle_pedido as $detalle) {

                $temp = json_decode(json_encode($detalle));
                $total = $total + $temp->subtotal;

                DetallePedido::create([
                    'pedido_id' =>  $pedido->id,
                    'producto_id' => $temp->producto_id,
                    'cantidad' =>  $temp->cantidad,
                    'subtotal' =>  $temp->subtotal,
                    'comentarios' =>  $temp->comentarios,
                ]);
                // @audit falta detalle costo
            }

            $pedido->total = $total;
            $pedido->save();

            // Estado registrado
            $estadoRegistrado = EstadosPedido::where('nombre','REGISTRADO')->first();
            PedidoEstado::create([
                'hora' => $fechaActual,
                'pedido_id' => $pedido->id,
                'estado_id' => $estadoRegistrado->id
            ]);
            // Estado asignado
            if($request->repartidor_id != null) {
                $estadoAsignado = EstadosPedido::where('nombre','ASIGNADO')->first();
                PedidoEstado::create([
                    'hora' => $fechaActual,
                    'pedido_id' => $pedido->id,
                    'estado_id' => $estadoAsignado->id
                ]);
            }

            broadcast(new PedidosReload())->toOthers();
            DB::commit();
            return response()->json(['message' => 'PedidoRegistrado'],Response::HTTP_OK);
        } catch (Exception $ex) {
           Log::error($ex);
           DB::rollback();
           return response()->json(['error' => 'Ocurrio un error desconocido'],Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $pedido = Pedidos::
        with([
            'estados',
            'estadoactual',
            'cliente.persona',
            'repartidor.persona',
            'tienda',
            'detallepedido.producto'
        ])
        ->find($id);
        return new PedidoResource($pedido);
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

    public function asignarPedido(Request $request) {

        $request->validate([
            'pedido_id' => 'required',
            'repartidor_id' => 'required',
        ]);
        try {
            //code...
            $pedido = Pedidos::find($request->pedido_id)
            ->update(['repartidor_id' => $request->repartidor_id]);

            $estado = EstadosPedido::where('nombre','ASIGNADO')->first();
            PedidoEstado::create([
                'hora' => now(),
                'pedido_id' => $request->pedido_id,
                'estado_id' => $estado->id
            ]);
            broadcast(new PedidosReload())->toOthers();
            return response()->json(['message' => 'Repartidor asignado'],Response::HTTP_OK);
        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json(['error' => 'Ocurrio un error desconocido'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    // Cambiar estado Pedido

    public function cambiarEstadoPedido(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required',
            'estado_id' => 'required'
        ]);

        try {
            //code...
            PedidoEstado::create([
                'hora' => now(),
                'pedido_id' => $request->pedido_id,
                'estado_id' => $request->estado_id
            ]);
            broadcast(new PedidosReload())->toOthers();
            if($request->estado_id == 5){
                // @audit emitir evento motorizado disponible
                $pedido = Pedidos::find($request->pedido_id);
                broadcast(new PedidoEntregado($pedido))->toOthers();

            }
            return response()->json(['message' => 'Estado del pedido actualizado'],Response::HTTP_OK);
        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json(['error' => 'Ocurrio un error desconocido'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    // Para crear el codigo del pedido

    public function getCodigo($fechaActual) {
        try {
            $maxcodigo = (int) Pedidos::whereYear('created_at',$fechaActual)
                ->whereMonth('created_at',$fechaActual)
                ->max(DB::raw('SUBSTRING(codigo, 5, 5)'))  + 1;
            return date_format($fechaActual,'m').date_format($fechaActual,'y').str_pad( $maxcodigo, 5, "0", STR_PAD_LEFT);
        } catch (Exception $ex) {
            Log::error($ex);
            return null;
        }
    }
}

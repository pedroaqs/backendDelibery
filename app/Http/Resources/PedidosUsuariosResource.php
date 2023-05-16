<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PedidosUsuariosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        Log::info($this->detallepedido);
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'total' => $this->total,
            // 'cliente' => $this->nombre_cliente(),
            // 'telefono_cliente' => $this->telefono_cliente(),
            'repartidor_id' => $this->repartidor_id,
            'tienda' => $this->tienda,
            'detalle_pedido' => GlobalResource::collection($this->detalle),
           'estados' => PedidoEstadoResource::collection($this->estados),
            // 'asignable' => $this->estadoactual->estado_id < 2 ? true : false
        ];
    }
}

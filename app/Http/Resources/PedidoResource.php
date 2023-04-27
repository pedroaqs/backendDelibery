<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PedidoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        Log::info($this->estadoactual->estado_id);
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'total' => $this->total,
            'cliente' => $this->nombre_cliente(),
            'telefono_cliente' => $this->telefono_cliente(),
            'repartidor_id' => $this->repartidor_id,
            'tienda' => $this->tienda,
            'detalle_pedido' => GlobalResource::collection($this->detallepedido),
            'estados' => PedidoEstadoResource::collection($this->estados),
            'asignable' => $this->estadoactual->estado_id < 2 ? true : false
        ];
    }

    public function nombre_cliente() {
        if ($this->cliente == null){
            return $this->cliente_sin_registro;
        }

        return  $this->cliente->persona !== null ? $this->cliente->persona->nombre.' '.$this->cliente->persona->paterno.' '.$this->cliente->persona->materno : '';
    }

    public function telefono_cliente() {
        if ($this->cliente == null){
            return $this->telefono_cliente_sin_registro;
        }

        return  $this->cliente->persona !== null ? $this->cliente->persona->telefono : '';
    }
}

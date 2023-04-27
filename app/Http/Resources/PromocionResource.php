<?php

namespace App\Http\Resources;

use App\Models\Tienda;
use Illuminate\Http\Resources\Json\JsonResource;

class PromocionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fecha_inicial' => $this->fecha_inicial,
            'fecha_final' => $this->fecha_final,
            'producto_id' => $this->producto_id,
            'tipo_promocion' => $this->porcentaje_descuento !== null ? 1: 2,
            'porcentaje_descuento' => $this->porcentaje_descuento,
            'precio_promocion' => $this->precio_promocion,
            'tienda' =>  $this->tienda->ruc . ' - ' . $this->tienda->razonsocial
        ];
    }
}

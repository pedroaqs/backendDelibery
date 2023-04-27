<?php

namespace App\Http\Resources\Opciones;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
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
            'label' => 'S/.' . $this->precio . ' - ' . $this->nombre,
            'precio' => (float) $this->precio,
            'nombre' => $this->nombre
        ];
    }
}

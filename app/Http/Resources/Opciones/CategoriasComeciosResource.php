<?php

namespace App\Http\Resources\Opciones;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriasComeciosResource extends JsonResource
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
            'value' => $this->id,
            'label' => $this->nombre
        ];
    }
}

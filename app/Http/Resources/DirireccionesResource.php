<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DirireccionesResource extends JsonResource
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
            "id" => $this->id,
            "direccion" => $this->direccion,
            "referencia" => $this->referencia,
            "latitud" => $this->latitud,
            "longitud" => $this->longitud,
            "user_id" => $this->user_id
        ];
    }
}

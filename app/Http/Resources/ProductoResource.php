<?php

namespace App\Http\Resources;

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
        // return parent::toArray($request);

        return [
            "id" => $this->id,
            "nombre" => $this->nombre,
            "precio" => $this->precio,
            "descripcion" => $this->descripcion,
            "tienda_id" => $this->tienda_id,
            "categoria_id" => $this->categoria_id,
            "categoria" => $this->categoria ? $this->categoria->nombre : '',
            "foto" => new FotoResource($this->foto),
            "fotos" => FotoResource::collection($this->fotos),
        ];

    }
}

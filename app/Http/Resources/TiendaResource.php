<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TiendaResource extends JsonResource
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
            'categoria_id' => $this->categoria_id,
            'ruc' => $this->ruc,
            'razonsocial'  => $this->razonsocial,
            'logo'  => $this->getLogo(),
            'latitud'  => $this->latitud,
            'longitud'  => $this->longitud,
            'categoria'  => new CategoriasResource($this->categoria)
        ];
    }
}

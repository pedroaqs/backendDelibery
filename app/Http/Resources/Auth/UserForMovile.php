<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserForMovile extends JsonResource
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
            'usuario' => $this->name,
            'existPersona' => $this->persona != null ? true: false,
            'email' => $this->persona != null? $this->persona->correo : '',
            'foto' => $this->persona != null ? $this->persona->url_foto : '',
            'nombres' => $this->persona != null ? $this->persona->nombre : '',
            'nombreCompleto' => $this->persona != null ? $this->persona->nombre . ' ' .$this->persona->paterno . ' ' .$this->persona->materno : '',
        ];
    }
}

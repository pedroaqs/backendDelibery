<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            "nro_documento" => $this->persona != null ? $this->persona->nro_documento: "",
            "paterno" => $this->persona != null ? $this->persona->paterno: "",
            "materno" => $this->persona != null ? $this->persona->materno: "",
            "nombre" => $this->persona != null ? $this->persona->nombre: "",
            "correo" => $this->persona != null ? $this->persona->correo: "",
            "telefono" => $this->persona != null ? $this->persona->telefono: "",
            'rol_id' => $this->roles ? ( $this->roles->first() ? $this->roles->first()->id : '' ) : '',
            'rol_name' => $this->roles ? ( $this->roles->first() ? $this->roles->first()->name : '' ) : '',
        ];
    }
}

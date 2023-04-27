<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ListaPedidosResource extends JsonResource
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
            'codigo' => $this->codigo,
            'estado' => $this->estadoactual->estado->nombre,
            'type' => $this->type(),
            'fecha' => date_format(Carbon::parse($this->fecha),'h:i a')
        ];
    }

    public function type() {
        if ($this->estadoactual->estado_id < 2) {
            return 'info';
        }

        if ($this->estadoactual->estado_id >= 2 && $this->estadoactual->estado_id < 5) {
            return 'primary';
        }

        return 'success';
    }
}

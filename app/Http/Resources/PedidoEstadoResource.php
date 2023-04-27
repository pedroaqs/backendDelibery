<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPSTORM_META\type;

class PedidoEstadoResource extends JsonResource
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
            'hora' => date_format(Carbon::parse($this->hora),'d-m-Y h:i a'),
            'timestamp' => $this->hora,
            'nombre' => $this->estado->nombre,
            'type' => $this->type()
        ];
    }
    public function type() {
        if ($this->estado_id < 2) {
            return 'info';
        }

        if ($this->estado_id >= 2 && $this->estado_id < 5) {
            return 'primary';
        }

        return 'success';
    }
}

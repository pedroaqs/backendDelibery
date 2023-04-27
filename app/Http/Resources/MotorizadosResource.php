<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;

class MotorizadosResource extends JsonResource
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
            'value'=> $this->id,
            'label' =>$this->persona !== null ? $this->persona->nombre.' '.$this->persona->paterno.' '.$this->persona->materno : '',
            'no_disabled' => $this->asignable()
        ];
    }

    public function asignable()
    {
        if($this->ultimopedido == null) {
            return true;
        }

        if($this->ultimopedido->estadoactual->estado_id >= 5){
            Log::info($this->ultimopedido);
            return true;
        }

        return false;
    }
}

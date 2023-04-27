<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportePedidoResource extends JsonResource
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
            "total" => $this->total,
            "fecha" => $this->fecha != null ? date("d/m/Y H:i:s", strtotime($this->fecha)) : '',
            "codigo" => $this->codigo,
            "cliente" => $this->getCliente(),
            "cliente_telefono" => $this->getClienteTelefono(),
            // "estadoactual" => $this->ultimoestado,
            "estado_actual" => $this->getUltimoEstado($this->ultimoestado),
            "estado_actual_hora" => $this->getUltimoEstadoHora($this->ultimoestado)
        ];
    }


    private function getCliente()
    {
        if ($this->cliente != null) {
            return $this->cliente->persona->nombre. ' ' . $this->cliente->persona->paterno . " " . $this->cliente->persona->materno;
        }
        else {
            return $this->cliente_sin_registro;
        }
    }


    private function getClienteTelefono()
    {
        if ($this->cliente != null) {
            return $this->cliente->persona->telefono;
        }
        else {
            return $this->telefono_cliente_sin_registro;
        }
    }



    private function getUltimoEstado($ultimoestado)
    {
        // return $ultimoestado;

        if ($ultimoestado != null) {
            if ($ultimoestado->estado != null) {
                return $ultimoestado->estado->nombre;
            } else {
                return "";
            }
        }
        else {
            return "";
        }
    }


    private function getUltimoEstadoHora($ultimoestado)
    {
        // return $ultimoestado;
        if ($ultimoestado != null) {
            return $ultimoestado->hora != null ? date("d/m/Y H:i:s", strtotime($ultimoestado->hora)) : '';
        }
        else {
            return "";
        }
    }
}

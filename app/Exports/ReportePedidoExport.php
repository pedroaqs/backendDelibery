<?php

namespace App\Exports;

use App\Models\Pedido;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportePedidoExport implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize
{

    use Exportable;

    private $current_row = 1;

    protected $fechaInicio;
    protected $fechaFin;

    public function __construct(
        string $fechaInicio,
        string $fechaFin
    )
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }


    /**
     * @return Builder
     */
    public function query()
    {
        return Pedido::with(["cliente.persona", "detalle.producto", "ultimoestado.estado"])
        ->whereDate("fecha", ">=", $this->fechaInicio)
        ->whereDate("fecha", "<=",  $this->fechaFin);
    }



    /**
     * @return string
     */
    public function title(): string
    {
        return 'Pedidos';
    }



    /**
     * @return string for Headings
     */
    public function headings(): array
    {
        return [
            'NRO',
            "CODIGO",
            "FECHA Y HORA",
            "CLIENTE",
            "TELEFONO",
            "PEDIDO ESTADO",
            "PEDIDO FECHA Y HORA",
            "TOTAL"
        ];
    }



    /**
     * @return array
     */
    public function map($row): array
    {
        return [
            $this->current_row++,
            $row->codigo,
            $row->fecha != null ? date("d/m/Y H:i:s", strtotime($row->fecha)) : '',
            $this->getCliente($row),
            $this->getClienteTelefono($row),
            $this->getUltimoEstado($row->ultimoestado),
            $this->getUltimoEstadoHora($row->ultimoestado),
            number_format($row->total, 2),
        ];
    }


    private function getCliente($row)
    {
        if ($row->cliente != null) {
            return $row->cliente->persona->nombre. ' ' . $row->cliente->persona->paterno . " " . $row->cliente->persona->materno;
        }
        else {
            return $row->cliente_sin_registro;
        }
    }


    private function getClienteTelefono($row)
    {
        if ($row->cliente != null) {
            return $row->cliente->persona->telefono;
        }
        else {
            return $row->telefono_cliente_sin_registro;
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

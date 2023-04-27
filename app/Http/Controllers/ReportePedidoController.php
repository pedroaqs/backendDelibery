<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Pedido;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\ReportePedidoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ReportePedidoResource;

class ReportePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', 20);
        $page = Arr::get($searchParams, 'page', 1);
        $fechaInicio = Arr::get($searchParams, 'fechaInicio', date("Y-m-d"));
        $fechaFin = Arr::get($searchParams, 'fechaFin', date("Y-m-d"));

        $pedidos = Pedido::with(["cliente.persona", "detalle.producto", "ultimoestado.estado"])
        ->whereDate("fecha", ">=", $fechaInicio)
        ->whereDate("fecha", "<=",  $fechaFin)
        ->paginate($limit, ['*'], '', $page);

        return ReportePedidoResource::collection($pedidos);
    }



    public function exportar(Request $request)
    {
        $searchParams = $request->all();
        $fechaInicio = Arr::get($searchParams, 'fechaInicio', date("yyyy-mm-dd"));
        $fechaFin = Arr::get($searchParams, 'fechaFin', date("yyyy-mm-dd"));

        $filename = 'exports/reportes/Pedidos-' . (new DateTime())->getTimestamp(). '.xlsx';
        Excel::store(
            new ReportePedidoExport($fechaInicio, $fechaFin),
            $filename,
            'public'
        );
        sleep(2);

        return route("home")."/descargararchivo?rutaarchivo=".'public/'.$filename;
    }


}

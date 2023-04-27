<?php

use App\Events\Test;
use App\Models\Pedidos;
use App\Events\PedidosReload;
use App\Events\PedidoEntregado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DescargararchivoController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})
->name("home");

Route::get('login', function (Request $request) {
    return response('',Response::HTTP_UNAUTHORIZED);
})->name("login");


// Descargar archivo
Route::get('descargararchivo', [DescargararchivoController::class, "index"]);

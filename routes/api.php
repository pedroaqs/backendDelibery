<?php

use App\Events\PedidoEntregado;
use App\Events\PedidoEntregago;
use App\Events\PedidosReload;
use App\Events\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Auth\UserResource;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UsuarioController;
use App\Http\Controllers\CostoDistanciaController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\FotoproductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\ReportePedidoController;
use App\Http\Resources\Auth\UserForMovile;
use Illuminate\Http\Response;
use App\Http\Controllers\PruebaDireccionesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('auth/login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::get('/userformovileapp', function (Request $request) {
        return new UserForMovile($request->user());
    });

    Route::post('/auth/logout', [LogoutController::class, 'logout']);


    // Modulo Auth, permisos, roles y usuarios
    require __DIR__ . '/api/apiauth.php';

    // Categorias
    Route::resource('categorias', CategoriasController::class);
    Route::post('categoriasactualizar', [CategoriasController::class, 'update']);
    // lista de categorias de comercios
    Route::get('categoriasopcionescomercios', [CategoriasController::class, 'listaCategoriasComercios']);
    // lista de categorias de produtos
    Route::get('categoriasopcionesproductos', [CategoriasController::class, 'listaCategoriasProductos']);

    // Tiendas
    Route::resource('tiendas',TiendaController::class);
    Route::post('tiendas/listaopciones',[TiendaController::class,'listaopciones']);

     // Productos
     Route::resource('productos', ProductosController::class);

     // Productos
     Route::resource('fotosproducto', FotoproductoController::class);
    // Productos
    Route::post('productos/listaopciones',[ProductoController::class,'listaopciones']);

    // Pedidos
    Route::resource('pedidos',PedidosController::class);
    // Asginar Pedido
    Route::post('pedidos/asignarpedido',[PedidosController::class,'asignarPedido']);
    Route::post('pedidos/cambiarEstadoPedido',[PedidosController::class,'cambiarEstadoPedido']);

    // Costos por distancia
    Route::resource('costosdistancia',CostoDistanciaController::class);
    // Promociones
    Route::resource('promociones',PromocionController::class);

    // Lista de usuarios con rol motorizado
    Route::get('motorizados',[UsuarioController::class,'motorizados']);

    Route::get('reportes/pedidos', [ReportePedidoController::class, 'index']);
    Route::get('reportes/exportar/pedidos', [ReportePedidoController::class, 'exportar']);

});

// Web soquet
Route::post('pedidosreload', function () {
    // event(new App\Events\Test());
    // broadcast(new PedidosReload())->toOthers();
    return "Event has been sent!";
    Route::resource('tiendas', TiendaController::class);

    // Productos
    Route::resource('productos', ProductosController::class);

    // Productos
    Route::resource('fotosproducto', FotoproductoController::class);

});

//Route::get('direcciones', [PruebaDireccionesController::class, 'prueba']);

Route::post('direcciones/{user_id}', [PruebaDireccionesController::class, 'prueba']);
Route::get('/users/{user_id}/direcciones', [PruebaDireccionesController::class, 'listarDirecciones']);
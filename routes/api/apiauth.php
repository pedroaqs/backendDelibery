<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RolController;
use App\Http\Controllers\Auth\PermisoController;
use App\Http\Controllers\Auth\UsuarioController;

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

Route::get('auth/roles', [RolController::class, 'index']);
Route::post('auth/roles', [RolController::class, 'store']);
Route::get('auth/roles/{id}', [RolController::class, 'show']);
Route::put('auth/roles/{id}', [RolController::class, 'update']);
Route::delete('auth/roles/{id}', [RolController::class, 'destroy']);

Route::get('roles/{id}/permissions', [RolController::class, 'permisos']);
Route::post('roles/{id}/permissions', [RolController::class, 'updatePermisos']);

Route::get('auth/permisos', [PermisoController::class, 'index']);
Route::post('auth/permisos', [PermisoController::class, 'store']);
Route::get('auth/permisos/{id}', [PermisoController::class, 'show']);
Route::put('auth/permisos/{id}', [PermisoController::class, 'update']);
Route::delete('auth/permisos/{id}', [PermisoController::class, 'destroy']);

Route::get('auth/usuarios/roles', [RolController::class, 'listarTodas']);

Route::get('auth/usuarios', [UsuarioController::class, 'index']);
Route::post('auth/usuarios', [UsuarioController::class, 'store']);
Route::get('auth/usuarios/{id}', [UsuarioController::class, 'show']);
Route::put('auth/usuarios/{id}', [UsuarioController::class, 'update']);
Route::delete('auth/usuarios/{id}', [UsuarioController::class, 'destroy']);
Route::post('auth/cambiarclaveapoderados', [UsuarioController::class, 'cambiarclaveapoderados']);

Route::post('actualizar/miclave', [UsuarioController::class, 'actualizarmiclave']);
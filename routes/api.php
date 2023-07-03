<?php

use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\SeguidorController;
use App\Http\Controllers\UserController;
use App\Models\Comentario;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ["auth:sanctum"]], function (){
    Route::controller(UserController::class)->group(function () {
        Route::get('auth/user-profile', 'userProfile');
        Route::post('auth/logout',  'logout');
        Route::get('user/{id}',  'show');
    });
    Route::apiResource('favorito',FavoritoController::class)->only('store','destroy','index');
    Route::apiResource('interaccion',SeguidorController::class)->only('store','destroy','index');
    Route::apiResource('comentario',ComentarioController::class)->only('store','show');
    Route::apiResource('calificacion',CalificacionController::class)->only('store','show');
});
Route::post('auth/login', [UserController::class, 'login']);
Route::post('auth/register', [UserController::class, 'register']);

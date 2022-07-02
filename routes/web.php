<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\ApiAuthMiddleware;
//use App\Helpers\Facades\Btn;
//use App\Helpers\Facades\JwtAuth;

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
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\RubroController;
use App\Http\Controllers\ServicioController;


Route::get('/', function () {
    //$oco= new btn;
    //echo btn::a('ss');

    return view('welcome');

});


//Route::post('/api/usuario/prueba',[UsuarioController::class,'prueba']);

//Rutas de controlador Usuario
Route::post('/api/usuario/register',[UsuarioController::class,'register']);
Route::post('/api/usuario/login',[UsuarioController::class,'login']);
Route::put('/api/usuario/update',[UsuarioController::class,'update']);
Route::post('/api/usuario/upload',[UsuarioController::class,'upload'])->middleware(ApiAuthMiddleware::class);
Route::get('/api/usuario/avatar/{filename}',[UsuarioController::class,'getImage']);
Route::get('/api/usuario/detail/{id}',[UsuarioController::class,'detail']);

//Rutas de controlador Cliente
Route::get('/api/cliente/get-clientes',[ClienteController::class,'getClientes']);


//Rutas de controlador Rubro
Route::get('/api/rubro/get-rubros',[RubroController::class,'getRubros']);


//Rutas de controlador Servicios
Route::get('/api/servicio/get-servicios',[ServicioController::class,'getServicios']);

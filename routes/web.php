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
use App\Http\Controllers\PuestoController;


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
Route::get('/api/cliente/get-cliente/{id}',[ClienteController::class,'getCliente']);
Route::get('/api/cliente/getPuestosCliente/{id}',[ClienteController::class,'getPuestosCliente']);
Route::get('/api/cliente/get-departamentos',[ClienteController::class,'getDepartamentos']);
Route::get('/api/cliente/get-provincias/{id}',[ClienteController::class,'getProvincias']);
Route::get('/api/cliente/get-distritos/{id}',[ClienteController::class,'getDistritos']);
Route::post('/api/cliente/add',[ClienteController::class,'addCliente']);
Route::post('/api/cliente/registrarPuestoToCliente',[ClienteController::class,'registrarPuestoToCliente']);
Route::post('/api/cliente/edit',[ClienteController::class,'editCliente']);
Route::delete('/api/cliente/eliminar/{id}',[ClienteController::class,'deleteCliente']);

//Rutas de controlador Rubro
//Rutas de controlador Servicios
Route::get('/api/rubro/listar',[RubroController::class,'listar']);
Route::post('/api/rubro/add',[RubroController::class,'registrar']);
Route::post('/api/rubro/edit',[RubroController::class,'editar']);
Route::delete('/api/rubro/eliminar/{id}',[RubroController::class,'eliminar']);
Route::get('/api/rubro/listarById/{id}',[RubroController::class,'listarById']);


//Rutas de controlador Servicios
Route::get('/api/servicio/listar',[ServicioController::class,'listar']);
Route::get('/api/servicio/listarServiciosPuesto/{id}',[ServicioController::class,'listarServiciosPuesto']);
Route::get('/api/servicio/listarServiciosNoTienePuesto/{id}',[ServicioController::class,'listarServiciosNoTienePuesto']);
Route::post('/api/servicio/add',[ServicioController::class,'registrar']);
Route::post('/api/servicio/addServicioToPuesto',[ServicioController::class,'addServicioToPuesto']);
Route::post('/api/servicio/edit',[ServicioController::class,'editar']);
Route::delete('/api/servicio/eliminar',[ServicioController::class,'eliminar']);
Route::get('/api/cliente/listarById/{id}',[ServicioController::class,'listarById']);
Route::get('/api/servicio/get-afiliados-servicio/{id}',[ServicioController::class,'getPuestosClientesxServicio']);
Route::get('/api/servicio/get-deudasxservicioxcliente/{id}',[ServicioController::class,'getDeudasClientesxServicio']);
Route::post('/api/servicio/pagarDeudas',[ServicioController::class,'pagarDeudas']);
Route::get('/api/servicio/getPagosServicios',[ServicioController::class,'getPagosServicios']);
Route::get('/api/servicio/generaComprobante/{id}',[ServicioController::class,'generaComprobante']);


//Rutas de controlador Puesto
Route::get('/api/puesto/listar',[PuestoController::class,'listar']);
Route::post('/api/puesto/add',[PuestoController::class,'registrar']);
Route::post('/api/puesto/edit',[PuestoController::class,'editar']);
Route::delete('/api/puesto/eliminar/{id}',[PuestoController::class,'eliminar']);
Route::get('/api/puesto/listarById/{id}',[PuestoController::class,'listarById']);
//Rutas de controlador Puesto vs Servicios
Route::get('/api/puesto/listarServicios',[PuestoController::class,'listarServicios']);
Route::post('/api/puesto/registrar_servicio',[PuestoController::class,'registrar_servicio']);
Route::delete('/api/puesto/eliminarServicio/{id}',[PuestoController::class,'eliminarServicio']);


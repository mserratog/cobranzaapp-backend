<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Servicio;
use Illuminate\Support\Facades\Validator;

class ServicioController extends Controller
{
    //
    public function getServicios(Request $request){

        $servicios = Servicio::all();

        if(is_object($servicios)){

            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'servicios' => $servicios
            );

        }else{
            $data = array(
                'status'  => 'error',
                'code'    => 400,
                'message' => 'No existen clientes'
            );

        }
        return response()->json($data,$data['code']);



    }
}

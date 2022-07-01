<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    //
    public function getClientes(Request $request){

        $clientes = Cliente::all();

        if(is_object($clientes)){

            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'clientes' => $clientes
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

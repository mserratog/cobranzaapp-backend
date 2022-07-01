<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Rubro;
use Illuminate\Support\Facades\Validator;

class RubroController extends Controller
{
    //
    public function getRubros(Request $request){

        $rubros = Rubro::all();

        if(is_object($rubros)){

            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'rubros' => $rubros
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

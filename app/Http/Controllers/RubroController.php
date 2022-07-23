<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Rubro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RubroController extends Controller
{
    //
    public function listar(Request $request){

        $rubros = Rubro::all();

        if(is_object($rubros)){

            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'registros' => $rubros
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

    public function registrar(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_inserted = DB::table('rubro')->insertGetId(
            ['descripcion' => $params->descripcion,
             'estado' => 1
              ]
        );

        if($id_inserted){
            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Registro exitoso'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function editar(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_inserted = DB::table('rubro')->where('idRubro', $params->idRubro)->update(
            ['descripcion' => $params->descripcion
              ]
        );

        if($id_inserted){
            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Registro actualizado'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function eliminar($id){

        $resultado = DB::select('delete from rubro where idRubro=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'servicio' => $resultado
        );

        return response()->json($data,$data['code']);
    }

    public function listarById($id){

        $resultado = DB::select('Select * from rubro where idRubro=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'registro' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }
}

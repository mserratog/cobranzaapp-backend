<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
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


    public function addCliente(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_cliente_inserted = DB::table('cliente')->insertGetId(
            ['nombres' => $params->nombres,
             'apellidos' => $params->apellidos,
              'departamento'=>$params->departamento,
              'provincia'=>$params->provincia,
              'distrito'=>$params->distrito,
              'profesion'=>$params->profesion,
              'actividad'=>$params->actividad,
              'grado'=>$params->grado,
              'estado_civil'=>$params->estado_civil,
              'docume'=>$params->docume,
              'direccion'=>$params->direccion,
              'telefono'=>$params->telefono,
              'tipdoc'=>1,
              'fecreg'=>date("Y-m-d"),
              'horareg'=>date("H:i:s", time()),
              'estado'=>1,
              'usureg'=>'SEGOMI',
              'nrofolio'=>2312
              ]
        );

        if($id_cliente_inserted){
            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Cliente registrado'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function registrarPuestoToCliente(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_cliente_inserted = DB::table('puesto')->insertGetId(
            ['idCliente' => $params->idCliente,
             'idRubro' => $params->idRubro,
              'nombrePuesto'=>$params->nombrePuesto,
              'estado'=>1
              ]
        );

        if($id_cliente_inserted){
            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Cliente registrado'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function editCliente(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_cliente_inserted = DB::table('cliente')->where('idCliente', $params->idCliente)->update(
            ['nombres' => $params->nombres,
             'apellidos' => $params->apellidos,
              'departamento'=>$params->departamento,
              'provincia'=>$params->provincia,
              'distrito'=>$params->distrito,
              'profesion'=>$params->profesion,
              'actividad'=>$params->actividad,
              'grado'=>$params->grado,
              'estado_civil'=>$params->estado_civil,
              'docume'=>$params->docume,
              'direccion'=>$params->direccion,
              'telefono'=>$params->telefono
              ]
        );

        if($id_cliente_inserted){
            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Cliente actualizado'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function getCliente($id){

        $resultado = DB::select('Select * from cliente where idCliente=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'cliente' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }


    public function getPuestosCliente($id){

        $resultado = DB::select('Select idPuesto, nombrePuesto, descripcion from puesto a inner join rubro b on a.idRubro=b.idRubro where idCliente=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'cliente' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }

    public function deleteCliente($id){

        $resultado = DB::select('delete from cliente where idCliente=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'cliente' => $resultado
        );

        return response()->json($data,$data['code']);
    }

    public function getDepartamentos(Request $request){

        $resultado = DB::select('Select * from departamentos');
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );

        return response()->json($data,$data['code']);
    }

    public function getProvincias($id){

        $resultado = DB::select('Select * from provincia where idDepartamento=?',[$id]);
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );

        return response()->json($data,$data['code']);
    }

    public function getDistritos($id){

        $resultado = DB::select('Select * from distrito where idProvincia=?',[$id]);
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );

        return response()->json($data,$data['code']);
    }
}

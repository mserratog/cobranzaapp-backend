<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Puesto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PuestoController extends Controller
{
    //
    public function listar(Request $request){

        $resultado = DB::select("select  a.idPuesto, a.nombrePuesto, b.idCliente,CONCAT(b.nombres,' ',b.apellidos) as nombre,
        c.idRubro,c.descripcion
        from puesto a inner join cliente b on a.idCliente = b.idCliente
        inner join rubro c on a.idRubro=c.idRubro
        where a.estado = 1");

        if($resultado){
            $data = array(
                'status'  => 'success',
                'code'    => 200,
                'registros' => $resultado
            );
        }

        return response()->json($data,$data['code']);

    }

    public function registrar(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_inserted = DB::table('puesto')->insertGetId(
            ['idCliente' => $params->idCliente,
             'idRubro' => $params->idRubro,
             'nombrePuesto' => $params->nombrePuesto,
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

        $id_inserted = DB::table('puesto')->where('idPuesto', $params->idPuesto)->update(
            ['idCliente' => $params->idCliente,
            'idRubro' => $params->idRubro,
            'nombrePuesto' => $params->nombrePuesto
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

        $resultado = DB::select('delete from puesto where idPuesto=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'servicio' => $resultado
        );

        return response()->json($data,$data['code']);
    }

    public function listarById($id){

        $resultado = DB::select('Select * from puesto where idPuesto=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'registro' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }

    public function listarServicios($id){

        $resultado = DB::select('SELECT a.idPuestoServicio,a.idServicio,b.descripcion,
        a.idPuesto,a.tarifaItem FROM detalle_puesto_servicio a inner join
        servicio b on a.idServicio=b.idServicio where idPuesto=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'registro' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }

    public function registrar_servicio(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_inserted = DB::table('detalle_puesto_servicio')->insertGetId(
            ['idServicio' => $params->idCliente,
             'idPuesto' => $params->idRubro,
             'tarifaItem' => $params->nombrePuesto
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

    public function eliminarServicio($id){

        $resultado = DB::select('delete from detalle_puesto_servicio where idPuestoServicio=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'servicio' => $resultado
        );

        return response()->json($data,$data['code']);
    }
}

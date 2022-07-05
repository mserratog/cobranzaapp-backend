<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Servicio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    public function getPuestosClientesxServicio(Request $request){
        $resultado = DB::select('Select a.idServicio,b.idPuestoServicio, c.nombrePuesto,cli.apellidos,cli.nombres,cli.docume from servicio a inner join detalle_puesto_servicio b on a.idServicio=b.idServicio inner join puesto c on b.idPuesto=c.idPuesto inner join cliente cli on c.idCliente=cli.idCliente where a.idServicio=?',[1]);
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );
        return response()->json($data,$data['code']);
    }

    public function getDeudasClientesxServicio(Request $request){
        $resultado = DB::select('select * from planilladeuda a where idPuestoServicio=?',[1]);
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );
        return response()->json($data,$data['code']);
    }

}

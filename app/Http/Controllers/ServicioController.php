<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Servicio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
define('EURO',chr(128));



class ServicioController extends Controller
{
    //
    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf('P','mm',array(80,150));

    }

    public function listar(Request $request){

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
                'message' => 'No existen servicios'
            );

        }
        return response()->json($data,$data['code']);

    }

    public function listarServiciosPuesto($id){

        $resultado = DB::select('select b.idPuestoServicio,a.idServicio,a.descripcion
        from servicio a inner join detalle_puesto_servicio b on a.idServicio=b.idServicio
        where b.idPuesto=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'registros' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }

    public function listarServiciosNoTienePuesto($id){

        $resultado = DB::select('select * from servicio a where
        not exists(select * from detalle_puesto_servicio x where a.idServicio=x.idServicio and x.idPuesto=?)',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'registros' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }

    public function registrar(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_inserted = DB::table('servicio')->insertGetId(
            ['descripcion' => $params->descripcion,
             'tarifa' => $params->tarifa,
              'frecuencia'=>$params->frecuencia,
              'fecreg'=>date("Y-m-d"),
              'horareg'=>date("H:i:s", time()),
              'estado'=>1,
              'usureg'=>'SEGOMI'
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

    public function addServicioToPuesto(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array

        $id_inserted = DB::table('detalle_puesto_servicio')->insertGetId(
            ['idServicio' => $params->idServicio,
             'idPuesto' => $params->idPuesto,
              'tarifaItem'=>0
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

        $id_inserted = DB::table('servicio')->where('idServicio', $params->idServicio)->update(
            ['descripcion' => $params->descripcion,
            'tarifa' => $params->tarifa,
             'frecuencia'=>$params->frecuencia
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

        $resultado = DB::select('delete from servicio where idServicio=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'servicio' => $resultado
        );

        return response()->json($data,$data['code']);
    }

    public function listarById($id){

        $resultado = DB::select('Select * from servicio where idServicio=?',[$id]);

        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'cliente' => $resultado
        );

        return response()->json($resultado,$data['code']);
    }

    public function getPagosServicios(Request $request){
        $resultado = DB::select('select * from pagodeuda_sp order by idPagoDeuda_SP desc');
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );
        return response()->json($data,$data['code']);
    }


    public function generaComprobante($id_pago){
        $this->fpdf->AddPage();

        /*
        $this->fpdf->SetFont('Arial', 'B', 15);
        $this->fpdf->AddPage("L", ['100', '100']);
        $this->fpdf->Text(10, 10, "ACOMIPOMALER");

        //$this->fpdf->Output('F','boleta2.pdf');
        $_pdf= $this->fpdf->Output();
        */


        //obtener datos de tabla cabecera comprobante
        $comprobante_datos = DB::table('comprobante')->where('idPagoDeuda_SP', $id_pago)->first();
        $_id_comprobante= $comprobante_datos->idComprobante;
        $_serie_comprobante = $comprobante_datos->serie;
        $_numero_comprobante = $comprobante_datos->numero;
        $_ope_grav_comprobante = $comprobante_datos->opGravada;
        $_igv_comprobante=$comprobante_datos->igv;
        $_monto_total_comprobante=$comprobante_datos->total;
        $_fecha_emision_comprobante=date("d/m/Y", strtotime($comprobante_datos->fechaEmision));
        $_hora_emision_comprobante=$comprobante_datos->horaEmision;

        //obtener datos de tabla detalle comprobante
        $detallecomprobante_datos = DB::table('detallecomprobante')->where('idComprobante', $_id_comprobante)->first();
        $_glosa_detalle_comprobante=$detallecomprobante_datos->glosa;
        $_tarifad_detalle_comprobante=$detallecomprobante_datos->tarifa_detalle;
        $_cantidad_detalle_comprobante=$detallecomprobante_datos->cantidad;
        $_subtotal_detalle_comprobante=$detallecomprobante_datos->importe;
        // CABECERA IMPRESION
        $this->fpdf->SetFont('Helvetica','',12);
        $this->fpdf->Cell(60,4,'ACOMIPOMALER.com',0,1,'C');
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(60,4,'Av. Sullana Norte S/N',0,1,'C');
        $this->fpdf->Cell(60,4,'INT S/N INT EX TERMINAL, 1',0,1,'C');
        $this->fpdf->Cell(60,4,'PESQUERO PIURA - IURA - PIURA',0,1,'C');
        $this->fpdf->Cell(60,4,'TELF. (073)-630521',0,1,'C');
        $this->fpdf->Cell(60,4,'RUC 20483988801',0,1,'C');

        // DATOS FACTURA
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(60,4,'Boleta Elect.:'.$_serie_comprobante.'-'.$_numero_comprobante,0,1,'');
        $this->fpdf->Cell(60,4,'Fecha: '.$_fecha_emision_comprobante,0,1,'');
        $this->fpdf->Cell(60,4,'Metodo de pago: Efectivo',0,1,'');

        // COLUMNAS
$this->fpdf->SetFont('Helvetica', 'B', 7);
$this->fpdf->Cell(30, 10, 'Detalle', 0);
$this->fpdf->Cell(5, 10, 'Ud',0,0,'R');
$this->fpdf->Cell(10, 10, 'Monto',0,0,'R');
$this->fpdf->Cell(15, 10, 'Total',0,0,'R');
$this->fpdf->Ln(8);
$this->fpdf->Cell(60,0,'','T');
$this->fpdf->Ln(0);

// Detalle Boleta
$this->fpdf->SetFont('Helvetica', '', 7);
$this->fpdf->MultiCell(30,4,'Aporte Diario ',0,'L');
$this->fpdf->Cell(35, -5, $_cantidad_detalle_comprobante,0,0,'R');
$this->fpdf->Cell(10, -5, $_tarifad_detalle_comprobante,0,0,'R');
$this->fpdf->Cell(15, -5, $_subtotal_detalle_comprobante,0,0,'R');
$this->fpdf->Ln(1);
$this->fpdf->MultiCell(30,4,$_glosa_detalle_comprobante,0,'L');

/*
$this->fpdf->MultiCell(30,4,'Malla naranjas 3Kg',0,'L');
$this->fpdf->Cell(35, -5, '1',0,0,'R');
$this->fpdf->Cell(10, -5, number_format(round(1.25,2), 2, ',', ' ').EURO,0,0,'R');
$this->fpdf->Cell(15, -5, number_format(round(1.25,2), 2, ',', ' ').EURO,0,0,'R');
$this->fpdf->Ln(3);
$this->fpdf->MultiCell(30,4,'Uvas',0,'L');
$this->fpdf->Cell(35, -5, '5',0,0,'R');
$this->fpdf->Cell(10, -5, number_format(round(1,2), 2, ',', ' ').EURO,0,0,'R');
$this->fpdf->Cell(15, -5, number_format(round(1*5,2), 2, ',', ' ').EURO,0,0,'R');
$this->fpdf->Ln(3);
*/
// SUMATORIO DE LOS PRODUCTOS Y EL IVA
$this->fpdf->Ln(10);
$this->fpdf->Cell(60,0,'','T');
$this->fpdf->Ln(2);
$this->fpdf->Cell(25, 10, 'OP. GRAVADA   S/.', 0);
$this->fpdf->Cell(20, 10, '', 0);
$this->fpdf->Cell(15, 10, $_ope_grav_comprobante,0,0,'R');
$this->fpdf->Ln(3);
$this->fpdf->Cell(25, 10, 'IGV : S/.', 0);
$this->fpdf->Cell(20, 10, '', 0);
$this->fpdf->Cell(15, 10, $_igv_comprobante,0,0,'R');
$this->fpdf->Ln(3);
$this->fpdf->Cell(25, 10, 'T O T A L : S/.', 0);
$this->fpdf->Cell(20, 10, '', 0);
$this->fpdf->Cell(15, 10, $_monto_total_comprobante,0,0,'R');

// PIE DE PAGINA
$this->fpdf->Ln(10);
$this->fpdf->Cell(60,0,'EL PERIODO DE DEVOLUCIONES',0,1,'C');
$this->fpdf->Ln(3);
$this->fpdf->Cell(60,0,'CADUCA EL DIA  31/12/2022',0,1,'C');

        $_pdf= $this->fpdf->Output();
        return response()->json($_pdf,200);
    }

    public function getPuestosClientesxServicio($id_servicio){
        $resultado = DB::select('Select a.idServicio,b.idPuestoServicio, c.nombrePuesto,cli.apellidos,cli.nombres from servicio a inner join detalle_puesto_servicio b on a.idServicio=b.idServicio inner join puesto c on b.idPuesto=c.idPuesto inner join cliente cli on c.idCliente=cli.idCliente where a.idServicio=?',[$id_servicio]);
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );
        return response()->json($data,$data['code']);
    }

    public function getDeudasClientesxServicio($id_planilla_deuda){
        $resultado = DB::select('select * from planilladeuda a where estado<3 and idPuestoServicio=?',[$id_planilla_deuda]);
        $data = array(
            'status'  => 'success',
            'code'    => 200,
            'result' => $resultado
        );
        return response()->json($resultado,$data['code']);
    }

    public function pagarDeudas(Request $request){
        $json = $request->input('json',null);
        $params = json_decode($json);//objeto
        $params_array =json_decode($json,true);//array
        $montoTotal=0;
        //echo json_encode($params_array);
        $nro_cuotas_pagar=0;

        foreach ($params as $deuda) {
            $id_planilla= $deuda->idPlanillaDeuda;
            $deuda_monto= $deuda->monto;
            $affected = DB::update(
                'update planilladeuda set estado = 3, montoPagado=?,saldo=? where idPlanillaDeuda = ? ',
                [3,0,$id_planilla]
            );
            $montoTotal=$montoTotal+$deuda_monto;
            $nro_cuotas_pagar++;
        }

        $ope_grabada=$montoTotal/1.18;
        $_igv=$montoTotal-$ope_grabada;
        /*
        $insert_pago = DB::insert(
            'insert into pagodeuda_sp(monto, fecreg,horareg, usuario,estado) values(?,?,?,?,?) ',
            [$montoTotal,date("Y-m-d"),date("H:i:s", time()),'SEGOMI',1]
        );
        */
        $id = DB::table('pagodeuda_sp')->insertGetId(
            ['monto' => $montoTotal, 'fecreg' => date("Y-m-d"), 'horareg'=>date("H:i:s", time()),'usuario'=>'SEGOMI','estado'=>1]
        );

        foreach ($params as $deuda2) {
            $id_planilla2= $deuda2->idPlanillaDeuda;

            $affected2 = DB::update(
                'update planilladeuda set idPagoDeuda=? where idPlanillaDeuda = ? ',
                [$id,$id_planilla2]
            );

        }

        //obtener serie,correlativo de tipo boleta
        $serie = DB::table('serie')->where('tipo', 3)->first();
        $_numero = $serie->correl +1;

        $id_comprobante_gen = DB::table('comprobante')->insertGetId(
            ['tipo' => 3, 'serie' => 'B003', 'numero'=>$_numero,'fechaemision'=>date("Y-m-d"),'horaEmision'=>date("H:i:s", time()),'glosa'=>'Aporte diario','opGravada'=>$ope_grabada,'opExoneradas'=>0, 'igv'=>$_igv,'total'=>$montoTotal,'usureg'=>'SEGOMI','estado'=>1,'idPagoDeuda_SP'=>$id ]
        );
        $glosa_detcomprobante='';

        $planilla = DB::table('planilladeuda')->where('idPagoDeuda', $id)
        ->orderBy('mes', 'asc')
        ->orderBy('dia', 'asc')
        ->first();

        $_dia=$planilla->dia;
        $_mes=$planilla->mes;

        if($nro_cuotas_pagar ==1){
            $glosa_detcomprobante='Aporte diario '.$_dia.'/'.$_mes;
        }else{

            $planilla_fin = DB::table('planilladeuda')->where('idPagoDeuda', $id)
        ->orderBy('mes', 'desc')
        ->orderBy('dia', 'desc')
        ->first();
        $_dia_fin=$planilla_fin->dia;
        $_mes_fin=$planilla_fin->mes;
        $glosa_detcomprobante='Aporte diario del '.$_dia.'/'.$_mes.' al '.$_dia_fin.'/'.$_mes_fin;

        }

        $id_comprobante_gen = DB::table('detallecomprobante')->insertGetId(
            ['idComprobante' => $id_comprobante_gen, 'correlativo' => 1, 'importe'=>$montoTotal,'cantidad'=>$nro_cuotas_pagar,'tarifa_detalle'=>2,'infoAdicional'=>'Informacion ad','glosa'=>$glosa_detcomprobante,'estado'=>1,'fechaing'=>date("Y-m-d"), 'horaing'=>date("H:i:s", time())]
        );


        $data = array(
            'status'  => 'success',
            'code'    => 200
        );
        return response()->json($data,$data['code']);


    }

}

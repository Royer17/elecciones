<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\VentaFormRequest;
use sisVentas\Venta;
use sisVentas\DetalleVenta;
use DB;
use Fpdf;
use Excel;
use Date;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request)
        {
           $query=trim($request->get('searchText'));
           $ventas=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
            ->where('v.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
            ->paginate(7);
            return view('ventas.venta.index',["ventas"=>$ventas,"searchText"=>$query]);

        }
    }
    public function create()
    {
        $personas=DB::table('persona')->where('tipo_persona','=','Cliente')->get();
        //dd($personas);
    	$articulos = DB::table('articulo as art')
    		->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo','art.stock',DB::raw('avg(di.precio_venta) as precio_promedio'), DB::raw('avg(di.precio_venta2) as precio_promedio2'), DB::raw('avg(di.precio_venta3) as precio_promedio3'), DB::raw('avg(di.precio_venta4) as precio_promedio4'))
            ->where('art.estado','=','Activo')
            ->where('art.stock','>','0')
            ->groupBy('articulo','art.idarticulo','art.stock')
            ->get();

        //info(json_encode($articulos));

        return view("ventas.venta.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

     public function store (VentaFormRequest $request)
    {
    	try{
        	DB::beginTransaction();
        	$venta=new Venta;
	        $venta->idcliente=$request->get('idcliente');
	        $venta->tipo_comprobante=$request->get('tipo_comprobante');
	        $venta->serie_comprobante=$request->get('serie_comprobante');
	        $venta->num_comprobante=$request->get('num_comprobante');
	        $venta->total_venta=$request->get('total_venta');

	        $mytime = Carbon::now('America/Lima');
	        $venta->fecha_hora=$mytime->toDateTimeString();
	        if ($request->get('impuesto')=='1')
            {
                $venta->impuesto='18';
            }
            else
            {
                $venta->impuesto='0';
            }
	        $venta->estado='A';
	        $venta->save();

	        $idarticulo = $request->get('idarticulo');
	        $cantidad = $request->get('cantidad');
	        $descuento = $request->get('descuento');
	        $precio_venta = $request->get('precio_venta');

	        $cont = 0;

	        while($cont < count($idarticulo)){
	            $detalle = new DetalleVenta();
	            $detalle->idventa= $venta->idventa;
	            $detalle->idarticulo= $idarticulo[$cont];
	            $detalle->cantidad= $cantidad[$cont];
	            $detalle->descuento= $descuento[$cont];
	            $detalle->precio_venta= $precio_venta[$cont];
	            $detalle->save();
	            $cont=$cont+1;
	        }

        	DB::commit();

        }catch(\Exception $e)
        {
          	DB::rollback();
        }

        return Redirect::to('ventas/venta');
    }

    public function show($id)
    {
    	$venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
            ->where('v.idventa','=',$id)
            ->first();

        $detalles=DB::table('detalle_venta as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
             ->where('d.idventa','=',$id)
             ->get();
        return view("ventas.venta.show",["venta"=>$venta,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
    	$venta=Venta::findOrFail($id);
        $venta->Estado='C';
        $venta->update();
        return Redirect::to('ventas/venta');
    }

    public function reporteExcel(Request $request)
    {
        $mes = $request->q;

        $headers = ['RUC', 'RAZON SOCIAL', 'COMPROBANTE', 'FECHA', 'PRECIO', 'IGV(18%)', 'TOTAL'];

        $ventas = Venta::with('persona');

        if ($mes) {
            $ventas = $ventas->whereMonth('fecha_hora', '=', date('m'))
            ->whereYear('fecha_hora', '=', date('Y'));
        }

        $ventas = $ventas->orderBy('idventa', 'desc')->get();

        $data = [];

        foreach ($ventas as $key => $venta) {
            $precio = $venta->total_venta;
            $impuesto = 0;
            $total = $precio;
            if ($venta->impuesto > 0) {
                $impuesto = ($venta->total_venta * 18)/100;
                $total = $venta->total_venta + $impuesto;
            }

            $data[] = [
                'ruc' => $venta->persona->num_documento,
                'razon social' => $venta->persona->nombre,
                'comprobante' => "{$venta->tipo_comprobante} {$venta->serie_comprobante}-{$venta->num_comprobante}",
                'fecha' => Carbon::parse($venta->fecha_hora)->format('d/m/Y'),
                'precio' => $precio,
                'igv' => $impuesto,
                'total' => $total
            ];
        }

        $now = Carbon::now('America/Lima');
        $date = $now->format('Y-m-d g:i');
        $month = '';
        if ($mes) {
            $month = Date::now('America/Lima')->format('F');
        }
        $name  = "Excel de ventas {$month} - {$date} Hrs";

        Excel::create($name, function($excel) use ($name, $headers, $data){
            $excel->sheet('Ventas', function($sheet) use ($headers, $data) {
                $sheet->setOrientation('landscape');
                $sheet->cell('A1:G1', function($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->fromArray($headers);
                foreach ($data as $key => $venta) {
                    $sheet->row($key+2, $venta);
                }
            });
        })->export('xls');
    }

    public function reportec($id)
    {
         //Obtengo los datos

        $venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','p.direccion','p.num_documento','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
            ->where('v.idventa','=',$id)
            ->first();

        $detalles=DB::table('detalle_venta as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
             ->where('d.idventa','=',$id)
             ->get();

             // REPORTE VENTA X DETALLE
        $pdf = new Fpdf();
        $pdf::AddPage();
        $pdf::SetFont('Arial','B',10); // TAMAÑO TIPO BOLETA
        //Inicio con el reporte

        $pdf::Text(80, 15, "CORPORACION FARY E.I.R.L.");
        $pdf::SetFont('Arial','B',8);
        $pdf::text(60, 19, "CAL.HIPOLITO UNANUE NRO. 612 CERCADO LIMA - LA VICTORIA");
        $pdf::SetFont('Arial','B',7);
        $pdf::text(85, 23, "Tlfo: ");
        $pdf::text(85, 26, "Email: ");
        $pdf::Image("img/logito.png", 20, 8, 20, 20);
        /* hacia la derecha //* hacia la abajo //* estira a la derecha //* estira hacia abajo /*/

        $pdf::SetFont('Arial','B',10);
        $pdf::SetXY(174,15);
        $pdf::Cell(0,0,utf8_decode($venta->tipo_comprobante));

        $pdf::SetFont('Arial','B',10); //TAMAÑO SERIE
        //Inicio con el reporte
        $pdf::SetXY(180,19);
        $pdf::Cell(0,0,utf8_decode($venta->serie_comprobante."-".$venta->num_comprobante));

/* ******************** DATOS *************************************** */
        $pdf::Ln(15);
        $pdf::SetFont('Arial','B',10);
        $pdf::SetFillColor(224,100,100);
        $pdf::SetTextColor(255);
        $pdf::SetLineWidth(.3);
        $camp_datos = "DATOS";
        $pdf::SetFont('','B');
        $pdf::Cell(190, 7,$camp_datos,0,1,'L',true);


/**************** Señores ****************/
    $pdf::SetFont('Arial','B',11);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_senores = "Señores:";
    $pdf::Cell(40, 7,utf8_decode($camp_senores),0);
    $pdf::SetFont("Times");
    $pdf::Cell(150, 7,utf8_decode($venta->nombre),0);
    $pdf::Ln();

/**************** DNI / RUC ****************/
    $pdf::SetFont('Arial','B',11);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_ruc = "Dni / Ruc:";
    $pdf::Cell(40, 7,utf8_decode($camp_ruc),0);
    $pdf::SetFont("Times");
    $pdf::Cell(150, 7,utf8_decode($venta->num_documento),0);
    $pdf::Ln();

/**************** Dirección ****************/
    $pdf::SetFont('Arial','B',11);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_direccion = "Dirección:";
    $pdf::Cell(40, 7,utf8_decode($camp_direccion),0);
    $pdf::SetFont("Times");
    $pdf::Cell(150, 7,utf8_decode($venta->direccion),0);
    $pdf::Ln();

/**************** Fecha ****************/
    $pdf::SetFont('Arial','B',11);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_fecha = "Fecha:";
    $pdf::Cell(40, 7,utf8_decode($camp_fecha),0);
    $pdf::SetFont("Times");
    $pdf::Cell(150, 7,substr($venta->fecha_hora,0,10),0);
    $pdf::Ln();


    $pdf::Ln();
/**************** CANTIDAD ****************/
    $pdf::SetFont('Arial','B',10);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(255);
    $pdf::SetLineWidth(.3);
    $camp_cantidad = "CANTIDAD";
    $pdf::SetFont('','B');
    $pdf::Cell(20, 7,$camp_cantidad,0,0,'C',true);

    $camp_articulos = "ARTICULO(S)";
    $pdf::SetFont('','B');
    $pdf::Cell(110, 7,$camp_articulos,0,0,'C',true);

    $camp_unit = "PRECIO UNIT";
    $pdf::SetFont('','B');
    $pdf::Cell(30, 7,$camp_unit,0,0,'C',true);

    $camp_precio = "PRECIO TOTAL";
    $pdf::SetFont('','B');
    /*RELLENO */
    $pdf::Cell(30, 7,$camp_precio,0,1,'C',true);
    $total=0;
    /**************** CAMPO RELLENO DETALLES ****************/
    $y=85;
    foreach($detalles as $det){
    $pdf::SetXY(10,$y);
    $pdf::SetFont('Arial','B',10);
    /* $pdf::Ln(3); */
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $pdf::Cell(20, 7,utf8_decode($det->cantidad),0);
    $pdf::SetFont("Times");
    $pdf::Cell(110, 7,utf8_decode($det->articulo),0); /* precios */
    $pdf::Cell(30, 7,$det->precio_venta-$det->descuento,0,'C',true);
    $pdf::Cell(30, 7,sprintf("%0.2F",(($det->precio_venta-$det->descuento)*$det->cantidad)),0,'C',true);
    $total=$total+($det->precio_venta*$det->cantidad);
    $y=$y+7;
    $pdf::Ln();


        }

        $pdf::Ln();
/**************** Campo TOTAL ****************/
    $pdf::SetFont('Arial','B',10);
    $pdf::SetFillColor(255);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_vacio = "";
    $pdf::SetFont('','B');
    $pdf::Cell(20, 7,$camp_vacio,0,0,'C',true);

    $camp_vacio2 = "";
    $pdf::SetFont('','B');
    $pdf::Cell(110, 7,$camp_vacio2,0,0,'C',true);

    $camp_unit = "TOTAL:";
    $pdf::SetFont('','B');
    $pdf::Cell(30, 7,$camp_unit,0,0,'R',true);

    $pdf::SetFont('','B');
    /*RELLENO */
    $pdf::Cell(30, 7,"S/. ".sprintf("%0.2F", $venta->total_venta-($venta->total_venta*$venta->impuesto/($venta->impuesto+100))),0,1,'R',true);

/**************** Campo IGV ****************/
    $pdf::SetFont('Arial','B',10);
    $pdf::SetFillColor(255);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_vacio3 = "";
    $pdf::SetFont('','B');
    $pdf::Cell(20, 7,$camp_vacio3,0,0,'C',true);

    $camp_vacio4 = "";
    $pdf::SetFont('','B');
    $pdf::Cell(110, 7,$camp_vacio4,0,0,'C',true);

    $camp_igv = "IGV (18%):";
    $pdf::SetFont('','B');
    $pdf::Cell(30, 7,$camp_igv,0,0,'R',true);

    $pdf::SetFont('','B');
    /*RELLENO */
    $pdf::Cell(30, 7,"S/. ".sprintf("%0.2F", ($venta->total_venta*$venta->impuesto/($venta->impuesto+100))),0,1,'R',true);

/**************** Campo TOTAL PAGAR ****************/
    $pdf::SetFont('Arial','B',10);
    $pdf::SetFillColor(255);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_vacio5 = "";
    $pdf::SetFont('','B');
    $pdf::Cell(20, 7,$camp_vacio5,0,0,'C',true);

    $camp_vacio6 = "";
    $pdf::SetFont('','B');
    $pdf::Cell(110, 7,$camp_vacio6,0,0,'C',true);

    $camp_total = "TOTAL A PAGAR:";
    $pdf::SetFont('','B');
    $pdf::Cell(30, 7,$camp_total,0,0,'R',true);

    $pdf::SetFont('','B');
    /*RELLENO */
    $pdf::Cell(30, 7,"S/. ".sprintf("%0.2F", $venta->total_venta),0,1,'R',true);


/************* Footer ************************/
    $pdf::SetFont('Arial','B',10);
    $pdf::Cell(190, 30,"Gracias por visitarnos.",0,0,'C');
    $pdf::Ln();


        $pdf::Output();
        exit;
    }
    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
            ->get();

            // REPORTE VENTAS TOTAL
         //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf('L','mm','A4');
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Ventas"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda
         $pdf::SetFont('Arial','B',10);
         //El ancho de las columnas debe de sumar promedio 190
         $pdf::cell(35,8,utf8_decode("Fecha"),1,"","L",true);
         $pdf::cell(80,8,utf8_decode("Cliente"),1,"","L",true);
         $pdf::cell(45,8,utf8_decode("Comprobante"),1,"","L",true);
         $pdf::cell(10,8,utf8_decode("Imp"),1,"","C",true);
         $pdf::cell(25,8,utf8_decode("Total"),1,"","R",true);

         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);

         foreach ($registros as $reg)
         {
            $pdf::cell(35,8,utf8_decode($reg->fecha_hora),1,"","L",true);
            $pdf::cell(80,8,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(45,8,utf8_decode($reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante),1,"","L",true);
            $pdf::cell(10,8,utf8_decode($reg->impuesto),1,"","C",true);
            $pdf::cell(25,8,utf8_decode($reg->total_venta),1,"","R",true);
            $pdf::Ln();
         }

         $pdf::Output();
         exit;
    }
}

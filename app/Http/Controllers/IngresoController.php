<?php

namespace sisVentas\Http\Controllers;

use Carbon\Carbon;
use DB;
use Date;
use Excel;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Response;
use sisVentas\Articulo;
use sisVentas\DetalleIngreso;
use sisVentas\Http\Requests;
use sisVentas\Http\Requests\IngresoFormRequest;
use sisVentas\Ingreso;

class IngresoController extends Controller
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
           $ingresos=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy('i.idingreso','desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->paginate(7);
            return view('compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);

        }
    }
    public function create()
    {
    	$personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
        $articulos = Articulo::where('estado', 'Activo')->get();
        info(json_encode($articulos));
        return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

     public function store (IngresoFormRequest $request)
    {
    	try{
        	DB::beginTransaction();
        	$ingreso=new Ingreso;
	        $ingreso->idproveedor=$request->get('idproveedor');
	        $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
	        $ingreso->serie_comprobante=$request->get('serie_comprobante');
	        $ingreso->num_comprobante=$request->get('num_comprobante');

	        $mytime = Carbon::now('America/Lima');
	        $ingreso->fecha_hora=$mytime->toDateTimeString();
	        if ($request->get('impuesto')=='1')
            {
                $ingreso->impuesto='18';
            }
            else
            {
                $ingreso->impuesto='0';
            }
	        $ingreso->estado='A';
	        $ingreso->save();

	        $idarticulo = $request->get('idarticulo');
	        $cantidad = $request->get('cantidad');
	        $precio_compra = $request->get('precio_compra');
	        $precio_venta = $request->get('precio_venta');
            $precio_venta2 = $request->get('precio_venta2');
            $precio_venta3 = $request->get('precio_venta3');
            $precio_venta4 = $request->get('precio_venta4');

	        $cont = 0;

	        while($cont < count($idarticulo)){
	            $detalle = new DetalleIngreso();
	            $detalle->idingreso= $ingreso->idingreso;
	            $detalle->idarticulo= $idarticulo[$cont];
	            $detalle->cantidad= $cantidad[$cont];
	            $detalle->precio_compra= $precio_compra[$cont];
	            $detalle->precio_venta= $precio_venta[$cont];
                $detalle->precio_venta2= $precio_venta2[$cont];
                $detalle->precio_venta3= $precio_venta3[$cont];
                $detalle->precio_venta4= $precio_venta4[$cont];
                if ($detalle->save()) {
                    $detalle->updateStock();
                }
	            $cont=$cont+1;
	        }

        	DB::commit();

        }catch(\Exception $e)
        {
          	DB::rollback();
        }

        return Redirect::to('compras/ingreso');
    }

    public function show($id)
    {
    	$ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta','d.precio_venta2','d.precio_venta3','d.precio_venta4')
             ->where('d.idingreso','=',$id)
             ->get();
        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
    	$ingreso=Ingreso::findOrFail($id);
        $ingreso->Estado='C';
        $ingreso->update();
        return Redirect::to('compras/ingreso');
    }

    public function reporteExcel(Request $request)
    {
        $mes = $request->q;

        $headers = ['RUC', 'RAZON SOCIAL', 'COMPROBANTE', 'FECHA', 'PRECIO', 'IGV(18%)', 'TOTAL'];

        $compras = Ingreso::with('persona')->with('detalle_ingreso');

        if ($mes) {
            $compras = $compras->whereMonth('fecha_hora', '=', date('m'))
            ->whereYear('fecha_hora', '=', date('Y'));
        }

        $compras = $compras->orderBy('idingreso', 'desc')->get();

        $data = [];

        foreach ($compras as $key => $compra) {
            $precio = 0;
            foreach ($compra->detalle_ingreso as $key => $ingreso) {
                $precio = $precio + ($ingreso->precio_compra * $ingreso->cantidad);
            }

            $total = $precio;
            $impuesto = 0;

            if ($compra->impuesto > 0) {
                $impuesto = ($precio * 18)/100;
                $total = $precio + $impuesto;
            }

            $data[] = [
                'ruc' => $compra->persona->num_documento,
                'razon social' => $compra->persona->nombre,
                'comprobante' => "{$compra->tipo_comprobante} {$compra->serie_comprobante}-{$compra->num_comprobante}",
                'fecha' => Carbon::parse($compra->fecha_hora)->format('d/m/Y'),
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
            $excel->sheet('Compras', function($sheet) use ($headers, $data) {
                $sheet->setOrientation('landscape');
                $sheet->cell('A1:G1', function($cell) {
                    //$cell->setFontSize(14);
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

    $ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','p.direccion','p.num_documento','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta','d.precio_venta2','d.precio_venta3','d.precio_venta4')
             ->where('d.idingreso','=',$id)
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
        $pdf::Cell(0,0,utf8_decode($ingreso->tipo_comprobante));

        $pdf::SetFont('Arial','B',10); //TAMAÑO SERIE
        //Inicio con el reporte
        $pdf::SetXY(180,19);
        $pdf::Cell(0,0,utf8_decode($ingreso->serie_comprobante."-".$ingreso->num_comprobante));

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
    $pdf::Cell(150, 7,utf8_decode($ingreso->nombre),0);
    $pdf::Ln();

/**************** DNI / RUC ****************/
    $pdf::SetFont('Arial','B',11);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_ruc = "Dni / Ruc:";
    $pdf::Cell(40, 7,utf8_decode($camp_ruc),0);
    $pdf::SetFont("Times");
    $pdf::Cell(150, 7,utf8_decode($ingreso->num_documento),0);
    $pdf::Ln();

/**************** Dirección ****************/
    $pdf::SetFont('Arial','B',11);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_direccion = "Dirección:";
    $pdf::Cell(40, 7,utf8_decode($camp_direccion),0);
    $pdf::SetFont("Times");
    $pdf::Cell(150, 7,utf8_decode($ingreso->direccion),0);
    $pdf::Ln();

/**************** Fecha ****************/
    $pdf::SetFont('Arial','B',11);
    $pdf::SetFillColor(224,100,100);
    $pdf::SetTextColor(0);
    $pdf::SetLineWidth(.3);
    $camp_fecha = "Fecha:";
    $pdf::Cell(40, 7,utf8_decode($camp_fecha),0);
    $pdf::SetFont("Times");
    $pdf::Cell(150, 7,substr($ingreso->fecha_hora,0,10),0);
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
    $pdf::Cell(30, 7,$det->precio_compra,0,'C',true);
    $pdf::Cell(30, 7,sprintf("%0.2F",($det->precio_compra*$det->cantidad)),0,'C',true);
    $total=$total+($det->precio_compra*$det->cantidad);
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
    $pdf::Cell(30, 7,"".sprintf("%0.2F", $ingreso->total-($ingreso->total*$ingreso->impuesto/($ingreso->impuesto+100))),0,1,'R',true);

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
    $pdf::Cell(30, 7,"".sprintf("%0.2F", ($ingreso->total*$ingreso->impuesto/($ingreso->impuesto+100))),0,1,'R',true);

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
    $pdf::Cell(30, 7,"".sprintf("%0.2F", $ingreso->total),0,1,'R',true);


/************* Footer ************************/
    $pdf::SetFont('Arial','B',10);
    $pdf::Cell(190, 30,"Gracias por visitarnos.",0,0,'C');
    $pdf::Ln();
        $pdf::Output();
        exit;
    }
    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->orderBy('i.idingreso','desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->get();

         //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf('L','mm','A4');
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Compras"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda
         $pdf::SetFont('Arial','B',10);
         //El ancho de las columnas debe de sumar promedio 190
         $pdf::cell(35,8,utf8_decode("Fecha"),1,"","L",true);
         $pdf::cell(80,8,utf8_decode("Proveedor"),1,"","L",true);
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
            $pdf::cell(25,8,utf8_decode($reg->total),1,"","R",true);
            $pdf::Ln();
         }

         $pdf::Output();
         exit;
    }
}

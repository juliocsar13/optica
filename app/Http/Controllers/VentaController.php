<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Optica\Venta;
use Optica\DetalleVenta;
use Optica\User;
use Optica\Ingreso;
use Illuminate\Support\Facades\Storage;
use Optica\Persona;
use Optica\Movimiento;
use Optica\Caja;

use Optica\Producto;
use Optica\Sucursal;
use Optica\Notifications\NotifyAdmin;
use Illuminate\Support\Facades\Auth;
use Optica\Empresa;
use Optica\Support\Collection;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar=='') {
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
            'ventas.estado','ventas.created_at','personas.nombre','users.usuario','ventas.idproveedor',
            'ventas.pendiente')
            ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('ventas.id', 'desc')->paginate(6);
        }
        else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
            'ventas.estado','ventas.created_at','personas.nombre','users.usuario','ventas.idproveedor',
            'ventas.pendiente')
              ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('ventas.id', 'desc')->paginate(6);
        }

        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }
    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
        'ventas.estado','personas.nombre','users.usuario',
        'ventas.adelanto','ventas.pendiente')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }
    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetalleVenta::join('productos','detalle_ventas.idproducto','=','productos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'productos.nombre as producto', 'productos.codigo as codigo', 'productos.descripcion as descripcion', 'detalle_ventas.n_material')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();

        return ['detalles' => $detalles];
    }
    public function pdf($id, $type)
    {

        if ($type == 'BOLETA') {

          $data = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->join('sucursales','sucursales.id','=', 'ventas.idsucursal' )

                        ->crossJoin('empresas')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
                                    'ventas.num_comprobante','ventas.created_at','ventas.impuesto','ventas.total',
                                    'ventas.estado','personas.nombre','personas.tipo_documento','personas.num_documento',
                                'ventas.adelanto','ventas.pendiente',
                                'personas.direccion','personas.email',
                                'personas.telefono','users.usuario',
                                'empresas.direccion_e','empresas.email_e','empresas.nombre_e','empresas.razon_social','empresas.representante','empresas.ruc_e',
                                'empresas.telefono_e', 'sucursales.telefono_s','sucursales.razon_social_s','sucursales.tipo_documento_s','sucursales.num_documento_s','sucursales.direccion_s',
                                'sucursales.email_s','sucursales.representante_s',
                                DB::raw("DAY(ventas.created_at) as day"), DB::raw("MONTHNAME(ventas.created_at) as month"), DB::raw("YEAR(ventas.created_at) as year"))
                                ->where('ventas.id','=',$id)
                                ->orderBy('ventas.id', 'desc')->take(1)->get()[0];

                                switch ($data['month']) {
                                  case 'January':
                                    $data['month'] = 'Enero';
                                  break;
                                  case 'February':
                                    $data['month'] = 'Febrero';
                                  break;
                                  case 'March':
                                    $data['month'] = 'Marzo';
                                  break;
                                  case 'April':
                                    $data['month'] = 'Abril';
                                  break;
                                  case 'May':
                                    $data['month'] == 'Mayo';
                                  break;
                                  case 'June':
                                    $data['month'] = 'Junio';
                                  break;
                                  case 'July':
                                    $data['month'] = 'Julio';
                                  break;
                                  case 'August':
                                    $data['month'] = 'Agosto';
                                  break;
                                  case 'September':
                                    $data['month'] = 'Septiembre';
                                  break;
                                  case 'October':
                                    $data['month'] = 'October';
                                  break;
                                  case 'November':
                                    $data['month'] = 'Noviembre';
                                  break;
                                  case 'December':
                                    $data['month'] = 'Diciembre';
                                  break;
                                  default:
                                  break;
                                }

          $detalles = DetalleVenta::join('productos', 'detalle_ventas.idproducto','=','productos.id')
                                    ->join('familias', 'familias.id','=','productos.idfamilia')
                                    ->select('detalle_ventas.cantidad','detalle_ventas.precio',
                                    'detalle_ventas.descuento', 'productos.nombre as producto',
                                    'detalle_ventas.n_material as n_material',
                                    'familias.nombre as nombre_categoria')
                                    ->where('detalle_ventas.idventa','=',$id)
                                    ->orderBy('detalle_ventas.id', 'desc')->get();

          foreach ($detalles as $key => $value) {
            if ($value['nombre_categoria'] == 'materiales') {
              $detalles[$key]['producto'] = $detalles[$key]['n_material'];
            }
          }
          $total = DetalleVenta::join('productos', 'detalle_ventas.idproducto','=','productos.id')
                                    ->select(DB::raw('SUM(detalle_ventas.cantidad*detalle_ventas.precio)  as total '))
                                    ->where('detalle_ventas.idventa','=',$id)->get()[0];

          $numventa=Venta::select('num_comprobante')->where('id',$id)->get();
          $pdf = \PDF::loadView('pdf.venta',['ventas' => $detalles,'info'=>$data,'total'=> $total]);
          $pdf->setPaper('A4', 'portrait');
          return $pdf->stream();
        } else if($type== 'FACTURA'){
          $data = Venta::join('personas','ventas.idcliente','=','personas.id')
                        ->join('users','ventas.idusuario','=','users.id')
                        ->join('sucursales','sucursales.id','=', 'ventas.idsucursal' )
                        ->crossJoin('empresas')
                        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
                                    'ventas.num_comprobante','ventas.created_at','ventas.impuesto','ventas.total',
                                    'ventas.estado','personas.nombre','personas.tipo_documento','personas.num_documento',
                                'ventas.adelanto','ventas.pendiente',
                                'personas.direccion','personas.email',
                                'personas.telefono','users.usuario',
                                'empresas.direccion_e','empresas.email_e','empresas.nombre_e','empresas.razon_social','empresas.representante','empresas.ruc_e',
                                'empresas.telefono_e', 'sucursales.telefono_s','sucursales.razon_social_s','sucursales.tipo_documento_s','sucursales.num_documento_s','sucursales.direccion_s',
                                'sucursales.email_s','sucursales.representante_s',
                                DB::raw("DAY(ventas.created_at) as day"), DB::raw("MONTHNAME(ventas.created_at) as month"), DB::raw("YEAR(ventas.created_at) as year"))
                                ->where('ventas.id','=',$id)
                                ->orderBy('ventas.id', 'desc')->take(1)->get()[0];
                                switch ($data['month']) {
                                  case 'January':
                                    $data['month'] = 'Enero';
                                  break;
                                  case 'February':
                                    $data['month'] = 'Febrero';
                                  break;
                                  case 'March':
                                    $data['month'] = 'Marzo';
                                  break;
                                  case 'April':
                                    $data['month'] = 'Abril';
                                  break;
                                  case 'May':
                                    $data['month'] == 'Mayo';
                                  break;
                                  case 'June':
                                    $data['month'] = 'Junio';
                                  break;
                                  case 'July':
                                    $data['month'] = 'Julio';
                                  break;
                                  case 'August':
                                    $data['month'] = 'Agosto';
                                  break;
                                  case 'September':
                                    $data['month'] = 'Septiembre';
                                  break;
                                  case 'October':
                                    $data['month'] = 'October';
                                  break;
                                  case 'November':
                                    $data['month'] = 'Noviembre';
                                  break;
                                  case 'December':
                                    $data['month'] = 'Diciembre';
                                  break;
                                  default:
                                  break;
                                }
          $detalles = DetalleVenta::join('productos', 'detalle_ventas.idproducto','=','productos.id')
                                    ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                                    'productos.nombre as producto')
                                    ->where('detalle_ventas.idventa','=',$id)
                                    ->orderBy('detalle_ventas.id', 'desc')->get();

          $total = DetalleVenta::join('productos', 'detalle_ventas.idproducto','=','productos.id')
                                    ->select(DB::raw('SUM(detalle_ventas.cantidad*detalle_ventas.precio)  as total '))
                                    ->where('detalle_ventas.idventa','=',$id)->get()[0];

          $numventa=Venta::select('num_comprobante')->where('id',$id)->get();
          $pdf = \PDF::loadView('pdf.factura',['ventas' => $detalles,'info'=>$data,'total'=> $total]);
          $pdf->setPaper('A4', 'portrait');
          return $pdf->stream();


        } else if($type == 'TICKET') {

          $detalles = DetalleVenta::join('productos','detalle_ventas.idproducto','=','productos.id')
            ->select('detalle_ventas.cantidad', 'detalle_ventas.precio', 'productos.nombre as producto')
            ->where('detalle_ventas.idventa','=',$id)
            ->orderBy('detalle_ventas.id', 'desc')->get();
            $sucursal= Sucursal::where('id', '=', Auth::user()->idsucursal)->get();

            $totales = Venta::join('personas','ventas.idcliente','=','personas.id')
              ->select(DB::raw("SUM(ventas.total) as total"))
              ->where('ventas.id','=',$id)
              ->get();
            $pendiente = Venta::join('personas','ventas.idcliente','=','personas.id')
              ->select(DB::raw("SUM(ventas.pendiente) as pendiente"))
              ->where('ventas.id','=',$id)
              ->get();
            $adelanto = Venta::join('personas','ventas.idcliente','=','personas.id')
              ->select(DB::raw("SUM(ventas.adelanto) as adelanto"))
              ->where('ventas.id','=',$id)
              ->get();

            $paciente = Venta::join('personas','ventas.idcliente','=','personas.id')
              ->select('personas.nombre as paciente')
              ->where('ventas.id','=',$id)
              ->get();

          $pdf = \PDF::loadView('pdf.sales.reportTicket', [ 'ventas' => $detalles,
          'sucursal' =>$sucursal[0],
          'totales'=>$totales[0],
          'pendiente' => $pendiente[0],
          'adelanto' => $adelanto[0],
          'paciente' => $paciente[0],
        ]);
          $pdf->setPaper('B5', 'portrait');
          return $pdf->stream();
        }

    }
    public function pdf2(Request $request,$id)
    {
        $venta = Venta::join('personas','ventas.idproveedor','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.created_at','ventas.impuesto','ventas.total',
        'ventas.estado',
        'ventas.esfera','ventas.cilindro','ventas.eje','ventas.add','ventas.dip','ventas.av','ventas.prisma','ventas.esfera2',
        'ventas.cilindro2','ventas.eje2','ventas.av2','ventas.prisma2','ventas.idproveedor','ventas.referencia',
        'ventas.adelanto','pendiente',
        'personas.nombre','personas.tipo_documento','personas.num_documento',
        'personas.direccion','personas.email',
        'personas.telefono','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        $detalles = DetalleVenta::join('productos', 'detalle_ventas.idproducto','=','productos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'productos.nombre as producto')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();

        $numventa=Venta::select('num_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.venta2',['venta'=>$venta,'detalles'=>$detalles]);
        return $pdf->download('orden-'.$numventa[0]->num_comprobante.'.pdf');
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $mytime= Carbon::now('America/Lima');

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime->toDateString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->estado = 'Registrado';

            $venta->esfera = $request->esfera;
            $venta->cilindro = $request->cilindro;
            $venta->eje = $request->eje;
            $venta->add = $request->add;
            $venta->dip = $request->dip;
            $venta->av = $request->av;
            $venta->prisma = $request->prisma;

            $venta->esfera2 = $request->esfera2;
            $venta->cilindro2 = $request->cilindro2;
            $venta->eje2 = $request->eje2;
            $venta->av2 = $request->av2;
            $venta->prisma2 = $request->prisma2;

            $venta->idproveedor = $request->idproveedor;
            $venta->referencia = $request->referencia;

            $venta->puente = $request->puente;
            $venta->hor = $request->hor;
            $venta->vert = $request->vert;
            $venta->diag - $request->diag;

            $venta->color = $request->venta;
            $venta->efecto = $request->efecto;
            $venta->tono = $request->tono;

            $venta->angulo_pantoscopio = $request->angulo_pantoscopio;
            $venta->ang_curvatura = $request->ang_curvatura;
            $venta->dist_lectura = $request->dist_lectura;
            $venta->st = $request->st;
            $venta->he = $request->he;

            $venta->adelanto = $request->adelanto;
            $venta->pendiente = $request->pendiente;
            $venta->forma_pago = $request->forma_pago;
            $venta->adelanto_v = $request->adelanto_v;
            $venta->idsucursal = $request->idsucursal;
            //$venta->n_material = $request->n_material;
            $venta->save();
            $detalles = $request->data;
            $flat = 0;
            $total_impuesto = 0;
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idproducto = $det['idproducto'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->n_material = $det['n_material'];

                $total_impuesto = $det['precio']*$request->impuesto*$det['cantidad'] + $flat;
                $flat = $total_impuesto;
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }
            $user = Auth::user();
            $sucursal = DB::table('sucursales')->where('id', $user->idsucursal)->get();
            $ds = DB::table('detalle_ventas')->select(DB::raw('SUM(descuento) as descuento'))->where('idventa', $venta->id)->get()[0];

            $pendiente = $request->pendiente;
            $tipo_comprobante = $request->tipo_comprobante;

            if ($pendiente == 0 && $sucursal[0]->num_documento_s != null && $tipo_comprobante == 'BOLETA' || $tipo_comprobante == 'FACTURA') {
              if ($tipo_comprobante == 'BOLETA')  {
                $tipo = '03';
              }else if($tipo_comprobante == 'FACTURA')  {
                $tipo = '01';
              }
              $tipOperacion = '0101';
              $fecEmision = $mytime->toDateString();
              $horEmision = $mytime->toTimeString();
              $fecVencimiento = $mytime->toDateString();
              $codLocalEmisor = '0001';
              $tipDocUsuario = $sucursal[0]->tipo_documento_s == 'ruc'? '0': '1';
              $numDocUsuario = $sucursal[0]->num_documento_s;
              $rznSocialUsuario = $sucursal[0]->razon_social_s;
              $tipMoneda = 'PEN';
              $sumTotTributo = $total_impuesto;
              $sumTotValVenta = $request->total - $total_impuesto;
              $sumTotVenta = $request->total;
              $sumDescTotal = $ds->descuento;
              $sumOtrosCargos = '0.00';
              $sumTotalAnticipos = '0.00';
              $sumImpVenta = $request->total;
              $udl = '2.1';
              $cusId = '2.0';

              $content = $tipOperacion.'|'.$fecEmision.'|'.$horEmision.'|'.
                         $fecVencimiento.'|'.$codLocalEmisor.'|'.$tipDocUsuario.'|'.
                         $numDocUsuario.'|'.$rznSocialUsuario.'|'.$tipMoneda.'|'.$sumTotTributo.'|'.
                         $sumTotValVenta.'|'.$sumTotVenta.'|'.$sumDescTotal.'|'.$sumOtrosCargos.'|'.
                         $sumTotalAnticipos.'|'.$sumImpVenta.'|'.$udl.'|'.$cusId;
              Storage::disk('local')->put('/public/facture/'.$numDocUsuario.'-'.$tipo.'-'.$venta->serie_comprobante.'-'.$venta->num_comprobante.'.cab', $content);

              //Storage::disk('local')->put('facture/'.$numDocUsuario.'-'.$tipo.'-'.$venta->serie_comprobante.'-'.$venta->num_comprobante.'.det', '');
              foreach($detalles as $ep=>$det){
                  $codUnidadMedida = 'NIU';
                  $ctdUnidadItem = $det['cantidad'];
                  $codProducto = $det['codigo'];
                  $codProductoSunat = '-';
                  $desItem = $det['producto'].'-'.$det['descripcion'];
                  $mtoValorUnitario = $det['precio'];
                  $sumTotTributoItem = $det['precio']*$request->impuesto*$det['cantidad'];

                  $codTrilIGV = '1000';
                  $motIgvItem = $det['precio']*$request->impuesto*$det['cantidad'];
                  $motBaseIgvItem = $sumImpVenta - $motIgvItem;
                  $nomTributoIgvItem = 'IGV';
                  $codTributoIgvItem = '1000';
                  $tipAfIGV = '10';
                  $porIgvItem = '18.0';

                  $codTriISC = '-';
                  $mtoIscItem = '0.00';
                  $mtoBaseIscItem = '0.00';
                  $nomTributoIscItem = '0.00';
                  $codTipTributoIscItem = '';
                  $tipSisISC = '';
                  $porIscItem = '';

                  $codTriOtroItem= '-';
                  $mtoTriOtroItem = '0.00';
                  $mtoBaseTriOtroItem= '0.00';
                  $nomTributoIOtroItem= '0.00';
                  $codTipTributoIOtroItem= '';
                  $porTriOtroItem= '';

                  $mtoPrecioVentaUnitario = $det['precio'];
                  $mtoValorVentaItem = $det['precio']*$det['cantidad'];
                  $mtoValorReferencialUnitario = '-';

                  $content_detail = $codUnidadMedida.'|'.$ctdUnidadItem.'|'.$codProducto.'|'.$codProductoSunat.'|'.$desItem.'|'.$mtoValorUnitario.'|'.$sumTotTributoItem.'|'.
                  $codTrilIGV.'|'.$motIgvItem.'|'.$motBaseIgvItem.'|'.$nomTributoIgvItem.'|'.$codTributoIgvItem.'|'.$tipAfIGV.'|'.$porIgvItem.'|'.
                  $codTriISC.'|'.$mtoIscItem.'|'.$mtoBaseIscItem.'|'.$nomTributoIscItem.'|'.$codTipTributoIscItem.'|'.$tipSisISC.'|'.$porIscItem.'|'.
                  $codTriOtroItem.'|'.$mtoTriOtroItem.'|'.$mtoBaseTriOtroItem.'|'.$nomTributoIOtroItem.'|'.$codTipTributoIOtroItem.'|'.$porTriOtroItem.'|'.
                  $mtoPrecioVentaUnitario.'|'.$mtoValorVentaItem.'|'.$mtoValorReferencialUnitario.'|';

                  Storage::append('/public/facture/'.$numDocUsuario.'-'.$tipo.'-'.$venta->serie_comprobante.'-'.$venta->num_comprobante.'.det', $content_detail);
              }

            }

            $fechaActual= date('Y-m-d');
            $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count();
            $numIngresos = DB::table('ingresos')->whereDate('created_at', $fechaActual)->count();

            $arregloDatos = [
                'ventas' => [
                    'numero' => $numVentas,
                    'msj' => 'Ventas'
                ],
                'ingresos' => [
                    'numero' => $numIngresos,
                    'msj' => 'Ingresos'
                ]
            ];
            $allUsers = User::all();

            foreach ($allUsers as $notificar) {
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));
            }
            ////////////////////////////////////////////////////////////////////
            $caja = Caja::select('*')
              ->where('estado' ,'=', '1')
              ->where('idsucursal', '=', Auth::user()->idsucursal)
              ->get();

            $caja1 = new Caja();
            $caja1 = Caja::findOrFail($caja[0]->id);
            $caja1->monto_final =  $caja[0]->monto_final + $request->adelanto;
            $caja1->save();
            ////////////////////////////////////////////////////////////////////
            DB::commit();
            return [
                'id' => $venta->id
            ];
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }
    public function desactivarPendiente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        //die(json_encode($venta));

        $caja = Caja::select('*')
          ->where('estado' ,'=', '1')
          ->where('idsucursal', '=', Auth::user()->idsucursal)
          ->get();

        $monto = $venta->total - $venta->adelanto;
        $caja1 = Caja::findOrFail($caja[0]->id);
        $caja1->monto_final =   $caja[0]->monto_final + $monto;

        $caja1->save();

        $venta->adelanto = $venta->pendiente;
        $venta->pendiente = '0.00';
        $venta->save();
        $mytime= Carbon::now('America/Lima');
        $user = Auth::user();
        $sucursal = DB::table('sucursales')->where('id', $user->idsucursal)->get();
        $ds = DB::table('detalle_ventas')->select(DB::raw('SUM(descuento) as descuento'))->where('idventa', $request->id)->get()[0];
        $vt = \Optica\Venta::with(['detalle_ventas'])
          ->where('id', '=', $request->id)
          ->get();
        $ventas = $vt[0];
        $pendiente = $ventas->pendiente;
        $tipo_comprobante = $ventas->tipo_comprobante;
        $detalles  = $ventas->detalle_ventas;
        $flat=0;
        $total_impuesto=0;
        foreach($detalles as $ep=>$det)
        {
            $detalle = new DetalleVenta();
            $detalle->idventa = $venta->id;
            $detalle->idproducto = $det['idproducto'];
            $detalle->cantidad = $det['cantidad'];
            $detalle->precio = $det['precio'];
            $total_impuesto = $det['precio']*$ventas->impuesto*$det['cantidad'] + $flat;
            $flat = $total_impuesto;
        }
        if ($pendiente == 0 && $sucursal[0]->num_documento_s != null && $tipo_comprobante == 'BOLETA' || $tipo_comprobante == 'FACTURA') {
          if ($tipo_comprobante == 'BOLETA')  {
            $tipo = '03';
          }else if($tipo_comprobante == 'FACTURA')  {
            $tipo = '01';
          }
          $tipOperacion = '0101';
          $fecEmision = $mytime->toDateString();
          $horEmision = $mytime->toTimeString();
          $fecVencimiento = $mytime->toDateString();
          $codLocalEmisor = '0001';
          $tipDocUsuario = $sucursal[0]->tipo_documento_s == 'ruc'? '0': '1';
          $numDocUsuario = $sucursal[0]->num_documento_s;
          $rznSocialUsuario = $sucursal[0]->razon_social_s;
          $tipMoneda = 'PEN';
          $sumTotTributo = $total_impuesto;
          $sumTotValVenta = $ventas->total - $total_impuesto;
          $sumTotVenta = $ventas->total;
          $sumDescTotal = $ds->descuento;
          $sumOtrosCargos = '0.00';
          $sumTotalAnticipos = '0.00';
          $sumImpVenta = $ventas->total;
          $udl = '2.1';
          $cusId = '2.0';

          $content = $tipOperacion.'|'.$fecEmision.'|'.$horEmision.'|'.
                     $fecVencimiento.'|'.$codLocalEmisor.'|'.$tipDocUsuario.'|'.
                     $numDocUsuario.'|'.$rznSocialUsuario.'|'.$tipMoneda.'|'.$sumTotTributo.'|'.
                     $sumTotValVenta.'|'.$sumTotVenta.'|'.$sumDescTotal.'|'.$sumOtrosCargos.'|'.
                     $sumTotalAnticipos.'|'.$sumImpVenta.'|'.$udl.'|'.$cusId;
          Storage::disk('local')->put('/public/facture/'.$numDocUsuario.'-'.$tipo.'-'.$ventas->serie_comprobante.'-'.$ventas->num_comprobante.'.cab', $content);

          //Storage::disk('local')->put('facture/'.$numDocUsuario.'-'.$tipo.'-'.$venta->serie_comprobante.'-'.$venta->num_comprobante.'.det', '');
          foreach($detalles as $ep=>$det){
              $codUnidadMedida = 'NIU';
              $ctdUnidadItem = $det['cantidad'];
              $codProducto = $det['codigo'];
              $codProductoSunat = '-';
              $desItem = $det['producto'].'-'.$det['descripcion'];
              $mtoValorUnitario = $det['precio'];
              $sumTotTributoItem = $det['precio']*$ventas->impuesto*$det['cantidad'];

              $codTrilIGV = '1000';
              $motIgvItem = $det['precio']*$ventas->impuesto*$det['cantidad'];
              $motBaseIgvItem = $sumImpVenta - $motIgvItem;
              $nomTributoIgvItem = 'IGV';
              $codTributoIgvItem = '1000';
              $tipAfIGV = '10';
              $porIgvItem = '18.0';

              $codTriISC = '-';
              $mtoIscItem = '0.00';
              $mtoBaseIscItem = '0.00';
              $nomTributoIscItem = '0.00';
              $codTipTributoIscItem = '';
              $tipSisISC = '';
              $porIscItem = '';

              $codTriOtroItem= '-';
              $mtoTriOtroItem = '0.00';
              $mtoBaseTriOtroItem= '0.00';
              $nomTributoIOtroItem= '0.00';
              $codTipTributoIOtroItem= '';
              $porTriOtroItem= '';

              $mtoPrecioVentaUnitario = $det['precio'];
              $mtoValorVentaItem = $det['precio']*$det['cantidad'];
              $mtoValorReferencialUnitario = '-';

              $content_detail = $codUnidadMedida.'|'.$ctdUnidadItem.'|'.$codProducto.'|'.$codProductoSunat.'|'.$desItem.'|'.$mtoValorUnitario.'|'.$sumTotTributoItem.'|'.
              $codTrilIGV.'|'.$motIgvItem.'|'.$motBaseIgvItem.'|'.$nomTributoIgvItem.'|'.$codTributoIgvItem.'|'.$tipAfIGV.'|'.$porIgvItem.'|'.
              $codTriISC.'|'.$mtoIscItem.'|'.$mtoBaseIscItem.'|'.$nomTributoIscItem.'|'.$codTipTributoIscItem.'|'.$tipSisISC.'|'.$porIscItem.'|'.
              $codTriOtroItem.'|'.$mtoTriOtroItem.'|'.$mtoBaseTriOtroItem.'|'.$nomTributoIOtroItem.'|'.$codTipTributoIOtroItem.'|'.$porTriOtroItem.'|'.
              $mtoPrecioVentaUnitario.'|'.$mtoValorVentaItem.'|'.$mtoValorReferencialUnitario.'|';

              Storage::append('/public/facture/'.$numDocUsuario.'-'.$tipo.'-'.$ventas->serie_comprobante.'-'.$ventas->num_comprobante.'.det', $content_detail);
          }
        }
    }
    public function filterSales(Request $request)
    {
      $request = $request->all();
      $client_id = $request['client_id'];
      $dateStart = $request['dateStart'];
      $dateEnd = $request['dateEnd'];


        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
        'ventas.estado','ventas.created_at','personas.nombre','users.usuario','ventas.idproveedor',
        'ventas.pendiente')
        ->where('personas.id', 'like', '%'. $client_id . '%')
        ->where('ventas.fecha_hora','>=',$dateStart)
        ->where('ventas.fecha_hora','<=',$dateEnd)
        ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)
        ->orderBy('ventas.id', 'desc')->paginate();

        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }
    public function reportHistory(Request $request)
    {
      $request = $request->all();
      $client_id = $request['client_id'];

      $dateEnd = $request['dateEnd'];
      $dateStart = $request['dateStart'];
      $user = Auth::user();
      if ($client_id == 'undefined') {
        $client_id = null;
      }
      //////////////////////////////////////
      $detalles = \Optica\Venta::with(['detalle_ventas'])
                  ->where('ventas.idcliente', 'like', '%'. $client_id . '%')
                  ->where('ventas.fecha_hora','>=',$dateStart)
                  ->where('ventas.fecha_hora','<=',$dateEnd)
                  ->where('ventas.idsucursal', '=',Auth::user()->idsucursal)
                  ->get();

        foreach ($detalles as $key => $venta) {

          foreach ($venta->detalle_ventas as $key1 => $value) {
            $a = json_encode($value->idproducto);
            if (isset($a)) {

                $producto = Producto::join('familias', 'familias.id','=','productos.idfamilia')
                                    ->select('familias.nombre as nombre_categoria', 'productos.nombre as nombre')
                                    ->where('productos.id', '=', $a)->get()[0];
                if ($producto->nombre_categoria == 'materiales') {
                  $detalles[$key]['detalle_ventas'][$key1]['nombre'] = $detalles[$key]['detalle_ventas'][$key1]['n_material'];

                } else {
                  $detalles[$key]['detalle_ventas'][$key1]['nombre'] = $producto->nombre;
                }
            }
          }
        }
      //die($detalles);
      $cliente = Persona::where('id', 'like', '%'. $client_id . '%')->get();
      $sucursal= Sucursal::where('id', '=', $user->idsucursal)->get();
      $totales = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->select(DB::raw("SUM(ventas.total) as total"))
        ->where('ventas.fecha_hora','>=',$dateStart)
        ->where('ventas.fecha_hora','<=',$dateEnd)
        ->where('personas.id', 'like', '%'. $client_id . '%')
        ->get();


      $pdf = \PDF::loadView('pdf.sales.reportClient',[ 'ventas' => $detalles,
                                                       'totales' => $totales[0]->total,
                                                        'sucursal' => $sucursal[0],
                                                        'cliente'=> $cliente[0]
                                                      ]);

      $pdf->setPaper('A4', 'portrait');
      return $pdf->stream();
    }
    public function reportCaja(Request $request) {

      $request = $request->all();
      $dateEnd = $request['dateEnd'];
      $dateStart = $request['dateStart'];
      $tipo_comprobante = $request['tipo_comprobante'];

      $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.created_at as date', 'ingresos.forma_pagoI as forma_pago',
        'ingresos.tipo_comprobante', 'ingresos.total as total',
        'ingresos.adelantoI as adelanto', 'ingresos.pendienteI as pendiente',
        'ingresos.tipo_comprobante as tipo_comprobante', 'ingresos.serie_comprobante as serie_comprobante',
        'ingresos.num_comprobante as num_comprobante', 'users.usuario as usuario')
        ->where('ingresos.fecha_hora','>=',$dateStart)
        ->where('ingresos.fecha_hora','<=',$dateEnd)
        ->where('ingresos.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
        ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

      $ventas = Venta::join('personas','ventas.idproveedor','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.created_at as date', 'ventas.forma_pago as forma_pago',
        'ventas.tipo_comprobante', 'ventas.total as total',
        'ventas.adelanto as adelanto', 'ventas.pendiente as pendiente',
        'ventas.tipo_comprobante as tipo_comprobante', 'ventas.serie_comprobante as serie_comprobante',
        'ventas.num_comprobante as num_comprobante', 'users.usuario as usuario', 'ventas.created_at as created_at' ,'ventas.updated_at as updated_at')
        ->where('ventas.fecha_hora','>=',$dateStart)
        ->where('ventas.fecha_hora','<=',$dateEnd)
        ->where('ventas.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
        ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)->get();

      ///die(json_encode($ventas));
      foreach ($ventas as $key => $value) {
        if ($value['total'] != $value['adelanto']) {
          if ($value['created_at'] != $value['updated_at']) {
            $ventas[$key]['adelanto'] = $value['total'];
          }
        }
      }
      $movimientos = Movimiento::join('personas', 'movimientos.idpersona', '=', 'personas.id')
                  ->select('movimientos.id', 'personas.nombre as usuario', 'movimientos.descripcion',
                            'movimientos.created_at', 'movimientos.tipo', 'movimientos.created_at',
                            'movimientos.monto')
                  ->where('movimientos.created_at','>=',$dateStart)
                  ->where('movimientos.created_at','<=',$dateEnd)
                  ->where('idsucursal', '=', Auth::user()->idsucursal)
                  ->orderBy('movimientos.id', 'desc')
                  ->get();
      $mi_total = Movimiento::join('personas', 'movimientos.idpersona', '=', 'personas.id')
                  ->select(DB::raw("SUM(movimientos.monto) as total"))
                  ->where('movimientos.created_at','>=',$dateStart)
                  ->where('movimientos.created_at','<=',$dateEnd)
                  ->where('movimientos.tipo','=',0)
                  ->where('idsucursal', '=', Auth::user()->idsucursal)
                  ->orderBy('movimientos.id', 'desc')
                  ->get();
      $me_total = Movimiento::join('personas', 'movimientos.idpersona', '=', 'personas.id')
                  ->select(DB::raw("SUM(movimientos.monto) as total"))
                  ->where('movimientos.created_at','>=',$dateStart)
                  ->where('movimientos.created_at','<=',$dateEnd)
                  ->where('movimientos.tipo','=',1)
                  ->where('idsucursal', '=', Auth::user()->idsucursal)
                  ->orderBy('movimientos.id', 'desc')
                  ->get();
      $mov_total = $mi_total[0]->total - $me_total[0]->total;
      //die(json_encode($mov_total));

      $ingreso= collect($ingresos);
      $venta= collect($ventas);

      $ingreso->each(function ($item, $key) {
          $item['movimiento'] = 'compra';
      });
      $venta->each(function ($item, $key) {
          $item['movimiento'] = 'venta';
      });
      $cliente = User::where('id', '=', Auth::user()->id)->get();
      $sucursal= Sucursal::where('id', '=', Auth::user()->idsucursal)->get();
        $collection_ingreso = collect($ingreso);
        $fico = $collection_ingreso->filter(function ($value, $key) {
          return $value->forma_pago  == 'Contado';
        });
        $filter_ingreso_contado=$fico->sum('adelanto');
        //die($fico);
        $fic = $collection_ingreso->filter(function ($value, $key) {
          return $value->forma_pago   == 'Credito';
        });
        $filter_ingreso_credito=$fic->sum('adelanto');

        $total_ingresos = $filter_ingreso_contado + $filter_ingreso_credito;
        $cv = collect($ventas);
        $fcv = $cv->filter(function ($value, $key) {
          return $value->forma_pago== 'Visa';
        });
        $tcv=$fcv->sum('adelanto');

        $fce=$cv->filter(function ($value, $key) {
          return $value->forma_pago== 'Efectivo';
        });
        $tce=$fce->sum('adelanto');
        $total_cobros = $tcv+$tce;
        $ingreso_total = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select(DB::raw("SUM(ingresos.pendienteI) as total"))
        ->where('ingresos.fecha_hora','>=',$dateStart)
        ->where('ingresos.fecha_hora','<=',$dateEnd)
        ->where('ingresos.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
        ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();



        $venta_total = Venta::join('personas','ventas.idproveedor','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select(DB::raw("SUM(ventas.pendiente) as total"))
        ->where('ventas.fecha_hora','>=',$dateStart)
        ->where('ventas.fecha_hora','<=',$dateEnd)
        ->where('ventas.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
        ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)->get();

        $totales = $venta_total[0]->total + $ingreso_total[0]->total;

        $caja = Caja::select('*')
          ->where('estado' ,'=', '1')
          ->where('idsucursal', '=', Auth::user()->idsucursal)
          ->get();


        $collection = collect($venta);
        $collection_i = collect($ingreso);

        $count = $collection->count();
        $count_i = $collection->count();
        $pdf = \PDF::loadView('pdf.caja.caja',[ 'ventas' => $venta,
                                                'ingresos'=> $ingresos,
                                                'total_ingresos_contado' => $filter_ingreso_contado,
                                                'total_ingresos_credito' => $filter_ingreso_credito,
                                                'total_cobros_contado' => $tce,
                                                'total_cobros_credito' => $tcv,
                                                'total_ingresos' => $total_ingresos,
                                                'total_cobros' => $total_cobros,
                                                'totales' => $totales,
                                                'count' => $count,
                                                'caja'=> $caja[0]->monto_inicial,
                                                'movimientos'=> $movimientos,
                                                'total_movimientos'=> $mov_total,
                                                'count_i' => $count_i,
                                                'sucursal' => $sucursal[0],
                                                'cliente'=> $cliente[0]]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream();
    }
    public function filterCaja(Request $request) {

      $request = $request->all();
      $dateEnd = $request['dateEnd'];
      $dateStart = $request['dateStart'];
      $tipo = $request['tipo'];

      $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.created_at as date', 'ingresos.forma_pagoI as forma_pago',
        'ingresos.tipo_comprobante', 'ingresos.total as total',
        'ingresos.adelantoI as adelanto', 'ingresos.pendienteI as pendiente',
        'ingresos.tipo_comprobante as tipo_comprobante', 'ingresos.serie_comprobante as serie_comprobante',
        'ingresos.num_comprobante as num_comprobante', 'users.usuario as usuario', 'ingresos.fecha_hora')
        ->where('ingresos.fecha_hora','>=',$dateStart)
        ->where('ingresos.fecha_hora','<=',$dateEnd)
        ->where('ingresos.tipo_comprobante', 'like', '%'. $tipo . '%')
        ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

      $ventas = Venta::join('personas','ventas.idproveedor','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.created_at as date', 'ventas.forma_pago as forma_pago',
        'ventas.tipo_comprobante', 'ventas.total as total',
        'ventas.adelanto as adelanto', 'ventas.pendiente as pendiente',
        'ventas.tipo_comprobante as tipo_comprobante', 'ventas.serie_comprobante as serie_comprobante',
        'ventas.num_comprobante as num_comprobante', 'users.usuario as usuario', 'ventas.fecha_hora')
        ->where('ventas.fecha_hora','>=',$dateStart)
        ->where('ventas.fecha_hora','<=',$dateEnd)
        ->where('ventas.tipo_comprobante', 'like', '%'. $tipo . '%')
        ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)->get();

        $ingreso= collect($ingresos);
        $venta= collect($ventas);


        $ingreso->each(function ($item, $key) {
            $item['movimiento'] = 'compra';
        });
        $venta->each(function ($item, $key) {
            $item['movimiento'] = 'venta';
        });

        $result = $ingreso->concat($venta);
        $collection = (new Collection($result))->paginate(10);
        return [
          'pagination' => [
              'total'        => $collection->total(),
              'current_page' => $collection->currentPage(),
              'per_page'     => $collection->perPage(),
              'last_page'    => $collection->lastPage(),
              'from'         => $collection->firstItem(),
              'to'           => $collection->lastItem(),
          ],
          'ventas' => $collection ];

    }
    public function pdfAdelanto($id, $tipo) {
      $detalles = DetalleVenta::join('productos','detalle_ventas.idproducto','=','productos.id')
        ->select('detalle_ventas.cantidad', 'detalle_ventas.precio', 'productos.nombre as producto')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();
      $sucursal= Sucursal::where('id', '=', Auth::user()->idsucursal)->get();
      $totales = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->select(DB::raw("SUM(ventas.total) as total"))
        ->where('ventas.id','=',$id)
        ->get();

        $pendiente = Venta::join('personas','ventas.idcliente','=','personas.id')
          ->select(DB::raw("SUM(ventas.pendiente) as pendiente"))
          ->where('ventas.id','=',$id)
          ->get();
        $adelanto = Venta::join('personas','ventas.idcliente','=','personas.id')
          ->select(DB::raw("SUM(ventas.adelanto) as adelanto"))
          ->where('ventas.id','=',$id)
          ->get();

        $paciente = Venta::join('personas','ventas.idcliente','=','personas.id')
          ->select('personas.nombre as paciente')
          ->where('ventas.id','=',$id)
          ->get();

      $pdf = \PDF::loadView('pdf.sales.reportAdelanto',
        [ 'ventas' => $detalles,
        'sucursal' => $sucursal[0],
        'totales' => $totales[0],
        'pendiente' => $pendiente[0],
        'adelanto' => $adelanto[0],
        'paciente' => $paciente[0],
      ]);
      $pdf->setPaper('B5', 'portrait');
      return $pdf->stream();
    }
    public function descargarArchivos($id) {
      $venta = Venta::select('*')
      ->where('ventas.id', '=', $id)
      ->get()[0];
      if ($venta->tipo_comprobante == 'BOLETA')  {
        $tipo = '03';
      }else if($venta->tipo_comprobante == 'FACTURA')  {
        $tipo = '01';
      }
      $user = Auth::user();
      $sucursal = DB::table('sucursales')->where('id', $user->idsucursal)->get();
      $tipDocUsuario = $sucursal[0]->tipo_documento_s == 'ruc'? '0': '1';
      $numDocUsuario = $sucursal[0]->num_documento_s;
      $nombre = $numDocUsuario.'-'.$tipo.'-'.$venta->serie_comprobante.'-'.$venta->num_comprobante;

      $file= "/public/facture/".$nombre;
      $array  = [ $file.'.cab', $file.'.det'];
      $headers = array('Content-Type: text/plain');
      return Storage::download($array[0]);

    }
    public function descargarArchivos2($id) {
      $venta = Venta::select('*')
      ->where('ventas.id', '=', $id)
      ->get()[0];
      if ($venta->tipo_comprobante == 'BOLETA')  {
        $tipo = '03';
      }else if($venta->tipo_comprobante == 'FACTURA')  {
        $tipo = '01';
      }
      $user = Auth::user();
      $sucursal = DB::table('sucursales')->where('id', $user->idsucursal)->get();
      $tipDocUsuario = $sucursal[0]->tipo_documento_s == 'ruc'? '0': '1';
      $numDocUsuario = $sucursal[0]->num_documento_s;
      $nombre = $numDocUsuario.'-'.$tipo.'-'.$venta->serie_comprobante.'-'.$venta->num_comprobante;

      $file= "/public/facture/".$nombre;
      $array  = [ $file.'.cab', $file.'.det'];
      $headers = array('Content-Type: text/plain');
      return Storage::download($array[1]);

    }
}

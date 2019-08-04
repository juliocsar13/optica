<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Optica\Ingreso;
use Optica\DetalleIngreso;
use Optica\User;
use Optica\Proveedor;
use Optica\Persona;
use Optica\Sucursal;
use Optica\Caja;

use Optica\Notifications\NotifyAdmin;
use Illuminate\Support\Facades\Auth;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
            'ingresos.adelantoI','ingresos.pendienteI','ingresos.updated_at')
            ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('ingresos.id', 'desc')->paginate(10);
        }
        else{
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
            'ingresos.adelantoI','ingresos.pendienteI','ingresos.updated_at')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('ingresos.id', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }
    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $ingreso = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.adelantoI',
        'ingresos.estado','personas.nombre','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id', 'desc')->take(1)->get();

        return ['ingreso' => $ingreso];
    }
    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetalleIngreso::join('productos','detalle_ingresos.idproducto','=','productos.id')
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','productos.nombre as producto')
        ->where('detalle_ingresos.idingreso','=',$id)
        ->orderBy('detalle_ingresos.id', 'desc')->get();

        return ['detalles' => $detalles];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $mytime= Carbon::now('America/Lima');

            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = 'Registrado';

            $ingreso->adelantoI = $request->adelantoI;
            $ingreso->pendienteI = $request->pendienteI;
            $ingreso->forma_pagoI = $request->forma_pagoI;

            $ingreso->idsucursal = $request->idsucursal;
            $ingreso->save();

            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idproducto = $det['idproducto'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
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
            $caja1->monto_final =  $caja[0]->monto_final - $request->adelantoI;
            $caja1->save();
            ////////////////////////////////////////////////////////////////////
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }
    public function desactivarPendiente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        //$ingreso->adelantoI = $ingreso->pendienteI;
        $ingreso->pendienteI = '0.00';
        $ingreso->save();
    }
    public function reportCompra(Request $request) {
      $request = $request->all();
      $id = $request['venta'];
      //die(json_encode($request));
      $data = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
                    ->join('users','ingresos.idusuario','=','users.id')
                    ->join('sucursales','sucursales.id','=', 'ingresos.idsucursal' )
                    ->crossJoin('empresas')
                    ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                                'ingresos.num_comprobante','ingresos.created_at','ingresos.impuesto','ingresos.total',
                                'ingresos.estado','personas.nombre','personas.tipo_documento','personas.num_documento',
                            'ingresos.adelantoI','ingresos.pendienteI',
                            'personas.direccion','personas.email',
                            'personas.telefono','users.usuario',
                            'empresas.direccion_e','empresas.email_e','empresas.nombre_e','empresas.razon_social','empresas.representante','empresas.ruc_e',
                            'empresas.telefono_e', 'sucursales.telefono_s','sucursales.razon_social_s','sucursales.tipo_documento_s','sucursales.num_documento_s','sucursales.direccion_s',
                            'sucursales.email_s','sucursales.representante_s',
                            DB::raw("DAY(ingresos.created_at) as day"), DB::raw("MONTHNAME(ingresos.created_at) as month"), DB::raw("YEAR(ingresos.created_at) as year"))
                            ->where('ingresos.id','=',$id)
                            ->orderBy('ingresos.id', 'desc')->take(1)->get()[0];


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

      //die(json_encode($data));

      $detalles = DetalleIngreso::join('productos', 'detalle_ingresos.idproducto','=','productos.id')
                                ->select('detalle_ingresos.cantidad','detalle_ingresos.precio',
                                'productos.nombre as producto')
                                ->where('detalle_ingresos.idingreso','=',$id)
                                ->orderBy('detalle_ingresos.id', 'desc')->get();

      $total = DetalleIngreso::join('productos', 'detalle_ingresos.idproducto','=','productos.id')
                                ->select(DB::raw('SUM(detalle_ingresos.cantidad*detalle_ingresos.precio)  as total '))
                                ->where('detalle_ingresos.idingreso','=',$id)->get()[0];

      $numventa=Ingreso::select('num_comprobante')->where('id',$id)->get();
      $pdf = \PDF::loadView('pdf.compras.compra',['ingresos' => $detalles,'info'=>$data,'total'=> $total]);
      $pdf->setPaper('A4', 'portrait');
      return $pdf->stream();
    }
    public function filterCompra(Request $request) {
      $request = $request->all();
      $dateStart = $request['dateStart'];
      $dateEnd = $request['dateEnd'];
      $tipo_compra = $request['tipo_compra'];
      $num_comprobante = $request['comprobante'];
      $proveedor = $request['filterProveedor'];
      if ($proveedor == 'undefined') {
        $proveedor = null;
      }
      //die(json_encode($request));
      if ($tipo_compra == 'pagado') {
        $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
        'personas.tipo_documento','personas.num_documento',
        'ingresos.adelantoI','ingresos.pendienteI')
        ->where('ingresos.pendienteI','=','0')
        ->where('personas.id', 'like', '%'. $proveedor . '%')
        ->where('ingresos.fecha_hora','>=',$dateStart)
        ->where('ingresos.fecha_hora','<=',$dateEnd)
        ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
        ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->paginate(10);


      } else if($tipo_compra == 'pendiente'){
        //die(json_encode($request));

        $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
        'personas.tipo_documento','personas.num_documento',
        'ingresos.adelantoI','ingresos.pendienteI')
        ->where('ingresos.pendienteI','>','1')
        ->where('personas.id', 'like', '%'. $proveedor . '%')
        ->where('ingresos.fecha_hora','>=',$dateStart)
        ->where('ingresos.fecha_hora','<=',$dateEnd)
        ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
        ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->paginate(10);


      } else {
        $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
        'personas.tipo_documento','personas.num_documento',
        'ingresos.adelantoI','ingresos.pendienteI')
        ->where('personas.id', 'like', '%'. $proveedor . '%')
        ->where('ingresos.fecha_hora','>=',$dateStart)
        ->where('ingresos.fecha_hora','<=',$dateEnd)
        ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
        ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->paginate(10);

      }


      return [
          'pagination' => [
              'total'        => $ingresos->total(),
              'current_page' => $ingresos->currentPage(),
              'per_page'     => $ingresos->perPage(),
              'last_page'    => $ingresos->lastPage(),
              'from'         => $ingresos->firstItem(),
              'to'           => $ingresos->lastItem(),
          ],
          'ingresos' => $ingresos
      ];


    }
    public function generarPDF(Request $request) {
      $request = $request->all();
      $dateStart = $request['dateStart'];
      $dateEnd = $request['dateEnd'];
      $tipo_compra = $request['tipo_compra'];
      $num_comprobante = $request['comprobante'];
      $proveedor = $request['filterProveedor'];
      if ($proveedor == 'undefined') {
        $proveedor = null;
      }
      //die(json_encode($request));
      if ($tipo_compra == 'pagado') {
        $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->join('users','ingresos.idusuario','=','users.id')
          ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
          'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
          'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
          'personas.tipo_documento','personas.num_documento', 'ingresos.adelantoI as adelanto','ingresos.pendienteI as pendiente',
          'ingresos.adelantoI','ingresos.pendienteI')
          ->where('ingresos.pendienteI','=','0')
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

        $totales = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->select(DB::raw("SUM(ingresos.adelantoI) as total"))
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.pendienteI','=','0')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

          $proveedor = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->select("personas.nombre")
            ->where('personas.id', 'like', '%'. $proveedor . '%')
            ->where('ingresos.pendienteI','=','0')
            ->where('ingresos.created_at', 'like', '%'. $date . '%')
            ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
            ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

      } else if ($tipo_compra == 'pagado') {
        $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->join('users','ingresos.idusuario','=','users.id')
          ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
          'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
          'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
          'personas.tipo_documento','personas.num_documento','ingresos.adelantoI as adelanto','ingresos.pendienteI as pendiente',
          'ingresos.adelantoI','ingresos.pendienteI')
          ->where('ingresos.pendienteI','>','1')
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

        $totales = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->select(DB::raw("SUM(ingresos.adelantoI) as total"))
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.pendienteI','>','1')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

        $proveedor = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->select("personas.nombre")
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.pendienteI','>','1')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();
      } else {
        $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->join('users','ingresos.idusuario','=','users.id')
          ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
          'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
          'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
          'personas.tipo_documento','personas.num_documento','ingresos.adelantoI as adelanto','ingresos.pendienteI as pendiente',
          'ingresos.adelantoI','ingresos.pendienteI')
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

        $totales = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->select(DB::raw("SUM(ingresos.adelantoI) as total"))
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

        $proveedor = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->select("personas.nombre")
          ->where('personas.id', 'like', '%'. $proveedor . '%')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();
      }
      $user = Auth::user();
      $cliente = Persona::where('id', '=', $user->id)->get();
      $sucursal= Sucursal::where('id', '=', $user->idsucursal)->get();
      $pdf = \PDF::loadView('pdf.compras.reportProveedor',[ 'ventas' => $ingresos, 'totales' => $totales[0]->total,
                                                        'sucursal' => $sucursal[0], 'cliente'=> $cliente[0], 'proveedor'=> $proveedor[0]->nombre]);

      $pdf->setPaper('A4', 'portrait');
      return $pdf->stream();
    }
    public function listarProveedor() {
      $proveedores = Proveedor::join('personas','proveedores.id','=','personas.id')
      ->select('personas.id','personas.nombre','personas.num_documento')
      ->orderBy('personas.nombre', 'asc')->get();

      return ['proveedores' => $proveedores];
    }
}

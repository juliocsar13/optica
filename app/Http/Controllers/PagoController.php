<?php

namespace Optica\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Optica\Ingreso;
use Optica\DetalleIngreso;
use Optica\User;
use Optica\Persona;
use Optica\Sucursal;
use Optica\Notifications\NotifyAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
            'personas.tipo_documento','personas.num_documento',
            'ingresos.adelantoI','ingresos.pendienteI')
            ->where('ingresos.pendienteI','>','1')
            ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('ingresos.id', 'desc')->paginate(10);
        }
        else{
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
            'personas.tipo_documento','personas.num_documento',
            'ingresos.adelantoI','ingresos.pendienteI')
            ->where('ingresos.pendienteI','>','1')
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
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','personas.nombre','users.usuario',
        'ingresos.adelantoI','ingresos.pendienteI')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id', 'desc')->take(1)->get();

        return ['ingreso' => $ingreso];
    }
    public function pagoFilter(Request $request) {
      $request = $request->all();
      $dateStart = $request['dateStart'];
      $dateEnd = $request['dateEnd'];

      $tipo_comprobante = $request['tipo_comprobante'];
      $num_comprobante = $request['num_comprobante'];
      $type = $request['type'];

      if ($tipo_comprobante=='' && $dateStart == '' && $num_comprobante== ''){
          $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->join('users','ingresos.idusuario','=','users.id')
          ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
          'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
          'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
          'personas.tipo_documento','personas.num_documento',
          'ingresos.adelantoI','ingresos.pendienteI')
          ->where('ingresos.pendienteI','>','1')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();


          $totales = Ingreso::select(DB::raw("SUM(ingresos.pendienteI) as total"))
            ->where('ingresos.pendienteI','>','1')
            ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();
      }
      else{
          $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
          ->join('users','ingresos.idusuario','=','users.id')
          ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
          'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
          'ingresos.estado','ingresos.created_at','personas.nombre','users.usuario',
          'personas.tipo_documento','personas.num_documento',
          'ingresos.adelantoI','ingresos.pendienteI')
          ->where('ingresos.pendienteI','>','1')
          ->where('ingresos.fecha_hora','>=',$dateStart)
          ->where('ingresos.fecha_hora','<=',$dateEnd)
          ->where('ingresos.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
          ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();

          $totales = Ingreso::select(DB::raw("SUM(ingresos.pendienteI) as total"))
            ->where('ingresos.pendienteI','>','1')
            ->where('ingresos.fecha_hora','>=',$dateStart)
            ->where('ingresos.fecha_hora','<=',$dateEnd)
            ->where('ingresos.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
            ->where('ingresos.num_comprobante', 'like', '%'. $num_comprobante . '%')
            ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)->get();
      }
      if ($type=='filter') return [ 'ventas' => $ingresos ];
      if ($type=='report') {

        $user = Auth::user();
        $cliente = Persona::where('id', '=', $user->id)->get();
        $sucursal= Sucursal::where('id', '=', $user->idsucursal)->get();
        $pdf = \PDF::loadView('pdf.caja.pagar',[ 'ventas' => $ingresos, 'totales' => $totales[0]->total,
                                                          'sucursal' => $sucursal[0], 'cliente'=> $cliente[0]]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream();
      }
    } 
    public function generatePDF(Request $request) {
      if (!$request->ajax()) return redirect('/');

      $buscar = $request->buscar;
      $criterio = $request->criterio;

      if ($buscar==''){
          $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
          ->join('users','ventas.idusuario','=','users.id')
          ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
          'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
          'ventas.estado','ventas.created_at','ventas.adelanto','ventas.pendiente','personas.nombre','personas.tipo_documento','personas.num_documento',
          'users.usuario','ventas.idproveedor')
          ->where('ventas.pendiente','>','1')
          ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)
          ->orderBy('ventas.id', 'desc')->paginate(10);
      }
      else{
          $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
          ->join('users','ventas.idusuario','=','users.id')
          ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
          'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
          'ventas.estado','ventas.created_at','ventas.adelanto','ventas.pendiente','personas.nombre','personas.tipo_documento','personas.num_documento',
          'users.usuario','ventas.idproveedor')
          ->where('ventas.pendiente','>','1')
          ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
          ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)
          ->orderBy('ventas.id', 'desc')->paginate(10);
      }

    }
}

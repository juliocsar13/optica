<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Optica\Venta;
use Optica\DetalleVenta;
use Optica\User;
use Optica\Sucursal;

use Optica\Persona;

use Optica\Notifications\NotifyAdmin;
use Illuminate\Support\Facades\Auth;

class CobroController extends Controller
{
    public function index(Request $request)
    {
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
    public function obtenerCabecera(Request $request){
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
    public function cobroFilter(Request $request) {
      $request = $request->all();
      $dateEnd = $request['dateEnd'];
      $dateStart = $request['dateStart'];
      //  die(json_encode($request));
      $tipo_comprobante = $request['tipo_comprobante'];
      $num_comprobante = $request['num_comprobante'];
      $type = $request['type'];

      if ($tipo_comprobante=='' && $dateEnd == '' && $num_comprobante== ''){
          $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
          ->join('users','ventas.idusuario','=','users.id')
          ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
          'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
          'ventas.estado','ventas.created_at','ventas.adelanto','ventas.pendiente','personas.nombre','personas.tipo_documento','personas.num_documento',
          'users.usuario','ventas.idproveedor')
          ->where('ventas.pendiente','>','1')
          ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)->get();

          $totales = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->select(DB::raw("SUM(ventas.pendiente) as total"))
            ->where('ventas.pendiente','>','1')
            ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)->get();
      }
      else{
          $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
          ->join('users','ventas.idusuario','=','users.id')
          ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
          'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
          'ventas.estado','ventas.created_at','ventas.adelanto','ventas.pendiente','personas.nombre','personas.tipo_documento','personas.num_documento',
          'users.usuario','ventas.idproveedor')
          ->where('ventas.pendiente','>','1')
          ->where('ventas.fecha_hora','>=',$dateStart)
          ->where('ventas.fecha_hora','<=',$dateEnd)
          ->where('ventas.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
          ->where('ventas.num_comprobante', 'like', '%'. $num_comprobante . '%')
          ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)->get();


          $totales = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->select(DB::raw("SUM(ventas.pendiente) as total"))
            ->where('ventas.pendiente','>','1')
            ->where('ventas.fecha_hora','>=',$dateStart)
            ->where('ventas.fecha_hora','<=',$dateEnd)
            ->where('ventas.tipo_comprobante', 'like', '%'. $tipo_comprobante . '%')
            ->where('ventas.num_comprobante', 'like', '%'. $num_comprobante . '%')
            ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)->get();
      }
      if ($type=='filter') return [ 'ventas' => $ventas ];
      if ($type=='report') {
        $user = Auth::user();
        $cliente = Persona::where('id', '=', $user->id)->get();
        $sucursal= Sucursal::where('id', '=', $user->idsucursal)->get();
        $pdf = \PDF::loadView('pdf.caja.cobrar',[ 'ventas' => $ventas, 'totales' => $totales[0]->total,
                                                          'sucursal' => $sucursal[0], 'cliente'=> $cliente[0]]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream();

      }
    }

}

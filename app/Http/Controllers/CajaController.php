<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Optica\Venta;
use Optica\Caja;

use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    public function index(Request $request){

      $cajas = Caja::join('personas', 'cajas.idpersona', '=', 'personas.id')
                  ->select('cajas.id', 'personas.nombre as usuario', 'cajas.monto_inicial', 'cajas.created_at',
                  'cajas.estado', 'cajas.fecha_apertura','cajas.fecha_cierre', 'cajas.monto_final')
                  ->where('idsucursal', '=', Auth::user()->idsucursal)
                  ->orderBy('cajas.id', 'desc')
                  ->paginate(6);
      return [
          'pagination' => [
              'total'        => $cajas->total(),
              'current_page' => $cajas->currentPage(),
              'per_page'     => $cajas->perPage(),
              'last_page'    => $cajas->lastPage(),
              'from'         => $cajas->firstItem(),
              'to'           => $cajas->lastItem(),
          ],
          'cajas' => $cajas
      ];
    }
    public function store(Request $request) {
      if (!$request->ajax()) return redirect('/');
      $request = $request->all();
      $caja = new Caja();
      $mytime= Carbon::now('America/Lima');
      $caja->idpersona = $request['idpersona'];
      $caja->monto_inicial = $request['monto'];
      $caja->monto_final = $request['monto'];

      $caja->idsucursal = Auth::user()->idsucursal;
      //$caja->fecha_apertura = Carbon::now()->toDateTimeString();
      $caja->save();
    }
    public function activar(Request $request) {
      if (!$request->ajax()) return redirect('/');
      $request = $request->all();
      $mytime= Carbon::now('America/Lima');

      $caja = Caja::findOrFail($request['id']);
      $caja->estado = '1';
      $caja->fecha_apertura = $mytime->toDateTimeString();
      $caja->save();
    }
    public function desactivar(Request $request) {
      if (!$request->ajax()) return redirect('/');
      $request = $request->all();
      $mytime= Carbon::now('America/Lima');

      $caja = Caja::findOrFail($request['id']);
      $caja->estado = '0';
      $caja->fecha_cierre = $mytime->toDateTimeString();
      $caja->save();
    }
    public function verificar(Request $request) {
      if (!$request->ajax()) return redirect('/');
      $request = $request->all();
      $caja = Caja::select('*')
        ->where('estado' ,'=', '1')
        ->where('idsucursal', '=', Auth::user()->idsucursal)
        ->get();
      if (isset($caja[0])) {
        return ['result' => 1 ];
      } else {
        return ['result' => 0 ];
      }

    }
    public function saldo(Request $request) {
      if (!$request->ajax()) return redirect('/');
      $request = $request->all();
      $caja = Caja::select('*')
        ->where('estado' ,'=', '1')
        ->where('idsucursal', '=', Auth::user()->idsucursal)
        ->get();
        //die(json_encode($caja[0]->monto_final));
        //die(json_encode($request['saldo']));
      if ($caja[0]->monto_final<$request['saldo']) {
        return ['result' => 1 ];
      } else {
        return ['result' => 0 ];
      }
    }


}

<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Optica\Caja;
use Optica\Movimiento;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
      $movimientos = Movimiento::join('personas', 'movimientos.idpersona', '=', 'personas.id')
                  ->select('movimientos.id', 'personas.nombre as usuario', 'movimientos.descripcion',
                            'movimientos.created_at', 'movimientos.tipo', 'movimientos.created_at',
                            'movimientos.monto')
                  ->where('idsucursal', '=', Auth::user()->idsucursal)
                  ->orderBy('movimientos.id', 'desc')
                  ->paginate(6);
      return [
          'pagination' => [
              'total'        => $movimientos->total(),
              'current_page' => $movimientos->currentPage(),
              'per_page'     => $movimientos->perPage(),
              'last_page'    => $movimientos->lastPage(),
              'from'         => $movimientos->firstItem(),
              'to'           => $movimientos->lastItem(),
          ],
          'movimientos' => $movimientos
      ];
    }
    public function store(Request $request)
    {
      if (!$request->ajax()) return redirect('/');
      $request = $request->all();
      $movimiento = new Movimiento();
      $movimiento->idpersona = $request['idpersona'];
      $movimiento->monto = $request['monto'];
      $movimiento->descripcion = $request['descripcion'];
      $movimiento->tipo = $request['movimiento'];

      $caja = Caja::select('*')
        ->where('estado' ,'=', '1')
        ->where('idsucursal', '=', Auth::user()->idsucursal)
        ->get();

      $movimiento->idcaja = $caja[0]->id;
      $movimiento->idsucursal = Auth::user()->idsucursal;
      $movimiento->save();


      $caja1 = new Caja();
      $caja1 = Caja::findOrFail($caja[0]->id);
      if ($request['movimiento'] == 0) {
        $caja1->monto_final =   $caja[0]->monto_final + $request['monto'];
      } else {
        $caja1->monto_final =   $caja[0]->monto_final - $request['monto'];
      }
      $caja1->save();
    }
    public function update(Request $request)
    {
      if (!$request->ajax()) return redirect('/');
      $movimiento = new Movimiento();
      //die(json_encode($request->all()));
      $movimiento = Movimiento::findOrFail($request->id);
      $movimiento->descripcion = $request->descripcion;

      $movimiento->save();
    }
}

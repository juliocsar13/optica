<?php

namespace Optica\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Optica\Exports\KardexExport;
use Illuminate\Http\Request;
use Optica\Producto;
use Optica\Persona;
use Optica\Sucursal;
use Optica\Familia;
use Optica\DetalleVenta;
use Optica\DetalleIngreso;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KardexController extends Controller
{
    public function listarProducto() {
      $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
      ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre','familias.nombre as nombre_familia',
      'productos.precio_venta','productos.stock','productos.descripcion','productos.condicion')
      ->where('productos.idsucursal', '=', Auth::user()->idsucursal)
      ->orderBy('productos.id', 'desc')->paginate();
      return [
        'productos' => $productos
      ];
    }
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $request = $request->all();
        $date = $request['date'];

        if ($request['producto'] == 'undefined') {
          $request['producto'] = null;
        }
        $productos = \Optica\Producto::with(['detalle_ventas', 'detalle_ingresos'])
        ->get();

          foreach ($productos as $key => $value) {
            $detalle_ventas = $value['detalle_ventas'];
            $detalle_ingresos = $value['detalle_ingresos'];
            $productos[$key]['familia'] = Familia::where('id', '=', $value['idfamilia'])->get()[0]->nombre;
            $productos[$key]['empresa'] = Sucursal::where('id', '=', $value['idsucursal'])->get()[0]->razon_social_s;

            if (count($detalle_ventas) > 0) {
              $v_cantidad = 0;
              $v_total = 0;
              $total = 0;
              $count = 0;
              $v_precio = 0;
              foreach ($detalle_ventas as $key1 => $value) {
                $v_cantidad = $v_cantidad + $value['cantidad'];
                $v_precio = $v_precio + $value['precio'];
                $total = $value['cantidad']*$value['precio'];
                $v_total = $v_total + $total;
                if ($value['precio']>0) {
                  $count = $count +1;
                }
              }
              $productos[$key]['v_cantidad'] = $v_cantidad;
              $productos[$key]['v_precio'] = $v_precio/$count;
              $productos[$key]['v_total'] = $v_total;
            }
            else {
              $productos[$key]['v_cantidad'] = 0.00;
              $productos[$key]['v_precio'] = 0.00;
              $productos[$key]['v_total'] = 0.00;
            }
            if (count($detalle_ingresos) > 0) {
              $i_cantidad = 0;
              $i_total = 0;
              $total = 0;
              $i_precio=0;
              $count = 0;
              foreach ($detalle_ingresos as $key2 => $value) {
                $i_cantidad = $i_cantidad + $value['cantidad'];
                //$i_precio = $value['precio'];
                $i_precio = $i_precio + $value['precio'];

                $total = $value['cantidad']*$value['precio'];
                $i_total = $i_total + $total;
                if ($value['precio']>0) {
                  $count = $count +1;
                }
              }
              $productos[$key]['i_cantidad'] = $i_cantidad;
              $productos[$key]['i_precio'] = $i_precio/$count;
              $productos[$key]['i_total'] = $i_total;
            }
            else {
              $productos[$key]['i_cantidad'] = 0.00;
              $productos[$key]['i_precio'] = 0.00;
              $productos[$key]['i_total'] = 0.00;
            }
          }

        return [

            'productos' => $productos
        ];
    }
    public function filterKardex(Request $request) {
      if (!$request->ajax()) return redirect('/');
      $request = $request->all();
      $dateStart = $request['dateStart'];
      $dateEnd = $request['dateEnd'];
      if ($request['producto'] == 'undefined') {
        $request['producto'] = null;
      }

      $detalle_ingresos = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                            ->select('ingresos.created_at as fecha', 'ingresos.num_comprobante', 'ingresos.serie_comprobante',
                                      'detalle_ingresos.cantidad as i_cantidad', 'detalle_ingresos.precio as i_precio',
                                      DB::raw('detalle_ingresos.cantidad*detalle_ingresos.precio as i_total'))
                                      ->where('detalle_ingresos.idproducto', 'like', '%'. $request['producto'] . '%')
                                      ->where('detalle_ingresos.created_at','>=',$dateStart)
                                      ->where('detalle_ingresos.created_at','<=',$dateEnd)
                                      ->groupBy('detalle_ingresos.id')->get();

      foreach ($detalle_ingresos as $key => $value) {
        $detalle_ingresos[$key]['tipo'] = 'compra';
      }
      $total_ingreso = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                            ->select(DB::raw('SUM(detalle_ingresos.cantidad*detalle_ingresos.precio) as total'))
                            ->where('detalle_ingresos.idproducto', 'like', '%'. $request['producto'] . '%')
                            ->where('detalle_ingresos.created_at','>=',$dateStart)
                            ->where('detalle_ingresos.created_at','<=',$dateEnd)->get()[0]->total;

      $total_ingreso_cantidad = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                                  ->select(DB::raw('SUM(detalle_ingresos.cantidad) as cantidad'))
                                  ->where('detalle_ingresos.idproducto', 'like', '%'. $request['producto'] . '%')
                                  ->where('detalle_ingresos.created_at','>=',$dateStart)
                                  ->where('detalle_ingresos.created_at','<=',$dateEnd)->get()[0]->cantidad;

      $detalle_ventas = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                            ->select('ventas.created_at as fecha', 'ventas.num_comprobante', 'ventas.serie_comprobante',
                              'detalle_ventas.cantidad as v_cantidad', 'detalle_ventas.precio as v_precio',
                              DB::raw('detalle_ventas.cantidad*detalle_ventas.precio as v_total'))
                              ->where('detalle_ventas.idproducto', 'like', '%'. $request['producto'] . '%')
                              ->where('detalle_ventas.created_at','>=',$dateStart)
                              ->where('detalle_ventas.created_at','<=',$dateEnd)
                              ->groupBy('detalle_ventas.id')->get();

      foreach ($detalle_ventas as $key => $value) {
        $detalle_ventas[$key]['tipo'] = 'venta';
      }
      $total_salida = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                        ->select(DB::raw('SUM(detalle_ventas.cantidad*detalle_ventas.precio) as total'))
                        ->where('detalle_ventas.idproducto', 'like', '%'. $request['producto'] . '%')
                        ->where('detalle_ventas.created_at','>=',$dateStart)
                        ->where('detalle_ventas.created_at','<=',$dateEnd)->get()[0]->total;

      $total_salida_cantidad = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                                ->select(DB::raw('SUM(detalle_ventas.cantidad) as cantidad'))
                                ->where('detalle_ventas.idproducto', 'like', '%'. $request['producto'] . '%')
                                ->where('detalle_ventas.created_at','>=',$dateStart)
                                ->where('detalle_ventas.created_at','<=',$dateEnd)->get()[0]->cantidad;


      $productos = $detalle_ventas->concat($detalle_ingresos)->sortBy('fecha')->values()->all();

      foreach ($productos as $key => $value) {
        if ($key == 0 && $value['tipo'] == 'compra') {
          $productos[$key]['t_cantidad'] = $value['i_cantidad'];
          $productos[$key]['t_precio'] = $value['i_precio'];
          $productos[$key]['t_total'] = $value['i_total'];
          $productos[$key]['v_cantidad'] = '';
          $productos[$key]['v_precio'] = '';
          $productos[$key]['v_total'] = '';
        }
        if ($key == 0 && $value['tipo'] == 'venta') {
          $productos[$key]['t_cantidad'] = $value['v_cantidad'];
          $productos[$key]['t_precio'] = $value['v_precio'];
          $productos[$key]['t_total'] = $value['v_total'];
          $productos[$key]['i_cantidad'] = '';
          $productos[$key]['i_precio'] = '';
          $productos[$key]['i_total'] = '';
        }
        if ($key > 0) {
          if ($productos[$key]['tipo'] == 'compra') {
              $productos[$key]['t_cantidad'] = $productos[$key-1]['t_cantidad']+$value['i_cantidad'];
              $productos[$key]['t_precio'] = $value['i_precio'];
              $productos[$key]['t_total'] = $productos[$key-1]['t_total'] + $value['i_total'];
              $productos[$key]['v_cantidad'] = '';
              $productos[$key]['v_precio'] = '';
              $productos[$key]['v_total'] = '';
          } else {
            $productos[$key]['t_cantidad'] = $productos[$key-1]['t_cantidad'] - $value['v_cantidad'];
            $productos[$key]['t_precio'] = $value['v_precio'];
            $productos[$key]['t_total'] = $productos[$key-1]['t_total'] - $value['v_total'];
            $productos[$key]['i_cantidad'] = '';
            $productos[$key]['i_precio'] = '';
            $productos[$key]['i_total'] = '';
          }
        }
      }
      //die($ditc);
      return [
          'productos' => $productos,
          'total_ingreso' => $total_ingreso,
          'total_ingreso_cantidad' => $total_ingreso_cantidad,
          'total_salida' => $total_salida,
          'total_salida_cantidad' => $total_salida_cantidad
      ];
    }
    public function excel(Request $request) {
      $request = $request->all();
      return Excel::download(new KardexExport($request), 'kardex.xlsx');
    }
    public function generatePDF(Request $request) {
      $request = $request->all();
      $dateStart = $request['dateStart'];
      $dateEnd = $request['dateEnd'];
      if ($request['producto'] == 'undefined') {
        $request['producto'] = null;
      }

      $detalle_ingresos = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                            ->select('ingresos.created_at as fecha', 'ingresos.num_comprobante', 'ingresos.serie_comprobante',
                                      'detalle_ingresos.cantidad as i_cantidad', 'detalle_ingresos.precio as i_precio',
                                      DB::raw('detalle_ingresos.cantidad*detalle_ingresos.precio as i_total'))
                                      ->where('detalle_ingresos.idproducto', 'like', '%'. $request['producto'] . '%')
                                      ->where('detalle_ingresos.created_at','>=',$dateStart)
                                      ->where('detalle_ingresos.created_at','<=',$dateEnd)
                                      ->groupBy('detalle_ingresos.id')->get();

      foreach ($detalle_ingresos as $key => $value) {
        $detalle_ingresos[$key]['tipo'] = 'compra';
      }
      $total_ingreso = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                            ->select(DB::raw('SUM(detalle_ingresos.cantidad*detalle_ingresos.precio) as total'))
                            ->where('detalle_ingresos.idproducto', 'like', '%'. $request['producto'] . '%')
                            ->where('detalle_ingresos.created_at','>=',$dateStart)
                            ->where('detalle_ingresos.created_at','<=',$dateEnd)->get()[0]->total;

      $total_ingreso_cantidad = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                                  ->select(DB::raw('SUM(detalle_ingresos.cantidad) as cantidad'))
                                  ->where('detalle_ingresos.idproducto', 'like', '%'. $request['producto'] . '%')
                                  ->where('detalle_ingresos.created_at','>=',$dateStart)
                                  ->where('detalle_ingresos.created_at','<=',$dateEnd)->get()[0]->cantidad;

      $detalle_ventas = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                            ->select('ventas.created_at as fecha', 'ventas.num_comprobante', 'ventas.serie_comprobante',
                              'detalle_ventas.cantidad as v_cantidad', 'detalle_ventas.precio as v_precio',
                              DB::raw('detalle_ventas.cantidad*detalle_ventas.precio as v_total'))
                              ->where('detalle_ventas.idproducto', 'like', '%'. $request['producto'] . '%')
                              ->where('detalle_ventas.created_at','>=',$dateStart)
                              ->where('detalle_ventas.created_at','<=',$dateEnd)
                              ->groupBy('detalle_ventas.id')->get();

      foreach ($detalle_ventas as $key => $value) {
        $detalle_ventas[$key]['tipo'] = 'venta';
      }
      $total_salida = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                        ->select(DB::raw('SUM(detalle_ventas.cantidad*detalle_ventas.precio) as total'))
                        ->where('detalle_ventas.idproducto', 'like', '%'. $request['producto'] . '%')
                        ->where('detalle_ventas.created_at','>=',$dateStart)
                        ->where('detalle_ventas.created_at','<=',$dateEnd)->get()[0]->total;

      $total_salida_cantidad = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                                ->select(DB::raw('SUM(detalle_ventas.cantidad) as cantidad'))
                                ->where('detalle_ventas.idproducto', 'like', '%'. $request['producto'] . '%')
                                ->where('detalle_ventas.created_at','>=',$dateStart)
                                ->where('detalle_ventas.created_at','<=',$dateEnd)->get()[0]->cantidad;


      $productos = $detalle_ventas->concat($detalle_ingresos)->sortBy('fecha')->values()->all();

      foreach ($productos as $key => $value) {
        if ($key == 0 && $value['tipo'] == 'compra') {
          $productos[$key]['t_cantidad'] = $value['i_cantidad'];
          $productos[$key]['t_precio'] = $value['i_precio'];
          $productos[$key]['t_total'] = $value['i_total'];
          $productos[$key]['v_cantidad'] = '';
          $productos[$key]['v_precio'] = '';
          $productos[$key]['v_total'] = '';
        }
        if ($key == 0 && $value['tipo'] == 'venta') {
          $productos[$key]['t_cantidad'] = $value['v_cantidad'];
          $productos[$key]['t_precio'] = $value['v_precio'];
          $productos[$key]['t_total'] = $value['v_total'];
          $productos[$key]['i_cantidad'] = '';
          $productos[$key]['i_precio'] = '';
          $productos[$key]['i_total'] = '';
        }
        if ($key > 0) {
          if ($productos[$key]['tipo'] == 'compra') {
              $productos[$key]['t_cantidad'] = $productos[$key-1]['t_cantidad']+$value['i_cantidad'];
              $productos[$key]['t_precio'] = $value['i_precio'];
              $productos[$key]['t_total'] = $productos[$key-1]['t_total'] + $value['i_total'];
              $productos[$key]['v_cantidad'] = '';
              $productos[$key]['v_precio'] = '';
              $productos[$key]['v_total'] = '';
          } else {
            $productos[$key]['t_cantidad'] = $productos[$key-1]['t_cantidad'] - $value['v_cantidad'];
            $productos[$key]['t_precio'] = $value['v_precio'];
            $productos[$key]['t_total'] = $productos[$key-1]['t_total'] - $value['v_total'];
            $productos[$key]['i_cantidad'] = '';
            $productos[$key]['i_precio'] = '';
            $productos[$key]['i_total'] = '';
          }
        }
      }

    
      $user = Auth::user();
      $cliente = Persona::where('id', '=', $user->id)->get();
      $sucursal= Sucursal::where('id', '=', $user->idsucursal)->get();

      $pdf = \PDF::loadView('pdf.kardex.kardex',[
                                                  'producto' => $request['producto'],
                                                  'date' => $dateStart,
                                                  'productos' => $productos,
                                                  'sucursal' => $sucursal[0],
                                                  'cliente' => $cliente[0],
                                                  'total_ingreso' => $total_ingreso,
                                                  'total_ingreso_cantidad' => $total_ingreso_cantidad,
                                                  'total_salida' => $total_salida,
                                                  'total_salida_cantidad' => $total_salida_cantidad
                                                ]);

      $pdf->setPaper('A4', 'landscape');
      return $pdf->stream();
    }
}


/*
      foreach ($productos as $key => $value) {
        $detalle_ventas = collect($value['detalle_ventas']);
        $detalle_ingresos = collect($value['detalle_ingresos']);
        $productos[$key]['familia'] = Familia::where('id', '=', $value['idfamilia'])->get()[0]->nombre;
        $productos[$key]['empresa'] = Sucursal::where('id', '=', $value['idsucursal'])->get()[0]->razon_social_s;
        if (isset($date)) {

          $detalle_ingresos = $detalle_ingresos->where('created_at', $date.' '.'00:00:00');
          $detalle_ventas = $detalle_ventas->where('created_at', $date.' '.'00:00:00');
        }

        if (count($detalle_ventas) > 0) {
          $v_cantidad = 0;
          $v_total = 0;
          $total = 0;
          $count = 0;
          $v_precio = 0;
          foreach ($detalle_ventas as $key1 => $value) {
            $v_cantidad = $v_cantidad + $value['cantidad'];
            $v_precio = $v_precio + $value['precio'];
            $total = $value['cantidad']*$value['precio'];
            $v_total = $v_total + $total;
            if ($value['precio']>0) {
              $count = $count +1;
            }
          }
          $productos[$key]['v_cantidad'] = $v_cantidad;
          $productos[$key]['v_precio'] = $v_precio/$count;
          $productos[$key]['v_total'] = $v_total;
        }
        else {
          $productos[$key]['v_cantidad'] = 0.00;
          $productos[$key]['v_precio'] = 0.00;
          $productos[$key]['v_total'] = 0.00;
        }

        if (count($detalle_ingresos) > 0) {
          $i_cantidad = 0;
          $i_total = 0;
          $total = 0;
          $count = 0;
          $i_precio = 0;
          foreach ($detalle_ingresos as $key2 => $value) {
            $i_cantidad = $i_cantidad + $value['cantidad'];
            //$i_precio = $value['precio'];
            $i_precio = $i_precio + $value['precio'];

            $total = $value['cantidad']*$value['precio'];
            $i_total = $i_total + $total;
            if ($value['precio']>0) {
              $count = $count +1;
            }
          }
          $productos[$key]['i_cantidad'] = $i_cantidad;
          $productos[$key]['i_precio'] = $i_precio/$count;
          $productos[$key]['i_total'] = $i_total;
        }
        else {
          $productos[$key]['i_cantidad'] = 0.00;
          $productos[$key]['i_precio'] = 0.00;
          $productos[$key]['i_total'] = 0.00;
        }
      }
*/

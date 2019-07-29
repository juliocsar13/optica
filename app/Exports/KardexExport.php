<?php

namespace Optica\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Http\Request;
use Optica\Producto;
use Optica\Familia;
use Optica\Sucursal;
use Optica\DetalleVenta;
use Optica\DetalleIngreso;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class KardexExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;
    public function __construct($request)
     {
         $this->producto = $request['producto'];
         $this->dateStart = $request['dateStart'];
         $this->dateEnd = $request['dateEnd'];

     }
    public function collection()
    {
      $dateStart = $this->dateStart;
      $dateEnd = $this->dateEnd;
      $producto = $this->producto;

      if ($producto == 'undefined') {
        $producto = null;
      }

      $detalle_ingresos = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                            ->select('ingresos.created_at as fecha', 'ingresos.num_comprobante', 'ingresos.serie_comprobante',
                                      'detalle_ingresos.cantidad as i_cantidad', 'detalle_ingresos.precio as i_precio',
                                      DB::raw('detalle_ingresos.cantidad*detalle_ingresos.precio as i_total'))
                                      ->where('detalle_ingresos.idproducto', 'like', '%'. $producto . '%')
                                      ->where('detalle_ingresos.created_at','>=',$dateStart)
                                      ->where('detalle_ingresos.created_at','<=',$dateEnd)
                                      ->groupBy('detalle_ingresos.id')->get();

      foreach ($detalle_ingresos as $key => $value) {
        $detalle_ingresos[$key]['tipo'] = 'compra';
      }
      $total_ingreso = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                            ->select(DB::raw('SUM(detalle_ingresos.cantidad*detalle_ingresos.precio) as total'))
                            ->where('detalle_ingresos.idproducto', 'like', '%'. $producto . '%')
                            ->where('detalle_ingresos.created_at','>=',$dateStart)
                            ->where('detalle_ingresos.created_at','<=',$dateEnd)->get()[0]->total;

      $total_ingreso_cantidad = DetalleIngreso::join('ingresos', 'ingresos.id' ,'=', 'detalle_ingresos.idingreso')
                                  ->select(DB::raw('SUM(detalle_ingresos.cantidad) as cantidad'))
                                  ->where('detalle_ingresos.idproducto', 'like', '%'. $producto . '%')
                                  ->where('detalle_ingresos.created_at','>=',$dateStart)
                                  ->where('detalle_ingresos.created_at','<=',$dateEnd)->get()[0]->cantidad;

      $detalle_ventas = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                            ->select('ventas.created_at as fecha', 'ventas.num_comprobante', 'ventas.serie_comprobante',
                              'detalle_ventas.cantidad as v_cantidad', 'detalle_ventas.precio as v_precio',
                              DB::raw('detalle_ventas.cantidad*detalle_ventas.precio as v_total'))
                              ->where('detalle_ventas.idproducto', 'like', '%'. $producto . '%')
                              ->where('detalle_ventas.created_at','>=',$dateStart)
                              ->where('detalle_ventas.created_at','<=',$dateEnd)
                              ->groupBy('detalle_ventas.id')->get();

      foreach ($detalle_ventas as $key => $value) {
        $detalle_ventas[$key]['tipo'] = 'venta';
      }
      $total_salida = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                        ->select(DB::raw('SUM(detalle_ventas.cantidad*detalle_ventas.precio) as total'))
                        ->where('detalle_ventas.idproducto', 'like', '%'. $producto . '%')
                        ->where('detalle_ventas.created_at','>=',$dateStart)
                        ->where('detalle_ventas.created_at','<=',$dateEnd)->get()[0]->total;

      $total_salida_cantidad = DetalleVenta::join('ventas', 'ventas.id' ,'=', 'detalle_ventas.idventa')
                                ->select(DB::raw('SUM(detalle_ventas.cantidad) as cantidad'))
                                ->where('detalle_ventas.idproducto', 'like', '%'. $producto . '%')
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

      $collection = collect();
      foreach ($productos as $key => $value) {
        $producto = $value->only('fecha','serie_comprobante','num_comprobante', 'i_cantidad', 'i_precio', 'i_total',
        'v_cantidad', 'v_precio', 'v_total', 't_cantidad','t_precio','t_total');
        $collection->push($producto);
      }
      return $collection;
    }


    public function headings(): array
    {
      return [
          'fecha', 'serie' ,'comprobante',
          'i_cantidad', 'i_precio', 'i_total',
          'v_cantidad', 'v_precio', 'v_total',
          't_cantidad', 't_precio', 't_total'
      ];
  }
  public function registerEvents(): array
  {
      return [
          AfterSheet::class    => function(AfterSheet $event) {
              $cellRange = 'A1:W1'; // All headers
              $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
          },
      ];
  }
}

<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Optica\Venta;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
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
            'ventas.estado','ventas.created_at','ventas.updated_at','ventas.forma_pago','ventas.pendiente','ventas.adelanto','personas.nombre',
            'personas.tipo_documento','personas.num_documento','users.usuario')
            ->where('ventas.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('ventas.id', 'desc')->paginate(6);
        }
        else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
            'ventas.estado','ventas.created_at','ventas.updated_at','ventas.forma_pago','ventas.pendiente','ventas.adelanto','personas.nombre',
            'personas.tipo_documento','personas.num_documento','users.usuario')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('estado','=','Registrado')
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
}

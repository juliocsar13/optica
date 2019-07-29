<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Optica\Ingreso;
use Optica\DetalleIngreso;
use Optica\User;
use Optica\Notifications\NotifyAdmin;
use Illuminate\Support\Facades\Auth;

class EgresoController extends Controller
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
            'ingresos.estado','ingresos.created_at','personas.nombre','personas.tipo_documento','personas.num_documento','users.usuario',
            'ingresos.adelantoI','ingresos.updated_at')
            ->where('ingresos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('ingresos.id', 'desc')->paginate(10);
        }    
        else{
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','ingresos.created_at','personas.nombre','personas.tipo_documento','personas.num_documento','users.usuario',
            'ingresos.adelantoI','ingresos.updated_at')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('estado','=','Registrado')
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
}

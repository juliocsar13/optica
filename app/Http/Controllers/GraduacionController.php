<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Optica\Graduacion;

class GraduacionController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $graduaciones = Graduacion::orderBy('id', 'desc')->paginate(10);
        }    
        else{
            $graduaciones = Graduacion::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'         => $graduaciones->total(),
                'current_page'  => $graduaciones->currentPage(),
                'per_page'      => $graduaciones->perPage(),
                'last_page'     => $graduaciones->lastPage(),
                'from'          => $graduaciones->firstItem(),
                'to'            => $graduaciones->lastItem(),
            ],
            'graduaciones' => $graduaciones
        ];        
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $graduacion = new Graduacion();
        $graduacion->nombre = $request->nombre;
        $graduacion->valor = $request->valor;
        $graduacion->condicion = '1';
        $graduacion->save();

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $graduacion = Graduacion::findOrFail($request->id);
        $graduacion->nombre = $request->nombre;    
        $graduacion->valor = $request->valor;
        $graduacion->condicion = '1';
        $graduacion->save();
    }
    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $graduacion = Graduacion::findOrFail($request->id);
        $graduacion->condicion = '0';
        $graduacion->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $graduacion = Graduacion::findOrFail($request->id);
        $graduacion->condicion = '1';
        $graduacion->save();
    }

    public function selectEsfera(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $esferas = Graduacion::where('condicion','=','1')
        ->where('graduaciones.nombre','=','ESFERA')
        ->orWhere('graduaciones.valor', 'like'. $filtro . '%')
        ->select('graduaciones.id','graduaciones.nombre','graduaciones.valor')
        ->orderBy('graduaciones.valor', 'asc')->get();

        return ['esferas' => $esferas];
    }

    public function selectCilindro(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $cilindros = Graduacion::where('condicion','=','1')
        ->where('graduaciones.nombre','=','CILINDRO')
        ->orwhere('graduaciones.valor', 'like'. $filtro . '%')
        ->select('graduaciones.id', 'graduaciones.nombre', 'graduaciones.valor')
        ->orderBy('graduaciones.valor', 'asc')->get();

        return ['cilindros' => $cilindros];
    }

    public function selectEje(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $ejes = Graduacion::where('condicion','=','1')
        ->where('graduaciones.nombre','=','Eje')
        ->orwhere('graduaciones.valor', 'like'. $filtro . '%')
        ->select('graduaciones.id', 'graduaciones.nombre', 'graduaciones.valor')
        ->orderBy('graduaciones.valor', 'asc')->get();

        return ['ejes' => $ejes];
    }

    public function selectAdd(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $adds = Graduacion::where('condicion','=','1')
        ->where('graduaciones.nombre','=','Adicion')
        ->orwhere('graduaciones.valor', 'like'. $filtro . '%')
        ->select('graduaciones.id', 'graduaciones.nombre', 'graduaciones.valor')
        ->orderBy('graduaciones.valor', 'asc')->get();

        return ['adds' => $adds];
    }

    public function selectDip(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $dips = Graduacion::where('condicion','=','1')
        ->where('graduaciones.nombre','=','Distancia Interpupilar')
        ->orwhere('graduaciones.valor', 'like'. $filtro . '%')
        ->select('graduaciones.id', 'graduaciones.nombre', 'graduaciones.valor')
        ->orderBy('graduaciones.valor', 'asc')->get();

        return ['dips' => $dips];
    }

    public function selectAv(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $avs = Graduacion::where('condicion','=','1')
        ->where('graduaciones.nombre','=','Agudeza Visual')
        ->orwhere('graduaciones.valor', 'like'. $filtro . '%')
        
        ->select('graduaciones.id', 'graduaciones.nombre', 'graduaciones.valor')
        ->orderBy('graduaciones.valor', 'asc')->get();

        return ['avs' => $avs];
    }
}
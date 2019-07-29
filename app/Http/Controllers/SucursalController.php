<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Optica\Sucursal;

class SucursalController extends Controller
{    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $sucursales = Sucursal::orderBy('id', 'asc')->paginate(10);
        }    
        else{
            $sucursales = Sucursal::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'asc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'         => $sucursales->total(),
                'current_page'  => $sucursales->currentPage(),
                'per_page'      => $sucursales->perPage(),
                'last_page'     => $sucursales->lastPage(),
                'from'          => $sucursales->firstItem(),
                'to'            => $sucursales->lastItem(),
            ],
            'sucursales' => $sucursales
        ];        
    }

    // public function selectFamilia(Request $request){
    //     if (!$request->ajax()) return redirect('/');
    //     $familias = Familia::where('condicion','=','1')
    //     ->select('id','nombre')->orderBy('nombre', 'asc')->get();
    //     return ['familias' =>$familias];
    // }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = new Sucursal();
        $sucursal->razon_social_s = $request->razon_social_s;
        $sucursal->tipo_documento_s = $request->tipo_documento_s;
        $sucursal->num_documento_s = $request->num_documento_s;
        $sucursal->direccion_s = $request->direccion_s;
        $sucursal->telefono_s = $request->telefono_s;
        $sucursal->email_s = $request->email_s;
        $sucursal->representante_s = $request->representante_s;
        $sucursal->estado = '1';
        $sucursal->save();

    }
    
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = Sucursal::findOrFail($request->id);
        $sucursal->razon_social_s = $request->razon_social_s;
        $sucursal->tipo_documento_s = $request->tipo_documento_s;
        $sucursal->num_documento_s = $request->num_documento_s;
        $sucursal->direccion_s = $request->direccion_s;
        $sucursal->telefono_s = $request->telefono_s;
        $sucursal->email_s = $request->email_s;
        $sucursal->representante_s = $request->representante_s;
        $sucursal->estado = '1';
        $sucursal->save();
    }
    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = Sucursal::findOrFail($request->id);
        $sucursal->estado = '0';
        $sucursal->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = Sucursal::findOrFail($request->id);
        $sucursal->estado = '1';
        $sucursal->save();
    }

    public function selectSucursal(Request $request)
    {
        $sucursales = Sucursal::where('estado', '=', '1')
        ->select('id', 'razon_social_s')
        ->orderBy('razon_social_s', 'asc')->get();

        return ['sucursales' => $sucursales];
    }
}
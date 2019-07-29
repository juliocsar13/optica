<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Optica\Empresa;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $empresas = Empresa::orderBy('id', 'desc')->paginate(10);
        }    
        else{
            $empresas = Empresa::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'         => $empresas->total(),
                'current_page'  => $empresas->currentPage(),
                'per_page'      => $empresas->perPage(),
                'last_page'     => $empresas->lastPage(),
                'from'          => $empresas->firstItem(),
                'to'            => $empresas->lastItem(),
            ],
            'empresas' => $empresas
        ];        
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $empresa = new Empresa();
        $empresa->direccion_e = $request->direccion_e;
        $empresa->email_e = $request->email_e;
        $empresa->nombre_e = $request->nombre_e;
        $empresa->razon_social = $request->razon_social;
        $empresa->representante = $request->representante;
        $empresa->ruc_e = $request->ruc_e;
        $empresa->telefono_e = $request->telefono_e;        
        $empresa->save();

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $empresa = Empresa::findOrFail($request->id);
        $empresa->direccion_e = $request->direccion_e;
        $empresa->email_e = $request->email_e;
        $empresa->nombre_e = $request->nombre_e;
        $empresa->razon_social = $request->razon_social;
        $empresa->representante = $request->representante;
        $empresa->ruc_e = $request->ruc_e;
        $empresa->telefono_e = $request->telefono_e;
        $empresa->save();
    }
}
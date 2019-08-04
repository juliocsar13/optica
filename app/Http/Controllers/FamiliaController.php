<?php

namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Optica\Familia;

class FamiliaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $familias = Familia::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $familias = Familia::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'         => $familias->total(),
                'current_page'  => $familias->currentPage(),
                'per_page'      => $familias->perPage(),
                'last_page'     => $familias->lastPage(),
                'from'          => $familias->firstItem(),
                'to'            => $familias->lastItem(),
            ],
            'familias' => $familias
        ];
    }

    public function selectFamilia(Request $request){
        if (!$request->ajax()) return redirect('/');
        $familias = Familia::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre', 'asc')->get();
        return ['familias' => $familias];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $familia = new Familia();
        $familia->nombre = $request->nombre;
        $familia->descripcion = $request->descripcion;
        $familia->condicion = '1';
        $familia->save();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $familia = Familia::findOrFail($request->id);
        $familia->nombre = $request->nombre;
        $familia->descripcion = $request->descripcion;
        $familia->condicion = '1';
        $familia->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $familia = Familia::findOrFail($request->id);
        $familia->condicion = '0';
        $familia->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $familia = Familia::findOrFail($request->id);
        $familia->condicion = '1';
        $familia->save();
    }
}

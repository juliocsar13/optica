<?php namespace Optica\Http\Controllers;

use Illuminate\Http\Request;
use Optica\Producto;
use Illuminate\Support\Facades\Auth;
use Optica\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Optica\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
            ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre','familias.nombre as nombre_familia',
            'productos.precio_venta','productos.stock','productos.descripcion','productos.condicion','productos.idsucursal')
            ->where('productos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{
            $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
            ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre','familias.nombre as nombre_familia',
            'productos.precio_venta','productos.stock','productos.descripcion','productos.condicion')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('productos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('productos.id', 'desc')->paginate(10);
        }


        return [
            'pagination' => [
                'total'         => $productos->total(),
                'current_page'  => $productos->currentPage(),
                'per_page'      => $productos->perPage(),
                'last_page'     => $productos->lastPage(),
                'from'          => $productos->firstItem(),
                'to'            => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
    }
    public function listarProducto(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
            ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre','familias.nombre as nombre_familia','productos.precio_venta','productos.stock','productos.descripcion','productos.condicion')
            ->where('productos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{
            $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
            ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre','familias.nombre as nombre_familia','productos.precio_venta','productos.stock','productos.descripcion','productos.condicion')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('productos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('productos.id', 'desc')->paginate(10);
        }


        return ['productos' => $productos];
    }
    public function listarProductoVenta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
            ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre','familias.nombre as nombre_familia','productos.precio_venta','productos.stock','productos.descripcion','productos.condicion')
            ->where('productos.stock','>','0')
            ->where('productos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{
            $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
            ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre','familias.nombre as nombre_familia','productos.precio_venta','productos.stock','productos.descripcion','productos.condicion')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('productos.stock','>','0')
            ->where('productos.idsucursal', '=', Auth::user()->idsucursal)
            ->orderBy('productos.id', 'desc')->paginate(10);
        }


        return ['productos' => $productos];
    }
    public function listarPdf(){
        $productos = Producto::join('familias','productos.idfamilia','=','familias.id')
            ->select('productos.id','productos.idfamilia','productos.codigo','productos.nombre',
            'familias.nombre as nombre_familia','productos.precio_venta','productos.stock',
            'productos.descripcion','productos.condicion')
            ->orderBy('productos.nombre', 'desc')->get();

        $cont=Producto::count();

        $pdf = \PDF::loadView('pdf.productospdf',['productos'=>$productos,'cont'=>$cont]);
        return $pdf->download('productos.pdf');
    }
    public function buscarProducto(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $productos = Producto::where('codigo','=', $filtro)
        ->select('id','nombre')->orderBy('nombre', 'asc')->take(1)->get();
        return ['productos' => $productos];
    }
    public function buscarProductoVenta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $productos = Producto::where('codigo','=', $filtro)
        ->select('id','nombre','stock','precio_venta')
        ->where('stock','>','0')
        ->orderBy('nombre', 'asc')
        ->take(1)->get();
        return ['productos' => $productos];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = new Producto();
        $producto->idfamilia = $request->idfamilia;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->condicion = '1';
        $producto->idsucursal = $request->idsucursal;
        $producto->save();

    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->idfamilia = $request->idfamilia;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->condicion = '1';
        $producto->idsucursal = $request->idsucursal;
        $producto->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->condicion = '0';
        $producto->save();
    }
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->condicion = '1';
        $producto->save();
    }
    public function import()
    {
      $collection = Excel::toCollection(new ProductsImport, request()->file('file'))[0];
      //die($collection);
      $products = $collection->filter(function($item, $key) {
          return $item['codigo'] != null;
      });
      $uniques = $products->unique();
      if (count($uniques) == count($products)){

        foreach ($products as $key => $product) {
          $products  = json_decode($uniques, true);
          $oValidate = Validator::make($products[$key], [
              'nombre' => 'required|unique:productos',
          ]);
          if ($oValidate->fails()) {
            $error['status'] = 500;
            $error['content'] = 'El campo nombre ya existe en la BD';
            return response()->json(array('data' => 'error', 'status' => $error));
          }
        }
        $error['status'] = 200;
        $error['content'] = 'Datos validados exitosamente';
        return response()->json(array('data' => $products, 'status' => $error));
      } else {
        $error['status'] = 500;
        $error['content'] = 'El campo nombre debe ser unico en el Archivo';
        return response()->json(array('data' => 'error', 'status' => $error));
      }
    }
    public function importDB(Request $request)
    {
      $request = $request->all();
      $products = $request['products'];
      foreach ($products as $key => $product) {
        $oValidate = Validator::make($products[$key], [
            'nombre' => 'required|unique:productos',
        ]);
        if ($oValidate->passes()) {
          $productoDB = new Producto();
          $productoDB->idfamilia = $product['idfamilia'];
          $productoDB->codigo = $product['codigo'];
          $productoDB->nombre = $product['nombre'];
          $productoDB->precio_venta = $product['precio_venta'];
          $productoDB->descripcion = $product['descripcion'];
          $productoDB->condicion = '1';
          $productoDB->idsucursal = $product['idsucursal'];
          $productoDB->save();
          $error['status'] = 200;
          $error['content'] = 'Datos validados exitosamente';
        }
      }
      return response()->json(array('data' => 'success', 'status' => $error));
    }
}

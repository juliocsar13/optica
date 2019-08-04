<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');
    //Notificaciones
    Route::post('/notification/get', 'NotificationController@get');

    Route::get('/main', function () {
        return view('content/content');
    })->name('main');

    Route::group(['middleware' => ['Almacenero']], function () {
        Route::get('/familia', 'FamiliaController@index');
        Route::post('/familia/registrar', 'FamiliaController@store');
        Route::put('/familia/actualizar', 'FamiliaController@update');
        Route::put('/familia/desactivar', 'FamiliaController@desactivar');
        Route::put('/familia/activar', 'FamiliaController@activar');
        Route::get('/familia/selectFamilia', 'FamiliaController@selectFamilia');

        Route::get('/producto', 'ProductoController@index');
        Route::post('/producto/registrar', 'ProductoController@store');
        Route::put('/producto/actualizar', 'ProductoController@update');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/listarProducto', 'ProductoController@listarProducto');
        Route::get('/producto/buscarProductoVenta', 'ProductoController@buscarProductoVenta');
        Route::get('/producto/listarProductoVenta', 'ProductoController@listarProductoVenta');
        Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos_pdf');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');

        Route::get('/egreso', 'EgresoController@index');
    });

    Route::group(['middleware' => ['Vendedor']], function () {

        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/listarProducto', 'ProductoController@listarProducto');
        Route::get('/producto/buscarProductoVenta', 'ProductoController@buscarProductoVenta');
        Route::get('/producto/listarProductoVenta', 'ProductoController@listarProductoVenta');
        Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos_pdf');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/pdf2/{id}','VentaController@pdf2')->name('venta_pdf2');

        Route::get('/caja', 'CajaController@index');
        Route::get('/cobro', 'CobroController@index');
    });

    Route::group(['middleware' => ['Administrador']], function () {

        Route::get('/familia', 'FamiliaController@index');
        Route::post('/familia/registrar', 'FamiliaController@store');
        Route::put('/familia/actualizar', 'FamiliaController@update');
        Route::put('/familia/desactivar', 'FamiliaController@desactivar');
        Route::put('/familia/activar', 'FamiliaController@activar');
        Route::get('/familia/selectFamilia', 'FamiliaController@selectFamilia');

        Route::post('/producto/imprimir', 'ProductoController@import');
        Route::post('/producto/import', 'ProductoController@importDB');

        Route::get('/producto', 'ProductoController@index');
        Route::post('/producto/registrar', 'ProductoController@store');
        Route::put('/producto/actualizar', 'ProductoController@update');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/listarProducto', 'ProductoController@listarProducto');
        Route::get('/producto/buscarProductoVenta', 'ProductoController@buscarProductoVenta');
        Route::get('/producto/listarProductoVenta', 'ProductoController@listarProductoVenta');
        Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos_pdf');


        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::put('/ingreso/desactivarPendiente', 'IngresoController@desactivarPendiente');
        Route::get('/ingreso/pdf/', 'IngresoController@reportCompra');
        Route::get('/ingreso/filter/', 'IngresoController@filterCompra');
        Route::get('/ingreso/filter/pdf', 'IngresoController@generarPDF');
        Route::get('/ingreso/proveedor', 'IngresoController@listarProveedor');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::put('/venta/desactivarPendiente', 'VentaController@desactivarPendiente');
        Route::get('/venta/filterSales', 'VentaController@filterSales');
        Route::get('/venta/pdf/{id}/{type}','VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/pdf/ticket/adelanto/{id}/{type}','VentaController@pdfAdelanto');

        Route::get('/venta/pdf2/{id}','VentaController@pdf2')->name('venta_pdf2');
        //Route::get('/venta/pdf/ticket/{id}','VentaController@ticket');
        Route::post('/venta/filtercaja', 'VentaController@filterCaja');

        Route::get('/venta/reportcaja', 'VentaController@reportCaja');

        Route::get('/venta/history','VentaController@reportHistory');
        Route::get('/venta/descargar/{id}','VentaController@descargarArchivos');
        Route::get('/venta/descargar2/{id}','VentaController@descargarArchivos2');

        Route::get('/caja/pago/pdf/{id}','PagoController@cajaPago');
        Route::get('/caja/cobro/pdf/{id}','CobroController@cajaCobro');
        Route::post('/caja/registrar','CajaController@store');
        Route::get('/movimiento','MovimientoController@index');

        Route::post('/movimiento/registrar','MovimientoController@store');

        Route::put('/caja/desactivar', 'CajaController@desactivar');
        Route::put('/caja/activar', 'CajaController@activar');
        Route::post('/caja/verificar', 'CajaController@verificar');
        Route::post('/caja/verificar/saldo', 'CajaController@saldo');



        Route::get('/cobro/filter','CobroController@cobroFilter');
        Route::get('/pago/filter','PagoController@pagoFilter');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');

        Route::get('/graduacion', 'GraduacionController@index');
        Route::post('/graduacion/registrar', 'GraduacionController@store');
        Route::put('/graduacion/actualizar', 'GraduacionController@update');
        Route::put('/graduacion/desactivar', 'GraduacionController@desactivar');
        Route::put('/graduacion/activar', 'GraduacionController@activar');
        Route::get('/graduacion/selectEsfera', 'GraduacionController@selectEsfera');
        Route::get('/graduacion/selectCilindro', 'GraduacionController@selectCilindro');
        Route::get('/graduacion/selectEje', 'GraduacionController@selectEje');
        Route::get('/graduacion/selectAdd', 'GraduacionController@selectAdd');
        Route::get('/graduacion/selectDip', 'GraduacionController@selectDip');
        Route::get('/graduacion/selectAv', 'GraduacionController@selectAv');

        Route::get('/caja', 'CajaController@index');
        Route::get('/egreso', 'EgresoController@index');
        Route::get('/egreso/obtenerCabecera', 'EgresoController@obtenerCabecera');
        Route::get('/cobro', 'CobroController@index');
        Route::get('/pago', 'PagoController@index');
        Route::get('/pago/obtenerCabecera', 'PagoController@obtenerCabecera');

        Route::get('/empresa', 'EmpresaController@index');
        Route::post('/empresa/registrar', 'EmpresaController@store');
        Route::put('/empresa/actualizar', 'EmpresaController@update');

        Route::get('/kardex', 'KardexController@index');
        Route::get('/kardex/filter', 'KardexController@filterKardex');

        Route::get('/kardex/excel', 'KardexController@excel');
        Route::get('/kardex/pdf', 'KardexController@generatePDF');
        Route::get('/kardex/productos', 'KardexController@listarProducto');


        Route::get('/sucursal', 'SucursalController@index');
        Route::post('/sucursal/registrar', 'SucursalController@store');
        Route::put('/sucursal/actualizar', 'SucursalController@update');
        Route::put('/sucursal/desactivar', 'SucursalController@desactivar');
        Route::put('/sucursal/activar', 'SucursalController@activar');
        Route::get('/sucursal/selectSucursal', 'SucursalController@selectSucursal');
    });

});

//Route::get('/home', 'HomeController@index')->name('home');

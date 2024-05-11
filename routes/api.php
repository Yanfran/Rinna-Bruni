<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\DistribuidoresController;
use App\Http\Controllers\Api\AjaxController;
use App\Http\Controllers\Api\PedidosController;
use App\Http\Controllers\Api\NotificacionesController;
use App\Http\Controllers\Api\VendedoresController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CatalogosController;
use App\Http\Controllers\Api\PedidoPagosController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('getColors', 'getColors');
    Route::post('reset', 'reset');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::post('reset', 'reset');
});

Route::controller(DistribuidoresController::class)->group(function () {
    Route::post('listaDistribuidores', 'listaDistribuidores');
});

Route::controller(AjaxController::class)->group(function () {

    Route::get('/notifications', 'getNotifications')->name('notifications.all');
    Route::post('listaTiendas', 'listaTiendas');
    Route::post('/lista/pais', 'getPaisLista');
    Route::post('lista/estados/{id}', 'listaEstados');
    Route::post('/lista/municipio/{id}', 'listaMunicipio');
    Route::post('/lista/localidades/{id}', 'listaLocalidades');
    Route::post('/lista/sucursales/{id}', 'listaSucursales');
    Route::get('/pais/{id}', 'getPaisID');
    Route::get('/estado/{id}', 'getEstadoID');
    Route::get('/municipio/{id}', 'getMunicipiosID');
    Route::get('/localidad/{id}', 'getLocalidadID');
    Route::get('/sucursal/{id}', 'getSucursalID');
});


Route::controller(NotificacionesController::class)->group(function () {

    Route::get('/notifications/all/{id}', 'getNotifications')->name('notifications.all');
    Route::get('/notifications/mark-as-read/{id}', 'markAsRead')->name('notifications.mark-as-read');

});

Route::controller(ProductController::class)->group(function () {
    Route::post('/products', 'getProducts');
    Route::post('/products/{size}{}', 'getSizeColor');
});

Route::controller(PedidosController::class)->group(function () {
    Route::get('/buscador/producto/{key}/{id_usuario?}', 'getProductos');
    Route::get('/buscador/productoById/{idProducto}/usuario/{idUsuario}', 'getProductobyId');
    // Route::get('/buscador/productosById/{idProducto}/usuario/{idUsuario}', 'getProductosbyId');
    Route::post('/buscador/productosById/usuario', 'getProductosbyId');
});

Route::controller(CatalogosController::class)->group(function () {
    Route::get('/catalogos', 'index');
    Route::get('/catalogos/{id}', 'getCatalogo');
    Route::get('/catalogos/{id}/products', 'getCatalogoWithProducts');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(VendedoresController::class)->group(function () {
        Route::post('/vendedoresActualizar', 'actualizarVendedor');
        Route::get('/vendedor/{id}', 'getVendedor');
    });

    Route::controller(PedidosController::class)->group(function () {
        Route::get('/pedidos', 'index');
        Route::get('/pedido/{pedidoID}', 'getPedido');
        Route::delete('/pedido/delete/{pedidoID}', 'destroy');
        // Route::get('/buscador/producto/{key}/usuario/{id_usuario}', 'getProductos');
        Route::get('/pedidos/totalExistencias/producto/{productoID}', 'totalExistencias');
        Route::post('/pedido/agregar/producto', 'store');
        Route::post('/pedido/editar/producto', 'editarPedido');
        Route::post('/pedido/editar/producto_pedido', 'editarProductoPedido');
        Route::post('/pedido/revision', 'revision');
        Route::post('/pedido/validatepedido', 'getPedidoAbierto');
        Route::get('/pedido/{pedidoID}/producto/{productoID}/usuario/{userID}/eliminar', 'eliminarProducto')->name('pedido.eliminar');
        Route::post('/pedidos/cupon', 'cuponesVale')->name('pedidos.aplicarCupon');
    });

    Route::controller(PedidoPagosController::class)->group(function () {
        Route::post('pedido/save/comprobante-pago', 'saveComprobante');
    });


});

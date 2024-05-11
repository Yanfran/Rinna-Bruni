<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\EstadostController;
use App\Http\Controllers\MunicipiosController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\InicioLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\TiendasController;
use App\Http\Controllers\CuponsController;
use App\Http\Controllers\DistribuidoresController;
use App\Http\Controllers\VendedoresController;
use App\Http\Controllers\DireccionesController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ExistenciasController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\WebhooksController;


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

//route::post('login', 'Auth\LoginController@login');

Route::post('webhooks', 'WebhooksController@handleMercadoPagoWebhook');

//Route::get('authcrol', 'DistribuidoresController@testAuthCROL');
Route::get('syncp', 'CROL_SyncProductsController@syncProducts');
Route::get('syncc', 'CROL_SyncProductsController@syncContactos');
Route::get('lista-precio/{id}', 'CROL_SyncProductsController@getListaDePrecio');
Route::get('lista-precio-producto/{idProducto}/{dlistaPrecio}/{tipoPrecio}', 'CROL_SyncProductsController@getPrecioListaProducto');

Route::group(['prefix' => 'ajax'], function () {
    Route::get('/email/{email}', 'AjaxController@validateEmailRegistro');
    Route::get('/estado/{id}', 'AjaxController@getEstadoData');
    Route::get('/direccion/{id}', 'AjaxController@getDireccionData');
    Route::get('/municipios/{id}', 'AjaxController@getMunicipiosData');
    Route::get('/localidad/{id}', 'AjaxController@getLocalidadData');
    Route::get('/pais/{id}', 'AjaxController@getPaisData');
    Route::get('/localidadBusqueda/{id}', 'AjaxController@getLocalidadDataBusqueda');
    Route::post('/validateEmail', 'AjaxController@validateEmail')->name('validateEmail');
    Route::post('/validateRFC', 'AjaxController@validateRFC')->name('validateRFC');
    Route::get('/AliasDirecciones/{id}', 'AjaxController@getAliasDireccioneData');
    Route::get('/direcciones/{id}', 'AjaxController@getDireccioneData');
    Route::get('/estadoDirecciones/{id}', 'AjaxController@getEstadoDireccionesData');
    Route::get('/municipioDirecciones/{id}', 'AjaxController@getMunicipioDireccionesData');
    Route::get('/localidadDirecciones/{id}', 'AjaxController@getLocalidadDireccionesData');
    Route::get('/distribuidoresAll/{id}', 'AjaxController@getDistribuidores');
    Route::get('/obtenerEstados/', 'AjaxController@getEstadosData');
    Route::get('/tiendaDirecciones/{id}', 'AjaxController@getTiendaDireccionesData');
});

// Registration Routes...
route::get('register_by_comp/{id?}', 'Auth\RegisterController@showRegistrationFormByCompany')->name('register_by_com');
route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
route::post('register', 'Auth\RegisterController@register');
route::get('reset/password/{id?}', 'Auth\ResetPasswordController@showLinkRequestFormCompany')->name('password_by_com');
route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', [InicioLoginController::class, 'index'])->name('inicio');
route::get('login/{id?}', [LoginController::class, 'LoginForm'])->name('login');
route::post('login', [LoginController::class, 'login'])->name('login-post');
route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::view('auth-error', 'auth-error');



Route::prefix('reset-password')->group(function () {
    Route::get('/{userId}', 'ResetPasswordController@index')->name('resetPassword.index');
    Route::post('/update', 'ResetPasswordController@update')->name('resetPassword.update');
});

Route::prefix('perfil')->group(function () {
    Route::get('/{userId}', 'PerfilController@index')->name('perfil.index');
    Route::post('/update', 'PerfilController@update')->name('perfil.update');
});
Route::get('/tratamiento', [TratamientoController::class, 'index'])->name('tratamiento');

Route::delete('/eliminar-imagen/{id}', 'ProductController@eliminarImagen')->name('eliminar.imagen');
Route::post('/actualizar-estados-imagenes', 'ProductController@actualizarEstadosImagenes')->name('actualizar.estados.imagenes');





Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('index');
    //rutas de productos negados
    Route::post('/add/productosnegados', 'AjaxController@addNegados')->name('addNegados');
    //rutas del pedido
    Route::get('/pedidos/reporte/{id}', [PdfController::class, 'reporte'])->name('reporte');
    Route::get('/misCupones', 'MisCuponesController@index')->name('misCupones.index');
    Route::get('/misVales', 'MisCuponesController@vales')->name('misVales.index');
    Route::get('/autosearch/cliente/{key}', 'AjaxController@getClientes');
    Route::get('/autosearch/producto/{key}/{id_usuario}', 'AjaxController@getProductos');
    Route::post('pedidos/totalExistencias/ajax', 'AjaxController@totalExistencias')->name('totalExistencias.ajax');
    Route::get('/pedido/{pedidoID}/producto/{productoID}/usuario/{userID}/eliminar', 'PedidosController@eliminarProducto')->name('pedido.eliminar');
    Route::get('mercadopago/success', 'PedidosController@success')->name('mercadopago.success');
    Route::get('mercadopago/failure', 'PedidosController@failure')->name('mercadopago.failure');
    Route::get('mercadopago/pending', 'PedidosController@pending')->name('mercadopago.pending');
    Route::post('/crear_referencia_mp', 'AjaxController@crearReferenciaMP')->name('crear_referencia_mp');
    Route::post('/direcciones/pedido/', 'AjaxController@getDireccionePOST')->name('direcciones.pedido');
    Route::post('/direcciones/pedido/tienda/', 'AjaxController@getDireccionTienda')->name('direccion.pedidoTienda');
    Route::post('detalle/direccion/', 'AjaxController@getDetallesDireccion')->name('detalle.direccion');
    Route::post('distribuidor/bloqueado/', 'AjaxController@validarDistribuidorBloqueado')->name('distribuidor.bloqueado');
    Route::post('/solicitud/pedido/', 'AjaxController@getPedidoAbierto')->name('validate.pedido');
    Route::post('/pedidos/cupon', 'AjaxController@cuponesVale')->name('pedidos.aplicarCupon');
    Route::post('/actualizate/producto/', 'AjaxController@getProductosUpdate')->name('actualizate.producto');
    Route::post('/actualizate/producto/updatear', 'AjaxController@getProductosUpdateAjax')->name('updatear.producto');
    Route::get('obtener/empresa/', 'AjaxController@obtenerEmpresa')->name('obtenerEmpresa.ajaxController');

    Route::get('/productosnegados', 'ProductosNegadosController@index');
    Route::get('/productosgestion', 'ProductosGestionController@index');

    Route::get('/image-upload', 'ImageUploadController@index');
    Route::post('/image-upload', 'ImageUploadController@store');

    Route::post('/update-preference-id-mercado-pago', 'PedidosController@updatePreferenceIdMercadoPago')->name('pedidos.updatePreferenceIdMercadoPago');

    //notificaciones
    Route::get('/usuarios/all/', 'AjaxController@getUsers')->name('usuarios.all');
    Route::get('/ejecutar/notificaciones', 'AjaxController@ejecutarNotificaciones')->name('ajax.ejecutarNotificaciones');
    Route::get('/notifications/all', 'AjaxController@getNotifications')->name('notifications.all');
    Route::post('/notifications/mark-as-read', 'AjaxController@markAsRead')->name('notifications.mark-as-read');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('distribuidores', DistribuidoresController::class);
    Route::resource('vendedores', VendedoresController::class);
    Route::resource('products', ProductController::class);
    Route::resource('pais', PaisController::class);
    Route::resource('estados', EstadosController::class);
    Route::resource('municipios', MunicipiosController::class);
    Route::resource('localidad', LocalidadController::class);
    Route::resource('tiendas', TiendasController::class);
    Route::resource('cupons', CuponsController::class);
    Route::resource('pedidos', PedidosController::class);

    //catalogos
    Route::resource('catalogos', Catalogos\CatalogosController::class);
    Route::resource('lineas', Catalogos\LineasController::class);
    Route::resource('temporadas', Catalogos\TemporadasController::class);
    Route::resource('descripciones', Catalogos\DescripcionesController::class);
    Route::resource('marcas', Catalogos\MarcasController::class);
    Route::get('/catalogos/{catalogo}/pdf', 'Catalogos\PdfController@vista')->name('catalogos.pdf');
    Route::get('/catalogos/{catalogo}/download', 'Catalogos\PdfController@download')->name('catalogos.download');
    Route::get('/catalogos/modal/getFilteredProducts', 'Catalogos\CatalogosController@getFilteredProducts')->name('catalogos.filteredProducts');


    //end catalogos

    Route::put('existencias/update_multiple', 'ExistenciasController@updateMultiple')->name('existencias.update_multiple');
    Route::delete('existencias/{id}', 'ExistenciasController@destroy')->name('existencias.destroy');
    Route::resource('existencias', ExistenciasController::class);
    Route::post('pedidos/solicitar', [PedidosController::class, 'solicitar'])->name('pedidos.solicitar');
    //Route::resource('sucursales', SucursalesController::class);
    Route::get('/sucursales/{user}', 'SucursalesController@index')->name('sucursales.index');
    Route::get('/sucursales/create/{user}', 'SucursalesController@create')->name('sucursales.create');
    Route::post('/sucursales', 'SucursalesController@store')->name('sucursales.store');
    Route::post('/sucursales/{sucursal}', 'SucursalesController@update')->name('sucursales.update');
    Route::get('/sucursales/edit/{sucursal}', 'SucursalesController@edit')->name('sucursales.edit');
    Route::get('/sucursales/show/{sucursal}', 'SucursalesController@show')->name('sucursales.show');

    Route::get('/vendedoresAsociados/{user}', 'VendedoresAsociadosController@index')->name('vendedoresAsociados.index');
    Route::get('/vendedoresAsociados/create/{user}', 'VendedoresAsociadosController@create')->name('vendedoresAsociados.create');
    Route::post('/vendedoresAsociados', 'VendedoresAsociadosController@store')->name('vendedoresAsociados.store');
    Route::put('/vendedoresAsociados/{user}', 'VendedoresAsociadosController@update')->name('vendedoresAsociados.update');
    Route::get('/vendedoresAsociados/edit/{user}', 'VendedoresAsociadosController@edit')->name('vendedoresAsociados.edit');
    Route::get('/vendedoresAsociados/show/{user}', 'VendedoresAsociadosController@show')->name('vendedoresAsociados.show');

    Route::delete('/pedidos/{pedido}', 'PedidosController@destroy')->name('pedidos.destroy');

    Route::resource('empresas', 'EmpresasController');
    Route::get('/configuraciones/{id}', 'EmpresasController@edit')->name('cofiguraciones');
    Route::get('/empresa/restore/{id}', 'EmpresasController@restore')->name('empresaRestore');

    /** Route to show an image */
    Route::get('storage/{typeFile}/{filename}', function ($typeFile, $filename)
    {
        $path = storage_path('app/public/' . $typeFile . '/' . $filename);

        if (!File::exists($path)) { abort(404); }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    })->name('storage');

    /*Rutas para consumir APIS desde el Erp CROL*/
    Route::get('producto-crol/{id}/{tipoCliente}', 'CROL_SyncProductsController@getProductoCROL')->name('crol.producto');


});




Route::group(['prefix' => 'slider'], function () {

    Route::get('/', 'SliderController@index')->name('slider');
    Route::get('/crear', 'SliderController@create')->name('sliderCrear');
    Route::post('/store', 'SliderController@store')->name('sliderStore');
    Route::get('/edit/{id}', 'SliderController@editar')->name('sliderEdit');
    Route::get('/{id}', 'SliderController@delete')->name('sliderDelete');
    Route::get('/papelera/slider', 'SliderController@papelera')->name('sliderPapelera');
    Route::get('/restore/{id}', 'SliderController@restore')->name('sliderRestore');
});

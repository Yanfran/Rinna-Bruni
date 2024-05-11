<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\Pedidos;
use App\Models\ProductosPedido;
use App\Models\Product;
use App\Models\User;
use App\Models\Existencias;
use App\Models\Empresas;
use App\Models\Galeria;
use App\Models\Cupons;
use App\Models\Tiendas;
use App\Models\Mercadopago;
use App\Models\Notification;
use App\Helpers\NotificationHelper;
use Pusher\Pusher;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\Direcciones;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use Config;



class PedidosController extends BaseController
{
    use TraitApiCROL;

    function __construct()
    {
        $empresa = Empresas::find(1);
        $this->publicKey = $empresa->mp_public_key;
        $this->accessToken = $empresa->mp_access_token;
        $this->costo_paqueteria = Empresas::find(1)->costo_paqueteria;
    }

    public function index()
    {
        if (\Auth::user()->isDistribuidor()) {

            $data = Pedidos::where('distribuidor_id', \Auth::user()->id)->orderBy('id', 'DESC')->paginate(15);
        } else if (\Auth::user()->isVendedor()) {

            $data = Pedidos::where('distribuidor_id', \Auth::user()->distribuidor_id)->orderBy('id', 'DESC')->paginate(15);
        } else if (\Auth::user()->isVendedorLibre()) {

            $data = Pedidos::where('vendedor_id', \Auth::user()->id)->orderBy('id', 'DESC')->paginate(15);
        }
        return $this->sendResponse($data, 'Lista de pedidos.');
    }




    public function getPedido($pedidoID)
    {

        $data = Pedidos::with('productosPedidos')->find($pedidoID);
        $empresa = Empresas::first();

        $productosPedidos = $data->productosPedidos;
        $montoTotal = 0;

        $productsWithImages = [];

        // Crear un arreglo temporal para realizar la suma
        $tempArray = [];

        foreach ($productosPedidos as $productoPedido) {
            $product = Product::find($productoPedido->product_id);
            if (!$product) {
                continue; // Omitir el producto si no se encuentra
            }

            $product->load('linea');
            $linea = $product->linea;
            $product->load('galerias'); // Carga las imágenes asociadas al producto
            $galerias = $product->galerias;

            // Encuentra la imagen destacada con estado igual a 1
            $imagenDestacada = $galerias->where('estatus', 1)->first();

            if (!$imagenDestacada && $galerias->isNotEmpty()) {
                // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
                $imagenDestacada = $galerias->first();
            }

            // Agregar campos adicionales al objeto productosPedidos
            //$productoPedido->linea = $product->linea;
            $productoPedido->color = $product->color;
            //$productoPedido->talla_mayor = $product->talla_mayor;
            $productoPedido->talla = $product->talla;
            $productoPedido->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;
            $productoPedido->linea = $linea;

            // Agrega otros campos según sea necesario

            // Realizar el cálculo del monto total para este producto y sumarlo al monto total general
            // $montoProducto = $productoPedido->cantidad_solicitada * $productoPedido->monto;
            // $productoPedido->monto = $montoProducto; // Actualizar el monto en el objeto
            // $montoTotal += $productoPedido->monto; // Sumar al monto total


            // Actualizar los valores en el arreglo temporal
            if (isset($tempArray[$productoPedido->product_id])) {
                $tempArray[$productoPedido->product_id]->cantidad_solicitada += $productoPedido->cantidad_solicitada;
                $tempArray[$productoPedido->product_id]->monto += $productoPedido->monto;
                $tempArray[$productoPedido->product_id]->neto += $productoPedido->neto;
            } else {
                $tempArray[$productoPedido->product_id] = $productoPedido;
            }

            // $montoTotal += $productoPedido->monto;

            // $productsWithImages[] = $productoPedido;
        }

        // $data->monto_total = $montoTotal;

        // Calcular el monto total y construir el arreglo final
        foreach ($tempArray as $productPedido) {
            $montoTotal += $productPedido->monto;
            $productsWithImages[] = $productPedido;
        }

        $data->monto_total = $montoTotal;
        $data->monto_paqueteria = intval($empresa->costo_paqueteria);
        $data->productosPedidos = $productsWithImages;

        //agregar dirección si es domiclio
        $direccionCliente = $data->direccion_cliente;
        $tipoEnvio = $data->tipo_envio;
        if(isset($direccionCliente) && $tipoEnvio == "domicilio") {
            $direccionEnvio = Direcciones::find($direccionCliente);
            $data->direccionEnvio = $direccionEnvio;
        }

        return $this->sendResponse($data, 'Pedido por ID.');
    }




    // public function getPedido($pedidoID)
    // {

    //     $data = Pedidos::with('productosPedidos')->find($pedidoID);

    //     $productosPedidos = $data->productosPedidos;
    //     $montoTotal = 0;

    //     $productsWithImages = [];

    //     foreach ($productosPedidos as $productoPedido) {
    //         $product = Product::find($productoPedido->product_id);
    //         if (!$product) {
    //             continue; // Omitir el producto si no se encuentra
    //         }

    //         $product->load('galerias'); // Carga las imágenes asociadas al producto
    //         $galerias = $product->galerias;

    //         // Encuentra la imagen destacada con estado igual a 1
    //         $imagenDestacada = $galerias->where('estatus', 1)->first();

    //         if (!$imagenDestacada && $galerias->isNotEmpty()) {
    //             // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
    //             $imagenDestacada = $galerias->first();
    //         }

    //         // Agregar campos adicionales al objeto productosPedidos
    //         $productoPedido->linea = $product->linea;
    //         $productoPedido->color = $product->color;
    //         $productoPedido->talla_mayor = $product->talla_mayor;
    //         $productoPedido->talla_menor = $product->talla_menor;
    //         $productoPedido->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;
    //         // Agrega otros campos según sea necesario

    //         // Realizar el cálculo del monto total para este producto y sumarlo al monto total general
    //         // $montoProducto = $productoPedido->cantidad_solicitada * $productoPedido->monto;
    //         // $productoPedido->monto = $montoProducto; // Actualizar el monto en el objeto
    //         // $montoTotal += $productoPedido->monto; // Sumar al monto total

    //         $montoTotal += $productoPedido->monto;

    //         $productsWithImages[] = $productoPedido;
    //     }

    //     $data->monto_total = $montoTotal;

    //     return $this->sendResponse($data, 'Pedido por ID.');
    // }


    public function store(Request $request)
    {
        $primerProducto = false;
        $cantidadDisponible = 0;
        $cantidadSolicitada = $request->cantidad;
        $pedidoID = $request->input('pedidoID');
        $cliente = User::find($request->user_id);
        if (empty($cliente)) {
            return response()->json('el usuario no existe', '404');
        }

        //Esto se verifico previamente en la App con otr servico que conecta con el ERP
        // y devuelve la existencia real

        // $existencia = Existencias::where('product_id', $request->product_id)
        //     ->where('tienda_id', $cliente->tienda_id)
        //     ->first();

        // if(isset($existencia)) {
        //     $cantidadDisponible = $existencia->cantidad;
        // }

        // // validamos existencias en tienda
        // if($cantidadDisponible <= 0) {
        //     return response()->json("Producto sin existencias en tienda", "404");
        // }

        // // validamos cantidad disponible
        // if($cantidadDisponible < $cantidadSolicitada) {
        //     return response()->json([
        //         "msg" => "La cantidad solicitada supera las existencias en tienda",
        //         "cantidad_disponible" => $cantidadDisponible
        //     ]);
        // }

        $precio = $request->precio_socio * $request->cantidad; // Precio normal
        $precio_neto = $request->precio_final * $request->cantidad; // Precio neto
        $precioCliente = $request->descuento * $request->cantidad; // Precio descuento
        $pedidoAbierto = Pedidos::find($pedidoID);
        $config = Empresas::find(1);

        if ($pedidoID === null and $pedidoAbierto === null) { // caso 1 validamos si el pedido es nuevo
            // creación del pedido
            $primerProducto = true;
            $pedido = new Pedidos();
            if ($cliente->tipo == 3) {
                $pedido->distribuidor_id = $cliente->id;
            } elseif ($cliente->tipo == 2) {
                $pedido->vendedor_id  = $cliente->id;
                //$pedido->distribuidor_id = $cliente->getDistribuidor();
                $pedido->distribuidor_id = $cliente->distribuidor_id;
            } elseif ($cliente->tipo == 4) {
                $pedido->vendedor_id  = $cliente->id;
            }
            $pedido->estatus = 0;
            $pedido->creado_por = Auth::user()->id;
            $pedido->monto_total +=  $precio;
            $pedido->monto_neto +=  $precio_neto;
            $pedido->monto_descuento_cliente +=  $precioCliente;
            $pedido->push();
            $pedidoID = $pedido->id;

        } else {
            $pedido = Pedidos::with('productosPedidos')->find($pedidoID);
            $pedido->monto_total +=  $precio;
            $pedido->monto_neto +=  $precio_neto;
            $pedido->monto_descuento_cliente +=  $precioCliente;
            $pedido->push();
        }

        // creación de productos solicitados
        $productoPedido = new ProductosPedido();
        $productoPedido->pedido_id = $pedidoID;
        $productoPedido->user_id = $request->user_id;
        $productoPedido->product_id = $request->product_id;
        $productoPedido->cantidad_solicitada = $cantidadSolicitada;
        $productoPedido->cantidad_pendiente = 0;
        $productoPedido->monto = $precio;
        $productoPedido->neto =  $precio_neto;
        $productoPedido->descuento = $precioCliente;
        $productoPedido->precio_unitario = $request->precio_socio;
        $productoPedido->external_id = $request->external_id;
        $productoPedido->save();

        //actualización de existencia
        // $existencia->cantidad = $existencia->cantidad - $cantidadSolicitada;
        // $existencia->save();

        // creación de referencia de pago
        $pedido = Pedidos::with('productosPedidos')->find($pedidoID);

        SDK::setAccessToken($config->mp_access_token);
        $preference = new Preference();

        // Configuración de las URLs de redireccionamiento
        $preference->back_urls = array(
            "success" => Route('mercadopago.success'),
            "failure" => Route('mercadopago.failure'),
            "pending" => Route('mercadopago.pending')
        );
        $preference->auto_return = "approved";
        $preference->expires = false;

        // Configuración del artículo a pagar
        $item = new Item();
        $item->title = 'Pago por pedido Rinna Bruni N°: ' . $pedidoID;
        $item->quantity = 1;
        $item->unit_price = $pedido->getMonto();
        $preference->items = array($item);


        // Configuración del pagador (payer)
        $payer = new Payer();
        $payer->email = $cliente->email;
        $payer->identification = (object) array(
            "type" => "INE",
            "number" => "12345678"
        );
        $preference->payer = $payer;
        $preference->save();
        $preferenciaID = $preference->id;

        $mercadopago = Mercadopago::where('pedido_id', $pedidoID)->first();
        if ($mercadopago == null) {
            $mercadopago = new Mercadopago();
            $mercadopago->pedido_id = $pedidoID;
        }

        $mercadopago->referencia = $preferenciaID;
        $mercadopago->precio = $pedido->getMonto();
        $mercadopago->save();

        $pedido->mercadopago_id = $mercadopago->id;
        $pedido->referencia_mercadopago = $preferenciaID;
        $pedido->save();

        if (\Auth::user()->isAdmin()) {

            if ($pedido->distribuidor_id != null) {
                $titulo = 'Pedido abierto';
                $msg = 'Nuevo pedido abierto n°:' . $pedidoID;
                NotificationHelper::notificacionUsuario($pedidoID, $pedido->distribuidor_id, $msg, $titulo);
            } else {
                $titulo = 'Pedido abierto';
                $msg = 'Nuevo pedido abierto n°:' . $pedidoID;
                NotificationHelper::notificacionUsuario($pedidoID, $pedido->vendedor_id, $msg, $titulo);
            }
        } elseif ($cliente->tipo == 3) {
            $titulo = 'Pedido abierto';
            $tiendaID = $cliente->tienda_id;
            $msg = 'Nuevo pedido abierto n°: ' . $pedidoID . ' por el distribuidor: ' . $cliente->numero_afiliacion . ': ' . $cliente->name . ' ' . $cliente->apellido_paterno . ' ' . $cliente->apellido_materno;
            NotificationHelper::notificacionAdmin($msg, $pedidoID, $titulo, $tiendaID);
        } elseif ($cliente->tipo == 2) {

            if ($pedido->distribuidor_id == null) {
                $titulo = 'Pedido abierto';
                $tiendaID = $cliente->tienda_id;
                $msg = 'Nuevo pedido abierto n°: ' . $pedidoID . ' por el vendedor agente libre: ' . $cliente->numero_afiliacion . ': ' . $cliente->name . ' ' . $cliente->apellido_paterno . ' ' . $cliente->apellido_materno;
                NotificationHelper::notificacionAdmin($msg, $pedidoID, $titulo, $tiendaID);
            }
        }
        $data = [];

        if ($primerProducto) {
            $data = [
                'id' => $pedidoID,
                'estatus' => 'Abierto',
                'pedido' => $pedido,
                'key_mercadopago' => $config->mp_public_key,
                'message' => 'Producto agregado al pedido nuevo.',
            ];
        } else {
            $data = [
                'id' => $pedidoID,
                'pedido' => $pedido,
                'key_mercadopago' => $config->mp_public_key,
                'estatus' => 'Abierto',
                'message' => 'Producto agregado al pedido existente.',
            ];
        }

        // Send the response as JSON
        return response()->json($data);
    }

    public function solicitar(Request $request)
    {
        $config = Empresas::find(1);
        $pedidoID = $request->pedido_id;
        $cliente = User::find($request->id_usuario);
        $pedido = Pedidos::find($pedidoID);
        $pedido->metodo_pago = $request->metodo_pago;
        $pedido->observacion = $request->observacion;
        $pedido->tipo_envio = $request->tipo_envio;
        $pedido->created_at = now();

        if ($request->tipo_envio == 'domicilio' and \Auth::user()->isAdmin()) {
            $pedido->direccion_cliente = $request->direccion_cliente;
            $pedido->total_cajas = $request->total_cajas;
        } else if ($request->tipo_envio == 'domicilio') {
            $pedido->direccion_cliente = $request->direccion_cliente;
        }

        // Acciones comunes para ambos tipos de envío y roles de usuario
        if (\Auth::user()->isAdmin()) {
            if ($request->accion_pedido == 'guardar') {
                $pedido->estatus = 0; // El pedido queda abierto, estatus abierto
            } elseif ($request->accion_pedido == 'procesar') {
                $pedido->estatus = 3; // Estatus en pendiente de pago
            } else {
                $pedido->estatus = 3; // Estatus en revisión
            }
        } else {
            if ($request->accion_pedido == 'guardar') {
                $pedido->estatus = 0; // El pedido queda abierto, estatus abierto
            } elseif ($request->accion_pedido == 'procesar') {
                $pedido->estatus = 3; // Estatus en pendiente de pago
            } else {
                $pedido->estatus = 1; // Estatus en revisión
            }
        }

        $pedido->estatus_pago = 0; // Estatus no se ha efectuado el pago
        $pedido->estatus_envio = 0; // Estatus no se ha enviado

        $pedido->save();

        // Notificaciones del pedido
        if (\Auth::user()->isAdmin()) {
            // Notificaciones específicas para administradores
            if ($pedido->distribuidor_id != null) {
                // Notificación para el distribuidor
                if ($pedido->estatus  == 3) {
                    $titulo = 'Pago pendiente';
                    $msg = 'Tienes un pago pendiente n°:' . $pedidoID;
                    NotificationHelper::notificacionUsuario($pedidoID, $pedido->distribuidor_id, $msg, $titulo);
                }
            } else {
                if ($pedido->estatus  == 3) {
                    $titulo = 'Pago pendiente';
                    $msg = 'Tienes un pago pendiente n°:' . $pedidoID;
                    NotificationHelper::notificacionUsuario($pedidoID, $pedido->vendedor_id, $msg, $titulo);
                }
            }
        } elseif ($cliente->tipo == 3) {
            // Notificación para distribuidores
            if ($pedido->estatus  == 1) {
                $titulo = 'Pedido solicitado';
                $tiendaID = $cliente->tienda_id;
                $msg = 'Nuevo pedido solicitado n°: ' . $pedidoID . ' por el distribuidor: ' . $cliente->numero_afiliacion . ': ' . $cliente->name . ' ' . $cliente->apellido_paterno . ' ' . $cliente->apellido_materno;
                NotificationHelper::notificacionAdmin($msg, $pedidoID, $titulo, $tiendaID);
            }
        } elseif ($cliente->tipo == 2 && $pedido->distribuidor_id == null) {
            if ($pedido->estatus  == 1) {
                $titulo = 'Pedido solicitado';
                $tiendaID = $cliente->tienda_id;
                $msg = 'Nuevo pedido solicitado n°: ' . $pedidoID . ' por el vendedor agente libre: ' . $cliente->numero_afiliacion . ': ' . $cliente->name . ' ' . $cliente->apellido_paterno . ' ' . $cliente->apellido_materno;
                NotificationHelper::notificacionAdmin($msg, $pedidoID, $titulo, $tiendaID);
            }
        }
        if (\Auth::user()->isAdmin()) {
            return redirect()->route('pedidos.index')->with('success', 'El pedido se ha procesado exitosamente.');
        } else {
            return redirect()->route('pedidosUsuario.index')->with('success', 'El pedido se ha procesado exitosamente.');
        }
    }

    public function success(Request $request)
    {
        $collectionId = $request->query('collection_id');
        $preferenceId = $request->query('preference_id');
        $collectionStatus = $request->query('collection_status');
        $paymentId =    $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');
        $paymentType = $request->query('payment_type');

        // Actualizar el modelo Pedido
        $pedido = Pedidos::where('referencia_mercadopago', $preferenceId)->first();

        if ($pedido->distribuidor_id != null) {
            $usuario = User::find($pedido->distribuidor_id);
        } else {
            $usuario = User::find($pedido->vendedor_id);
        }

        if ($pedido) {
            $pedido->metodo_pago = 'Mercado Pago';
            $pedido->estatus_pago = 2;
            $pedido->estatus = 4;
            $pedido->save();
        }

        $admin = User::where('tipo', 1)->where('tienda_id', $usuario->tienda_id)->first();

        if ($admin) {
            $msg = 'Pedido Pagado n°: ' . $pedido->id . ' por el usuario: ' .  $usuario->name . ' ' .  $usuario->apellido_paterno . ' ' .  $usuario->apellido_materno;
            NotificationHelper::notificacionUsuario($pedido->id, $admin->id, $msg, 'Pedido Pagado');
        }

        $mercadopago = MercadoPago::where('referencia', $preferenceId)->first();

        if ($mercadopago) {
            $mercadopago->collection_id = $collectionId;
            $mercadopago->referencia = $preferenceId;
            $mercadopago->collection_status = $collectionStatus;
            $mercadopago->payment_id = $paymentId;
            $mercadopago->status = $status;
            $mercadopago->external_reference = $externalReference;
            $mercadopago->payment_type = $paymentType;
            $mercadopago->save();
        }

        return view('pedidos.success', compact('pedido'));
    }
    public function failure(Request $request)
    {
        $collectionId = $request->query('collection_id');
        $preferenceId = $request->query('preference_id');
        $collectionStatus = $request->query('collection_status');
        $paymentId =    $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');
        $paymentType = $request->query('payment_type');

        // Actualizar el modelo Pedido
        $pedido = Pedidos::where('referencia_mercadopago', $preferenceId)->first();

        if ($pedido->distribuidor_id != null) {
            $usuario = User::find($pedido->distribuidor_id);
        } else {
            $usuario = User::find($pedido->vendedor_id);
        }
        return view('pedidos.failure', compact('pedido'));
    }
    public function pending(Request $request)
    {
        // Lógica para manejar el caso de pendiente $collectionId = $request->query('collection_id');
        $preferenceId = $request->query('preference_id');
        $collectionStatus = $request->query('collection_status');
        $paymentId =    $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');
        $paymentType = $request->query('payment_type');

        // Actualizar el modelo Pedido
        $pedido = Pedidos::where('referencia_mercadopago', $preferenceId)->first();

        if ($pedido->distribuidor_id != null) {
            $usuario = User::find($pedido->distribuidor_id);
        } else {
            $usuario = User::find($pedido->vendedor_id);
        }

        if ($pedido) {
            $pedido->metodo_pago = 'Mercado Pago';
            $pedido->estatus_pago = 1;
            $pedido->estatus = 3;
            $pedido->save();
        }

        $admin = User::where('tipo', 1)->where('tienda_id', $usuario->tienda_id)->first();

        if ($admin) {
            $msg = 'Pedido Pago en proceso n°: ' . $pedido->id . ' por el usuario: ' .  $usuario->name . ' ' .  $usuario->apellido_paterno . ' ' .  $usuario->apellido_materno;
            NotificationHelper::notificacionUsuario($pedido->id, $admin->id, $msg, 'Pago en proceso');
        }

        $mercadopago = MercadoPago::where('referencia', $preferenceId)->first();

        if ($mercadopago) {
            $mercadopago->collection_id = $collectionId;
            $mercadopago->referencia = $preferenceId;
            $mercadopago->collection_status = $collectionStatus;
            $mercadopago->payment_id = $paymentId;
            $mercadopago->status = $status;
            $mercadopago->external_reference = $externalReference;
            $mercadopago->payment_type = $paymentType;
            $mercadopago->save();
        }
        return view('pedidos.pending', compact('pedido'));
    }

    public function destroy($pedidoID)
    {
        $pedido = Pedidos::find($pedidoID);

        if (!$pedido) {
            return response()->json(['error' => 'El pedido no fue encontrado'], 404);
        }

        $productosPedido = ProductosPedido::where('pedido_id', $pedido->id)->get();

        foreach ($productosPedido as $productoPedido) {
            $productoID = $productoPedido->product_id;
            $cantidadSolicitada = $productoPedido->cantidad_solicitada;
            $tiendaID = $productoPedido->user->tienda_id;

            $existencia = Existencias::where('product_id', $productoID)
                ->where('tienda_id', $tiendaID)
                ->first();

            if ($existencia) {
                $existencia->cantidad += $cantidadSolicitada;
                $existencia->save();
            }
        }

        // Instead of using delete(), you can use the destroy() method with the ID
        ProductosPedido::where('pedido_id', $pedido->id)->delete();

        $pedido->delete();

        return response()->json(['message' => 'El pedido ha sido eliminado exitosamente'], 200);
    }

    // public function getProductos($key, $id_usuario)
    // {
    //     if (mb_strlen($key, 'utf-8') > 2) {
    //         $search = ' ' . $key;
    //         $usuario = User::find($id_usuario);
    //         $tienda_id = $usuario->Tienda->id;
    //         $productos = Product::where(function ($query) use ($key) {
    //             $query->where('linea', 'like', $key . '%')
    //                 ->orWhere('estilo', 'like', $key . '%')
    //                 ->orWhere('color', 'like', $key . '%')
    //                 ->orWhere('codigo', 'like', $key . '%');
    //         })
    //             ->take(20)
    //             ->with(['existencias' => function ($query) {
    //                 $query->select('product_id', \DB::raw('SUM(cantidad) as total_cantidad'))
    //                     ->groupBy('product_id');
    //             }])
    //             ->with(['existencias_por_tienda' => function ($query) use ($tienda_id) {
    //                 $query->select('product_id', \DB::raw('SUM(cantidad) as cantidad_tienda'))
    //                     ->where('tienda_id', $tienda_id)
    //                     ->groupBy('product_id');
    //             }])->with(['galerias'])
    //             ->get();

    //         // Calcular cantidad total sumando las cantidades de todas las existencias de todas las tiendas
    //         $cantidadTotal = $productos->sum(function ($producto) {
    //             return $producto->existencias->sum('total_cantidad');
    //         });

    //         // Obtener cantidad por tienda relacionada al cliente
    //         $cantidadPorTienda = $productos->pluck('existencias_por_tienda.*.cantidad_tienda')->flatten()->sum();

    //         // Convert the data into an array format
    //         $data = [
    //             'productos' => $productos->toArray(),
    //             'key' => $key,
    //             'cantidadTotal' => $cantidadTotal,
    //             'cantidadPorTienda' => $cantidadPorTienda,
    //         ];

    //         // Send the response as JSON using the sendResponse method
    //         return $this->sendResponse($data, 'Buscador de productos.');
    //     }
    // }

//     public function getProductos($key, $id_usuario)
// {
//     if (mb_strlen($key, 'utf-8') > 2) {
//         $search = ' ' . $key;
//         $usuario = User::find($id_usuario);
//         $tienda_id = $usuario->Tienda->id;
//         // Obtener los productos con imágenes
//         $productos = Product::
//         whereHas('linea', function ($query) use ($key) {
//             $query->where('nombre', 'like', '%' . $key . '%');
//         })
//         ->orWhere(function ($query) use ($key) {
//             $query->where('estilo', 'like', $key . '%')
//             ->orWhere('color', 'like', $key . '%')
//             ->orWhere('codigo', 'like', $key . '%');
//         })
//         ->take(20)
//         ->with(['existencias' => function ($query) {
//             $query->select('product_id', \DB::raw('SUM(cantidad) as total_cantidad'))
//                 ->groupBy('product_id');
//         }])
//         ->with(['existencias_por_tienda' => function ($query) /* use ($tienda_id) */{
//             $query->select('product_id', 'tienda_id', \DB::raw('SUM(cantidad) as cantidad_tienda'))
//                 ->where('cantidad', '>' , 0)
//                 ->groupBy('product_id', 'tienda_id');
//         }])
//         ->with(['galerias'])
//         ->get();

//         // Calcular cantidad total sumando las cantidades de todas las existencias de todas las tiendas
//         $cantidadTotal = $productos->sum(function ($producto) {
//             return $producto->existencias->sum('total_cantidad');
//         });

//         // Obtener cantidad por tienda relacionada al cliente
//         $cantidadPorTienda = $productos->pluck('existencias_por_tienda.*.cantidad_tienda')->flatten()->sum();

//         // Transformar los datos para incluir la imagen destacada y todos los campos del producto
//         $productos->transform(function ($producto) {
//             $galerias = $producto->galerias;

//             $imagenDestacada = $galerias->firstWhere('estatus', 1);

//             return [
//                 'id' => $producto->id,
//                 'codigo' => $producto->codigo,
//                 'estilo' => $producto->estilo,
//                 //'linea' => $producto->linea,
//                 'talla' => $producto->talla,
//                 //'talla_mayor' => $producto->talla_mayor,
//                 //'marca' => $producto->marca,
//                 'ancho' => $producto->ancho,
//                 'color' => $producto->color,
//                 'concepto' => $producto->concepto,
//                 'composicion' => $producto->composicion,
//                 //'temporada' => $producto->temporada,
//                 //'clasificacion' => $producto->clasificacion,
//                 'costo_bruto' => $producto->costo_bruto,
//                 'descuento_1' => $producto->descuento_1,
//                 'descuento_2' => $producto->descuento_2,
//                 'proveedor' => $producto->proveedor,
//                 'suela' => $producto->suela,
//                 'nombre_suela' => $producto->nombre_suela,
//                 'forro' => $producto->forro,
//                 'horma' => $producto->horma,
//                 'planilla' => $producto->planilla,
//                 'tacon' => $producto->tacon,
//                 'inicial' => $producto->inicial,
//                 'promedio' => $producto->promedio,
//                 'actual' => $producto->actual,
//                 'bloqueo_devolucion' => $producto->bloqueo_devolucion,
//                 'precio' => $producto->precio,
//                 'name' => $producto->name,
//                 'detail' => $producto->detail,
//                 'tipo' => $producto->tipo,
//                 'imagen_destacada' => $imagenDestacada ? $imagenDestacada->ruta : null,
//                 'estatus' => $producto->estatus,
//                 'existencias' => $producto->existencias,
//                 'existencias_por_tienda' => $producto->existencias_por_tienda,
//                 'galerias' => $producto->galerias,
//                 'linea' => $producto->linea,
//                 'temporada' => $producto->temporada,
//                 'marca' => $producto->marca,
//                 'descripcion' => $producto->descricion,
//             ];
//         });

//         // Convertir los datos en un formato de array
//         $data = [
//             'productos' => $productos->toArray(),
//             'key' => $key,
//             'cantidadTotal' => $cantidadTotal,
//             'cantidadPorTienda' => $cantidadPorTienda,
//         ];

//         // Enviar la respuesta como JSON utilizando el método sendResponse
//         return $this->sendResponse($data, 'Buscador de productos.');
//     }
// }

public function getProductos(Client $client, $key, $id_usuario = null): JsonResponse
{
    //if(! isset($id_usuario) ) return response()->json(['error_message' => 'Id usuario requerido'], 400);

    if (mb_strlen($key, 'utf-8') > 2) {

        $search = ' ' . $key;
        $usuarioId = $id_usuario;
        $tiendaId = null;
        // validacion para recibir el parametro
        if(isset($usuarioId)) {
            $usuario = User::find($usuarioId);
        }
        // validacion de usuario encontrado
        if(isset($usuario)) {
            $tienda = $usuario->Tienda;
            if($tienda) {
                $tiendaId = $tienda->id;
            } else {
                return $this->sendResponse([], 'El usuario no tiene tienda asignada');
            }
            $tipoCliente = $usuario->tipo;
        }


        $products = Product::
        whereHas('existencias', function ($query) use ($tiendaId) {
            $query->where('cantidad', '>', 0);
            // validamos si existe tienda_id
            if(isset($tiendaId)) {
                $query->where('tienda_id', $tiendaId);
            }
        })
        ->where(function ($query) use ($key) {
            $query->where('estilo', 'LIKE', "%$key%")
                  ->orWhere('nombre_corto', 'LIKE', "%$key%")
                  ->orWhere('codigo', 'LIKE', "%$key%");
        })->orWhereHas('marca', function ($query) use ($key) {
            $query->where('nombre', 'LIKE', "%$key%");
        })->orWhereHas('linea', function ($query) use ($key) {
            $query->where('nombre', 'LIKE', "%$key%");
        })
        ->selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo,
                   nombre_corto,
                   estilo,
                   id,
                   marca_id,
                   imagen_destacada,
                   external_id,
                   precio
                   '
        )
        ->orderBy('id', 'DESC')
        ->take(20)
        ->get();

        //return $this->sendResponse($products, 'Busqueda de productos agrupados.');

        if(isset($tipoCliente)) {
            if($tipoCliente !=3  && $tipoCliente !=2 && $tipoCliente !=4) {
                return $this->sendResponse([], 'El usuario no es valido para consultar lista de precios');
            }

            if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
            if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes');
            if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.distribuidores'); //aqui va es la de independientes

            //Carga la lista de precio dependiendo del tipo de usuario
            $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);

        } else {
            //Si no esta logueado se usa la lista de precios del consumidor final
            $listaDePrecio = $this->CROL_getListaDePrecio($client, Config::get('constants.listas_precios.consumidor_final'));
        }


        $groupedProducts = [];

        foreach ($products as $product) {
            $product->load('marca');
            $product->load('galerias');
            $codigo = $product->codigo;

            if($codigo == "") {
                $codigo = $product->nombre_corto;
            }

            if(isset($listaDePrecio)) {

                $tipoPrecio = 0;

                if($product->temporada) {
                    if($product->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
                    if($product->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
                    if($product->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
                    if($product->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
                }

                $precioListaPrecio =
                    $this->CROL_getPrecioProducto(
                        $listaDePrecio, $product->id, $tipoPrecio, $product->external_id);

            } else {
                $precioListaPrecio = null;
            }

            $tallas = [];
            $ids =[];

            $productos_agrupados = 0;
            foreach ($products->where('codigo', $codigo) as $productWithTalla) {
                $tallas[] = $productWithTalla->tallas;
                $ids[]    = $productWithTalla->id;
                $productos_agrupados++;
            }

            $galerias = $product->galerias;

            // Encuentra la imagen destacada con estado igual a 1
            $imagenDestacada = $galerias->where('estatus', 1)->first();

            if (!$imagenDestacada && $galerias->isNotEmpty()) {
                // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
                $imagenDestacada = $galerias->first();
            }

            // Asignar la imagen destacada al producto
            $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;

            if($precioListaPrecio !=0 && $precioListaPrecio != null) {
                $groupedProducts[$codigo] = [
                    'tiendaId' => $tiendaId,
                    'codigo' => $codigo,
                    'nombre' => $product->estilo,
                    'productos_agrupados' => $productos_agrupados,
                    'precio' => $precioListaPrecio,
                    'imagen_destacada' => $product->imagen_destacada,
                    'galeria' => $product->galerias,
                    'marca'  => $product->marca,
                    'tallas' => $tallas,
                    'ids'    => $ids,
                ];
            }
        }
    }


    return $this->sendResponse($groupedProducts, 'Busqueda de productos agrupados.');
}

    public function getProductobyId($idProducto, $idUsuario, Client $client)
    {
        $usuario = User::find($idUsuario);

        if(! $usuario ) return response()->json(['error_message' => 'Usuario no encontrado'], 400);

        $tipoCliente = $usuario->tipo;

        if( $tipoCliente !=2 && $tipoCliente !=3 && $tipoCliente !=4) return response()->json(['error_message' => 'El tipo de usuario no es compatible con este servicio'], 400);

        $tienda =  $usuario->Tienda;
        $tienda_id = $tienda->id;

        // Obtener los productos con imágenes
        $producto = Product::
        where('id',$idProducto)
        ->take(20)
        ->with(['galerias'])
        ->first();

        if(! $producto ) return response()->json(['error_message' => 'Producto no encontrado'], 400);

        //Obtener Producto del Erp para Extraer las Existencias
        $idListaPrecio = null;

        if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
        if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes'); //TODO: pendiente lista de precios asociados
        if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.distribuidores'); //aqui va es la de independientes

        if( $idListaPrecio == null) return response()->json(['error_message' => 'El tipo de contacto no es valido para la lista de precios'], 400);

        $tipoPrecio = 0;

        if($producto->temporada) {
            if($producto->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
            if($producto->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
            if($producto->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
            if($producto->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
        }

        $productoErp = $this->CROL_getProducto($client, $producto->external_id,
                            [
                                'listaId' => $idListaPrecio,
                                'precioId' => $tipoPrecio,
                                //'sucursalId' => Config::get('constants.sucursalCROL'),
                            ]
                        )['data'];

        $existenciasPorTienda = [];
        $cantidadTotal = 0;
        $cantidadTiendaUsuario = 0;

        foreach($productoErp['existencias'] as $existencia) {

            $almacenId = $existencia['almacenId'] ?? null;
            //validar que exista almacenId
            if($almacenId != null) {

                $tienda = Tiendas::where('external_id', $existencia['almacenId'])->first();
                //validar que exista la tienda
                if(isset($tienda)) {
                    if($existencia['almacenId'] == $tienda->external_id || $existencia['almacenId'] == Config::get('constants.almacenCROL')) {
                        $existenciasPorTienda[] = [
                            'product_id'     => $producto->id,
                            'tienda_id' => $tienda->id,
                            'cantidad_tienda' => $existencia['existencia']
                          ];
                          //guardamos aca solo la existencia que corresponde al almacen del usuario;
                          if($tienda->id == $tienda_id) $cantidadTiendaUsuario = $existencia['existencia'];
                          //acumulamos el total de las existencias de todos los almacenes
                          $cantidadTotal += $existencia['existencia'];
                    }
                }
            }
        }
        $existencias = [
            'product_id'     => $producto->id,
            'total_cantidad' => $cantidadTotal
        ];

        $galerias = $producto->galerias;

        $imagenDestacada = $galerias->firstWhere('estatus', 1);

        $productoWithImagenDestacada = [
            'id_tienda_usuario' => $tienda_id,
            'id' => $producto->id,
            'codigo' => $producto->codigo,
            'estilo' => $producto->estilo,
            //'linea' => $producto->linea,
            'talla' => $producto->talla,
            //'talla_mayor' => $producto->talla_mayor,
            //'marca' => $producto->marca,
            'ancho' => $producto->ancho,
            'color' => $producto->color,
            'concepto' => $producto->concepto,
            'composicion' => $producto->composicion,
            //'temporada' => $producto->temporada,
            //'clasificacion' => $producto->clasificacion,
            'costo_bruto' => $producto->costo_bruto,
            'descuento_1' => $producto->descuento_1,
            'descuento_2' => $producto->descuento_2,
            'proveedor' => $producto->proveedor,
            'suela' => $producto->suela,
            'nombre_suela' => $producto->nombre_suela,
            'forro' => $producto->forro,
            'horma' => $producto->horma,
            'planilla' => $producto->planilla,
            'tacon' => $producto->tacon,
            'inicial' => $producto->inicial,
            'promedio' => $producto->promedio,
            'actual' => $producto->actual,
            'bloqueo_devolucion' => $producto->bloqueo_devolucion,
            'precio' => $productoErp['precio'],
            'name' => $producto->name,
            'detail' => $producto->detail,
            'tipo' => $producto->tipo,
            'imagen_destacada' => $imagenDestacada ? $imagenDestacada->ruta : null,
            'estatus' => $producto->estatus,
            'external_id' => $producto->external_id,
            'existencias' => $existencias, //$producto->existencias,
            'existencias_por_tienda' => $existenciasPorTienda, //$producto->existencias_por_tienda,
            'galerias' => $producto->galerias,
            'linea' => $producto->linea,
            'temporada' => $producto->temporada,
            'marca' => $producto->marca,
            'descripcion' => $producto->descricion,
        ];


        // Convertir los datos en un formato de array
        $data = [
            'productos' => $productoWithImagenDestacada,
            'key' => $idProducto,
            'cantidadTotal' => $cantidadTotal, //$cantidadTotal,
            'cantidadPorTienda' => $cantidadTiendaUsuario, //$cantidadPorTienda,
        ];

        // Enviar la respuesta como JSON utilizando el método sendResponse
        return $this->sendResponse($data, 'Producto por ID');
    }


     // $idProducto, $idUsuario, Client $client
    public function getProductosbyId(Request $request, Client $client)
    {     

        $dataInf = $request->input('data');
        $userId = $request->input('userId');
        $idUsuario = $userId;      
                    
        $usuario = User::find($idUsuario);

        if(! $usuario ) return response()->json(['error_message' => 'Usuario no encontrado'], 400);

        $tipoCliente = $usuario->tipo;

        if( $tipoCliente !=2 && $tipoCliente !=3 && $tipoCliente !=4) return response()->json(['error_message' => 'El tipo de usuario no es compatible con este servicio'], 400);

        $tienda =  $usuario->Tienda;
        $tienda_id = $tienda->id;

        // Obtener los productos con imágenes
        $productos = [];
        $existenciasArr = [];
        foreach ($dataInf as $productoAr) {
            $producto = Product::where('id',$productoAr['idProducto'])
                        ->take(20)        
                        ->first();  
            if ($producto) {array_push($productos, $producto);}  
            
            


            // } PARA PROBAR SI LA CANTIDAD ES 0

            // if($producto->id = 7116) {
            //     $cantidadTotal = 0;
            // }  else {             

                //Obtener Producto del Erp para Extraer las Existencias
                $idListaPrecio = null;

                if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
                if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes');
                if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');

                if( $idListaPrecio == null) return response()->json(['error_message' => 'El tipo de contacto no es valido para la lista de precios'], 400);

                $tipoPrecio = 0;

                if($producto->temporada) {
                    if($producto->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
                    if($producto->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
                    if($producto->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
                    if($producto->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
                }
        
                $productoErp = $this->CROL_getProducto($client, $producto->external_id,                
                    [
                        'listaId' => $idListaPrecio,
                        'precioId' => $tipoPrecio,
                        //'sucursalId' => Config::get('constants.sucursalCROL'),
                    ]
                )['data'];
                            
        
                $existenciasPorTienda = [];
                $cantidadTotal = 0;
                $cantidadTiendaUsuario = 0;
        
                foreach($productoErp['existencias'] as $existencia) {
        
                    $almacenId = $existencia['almacenId'] ?? null;
                    //validar que exista almacenId
                    if($almacenId != null) {
        
                        $tienda = Tiendas::where('external_id', $existencia['almacenId'])->first();
                        //validar que exista la tienda
                        if(isset($tienda)) {
                            if($existencia['almacenId'] == $tienda->external_id || $existencia['almacenId'] == Config::get('constants.almacenCROL')) {                           
                                $existenciasPorTienda[] = [
                                    'product_id'     => $producto->id,
                                    'tienda_id' => $tienda->id,
                                    'cantidad_tienda' => $existencia['existencia']
                                ];
                                //guardamos aca solo la existencia que corresponde al almacen del usuario;
                                if($tienda->id == $tienda_id) $cantidadTiendaUsuario = $existencia['existencia'];
                                //acumulamos el total de las existencias de todos los almacenes
                                $cantidadTotal += $existencia['existencia'];
                                
                            }
                        }
                    }
                }
            
                if ($productoAr['cantidadSolicitada'] == 0) {
                    return response()->json([
                        'success' => false,
                        'data' => 'La cantidad solicitada no puede ser 0',                
                        'message' => 'Error'
                    ]);
                }    


                
                //  } #PARA PROBAR SI LA CANTIDAD ES 0



                $cantidadDisponible = min($cantidadTotal, $productoAr['cantidadSolicitada']);
                if ($cantidadDisponible < 0) {
                    $cantidadDisponible = 0;
                }
                

                $estatus = "";
                
                if ($productoAr['cantidadSolicitada'] == $cantidadTotal || $productoAr['cantidadSolicitada'] == $cantidadDisponible) {
                    $estatus = "Completo";
                } else if ($productoAr['cantidadSolicitada'] > $cantidadTotal && $cantidadTotal != 0 || $cantidadTotal !== 0){
                    $estatus = "Parcial";
                } else if ($productoAr['cantidadSolicitada'] > $cantidadTotal && $cantidadTotal == 0){
                    $estatus = "No";
                }

           

            $existencias = [
                'product_id'     => $producto->id,
                'total_cantidad' => $cantidadTotal,
                'cantidad_solicitada' => $productoAr['cantidadSolicitada'],
                'cantidadDisponible' => $cantidadDisponible,
                'disponible' => $estatus
            ];               
    
            array_push($existenciasArr, $existencias);    
    
        }
                    
        return response()->json([
            'success' => true,
            'data' => $existenciasArr,                
            'message' => 'Productos por ID'
        ]);

        


       

      


    }



    public function totalExistencias($productoID)
    {
        // Obtener las existencias del producto en todas las tiendaD
        $existencias = Existencias::where('product_id', $productoID)->get();
        $data = [];

        foreach ($existencias as $existencia) {
            $tienda = Tiendas::where('id', $existencia->tienda_id)->first();
            $tienda = $tienda;
            $data[] = [
                'tienda' => [
                    'nombre' => $tienda->nombre,
                    'cantidad' => $existencia->cantidad,
                ],
            ];
        }

        return response()->json($data);
    }

    public function getPedidoAbierto(Request $request)
    {
        $cliente = User::find($request->clienteID);
        $tienda_id = $cliente->tienda_id;
        $config = Empresas::find(1);

        //dd('hola');

        if ($cliente->tipo == 3) {
            $vendedor_id = null;
            $distribuidor_id = $cliente->id;
        } elseif ($cliente->tipo == 2) {
            $vendedor_id = $cliente->id;
            $distribuidor_id = $cliente->getDistribuidor();
        }

        $pedido = Pedidos::where('distribuidor_id', $distribuidor_id)
            ->where('vendedor_id', $vendedor_id)
            ->where('estatus', 0)
            ->first();

        if (!empty($pedido)) {
            $productosPedido = $pedido->productosPedidos;
            $productos = Product::whereIn('id', $productosPedido->pluck('product_id'))
                ->take(20)
                ->with([
                    'linea',
                    'marca',
                    'temporada',
                    'descripcion',
                    'existencias' => function ($query) {
                        $query->select('product_id', \DB::raw('SUM(cantidad) as total_cantidad'))
                            ->groupBy('product_id');
                    },
                    'existencias_por_tienda' => function ($query) use ($tienda_id) {
                        $query->select('product_id', \DB::raw('SUM(cantidad) as cantidad_tienda'))
                            ->where('tienda_id', $tienda_id)
                            ->groupBy('product_id');
                    }
                ])
                ->get();

            $productosData = [];
            foreach ($productos as $producto) {
                $existencias = $producto->existencias;
                $cantidadTotal = $existencias->sum('total_cantidad');
                $existenciasPorTienda = $producto->existencias_por_tienda;

                $productosData[] = [
                    'id' => $producto->id,
                    'nombre' => $producto->name,
                    'existencias' => $existencias,
                    'existencias_por_tienda' => $existenciasPorTienda,
                    'cantidadTotal' => $cantidadTotal,
                    'codigo' => $producto->codigo,
                    'estilo' => $producto->estilo,
                    //'linea' => $producto->linea,
                    //'talla_menor' => $producto->talla_menor,
                    'talla' => $producto->talla,
                    //'marca' => $producto->marca,
                    'color' => $producto->color,
                    'costo_bruto' => $producto->costo_bruto,
                    'precio' => $producto->precio,
                ];
            }

            // dd($productosData->count());

            $data = [
                'estatus' => true,
                'pedido' => $pedido,
                'productos' => $productosData,
                'key_mercadopago' => $config->mp_public_key,
            ];
            return $this->sendResponse($data, 'Pedido abierto');
        }

        $data = [
            'estatus' => false
        ];

        return $this->sendResponse($data, 'No hay pedido abierto');
    }

    public function eliminarProducto($pedidoID, $producto, $user)
    {

        $productosPedido = ProductosPedido::where('pedido_id', $pedidoID)
            ->where('product_id', $producto)
            ->where('user_id', $user)
            ->first();
        if (empty($productosPedido)) {
            return response()->json([
                'message' => 'El producto no existe en el pedido.'
            ], 200);
        }
        $cliente = User::find($user);

        $productoID = $productosPedido->product_id;
        $cantidadSolicitada = $productosPedido->cantidad_solicitada;
        $tiendaID = $productosPedido->user->tienda_id;

        $existencia = Existencias::where('product_id', $productoID)
            ->where('tienda_id', $tiendaID)
            ->first();

        if ($existencia) {
            $existencia->cantidad += $cantidadSolicitada;
            $existencia->save();
        }

        $productosPedido->delete();

        $pedido = Pedidos::with('productosPedidos')->find($pedidoID);
        $config = Empresas::find(1);

        $montoTotal = 0;
        $montoTotalNeto = 0;
        $descuentoTotal = 0;

        foreach ($pedido->productosPedidos as $productoPedido) {
            $montoTotal += $productoPedido->monto;
            $montoTotalNeto += $productoPedido->neto;
            $descuentoTotal += $productoPedido->descuento;
        }

        $pedido->monto_total = $montoTotal;
        $pedido->monto_neto = $montoTotalNeto;
        $pedido->monto_descuento_cliente = $descuentoTotal;
        // $pedido->monto_paqueteria = $paqueteria;
        $pedido->save();
        //dd($config->mp_access_token);
        // Actualización de la referencia de MercadoPago
        if ($pedido->getMonto() == 0) {
            $pedido->delete();
        } else {
            SDK::setAccessToken($config->mp_access_token);
            $preference = new Preference();

            // Configuración de las URLs de redireccionamiento
            $preference->back_urls = array(
                "success" => Route('mercadopago.success'),
                "failure" => Route('mercadopago.failure'),
                "pending" => Route('mercadopago.pending')
            );
            $preference->auto_return = "approved";
            $preference->expires = false;

            // Configuración del artículo a pagar
            $item = new Item();
            $item->title = 'Pago por pedido Rinna Bruni N°: ' . $pedidoID;
            $item->quantity = 1;
            $item->unit_price = $pedido->getMonto();
            $preference->items = array($item);

            // Configuración del pagador (payer)
            $payer = new Payer();
            $payer->email = $cliente->email;
            $payer->identification = (object) array(
                "type" => "INE",
                "number" => "12345678"
            );
            $preference->payer = $payer;
            $preference->save();
            $preferenciaID = $preference->id;

            $mercadopago = Mercadopago::where('pedido_id', $pedidoID)->first();
            if ($mercadopago == null) {
                $mercadopago = new Mercadopago();
                $mercadopago->pedido_id = $pedidoID;
            }

            $mercadopago->referencia = $preferenciaID;
            $mercadopago->precio = $pedido->getMonto();
            $mercadopago->save();
            $pedido->mercadopago_id = $mercadopago->id;
            $pedido->referencia_mercadopago = $preferenciaID;
            $pedido->save();
        }

        $productos = Product::where('id', $productoID)
            ->take(20)
            ->with(['existencias' => function ($query) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as total_cantidad'))
                    ->groupBy('product_id');
            }])
            ->with(['existencias_por_tienda' => function ($query) use ($tiendaID) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as cantidad_tienda'))
                    ->where('tienda_id', $tiendaID)
                    ->groupBy('product_id');
            }])
            ->get();

        $cantidadTotal = $productos->sum(function ($producto) {
            return $producto->existencias->sum('total_cantidad');
        });

        $cantidadPorTienda = $productos->pluck('existencias_por_tienda.*.cantidad_tienda')->flatten()->sum();

        return response()->json([
            'cantidadPorTienda' => $cantidadPorTienda,
            'producto' => $productos,
            'cantidadTotal' => $cantidadTotal,
            'pedido' => $pedido,
            'key_mercadopago' => $config->mp_public_key,
            'message' => 'El producto ha sido eliminado exitosamente'
        ], 200);
    }

    public function cuponesVale(Request $request)
    {

        //dd($request->all());
        $cliente = User::find($request->clienteID);


        $tienda_id = $cliente->tienda_id;
        $pedidoID = $request->pedidoID;
        $codigoCupon = $request->cupon;
        $codigoVale = $request->vale;
        $tipo = null;
        $pedido = Pedidos::find($pedidoID);

        $cupon = false;
        $cuponAplicado = false;

        $vale = false;
        $valeAplicado = false;
        // Obtener el cupón o vale válido para el cliente y el pedido


        if ($codigoCupon) {
            if ($pedido->cupon == null) {
                $cupon = Cupons::where('codigo', $codigoCupon)
                    ->where(function ($query) use ($cliente) {
                        $query->whereNull('user_id')
                            ->orWhere('user_id', $cliente->id);
                    })
                    ->whereIn('tipo', [1, 2])
                    ->where('cantidad_usos', '>', 0)
                    ->where('estatus', '=', 1)
                    ->first();
                $cuponAplicado = false;

                if ($cupon == '') {
                    return response()->json(["msg" => "Cupon invalido"]);
                }

            } else {
                $cupon = null;
                $cuponAplicado = true;
            }
        }



        if ($cupon) {
            $total_pedido = $pedido->monto_total;
            if ($cupon->tipo == 1) {
                $monto = $cupon->monto;
                $pedido->monto_cupon = $monto;
                $pedido->tipoCupon = 2;
                $pedido->montoCuponAplicado = $monto;
                // $pedido->vale =  $codigoVale;
                $pedido->cupon =  $codigoCupon;
                $pedido->monto_neto = $pedido->getMonto();
                $pedido->save();
                $cupon->cantidad_usos = $cupon->cantidad_usos - 1;
                $cupon->save();
                $tipo = 1;
            } elseif ($cupon->tipo == 2) {
                $porcentaje = $cupon->porcentaje;
                $monto = $total_pedido * ($porcentaje / 100);
                $pedido->monto_cupon = $monto;
                $pedido->tipoCupon = 2;
                $pedido->vale =  $codigoVale;
                $pedido->cupon =  $codigoCupon;
                $pedido->porcentjeCuponAplicado = $porcentaje;
                $pedido->monto_neto = $pedido->getMonto();
                $pedido->save();
                $cupon->cantidad_usos = $cupon->cantidad_usos - 1;
                $cupon->save();
                $tipo = 2;
            }
            $cuponAplicado = true;
        }


        if ($codigoVale > 1) {
            if ($pedido->vale == null) {
                $vale = Cupons::where('codigo', $codigoVale)
                    ->where(function ($query) use ($cliente) {
                        $query->whereNull('user_id')
                            ->orWhere('user_id', $cliente->id);
                    })
                    ->where('tipo', 3)
                    ->where('cantidad_usos', '>', 0)
                    ->where('estatus', '=', 1)
                    ->first();
                $valeAplicado = false;

                if ($vale == '') {
                    return response()->json(["msg" => "Vale invalido"]);
                }

            } else {
                // $vale = Cupons::where('codigo', $pedido->vale)->first();
                $vale = null;
                $valeAplicado = true;

            }


            if ($vale) {
                $total_pedido = $pedido->monto_total;
                $monto = $vale->monto;
                $pedido->monto_vale = $monto;
                $pedido->vale =  $codigoVale;
                $pedido->save();
                $vale->cantidad_usos = $vale->cantidad_usos - 1;
                $vale->save();
                $valeAplicado = true;
            }

        }





        $config = Empresas::find(1);
        SDK::setAccessToken($config->mp_access_token);
        $preference = new Preference();

        // Configuración de las URLs de redireccionamiento
        $preference->back_urls = array(
            "success" => Route('mercadopago.success'),
            "failure" => Route('mercadopago.failure'),
            "pending" => Route('mercadopago.pending')
        );
        $preference->auto_return = "approved";
        $preference->expires = false;

        // Configuración del artículo a pagar
        $item = new Item();
        $item->title = 'Pago por pedido Rinna Bruni N°: ' . $pedidoID;
        $item->quantity = 1;
        $item->unit_price = $pedido->getMonto();
        $preference->items = array($item);

        // Configuración del pagador (payer)
        $payer = new Payer();
        $payer->email = $cliente->email;
        $payer->identification = (object) array(
            "type" => "INE",
            "number" => "12345678"
        );
        $preference->payer = $payer;
        $preference->save();
        $preferenciaID = $preference->id;

        $mercadopago = Mercadopago::where('pedido_id', $pedidoID)->first();
        if ($mercadopago == null) {
            $mercadopago = new Mercadopago();
            $mercadopago->pedido_id = $pedidoID;
        }

        $mercadopago->referencia = $preferenciaID;
        $mercadopago->precio = $pedido->getMonto();
        $mercadopago->save();

        $pedido->mercadopago_id = $mercadopago->id;
        $pedido->referencia_mercadopago = $preferenciaID;
        $pedido->save();


        $data = [
            'cuponStatus' => $cupon ? true : false,
            'cupon' => $cupon,
            'cuponAplicado' => $cuponAplicado,
            'tipoCupon' => $tipo,
            'valeStatus' => $vale ? true : false,
            'vale' => $vale,
            'valeAplicado' => $valeAplicado,
            'pedido' => $pedido,
            'key_mercadopago' => $config->mp_public_key,
        ];


        return $this->sendResponse($data, 'Cupones y vales');
    }

    public function getNotifications(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications; // Convertir las notificaciones en una matriz

        $notificationCount = count($notifications); // Contar las notificaciones

        return response()->json([
            'notifications' => $notifications,
            'count' => $notificationCount
        ]);
    }
    public function markAsRead(Request $request)
    {
        // Marcar las notificaciones como leídas
        Notification::where('user_id', $request->user()->id)->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    protected function ejecutarNotificaciones()
    {

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ]);

        // Enviar la notificación a través de Pusher
        $pusher->trigger('notifications', 'new-notification', ['message' => 'Tienes una nueva notificación.']);
    }

    protected function crearReferenciaMP(Request $request)
    {
        $config = Empresas::find(1);
        SDK::setAccessToken($config->mp_access_token);
        $pedido = Pedidos::find($request->pedidoID);

        $preference = new Preference();

        $item = new Item();
        $item->title = 'Pago por pedido N°: ' . $request->pedidoId;
        $item->quantity = 1;
        $item->unit_price = $pedido->getMonto();
        $preference->items = array($item);

        $preference->save();

        return response()->json(['preference_id' => $preference->id]);
    }

    public function editarPedido(Request $request)
    {
        $input = $request->all();

        $pedido = Pedidos::find($input['id_pedido']);
        $pedido->update($input);
        return response()->json([
            'pedido' => $pedido
        ]);
    }

    public function editarProductoPedido(Request $request)
    {
        $input = $request->all();
        $cantidadSolicitada = $input['cantidad_solicitada'];


        $pedido = Pedidos::where('id', $input['pedido_id'])->where('estatus', '=', 0)->first();

        if (isset($pedido)) {

            $product = Product::where('id', $input['product_id'])->first();

            $producto_pedido = ProductosPedido::where('pedido_id', $input['pedido_id'])
                ->where('product_id', $input['product_id'])->first();


            if ($producto_pedido->cantidad_solicitada == $cantidadSolicitada) {
                return response()->json([
                    'msg' => "La cantidad solicitada es la misma para este producto pedido"
                ]);
            }

            if ($cantidadSolicitada < $producto_pedido->cantidad_solicitada) {
                $cantidad = $producto_pedido->cantidad_solicitada - $cantidadSolicitada;

                $existencia = Existencias::where('product_id', $input['product_id'])
                    ->where('tienda_id', $input['tienda_id'])
                    ->first();


                $existencia->cantidad = $existencia->cantidad + $cantidad;
                $existencia->save();
            }

            if ($cantidadSolicitada > $producto_pedido->cantidad_solicitada) {
                $cantidad = $cantidadSolicitada - $producto_pedido->cantidad_solicitada;

                $existencia = Existencias::where('product_id', $input['product_id'])
                    ->where('tienda_id', $input['tienda_id'])
                    ->first();

                // if ($existencia->cantidad < $cantidad) {
                //     return response()->json([
                //         'msg' => "No hay existencias disponibles."
                //     ]);
                // }

                $existencia->cantidad = $existencia->cantidad - $cantidad;
                $existencia->save();

            }

            $precio = $product->precio * $request->cantidad_solicitada; // Precio normal
            $precio_neto = $product->precio * $request->cantidad_solicitada; // Precio neto
            $precioCliente = $request->descuento * $request->cantidad_solicitada; // Precio descuento

            // return response()->json($precio);

            $input['monto'] = $precio;
            $input['descuento'] = $precioCliente;
            $input['neto'] = $precio_neto;
            $producto_pedido->update($input);

            //actualizar precios en la tabla pedido
            $montoTotal = $pedido->productosPedidos()->sum('monto');
            $montoDescuentoCliente = $pedido->productosPedidos()->sum('descuento');
            $montoNeto = $pedido->productosPedidos()->sum('neto');

            $pedido->monto_total = $montoTotal;
            $pedido->monto_descuento_cliente = $montoDescuentoCliente;
            $pedido->monto_neto = $montoNeto;
            $pedido->save();

            return response()->json([
                'producto_pedido' => $producto_pedido
            ]);

        } else {
            return response()->json([
                'msg' => "Este pedido ya no puede ser modificado"
            ]);
        }




    }

    public function revision(Request $request, Client $client)
    {
        $data = $request->all();

        $pedidoId = $data['id'];

        if(!isset($pedidoId)) { //validamos que recibimos pedidoId
            return response()->json([
                "msg" => "pedidoId no encontrado"
            ]);
        }

        $pedido = Pedidos::find($pedidoId);

        if (!isset($pedido)) { //valida pedido encontrado
           return response()->json([
            "msg" => "pedido no encontrado"
           ]);
        }

        /*Creacion de Pedido en el ERP*/

        $statusCreaPedidoCrol = $this->CROL_createPedido($request, $client);



        if(! is_array($statusCreaPedidoCrol) ) {
            return response()->json(['error_message' => 'Hubo un error creando el pedido en el ERP'], 400);
        }

        if($statusCreaPedidoCrol[0] == 200 && $statusCreaPedidoCrol[1]['resultado'] ) {

            $datosPedidoErp = $statusCreaPedidoCrol[1]['data'];

            $data['external_folio']          = $datosPedidoErp['folio'];
            $data['external_transaccion_id'] = $datosPedidoErp['transaccionId'];

            $pedido->update($data);
            $pedidoActualizado = Pedidos::find($pedidoId);

            return response()->json(["order_update" => $pedidoActualizado]);
        }
        else {
            return response()->json(['error_message' => 'Hubo un error creando el pedido en el ERP'], 400);
        }


    }

}

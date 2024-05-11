<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\ProductosPedido;
use App\Models\Product;
use App\Models\ProductosNegados;
use App\Models\ProductosGestion;
use App\Models\User;
use App\Models\Existencias;
use App\Models\Empresas;
use App\Models\Mercadopago;
use Auth;
use App\Models\Notification;
use App\Helpers\NotificationHelper;
use App\Models\Direcciones;
use Pusher\Pusher;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use Illuminate\Http\Response;
use App\Traits\TraitPedidos;



class PedidosController extends Controller
{
    use TraitPedidos;

    function __construct()
    {
        $empresa = Empresas::find(1);
        $this->publicKey = $empresa->mp_public_key;
        $this->accessToken = $empresa->mp_access_token;
        $this->costo_paqueteria = Empresas::find(1)->costo_paqueteria;

        $this->middleware('permission:pedidos-list|pedidos-create|pedidos-edit|pedidos-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:pedidos-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pedidos-edit', ['only' => ['edit', 'update',]]);
        $this->middleware('permission:pedidos-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if (\Auth::user()->isAdmin() ||
            \Auth::user()->isDistribuidor() ||
            \Auth::user()->isVendedor() ||
            \Auth::user()->isVendedorLibre()
        ){
            $perPage = $request->query('perPage', 10);
            $sortBy = $request->query('sortBy', 'id');
            $sortOrder = $request->query('sortOrder', 'desc');
            $pedidoId = $request->query('pedidoId', '');
            $estatus = $request->query('estatus', '');
            $fechaCompra = $request->query('fechaCompra', '');

            $query = Pedidos::query();

            if ($pedidoId) {
                $query->where('id', 'like', '%' . $pedidoId . '%');
            }

            if ($estatus !== '') {
                $query->where('estatus', $estatus);
            }

            if ($fechaCompra) {
                $query->whereDate('created_at', '>=', $fechaCompra);
            }

            if(\Auth::user()->isDistribuidor()) {
                $query->where('distribuidor_id', \Auth::user()->id);
            }

            if(\Auth::user()->isVendedor() || \Auth::user()->isVendedorLibre()) {
                $query->where('vendedor_id', \Auth::user()->id);
            }

            $pedidos = $query->orderBy($sortBy, $sortOrder)
                        ->paginate($perPage);


            $pedidos->appends([
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'pedidoId' => $pedidoId,
                'estatus' => $estatus,
                'fechaCompra' => $fechaCompra
            ]);

            return view('pedidos.index', [
                'pedidos' => $pedidos,
                'i' => ($pedidos->currentPage() - 1) * $pedidos->perPage(),
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'pedidoId' => $pedidoId,
                'estatus' => $estatus,
                'fechaCompra' => $fechaCompra
            ]);
        } else {
            return redirect()
                ->route('inicio')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }
    }

    public function create()
    {
        $usuario = \Auth::user();
        $usuario = json_encode($usuario);
        $bloqueo_pedido = (is_null(Auth::user()->bloqueo_pedido) || Auth::user()->bloqueo_pedido !== 0) ? 1: 0;

        if( \Auth::user()->isAdmin() ||
            \Auth::user()->isDistribuidor() ||
            \Auth::user()->isVendedor() ||
            \Auth::user()->isVendedorLibre()
        ) {
            if ( \Auth::user()->isAdmin() ||
                 \Auth::user()->isDistribuidor()
            ) {
                return view('pedidos.create', compact('usuario', 'bloqueo_pedido'));
            } else {
                return view('pedidosUsuario.create', compact('usuario', 'bloqueo_pedido'));
            }

        } else {
            return redirect()->route('inicio')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $primerProducto = false;
        $pedidoID = $request->input('pedidoID');
        $cliente = User::find($request->user_id);
        $existencia = Existencias::where('product_id', $request->product_id)->where('tienda_id', $cliente->tienda_id)->first();

        $cantidad_a_surtir  = 0;

        $cantidad_a_surtir = ($request->cantidad > $request->cantidad_disponible) ? $request->cantidad_disponible: $request->cantidad;

        $cantidad_negada   = ($request->cantidad_solicitada > $request->cantidad) ? $request->cantidad_solicitada - $request->cantidad : 0;

        $precio         = $request->precio_socio * $cantidad_a_surtir;
        $precio_neto    = $request->precio_final * $cantidad_a_surtir;
        $precioCliente  = $request->descuento * $cantidad_a_surtir;
        $config         = Empresas::find(1);
        //$paqueteria = $config->costo_paqueteria * $request->total_cajas; //Pendiente de agregar
        // dd($cantidad_a_surtir);

        if ($pedidoID === null) {
            $primerProducto = true;
            $pedido = new Pedidos();
            if ($cliente->tipo == 3) {
                $pedido->distribuidor_id = $cliente->id;
            } elseif ($cliente->tipo == 2) {
                $pedido->vendedor_id  = $cliente->id;
                if($cliente->distribuidor_id != null) {
                    $pedido->distribuidor_id = $cliente->getDistribuidor()->id;
                }
            }
            $pedido->estatus = 0;
            $pedido->creado_por = Auth::user()->id;
            $pedido->monto_total +=  $precio;
            $pedido->monto_neto +=  $precio_neto;
            $pedido->monto_descuento_cliente +=  $precioCliente;
            $pedido->metodo_pago = $request->metodo_pago;
            $pedido->push();
            $pedidoID = $pedido->id;
        } else {
            $pedido = Pedidos::find($pedidoID);
            $pedido->monto_total +=  $precio;
            $pedido->monto_neto +=  $precio_neto;
            $pedido->monto_descuento_cliente +=  $precioCliente;
            $pedido->push();
        }

        //existencias del producto
        if (empty($existencia)) {
            $existencia = new Existencias();
            $existencia->product_id = $request->product_id;
            $existencia->tienda_id = $cliente->tienda_id;
            $existencia->cantidad = $cantidad_a_surtir;
            $existencia->save();
        } else {
            $existencia->cantidad = ($existencia->cantidad - $cantidad_a_surtir);
            $existencia->save();
        }

        $pedido = Pedidos::find($pedidoID);

        $productoPedido = new ProductosPedido();
        $productoPedido->pedido_id = $pedidoID;
        $productoPedido->user_id = $request->user_id;
        $productoPedido->product_id = $request->product_id;
        $productoPedido->cantidad_solicitada = $cantidad_a_surtir;
        $productoPedido->cantidad_pendiente = $request->cantidad_pendiente;
        $productoPedido->cantidad_negada = $cantidad_negada;
        $productoPedido->monto = $precio;
        $productoPedido->neto =  $precio_neto;
        $productoPedido->descuento = $precioCliente;
        $productoPedido->save();

        // productos negados y pendientes
        if ($cantidad_negada > 0) {

            $productoNegado = new ProductosNegados();
            $productoNegado->pedido_id = $pedidoID;
            $productoNegado->user_id = $cliente->id;
            $productoNegado->tienda_id = $cliente->tienda_id;
            $productoNegado->product_id = $request->product_id;
            $productoNegado->cantidad = $cantidad_negada;
            $productoNegado->save();
        }
        //aqui faltaria descontar de las tiendas en que exista queda pendiente por desarrollar
        if ($request->cantidad_pendiente > 0) {

            $productoNegado = new ProductosGestion();
            $productoNegado->pedido_id = $pedidoID;
            $productoNegado->user_id = $cliente->id;
            $productoNegado->tienda_id = $cliente->tienda_id;
            $productoNegado->product_id = $request->product_id;
            $productoNegado->cantidad = $request->cantidad_pendiente;
            $productoNegado->save();
        }


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
        $getMonto = $pedido->getMonto();

        if ($pedido->getMonto() == 0) {
            $getMonto = 50;
        }
        $item->unit_price = $getMonto;
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
        $mercadopago->precio = $getMonto;
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

        if ($primerProducto) {
            return response()->json(['id' => $pedidoID, 'estatus' => 'Abierto', 'pedido' => $pedido, 'key_mercadopago' => $config->mp_public_key, 'message' => 'Producto agregado al pedido nuevo.']);
        } else {
            return response()->json(['id' => $pedidoID, 'pedido' => $pedido, 'key_mercadopago' => $config->mp_public_key, 'estatus' => 'Abierto', 'message' => 'Producto agregado al pedido existente.']);
        }
    }

    public function solicitar(Request $request)
    {
        $config = Empresas::find(1);
        $pedidoID = $request->pedido_id;
        $cliente = User::find($request->id_usuario);
        $pedido = Pedidos::find($pedidoID);
        $pedido->metodo_pago = $request->metodo_pago;
        $pedido->observacion = $request->observacion;
        $pedido->created_at = now();


        if ($request->tipo_envio == 'domicilio' and \Auth::user()->isAdmin()) {

            $pedido->direccion_cliente = $request->direccion_cliente;
            $pedido->total_cajas = $request->total_cajas;
            $pedido->monto_paqueteria = $config->costo_paqueteria * $request->total_cajas;
            $pedido->tipo_envio = 'domicilio';
        } else if ($request->tipo_envio == 'domicilio') {

            $pedido->monto_paqueteria = $config->costo_paqueteria * $request->total_cajas;
            $pedido->direccion_cliente = $request->direccion_cliente;
            $pedido->tipo_envio = 'domicilio';
        } else if ($request->tipo_envio == 'tienda' and \Auth::user()->isAdmin()) {

            $pedido->total_cajas = 0;
            $pedido->monto_paqueteria = 0;//$config->costo_paqueteria * $request->total_cajas;
            $pedido->tipo_envio = 'tienda';
        } else {
            $pedido->total_cajas = 0;
            $pedido->monto_paqueteria = 0;//$config->costo_paqueteria * $request->total_cajas;
            $pedido->tipo_envio = 'tienda';
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

        if( $pedido->save() ){
            $this->saveComprobante($request);
        }

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
        // if (\Auth::user()->isAdmin()) {
        //     return redirect()->route('pedidos.index')->with('success', 'El pedido se ha procesado exitosamente.');
        // } else {
        //     return redirect()->route('pedidos.index')->with('success', 'El pedido se ha procesado exitosamente.');
        // }

        return redirect()->route('pedidos.index')
        ->with('success', 'El pedido se ha ' . ($request->accion_pedido == 'guardar' ? 'guardado' : 'procesado') .' exitosamente.');



    }

    public function show(Pedidos $pedido)
    {
        $id_cliente = is_null($pedido->distribuidor_id) ? $pedido->vendedor_id : $pedido->distribuidor_id;
        $cliente    = User::find($id_cliente);
        $direccion  = Direcciones::where('user_id', $id_cliente)->first();
        $detalles   = ProductosPedido::where('pedido_id', $pedido->id)->get();

        foreach ($detalles as $detalle) {
            $productoID = $detalle->product_id;
            $detalle->producto = Product::find($productoID);
        }

        if (\Auth::user()->isAdmin()) {
            return view('pedidos.show', compact('pedido', 'cliente', 'direccion', 'detalles'));
        } elseif (\Auth::user()->isDistribuidor()) {
            return view('pedidosUsuario.show', compact('pedido', 'cliente', 'direccion', 'detalles'));
        } else {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
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
            if ($pedido->tipo_envio == null) {
                $pedido->tipo_envio = 'Tienda';
            }
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
    public function edit(Pedidos $pedido)
    {
        if ($pedido->distribuidor_id != null) {
            $cliente = User::find($pedido->distribuidor_id);
            if($pedido->vendedor_id != null) {
                $cliente = User::find($pedido->vendedor_id);
            }
        } else {
            $cliente = User::find($pedido->vendedor_id);
        }

        if (\Auth::user()->isAdmin()) {
            return view('pedidos.edit', compact('pedido', 'cliente'));
        } elseif (\Auth::user()->isDistribuidor()) {
            return view('pedidosUsuario.edit', compact('pedido','cliente'));
        } else {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
    }

    public function destroy(Pedidos $pedido)
    {
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

        $pedido->delete();

        $productosNegados = ProductosNegados::where('pedido_id', $pedido->id)->forceDelete();
        $productosGestion = ProductosGestion::where('pedido_id', $pedido->id)->forceDelete();

        return response()->json(['message' => 'El pedido ha sido eliminado exitosamente'], 200);
    }

    public function eliminarProducto(Request $request, $pedidoID, $producto, $user)
    {

        $productosPedido = ProductosPedido::where('pedido_id', $pedidoID)
            ->where('product_id', $producto)
            ->where('user_id', $user)
            ->first();
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

        $pedido = Pedidos::find($pedidoID);
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
        $pedido->save();

        ProductosNegados::where('pedido_id', $pedido->id)
            ->where('product_id', $producto)->forceDelete();
        ProductosGestion::where('pedido_id', $pedido->id)
            ->where('user_id', $user)->forceDelete();

        // Actualización de la referencia de MercadoPago
        if ($pedido->getMonto() <= 0) {
            $pedido->delete();
            // aquiel retorno a la vista con el pedido eliminado
            return response()->json([
                'status' => 'eliminado',
                'message' => 'El pedido se ha eliminado exitosamente por que solo habia un producto en el pedido',
            ], 200);
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

    public function uploadImagen(Request $request,)
    {
        return response()->json(['message' => 'Imágenes cargadas exitosamente.']);
    }

    public function updatePreferenceIdMercadoPago(Request $request)
    {
        try {

            $request->validate([
                'pedidoId' => 'required',
                'usuarioId' => 'required',
                'costoPaqueteria' => 'required',
            ],[
                'pedidoId.required' => 'El pedido es requerido',
                'usuarioId.required' => 'El usuario es requerido',
                'costoPaqueteria.required' => 'El costo de la paqueteria es requerido'
            ]);

            $empresa = Empresas::where('id', 1)->first();

            if(!isset($empresa)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'Error al obtener empresa'
                ], 500);
            }

            $pedidoId = $request->input('pedidoId');

            $pedido = Pedidos::where('id', $pedidoId)->first();

            if(!isset($pedido)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'El pedido no existe'
                ], 500);
            }

            $userId = $request->input('usuarioId');

            $usuario = User::where('id', $userId)->first();

            if(!isset($usuario)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'El usuario no existe'
                ], 500);
            }

            //actualizar el costo de la paqueteria
            $costoPaqueteria = $request->input('costoPaqueteria');
            $pedido->monto_paqueteria = $costoPaqueteria;
            $pedido->save();

            SDK::setAccessToken($empresa->mp_access_token);
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
            $item->title = 'Pago por pedido Rinna Bruni N°: ' . $pedidoId;
            $item->quantity = 1;
            $getMonto = $pedido->getMonto();

            if ($pedido->getMonto() == 0) {
                $getMonto = 50;
            }
            $item->unit_price = $getMonto;
            $preference->items = array($item);

            // Configuración del pagador (payer)
            // $payer = new Payer();
            // $payer->email = $usuario->email;
            // $payer->identification = (object) array(
            //     "type" => "INE",
            //     "number" => "12345678"
            // );
            // $preference->payer = $payer;

            $preference->save();
            $preferenciaID = $preference->id;


            $mercadopago = Mercadopago::where('pedido_id', $pedidoId)->first();
            if ($mercadopago == null) {
                $mercadopago = new Mercadopago();
                $mercadopago->pedido_id = $pedidoId;
            }

            $mercadopago->referencia = $preferenciaID;
            $mercadopago->precio = $getMonto;
            $mercadopago->save();

            $pedido->mercadopago_id = $mercadopago->id;
            $pedido->referencia_mercadopago = $preferenciaID;
            $pedido->save();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $pedido
            ], 200);

        }catch(\Exception  $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al actualizar preference mercado pago' . $e->getMessage()
            ], 500);
        }
    }
}

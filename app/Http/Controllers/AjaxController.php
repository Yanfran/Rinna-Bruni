<?php

namespace App\Http\Controllers;

use App\Models\Cupons;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Localidads;
use App\Models\Pais;
use App\Models\Direcciones;
use App\Models\Tiendas;
use App\Models\Product;
use App\Models\ProductosNegados;
use App\Models\ProductosGestion;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewNotificationEvent;
use App\Models\Existencias;
use App\Models\ProductosPedido;
use App\Models\Pedidos;
use App\Models\Mercadopago;
use App\Models\Empresas;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Pusher\Pusher;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use Illuminate\Http\Response;
use DateTime;

use function PHPSTORM_META\map;

class AjaxController extends Controller
{
    public function totalExistencias(Request $request)
    {
        $productoId = $request->productoId;

        // Obtener las existencias del producto en todas las tiendas
        $existencias = Existencias::where('product_id', $productoId)->get();
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

    public function getUsers()
    {
        $users = User::whereIn('tipo', [2, 3])->get();
        return response()->json($users);
    }


    public function getEstadoData($id)
    {

        new Estado();

        $pais = Estado::withTrashed()->where("pais_id", $id)->where('estatus', 1)->pluck("nombre", "id");
        return json_encode($pais);
    }

    public function getMunicipiosData($id)
    {

        new Municipios();

        $municipio  = Municipios::withTrashed()->where("estado_id", $id)->where('estatus', 1)->pluck("nombre", "id");

        return json_encode($municipio);
    }


    public function getLocalidadData($id)
    {

        new Localidads();
        $localidad  = Localidads::withTrashed()->where("municipio_id", $id)->where('estatus', 1)->orderBy('nombre', 'ASC')->pluck("nombre", "id");
        return json_encode($localidad);
    }

    public function getLocalidadDataBusqueda($id)
    {

        new Localidads();
        $localidadBusqueda = Localidads::withTrashed()
            ->where("id", $id)
            ->where('estatus', 1)
            ->first(["ciudad", "cp", "id"]);

        return json_encode($localidadBusqueda);
    }


    public function validateEmail(Request $request)
    {

        if ($request->email) {
            $email = $request->email;
            $data  = User::where('email', $email)->count();
            if ($data > 0) {
                echo 'not_unique';
            } else {
                echo 'unique';
            }
        }
    }

    public function validateEmailRegistro($email = null)
    {

        if ($email) {
            $data = User::where('email', $email)->count();
            if ($data > 0) {
                echo 'not_unique';
            } else {
                echo 'unique';
            }
        }
    }


    public function getEmail(Request $request)
    {

        if ($request->email) {
            $email = $request->email;
            $data  = User::where('email', $email)->first();
            if (!empty($data)) {
                return json_encode($data);
            } else {
                return 'none';
            }
        }
    }

    public function getAliasDireccioneData($id)
    {
        new Direcciones();

        $alias  = Direcciones::withTrashed()->where("estatus", '!=', 0)->where("user_id", $id)->pluck("alias", "id");

        return json_encode($alias);
    }

    public function getDireccioneData($id)
    {

        new Direcciones();

        $direcciones  = Direcciones::withTrashed()->where("id", $id)->get();

        return json_encode($direcciones);
    }

    public function getEstadoDireccionesData($id)
    {
        $estado = Estados::withTrashed()->where("idEstado", $id)->first(["nombre", "id"]);;
        return json_encode($estado);
    }

    public function getMunicipioDireccionesData($id)
    {
        $municipio = Municipios::withTrashed()->where("id", $id)->first(["nombre", "id"]);;
        return json_encode($municipio);
    }

    public function getLocalidadDireccionesData($id)
    {
        $localidad = Localidads::withTrashed()->where("id", $id)->first(["nombre", "id"]);
        return json_encode($localidad);
    }

    public function getClientes($key)
    {

        $query = User::query();

        if (\Auth::user()->isDistribuidor()) {
            $idDistribuidor = \Auth::user()->id;
            $query->where(function ($query) use ($idDistribuidor) {
                $query->where('id', $idDistribuidor)
                     ->orWhere('distribuidor_id',$idDistribuidor);
            });
        }


        if (mb_strlen($key, 'utf-8') > 2) {
            $search = ' ' . $key;
            $clientes = $query->where(function ($query) use ($key) {
                $query->where('name', 'like', '%' . $key . '%')
                    ->orWhere('apellido_paterno', 'like','%' . $key . '%')
                    ->orWhere('apellido_materno', 'like','%' . $key . '%')
                    ->orWhere('numero_afiliacion', 'like','%' . $key . '%');
            })
                ->where('tipo', '!=', 1)
                ->take(10)
                ->get();

            return view('load.buscar_cliente', compact('clientes', 'key'));
        }
    }

    public function getProductos($key, $id_usuario)
    {
        if (mb_strlen($key, 'utf-8') > 2) {
            $search = ' ' . $key;
            $usuario = User::find($id_usuario);
            $tienda_id = $usuario->Tienda->id;

            $productos = Product::where(function ($query) use ($key) {
                $query->where('estilo', 'LIKE', "%$key%")
                    ->orWhere('nombre_corto', 'LIKE', "%$key%")
                    ->orWhere('codigo', 'LIKE', "%$key%");
            })->orWhereHas('marca', function ($query) use ($key) {
                $query->where('nombre', 'LIKE', "%$key%");
            })->orWhereHas('linea', function ($query) use ($key) {
                $query->where('nombre', 'LIKE', "%$key%");
            })
            ->take(20)
            ->with(['existencias' => function ($query) use ($tienda_id) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as total_cantidad'))
                    ->groupBy('product_id');
            }])
            ->with(['existencias_por_tienda' => function ($query) use ($tienda_id) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as cantidad_tienda'))
                    ->where('tienda_id', $tienda_id)
                    ->groupBy('product_id');
            }])
            ->with('marca')
            ->get();

            // Calcular cantidad total sumando las cantidades de todas las existencias de todas las tiendas
            $cantidadTotal = $productos->sum(function ($producto) {
                return $producto->existencias->sum('total_cantidad');
            });
            // Obtener cantidad por tienda relacionada al cliente
            $cantidadPorTienda = $productos->pluck('existencias_por_tienda.*.cantidad_tienda')->flatten()->sum();

            return view('load.buscar_producto', compact('productos', 'key', 'cantidadTotal', 'cantidadPorTienda'));
        }
    }

    public function getDireccionePOST(Request $request)
    {

        new Direcciones();

        $direcciones = Direcciones::where("user_id", $request->id)
                        ->where("estatus", 1)
                        ->get();
        //dd($direcciones);
        return json_encode($direcciones);
    }

    public function getDireccionTienda(Request $request) {
        $user = User::find($request->id);
        $tienda = Tiendas::where("id", $user->tienda_id)
                ->where("estatus", 1)
                ->get();
        return json_encode($tienda);
    }

    public function getDetallesDireccion(Request $request) {
        $localidad = Localidads::find($request->localidad_id);
        $municipio = Municipios::find($request->municipio_id);
        $estado = Estados::find($request->estado_id);

        $data = ["localidad" => $localidad,
        "municipio" => $municipio,
        "estado" => $estado];

        return json_encode($data);
    }

    public function getProductosUpdate(Request $request)
    {

        //primero actualizamos las existencias regresando la cantidad apartada
        $pedidoID = $request->pedido_id;
        $cliente = User::find($request->user_id);
        $search = ' ' . $request->producto_id;
        $tienda_id = $cliente->Tienda->id;

        $productoSolicitado = ProductosPedido::where('pedido_id', $pedidoID)
            ->where('product_id', $search)->where('user_id', $cliente->id)->first();

        $cantidadPrevia = $productoSolicitado->cantidad_solicitada;

        $productos = Product::where('id', $search)
            ->take(20)
            ->with(['existencias' => function ($query) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as total_cantidad'))
                    ->groupBy('product_id');
            }])
            ->with(['existencias_por_tienda' => function ($query) use ($tienda_id) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as cantidad_tienda'))
                    ->where('tienda_id', $tienda_id)
                    ->groupBy('product_id');
            }])
            ->get();

        // Calcular cantidad total sumando las cantidades de todas las existencias
        $cantidadTotal = $productos->sum(function ($producto) use ($cantidadPrevia) {
            return $producto->existencias->sum('total_cantidad') + $cantidadPrevia;
        });

        // Obtener cantidad por tienda
        $cantidadPorTienda = $productos->pluck('existencias_por_tienda.*.cantidad_tienda')->flatten()->sum() + $cantidadPrevia;
        //dd($cantidadPorTienda, $cantidadTotal);
        return response()->json([
            'cantidadPorTienda' => $cantidadPorTienda,
            'producto' => $productos,
            'cantidadTotal' => $cantidadTotal
        ]);
    }

    public function getProductosUpdateAjax(Request $request)
    {
        $pedidoID   = $request->pedido_id;
        $cliente    = User::find($request->user_id);
        $product_id = $request->product_id;
        $tienda_id  = $cliente->Tienda->id;
        //dd($request->all());

        $productoSolicitado = ProductosPedido::where('pedido_id', $pedidoID)
            ->where('product_id',  $product_id)
            ->where('user_id', $cliente->id)
            ->firstOrFail();

        $cantidadDisponible = $productoSolicitado->cantidad_solicitada + $request->cantidad_disponible;

        // calculo se la cantidad que se puede surtir
        if ($request->cantidad > $cantidadDisponible) {
            $cantidad_a_surtir =  $cantidadDisponible;
        } else {
            $cantidad_a_surtir = $request->cantidad;
        }

        $cantidad_negada = 0;
        // calculo de la cantidad negada que no existe del producto
        if ($request->cantidad_solicitada > $request->cantidad) {
            $cantidad_negada = $request->cantidad_solicitada - $request->cantidad;
        }



        // dd($cantidad_a_surtir, $cantidad_negada, $request->all());

        // calculos y actualizacion del producto del pedido
        $cantidadPrevia = $productoSolicitado->cantidad_solicitada;
        $neto =   $request->precio_final * $cantidad_a_surtir;
        $monto =  $request->precio_socio * $cantidad_a_surtir;
        $descuento = $request->descuento * $cantidad_a_surtir;

        $productoSolicitado->cantidad_solicitada = $cantidad_a_surtir;
        $productoSolicitado->cantidad_negada = $cantidad_negada;
        $productoSolicitado->monto = $monto;
        $productoSolicitado->neto = $neto;
        $productoSolicitado->descuento = $descuento;
        $productoSolicitado->cantidad_pendiente = $request->cantidad_pendiente;
        $productoSolicitado->save();

        $cantidadPendiente =  $productoSolicitado->cantidad_pendiente;

        // se actualizan las existencias del producto para devolver si es necesario
        $existencia = Existencias::where('product_id',  $product_id)
            ->where('tienda_id', $cliente->tienda_id)
            ->first();
        if ($existencia) {
            $existencia->cantidad = ($existencia->cantidad + $cantidadPrevia) - $cantidad_a_surtir;
            $existencia->save();
        }

        // se preparan los datos para calcular los montos del pedido
        $montoTotal = 0;
        $descuentoTotal = 0;
        $montoTotalNeto = 0;
        $pedido = Pedidos::find($pedidoID);
        $config = Empresas::find(1);
        $paqueteria = $config->costo_paqueteria * $request->total_cajas;

        // se suman los productos del pedido para actualizar el precio
        foreach ($pedido->productosPedidos as $productoPedido) {
            $montoTotal +=  $productoPedido->monto;
            $montoTotalNeto +=  $productoPedido->neto;
            $descuentoTotal +=  $productoPedido->descuento;
        }

        // se actualiza el pedido con los nuevos montos
        $pedido->monto_total =  $montoTotal;
        $pedido->monto_neto =  $montoTotalNeto;
        $pedido->monto_descuento_cliente =   $descuentoTotal;
        $pedido->save();
        // se regstran negados y gestionables si se da el caso
        // productos negados
        if ($cantidad_negada > 0) {
            $productoNegado = ProductosNegados::where('pedido_id', $pedidoID)
                ->where('product_id',  $product_id)
                ->where('user_id', $cliente->id)
                ->first();
            if ($productoNegado) {
                $productoNegado->cantidad = $cantidad_negada;
                $productoNegado->save();
            } else {
                $productoNegado = new ProductosNegados();
                $productoNegado->pedido_id = $pedidoID;
                $productoNegado->user_id = $cliente->id;
                $productoNegado->tienda_id = $cliente->tienda_id;
                $productoNegado->product_id =  $product_id;
                $productoNegado->cantidad = $cantidad_negada;
                $productoNegado->save();
            }
        } else {
            $productoNegado = ProductosNegados::where('pedido_id', $pedidoID)
                ->where('product_id',  $product_id)
                ->where('user_id', $cliente->id)
                ->first();
            if ($productoNegado) {
                $productoNegado->forceDelete();
            }
        }
        //aqui faltaria descontar de las tiendas en que exista queda pendiente por desarrollar
        if ($request->cantidad_pendiente > 0) {
            $productoNegado = ProductosGestion::where('pedido_id', $pedidoID)
                ->where('product_id',  $request->product_id)
                ->where('user_id', $cliente->id)
                ->first();
            if ($productoNegado) {
                $productoNegado->cantidad = $request->cantidad_pendiente;
                $productoNegado->save();
            } else {
                $productoNegado = new ProductosGestion();
                $productoNegado->pedido_id = $pedidoID;
                $productoNegado->user_id   = $cliente->id;
                $productoNegado->tienda_id = $cliente->tienda_id;
                $productoNegado->product_id = $request->product_id;
                $productoNegado->cantidad   = $request->cantidad_pendiente;
                $productoNegado->save();
            }
        } else {
            $productoNegado = ProductosGestion::where('pedido_id', $pedidoID)
                ->where('product_id',  $request->product_id)
                ->where('user_id', $cliente->id)
                ->first();
            if ($productoNegado) {
                $productoNegado->forceDelete();
            }
        }

        // se crea la referencia del api de mercado pago
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
            $getMonto = 1;
        }
        $item->unit_price =  $getMonto;
        $preference->items = array($item);

        // Configuración del pagador (payer) // falta la informacion real del pagador
        $payer = new Payer();
        $payer->email = $cliente->email;
        $payer->identification = (object) array(
            "type" => "INE",
            "number" => "12345678"
        );
        $preference->payer = $payer;
        $preference->save();
        $preferenciaID = $preference->id;

        // insercion de la referencia de mercadopago en DB
        $mercadopago = Mercadopago::where('pedido_id', $pedidoID)->first();
        if ($mercadopago == null) {
            $mercadopago = new Mercadopago();
            $mercadopago->pedido_id = $pedidoID;
        }

        // axtualizacion de montos actualizados y referencia de mercadopago en el pedido
        $mercadopago->referencia = $preferenciaID;
        $mercadopago->precio =  $getMonto; // funsion para acceder al monto del pedido
        $mercadopago->save();
        $pedido->mercadopago_id = $mercadopago->id;
        $pedido->referencia_mercadopago = $preferenciaID;
        $pedido->save();

        // se actualiza la existencias reales del producto para actualizar la tabla si es nesesario
        $productos = Product::where('id',  $product_id)
            ->take(20)
            ->with(['existencias' => function ($query) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as total_cantidad'))
                    ->groupBy('product_id');
            }])
            ->with(['existencias_por_tienda' => function ($query) use ($tienda_id) {
                $query->select('product_id', \DB::raw('SUM(cantidad) as cantidad_tienda'))
                    ->where('tienda_id', $tienda_id)
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
            'cantidad' => $cantidad_a_surtir,
            'pedido' => $pedido,
            'cantidadPendiente' =>  $cantidadPendiente,
            'key_mercadopago' => $config->mp_public_key, // se envia la llave publica de mercado pago para crear el boton en el front
        ]);
    }

    public function getDistribuidores($id)
    {
        $distribuidores = User::where('tipo', '3')->where("tienda_id", $id)->get(["name", "id"]);
        return response()->json($distribuidores);
    }

    public function getEstadosData()
    {

        $estados = Estados::where('estatus', 1)->pluck("nombre", "id");
        return json_encode($estados);
    }


    public function getTiendaDireccionesData($id)
    {
        $tienda = Tiendas::where('estatus', 1)->where("id", $id)->first(["nombre", "id", "estado_id", "municipio_id", "localidad_id", "calle_numero", "cp"]);
        return response()->json($tienda);
    }

    public function getPedidoAbierto(Request $request)
    {
        $cliente = User::find($request->clienteId);
        $tienda_id = $cliente->tienda_id;
        $config = Empresas::find(1);

        if ($cliente->tipo == 3) {
            $vendedor_id = null;
            $distribuidor_id = $cliente->id;
        } elseif ($cliente->tipo == 2) {
            $vendedor_id = $cliente->id;
            $distribuidor_id = $cliente->getDistribuidor()->id ?? NULL;
        }

        $pedido = Pedidos::where('distribuidor_id', $distribuidor_id)
            ->with('pedido_pagos')
            ->where('vendedor_id', $vendedor_id)
            ->where('estatus', 0)
            ->first();

        if (!empty($pedido)) {
            $productosPedido = $pedido->productosPedidos;
            $productos = Product::whereIn('id', $productosPedido->pluck('product_id'))
                ->take(20)
                ->with([
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
                    'linea' => $producto->linea->nombre,
                    'talla' => $producto->talla,
                    'marca' => $producto->marca->nombre,
                    'color' => $producto->color,
                    'composicion' => $producto->composicion,
                    'costo_bruto' => $producto->costo_bruto,
                    'precio' => $producto->precio,
                    'ancho' => $producto->ancho,
                    'concepto' => $producto->concepto,
                    'temporada' => $producto->temporada->nombre,
                    'descuento_1' => $producto->descuento_1,
                    'descuento_2' => $producto->descuento_2,
                ];
            }

            // dd($productosData->count());

            return [
                'estatus' => true,
                'pedido' => $pedido,
                'productos' => $productosData,
                'key_mercadopago' => $config->mp_public_key,
            ];
        }

        return ['estatus' => false];
    }

    public function cuponesVale(Request $request)
    {

        $cliente = User::find($request->clienteId);
        $tienda_id = $cliente->tienda_id;
        $pedidoID = $request->pedidoId;
        $codigoCupon = $request->cupon;
        $codigoVale = $request->vele;
        $tipo = null;
        $pedido = Pedidos::find($pedidoID);
        // Obtener el cupón o vale válido para el cliente y el pedido


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

            $fechaActual = now();

            if ($cupon) {
                $fechaInicio = new DateTime($cupon->fecha_inicio);
                $fechaFin = new DateTime($cupon->fecha_fin);

                // if ($fechaActual >= $fechaInicio && $fechaActual <= $fechaFin) {
                if ($fechaActual < $fechaInicio) {
                    $cuponAplicado = "cupon no diponible";
                    $cupon = false;
                }
                if ($fechaActual > $fechaFin) {
                    $cuponAplicado = "cupon vencido";
                    $cupon = false;
                }
            }

        } else {
            $cupon = null;
            $cuponAplicado = true;
        }

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

            $fechaActual = now();

            if ($vale) {
                $fechaInicio = new DateTime($vale->fecha_inicio);
                $fechaFin = new DateTime($vale->fecha_fin);

                // if ($fechaActual >= $fechaInicio && $fechaActual <= $fechaFin) {
                if ($fechaActual < $fechaInicio) {
                    $valeAplicado = "vale no diponible";
                    $vale = false;
                }
                if ($fechaActual > $fechaFin) {
                    $valeAplicado = "vale vencido";
                    $vale = false;
                }
            }
        } else {
            $vale = null;
            $valeAplicado = true;
        }


        if ($cupon) {
            $total_pedido = $pedido->monto_total;
            if ($cupon->tipo == 1) {
                $monto = $cupon->monto;
                $pedido->monto_cupon = $monto;
                $pedido->tipoCupon = 2;
                $pedido->montoCuponAplicado = $monto;
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
                $pedido->cupon =  $codigoCupon;
                $pedido->porcentjeCuponAplicado = $porcentaje;
                $pedido->monto_neto = $pedido->getMonto();
                $pedido->save();
                $cupon->cantidad_usos = $cupon->cantidad_usos - 1;
                $cupon->save();
                $tipo = 2;
            }
        }


        if ($vale) {
            $total_pedido = $pedido->monto_total;
            $monto = $vale->monto;
            $pedido->monto_vale = $monto;
            $pedido->vale =  $codigoVale;
            $pedido->save();
            $vale->cantidad_usos = $vale->cantidad_usos - 1;
            $vale->save();
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


        return [
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


        //dd($cuponAplicado);
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
    public function addNegados(Request $request)
    {

        $user = User::find($request->user_id);

        // Verificar cuántas veces el usuario ha agregado este producto con estatus 0 a la lista de negados
        $cantidadNegados = ProductosNegados::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->where('tienda_id', $user->tienda_id)
            ->where('estatus', 0)
            ->count();

        if ($cantidadNegados >= 3) {
            return response()->json(['success' => false, 'mensaje' => 'Ya has agregado este producto a la lista de negados más de tres veces.']);
        }

        $negado = new ProductosNegados();
        $negado->product_id = $request->product_id;
        $negado->user_id = $request->user_id;
        $negado->tienda_id = $user->tienda_id;
        $negado->cantidad = $request->cantidad;
        $negado->origen = 'Sin inventario';
        $negado->save();

        return response()->json(['success' => true, 'mensaje' => 'Producto agregado a la lista de negados']);
    }

    public function validarDistribuidorBloqueado(Request $request) {
        /* usuarios
            tipo 2 = vendedor
            tipo 3 = distribuidor
        */
        $usuario = User::find($request->usuario_id);
        $distruidorBloqueado = false;
        if($usuario->tipo == 3) {

            if($usuario->bloqueo_pedido != 1) {
                $distruidorBloqueado = true;
            }

        }elseif($usuario->tipo == 2) {
            if(isset($usuario->distribuidor_id)) {
                $distribuidor = User::find($usuario->distribuidor_id);
                if($distribuidor->bloqueo_pedido != 1) {
                    $distruidorBloqueado = true;
                }
            }
        }

        $data = ["distribuidorBloqueado" => $distruidorBloqueado];
        return json_encode($data);
    }

    public function obtenerEmpresa() {
        $empresa = Empresas::first();

        return response()->json($empresa);
    }
}

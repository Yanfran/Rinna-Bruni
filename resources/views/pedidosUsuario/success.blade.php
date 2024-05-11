@extends('layouts.app')

@section('css')
    <style>
        .success-message {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        .custom-alert {
            padding: 30px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }

        .payment-details {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
        }

        .order-details {
            padding: 20px;
        }
    </style>
@stop

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('pedidos.index') }}">Regresar</a>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="panel-body">
            <div class="text-center">
                <i class="fas fa-check-circle" style="font-size: 48px; color: green;"></i>
                <div class="success-message">
                    Pago exitoso
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Hubo algunos problemas con tus datos.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-success">
                <strong>Tu pedido ha sido pagado con éxito.</strong> Te estaremos notificando el cambio de estatus del
                mismo.
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="payment-details">
                                <h4 class="card-title">Detalles del pago</h4>
                                <ul>
                                    <li><strong>Collection ID:</strong> {{ request()->query('collection_id') }}</li>
                                    <li><strong>Collection Status:</strong> {{ request()->query('collection_status') }}</li>
                                    <li><strong>Payment ID:</strong> {{ request()->query('payment_id') }}</li>
                                    <li><strong>Status:</strong> {{ request()->query('status') }}</li>
                                    <li><strong>External Reference:</strong> {{ request()->query('external_reference') }}
                                    </li>
                                    <li><strong>Payment Type:</strong> {{ request()->query('payment_type') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-details">
                                <h4>Detalles del pedido</h4>
                                <ul>
                                    <li><strong>Total en artículos:</strong> @money($pedido->monto_total)</li>
                                    <li><strong>Cupón de descuento:</strong> @money($pedido->monto_cupon)</li>
                                    <li><strong>Vale a favor:</strong> @money($pedido->monto_vale)</li>
                                    <li><strong>Paquetería:</strong> @money($pedido->monto_paqueteria)</li>
                                    <li><strong>Descuento de cliente aplicado:</strong> @money($pedido->monto_descuento_cliente)</li>
                                    <li class="total_pagar"><strong>Total a pagar:</strong> @money($pedido->monto_neto)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="row">
                        <table id="empresas" class="table table-striped nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th class="text-center">Estilo</th>
                                    <th class="text-center">Marca</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Acabado</th>
                                    <th class="text-center">Talla</th>
                                    <th class="text-center">Precio socio</th>
                                    <th class="text-center">Descuento</th>
                                    <th class="text-center">Costo neto</th>
                                    <th class="text-center">Cantidad solicitada</th>
                                    <th class="text-center">Cantidad pendiente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedido->productosPedidos as $productoPedido)
                                <tr>
                                    <td>{{ $productoPedido->id }}</td>
                                    <td class="text-center">{{ $productoPedido->product->estilo }}</td>
                                    <td class="text-center">{{ $productoPedido->product->marca }}</td>
                                    <td class="text-center">{{ $productoPedido->product->color }}</td>
                                    <td class="text-center">{{ $productoPedido->product->acabado }}</td>
                                    <td class="text-center">{{ $productoPedido->product->talla_mayor }}</td>
                                    <td class="text-center">{{ $productoPedido->monto }}</td>
                                    <td class="text-center">{{ $productoPedido->descuento }}</td>
                                    <td class="text-center">{{ $productoPedido->neto }}</td>
                                    <td class="text-center">{{ $productoPedido->cantidad_solicitada }}</td>
                                    <td class="text-center">{{ $productoPedido->cantidad_pendiente }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Pedido No.{{$pedido->id}}
                {{-- @can('pedido-list') --}}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('pedidos.index') }}"> Regresar</a>
                </div>
                {{-- @endcan --}}
            </h3>
        </div>
        <div class="panel-body">
            <div class="row mt-3">
                <div class="col-xs-12 col-sm-12 col-md-10">
                    <div class="row">
                        <h4 class="sub-title">Datos del cliente</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">No de afiliación<br><span><b>{{ $cliente->numero_afiliacion }}</b></span> </h5>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">Teléfono fijo<br><span><b>{{ $cliente->telefono_fijo }}</b></span></h5>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">Nombre<br><span><b>{{ $cliente->name . ' ' . $cliente->apellido_paterno . ' ' . $cliente->apellido_materno}}</b></span></h5>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">Móvil<br><span><b>{{ $cliente->celular }}</b></span></h5>
                        </div>

                        <div class="col-md-6 col-md-offset-6">
                            <h5 class="ml-3">Correo<br><span><b>{{ $cliente->email }}</b></span></h5>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-2">
                    <div class="row">
                        <h4 class="sub-title">Estatus</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <span class="{{ $pedido->getCSS() }}">{{ $pedido->getEstatus() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <h4 class="sub-title">Desglose Pedido</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="table-responsive" style="height: 250px;">
                        <table id="empresas" class="table table-striped nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Estilo</th>
                                    <th>Marca</th>
                                    <th>Color</th>
                                    <th>Composición</th>
                                    <th>Talla</th>
                                    <th>Precio público</th>
                                    <th>Precio socio</th>
                                    <th>Total existencias</th>
                                    <th>Existencia tienda</th>
                                    <th>Cantidad solicitada</th>
                                    <th>Cantidad pendiente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detalles as $item)
                                <tr>
                                    <td>{{$item->producto->codigo}}</td>
                                    <td>{{$item->producto->estilo}}</td>
                                    <td>@if($item->producto->marca)  {{ $item->producto->marca-> nombre }} @endif</td>
                                    <td>{{$item->producto->color}}</td>
                                    <td>{{$item->producto->composicion}}</td>
                                    <td>{{$item->producto->talla}}</td>
                                    <td class="text-center">$ {{number_format($item->monto, 2)}}</td>
                                    <td class="text-center">$ {{number_format($item->neto, 2)}}</td>
                                    <td class="text-center"><a href="javascript:totalExistencias({{$item->producto->id}})" class="link-blue">{{$item->cantidad_solicitada}}</a></td>
                                    <td class="text-center">{{$item->cantidad_solicitada}}</td>
                                    <td class="text-center">{{$item->cantidad_solicitada}}</td>
                                    <td class="text-center">{{$item->cantidad_pendiente}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <h4 class="sub-title">Observaciones del pedido:</h4>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <textarea readonly name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="5">{{ $pedido->observacion }}</textarea>

                    </div>
                </div>
            </div>


            <div class="row mt-4">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    {{-- <div class="row">
                        <h4 class="sub-title">Vales y descuentos</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Cupón de descuento:</strong>
                                <input readonly type="number" name="descuento" value="" class="form-control"
                                    placeholder="Cupón de descuento" >
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Vale a fovor:</strong>
                                <input readonly type="text" name="vale" value="" class="form-control"
                                    placeholder="Vale a favor">
                            </div>
                        </div>

                    </div> --}}


                    <div class="row {{ ! \Auth::user()->isAdmin() ? 'd-none' : '' }}">
                        <h4 class="sub-title">Envío</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row">
                                <div class="col-xs-6"><strong>Recoger en tienda:</strong></div>
                                <div class="col-xs-6">{{ucwords($pedido->tipo_envio)}}</div>
                            </div>
                        </div>
                        @if($pedido->tipo_envio == 'domicilio')
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row">
                                <div class="col-xs-6"><strong>Dirección - Alias:</strong></div>
                                <div class="col-xs-6">{{ucwords($direccion->alias)}}</div>
                            </div>
                        </div>
                        @endif

                        <div class="col-xs-12 col-sm-12 col-md-8 mt-5">
                            <div class="form-group">
                                <h5>Total de cajas (Para el Admin)</h5>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 mt-5">
                            <div class="form-group">
                                <input readonly class="js-example-basic-single form-control text-right" type="number" value="{{$pedido->total_cajas}}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-5">
                            <div class="form-group">
                                <p>
                                    Leyenda sobre el manejo de la paqueteria, todo envio
                                    tiene por default; 1 costo de envio, depende del volumen de compra.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="row">
                        <h4 class="sub-title">Total del pedido</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped nowrap" style="width:100%">
                                    <tr>
                                        <th>Concepto:</th>
                                        <td>Costo</td>
                                    </tr>
                                    <tr>
                                        <th>Total en artículos:</th>
                                        <td>$ {{ number_format($pedido->monto_total, 2) }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th>Cupón de descuento:</th>
                                        <td>$ {{ number_format($pedido->monto_cupon, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Vale a favor:</th>
                                        <td>$ {{ number_format($pedido->monto_vale, 2) }}</td>
                                    </tr> --}}
                                    <tr>
                                        <th>Paquetería:</th>
                                        <td>$ {{ number_format($pedido->monto_paqueteria, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Descuento de cliente:</th>
                                        <td>$ {{ number_format($pedido->monto_descuento_cliente, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="total_pagar">Total a pagar:</th>
                                        <td class="total_pagar_span" id="total_a_pagar">$ {{ number_format($pedido->monto_neto, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <h4 class="sub-title mt-5">Método de pago</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row">
                                <div class="col-xs-6"><strong>Forma de pago:</strong></div>
                                <div class="col-xs-6">{{ucwords($pedido->metodo_pago)}}</div>
                            </div>

                            @if ($pedido->pedido_pagos)
                                <div class="row mt-3">
                                    <div class="col-xs-12"><strong>Imagen Voucher:</strong></div>
                                </div>
                                <div class="row">
                                    <div style="cursor: pointer" class="col-xs-12 mt-3">
                                            <img style="max-height: 500px"
                                                id="imgVoucher"
                                                class="img-thumbnail img-zoom"
                                                src="{{ route('storage', ['typeFile' => 'pedido_comprobantes', 'filename' => $pedido->pedido_pagos->img_comprobante]) }}"
                                            >
                                        </div>
                                    </div>

                                </div>
                            @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection


@include('pedidos.elementos.js')

@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Catálogo pedidos
                @can('pedidos-create')
                @if ($bloqueo_pedido)
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('pedidos.create') }}" title=""><i
                                class="fas fa-plus"></i>
                            {{ trans('general.btn_nuevo') }}</a></span>
                @endif
                @endcan
            </h3>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">
            <table id="empresas" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-center">Fecha de compra</th>
                        <th class="text-center">Cantidad de artículos</th>
                        <th class="text-center">Total de pedido</th>
                        <th class="text-center">Tipo de envio</th>
                        <th class="text-center">Estatus</th>
                        <th width="180px" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->getID() }}</td>
                            <td class="text-center">{{ $pedido->created_at->format('d-m-y') }}</td>
                            <td class="text-center">{{ $pedido->productosPedidos->count() }}</td>
                            <td class="text-center">$@money($pedido->getMonto() )</td>
                            <td class="text-center">{{ ucfirst($pedido->tipo_envio) }}</td>
                            <td class="text-center"><span class="{{ $pedido->getCSS() }}">{{ $pedido->getEstatus() }}</span></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">

                                            @can('pedidos-list')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('pedidos.show', $pedido->getID()) }}">Ver</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('pedidos-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('pedidos.edit', $pedido->getID()) }}">Editar</a>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            {!! $pedidos->links() !!}

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

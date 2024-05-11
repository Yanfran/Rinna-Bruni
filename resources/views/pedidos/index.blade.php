@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Catálogo pedidos

                @can('pedidos-create')
                    @if ( Auth::user()->tipo != 2)
                          <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('pedidos.create') }}" title=""><i
                                class="fas fa-plus"></i>
                                {{ trans('general.btn_nuevo') }}</a>
                          </span>
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
            <form class="contenedor-busqueda" method="GET" action="{{ route('pedidos.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="pedidoId">ID Pedido:</label>
                        <input type="text" class="form-control" id="pedidoId" name="pedidoId"
                            value="{{ $pedidoId }}" placeholder="Ingrese el id del pedido">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="fechaCompra">Fecha de compra</label>
                        <input type="date" class="form-control" id="fechaCompra" name="fechaCompra"
                            value="{{ $fechaCompra }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="estatus">Estatus:</label>
                        <select class="form-control" id="estatus" name="estatus">
                            <option value="" {{ $estatus == '' ? 'selected' : '' }}>Todos</option>
                            <option value="0" {{ $estatus == '0' ? 'selected' : '' }}>Abierto</option>
                            <option value="1" {{ $estatus === '1' ? 'selected' : '' }}>En revisión</option>
                            <option value="3" {{ $estatus === '3' ? 'selected' : '' }}>Pendiente de pago</option>
                            <option value="4" {{ $estatus === '4' ? 'selected' : '' }}>Pagado</option>
                            <option value="5" {{ $estatus === '5' ? 'selected' : '' }}>Enviado</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="sortOrder">Orden:</label>
                        <select class="form-control" id="sortOrder" name="sortOrder">
                            <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Descendente</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="perPage">Mostrar:</label>
                        <select name="perPage" id="perPage" class="form-control">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 pull-right text-right botones-buscar">
                        <button type="submit" class="btn btn-primary buscar-filtro">Buscar</button>
                        <a href="{{ route('pedidos.index') }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
                    </div>
                </div>
            </form>

            <hr>

            <table id="empresas" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Fecha de compra</th>
                        <th class="text-center">Artículos solicitados</th>
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
                            <td>{!! $pedido->getPropietario() !!}</td>
                            <td class="text-center">{{ $pedido->created_at->format('d-m-y') }}</td>
                            {{-- <td class="text-center">{{ $pedido->productosPedidos->count() }}</td> --}}
                            <td class="text-center">{{ $pedido->getArticulosSolicitados() }}</td>
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
                                            @if($pedido->estatus ==  '0' || $pedido->estatus == '1')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('pedidos.edit', $pedido->getID()) }}">Editar</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endif
                                            @endcan

                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" target="_blank" href="{{ route('reporte', $pedido->getID()) }}">Reporte</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            {!! $pedidos->links() !!}

            <div class="row">
                <div class="col-sm-6">
                    <p>Mostrando {{ $pedidos->firstItem() }}-{{ $pedidos->lastItem() }} de
                        {{ $pedidos->total() }} resultados</p>
                </div>
            </div>

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo vendedores - Distribuidor: {{ $user->id }}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('distribuidores.index') }}"> Regresar a distribuidores</a>
                </div>
                    {{-- @can('localidad-create') --}}                {{-- @endcan  --}}
                </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="panel-body">

            @if($limite_cuentas_creadas !== 0 && $estatusTienda !== 0)
                <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('vendedoresAsociados.create', $user->id) }}" title="">
                    <i class="fas fa-plus"></i>
                    Crear vendedor</a>
                </span>
            @endif

            <form class="contenedor-busqueda" method="GET" action="{{ route('vendedoresAsociados.index', $user->id) }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="vendedorNombre">Nombre Vendedor:</label>
                        <input type="text" class="form-control" id="vendedorNombre" name="vendedorNombre"
                            value="{{ $vendedorNombre }}" placeholder="Ingrese el nombre de un vendedor">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="estatus">Estatus:</label>
                        <select class="form-control" id="estatus" name="estatus">
                            <option value="" {{ $estatus == '' ? 'selected' : '' }}>Todos</option>
                            <option value="1" {{ $estatus == '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ $estatus === '0' ? 'selected' : '' }}>Inactivos</option>
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
                        <a href="{{ route('vendedoresAsociados.index', $user->id) }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
                    </div>
                </div>
            </form>

            <hr>

            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Correo</th>
                    <th class="text-center">Distribuidor asociado</th>
                    <th class="text-center">Sucursal</th>
                    <th class="text-center">Acción</th>
                </tr>
                @foreach ($vendedores as $key => $vendedor)
                {{-- @foreach ($data as $key => $vendedor) --}}
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td class="text-center">{{ $vendedor->name }}</td>
                        <td class="text-center">{{ $vendedor->email }}</td>
                        <td class="text-center">{{ $vendedor->getDistribuidor()->name }} {{ $vendedor->getDistribuidor()->apellido_paterno }}</td>
                        <th class="text-center">

                            @if($vendedor->direccionesSucursale->count() > 0)
                                @foreach($vendedor->direccionesSucursale as $direccion)
                                    <label class="badge badge-primary">{{ $direccion->alias }}</label>
                                @endforeach
                            @else
                                <label class="badge badge-danger">Sin direcciones asociadas</label>
                            @endif

                        </th>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Acción <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu drop-custome dropdown-menu-right">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('vendedoresAsociados.show', $vendedor->id) }}">Ver</a>
                                        </div>
                                        <div class="linea"></div>
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('vendedoresAsociados.edit', $vendedor->id) }}">Editar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
            <div class="botones-resultados">
                {!! $vendedores->appends([
                        'perPage' => $perPage,
                        'sortBy' => $sortBy,
                        'sortOrder' => $sortOrder,
                        'vendedorNombre' => $vendedorNombre,
                    ])->links() !!}

                <div class="row">
                    <div class="col-sm-6">
                        <p>Mostrando {{ $vendedores->firstItem() }}-{{ $vendedores->lastItem() }} de
                            {{ $vendedores->total() }} resultados</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

{{-- {!! $data->render() !!} --}}

<p class="text-center text-primary"><small>-</small></p>
@endsection

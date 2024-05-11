@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo vendedores independientes
                @can('vendedores-create')
                <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('vendedores.create') }}"
                        title=""><i class="fas fa-plus"></i>
                        Crear nuevo vendedor</a></span>
                @endcan
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

            <form class="contenedor-busqueda" method="GET" action="{{ route('vendedores.index') }}">
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
                        <a href="{{ route('vendedores.index') }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
                    </div>
                </div>
            </form>

            <hr>

            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Correo</th>
                    <th class="text-center">Tienda</th>
                    {{-- <th class="text-center">Sucursal</th> --}}
                    <th width="180px" class="text-center">Acción</th>
                </tr>
                @foreach ($vendedores as $key => $vendedor)

                <tr>
                    <td class="text-center">{{ $vendedor->id }}</td>
                    <td class="text-center">{{ $vendedor->name }}</td>
                    <td class="ellipsis text-center">{{ $vendedor->email }}</td>
                    <td class="text-center">
                        {{-- @if ($vendedor->getDistribuidor())
                            {{ $vendedor->getDistribuidor()->name }} {{ $vendedor->getDistribuidor()->apellido_paterno }}
                        @else
                            <p style="color: black;">Sin distribuidor asociado</p>
                        @endif --}}
                        @if ($vendedor->Tienda)
                             {{ $vendedor->Tienda->nombre }}
                        @endif

                    </td>
                    {{-- <th class="text-center">

                        @if($vendedor->direccionesSucursale->count() > 0)
                            @foreach($vendedor->direccionesSucursale as $direccion)
                                <label class="badge badge-primary">{{ $direccion->alias }}</label>
                            @endforeach
                        @else
                            <label class="badge badge-danger">Sin direcciones asociadas</label>
                        @endif

                    </th> --}}
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acción <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu drop-custome dropdown-menu-right">
                                <div class="row">

                                    @can('vendedores-list')
                                    <div class="col-md-12">
                                        <a class="btn btn-info-custome btn-block" href="{{ route('vendedores.show', $vendedor->id) }}">Ver</a>
                                    </div>
                                    <div class="linea"></div>
                                    @endcan

                                    @can('vendedores-edit')
                                    <div class="col-md-12">
                                        <a class="btn btn-info-custome btn-block" href="{{ route('vendedores.edit', $vendedor->id) }}">Editar</a>
                                    </div>
                                    @endcan

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
                        'estatus' => $estatus,
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


<p class="text-center text-primary"><small>-</small></p>
@endsection

@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo distribuidores
                @can('distribuidores-create')
                    @if ( Auth::user()->isAdmin() )
                        <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('distribuidores.create') }}"
                                title=""><i class="fas fa-plus"></i>
                                Crear nuevo distribuidor</a>
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
        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="panel-body">

            <form class="contenedor-busqueda" method="GET" action="{{ route('distribuidores.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="distribuidorNombre">Nombre de distribuidor:</label>
                        <input type="text" class="form-control" id="distribuidorNombre" name="distribuidorNombre"
                            value="{{ $distribuidorNombre }}" placeholder="Ingrese el nombre de distribuidor">
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
                        <a href="{{ route('distribuidores.index') }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
                    </div>
                </div>
            </form>

            <hr>

            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th width="15%">Nombre y apellido</th>
                    <th>Correo</th>
                    {{-- <th>Descuento</th> --}}
                    {{-- <th>Crédito</th> --}}
                    <th>Afiliaciones creadas</th>
                    <th>Afiliaciones disponibles</th>
                    <th>Vendedores asociados</th>
                    <th>Sucursales</th>
                    <th class="text-center">Acción</th>

                </tr>
                @foreach ($distribuidores as $key => $distribuidor)
                    <tr>
                        <td>{{ $distribuidor->id }}</td>
                        <td>{{ $distribuidor->name }} {{ $distribuidor->apellido_paterno }}</td>
                        <td class="ellipsis">{{ $distribuidor->email }}</td>
                        {{-- <td class="text-center">{{ $distribuidor->descuento }}%</td> --}}
                        {{-- <td class="text-center miles">@money($distribuidor->credito)</td> --}}
                        <td class="text-center">{{ $distribuidor->cuentas_creadas }}</td>
                        <td class="text-center">{{ $distribuidor->cuentas_restantes }}</td>

                        <td class="text-center">
                            @if ($distribuidor->estatus != '0')
                                <a href="{{ route('vendedoresAsociados.index', $distribuidor->id) }}"><i
                                        class="fa fa-users"></i></a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($distribuidor->estatus != '0')
                                <a href="{{ route('sucursales.index', $distribuidor->id) }}"> <i
                                        class="fa fa-home"></i></a>
                            @endif
                        </td>

                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    Acción <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu drop-custome dropdown-menu-right">
                                    <div class="row">

                                        @can('distribuidores-list')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block"
                                                    href="{{ route('distribuidores.show', $distribuidor->id) }}">Ver</a>
                                            </div>
                                            <div class="linea"></div>
                                        @endcan

                                        @can('distribuidores-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block"
                                                    href="{{ route('distribuidores.edit', $distribuidor->id) }}">Editar</a>
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
                {!! $distribuidores->appends([
                        'perPage' => $perPage,
                        'sortBy' => $sortBy,
                        'sortOrder' => $sortOrder,
                        'distribuidorNombre' => $distribuidorNombre,
                        'estatus' => $estatus,
                    ])->links() !!}

                <div class="row">
                    <div class="col-sm-6">
                        <p>Mostrando {{ $distribuidores->firstItem() }}-{{ $distribuidores->lastItem() }} de
                            {{ $distribuidores->total() }} resultados</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- {!! $data->render() !!} --}}

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var td = $('.miles');
            td.each(function() {
                var value = $(this).text();
                var cleanedValue = value.replace(/,/g, '');
                var number = parseFloat(cleanedValue);
                if (!isNaN(number)) {
                    var formattedNumber = formatNumber(number);
                    $(this).text(formattedNumber);
                } else {
                    $(this).text('');
                }
            });
            function formatNumber(number) {
                var parts = number.toFixed(0).split('.');
                var integerPart = parts[0];
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return integerPart;
            }


        });
    </script>
@stop

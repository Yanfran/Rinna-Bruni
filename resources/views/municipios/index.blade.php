@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo municipios
                @can('municipios-create')
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('municipios.create') }}"
                            title=""><i class="fas fa-plus"></i>
                            Nuevo municipio</a></span>
                @endcan
            </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">
            <form method="GET" action="{{ route('municipios.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="municipioNombre">Nombre Municipio:</label>
                        <input type="text" class="form-control" id="municipioNombre" name="municipioNombre"
                            value="{{ $municipioNombre }}" placeholder="Ingrese el nombre del municipio">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="estadoId">Estado:</label>
                        <select class="form-control" id="estadoId" name="estadoId">
                            <option value="">Seleccione un estado</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}" {{ $estado->id == $estadoId ? 'selected' : '' }}>
                                    {{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="sortBy">Ordenar por:</label>
                        <select class="form-control" id="sortBy" name="sortBy">
                            <option value="nombre" {{ $sortBy == 'nombre' ? 'selected' : '' }}>Municipio</option>
                            <option value="estado_id" {{ $sortBy == 'estado_id' ? 'selected' : '' }}>Estado</option>
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
                </div>
                <div class="form-group botones-busqueda text-right">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="{{ route('municipios.index') }}" class="btn btn-default">Limpiar</a>
                </div>
            </form>

            <hr>

            <table id="empresas" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-center">Municipio</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">País</th>
                        <th class="text-center">Estatus</th>
                        <th width="180px" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($municipios as $data)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td class="text-center">{{ $data->nombre }}</td>
                            <td class="text-center">{{ $data->Estado->nombre }}</td>
                            <td class="text-center">{{ $data->Pais->nombre }}</td>

                            <td class="text-center"><span class="{{ $data->getCSS() }}">{{ $data->getEstatus() }}</span></td>
                            <td class="text-center">

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">

                                            @can('municipios-list')
                                            <div class="col-md-12">  
                                                <a class="btn btn-info-custome btn-block" href="{{ route('municipios.show', $data->id) }}">Ver</a>                                                
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('municipios-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('municipios.edit', $data->id) }}">Editar</a>                                                
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('municipios-delete')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('municipios.destroy', $data->id) }}">Eliminar</a>                                                                                                
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
            <div class="botones-resultados">
                {!! $municipios->appends([
                        'perPage' => $perPage,
                        'sortBy' => $sortBy,
                        'sortOrder' => $sortOrder,
                        'municipioNombre' => $municipioNombre,
                        'estadoId' => $estadoId,
                    ])->links() !!}

                <div class="row">
                    <div class="col-sm-6">
                        <p>Mostrando {{ $municipios->firstItem() }}-{{ $municipios->lastItem() }} de
                            {{ $municipios->total() }} resultados</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

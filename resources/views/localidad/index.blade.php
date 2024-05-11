@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo colonia
                @can('localidad-create')
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('localidad.create') }}"
                            title=""><i class="fas fa-plus"></i>
                            Nueva colonia</a></span>
                @endcan
            </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">
            <form method="GET" action="{{ route('localidad.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="localidadNombre">Codígo postal</label>
                        <input type="text" class="form-control" id="cp" name="cp" value="{{ $cp }}"
                            placeholder="Ingrese cp">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="localidadNombre">Nombre Colonia:</label>
                        <input type="text" class="form-control" id="localidadNombre" name="localidadNombre"
                            value="{{ $localidadNombre }}" placeholder="Ingrese el nombre de la colonia">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="localidadNombre">Nombre Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{ $ciudad }}"
                            placeholder="Ingrese ciudad">
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
                    <div class="form-group col-md-3">
                        <label for="sortBy">Ordenar por:</label>
                        <select class="form-control" id="sortBy" name="sortBy">
                            <option value="cp" {{ $sortBy == 'nombre' ? 'selected' : '' }}>Codígo postal</option>
                            <option value="ciudad" {{ $sortBy == 'nombre' ? 'selected' : '' }}>Ciudad</option>
                            <option value="nombre" {{ $sortBy == 'nombre' ? 'selected' : '' }}>Colonia</option>
                            <option value="estado_id" {{ $sortBy == 'estado_id' ? 'selected' : '' }}>Estado</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="sortOrder">Orden:</label>
                        <select class="form-control" id="sortOrder" name="sortOrder">
                            <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Descendente</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="perPage">Mostrar:</label>
                        <select name="perPage" id="perPage" class="form-control">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="form-localidadform-group botones-busqueda text-right">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <a href="{{ route('localidad.index') }}" class="btn btn-default">Limpiar</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separador"><hr></div>
            <table id="empresas" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th width="120px" class="text-center">Cod. postal</th>
                        <th width="120px" class="text-center">Colonia</th>
                        <th width="140px" class="text-center">Ciudad</th>
                        <th width="140px" class="text-center">Municipio</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Pais</th>
                        <th class="text-center">Estatus</th>
                        <th width="180px" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($localidades as $data)
                        <tr>
                            <td class="text-center">{{ ++$i }}</td>
                            <td class="text-center">{{ $data->cp }}</td>
                            <td class="text-center">{{ $data->nombre }}</td>
                            <td class="text-center">{{ $data->ciudad }}</td>
                            <td class="text-center">{{ $data->Municipio->nombre }}</td>
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

                                            @can('localidad-list')
                                            <div class="col-md-12">  
                                                <a class="btn btn-info-custome btn-block" href="{{ route('localidad.show', $data->id) }}">Ver</a>                                                
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('localidad-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('localidad.edit', $data->id) }}">Editar</a>                                                
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('localidad-delete')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('localidad.destroy', $data->id) }}">Eliminar</a>                                                                                                
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>

                                {{-- <form action="{{ route('localidad.destroy', $data->id) }}" method="POST">
                                    <a class="btn btn-secondary" href="{{ route('localidad.show', $data->id) }}"><i
                                            class="fas fa-eye"></i></a>
                                    @can('localidad-edit')
                                        <a class="btn btn-secondary" href="{{ route('localidad.edit', $data->id) }}"><i
                                                class="far fa-edit"></i></a>
                                    @endcan

                                    @csrf
                                    @method('DELETE')
                                    @can('localidad-delete')
                                        <button type="submit" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                    @endcan
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="botones-resultados">
                {!! $localidades->appends([
                        'perPage' => $perPage,
                        'sortBy' => $sortBy,
                        'sortOrder' => $sortOrder,
                        'localidadNombre' => $localidadNombre,

                        'estadoId' => $estadoId,
                    ])->links() !!}

                <div class="row">
                    <div class="col-sm-6">
                        <p>Mostrando {{ $localidades->firstItem() }}-{{ $localidades->lastItem() }} de
                            {{ $localidades->total() }} resultados</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

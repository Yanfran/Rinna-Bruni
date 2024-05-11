@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo estados
                @can('estados-create')
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('estados.create') }}" title=""><i
                                class="fas fa-plus"></i>
                            {{ trans('general.btn_nuevo') }}</a></span>
                @endcan
            </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">

            <form class="contenedor-busqueda" method="GET" action="{{ route('estados.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="estadoNombre">Nombre Estado:</label>
                        <input type="text" class="form-control" id="estadoNombre" name="estadoNombre"
                            value="{{ $estadoNombre }}" placeholder="Ingrese el nombre del estado">
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
                        <a href="{{ route('estados.index') }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
                    </div>
                </div>
            </form>

            <hr>

            <table id="empresas" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th width="30%" class="text-center">Nombre</th>
                        <th width="30%" class="text-center">Pais</th>
                        <th width="30%" class="text-center">Estatus</th>
                        <th width="10%" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estados as $key => $estado)
                        <tr>
                            <td class="text-center">{{ $estado->id }}</td>
                            <td class="text-center">{{ $estado->nombre }}</td>
                            <td class="text-center">{{ $estado->Pais->nombre }}</td>

                            <td class="text-center"><span class="{{ $estado->getCSS() }}">{{ $estado->getEstatus() }}</span></td>


                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">

                                            @can('estados-list')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('estados.show', $estado->id) }}">Ver</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('estados-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('estados.edit', $estado->id) }}">Editar</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('estados-delete')
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-info-custome btn-block">Eliminar</button>                                                
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
                {!! $estados->appends([
                        'perPage' => $perPage,
                        'sortBy' => $sortBy,
                        'sortOrder' => $sortOrder,
                        'estadoNombre' => $estadoNombre,
                        'estatus' => $estatus,
                    ])->links() !!}

                <div class="row">
                    <div class="col-sm-6">
                        <p>Mostrando {{ $estados->firstItem() }}-{{ $estados->lastItem() }} de
                            {{ $estados->total() }} resultados</p>
                    </div>
                </div>
            </div>

            {{-- {!! $estados->links() !!} --}}

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@section('js')
    <script>            
        $(document).ready(function() {
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Confirmar eliminación',
                    text: '¿Estás seguro de que deseas eliminar este elemento?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop
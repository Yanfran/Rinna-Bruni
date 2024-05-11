@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo usuarios
                @can('user-create')
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('users.create') }}"
                            title=""><i class="fas fa-plus"></i>
                            Crear nuevo usuario</a></span>

                @endcan
            </h3>

        </div>


        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="panel-body">

            <form class="contenedor-busqueda" method="GET" action="{{ route('users.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="usuarioNombre">Nombre Usuario:</label>
                        <input type="text" class="form-control" id="usuarioNombre" name="usuarioNombre"
                            value="{{ $usuarioNombre }}" placeholder="Ingrese el nombre de usuario">
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
                        <a href="{{ route('users.index') }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
                    </div>
                </div>
            </form>

            <hr>
            
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th class="text-center">Correo</th>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Tienda</th>
                    <th>Estatus</th>
                    <th class="text-center" width="180px">Acción</th>
                </tr>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->usuario }}</td>
                        <td>{{ $user->name }}</td>
                        <td class="ellipsis text-center">{{ $user->email }}</td>
                        <td class="text-center">
                            @foreach ($roles as $value => $role)
                                @if ($user->hasRole($value))
                                    <label class="badge badge-success">{{ $role }}</label>
                                @endif
                            @endforeach
                        </td>

                        <td class="text-center">
                            @if($user->Tienda)
                            {{ $user->Tienda->nombre }}
                            @endif


                        </td>
                        <td>
                            @if($user->estatus == 1)
                                <label class="badge badge-primary">Activo</label>
                            @else
                                <label class="badge badge-danger">Inactivo</label>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acción <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu drop-custome dropdown-menu-right">
                                    <div class="row">

                                        @can('user-list')
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('users.show', $user->id) }}">Ver</a>
                                        </div>
                                        <div class="linea"></div>
                                        @endcan

                                        @can('user-edit')
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('users.edit', $user->id) }}">Editar</a>
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
                {!! $users->appends([
                        'perPage' => $perPage,
                        'sortBy' => $sortBy,
                        'sortOrder' => $sortOrder,
                        'usuarioNombre' => $usuarioNombre,
                        'estatus' => $estatus,
                    ])->links() !!}

                <div class="row">
                    <div class="col-sm-6">
                        <p>Mostrando {{ $users->firstItem() }}-{{ $users->lastItem() }} de
                            {{ $users->total() }} resultados</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- {!! $data->render() !!} --}}

    <p class="text-center text-primary"><small>-</small></p>
@endsection

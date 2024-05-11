@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo roles
                @can('role-create')
                <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('roles.create') }}"
                        title=""><i class="fas fa-plus"></i>
                        Crear nuevo rol</a></span>
                @endcan 
            </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('warning'))
        <div class="alert alert-warning">
            <p>{{ $message }}</p>
        </div>
    @endif

        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th class="text-center">Nombre</th>
                    <th width="280px" class="text-center">Actión</th>
                </tr>

                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td class="text-center">{{ $role->name }}</td>
                        <td class="text-center">


                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Acción <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu drop-custome dropdown-menu-right">
                                    <div class="row">
                                        @can('role-list')
                                        <div class="col-md-12">                                              
                                            <a class="btn btn-info-custome btn-block" href="{{ route('roles.show', $role->id) }}">Ver</a>                                                
                                        </div>
                                        <div class="linea"></div>
                                        @endcan 

                                        @can('role-edit')
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('roles.edit', $role->id) }}">Editar</a>                                                
                                        </div>
                                        <div class="linea"></div>
                                        @endcan 

                                        {{-- @can('role-delete')
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('roles.destroy', $role->id) }}">Eliminar</a>                                                                                                
                                        </div>
                                        @endcan  --}}
                                    </div>
                                </div>
                            </div>                           
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>


    {!! $roles->render() !!}


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

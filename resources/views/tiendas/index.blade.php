@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo tiendas
                @can('tiendas-create')
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('tiendas.create') }}" title=""><i
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
            <table id="empresas" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Estatus</th>
                        <th width="180px" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tiendas as $data)
                        <tr>
                            <td class="text-center">{{ $data->id }}</td>
                            <td class="text-center">{{ $data->nombre }}</td>

                            <td class="text-center"><span class="{{ $data->getCSS() }}">{{ $data->getEstatus() }}</span></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">

                                            @can('tiendas-list')
                                            <div class="col-md-12">  
                                                <a class="btn btn-info-custome btn-block" href="{{ route('tiendas.show', $data->id) }}">Ver</a>                                                
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('tiendas-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('tiendas.edit', $data->id) }}">Editar</a>                                                
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            {{-- @can('tiendas-delete')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="#">Eliminar</a>                                                                                                
                                            </div>
                                            @endcan --}}
                                        </div>
                                    </div>
                                </div>                               
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            {!! $tiendas->links() !!}

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


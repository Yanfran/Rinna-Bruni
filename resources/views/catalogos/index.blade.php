@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">

@include('componentes.catalogos.encabezado_listados', [
            'ruta'  => 'catalogos',
            'message' => Session::get('success')
        ])

        <div class="panel-body">

            @include('componentes.catalogos.formulario_busqueda', [
                'ruta' => 'catalogos',
                'placeholder' => 'Ingrese el nombre del catalogo',
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'nombre' => $nombre,
                'estatus' => $estatus,
            ])

            <hr>

            <table id="registros" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th class="text-center">Estatus</th>
                        <th class="text-center">PDF</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($catalogos as $key => $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td class="text-center"><span class="{{ $item->getCSS() }}">{{ $item->getEstatus() }}</span></td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      PDF <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome">
                                        <div class="row">
                                            @can('catalogos-list')
                                            <div class="col-md-12">
                                                <a target="_blank" class="btn btn-info-custome btn-block" href="{{ route('catalogos.pdf', $item->id) }}">Ver PDF</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('catalogos-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('catalogos.download', $item->id) }}">Descargar PDF</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                        </div>
                                    </div>
                                </div>

                            </td>


                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">

                                            @can('catalogos-list')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('catalogos.show', $item->id) }}">Ver</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('catalogos-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('catalogos.edit', $item->id) }}">Editar</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('catalogos-delete')
                                            <div class="col-md-12">
                                                <form action="{{route('catalogos.destroy',$item->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-info-custome btn-block delete-btn">Eliminar</button>
                                                </form>

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


            @include('componentes.catalogos.botones_resultados', [
                'tabla' => $catalogos,
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'nombre' => $nombre,
                'estatus' => $estatus,
            ])

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@section('js')
    <script src="{{ asset('js/commons.js') }}"></script>
@stop

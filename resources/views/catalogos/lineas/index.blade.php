@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">

        @include('componentes.catalogos.encabezado_listados', [
            'ruta'  => 'lineas',
            'message' => Session::get('success')
        ])


        <div class="panel-body">

            @include('componentes.catalogos.formulario_busqueda', [
                'ruta' => 'lineas',
                'placeholder' => 'Ingrese el nombre de la linea',
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'nombre' => $nombre,
                'estatus' => $estatus,
            ])

            <hr>

            @include('componentes.catalogos.tabla_registros', [
                'tabla' => $lineas,
                'ruta'  => 'lineas'
            ])


            @include('componentes.catalogos.botones_resultados', [
                'tabla' => $lineas,
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

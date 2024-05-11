@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">

        @include('componentes.catalogos.encabezado_listados', [
            'ruta'  => 'marcas',
            'message' => Session::get('success')
        ])


        <div class="panel-body">

            @include('componentes.catalogos.formulario_busqueda', [
                'ruta' => 'marcas',
                'placeholder' => 'Ingrese el nombre de la marca',
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'nombre' => $nombre,
                'estatus' => $estatus,
            ])

            <hr>

            @include('componentes.catalogos.tabla_registros', [
                'tabla' => $marcas,
                'ruta'  => 'marcas'
            ])


            @include('componentes.catalogos.botones_resultados', [
                'tabla' => $marcas,
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

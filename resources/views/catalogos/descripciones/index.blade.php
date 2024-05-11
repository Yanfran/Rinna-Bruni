@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">

        @include('componentes.catalogos.encabezado_listados', [
            'ruta'  => 'descripciones',
            'message' => Session::get('success')
        ])


        <div class="panel-body">

            @include('componentes.catalogos.formulario_busqueda', [
                'ruta' => 'descripciones',
                'placeholder' => 'Ingrese el nombre de la descripción',
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'nombre' => $nombre,
                'estatus' => $estatus,
            ])

            <hr>

            @include('componentes.catalogos.tabla_registros', [
                'tabla' => $descripciones,
                'ruta'  => 'descripciones'
            ])


            @include('componentes.catalogos.botones_resultados', [
                'tabla' => $descripciones,
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

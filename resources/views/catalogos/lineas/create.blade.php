@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.crear_registro', [
        'title'  => 'Agregar Nueva Linea',
        'ruta'   => 'lineas',
        'errors' => $errors
    ])

    <p class="text-center text-primary"><small>-</small></p>
@endsection

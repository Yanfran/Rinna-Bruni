@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.crear_registro', [
        'title'  => 'Agregar Nueva Temporada',
        'ruta'   => 'temporadas',
        'errors' => $errors
    ])

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.crear_registro', [
        'title'  => 'Agregar Nueva Marca',
        'ruta'   => 'marcas',
        'errors' => $errors
    ])

    <p class="text-center text-primary"><small>-</small></p>
@endsection

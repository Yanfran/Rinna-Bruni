@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.editar_registro', [
        'title'  => 'Editar Linea',
        'ruta'   => 'lineas',
        'model'   => $linea,
        'errors' => $errors
    ])


    <p class="text-center text-primary"><small>-</small></p>
@endsection

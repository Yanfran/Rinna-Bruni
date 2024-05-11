@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.editar_registro', [
        'title'  => 'Editar Marca',
        'ruta'   => 'marcas',
        'model'   => $marca,
        'errors' => $errors
    ])


    <p class="text-center text-primary"><small>-</small></p>
@endsection

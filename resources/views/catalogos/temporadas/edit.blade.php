@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.editar_registro', [
        'title'  => 'Editar Temporada',
        'ruta'   => 'temporadas',
        'model'   => $temporada,
        'errors' => $errors
    ])


    <p class="text-center text-primary"><small>-</small></p>
@endsection

@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.editar_registro', [
        'title'  => 'Editar Descripción',
        'ruta'   => 'descripciones',
        'model'   => $descripcione,
        'errors' => $errors
    ])


    <p class="text-center text-primary"><small>-</small></p>
@endsection

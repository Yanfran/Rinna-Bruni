@extends('layouts.app')

@section('contenido')

    @include('componentes.catalogos.ver_registro', [
        'model' => $temporada,
        'ruta'  => 'temporadas',
        'message' => Session::get('success')
    ])

    <p class="text-center text-primary"><small>-</small></p>
@endsection



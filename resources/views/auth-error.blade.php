<!-- resources/views/errors/403.blade.php -->

@extends('layouts.app')  <!-- Asegúrate de ajustar el nombre del layout a tu estructura de vistas -->

@section('content')
    <div class="container">
        <div class="alert alert-danger">
            <h1>Acceso no autorizado</h1>
            <p>Tu sesión ha expirado o no tienes permisos para acceder a esta página.</p>
        </div>
    </div>
@endsection

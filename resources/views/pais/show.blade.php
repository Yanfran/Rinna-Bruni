@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver pais
                @can('pais-list')
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('pais.index') }}"> Regresar</a>
                </div>
                @endcan
            </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        {{ $pai->nombre }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Estaus:</strong>
                        {{ $pai->getEstatus() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection



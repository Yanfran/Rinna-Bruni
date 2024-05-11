@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver municipios
                @can('municipios-list')
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('municipios.index') }}"> Regresar</a>
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
                        <strong>Municipio nombre:</strong>
                        {{ $municipio->nombre }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Estado:</strong>
                        {{ $municipio->Estado->nombre }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Pais:</strong>
                        {{ $municipio->Pais->nombre }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Estaus:</strong>
                        {{ $municipio->getEstatus() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection



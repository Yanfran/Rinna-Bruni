@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Agregar nuevo municipio
                @can('municipios-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('municipios.index') }}"> Regresar</a>
                    </div>
                @endcan
            </h3>

        </div>

        <div class="panel-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Hubo algunos problemas con tus datos.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('municipios.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Pais:</strong>
                            <input type="hidden" name="pais_id" value="1" class="form-control"
                            placeholder="Pais">
                            <input type="text" readonly="true" name="pais_nombre" value="Mexíco" class="form-control"
                                placeholder="Pais">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Estado:</strong>
                            <select class="form-control" name="estado_id">
                                @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>{{ trans('empresas.label_estatus') }}</label>
                            <select name="estatus" class="form-control" id="estatus">
                                <option class="form-control" value="0">{{ trans('empresas.select_inactivo') }}
                                </option>
                                <option selected class="form-control" value="1">{{ trans('empresas.select_activo') }}
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar municipio
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
        <form action="{{ route('municipios.update', $municipio->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        <input type="text" name="nombre" value="{{ $municipio->nombre }}" class="form-control"
                            placeholder="Nombre" required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Pais:</strong>
                        <input type="hidden" name="pais_id" value="{{ $municipio->Pais->id }}" class="form-control"
                        placeholder="Pais">
                        <input type="text" readonly="true" name="pais_nombre" value="{{ $municipio->Pais->nombre }}" class="form-control"
                            placeholder="Pais">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Estado:</strong>
                        <select class="form-control" name="estado_id">
                            @foreach($estados as $estado)
                            <option
                            @if($estado->id == $municipio->estado_id)
                            @selected(true)
                            @endif
                            value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>{{ trans('empresas.label_estatus') }}</label>
                        <select name="estatus" class="form-control" id="estatus">
                            <option class="form-control" value="0"
                                @if ($municipio->getEstatusValue() == 0) @selected(true) @endif>
                                {{ trans('empresas.select_inactivo') }}
                            </option>
                            <option @if ($municipio->getEstatusValue() == 1) @selected(true) @endif class="form-control"
                                value="1">{{ trans('empresas.select_activo') }}</option>

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

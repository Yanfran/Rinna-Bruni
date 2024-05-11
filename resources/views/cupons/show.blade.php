@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver cupon
                @can('cupons-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('cupons.index') }}"> Regresar</a>
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
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="{{ $cupon->nombre }}"
                            readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="tipo">Tipo de cupón:</label>
                        <select class="form-control" name="tipo" id="tipo" readonly>
                            <option @if ($cupon->tipo == '') selected @endif value="">Seleccione un tipo de cupón</option>
                            <option @if ($cupon->tipo == 1) selected @endif value="1">Cupón de dinero</option>
                            <option @if ($cupon->tipo == 2) selected @endif value="2">Cupón de porcentaje aplicable</option>
                            <option @if ($cupon->tipo == 3) selected @endif value="3">Vale a favor</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="cantidad_usos">Cantidad de usos:</label>
                        <input type="number" value="{{ $cupon->cantidad_usos }}" class="form-control" name="cantidad_usos" id="cantidadUsosInput">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="porcentaje">Porcentaje aplicable:</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="porcentaje" id="porcentaje"
                            @if ($cupon->tipo != 2) readonly @endif value="{{ $cupon->tipo == 2 ? $cupon->porcentaje : '' }}">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="monto">Monto:</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="monto" id="monto"
                            value="{{ $cupon->tipo == 1 || $cupon->tipo == 3 ? $cupon->monto : 0 }}" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <label for="fecha_desde">Fecha desde:</label>
                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_desde" min="<?= date('Y-m-d') ?>"
                        value="{{ $cupon->fecha_inicio ? date('Y-m-d', strtotime($cupon->fecha_inicio)) : '' }}" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <label for="fecha_hasta">Fecha hasta:</label>
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_hasta" min="<?= date('Y-m-d') ?>"
                            value="{{ $cupon->fecha_fin ? date('Y-m-d', strtotime($cupon->fecha_fin)) : '' }}" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <label>{{ trans('empresas.label_estatus') }}</label>
                        <select name="estatus" class="form-control" id="estatus" readonly>
                            <option class="form-control" value="0" @if ($cupon->estatus == 0) selected @endif>
                                {{ trans('empresas.select_inactivo') }}</option>
                            <option class="form-control" value="1" @if ($cupon->estatus == 1) selected @endif>
                                {{ trans('empresas.select_activo') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="aplicar_usuario" id="aplicar_usuario"
                                {{ $cupon->user_id != null ? 'checked' : '' }}> Aplicar a un usuario específico
                        </label>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" id="usuario_select_wrapper"
                    style="{{ $cupon->user_id != null ? '' : 'display: none;' }}">
                    <div class="form-group">
                        <label for="usuario_id">Usuario:</label>
                        <select class="form-control" name="user_id" id="usuario_id" readonly>
                            @if ($cupon->user_id != null)
                                <option value="{{ $cupon->usuario->id }}">{{ $cupon->usuario->numero_afiliacion }}:
                                    {{ $cupon->usuario->name }} {{ $cupon->usuario->apellido_paterno }}
                                    {{ $cupon->usuario->apellido_materno }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

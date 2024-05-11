@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar Colonia
                @can('localidad-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('localidad.index') }}"> Regresar</a>
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
                            @if ($error == 'El campo localidad id es obligatorio.')
                                <li>El campo colonia es obligatorio</li>
                            @else                            
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach                                                
                    </ul>
                </div>
            @endif
            <form action="{{ route('localidad.update', $localidad->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            <input type="text" name="nombre" value="{{ $localidad->nombre }}" class="form-control"
                                placeholder="Nombre" required>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Ciudad:</strong>
                            <input type="text" value="{{ $localidad->ciudad }}" name="ciudad" class="form-control" placeholder="Ciudad" required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Código postal:</strong>
                            <input type="number" name="cp" value="{{ $localidad->cp }}" class="form-control" placeholder="45600" pattern="[0-9]{5}" minlength="5" maxlength="5" required>
                          </div>
                    </div>



                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Pais:</strong>
                            <input type="hidden" name="pais_id" value="{{ $localidad->Pais->id }}" class="form-control"
                                placeholder="Pais">
                            <input type="text" readonly="true" name="pais_nombre" value="{{ $localidad->Pais->nombre }}"
                                class="form-control" placeholder="Pais">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Estado:</strong>
                            <select class="form-control" name="estado_id">
                                @foreach ($estados as $estado)
                                    <option @if ($estado->id == $localidad->estado_id) @selected(true) @endif
                                        value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Municipio:</strong>

                        <select class="form-control" name="municipio_id" class="form-control" id="municipio_id" required>

                            @foreach ($localidad->getMunicipios() as $municipio)
                                <option @if ($municipio->id == $localidad->municipio_id) @selected(true) @endif
                                    value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                            @endforeach

                        </select>
                        <small id="tag" class="red"></small>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>{{ trans('empresas.label_estatus') }}</label>
                        <select name="estatus" class="form-control" id="estatus">
                            <option class="form-control" value="0"
                                @if ($localidad->getEstatusValue() == 0) @selected(true) @endif>
                                {{ trans('empresas.select_inactivo') }}
                            </option>
                            <option @if ($localidad->getEstatusValue() == 1) @selected(true) @endif class="form-control"
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

@section('js')
    <script>
        $(document).ready(function() {
            $('select[name="estado_id"]').on('change', function() {
                var estadoId = $(this).val();
                if (estadoId && estadoId != 'Seleccione un estado') {
                    $.ajax({
                        url: '/ajax/municipios/' + estadoId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#municipio_id').empty();
                            $('#tag').empty();
                            $('#municipio_id').append(
                                '<option value="">Seleccione un municipio</option>');
                            if (data.length == 0) {
                                $('#tag').append(
                                    '{{ trans('empresas.msg_elemento_inactivo') }}');
                            }
                            $.each(data, function(key, value) {
                                $('#municipio_id').append('<option value="' + key +
                                    '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    $('#municipio_id').empty();
                    $('#municipio_id').append('<option value="">Seleccione un municipio</option>');
                    $('#tag').empty();
                }
            });

        });
    </script>
@stop

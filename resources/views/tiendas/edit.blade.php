@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar tiendas
                @can('tiendas-list')
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('tiendas.index') }}"> Regresar</a>
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
                    @switch($error)
                        @case('El campo estado id es obligatorio.')
                            <li>El campo estado es obligatorio</li>
                            @break
                        @case('El campo municipio id es obligatorio.')
                            <li>El campo municipio es obligatorio</li>
                            @break
                        @case('El campo localidad id es obligatorio.')
                            <li>El campo colonia es obligatorio</li>
                            @break
                        @default
                            <li>{{ $error }}</li>
                    @endswitch
                @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('tiendas.update', $tienda->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <h4 class="sub-title-principal">Datos generales de la tienda</h4>
                </div>
                <div class="col-md-6">
                    <h4 class="sub-title pull-right">ID del tienda ( {{ $tienda->id }} )</h4>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Nombre de la tienda:</strong>
                        <input value="{{ $tienda->nombre }}" type="text" name="nombre" class="form-control label-rinna" placeholder="Nombre">
                    </div>
                </div>
                {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Código de la tienda o identificador:</strong>
                        <input  value="{{ $tienda->codigo }}" type="text" name="codigo" class="form-control label-rinna" placeholder="0001">
                    </div>
                </div> --}}

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Pais:</strong>
                        <input type="hidden" name="pais_id" value="1" class="form-control" placeholder="Pais">
                        <input type="text" readonly="true" name="pais_nombre" value="México" class="form-control label-rinna"
                            placeholder="Pais">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Estado:</strong>
                        <select class="form-control label-rinna" name="estado_id" required>
                            <option value="">Seleccione un estado</option>

                            @foreach ($estados as $estado)
                                <option
                                @if($estado->id == $tienda->estado_id)
                                @selected(true)
                                @endif

                                value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Municipio:</strong><br>
                        <select style="width: 100%;" class="js-example-basic-single form-control label-rinna"  name="municipio_id" id="municipio_id"
                            required>

                            <option value="">Seleccione un municipio</option>
                            @foreach ($municipios as $municipio)
                            <option
                            @if($municipio->id == $tienda->municipio_id)
                            @selected(true)
                            @endif

                            value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                        @endforeach
                        </select>
                        <small id="tag" class="red"></small>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Colonia:</strong><br>
                        <select style="width: 100%;" class="js-example-basic-single form-control label-rinna"
                            name="localidad_id" id="localidad_id" required>
                            <option value="">Seleccione una Colonia</option>
                            @foreach ($localidads as $localidad)
                                <option
                                @if($localidad->id == $tienda->localidad_id)
                                @selected(true)
                                @endif
                                value="{{ $localidad->id }}">{{ $localidad->nombre }}</option>
                            @endforeach
                        </select>
                        <small id="tag" class="red"></small>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group mt-1">
                        <strong>Código postal:</strong><br>
                        <input type="text" name="cp" id="postal" value="{{ $tienda->cp }}"
                            class="form-control label-rinna"
                            placeholder="Código postal">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group mt-1">
                        <strong>Calle y número :</strong><br>
                        <input type="text" name="calle_numero" value="{{ $tienda->calle_numero }}"
                            class="form-control label-rinna"
                            placeholder="Calle Numero">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group mt-1">
                        <strong>Id externo:</strong><br>
                        <input type="text" name="external_id" value="{{ $tienda->external_id }}"
                            class="form-control label-rinna"
                            placeholder="Id externo">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>{{ trans('empresas.label_estatus') }}</label>
                        <select name="estatus" class="form-control label-rinna" id="estatus">
                            <option class="form-control" @if($tienda->estatus == '0') selected @endif value="0">{{ trans('empresas.select_inactivo') }}</option>
                            <option class="form-control" @if($tienda->estatus == '1') selected @endif value="1">{{ trans('empresas.select_activo') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center seccion-final">
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


            $('.js-example-basic-single').select2();




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

            $('select[name="municipio_id"]').on('change', function() {
                var municipioId = $(this).val();
                if (municipioId && municipioId != 'Seleccione una localidad') {
                    $.ajax({
                        url: '/ajax/localidad/' + municipioId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#localidad_id').empty();
                            $('#tag').empty();
                            $('#localidad_id').append(
                                '<option value="">Seleccione una localidad</option>');
                            if (data.length == 0) {
                                $('#tag').append(
                                    '{{ trans('empresas.msg_elemento_inactivo') }}');
                            }
                            $.each(data, function(key, value) {
                                $('#localidad_id').append('<option value="' + key +
                                    '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una localidad</option>');
                    $('#tag').empty();
                }
            });


            $('select[name="localidad_id"]').on('change', function() {
                $('#postal').val('');
                $('#ciudad').val('');
                var localidadId = $(this).val();
                if (localidadId && localidadId != 'Seleccione una colonia') {
                    $.ajax({
                        url: '/ajax/localidadBusqueda/' + localidadId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            // alert(data.cp);
                            $('#postal').val(data.cp);
                            $('#ciudad').val(data.ciudad);

                        },
                    });
                } else {
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una colonia</option>');
                    $('#postal').val('');
                    $('#ciudad').val('');
                }
            });


        });
    </script>
@stop

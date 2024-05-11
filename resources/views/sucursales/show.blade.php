@extends('layouts.app')
@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Ver sucursal
                {{-- @can('tiendas-list') --}}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('sucursales.index', $sucursal->user->id) }}"> Regresar</a>
                </div>
                {{-- @endcan --}}
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
            <form action="{{ route('sucursales.update', $sucursal->id) }}" method="POST">

                @csrf
                <input type="hidden" name="user_id" value="{{ $sucursal->user->id }}">
                <input type="hidden" name="tipo" value="2">
                <h4 class="sub-title pull-reigth">Distribuidor: {{ $sucursal->user->id }}</h4>
                <div class="row">
                    <h4 class="sub-title">Dirección</h4>

                    <hr class="espaciador">

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombre sucursal (alias)</strong>
                            <input readonly type="hidden" name="pais_id" value="1" class="form-control"
                                placeholder="Pais">
                            <input readonly type="text" name="alias" value="{{ $sucursal->alias }}"
                                class="form-control" placeholder="Nombre sucursal">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Estado* :</strong>
                            <input readonly type="text" value="{{ $sucursal->Estado->nombre }}"
                            class="form-control">

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Municipio* :</strong><br>
                            <input readonly type="text" value="{{ $sucursal->Municipio->nombre }}"
                            class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Colonia* :</strong><br>
                            <input readonly type="text" value="{{ $sucursal->Localidad->nombre }}"
                            class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Código postal* :</strong>
                            <input readonly type="text" name="codigo_postal" value="{{ $sucursal->cp }}"
                                id="postal"
                                class="form-control {{ $errors->has('codigo_postal') ? 'is-invalid' : '' }}"
                                placeholder="Código postal">
                        </div>
                    </div>                    
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input readonly  type="text" name="calle" value="{{ $sucursal->calle }}"
                                class="form-control {{ $errors->has('calle') ? 'is-invalid' : '' }}"
                                placeholder="Calle Numero">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h4 class="sub-title">Contacto</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nombre (Encargado de la sucursal)* :</strong>
                            <input readonly  type="text" name="nombre_encargado" value="{{ $sucursal->nombre_encargado }}"
                                class="form-control {{ $errors->has('nombre_encargado') ? 'is-invalid' : '' }}"
                                placeholder="Nombre encargado">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>1er apellido* :</strong>
                            <input readonly type="text" name="apellido_paterno" value="{{ $sucursal->apellido_paterno }}"
                                class="form-control {{ $errors->has('apellido_paterno') ? 'is-invalid' : '' }}"
                                placeholder="1er apellido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>2do apellido :</strong>
                            <input readonly type="text" name="apellido_materno" value="{{ $sucursal->apellido_materno }}"
                                class="form-control {{ $errors->has('apellido_materno') ? 'is-invalid' : '' }}"
                                placeholder="2do apellido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Correo eléctronico* :</strong>
                            <input readonly type="text" name="correo" value="{{ $sucursal->correo }}"
                                class="form-control {{ $errors->has('correo') ? 'is-invalid' : '' }}"
                                placeholder="Correo eléctronico">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Movil* :</strong>
                            <input readonly type="text" data-mask="99-9999-9999" name="celular" value="{{ $sucursal->celular }}"
                                class="form-control {{ $errors->has('celular') ? 'is-invalid' : '' }}"
                                placeholder="Celular">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Teléfono* :</strong>
                            <input readonly type="text" data-mask="99-9999-9999" name="telefono_fijo" value="{{ $sucursal->telefono_fijo }}"
                                class="form-control {{ $errors->has('telefono_fijo') ? 'is-invalid' : '' }}"
                                placeholder="Teléfono fijo">
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activación de la sucursal</label>
                                <label class="switch">
                                    <input type="checkbox" name="estatus" disabled="disabled" 
                                    @if($sucursal->estatus == 1) checked="true" @endif>
                                    <span class="read-switch slider round"></span>
                                </label>

                            </div>


                        </div>

                    </div>
                </div>


            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $('.js-example-basic-single').select2();

            setTimeout(() => {
                var estadoId = $("input[name='estado_id_hidden']").val();
                if (estadoId !== "") {
                    $("select[name='estado_id'] option[value='" + estadoId + "']").attr("selected",
                        "selected");
                }

                setTimeout(() => {
                    municipio();
                }, 200);

            }, 200);

            function municipio() {
                var estadoId = $("input[name='estado_id_hidden']").val();
                var municipioId = $("input[name='municipio_id_hidden']").val();
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
                                $('#tag').append('{{ trans('empresas.msg_elemento_inactivo') }}');
                            }
                            $.each(data, function(key, value) {
                                $('#municipio_id').append('<option value="' + key +
                                    '">' + value + '</option>');
                            });

                            // Autoseleccionar el municipio
                            if (municipioId !== "") {
                                $("select[name='municipio_id'] option[value='" + municipioId + "']")
                                    .attr("selected", "selected");
                            }

                            setTimeout(() => {
                                localidad();
                            }, 100);


                        },
                    });
                } else {
                    $('#municipio_id').empty();
                    $('#municipio_id').append('<option value="">Seleccione un municipio</option>');
                    $('#tag').empty();
                }
            }

            function localidad() {
                var municipioId = $("input[name='municipio_id_hidden']").val();
                var localidadId = $("input[name='localidad_id_hidden']").val();
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

                            // Autoseleccionar el localidad
                            if (localidadId !== "") {
                                $("select[name='localidad_id'] option[value='" + localidadId + "']")
                                    .attr("selected", "selected");
                            }


                            setTimeout(() => {
                                busquedaLocalidad()
                            }, 100);
                        },
                    });
                } else {
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una localidad</option>');
                    $('#tag').empty();
                }

            }

            function busquedaLocalidad() {
                var localidadId = $("input[name='localidad_id_hidden']").val();
                if (localidadId && localidadId != 'Seleccione una localidad') {
                    $.ajax({
                        url: '/ajax/localidadBusqueda/' + localidadId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            $('#postal').val(data.cp);
                            $('#ciudad').val(data.ciudad);

                        },
                    });
                } else {
                    $('#postal').val('');
                    $('#ciudad').val('');
                }
            }





            $('select[name="estado_id"]').on('change', function() {
                $('#postal').val('');
                $('#ciudad').val('');
                var estadoId = $(this).val();
                if (estadoId && estadoId != 'Seleccione un estado') {
                    $.ajax({
                        url: '/ajax/municipios/' + estadoId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#municipio_id').empty();
                            $('#localidad_id').empty();
                            $('#localidad_id').append(
                                '<option value="">Seleccione una localidad</option>');
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
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una localidad</option>');
                    $('#municipio_id').empty();
                    $('#municipio_id').append('<option value="">Seleccione un municipio</option>');
                    $('#tag').empty();
                }
            });

            $('select[name="municipio_id"]').on('change', function() {
                $('#postal').val('');
                $('#ciudad').val('');
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
                if (localidadId && localidadId != 'Seleccione una localidad') {
                    $.ajax({
                        url: '/ajax/localidadBusqueda/' + localidadId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            $('#postal').val(data.cp);
                            $('#ciudad').val(data.ciudad);

                        },
                    });
                } else {
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una localidad</option>');
                    $('#postal').val('');
                    $('#ciudad').val('');
                }
            });



        });
    </script>
@stop

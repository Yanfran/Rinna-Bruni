@extends('layouts.app')
@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Crear vendedor - Distribuidor: {{ $distribuidor->id }}
                {{-- @can('tiendas-list') --}}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('vendedoresAsociados.index', $distribuidor->id) }}"> Regresar</a>
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
                            @switch($error)
                                @case('contraseña y confirm-password deben coincidir.')
                                    <li>Error, la clave debe coincidir.</li>
                                    @break
                                @case('confirm-password y contraseña deben coincidir.')
                                    <li>Error, verificar clave debe coincidir.</li>
                                    @break
                                @default
                                    <li>{{ $error }}</li>
                            @endswitch
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('vendedores.store') }}" method="POST" autocomplete="off">

                @csrf
                {{-- Imput oara roles y permisos --}}
                <input type="hidden" name="roles[]" value="2">
                <input type="hidden" name="tipo" value="2">
                <input type="hidden" name="rol" value="2">
                <input type="hidden" name="isAsociate" value="true">

                <div class="row">

                    <h4 class="sub-title">Datos generales de la cuenta</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Correo electronico* :</strong>
                            <input type="text" name="email" value="{{ old('email') }}"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Correo electronico">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Clave de acceso* :</strong>
                            <input type="password" value="{{ old('confirm-password') }}" name="password" id="password-1"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" autocomplete="new-password"
                                placeholder="Clave de acceso"
                            >
                            <div class="input-group-append">
                                <button type="button" class="btn btn-show show-password-1" data-target="#password-1">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Verificar clave de acceso* :</strong>
                            <input type="password" value="{{ old('confirm-password') }}" name="confirm-password" id="password-2"
                                class="form-control {{ $errors->has('confirm-password') ? 'is-invalid' : '' }}" autocomplete="new-password"
                                placeholder="Verificar clave de acceso"
                            >
                            <div class="input-group-append">
                                <button type="button" class="btn btn-show show-password-2" data-target="#password-2">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <h4 class="sub-title">Datos generales</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombres* :</strong>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>1er apellido* :</strong>
                            <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}" class="form-control {{ $errors->has('apellido_paterno') ? 'is-invalid' : '' }}"
                                placeholder="1er apellido">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>2do apellido :</strong>
                            <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}" class="form-control {{ $errors->has('apellido_materno') ? 'is-invalid' : '' }}"
                                placeholder="2do apellido">
                        </div>
                    </div>
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Número de afiliación :</strong>
                            <input type="text" name="numero_afiliacion" value="{{ old('numero_afiliacion') }}" class="form-control"
                                placeholder="Número de afiliación">
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tienda a la que pertenece* :</strong>
                            <select disabled class="form-control {{ $errors->has('tienda_id') ? 'is-invalid' : '' }}" name="">
                                <option selected value="">Seleccione una tienda</option>
                                @foreach ($tiendas as $tienda)
                                    <option value="{{ $tienda->id }}"
                                        {{ $distribuidor->tienda_id == $tienda->id ? 'selected' : '' }}>{{ $tienda->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="tienda_id" value="{{ $distribuidor->tienda_id }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Distribuidor asociado :</strong>
                            <input type="text" readonly name="distribuidor"
                            value="{{ $distribuidor->nombre_empresa }} - {{ $distribuidor->name }} - {{ $distribuidor->apellido_paterno }}"
                            class="form-control">
                            <input type="hidden" name="distribuidor_id"
                            value="{{ $distribuidor->id }}"
                            class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de nacimiento :</strong>
                            <input type="date" name="fecha_nacimiento"
                                value=""
                                class="form-control fecha_no_futuras" placeholder="Fecha Nacimiento">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de ingreso :</strong>
                            <input type="date" name="fecha_ingreso"
                                value=""
                                class="form-control fecha_no_futuras" placeholder="Fecha de Ingreso">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <h4 class="sub-title">Dirección principal</h4>
                    <hr class="espaciador">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Domicilio :</strong>
                            <select class="js-example-basic-single form-control {{ $errors->has('domicilio') ? 'is-invalid' : '' }}"
                                name="domicilio" id="domicilio">
                                <option selected value="">Seleccione un domicilio</option>
                                @foreach ($direcciones as $direccion)
                                    <option value="{{ $direccion->id }}">{{ $direccion->alias }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="domicilio_input" name="domicilio_name">
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Pais:</strong>
                            <input type="hidden" name="pais_id" value="1" class="form-control"
                                placeholder="Pais">
                            <input type="text" readonly="true" name="pais_nombre" value="México"
                                class="form-control" placeholder="Pais">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Estado* :</strong>
                            <input type="text" class="form-control name="estado" id="estado" placeholder="Estado" readonly="true">
                            <input type="hidden" name="estado_id" id="estado_id" value="">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Municipio* :</strong><br>
                            <input type="text" class="form-control name="municipio" id="municipio" placeholder="Municipio" readonly="true">
                            <input type="hidden" name="municipio_id" id="municipio_id" value="">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Colonia* :</strong><br>
                            <input type="text" class="form-control name="localidad" id="localidad" placeholder="Colonia" readonly="true">
                            <input type="hidden" name="localidad_id" id="localidad_id" value="">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Código postal* :</strong>
                            <input type="text" onkeypress="return Numeros(event)" name="codigo_postal" value="{{ old('codigo_postal') }}"
                                id="postal"
                                minlength="2"
                                maxlength="5"
                                class="form-control {{ $errors->has('codigo_postal') ? 'is-invalid' : '' }}"
                                readonly="true"
                                placeholder="Código postal">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input type="text" name="calle_numero" id="calle_numero" value=""
                                class="form-control {{ $errors->has('calle_numero') ? 'is-invalid' : '' }}"
                                readonly="true"
                                placeholder="Calle Numero">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h4 class="sub-title">Contacto</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Movil* :</strong>
                            <input type="text" minlength="10" maxlength="10" name="celular" data-mask="99-9999-9999" value="{{ old('celular') }}"
                                class="form-control {{ $errors->has('celular') ? 'is-invalid' : '' }}"
                                placeholder="Movil">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Teléfono* :</strong>
                            <input type="text" minlength="10" maxlength="10" name="telefono_fijo" data-mask="99-9999-9999" value="{{ old('telefono_fijo') }}"
                                class="form-control {{ $errors->has('telefono_fijo') ? 'is-invalid' : '' }}"
                                placeholder="Teléfono">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h4 class="sub-title">Datos fiscales</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>RFC :</strong>
                            <input type="text" name="rfc" value="{{ old('rfc') }}" class="form-control" placeholder="RFC" maxlength="13">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Régimen fiscal:</strong>
                            <select class="form-control" name="regimen_fiscal"  value="{{ old('regimen_fiscal') }}" id="exampleFormControlSelect1">
                                <option selected>Seleccione un régimen fiscal</option>
                                <option value="Persona física">Persona física</option>
                                <option value="Persona moral">Persona moral</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <h4 class="sub-title">Condiciones de crédito</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento distribuidor/vendedor (%):</strong>
                            <input type="number" min="0" max="100" name="descuento" value="{{ old('descuento') }}" class="form-control"
                                placeholder="Descuento">
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Días de credito:</strong>
                            <input type="number" min="0" max="120" name="dia_credito" value="{{ old('dia_credito') }}" class="form-control"
                                placeholder="Días de credito">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de ofertas:</strong>
                            <input type="number" min="0" max="100" name="descuento_oferta" value="{{ old('descuento_oferta') }}" class="form-control"
                                placeholder="Descuento de ofertas">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Límite de crédito ($) :</strong>
                            <input type="text" min="0" name="credito" value="{{ old('credito') }}" class="form-control miles"
                                placeholder="Credito">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de outlet:</strong>
                            <input type="number" name="descuento_outlet" value="{{ old('descuento_outlet') }}" class="form-control"
                                placeholder="Descuento de outlet">
                        </div>
                    </div> --}}
                {{-- </div> --}}
                {{-- <div class="row">
                    <h4 class="sub-title">Descuento ofrecido a clientes (%)</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento ofrecido a clientes :</strong>
                            <input type="number" min="0" name="descuento_clientes" value="{{ old('descuento_clientes') }}"
                                class="form-control {{ $errors->has('descuento_clientes') ? 'is-invalid' : '' }}"
                                placeholder="Descuento ofrecido a clientes">
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h4 class="sub-title">Observaciones:</h4>
                        <div class="form-group">
                            <textarea name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ old('observaciones') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activación de vendedor</label>
                                <label class="switch">
                                    <input type="checkbox" name="estatus" checked="true">
                                    <span class="slider round"></span>
                                </label>

                            </div>
                            <span><small>OFF / ON swich para activacion o desactivacion de vendedor por defecto se
                                    creara en activo</small></span>

                        </div>

                    </div>
                </div>

                <div class="row mt-5 mb-5 seccion-final">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right save">Guardar</button>
                        <a class="btn btn-danger pull-right save" href="{{ route('vendedoresAsociados.index',  $distribuidor->id) }}">
                            Cancelar</a>
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



            $('select[name="domicilio"]').on('change', function() {
                var domicilio = $(this).val();
                var domicilioText = $(this).find('option:selected').text();
                $('#domicilio_input').val(domicilioText);

                var estadoDireccionId = "";
                var municipioDireccionId = "";
                var localidadDireccionId = "";
                if (domicilio && domicilio != 'Seleccione un estado') {
                    $.ajax({
                        url: '/ajax/direcciones/' + domicilio,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            data.forEach(element => {
                                estadoDireccionId = element.estado_id;
                                municipioDireccionId = element.municipio_id;
                                localidadDireccionId = element.localidad_id;
                                $("#postal").val(element.cp);
                                $("#ciudad").val(element.colonia);
                                $("#calle_numero").val(element.calle);
                            });

                            estado(estadoDireccionId);
                            municipio(municipioDireccionId);
                            localidad(localidadDireccionId);
                        },
                    });
                } else {

                }
            });


            function estado(id){
                var estadoId = id;
                $.ajax({
                    url: '/ajax/estadoDirecciones/' + estadoId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#estado").val(data.nombre);
                        $("#estado_id").val(data.id);
                    },
                });
            }

            function municipio(id) {
                var municipioId = id;
                $.ajax({
                    url: '/ajax/municipioDirecciones/' + municipioId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#municipio").val(data.nombre);
                        $("#municipio_id").val(data.id);
                    },
                });
            }

            function localidad(id) {
                var localidad = id;
                $.ajax({
                    url: '/ajax/localidadDirecciones/' + localidad,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#localidad").val(data.nombre);
                        $("#localidad_id").val(data.id);
                    },
                });
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

                            // Convertir el objeto en un array de objetos para ordenar
                            var dataArray = Object.entries(data);

                            // Ordenar el array de objetos por el valor (nombre)
                            dataArray.sort((a, b) => a[1].localeCompare(b[1]));

                            $.each(dataArray, function(_, item) {
                                var key = item[0];
                                var value = item[1];
                                $('#localidad_id').append('<option value="' + key + '">' + value + '</option>');
                            });

                            // $.each(data, function(key, value) {
                            //     $('#localidad_id').append('<option value="' + key +
                            //         '">' + value + '</option>');
                            // });
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
                            console.log(data);

                            $('#postal').val(data.cp);
                            $('#ciudad').val(data.ciudad);

                        },
                    });
                } else {
                    $('#postal').val('');
                    $('#ciudad').val('');
                }
            });


        });
    </script>
@stop

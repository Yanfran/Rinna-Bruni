@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Editar vendedor
                <!-- @can('tiendas-list')
        -->
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('vendedores.index') }}"> Regresar</a>
                    </div>
                    <!--
    @endcan -->
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
            <form action="{{ route('vendedores.update', $vendedores->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')


                <div class="row">
                    <input type="hidden" name="tipo" class="form-control" value="2">
                    <div class="col-md-6">
                        <h4 class="sub-title-principal">Datos generales de la cuenta</h4>
                    </div>
                    <div class="col-md-6">
                        <h4 class="sub-title pull-right">ID del usuario ( {{ $vendedores->id }} )</h4>
                    </div>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Correo electronico* :</strong>
                            <input type="text" name="email" value="{{ $vendedores->email }}"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Correo electronico">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Clave de acceso* :</strong>
                            <input type="password" name="password" id="password-1"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" autocomplete="new-password"
                                placeholder="Clave de acceso" readonly>
                            {{-- <div class="input-group-append">
                                <button type="button" class="btn btn-show show-password-1" data-target="#password-1">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Verificar clave de acceso* :</strong>
                            <input type="password" name="confirm-password" id="password-2"
                                class="form-control {{ $errors->has('confirm-password') ? 'is-invalid' : '' }}" autocomplete="new-password"
                                placeholder="Verificar clave de acceso" readonly>
                            {{-- <div class="input-group-append">
                                <button type="button" class="btn btn-show show-password-2" data-target="#password-2">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>

                </div>

                <div class="row">
                    <input type="hidden" name="tipo" class="form-control" value="2">
                    <h4 class="sub-title">Datos generales</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombres* :</strong>
                            <input type="text" name="name" value="{{ $vendedores->name }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>1er apellido* :</strong>
                            <input type="text" name="apellido_paterno" value="{{ $vendedores->apellido_paterno }}"
                                class="form-control" placeholder="1er apellido">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>2do apellido :</strong>
                            <input type="text" name="apellido_materno" value="{{ $vendedores->apellido_materno }}"
                                class="form-control" placeholder="2do apellido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Número de afiliación :</strong>
                            <input type="text" value="{{ $vendedores->numero_afiliacion }}" name="numero_afiliacion"
                                class="form-control" placeholder="Número de afiliación" readonly>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tienda a la que pertenece* :</strong>

                            <select disabled class="form-control {{ $errors->has('tienda') ? 'is-invalid' : '' }}" name="tienda_id" id="tienda_id">
                                <option selected value="">Seleccione una tienda</option>
                                @foreach ($tiendas as $tienda)

                                    <option value="{{ $tienda->id }}"
                                        {{ old('tienda_id') == $tienda->id ? 'selected' : ($idTienda == $tienda->id ? 'selected' : '') }}>
                                        {{ $tienda->nombre }}
                                    </option>

                                @endforeach
                            </select>

                            <input type="hidden" name="tienda_id" value="{{ $idTienda }}">
                            <input type="hidden" name="tienda_id_hidden" value="{{ $idTienda }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 d-none">
                        <div class="form-group">
                            <strong>Distribuidor asociado :</strong>
                            <select disabled class="js-example-basic-single form-control" name="distribuidor_id" id="distribuidor_id">
                                <option selected value="">Seleccione un distribuidor</option>
                                @foreach ($distribuidores as $distribuidor)
                                    <option value="{{ $distribuidor->id }}"
                                        {{ $vendedores->distribuidor_id == $distribuidor->id ? 'selected' : '' }}>
                                        {{ $distribuidor->name }} {{ $distribuidor->apellido_paterno }}
                                        {{ $distribuidor->apellido_materno }} - {{ $distribuidor->numero_afiliacion }}
                                    </option>
                                @endforeach

                            </select>
                            <input type="hidden" name="distribuidor_id_hidden" value="{{$vendedores->distribuidor_id}}">

                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de nacimiento :</strong>
                            <input type="date" name="fecha_nacimiento"
                                value="{{ $vendedores->fecha_nacimiento ? date('Y-m-d', strtotime($vendedores->fecha_nacimiento)) : '' }}"
                                class="form-control fecha_no_futuras" placeholder="Fecha Nacimiento">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de ingreso :</strong>
                            <input type="date" name="fecha_ingreso"
                                value="{{ $vendedores->fecha_ingreso ? date('Y-m-d', strtotime($vendedores->fecha_ingreso)) : '' }}"
                                class="form-control fecha_no_futuras" placeholder="Fecha de Ingreso">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <h4 class="sub-title">Dirección principal</h4>
                    <hr class="espaciador">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">

                            <div id="selectDomicilio">
                                <strong>Domicilio :</strong>
                                {{-- @if(Auth::user()->rol != '0' && Auth::user()->rol != '2') disabled @endif --}}
                                <select
                                    class="js-example-basic-single form-control {{ $errors->has('domicilio') ? 'is-invalid' : '' }}"
                                    name="domicilio" id="domicilio">
                                    <option selected value="">Seleccione un domicilio</option>
                                </select>
                                <input type="hidden" name="domicilio_id_hidden" value="{{ $direcciones->id }}">
                            </div>

                            <input type="hidden" id="domicilio_input" name="domicilio_name" value="{{ $direcciones->alias }}">

                        </div>
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
                        <div class="form-group {{ $errors->has('estado_id') ? 'is-invalid-select1' : '' }}">
                            <strong>Estado* :</strong>
                            <div id="selectEstado">
                                <select class="form-control"
                                    name="estado_id" id="estado_id">
                                    <option selected value="">Seleccione un estado</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}"
                                            {{ $direcciones && $direcciones->estado_id == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                                <input type="hidden" id="estado_id_hidden" name="" value="{{ $direcciones->estado_id }}">
                            </div>


                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('municipio_id') ? 'is-invalid-select2' : '' }}">
                            <strong>Municipio* :</strong><br>
                            <div id="selectMunicipio">
                                <select style="width: 100%;"
                                    class="js-example-basic-single form-control"
                                    name="municipio_id" id="municipio_id">
                                    <option value="{{ old('municipio_id') }}">Seleccione un municipio</option>
                                </select>
                                <input type="hidden" id="municipio_id_hidden" value="{{ $direcciones->municipio_id }}">
                                <small id="tag" class="red"></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('localidad_id') ? 'is-invalid-select3' : '' }}">
                            <strong>Colonia* :</strong><br>
                            <div id="selectLocalidad">
                                <select style="width: 100%"
                                    class="js-example-basic-single form-control"
                                    name="localidad_id" id="localidad_id">
                                    <option value="{{ old('localidad_id') }}">Seleccione una colonia</option>
                                </select>
                                <input type="hidden" id="localidad_id_hidden" value="{{ $direcciones->localidad_id }}">
                                <small id="tag" class="red"></small>

                            </div>

                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Código postal* :</strong>
                            <input type="text" onkeypress="return Numeros(event)" name="codigo_postal" value=""
                                id="postal"
                                minlength="2"
                                maxlength="5"
                                class="form-control {{ $errors->has('codigo_postal') ? 'is-invalid' : '' }}"
                                placeholder="Código postal">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input type="text" name="calle_numero" id="calle_numero"
                                value="{{ $direcciones ? $direcciones->calle : '' }}"
                                class="form-control {{ $errors->has('calle_numero') ? 'is-invalid' : '' }}"
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
                            <input type="text" minlength="10" maxlength="10" name="celular" data-mask="99-9999-9999"
                                value="{{ $vendedores->celular }}"
                                class="form-control {{ $errors->has('celular') ? 'is-invalid' : '' }}"
                                placeholder="Movil">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Teléfono* :</strong>
                            <input type="text" minlength="10" maxlength="10" name="telefono_fijo" data-mask="99-9999-9999"
                                value="{{ $vendedores->telefono_fijo }}"
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
                            <input type="text" name="rfc" value="{{ $vendedores->rfc }}" class="form-control" placeholder="RFC" maxlength="13">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Régimen fiscal :</strong>
                            <select class="form-control" name="regimen_fiscal" id="exampleFormControlSelect1">
                                <option value="" @if($vendedores->regimen_fiscal === '') selected @endif>Seleccione un régimen fiscal</option>
                                <option value="Persona física" @if($vendedores->regimen_fiscal === 'Persona física') selected @endif>Persona física</option>
                                <option value="Persona moral" @if($vendedores->regimen_fiscal === 'Persona moral') selected @endif>Persona moral</option>
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
                            <input type="text" name="descuento" value="{{ $vendedores->descuento }}"
                                class="form-control" placeholder="Descuento"
                                @if(Auth::user()->rol == '2') readonly @endif>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Días de credito:</strong>
                            <input type="text" min="0" max="120" name="dia_credito" value="{{ $vendedores->dia_credito }}" class="form-control"
                                placeholder="Días de credito"
                                @if(Auth::user()->rol == '2') readonly @endif>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de ofertas:</strong>
                            <input type="text" name="descuento_oferta" value="{{ $vendedores->descuento_oferta }}" class="form-control"
                                placeholder="Descuento de ofertas" readonly>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Límite de crédito ($) :</strong>
                            <input type="text" name="credito" value="{{ $vendedores->credito }}"
                                class="form-control miles" placeholder="Credito"
                                @if(Auth::user()->rol == '2') readonly @endif>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de outlet:</strong>
                            <input type="text" name="descuento_outlet" value="{{ $vendedores->descuento_outlet }}" class="form-control"
                                placeholder="Descuento de outlet" readonly>
                        </div>
                    </div> --}}

                {{-- </div> --}}

                {{-- <div class="row">
                    <h4 class="sub-title">Descuento ofrecido a clientes (%)</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento ofrecido a clientes :</strong>
                            <input type="number" name="descuento_clientes" value="{{ $vendedores->descuento_clientes }}"
                                class="form-control {{ $errors->has('descuento_clientes') ? 'is-invalid' : '' }}"
                                placeholder="Descuento ofrecido a clientes">
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h4 style="margin-left: 15px;">Observaciones:</h4>
                        <div class="form-group">
                            <textarea name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ $vendedores->observaciones }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <input type="hidden" class="estatus" name="estatus" value="{{$vendedores->estatus}}">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activación de vendedor</label>
                                <label class="switch">
                                    <input type="checkbox" name="estatus_check"
                                        @if($vendedores->estatus == 1) checked="true" @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <span><small>OFF / ON swich para activacion o desactivacion de vendedor por defecto se creara en activo</small></span>

                        </div>

                    </div>
                </div>

                <div class="row mt-5 seccion-final">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right save">Guardar</button>
                        <a class="btn btn-danger pull-right save" href="{{ route('vendedores.index') }}">
                            Cancelar</a>
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

            $('input[name="estatus_check"]').on('change', function() {
                var isChecked = $(this).prop('checked');

                if (isChecked) {
                    $('.estatus').val(1);
                } else {
                    $('.estatus').val(0);
                }
            });

            $('.js-example-basic-single').select2();


            if ("{{ $vendedores->distribuidor_id }}") {
                $("#selectDomicilio").show();
            }  else {
                $("#selectDomicilio").hide();
            }


            $('select[name="tienda_id"]').on('change', function() {

                var SelectEstado = $('select[name="tienda_id"]').val();
                var estadoSeleccionado = $('#estado_id');

                if (SelectEstado == '') {
                    $('#estado_id').empty();
                    $('#estado_id').append('<option value="">Seleccione un estado</option>');
                    $("#estado_id").trigger("change");

                    $.ajax({
                        url: '/ajax/obtenerEstados',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {

                            estadoSeleccionado.empty();
                            estadoSeleccionado.append('<option value="">Seleccione un estado</option>');

                            var estadosArray = Object.entries(data);

                            estadosArray.forEach(function(estado) {
                                var estadoId = estado[0];
                                var estadoNombre = estado[1];
                                estadoSeleccionado.append('<option value="' + estadoId + '">' + estadoNombre + '</option>');
                            });
                        },
                    });

                }



                var tienda = $(this).val();
                var postalInput = $('#postal');
                var ciudadlInput = $('#ciudad');
                var callelInput = $('#calle_numero');

                // direccionesTiendas(tienda);

                if (tienda && tienda != 'Seleccione un tienda') {

                    $.ajax({
                        url: '/ajax/distribuidoresAll/' + tienda,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            $('#distribuidor_id').empty();

                            $('#distribuidor_id').append(
                                '<option selected value="">Seleccione un distribuidor</option>'
                            );

                            data.forEach(function(element) {
                                $('#distribuidor_id').append('<option value="' + element
                                    .id +
                                    '">' + element.name + '</option>');
                            });

                        },
                    });
                } else {

                    $('#municipio_id').empty();
                    $('#localidad_id').empty();
                    $('#distribuidor_id').empty();
                    $('#postal').val('');
                    $('#ciudad').val('');
                    $('#calle_numero').val('');

                    $('#estado_id').prop("disabled", false);
                    $('#municipio_id').prop("disabled", false);
                    $('#localidad_id').prop("disabled", false);

                    postalInput.attr('readonly', false);
                    ciudadlInput.attr('readonly', false);
                    callelInput.attr('readonly', false);

                    $('#selectDomicilio').hide();

                    $('#distribuidor_id').append(
                        '<option value="">Seleccione un distribuidor</option>');

                    $('#municipio_id').append(
                        '<option value="">Seleccione un municipio</option>');

                    $('#localidad_id').append(
                        '<option value="">Seleccione una colonia</option>');
                }

            })


            $('select[name="distribuidor_id"]').on('change', function() {

                var SelectDistribuidor = $('select[name="distribuidor_id"]').val();
                var estadoSeleccionado = $('#estado_id');
                if (SelectDistribuidor == '') {
                    $('#estado_id').empty();
                    $('#estado_id').append('<option value="">Seleccione un estado</option>');
                    $("#estado_id").trigger("change");

                    $.ajax({
                        url: '/ajax/obtenerEstados',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {

                            estadoSeleccionado.empty();
                            estadoSeleccionado.append('<option value="">Seleccione un estado</option>');

                            var estadosArray = Object.entries(data);

                            estadosArray.forEach(function(estado) {
                                var estadoId = estado[0];
                                var estadoNombre = estado[1];
                                estadoSeleccionado.append('<option value="' + estadoId + '">' + estadoNombre + '</option>');
                            });
                        },
                    });

                }


                var distribuidor = $(this).val();
                var postalInput = $('#postal');
                var ciudadlInput = $('#ciudad');
                var callelInput = $('#calle_numero');


                if (distribuidor && distribuidor != 'Seleccione un distribuidor') {
                    $.ajax({
                        url: '/ajax/AliasDirecciones/' + distribuidor,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            $('#domicilio').empty();
                            $('#municipio_id').empty();
                            $('#localidad_id').empty();
                            $('#postal').val('');
                            $('#ciudad').val('');
                            $('#calle_numero').val('');

                            $('#selectDomicilio').show();


                            $('#domicilio').append(
                                '<option value="">Seleccione un domicilio</option>');



                            $.each(data, function(key, value) {
                                $('#domicilio').append('<option value="' + key +
                                    '">' + value + '</option>');
                            });



                            $('#estado_id').prop("disabled", true);
                            $('#municipio_id').prop("disabled", true);
                            $('#localidad_id').prop("disabled", true);

                            postalInput.attr('readonly', true);
                            ciudadlInput.attr('readonly', true);
                            callelInput.attr('readonly', true);

                            $('#municipio_id').append(
                                '<option value="">Seleccione un municipio</option>');

                            $('#localidad_id').append(
                                '<option value="">Seleccione una colonia</option>');

                            $('#estado_id_hidden').attr('name', 'estado_id');
                            $('#estado_id').removeAttr('name');
                            $('#municipio_id_hidden').attr('name', 'municipio_id');
                            $('#municipio_id').removeAttr('name');
                            $('#localidad_id_hidden').attr('name', 'localidad_id');
                            $('#localidad_id').removeAttr('name');

                        },
                    });
                } else {

                    $('#estado_id').prop("disabled", false);
                    $('#municipio_id').prop("disabled", false);
                    $('#localidad_id').prop("disabled", false);

                    postalInput.attr('readonly', false);
                    ciudadlInput.attr('readonly', false);
                    callelInput.attr('readonly', false);

                    $('#selectDomicilio').hide();
                    $('#municipio_id').empty();
                    $('#localidad_id').empty();
                    $('#postal').val('');
                    $('#ciudad').val('');
                    $('#calle_numero').val('');

                    $('#municipio_id').append(
                        '<option value="">Seleccione un municipio</option>');

                    $('#localidad_id').append(
                        '<option value="">Seleccione una colonia</option>');

                    $('#estado_id').attr('name', 'estado_id');
                    $('#estado_id_hidden').removeAttr('name');
                    $('#municipio_id').attr('name', 'municipio_id');
                    $('#municipio_id_hidden').removeAttr('name');
                    $('#localidad_id').attr('name', 'localidad_id');
                    $('#localidad_id_hidden').removeAttr('name');

                }
            });


            $('select#domicilio').on('change', function() {

                var domicilio = $(this).val();

                var domicilioText = $(this).find('option:selected').text();
                $('#domicilio_input').val(domicilioText);


                var estadoDireccionId = "";
                var municipioDireccionId = "";
                var localidadDireccionId = "";
                if (domicilio && domicilio != 'Seleccione un domicilio') {
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
                            setTimeout(() => {
                                estadoD(estadoDireccionId);
                                setTimeout(() => {
                                    municipioD(municipioDireccionId);
                                    setTimeout(() => {
                                        localidadD(localidadDireccionId);
                                    }, 100);
                                }, 100);
                            }, 100);

                        },
                    });
                } else {

                }
            });


            function estadoD(id) {
                var estadoId = id;
                $.ajax({
                    url: '/ajax/estadoDirecciones/' + estadoId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#estado_id option:selected").removeAttr("selected");
                        $("#estado_id option[value='" + estadoId + "']").attr("selected", "selected");
                        $("#estado_id").trigger("change");

                        $("#estado_id_hidden").val(estadoId);
                    },
                });
            }

            function municipioD(id) {
                var municipioId = id;
                $.ajax({
                    url: '/ajax/municipioDirecciones/' + municipioId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        setTimeout(() => {
                            $("#municipio_id option:selected").removeAttr("selected");
                            $("#municipio_id option[value='" + municipioId + "']").attr("selected", "selected");
                            $("#municipio_id").trigger("change");

                            $("#municipio_id_hidden").val(municipioId);
                        }, 1000);

                    },
                });
            }

            function localidadD(id) {
                var localidadId = id;
                $.ajax({
                    url: '/ajax/localidadDirecciones/' + localidadId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        setTimeout(() => {
                            $("#localidad_id option:selected").removeAttr("selected");
                            $("#localidad_id option[value='" + localidadId + "']").attr("selected", "selected");
                            $("#localidad_id").trigger("change");

                            $("#localidad_id_hidden").val(localidadId);
                        }, 1500);
                    },
                });
            }



            setTimeout(() => {
                var tiendaId = $("input[name='tienda_id_hidden']").val();
                var distribuidorId = $("input[name='distribuidor_id_hidden']").val();

                // alert(tiendaId +" " +distribuidorId);

                if (tiendaId && tiendaId != 'Seleccione una tienda') {
                    $.ajax({
                        url: '/ajax/distribuidoresAll/' + tiendaId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            // $('#distribuidor_id').empty();

                            if (distribuidorId) {
                                $('#selectDomicilio').show();
                            }

                            domicilio(distribuidorId);

                        },
                    });
                } else {
                    $('#distribuidor_id').empty();
                    $('#distribuidor_id').append(
                        '<option selected value="">Seleccione un distribuidor</option>'
                    );
                }

            }, 500);


            function domicilio(distribuidor){

                var domicilio = $("input[name='domicilio_name']").val();

                if (distribuidor && distribuidor != 'Seleccione un estado') {
                    $.ajax({
                        url: '/ajax/AliasDirecciones/' + distribuidor,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#domicilio').empty();

                            $.each(data, function(key, value) {
                                $('#domicilio').append('<option value="' + key +
                                    '">' + value + '</option>');
                            });

                            $("#domicilio option").filter(function() {
                                return $(this).text() === domicilio;
                            }).prop("selected", true);

                            var selectedOption = $('#domicilio').find(':selected');
                            var selectedText = selectedOption.text();

                            $('#domicilio_input').val(selectedText);


                        }
                    });
                } else {

                }
            }






            setTimeout(() => {

                setTimeout(() => {
                    var distribuidor = $('#distribuidor_id').val();
                    var tienda = $('#tienda_id').val();

                    if (distribuidor == '') {
                        if (tienda) {
                            municipio();
                            // direccionesTiendas(tienda);
                        }
                    } else {

                        var postalInput = $('#postal');
                        var ciudadlInput = $('#ciudad');
                        var callelInput = $('#calle_numero');

                        var domicilio = $("input[name='domicilio_id_hidden']").val();
                        if (domicilio && domicilio != 'Seleccione un domicilio') {
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
                                    setTimeout(() => {
                                        estadoD(estadoDireccionId);
                                        setTimeout(() => {
                                            municipioD(municipioDireccionId);
                                            setTimeout(() => {
                                                localidadD(localidadDireccionId);
                                            }, 100);
                                        }, 100);
                                    }, 100);


                                    setTimeout(() => {
                                        $('#estado_id').prop("disabled", true);
                                        $('#municipio_id').prop("disabled", true);
                                        $('#localidad_id').prop("disabled", true);

                                        postalInput.attr('readonly', true);
                                        ciudadlInput.attr('readonly', true);
                                        callelInput.attr('readonly', true);
                                    }, 350);

                                    $('#estado_id_hidden').attr('name', 'estado_id');
                                    $('#estado_id').removeAttr('name');
                                    $('#municipio_id_hidden').attr('name', 'municipio_id');
                                    $('#municipio_id').removeAttr('name');
                                    $('#localidad_id_hidden').attr('name', 'localidad_id');
                                    $('#localidad_id').removeAttr('name');

                                },
                            });
                        } else {

                        }
                    }

                }, 800);

            }, 500);




            //Tiendas
            function direccionesTiendas(id){
                var tiendaId = id;
                var postalInput = $('#postal');
                var ciudadlInput = $('#ciudad');
                var callelInput = $('#calle_numero');
                $.ajax({
                    url: '/ajax/tiendaDirecciones/' + tiendaId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var { estado_id, municipio_id, localidad_id, calle_numero, cp } = data;
                        setTimeout(() => {
                            estadoT(estado_id);
                            setTimeout(() => {
                                municipioT(municipio_id);
                                setTimeout(() => {
                                    localidadT(localidad_id);
                                    $('#calle_numero').val(calle_numero);
                                        setTimeout(() => {
                                            $('#postal').val(cp);
                                        }, 5000);
                                }, 100);
                            }, 100);
                        }, 100);



                        $('#estado_id').prop("disabled", true);
                        $('#municipio_id').prop("disabled", true);
                        $('#localidad_id').prop("disabled", true);

                        postalInput.attr('readonly', true);
                        ciudadlInput.attr('readonly', true);
                        callelInput.attr('readonly', true);


                        $('#estado_id_hidden').attr('name', 'estado_id');
                        $('#estado_id').removeAttr('name');
                        $('#municipio_id_hidden').attr('name', 'municipio_id');
                        $('#municipio_id').removeAttr('name');
                        $('#localidad_id_hidden').attr('name', 'localidad_id');
                        $('#localidad_id').removeAttr('name');



                    },
                });
            }

            function estadoT(id) {
                var estadoId = id;
                $.ajax({
                    url: '/ajax/estadoDirecciones/' + estadoId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#estado_id option:selected").removeAttr("selected");
                        $("#estado_id option[value='" + estadoId + "']").attr("selected", "selected");
                        $("#estado_id").trigger("change");

                        $("#estado_id_hidden").val(estadoId);
                    },
                });
            }

            function municipioT(id) {
                var municipioId = id;
                $.ajax({
                    url: '/ajax/municipioDirecciones/' + municipioId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        setTimeout(() => {
                            $("#municipio_id option:selected").removeAttr("selected");
                            $("#municipio_id option[value='" + municipioId + "']").attr("selected", "selected");
                            $("#municipio_id").trigger("change");

                            $("#municipio_id_hidden").val(municipioId);
                        }, 1000);

                    },
                });
            }

            function localidadT(id) {
                var localidadId = id;

                 // Desvincular el evento change del select con nombre "municipio_id"
                // $('select[name="municipio_id"]').off('change');

                $.ajax({
                    url: '/ajax/localidadDirecciones/' + localidadId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        setTimeout(() => {
                            $("#localidad_id option:selected").removeAttr("selected");
                            $("#localidad_id option[value='" + localidadId + "']").attr("selected", "selected");
                            $("#localidad_id").trigger("change");

                            $("#localidad_id_hidden").val(localidadId);


                        }, 1500);
                    },
                });
            }



            function municipio() {
                var estadoId = $("#estado_id").val();
                var municipioId = $("#municipio_id_hidden").val();
                if (estadoId && estadoId != 'Seleccione un estado') {
                    $.ajax({
                        url: '/ajax/municipios/' + estadoId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#municipio_id').empty();
                            $('#municipio_id').append('<option value="">Seleccione un municipio</option>');
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
                var municipioId = $("#municipio_id_hidden").val();
                var localidadId = $("#localidad_id_hidden").val();
                if (municipioId && municipioId != 'Seleccione una colonia') {
                    $.ajax({
                        url: '/ajax/localidad/' + municipioId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#localidad_id').empty();
                            $('#localidad_id').append('<option value="">Seleccione una colonia</option>');

                            // Convertir el objeto en un array de objetos para ordenar
                            var dataArray = Object.entries(data);

                            // Ordenar el array de objetos por el valor (nombre)
                            dataArray.sort((a, b) => a[1].localeCompare(b[1]));

                            $.each(dataArray, function(_, item) {
                                var key = item[0];
                                var value = item[1];
                                $('#localidad_id').append('<option value="' + key + '">' + value + '</option>');
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
                    $('#localidad_id').append('<option value="">Seleccione una colonia</option>');
                    $('#tag').empty();
                }

            }

            function busquedaLocalidad() {
                var localidadId = $("#localidad_id_hidden").val();
                if (localidadId && localidadId != 'Seleccione una colonia') {
                    $.ajax({
                        url: '/ajax/localidadBusqueda/' + localidadId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            $('#postal').val(data.cp);
                            // $('#ciudad').val(data.ciudad);

                        },
                    });
                } else {
                    $('#postal').val('');
                    // $('#ciudad').val('');
                }
            }




            $('select[name="estado_id"]').on('change', function() {
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
                                '<option value="">Seleccione una colonia</option>');
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
                                '<option value="">Seleccione una colonia</option>');

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
                    $('#localidad_id').append('<option value="">Seleccione una colinia 8</option>');
                    $('#tag').empty();
                }
            });

            $('select[name="localidad_id"]').on('change', function() {
                var localidadId = $(this).val();
                var distribuidor_id = $("#distribuidor_id").val();
                if (localidadId && localidadId != 'Seleccione una localidad' && distribuidor_id == "") {
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
                }
            });



        });
    </script>
@stop

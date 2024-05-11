@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar distribuidor
                {{-- @can('tiendas-list') --}}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('distribuidores.index') }}"> Regresar</a>
                </div>
                {{-- @endcan --}}
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
                                @case('El campo contraseña es obligatorio.')
                                    <li>El campo clave es obligatorio.</li>
                                    @break
                                @case('El campo confirm-password es obligatorio.')
                                    <li>El campo verificar clave es obligatorio.</li>
                                    @break
                                @default
                                    <li>{{ $error }}</li>
                            @endswitch
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('distribuidores.update', $distribuidores->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <input type="hidden" name="roles[]" value="3">
                <input type="hidden" name="tipo" value="3">
                <input type="hidden" name="rol" value="3">


                <div class="row">

                    <div class="col-md-6">
                        <h4 class="sub-title-principal">Datos generales de la cuenta</h4>
                    </div>
                    <div class="col-md-6">
                        <h4 class="sub-title pull-right">ID del usuario ( {{ $distribuidores->id }} )</h4>
                    </div>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Correo electronico* :</strong>
                            <input type="text" name="email" value="{{ $distribuidores->email }}"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Correo electronico">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Clave de acceso* :</strong>
                            <input type="password" name="password" id="password-1"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" autocomplete="new-password"
                                placeholder="Clave de acceso" readonly
                            >
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
                                placeholder="Verificar clave de acceso" readonly
                            >
                            {{-- <div class="input-group-append">
                                <button type="button" class="btn btn-show show-password-2" data-target="#password-2">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="row">

                    <h4 class="sub-title">Datos generales</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombres* :</strong>
                            <input type="text" name="name" value="{{ $distribuidores->name }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>1er apellido* :</strong>
                            <input type="text" name="apellido_paterno" value="{{ $distribuidores->apellido_paterno }}" class="form-control"
                                placeholder="1er apellido">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>2do apellido :</strong>
                            <input type="text" name="apellido_materno" value="{{ $distribuidores->apellido_materno }}" class="form-control"
                                placeholder="2do apellido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Número de afiliación* :</strong>
                            <input type="text" name="numero_afiliacion" value="{{ $distribuidores->numero_afiliacion }}" class="form-control {{ $errors->has('numero_afiliacion') ? 'is-invalid' : '' }}"
                                placeholder="Número de afiliación" readonly>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tienda a la que pertenece* :</strong>
                            <select class="form-control {{ $errors->has('tienda_id') ? 'is-invalid' : '' }}" name="tienda_id">
                                <option selected value="">Seleccione una tienda</option>
                                @foreach ($tiendas as $tienda)
                                    @if($distribuidores->Tienda) {
                                        <option value="{{ $tienda->id }}"
                                            {{ old('tienda_id') == $tienda->id ? 'selected' : ($distribuidores->Tienda->id == $tienda->id ? 'selected' : '') }}>
                                            {{ $tienda->nombre }}
                                        </option>
                                    @else
                                        <option value="{{ $tienda->id }}"
                                            {{ old('tienda_id') == $tienda->id ? 'selected' : '' }}>
                                            {{ $tienda->nombre }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombre de la empresa* :</strong>
                            <input type="text" name="nombre_empresa" value="{{ $distribuidores->nombre_empresa }}" class="form-control {{ $errors->has('nombre_empresa') ? 'is-invalid' : '' }}"
                                placeholder="Nombre de la empresa">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <h4 class="sub-title">Dirección principal</h4>
                    <hr class="espaciador">

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Pais:</strong>
                            <input type="hidden" name="pais_id" value="1" class="form-control" placeholder="Pais">
                            <input type="text" readonly="true" name="pais_nombre" value="México"
                                class="form-control" placeholder="Pais">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('estado_id') ? 'is-invalid-select1' : '' }}">
                            <strong>Estado* :</strong>
                            <select class="js-example-basic-single form-control"
                                name="estado_id">
                                <option selected value="">Seleccione un estado</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}"
                                        {{ $direcciones && $direcciones->estado_id == $estado->id ? 'selected' : '' }}>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($direcciones)
                                <input type="hidden" name="estado_id_hidden" value="{{ $direcciones->estado_id }}">
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('municipio_id') ? 'is-invalid-select2' : '' }}">
                            <strong>Municipio* :</strong><br>
                            <select style="width: 100%;"
                                class="js-example-basic-single form-control"
                                name="municipio_id" id="municipio_id">
                                <option value="{{ old('municipio_id') }}">Seleccione un municipio</option>
                            </select>
                            @if ($direcciones)
                                <input type="hidden" name="municipio_id_hidden"
                                    value="{{ $direcciones->municipio_id }}">
                            @endif
                            <small id="tag" class="red"></small>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('localidad_id') ? 'is-invalid-select3' : '' }}">
                            <strong>Colonia* :</strong><br>
                            <select style="width: 100%"
                                class="js-example-basic-single form-control"
                                name="localidad_id" id="localidad_id">
                                <option value="{{ old('localidad_id') }}">Seleccione una localidad</option>
                            </select>
                            @if ($direcciones)
                                <input type="hidden" name="localidad_id_hidden"
                                    value="{{ $direcciones->localidad_id }}">
                            @endif
                            <small id="tag" class="red"></small>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Código postal* :</strong>
                            <input type="text" onkeypress="return Numeros(event)" name="codigo_postal"
                                id="postal"
                                minlength="2"
                                maxlength="5"
                                value="{{ $direcciones ? $direcciones->cp : '' }}" id="postal"
                                class="form-control {{ $errors->has('codigo_postal') ? 'is-invalid' : '' }}"
                                placeholder="Código postal">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input type="text" name="calle_numero"
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
                            <input type="text" minlength="10" maxlength="10" name="celular" data-mask="99-9999-9999" value="{{ $distribuidores->celular }}"
                                class="form-control {{ $errors->has('celular') ? 'is-invalid' : '' }}"
                                placeholder="Movil">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Teléfono* :</strong>
                            <input type="text" minlength="10" maxlength="10" name="telefono_fijo" data-mask="99-9999-9999" value="{{ $distribuidores->telefono_fijo }}"
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
                            <strong>RFC* :</strong>
                            <input type="text" name="rfc" value="{{ $distribuidores->rfc }}" class="form-control" placeholder="RFC" maxlength="13">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Régimen fiscal:</strong>
                            <select class="form-control" name="regimen_fiscal" id="exampleFormControlSelect1">
                                <option value="" @if($distribuidores->regimen_fiscal === '') selected @endif>Seleccione un régimen fiscal</option>
                                <option value="Persona física" @if($distribuidores->regimen_fiscal === 'Persona física') selected @endif>Persona física</option>
                                <option value="Persona moral" @if($distribuidores->regimen_fiscal === 'Persona moral') selected @endif>Persona moral</option>
                            </select>
                        </div>
                    </div>
                </div>


                {{-- <div class="row">
                    <h4 class="sub-title">Condiciones de crédito</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Descuento distribuidor/vendedor (%):</strong>
                            <input @if(Auth::user()->rol == '3' || Auth::user()->rol == '2') disabled @endif type="number" min="0" name="descuento" value="{{ $distribuidores->descuento }}"
                                class="form-control" placeholder="Descuento">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Días de credito:</strong>
                            <input @if(Auth::user()->rol == '3' || Auth::user()->rol == '2') disabled @endif type="text" name="dia_credito" value="{{ $distribuidores->dia_credito }}" class="form-control"
                                placeholder="Días de credito">
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de outlet (%):</strong>
                            <input type="number" minlength="0" maxlength="100" name="descuento_outlet" value="{{ $distribuidores->descuento_outlet }}" class="form-control"
                                placeholder="Descuento de outlet">
                        </div>
                    </div>                     --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Límite de crédito ($) :</strong>
                            <input @if(Auth::user()->rol == '3' || Auth::user()->rol == '2') disabled @endif type="text" name="credito" value="{{ $distribuidores->credito }}"
                                class="form-control miles" placeholder="Credito">
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de ofertas (%):</strong>
                            <input type="number" min="0" max="100" name="descuento_oferta" value="{{ $distribuidores->descuento_oferta }}" class="form-control"
                                placeholder="Descuento de ofertas">
                        </div>
                    </div> --}}

                {{-- </div> --}}

                <div class="row">
                    <h4 class="sub-title">Afiliaciones y Días de devolución</h4>
                    <hr class="espaciador">

                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Afiliaciones disponibles :</strong>
                            <input @if(Auth::user()->rol == '3') disabled @endif type="number" min="0" name="cuentas_restantes" value="{{ $distribuidores->cuentas_restantes }}" class="form-control" placeholder="Cuenta restantes">
                            {{-- <span style="color:red;">Número de afiliaciones creadas: {{ $distribuidores->cuentas_creadas }}</span> --}}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Afiliaciones creadas :</strong>
                            <input readonly type="number" min="0" name="cuentas_creadas" value="{{ $distribuidores->cuentas_creadas }}" class="form-control" placeholder="Cuenta creadas">
                            {{-- <span style="color:red;">Número de afiliaciones creadas: {{ $distribuidores->cuentas_creadas }}</span> --}}
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>Días de devolución :</strong>
                            <input @if(Auth::user()->rol == '3') disabled @endif type="number" min="0"  name="dias_devolucion" value="{{ $distribuidores->dias_devolucion }}" class="form-control" placeholder="Días de devolución">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h4 class="sub-title">Observaciones:</h4>
                        <div class="form-group">
                            <textarea name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ $distribuidores->observaciones }}</textarea>

                        </div>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activar pedidos</label>
                                <label class="switch alert-switch">
                                    <input type="checkbox" name="bloqueo_pedido"
                                        @if($distribuidores->bloqueo_pedido == 1) checked="true" @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <span><small>OFF / ON swich para bloqueos de pedidos del distribuidor</small></span>

                        </div>

                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activación de distribuidor</label>
                                <label class="switch">
                                    <input type="checkbox" name="estatus"
                                        @if($distribuidores->estatus == 1) checked="true" @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <span><small>OFF / ON swich para activacion o desactivacion de distribuidor</small></span>

                        </div>

                    </div>
                </div>


                <div class="row mt-5 seccion-final">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right save">Guardar</button>
                        <a class="btn btn-danger pull-right save" href="{{ route('distribuidores.index') }}">
                            Cancelar
                        </a>
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

                            // Autoseleccionar el localidad
                            if (localidadId !== "") {
                                $("select[name='localidad_id'] option[value='" + localidadId + "']")
                                    .attr("selected", "selected");
                            }

                        },
                    });
                } else {
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una localidad</option>');
                    $('#tag').empty();
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


            $('.alert-switch input[type="checkbox"]').on('change', function(event) {
                var checkbox = $(this);

                if (!checkbox.is(':checked')) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Desactivar pedidos',
                        text: '¿Estás seguro? Se desactivarán los pedidos del distribuidor.',
                        icon: 'info',
                        showCancelButton: true ,
                        confirmButtonColor: '#3fc3ee',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, desactivar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        } else {
                            // checkbox.prop('checked', false);
                            checkbox.prop('checked', true);
                        }
                    });
                } else {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Activar pedidos',
                        text: '¿Estás seguro? Se activarán los pedidos del distribuidor.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, activar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        } else {
                            // checkbox.prop('checked', true);
                            checkbox.prop('checked', false);
                        }
                    });
                }


            });



        });
    </script>
@stop


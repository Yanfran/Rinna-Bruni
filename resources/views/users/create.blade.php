@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Crear nuevo usuario
                {{-- @can('tiendas-list') --}}
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('users.index') }}"> Regresar</a>
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

            <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
                @csrf

                <div class="row">
                    <h4 class="sub-title">Datos generales de la cuenta</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombres* :</strong>
                            <input min="2" max="10" type="text" name="name" value="{{ old('name') }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nombres">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>1er apellido* :</strong>
                            <input min="2" type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                                class="form-control {{ $errors->has('apellido_paterno') ? 'is-invalid' : '' }}" placeholder="1er apellido">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>2do apellido :</strong>
                            <input min="2" type="text" name="apellido_materno" value="{{ old('apellido_materno') }}" 
                            class="form-control {{ $errors->has('apellido_materno') ? 'is-invalid' : '' }}" placeholder="2do apellido">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Usuario* :</strong>
                            <input min="2" onkeypress="return Letras(event)" type="text" name="usuario" value="{{ old('usuario') }}"
                                class="form-control {{ $errors->has('usuario') ? 'is-invalid' : '' }}"
                                placeholder="Usuario">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Correo electronico* :</strong>
                            <input min="3" type="text" name="email" value="{{ old('email') }}" 
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Correo electronico">
                        </div>
                    </div>
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Número de empleado* :</strong>
                            <input type="text" value="{{ old('numero_empleado') }}" name="numero_empleado"
                                class="form-control {{ $errors->has('numero_empleado') ? 'is-invalid' : '' }}"
                                placeholder="Número Empleado">
                        </div>
                    </div> --}}


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Clave de acceso* :</strong>
                            <input                             
                                type="password" value="{{ old('confirm-password') }}" name="password" id="password-1"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
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
                                class="form-control {{ $errors->has('confirm-password') ? 'is-invalid' : '' }}"
                                placeholder="Verificar clave de acceso"                         
                            >                            
                            <div class="input-group-append">
                                <button type="button" class="btn btn-show show-password-2" data-target="#password-2">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>                    

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tienda a la que pertenece* :</strong>
                            <select class="form-control {{ $errors->has('tienda_id') ? 'is-invalid' : '' }}" name="tienda_id">
                                <option selected value="">Seleccione una tienda</option>
                                @foreach ($tiendas as $tienda)
                                    <option value="{{ $tienda->id }}" {{ old('tienda_id') == $tienda->id ? 'selected' : '' }}>
                                        {{ $tienda->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de ingreso :</strong>
                            <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}"
                                class="form-control fecha_no_futuras" placeholder="Fecha de Ingreso">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de nacimiento :</strong>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                class="form-control fecha_no_futuras" placeholder="Fecha Nacimiento">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('roles') ? 'is-invalid-select4' : '' }}">
                            <strong>Tipo de usuario* :</strong>

                            <select name="roles[]" class="js-example-basic-multiple form-control"  multiple>
                                @foreach($roles as $value => $role)
                                    <option value="{{ $value }}" {{ in_array($value, old('roles', [])) ? 'selected' : '' }}>
                                        {{ $role }} 
                                        {{-- - {{ $value }} --}}
                                    </option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Dirección</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Pais:</strong>
                            <input type="hidden" name="pais_id" value="1" class="form-control" placeholder="Pais">
                            <input type="text" readonly="true" name="pais_nombre" value="México" class="form-control"
                                placeholder="Pais">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('estado_id') ? 'is-invalid-select1' : '' }}">
                            <strong>Estado* :</strong>
                            <select style="width: 100%;" 
                                class="js-example-basic-single form-control"
                                name="estado_id">
                                <option selected value="">Seleccione un estado</option>
                                @foreach ($estados as $estado)
                                    @php
                                        $selected = '';
                                        $estado_nombre = '';
                                        if (old('estado_id') == $estado->id) {
                                            $selected = 'selected';
                                            $estado_nombre = $estado->nombre;
                                        }
                                    @endphp
                                    <option value="{{ $estado->id }}" {{ $selected }}>{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="estado_id_hidden" value="{{ old('estado_id') }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('municipio_id') ? 'is-invalid-select2' : '' }}">
                            <strong>Municipio* :</strong><br>
                            <select style="width: 100%;"
                                class="js-example-basic-single form-control"
                                name="municipio_id" id="municipio_id">
                                <option selected value="">Seleccione un municipio</option>
                            </select>
                            <small id="tag" class="red"></small>
                            <input type="hidden" name="municipio_id_hidden" value="{{ old('municipio_id') }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('localidad_id') ? 'is-invalid-select3' : '' }}">
                            <strong>Colonia* :</strong><br>
                            <select style="width: 100%;"
                                class="js-example-basic-single form-control"
                                name="localidad_id" id="localidad_id">
                                <option selected value="">Seleccione una Colonia</option>
                            </select>
                            <small id="tag" class="red"></small>
                            <input type="hidden" name="localidad_id_hidden" value="{{ old('localidad_id') }}">
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
                                placeholder="Código postal">
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input min="4" type="text" name="calle_numero" value="{{ old('calle_numero') }}"
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
                                placeholder="Teléfono fijo">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Condiciones de crédito</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento empleado (%) :</strong>
                            <input type="number" name="descuento" value="{{ old('descuento') }}" class="form-control"
                                placeholder="Descuento">     
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Límite de crédito ($) :</strong>
                            <input type="text" name="credito" value="{{ old('credito') }}" class="form-control miles"
                                placeholder="Credito">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Observaciones:</h4>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <textarea min="10" name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ old('observaciones') }}</textarea>

                        </div>
                    </div>
                </div>                

                <div class="row mt-5 seccion-final">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right save">Guardar</button>
                        <a class="btn btn-danger pull-right save" href="{{ route('distribuidores.index') }}">
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
            $('.js-example-basic-multiple').select2({
                placeholder: 'Selecciona opciones',
                allowClear: true
              });
            $('.js-example-basic-single').select2();


            setTimeout(() => {               

                var estadoId = $("input[name='estado_id_hidden']").val();                
                if (estadoId !== "") {
                    $("select[name='estado_id'] option[value='" + estadoId + "']").attr("selected",
                        "selected");
                }

                setTimeout(() => {
                    municipio();
                }, 500);

            }, 500);

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
                if (municipioId && municipioId != 'Seleccione una colonia') {
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

                            // Autoseleccionar el localidad
                            if (localidadId !== "") {
                                $("select[name='localidad_id'] option[value='" + localidadId + "']")
                                    .attr("selected", "selected");
                            }
                            

                            // setTimeout(() => {                                
                            //     busquedaLocalidad()                                
                            // }, 100);
                        },
                    });
                } else {
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una colonia</option>');
                    $('#tag').empty();
                }

            }

            function busquedaLocalidad() {
                var localidadId = $("input[name='localidad_id_hidden']").val();
                if (localidadId && localidadId != 'Seleccione una colonia') {
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
                $('#postal').val('');
                $('#ciudad').val('');
                var municipioId = $(this).val();
                if (municipioId && municipioId != 'Seleccione una colonia') {
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
                    $('#localidad_id').append('<option value="">Seleccione una colonia</option>');
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

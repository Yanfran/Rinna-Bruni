@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar usuario
                {{-- @can('tiendas-list') --}}
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('users.index') }}"> Regresar</a>
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
                                @default
                                    <li>{{ $error }}</li>
                            @endswitch
                        @endforeach                
                    </ul>
                </div>
            @endif
            <form action="{{ route('users.update', $user->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <h4 class="sub-title-principal">Datos generales de la cuenta</h4>
                    </div>
                    <div class="col-md-6">
                        <h4 class="sub-title pull-right">ID del usuario ( {{ $user->id }} )</h4>
                    </div>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombres* :</strong>
                            <input type="text" name="name" value="{{ old('name') ? old('name') : $user->name }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="Nombre y apellido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>1er apellido* :</strong>
                            <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') ? old('apellido_paterno') : $user->apellido_paterno }}"
                                class="form-control {{ $errors->has('apellido_paterno') ? 'is-invalid' : '' }}"
                                placeholder="1er apellido">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>2do apellido :</strong>
                            <input type="text" name="apellido_materno" value="{{ old('apellido_materno') ? old('apellido_materno') : $user->apellido_materno }}"
                                class="form-control {{ $errors->has('apellido_materno') ? 'is-invalid' : '' }}"
                                placeholder="2do apellido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Usuario* :</strong>
                            <input type="text" onkeypress="return Letras(event)" name="usuario" value="{{ old('usuario') ? old('usuario') : $user->usuario }}"
                                class="form-control {{ $errors->has('usuario') ? 'is-invalid' : '' }}"
                                placeholder="Usuario">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Correo electronico* :</strong>
                            <input type="email" name="email" value="{{ old('email') ? old('email') : $user->email }}" readonly
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Número de empleado :</strong>
                            <input type="text" value="{{ old('numero_afiliacion') ? old('numero_afiliacion') : $user->numero_afiliacion }}" 
                                name="numero_afiliacion"
                                class="form-control" placeholder="Número de empleado" readonly>
                        </div>
                    </div>
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Número de empleado* :</strong>
                            <input type="text" value="{{ old('numero_empleado') ? old('numero_empleado') : $user->numero_empleado }}"
                                name="numero_empleado"
                                class="form-control {{ $errors->has('numero_empleado') ? 'is-invalid' : '' }}"
                                placeholder="Número Empleado" readonly>
                        </div>
                    </div> --}}

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
                        </div>
                    </div>
                    

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tienda a la que pertenece* :</strong>
                            <select class="form-control {{ $errors->has('tienda_id') ? 'is-invalid' : '' }}"
                                name="tienda_id">
                                <option>Seleccione una tienda</option>
                                @foreach ($tiendas as $tienda)
                                    <option value="{{ old('tienda_id') ? old('tienda_id') : $tienda->id }}"
                                        {{ old('tienda_id') == $tienda->id ? 'selected' : ($user->Tienda->id == $tienda->id ? 'selected' : '') }}>
                                        {{ $tienda->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de ingreso :</strong>
                            <input type="date" name="fecha_ingreso"
                            value="{{ old('fecha_ingreso') ? old('fecha_ingreso') : ($user->fecha_ingreso ? date('Y-m-d', strtotime($user->fecha_ingreso)) : '') }}"
                                class="form-control fecha_no_futuras" placeholder="Fecha de Ingreso">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Fecha de nacimiento :</strong>
                            <input type="date" name="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento') ? old('fecha_nacimiento') : ($user->fecha_nacimiento ? date('Y-m-d', strtotime($user->fecha_nacimiento)) : '') }}"
                                class="form-control fecha_no_futuras" placeholder="Fecha Nacimiento">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('roles') ? 'is-invalid-select4' : '' }}">
                            <strong>Tipo de usuario* :</strong>
                            <select name="roles[]" class="js-example-basic-multiple form-control" multiple>
                                @foreach ($roles as $value => $role)
                                    <option
                                        @foreach ($user->roles as $key => $rol)
                                            @if ($rol->id == $value) selected 
                                            @endif 
                                        @endforeach
                                        value="{{ $value }}">{{ $role }}
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
                        <div class="form-group  {{ $errors->has('estado_id') ? 'is-invalid-select1' : '' }}">
                            <strong>Estado* :</strong>
                            <select style="width: 100%;" class="js-example-basic-single form-control"
                                name="estado_id" id="estado_id">
                                <option selected value="">Seleccione un estado</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ old('estado_id') ? old('estado_id') : $estado->id }}"
                                        {{ $direcciones && $direcciones->estado_id == $estado->id ? 'selected' : '' }}>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($direcciones)
                                <input type="hidden" name="estado_id_hidden" value="{{ old('estado_id') ? old('estado_id') : $direcciones->estado_id }}">
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
                                    value="{{ old('municipio_id') ? old('municipio_id') : $direcciones->municipio_id }}">
                            @endif
                            <small id="tag" class="red"></small>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group {{ $errors->has('localidad_id') ? 'is-invalid-select3' : '' }}">
                            <strong>Colonia* :</strong><br>
                            <select style="width: 100%;"
                                class="js-example-basic-single form-control"
                                name="localidad_id" id="localidad_id">
                                <option value="{{ old('localidad_id') }}">Seleccione una Colonia</option>
                            </select>
                            @if ($direcciones)
                                <input type="hidden" name="localidad_id_hidden"
                                    value="{{ old('localidad_id') ? old('localidad_id') : $direcciones->localidad_id }}">
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
                                class="form-control 
                                {{ $errors->has('codigo_postal') ? 'is-invalid' : '' }}"
                                value="{{ old('codigo_postal') ? old('codigo_postal') : ($direcciones ? $direcciones->cp : '') }}"
                                placeholder="Código postal">
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input type="text" name="calle_numero" id="calle_numero"                                
                                class="form-control {{ $errors->has('calle_numero') ? 'is-invalid' : '' }}"                                
                                value="{{ old('calle_numero') ? old('calle_numero') : ($direcciones ? $direcciones->calle : '') }}"
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
                            <input data-mask="99-9999-9999" minlength="10" maxlength="10" type="text" name="celular"
                                value="{{ old('celular') ? old('celular') : $user->celular }}"
                                class="form-control telefono {{ $errors->has('celular') ? 'is-invalid' : '' }}"
                                placeholder="Movil">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Teléfono* :</strong>
                            <input data-mask="99-9999-9999" minlength="10" maxlength="10" type="text" name="telefono_fijo"
                                value="{{ old('telefono_fijo') ? old('telefono_fijo') : $user->telefono_fijo }}"
                                class="form-control telefono_dijo {{ $errors->has('telefono_fijo') ? 'is-invalid' : '' }}"
                                placeholder="Teléfono">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Condiciones de crédito</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento empleado (%) :</strong>
                            <input type="number" name="descuento" value="{{ old('descuento') ? old('descuento') : $user->descuento }}"
                                class="form-control" placeholder="Descuento">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Límite de crédito ($) :</strong>
                            <input type="text" name="credito" value="{{ old('credito') ? old('credito') : $user->credito }}"
                                class="form-control miles" placeholder="Credito">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Observaciones:</h4>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <textarea name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ old('observaciones') ? old('observaciones') : $user->observaciones }}</textarea>

                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <input type="hidden" class="estatus" name="estatus" value="{{$user->estatus}}">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activación de usuario de sistema</label>
                                <label class="switch">
                                    <input 
                                        type="checkbox" 
                                        name="estatus_check"
                                        @if($user->estatus == 1) 
                                            checked="true" 
                                            @if($user->rol == 2 || $user->rol == 1 || $user->rol == 0)
                                                disabled="disabled" 
                                            @endif
                                        @endif>
                                    <span class="@if($user->rol == 2 || $user->rol == 1 || $user->rol == 0) read-switch @endif slider round"></span>
                                </label>
                            </div>
                            <span><small>OFF / ON swich para activacion o desactivacion de usuario de sistema</small></span>

                        </div>

                    </div>
                </div>                


                <div class="row mt-5 seccion-final">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right save">Guardar</button>
                        <a class="btn btn-danger pull-right save" href="{{ route('users.index') }}">
                            Cancelar</a>
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

            $('.js-example-basic-multiple').select2({
                placeholder: 'Selecciona opciones',
                allowClear: true
            });

            $('.js-example-basic-single').select2();


            $('select[name="tienda_id"]').on('change', function() {                                    
                
                // var SelectEstado = $('select[name="tienda_id"]').val();
                // var estadoSeleccionado = $('#estado_id');

                // if (SelectEstado == '') {
                //     $('#estado_id').empty();
                //     $('#estado_id').append('<option value="">Seleccione un estado</option>');
                //     $("#estado_id").trigger("change");

                //     $.ajax({
                //         url: '/ajax/obtenerEstados', 
                //         type: 'GET',
                //         dataType: 'json',
                //         success: function(data) {
                            
                //             estadoSeleccionado.empty(); 
                //             estadoSeleccionado.append('<option value="">Seleccione un estado</option>');
                                                            
                //             var estadosArray = Object.entries(data);
                            
                //             estadosArray.forEach(function(estado) {
                //                 var estadoId = estado[0];
                //                 var estadoNombre = estado[1];
                //                 estadoSeleccionado.append('<option value="' + estadoId + '">' + estadoNombre + '</option>');
                //             });
                //         },                          
                //     });

                // }



                var tienda = $(this).val();                
                // var postalInput = $('#postal');
                // var ciudadlInput = $('#ciudad');
                // var callelInput = $('#calle_numero');                

                direccionesTiendas(tienda);

                // if (tienda && tienda != 'Seleccione un tienda') {
                    
                //     $.ajax({
                //         url: '/ajax/distribuidoresAll/' + tienda,
                //         type: "GET",
                //         dataType: "json",
                //         success: function(data) {

                //             $('#distribuidor_id').empty();

                //             $('#distribuidor_id').append(
                //                 '<option selected value="">Seleccione un distribuidor</option>'
                //             );

                //             data.forEach(function(element) {
                //                 $('#distribuidor_id').append('<option value="' + element
                //                     .id +
                //                     '">' + element.name + '</option>');
                //             });                                
                        
                //         },
                //     });
                // } else {                    

                //     $('#municipio_id').empty();
                //     $('#localidad_id').empty();
                //     $('#distribuidor_id').empty();
                //     $('#postal').val('');
                //     $('#ciudad').val('');
                //     $('#calle_numero').val('');

                //     $('#estado_id').prop("disabled", false);
                //     $('#municipio_id').prop("disabled", false);
                //     $('#localidad_id').prop("disabled", false);

                //     postalInput.attr('readonly', false);
                //     ciudadlInput.attr('readonly', false);
                //     callelInput.attr('readonly', false); 

                //     $('#selectDomicilio').hide();                        

                //     $('#distribuidor_id').append(
                //         '<option value="">Seleccione un distribuidor</option>');

                //     $('#municipio_id').append(
                //         '<option value="">Seleccione un municipio</option>');

                //     $('#localidad_id').append(
                //         '<option value="">Seleccione una colonia</option>');
                // }

            })


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
                        var { estado_id, municipio_id, localidad_id, calle_numero } = data;                        
                        setTimeout(() => {
                            estadoT(estado_id);
                            setTimeout(() => {
                                municipioT(municipio_id);
                                setTimeout(() => {
                                    localidadT(localidad_id);
                                }, 200);
                            }, 200);
                        }, 200);    

                        $('#calle_numero').val(calle_numero);
                        
                        // $('#estado_id').prop("disabled", true);
                        // $('#municipio_id').prop("disabled", true);
                        // $('#localidad_id').prop("disabled", true);

                        // postalInput.attr('readonly', true);
                        // ciudadlInput.attr('readonly', true);
                        // callelInput.attr('readonly', true);                                
                        

                        // $('#estado_id_hidden').attr('name', 'estado_id');
                        // $('#estado_id').removeAttr('name');
                        // $('#municipio_id_hidden').attr('name', 'municipio_id');
                        // $('#municipio_id').removeAttr('name');
                        // $('#localidad_id_hidden').attr('name', 'localidad_id');
                        // $('#localidad_id').removeAttr('name');



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

                            // $("#municipio_id_hidden").val(municipioId);
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
                    $('#localidad_id').empty();
                    $('#localidad_id').append('<option value="">Seleccione una colonia</option>');
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

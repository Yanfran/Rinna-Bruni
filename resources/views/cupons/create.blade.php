@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Agregar nuevo cupon
                @can('cupons-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('cupons.index') }}"> Regresar</a>
                    </div>
                @endcan
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

            <form action="{{ route('cupons.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="tipo">Tipo de cupón:</label>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value="">Seleccione un tipo de cupón</option>
                                <option value="1" {{ old('tipo') == 1 ? 'selected' : '' }}>Cupón de dinero</option>
                                <option value="2" {{ old('tipo') == 2 ? 'selected' : '' }}>Cupón de porcentaje aplicable</option>
                                <option value="3" {{ old('tipo') == 3 ? 'selected' : '' }}>Vale a favor</option>
                            </select>                           
                        </div>
                        <input type="hidden" id="tipo_hidden" value="{{ old('tipo') }}">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="cantidad_usos">Cantidad de usos:</label>
                            <input required type="number" class="form-control" name="cantidad_usos" value="{{ old('cantidad_usos') }}" id="cantidadUsosInput">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="porcentaje">Porcentaje aplicable:</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control"
                                name="porcentaje" value="{{ old('porcentaje') }}" id="porcentaje" disabled>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="monto">Monto:</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="monto"
                                id="monto" value="{{ old('monto') }}" readonly>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <label for="fecha_desde">Fecha desde:</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_desde" value="{{ old('fecha_inicio') }}"
                                min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <label for="fecha_hasta">Fecha hasta:</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_hasta" value="{{ old('fecha_fin') }}"
                                min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            
                            <label>{{ trans('empresas.label_estatus') }}</label>
                            <select name="estatus" class="form-control" id="estatus">                                
                                <option selected class="form-control" value="1">
                                    {{-- {{ old('estatus') == 1 ? 'selected' : '' }} --}}
                                    {{ trans('empresas.select_activo') }}
                                </option>
                                <option class="form-control" value="0">
                                    {{-- {{ old('estatus') == 0 ? 'selected' : '' }} --}}
                                    {{ trans('empresas.select_inactivo') }}
                                </option>                                

                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="aplicar_usuario" id="aplicar_usuario"> Aplicar a un usuario
                                específico
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12" id="usuario_select_wrapper" style="display: none;">
                        <div class="form-group">
                            <label for="usuario_id">Usuario:</label>
                            <select class="form-control" name="user_id" id="usuario_id">
                                <option value="">Seleccione un usuario</option>
                            </select>
                            <input type="hidden" id="select_user" value="{{ old('user_id') }}">
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
        // Obtener referencias a los elementos del formulario
        const tipoCuponSelect = document.getElementById('tipo');
        const tipoCuponSelectHidden = document.getElementById('tipo_hidden');        
        const porcentajeInput = document.getElementById('porcentaje');
        const montoInput = document.getElementById('monto');
        const aplicarUsuarioCheckbox = document.getElementById('aplicar_usuario');
        const usuarioSelectWrapper = document.getElementById('usuario_select_wrapper');
        const usuarioSelect = document.getElementById('usuario_id');
        const fechaDesdeInput = document.getElementById('fecha_desde');
        const fechaHastaInput = document.getElementById('fecha_hasta');
        const selectUser = document.getElementById('select_user');        


        const tipoSeleccionadoHidden = tipoCuponSelectHidden.value;          
        var monto = $('#monto');
        var porcentaje = $('#porcentaje');        
        if (tipoSeleccionadoHidden == 1) {
            monto.attr('readonly', false);
        } else if(tipoSeleccionadoHidden == 2){            
            porcentaje.attr('readonly', false);
        } else if(tipoSeleccionadoHidden == 3){            
            monto.attr('readonly', false);
        }


        if (selectUser.value) {            
            cargarUsuarios();
            var checkbox = document.getElementById("aplicar_usuario");
            checkbox.checked = true; // Marcar el checkbox
            usuarioSelectWrapper.style.display = 'block';

        }


        // Evento para manejar el cambio en el tipo de cupón
        tipoCuponSelect.addEventListener('change', function() {            
            const tipoSeleccionado = tipoCuponSelect.value;            

            // Reiniciar los valores de los campos al seleccionar "Seleccione un tipo de cupón"
            if (tipoSeleccionado === '') {
                porcentajeInput.value = '';
                montoInput.value = '';
                cantidadUsosInput.value = '';
                cantidadUsosInput.readOnly = false;
            }
            

            // Habilitar/deshabilitar el campo de porcentaje y cantidad de usos según el tipo seleccionado
            if (tipoSeleccionado === '2') {
                porcentajeInput.disabled = false;
                porcentajeInput.value = '';
                montoInput.readOnly = true;
                cantidadUsosInput.value = '';
                cantidadUsosInput.readOnly = false;
            } else if (tipoSeleccionado === '3') {
                porcentajeInput.disabled = true;
                porcentajeInput.value = '';
                montoInput.readOnly = false;
                cantidadUsosInput.value = '1';
                cantidadUsosInput.readOnly = true;
            } else {
                porcentajeInput.disabled = true;
                porcentajeInput.value = '';
                montoInput.readOnly = false;
                cantidadUsosInput.value = '';
                cantidadUsosInput.readOnly = false;
            }
        });


        // Evento para manejar el cambio en el checkbox de aplicar a un usuario específico
        aplicarUsuarioCheckbox.addEventListener('change', function() {
            if (aplicarUsuarioCheckbox.checked) {
                cargarUsuarios();
                usuarioSelectWrapper.style.display = 'block';

            } else {
                usuarioSelectWrapper.style.display = 'none';
            }
        });

        // Validación de fechas
        fechaDesdeInput.addEventListener('change', function() {
            const fechaDesde = new Date(fechaDesdeInput.value);
            const fechaHasta = new Date(fechaHastaInput.value);

            if (fechaDesde > fechaHasta) {
                fechaHastaInput.value = fechaDesdeInput.value;
            }
        });

        fechaHastaInput.addEventListener('change', function() {
            const fechaDesde = new Date(fechaDesdeInput.value);
            const fechaHasta = new Date(fechaHastaInput.value);

            if (fechaHasta < fechaDesde) {
                fechaDesdeInput.value = fechaHastaInput.value;
            }
        });



        // Cargar opciones del select usando AJAX
        function cargarUsuarios() {
            const usuarioSelect = document.getElementById('usuario_id');
            fetch('/usuarios/all', {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    // Limpiar las opciones existentes del select
                    usuarioSelect.innerHTML = '';

                    // Agregar la opción por defecto
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Seleccione un usuario';
                    usuarioSelect.appendChild(defaultOption);

                   

                    // Agregar las opciones de usuarios obtenidas
                    data.forEach(users => {
                        const option = document.createElement('option');                        
                        option.value = users.id;
                        option.textContent = users.numero_afiliacion + ': ' + users.name + '  ' + users
                            .apellido_paterno + ' ' + users.apellido_materno;
                        usuarioSelect.appendChild(option);

                        // Si el valor coincide con selectUser.value, selecciona la opción.
                        if (users.id == selectUser.value) {
                            option.selected = true;
                        }
                    });

                    // Inicializar el select2
                    $(usuarioSelect).select2();
                })
                .catch(error => {
                    console.error('Error al cargar los usuarios:', error);
                });
        }
    </script>
@stop

@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar cupon
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
            <form action="{{ route('cupons.update', $cupon->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre"
                                value="{{ $cupon->nombre }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="tipo">Tipo de cupón:</label>
                            <select class="form-control" name="tipo" id="tipo">
                                <option @if ($cupon->tipo == '') selected @endif value="">Seleccione un tipo
                                    de cupón</option>
                                <option @if ($cupon->tipo == 1) selected @endif value="1">Cupón de dinero
                                </option>
                                <option @if ($cupon->tipo == 2) selected @endif value="2">Cupón de porcentaje
                                    aplicable</option>
                                <option @if ($cupon->tipo == 3) selected @endif value="3">Vale a favor
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="cantidad_usos">Cantidad de usos:</label>
                            <input  required type="number" value="{{ $cupon->cantidad_usos }}" class="form-control" name="cantidad_usos" id="cantidadUsosInput">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="porcentaje">Porcentaje aplicable:</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control"
                                name="porcentaje" id="porcentaje" @if ($cupon->tipo != 2) disabled @endif
                                value="{{ $cupon->tipo == 2 ? $cupon->porcentaje : '' }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="monto">Monto:</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="monto"
                                id="monto" value="{{ $cupon->tipo == 1 || $cupon->tipo == 3 ? $cupon->monto : 0 }}"
                                readonly>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <label for="fecha_desde">Fecha desde:</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_desde"
                                value="{{ $cupon->fecha_inicio ? date('Y-m-d', strtotime($cupon->fecha_inicio)) : '' }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <label for="fecha_hasta">Fecha hasta:</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_hasta"
                                value="{{ $cupon->fecha_fin ? date('Y-m-d', strtotime($cupon->fecha_fin)) : '' }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>{{ trans('empresas.label_estatus') }}</label>
                            <select name="estatus" class="form-control" id="estatus">
                                <option class="form-control" value="0"
                                    @if ($cupon->estatus == 0) selected @endif>
                                    {{ trans('empresas.select_inactivo') }}</option>
                                <option class="form-control" value="1"
                                    @if ($cupon->estatus == 1) selected @endif>{{ trans('empresas.select_activo') }}
                                </option>
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
                            <select class="form-control" name="user_id" id="usuario_id">
                                @if ($cupon->user_id != null)
                                    <option value="{{ $cupon->usuario->id }}">{{ $cupon->usuario->numero_afiliacion }}:
                                        {{ $cupon->usuario->name }} {{ $cupon->usuario->apellido_paterno }}
                                        {{ $cupon->usuario->apellido_materno }}</option>
                                @endif

                            </select>


                            <input type="hidden" name="" id="usuario_id_bd" value="{{ $cupon->user_id }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
@section('js')
    <script>
        window.addEventListener('DOMContentLoaded', cargarUsuarios);
        // Obtener referencias a los elementos del formulario
        const tipoCuponSelect = document.getElementById('tipo');
        const porcentajeInput = document.getElementById('porcentaje');
        const montoInput = document.getElementById('monto');
        const aplicarUsuarioCheckbox = document.getElementById('aplicar_usuario');
        const usuarioSelectWrapper = document.getElementById('usuario_select_wrapper');
        const usuarioSelect = document.getElementById('usuario_id');
        const fechaDesdeInput = document.getElementById('fecha_desde');
        const fechaHastaInput = document.getElementById('fecha_hasta');              
        var usuarioIdBd = $('#usuario_id_bd').val();

        // Evento para manejar el cambio en el tipo de cupón
        tipoCuponSelect.addEventListener('change', function() {
            const tipoSeleccionado = tipoCuponSelect.value;

            // Reiniciar los valores de los campos al seleccionar "Seleccione un tipo de cupón"
            if (tipoSeleccionado === '') {
                porcentajeInput.value = '';
                montoInput.value = '';
            }

            // Habilitar/deshabilitar el campo de porcentaje según el tipo seleccionado
            if (tipoSeleccionado === '2') {
                porcentajeInput.disabled = false;
                montoInput.value = '0';
                montoInput.readOnly = true;
            } else {
                porcentajeInput.disabled = true;
                porcentajeInput.value = '';
                montoInput.readOnly = false;
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

                    @if($cupon->user_id == null)
                    console.log({{ $cupon->user_id }});
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Seleccione un usuario';
                        usuarioSelect.appendChild(defaultOption);
                    @endif

                    // Agregar las opciones de usuarios obtenidas
                    data.forEach(users => {                        
                        const option = document.createElement('option');
                        option.value = users.id;
                        option.textContent = users.numero_afiliacion + ': ' + users.name + '  ' + users
                            .apellido_paterno + ' ' + users.apellido_materno;
                        usuarioSelect.appendChild(option);                        

                        if (users.id == usuarioIdBd) {
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

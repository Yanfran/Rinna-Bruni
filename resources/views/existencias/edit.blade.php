@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar existencia
                @can('existencias-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('existencias.index') }}"> Regresar</a>
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
                            @switch($error)
                                @case('El campo product id es obligatorio.')
                                    <li>El campo producto es obligatorio.</li>
                                @break

                                @case('El campo tienda_id.0 es obligatorio.')
                                    <li>El campo tienda es obligatorio.</li>
                                @break

                                @case('El campo cantidad.0 es obligatorio.')
                                    <li>El campo cantidad es obligatorio.</li>
                                @break

                                @default
                                    <li>{{ $error }}</li>
                            @endswitch
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $test = 0;
            @endphp

            <form action="{{ route('existencias.update_multiple') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                @foreach ($existencias as $existencia)
                    <input name="id[]" type="hidden" value="{{ $existencia->id }}">
                @endforeach

                    @foreach ($existencias as $key => $existencia)
                        <div class="row">
                            @if ($key == 0)
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Nombre de producto:</strong>
                                        <input name="product_id" class="form-control" type="hidden"
                                            value="{{ $existencia->product_id }}">
                                            <input readonly
                                            class="form-control"
                                            type="text"
                                            value="{{ $existencia->getProduct()->codigo }}{{" - " . $existencia->getProduct()->estilo}}@if($existencia->getProduct()->linea){{" - " . $existencia->getProduct()->linea->nombre}}@endif{{" - " . $existencia->getProduct()->nombre_corto}}">
                                     </div>
                                </div>
                            @endif


                            <div class="col-xs-12 col-sm-5 col-md-5">
                                <div class="form-group">
                                    <strong>Tienda* :</strong>
                                    <select
                                        class="form-control js-example-basic-single {{ $errors->has('tienda_id') ? 'is-invalid' : '' }}"
                                        name="tienda_id[]">
                                        <option selected value="">Seleccione una tienda</option>
                                        @foreach ($tiendas as $tienda)
                                            <option value="{{ $tienda->id }}"
                                                @if ($tienda->id == $existencia->tienda_id) selected @endif
                                                {{ old('tienda_id') == $tienda->id ? 'selected' : '' }}>{{ $tienda->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-5 col-md-5">
                                <div class="form-group">
                                    <strong>Cantidad disponible* :</strong>
                                    <input value="{{ $existencia->cantidad }}" type="number" min="0" name="cantidad[]"
                                        class="form-control">
                                </div>
                            </div>



                            @if ($test == 0)
                                <div class="col-lg-2 col-xs-12 col-sm-2 col-md-2 mt-4" id="add-container-1">
                                    <span class="btn btn-success delete-existencias-2"
                                        onclick="add(1, {{ $tiendas }});">Agregar</span>
                                </div>
                            @endif
                            @php
                                $test = 1;
                            @endphp

                            @if ($key > 0)
                            <div class="col-lg-2 col-xs-12 col-sm-2 col-md-2">
                                <button class="btn btn-danger delete-existencias-2 mt-4"
                                    onclick="eliminarExistencia(event, {{ $existencia->id }});">
                                    Eliminar
                                </button>
                            </div>
                            @endif


                        </div>

                    @endforeach

                    <div id="container-element-1" class="row"></div>

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
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

        });

        function add(input, tiendas) {
            const rndInt = randomIntFromInterval(10000, 6666666666666);
            var html = '';
            html += '<div id="' + rndInt + '">';

            html += '<input type="hidden" name="agregar" value="1">';
            html += '<div class="col-xs-12 col-sm-5 col-md-5">';
            html += '<div class="form-group">';
            html += '<strong>Tienda* :</strong>';
            html += '<select class="form-control js-example-basic-single tienda_select" name="tienda_id_two[]">';
            html += '<option selected value="">Seleccione una tienda</option>';
            for (var i = 0; i < tiendas.length; i++) {
                html += '<option value="' + tiendas[i].id + '">' + tiendas[i].nombre + '</option>';
            }
            html += '</select>';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-xs-12 col-sm-5 col-md-5">';
            html += '<div class="form-group">';
            html += '<strong>Cantidad disponible* :</strong>';
            html += '<input type="number" min="0" name="cantidad_two[]" class="form-control" placeholder="0">';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-lg-2 col-xs-12 col-sm-2 col-md-2 mt-4" id="var-b-' + rndInt + '">';
            html += '<label class="title" style="color:transparent;">label</label>';
            html += '<span onclick="remove(' + rndInt + ');"  class="btn btn-danger delete-existencias-3">Eliminar</span>';
            html += '</div>';
            html += '</div>';

            $('#container-element-' + input).append(html);
        }

        function randomIntFromInterval(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        }

        function remove(elementId) {
            var element = document.getElementById(elementId);
            if (element) {
                element.remove();
            }
        }

        function eliminarExistencia(event, existenciaId) {
        event.preventDefault();

            if (confirm('¿Estás seguro de que deseas eliminar esta existencia?')) {
                $.ajax({
                    url: '/existencias/' + existenciaId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // alert('Existencia eliminada exitosamente.');
                        // Opcional: Recarga la página después de la eliminación
                        location.reload();
                    },
                    error: function(error) {
                        alert('Error al eliminar la existencia.');
                    }
                });
            }
        }
    </script>

@stop

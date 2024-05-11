@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Agregar existencia
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
                                @case('El campo cantidad.1 es obligatorio.')
                                    <li>El campo cantidad es obligatorio.</li>
                                    @break
                                @default
                                    <li>{{ $error }}</li>
                            @endswitch
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('existencias.store') }}" method="POST">
                @csrf

                <div id="container-1" class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nombre de producto:</strong>
                            <select
                                class="js-example-basic-single form-control {{ $errors->has('product_id') ? 'is-invalid' : '' }}"
                                name="product_id">
                                <option selected value="">Seleccione un producto</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->codigo }} @if($product->linea)  {{" - " . $product->linea->nombre }} @endif - {{ $product->color }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="form-group">
                            <strong>Tienda* :</strong>
                            <select
                                class="form-control js-example-basic-single tienda_select {{ $errors->has('tienda_id') ? 'is-invalid' : '' }}"
                                name="tienda_id[]" readonly>
                                <option selected value="">Seleccione una tienda</option>
                                @foreach ($tiendas as $tienda)
                                    <option value="{{ $tienda->id }}"
                                        {{ old('tienda_id') == $tienda->id ? 'selected' : '' }}>
                                        {{ $tienda->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="form-group">
                            <strong>Cantidad disponible* :</strong>
                            <input type="number" name="cantidad[]" class="form-control" placeholder="1" min="1" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-xs-12 col-sm-2 col-md-2 mt-4" id="add-container-1">
                        <span class="btn btn-success add-existencias" onclick="add(1, {{ $tiendas }});">Agregar</span>
                    </div>
                </div>
                <div id="container-element-1" class="row"></div>

                <br><br><br><hr>
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
            console.log('input ==> ' + input);
            const rndInt = randomIntFromInterval(10000, 6666666666666);
            var html = '';
            html += '<div id="' + rndInt + '">';

            html += '<div class="col-xs-12 col-md-5">';
            html += '<div class="form-group">';
            html += '<strong>Tienda* :</strong>';
            html += '<select class="form-control js-example-basic-single tienda_select" name="tienda_id[]">';
            html += '<option selected value="">Seleccione una tienda</option>';
            for (var i = 0; i < tiendas.length; i++) {
                html += '<option value="' + tiendas[i].id + '">' + tiendas[i].nombre + '</option>';
            }
            html += '</select>';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-xs-12 col-md-5">';
            html += '<div class="form-group">';
            html += '<strong>Cantidad disponible* :</strong>';
            html += '<input type="number" min="1" name="cantidad[]" class="form-control" placeholder="1" required>';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-lg-2 col-xs-12 col-md-2 mt-4" id="var-b-' + rndInt + '">';
            html += '<label class="title" style="color:transparent;">label</label>';
            html += '<span onclick="remove(' + rndInt + ');"  class="btn btn-danger delete-existencias">Eliminar</span>';
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
    </script>
@endsection

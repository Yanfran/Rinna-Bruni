@extends('layouts.app')

@section('contenido')

    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Nuevo producto
                {{-- @can('tiendas-list')         --}}
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('products.index') }}"> Regresar</a>
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


            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <input type="hidden" name="tipo" value="2">
                    <h4 class="sub-title">Especificaciones</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Codigo:</strong>
                            <input type="text" name="codigo" value="{{ old('codigo') }}"
                                class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" placeholder="Codigo">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Estilo:</strong>
                            <input type="text" name="estilo" value="{{ old('estilo') }}"
                                class="form-control {{ $errors->has('estilo') ? 'is-invalid' : '' }}" placeholder="Estilo">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="linea_id">Linea:</label>
                            <select class="form-control" id="linea_id" name="linea_id">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($lineas as $key => $linea)
                                    <option value="{{ $linea->id }}" @if (old('linea_id') == $linea->id ) @selected(true) @endif>{{ $linea->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="talla">Talla:</label>
                            <select class="form-control" id="talla" name="talla">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($tallas as $key => $talla)
                                    <option value="{{ $talla }}">{{ $talla }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Talla menor:</strong>
                            <input type="text" name="talla_menor" value="{{ old('talla_menor') }}"
                                class="form-control {{ $errors->has('talla-menor') ? 'is-invalid' : '' }}"
                                placeholder="Talla menor">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Talla mayor:</strong>
                            <input type="text" name="talla_mayor" value="{{ old('talla_mayor') }}"
                                class="form-control {{ $errors->has('linea-talla_mayor') ? 'is-invalid' : '' }}"
                                placeholder="Talla mayor">
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="marca_id">Marca:</label>
                            <select class="form-control" id="marca_id" name="marca_id">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($marcas as $key => $marca)
                                    <option value="{{ $marca->id }}" @if (old('marca_id') == $marca->id ) @selected(true) @endif>{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="color">Color:</label>
                            <select class="form-control" id="color" name="color">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($colors as $key => $color)
                                    <option value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div> --}}

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">

                        <div class="form-group">
                            <label for="concepto">Concepto:</label>
                            <select class="form-control" id="concepto" name="concepto">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($conceptos as $key => $concepto)
                                    <option value="{{ $concepto }}">{{ $concepto }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="composicion">Composicion:</label>
                            <select class="form-control" id="composicion" name="composicion">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($composiciones as $key => $composicion)
                                    <option value="{{ $composicion }}">{{ $composicion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombre Corto:</strong>
                            <input type="text" name="nombre_corto" value="{{ old('nombre_corto') }}"
                                class="form-control" placeholder="Nombre Corto">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <h4 class="sub-title">Datos del producto</h4>
                    <hr class="espaciador">

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Temporada:</strong>
                            <input type="text" name="temporada" value="{{ old('temporada') }}"
                                class="form-control {{ $errors->has('temporada') ? 'is-invalid' : '' }}"
                                placeholder="Temporada">
                        </div>
                    </div> --}}
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="temporada_id">Temporada:</label>
                            <select class="form-control" id="temporada_id" name="temporada_id">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($temporadas as $key => $temporada)
                                    <option value="{{ $temporada->id }}" @if (old('temporada_id') == $temporada->id ) @selected(true) @endif>{{ $temporada->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="descripcion_id">Descripcion:</label>
                            <select class="form-control" id="descripcion_id" name="descripcion_id">
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($descripciones as $key => $descripcion)
                                    <option value="{{ $descripcion->id }}" @if (old('descripcion_id') == $descripcion->id ) @selected(true) @endif>{{ $descripcion->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">

                    {{--<div class="col-xs-12 col-sm-12 col-md-6">
                         <div class="form-group">
                            <strong>Clasificación:</strong>
                            <input type="text" name="clasificacion" value="{{ old('clasificacion') }}"
                                class="form-control {{ $errors->has('clasificacion') ? 'is-invalid' : '' }}"
                                placeholder="Clasificación">
                        </div>

                    </div>--}}

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Costo bruto:</strong>
                            <input type="number" min="0" name="costo_bruto" value="{{ old('costo_bruto') }}"
                                class="form-control {{ $errors->has('costo-bruto') ? 'is-invalid' : '' }}"
                                placeholder="Costo bruto">
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Descuento 1 (%):</strong>
                            <input type="number" min="0" max="100" name="descuento_1" value="{{ old('descuento_1') }}"
                                class="form-control {{ $errors->has('descuento_1') ? 'is-invalid' : '' }}"
                                placeholder="Descuento">
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Descuento 2 (%):</strong>
                            <input type="number" min="0" max="100" name="descuento_2" value="{{ old('descuento_2') }}"
                                class="form-control {{ $errors->has('descuento_2') ? 'is-invalid' : '' }}"
                                placeholder="Descuento 2 (%)">
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Proveedor:</strong>
                            <input type="text" name="proveedor" value="{{ old('proveedor') }}"
                                class="form-control {{ $errors->has('proveedor') ? 'is-invalid' : '' }}"
                                placeholder="Proveedor">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Precio* :</strong>
                            <input
                                type="number"
                                min="0"
                                name="precio"
                                value="{{ old('precio') }}"
                                class="form-control {{ $errors->has('precio-bruto') ? 'is-invalid' : '' }}"
                                placeholder="Precio">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <h4 class="sub-title">Datos del producto</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Suela:</strong>
                            <input type="text" name="suela" value="{{ old('suela') }}"
                                class="form-control {{ $errors->has('suela') ? 'is-invalid' : '' }}" placeholder="Suela">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombre de suela:</strong>
                            <input type="text" name="nombre_suela" value="{{ old('nombre_suela') }}"
                                class="form-control {{ $errors->has('nombre_suela') ? 'is-invalid' : '' }}"
                                placeholder="Nombre de suela">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Forro:</strong>
                            <input type="text" name="forro" value="{{ old('forro') }}"
                                class="form-control {{ $errors->has('forro') ? 'is-invalid' : '' }}" placeholder="Forro">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Horma:</strong>
                            <input type="text" name="horma" value="{{ old('horma') }}"
                                class="form-control {{ $errors->has('horma') ? 'is-invalid' : '' }}" placeholder="Horma">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Plantilla:</strong>
                            <input type="text" name="planilla" value="{{ old('plantilla') }}"
                                class="form-control {{ $errors->has('plantilla') ? 'is-invalid' : '' }}"
                                placeholder="Plantilla">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tacón:</strong>
                            <input type="text" name="tacon" value="{{ old('tacon') }}"
                                class="form-control {{ $errors->has('tacon') ? 'is-invalid' : '' }}" placeholder="Tacón">
                        </div>
                    </div>

                </div>

                {{-- <div class="row">
                    <h4 class="sub-title">Costo Netos</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Incial ($):</strong>
                            <input type="number" min="0" name="inicial" value="{{ old('inicial') }}"
                                class="form-control {{ $errors->has('inicial') ? 'is-invalid' : '' }}" placeholder="Incial ($)">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Promedio ($):</strong>
                            <input type="number" min="0" name="promedio" value="{{ old('promedio') }}"
                                class="form-control {{ $errors->has('promedio') ? 'is-invalid' : '' }}"
                                placeholder="Promedio ($)">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Actual($):</strong>
                            <input type="number" min="0" name="actual" value="{{ old('actual') }}"
                                class="form-control {{ $errors->has('actual') ? 'is-invalid' : '' }}" placeholder="Actual($)">
                        </div>
                    </div>

                </div> --}}
                <br>
                <div class="row">
                    <h4 class="sub-title">Devoluciones</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Bloquedo de devolución de articulos</label>
                                <label class="switch">
                                    <input type="checkbox" name="bloqueo_devolucion">
                                    <span class="slider round"></span>
                                </label>

                            </div>
                            <span><small>OFF / ON swich para bloquear articulo en la devolucion si esta ON el articulo no tiene permitido devoluciones</small></span>

                        </div>

                    </div>
                </div>


                {{-- <div class="row">
                    <h4 class="sub-title">Precio</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Precio al público:</strong>
                            <input type="number" min="0" name="precio_publico" value="{{ old('precio_publico') }}"
                                class="form-control {{ $errors->has('precio_publico') ? 'is-invalid' : '' }}" placeholder="$">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Precio distribuidor:</strong>
                            <input type="number" min="0" name="precio_distribuidor" value="{{ old('precio_distribuidor') }}"
                                class="form-control {{ $errors->has('precio_distribuidor') ? 'is-invalid' : '' }}"
                                placeholder="$">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Precio vendedor:</strong>
                            <input type="number" min="0" name="precio_vendedor" value="{{ old('precio_vendedor') }}"
                                class="form-control {{ $errors->has('precio_vendedor') ? 'is-invalid' : '' }}" placeholder="$">
                        </div>
                    </div>

                </div> --}}
                <div class="row mt-5">
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Imagen para el Catalogo</label>
                            <input type="file"
                                   name="img_catalogo"
                                   id="img_catalogo"
                                   class="form-control validar-img"
                                   accept="image/jpeg, image/png"
                                   data-max-width="500"   {{-- px --}}
                                   data-max-height="700" {{-- px --}}
                                   data-max-size="200"    {{-- Kb --}}
                                   data-container-img="preview_imagen"
                                   data-container-error="invalidImage"
                            >
                            <span class="help-block">Seleccione un imagen de ancho: 500px y  largo: 700px</span>
                        </div>
                        <span style="color:red !important;" id="invalidImage" class="d-none text-danger p-3"></span>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <img id="preview_imagen" name="preview_imagen" style="max-width: 100px" src="">
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Imágenes para la App:</strong>
                            <input type="file"
                                   name="rutafalsa[]"
                                   multiple
                                   class="form-control"
                                   id="imagenesInput"
                                   accept="image/jpeg, image/png"
                            >
                            <span class="help-block">Utilice el check para destacar la imagen</span>
                        </div>
                        <span style="color:red !important;" id="invalidImageApp" class="d-none text-danger p-3"></span>
                    </div>
                    {{-- Respalda la lista de archivos del carrusel y es la que se envia por el request--}}
                    <input type="file"
                        name="ruta[]"
                        multiple
                        class="form-control"
                        id="archivos"
                        accept="image/jpeg, image/png"
                        style="display:none"
                    >

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group mt-4">
                            <button type="button" id="btnCargar" class="btn btn-primary">Cargar</button>
                        </div>
                    </div> --}}

                </div>

                <div class="row mt-5">
                        <div id="aqui" class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                            <div class="owl-carousel owl-theme">
                                {{-- <div class="owl-wrapper">
                                    <!-- Aquí se agregarán las imágenes dinámicamente -->
                                </div> --}}
                            </div>
                        </div>
                </div>
                <input type="hidden" name="imagen_destacada" id="imagen_destacada">


                {{-- <div class="row">
                    <h4 class="sub-title">Descuento Excepciones</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped nowrap" style="width: 100%">
                                <tr>
                                    <th>Cliente:</th>
                                    <td>Descuento Linea</td>
                                    <td>Descuento Oferta</td>
                                    <td>Descuento Outlet</td>
                                    <td class="text-center">No aplica</td>
                                </tr>
                                <tr>
                                    <th>Distribuidor</th>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td class="text-center">
                                        <div style="margin-right: 15px;">
                                            <label class="text-md-right" for="color_1">
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" name="aplica">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vendedor</th>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td class="text-center">
                                        <div style="margin-right: 15px;">
                                            <label class="text-md-right" for="color_1">
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" name="aplica">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Comprador</th>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td id=""><input class="form-control" type="number" id="" name="" placeholder="$"></td>
                                    <td class="text-center">
                                        <div style="margin-right: 15px;">
                                            <label class="text-md-right" for="color_1">
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" name="aplica">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>

                </div> --}}



                <div class="row mt-5">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1">
                                    <span class="requerido">* </span>
                                    Activación de producto
                                </label>
                                <label class="switch">
                                    <input type="checkbox" name="estatus" checked="true">
                                    <span class="slider round"></span>
                                </label>

                            </div>
                            <span>
                                <small>OFF / ON swich para activacion o desactivacion de producto por defecto se
                                        creara en activo
                                </small>
                            </span>


                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2"></div>
                        <div class="col-xs-12 col-sm-5 col-md-5">
                            <button type="submit" class="btn btn-primary pull-right save">Guardar</button>
                            <a class="btn btn-danger pull-right save" href="{{ route('products.index') }}">
                                Cancelar</a>
                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>
    <p class="text-center text-primary"><small>-</small></p>

    <!-- Modal -->
    <div class="modal modal-primario" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog middle">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="modalHidden()">&times;</button>
                    {{-- <h4 class="modal-title" id="myModalLabel">Image Modal</h4> --}}
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" class="img-fluid img-size" alt="Modal Image">
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

        $(document).ready(function() {


            $(".owl-carousel").owlCarousel({
                autoplay:false,
                loop: false,
                margin: 20,
                center: false,
                nav: false,
                dots: true,
                mouseDrag:false,
                touchDrag:false,
                pullDrag:false,
                freeDrag:false,
                responsiveClass: true,
                responsiveRefreshRate: true,
                responsive : {
                    0 : {
                        items: 1
                    },
                    768 : {
                        items: 3
                    },
                    960 : {
                        items: 6
                    },
                    1200 : {
                        items: 9
                    },
                    1920 : {
                        items: 12
                    }
                }
            });


        });

        var imagenModal = '';

        function modal(imagePath) {
            imagenModal = imagePath;
            $('#modalImage').attr('src', imagenModal);
            $('#basicModal').modal('show');
        }

        function modalHidden() {
            $('#basicModal').modal('hide');
        }

        let dt = new DataTransfer();

        // $('#btnCargar').click(function() {
        //     // Obtener la lista de imágenes seleccionadas
        //     let files = $('#imagenesInput')[0].files;

        //     // Agregar las imágenes al carrusel
        //     respaldarImagenes();
        //     agregarImagenesAlCarrusel(dt.files);
        // });

        $('#imagenesInput').change( function() {
            //Validar cantidad de imagenes en la Galería a maximo 4
            let countAllImgs = (dt.files.length + this.files.length);
            const dtVacio = new DataTransfer();
            //console.log(countAllImgs);
            if( countAllImgs > 4 ) {
                $('#invalidImageApp').html('Solo se permite un máximo de 4 imágenes por producto');
                $('#invalidImageApp').removeClass('d-none');
                this.files = dtVacio.files;

            } else {
                $('#invalidImageApp').html('');
                $('#invalidImageApp').addClass('d-none');

                // Obtener la lista de imágenes seleccionadas
                let files = $('#imagenesInput')[0].files;;

                if ( validarBytesArchivos(this, (100 * 1024),'invalidImageApp')) {
                    // Agregar las imágenes al carrusel
                    respaldarImagenes();
                    agregarImagenesAlCarrusel(dt.files);
                }
            }

        });

        function agregarImagenesAlCarrusel(files) {
            // Obtener la referencia del carrusel
            let carrusel = $('.owl-carousel');
            let length = $('.item').length;

            for (let i=0; i<length; i++) {
                carrusel.trigger('remove.owl.carousel', i).trigger('refresh.owl.carousel');
            }

            for (let i = 0; i < files.length; i++) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    let imgBase64 = e.target.result;
                    let imgTag = `
                    <div class="item">
                        <img src="${imgBase64}" onclick="modal('${imgBase64}')" class="img-thumbnail" data-img-id="${i}" />

                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm mt-2 delete-btn" data-index="${i}" onclick="removeImagenCarrusel(event,${i})">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="col-md-6 mt-3">
                            <input style="transform: scale(1.5);" type="checkbox" class="imagen-checkbox" data-id-image="${i}">
                        </div>
                    </div>
                    `;

                    // Agregar la imagen al carrusel
                    carrusel.trigger('add.owl.carousel', [$(imgTag)]);
                    carrusel.trigger('refresh.owl.carousel');

                    checkBoxesSelects();

                    // Adjuntar evento de eliminación solo para el botón de esta imagen
                    //adjuntarEventoEliminacion(carrusel.find('.delete-btn:last'));
                };

                reader.readAsDataURL(files[i]);
            }
        }

        // Obtén todos los checkboxes con la clase "imagen-checkbox"
        let checkboxes = document.querySelectorAll('.imagen-checkbox');

        function checkBoxesSelects(){
            checkboxes = document.querySelectorAll('.imagen-checkbox');
            // Agrega un event listener a cada checkbox
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', (event) => {
                    // Desmarca todos los otros checkboxes cuando uno está marcado
                    checkboxes.forEach((otherCheckbox) => {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                });
            });

            $('.imagen-checkbox').on('change', function() {
                let idImagen = $(this).data('id-image');
                $('#imagen_destacada').val(idImagen);
            });
        }

        // function adjuntarEventoEliminacion(button) {
        //     button.on('click', function(event) {
        //         event.preventDefault();
        //         let carrusel = $('.owl-carousel');
        //         let itemContainer = $(this).closest('.item');

        //         carrusel.trigger('remove.owl.carousel', itemContainer.index()).trigger('refresh.owl.carousel');

        //         removeFileFromFileList(itemContainer.index());


        //         return false;
        //     });
        // }

        function removeImagenCarrusel(e,index) {
            e.preventDefault();
            let carrusel = $('.owl-carousel');

            removeFileFromFileList(index);

            agregarImagenesAlCarrusel(dt.files)

            //carrusel.trigger('remove.owl.carousel', index).trigger('refresh.owl.carousel');


            return false;

        }

        function removeFileFromFileList(index) {
            let dtVacio = new DataTransfer();
            dt = dtVacio;

            const input = document.getElementById('archivos');
            const { files } = input;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (index !== i) {
                    dt.items.add(file);
                }
            }

            archivos.files = dt.files;
            //console.log(archivos.files);
        }

        function respaldarImagenes() {

            dtVacio = new DataTransfer();

            const input = document.getElementById('imagenesInput');
            const archivos = document.getElementById('archivos');
            const { files } = input;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                dt.items.add(file);
            }

            input.files = dtVacio.files;
            archivos.files = dt.files; // Assign the updates list
            //console.log(archivos.files);
        }



    </script>


    <script src="{{ asset('js/commons.js') }}?ver={!! time() !!}"></script>
@stop

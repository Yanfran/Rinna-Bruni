@extends('layouts.app')

@section('contenido')

<style>
    /* .owl-carousel {
    display: block;
    position: relative;
    }

    .owl-carousel .item {
    display: block;
    position: relative;
    } */
</style>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Editar producto
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('products.index') }}"> Regresar</a>
                </div>
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


            <form id="editarProducto" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="row">
                    <input type="hidden" name="tipo" value="2">
                    <h4 class="sub-title">Especificaciones</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Codigo:</strong>
                            <input type="text" name="codigo" value="{{ $product->codigo }}"
                                class="form-control" placeholder="Codigo" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Estilo:</strong>
                            <input type="text" name="estilo" value="{{ $product->estilo }}"
                                class="form-control" placeholder="Estilo" disabled>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        {{-- <div class="form-group">
                            <strong>Línea:</strong>
                            <input type="text" name="linea" value="{{ $product->linea }}"
                                class="form-control" placeholder="Línea">
                        </div> --}}
                        <div class="form-group">
                            <label for="linea_id">Linea:</label>
                            <select class="form-control" id="linea_id" name="linea_id" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($lineas as $key => $linea)
                                    <option value="{{ $linea->id }}" @if ($linea->id == $product->linea_id ) @selected(true) @endif>
                                        {{ $linea->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="talla">Talla:</label>
                            <select class="form-control" id="talla" name="talla" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($tallas as $key => $talla)
                                    <option value="{{ $talla }}" @if ($talla == $product->talla ) @selected(true) @endif>
                                        {{ $talla }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="marca_id">Marca:</label>
                            <select class="form-control" id="marca_id" name="marca_id" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($marcas as $key => $marca)
                                    <option value="{{ $marca->id }}" @if ($marca->id == $product->marca_id ) @selected(true) @endif>{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="color">Color:</label>
                            <select class="form-control" id="color" name="color" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($colors as $key => $color)
                                    <option value="{{ $color }}" @if ($color == $product->color ) @selected(true) @endif>{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="concepto">Concepto:</label>
                            <select class="form-control" id="concepto" name="concepto" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($conceptos as $key => $concepto)
                                    <option value="{{ $concepto }}" @if ($concepto == $product->concepto ) @selected(true) @endif>{{ $concepto }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="composicion">Composicion:</label>
                            <select class="form-control" id="composicion" name="composicion" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($composiciones as $key => $composicion)
                                    <option value="{{ $composicion }}" @if ($composicion == $product->composicion ) @selected(true) @endif>{{ $composicion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombre Corto:</strong>
                            <input type="text" name="nombre_corto" value="{{ $product->nombre_corto }}"
                                class="form-control" placeholder="Nombre Corto" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Datos del producto</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        {{-- <div class="form-group">
                            <strong>Temporada:</strong>
                            <input type="text" name="temporada" value="{{ $product->temporada }}"
                                class="form-control"
                                placeholder="Temporada">
                        </div> --}}
                        <div class="form-group">
                            <label for="temporada_id">Temporada:</label>
                            <select class="form-control" id="temporada_id" name="temporada_id" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($temporadas as $key => $temporada)
                                    <option value="{{ $temporada->id }}" @if ($temporada->id == $product->temporada_id ) @selected(true) @endif>
                                        {{ $temporada->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="descripcion_id">Descripcion:</label>
                            <select class="form-control" id="descripcion_id" name="descripcion_id" disabled>
                                <option value="" disabled selected hidden>Selecciona tu opción</option>
                                @foreach ($descripciones as $key => $descripcion)
                                    <option value="{{ $descripcion->id }}" @if ($descripcion->id == $product->descripcion_id ) @selected(true) @endif>
                                        {{ $descripcion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Costo bruto:</strong>
                            <input type="number" min="0" name="costo_bruto" value="{{ $product->costo_bruto }}"
                                class="form-control"
                                placeholder="Costo bruto" disabled>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Descuento 1 (%):</strong>
                            <input type="number" min="0" max="100" name="descuento_1" value="{{ $product->descuento_1 }}"
                                class="form-control"
                                placeholder="Descuento" disabled>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Descuento 2 (%):</strong>
                            <input type="number" min="0" max="100" name="descuento_2" value="{{ $product->descuento_2 }}"
                                class="form-control"
                                placeholder="Descuento 2 (%)" disabled>
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Proveedor:</strong>
                            <input type="text" name="proveedor" value="{{ $product->proveedor }}"
                                class="form-control"
                                placeholder="Proveedor" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                        <strong>Precio* :</strong>
                          <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="precio"
                            value="{{ $product->precio }}"
                            class="form-control {{ $errors->has('precio') ? 'is-invalid' : '' }}"
                            placeholder="Precio"
                            disabled>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <h4 class="sub-title">Datos del producto</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Suela:</strong>
                            <input type="text" name="suela" value="{{ $product->suela }}"
                                class="form-control" placeholder="Suela" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombre de suela:</strong>
                            <input type="text" name="nombre_suela" value="{{ $product->nombre_suela }}"
                                class="form-control"
                                placeholder="Nombre de suela" disabled>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Forro:</strong>
                            <input type="text" name="forro"  value="{{ $product->forro }}"
                                class="form-control" placeholder="Forro" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Horma:</strong>
                            <input type="text" name="horma"  value="{{ $product->horma }}"
                                class="form-control" placeholder="Horma" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Plantilla:</strong>
                            <input type="text" name="planilla"  value="{{ $product->planilla }}"
                                class="form-control"
                                placeholder="Plantilla" disabled>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tacón:</strong>
                            <input type="text" name="tacon"  value="{{ $product->tacon }}"
                                class="form-control" placeholder="Tacón" disabled>
                        </div>
                    </div>

                </div>

                {{-- <div class="row">
                    <h4 class="sub-title">Costo Netos</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Incial ($):</strong>
                            <input type="number" min="0"   name="inicial"  value="{{ $product->inicial }}"
                                class="form-control" placeholder="Incial ($)" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Promedio ($):</strong>
                            <input type="number" min="0"   name="promedio"  value="{{ $product->promedio }}"
                                class="form-control"
                                placeholder="Promedio ($)" disabled>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Actual($):</strong>
                            <input type="number" min="0"   name="actual"  value="{{ $product->actual }}"
                                class="form-control" placeholder="Actual($)" disabled>
                        </div>
                    </div>

                </div> --}}
                <br>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Bloquedo de devolución de articulos</label>
                                <label class="switch">
                                    <input type="checkbox" name="bloqueo_devolucion" @if($product->bloqueo_devolucion == 1) checked="true" @endif disabled>
                                    <span class="slider round"></span>
                                </label>

                            </div>
                            <span><small>OFF / ON swich para bloquear articulo en la devolucion si esta ON el articulo no tiene permitido devoluciones</small></span>

                        </div>

                    </div>
                </div>

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
                            <span class="help-block">Seleccione un imagen de ancho: 500px X largo: 700px</span>
                        </div>
                        <span style="color:red !important;" id="invalidImage" class="d-none text-danger p-3"></span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            @if ($product->url_imagen_catalogo)
                                <img style="max-width: 100px"
                                    id="preview_imagen"
                                    name="preview_imagen"
                                    src="{{ route('storage', ['typeFile' => 'products_imgs_catalogs', 'filename' => $product->url_imagen_catalogo]) }}"
                                >
                            @else
                                <img id="preview_imagen" name="preview_imagen" style="max-width: 100px" src="">
                            @endif
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
                    <input type="file"
                        name="ruta[]"
                        multiple
                        class="form-control"
                        id="archivos"
                        accept="image/jpeg, image/png"
                        style="display:none"
                    >


                    {{-- <div class="col-xs-12 col-sm-12 col-lg-2 col-md-2">
                        <div class="form-group mt-4">
                            <button type="button" id="btnCargar" class="btn btn-primary">Cargar</button>
                        </div>
                    </div> --}}

                </div>

                <div  class="row">
                    <table style="display:none" id="tablaImagenesExistentes">
                        <tbody>
                            @foreach($imagenes as $key => $imagen)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td><input type="text" name="imagenesExistentes[{{ $key }}][id]" value="{{$imagen->id}}"></td>
                                    <td><input type="text" name="imagenesExistentes[{{ $key }}][nombre]" value="{{$imagen->ruta}}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <input type="hidden" name="donde_imagen_destacada" id="donde_imagen_destacada">
                    <input type="hidden" name="imagen_destacada" id="imagen_destacada">
                </div>

                <div class="row mt-5">
                    <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                        <div class="owl-carousel owl-theme ml-4">

                            {{-- Aca se cargaran las imagenes existentes--}}

                        </div>
                    </div>

                </div>


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
                                    <input type="checkbox" name="estatus" @if($product->estatus == 1) checked="true" @endif disabled>
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
        // $('body').removeClass('modal-open');
        // $('.modal-backdrop').remove();

        $(document).ready(function(){

            // $(".owl-carousel").owlCarousel({
            //     autoplay:false,
            //     loop: false,
            //     margin: 20,
            //     center: false,
            //     nav: false,
            //     dots: true,
            //     mouseDrag:false,
            //     touchDrag:false,
            //     pullDrag:false,
            //     freeDrag:false,
            //     responsiveClass: true,
            //     responsiveRefreshRate: true,
            //     responsive : {
            //         0 : {
            //             items: 1
            //         },
            //         768 : {
            //             items: 3
            //         },
            //         960 : {
            //             items: 6
            //         },
            //         1200 : {
            //             items: 9
            //         },
            //         1920 : {
            //             items: 12
            //         }
            //     }
            // });
            initCarousel();
            cargarImagenesExistentes();
            checkBoxesSelects();

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
        //     agregarImagenesAlCarrusel(files);
        // });

        $('#imagenesInput').change( function() {
            //Validar cantidad de imagenes en la Galería a maximo 4
            let countExistentesTable = $('#tablaImagenesExistentes tbody tr').length;
            let countExistentesInput = $('#archivos')[0].files.length;
            const countAllImgs = countExistentesTable+countExistentesInput+ this.files.length;

            if( countAllImgs > 4 ) {
                $('#invalidImageApp').html('Solo se permite un máximo de 4 imágenes por producto');
                $('#invalidImageApp').removeClass('d-none');
                const dtVacio = new DataTransfer();
                this.files = dtVacio.files;
            } else {

                // Obtener la lista de imágenes seleccionadas
                let files = $('#imagenesInput')[0].files;
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
            let countExistentes = $('#tablaImagenesExistentes tbody tr').length;
            let indexCarrusel = i+countExistentes;


            //Borramos todos los items para que se pueda inicializar bien
            for (let i=0; i<length; i++) {
                 carrusel.trigger('remove.owl.carousel', i).trigger('refresh.owl.carousel');
            }

            initCarousel();
            cargarImagenesExistentes();
            carrusel.trigger('refresh.owl.carousel');

            for (let i = 0; i < files.length; i++) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    let imgBase64 = e.target.result;
                    let imgTag = `
                    <div class="item imagen-nueva">
                        <img src="${imgBase64}" onclick="modal('${imgBase64}')" class="img-thumbnail" data-img-id="${i}" />

                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm mt-2 delete-btn" onclick="removeImagenCarrusel(event,'nueva',${i},${ indexCarrusel })">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="col-md-6 mt-3">
                            <input style="transform: scale(1.5);" type="checkbox" class="imagen-checkbox" data-type-image="nueva" data-id-image="${i}">
                        </div>
                    </div>
                    `;

                    //<input type="hidden" class="estatusG" name="estatusG" id="imagenPredeterminada_${i}">

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
            //console.log(dt.files);
        }


        function removeImagenCarrusel(e,typeImage,index,indexCarrusel) {
            e.preventDefault();

            if(typeImage == 'existente') {
                $(".owl-carousel").trigger('remove.owl.carousel', index).trigger('refresh.owl.carousel');
                 // Encuentra la fila con el ID coincidente
                var rowToDelete = $("#tablaImagenesExistentes tbody tr").filter(function() {
                    return $(this).find("td:first").text() == index;
                });

                // Elimina la fila de la tabla
                rowToDelete.remove();
            } else {
                removeFileFromFileList(index);
                $(".owl-carousel").trigger('remove.owl.carousel', indexCarrusel).trigger('refresh.owl.carousel');
                agregarImagenesAlCarrusel(dt.files)
            }



            return false;

        }


        // var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // function eliminarImagen(imagenId) {
        //     // Realizar una petición AJAX para eliminar la imagen por su ID
        //     $.ajax({
        //         url: '/eliminar-imagen/' + imagenId,
        //         type: 'DELETE',
        //         dataType: 'json',
        //         success: function (data) {
        //             // Si la eliminación es exitosa, eliminar la imagen del carousel en el frontend
        //             if (data.success) {
        //                 $(".owl-carousel").trigger('remove.owl.carousel', imagenId).trigger('refresh.owl.carousel');
        //             }
        //         },
        //         error: function (xhr, status, error) {
        //             console.log(error);
        //         }
        //     });
        // }

        // function SeleccionOff(){
        //     $('.imagen-checkbox').prop('checked', false);
        // }

        // let lastCheckedCheckbox;
        // let lastCheckedState;

        // $('.imagen-checkbox').on('change', function() {

        //     const imagenId = $(this).data('imagen-id');
        //     const productoId = $(this).data('producto-id');

        //     // Si se seleccionó el checkbox, mostrar la alerta
        //     if (this.checked) {
        //         const vari = this;
        //         Swal.fire({
        //             title: 'Confirmar selección',
        //             text: '¿Estás seguro de que deseas establecer esta imagen como predeterminada?',
        //             icon: 'warning',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3085d6',
        //             cancelButtonColor: '#d33',
        //             confirmButtonText: 'Establecer como predeterminado',
        //             cancelButtonText: 'Cancelar'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 $.ajax({
        //                     method: 'POST',
        //                     url: '{{ route('actualizar.estados.imagenes') }}',
        //                     data: {
        //                         _token: '{{ csrf_token() }}',
        //                         producto_id: productoId,
        //                         imagen_id: imagenId
        //                     },
        //                     success: function(data) {
        //                         SeleccionOff();
        //                         $(vari).prop('checked', true);
        //                     },
        //                     error: function(xhr, status, error) {
        //                         console.error(error);
        //                     }
        //                 });
        //             } else {
        //                 // Si el usuario cancela la alerta, desactivar el checkbox
        //                 //lastCheckedCheckbox.checked = false;
        //                 $(this).prop('checked', false);

        //             }
        //         });
        //     }else{
        //         $(this).prop('checked', true);
        //     }
        // });

        // Obtén todos los checkboxes con la clase "custom-checkbox"
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
                tipoImagen = $(this).data('type-image');
                let idImagen = $(this).data('id-image');
                $('#donde_imagen_destacada').val(tipoImagen);
                $('#imagen_destacada').val(idImagen);
            });
        }

        checkBoxesSelects();

        function checksMarcados(){
            const cantidadMarcados = $(".imagen-checkbox:checked").length;
            return cantidadMarcados;
        }

        function cargarImagenesExistentes() {

            let carrusel = $('.owl-carousel');
            let item ='';
            @foreach($imagenes as $key => $imagen)
                item  = `
                                <div class="item">

                                    <img src="{{ asset('galeria/' . $imagen->ruta) }}"
                                    onclick="modal('{{ asset('galeria/' . $imagen->ruta) }}')"
                                    class="img-thumbnail" alt="Imagen">

                                    <div class="col-md-6">
                                            <button type="button" class="btn btn-danger btn-sm mt-2"
                                            onclick="removeImagenCarrusel(event,'existente',{{ $key }},{{ $key }})"
                                            >
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <input
                                            style="transform: scale(1.5);"
                                            type="checkbox"
                                            class="imagen-checkbox"
                                            {{ $imagen->estatus ? 'checked' : '' }}
                                            data-type-image="existente"
                                            data-id-image="{{$imagen->id}}"
                                        >
                                    </div>
                                </div>
                `;

                carrusel.trigger('add.owl.carousel', [$(item)]);
                carrusel.trigger('refresh.owl.carousel');

                //$(".owl-carousel").append(item);
                @if ($imagen->estatus)
                    $('#donde_imagen_destacada').val('existente');
                    $('#imagen_destacada').val('{{$imagen->id}}');

                @endif

            @endforeach

        }


        function initCarousel(){
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


        }

        $('button[type="submit"]').click(function(event) {
            let form = document.getElementById("editarProducto");

            event.preventDefault();

            $("input[name='precio']").prop("disabled", false);
            $("select").prop("disabled", false);



            form.submit();


        });



    </script>
    <script src="{{ asset('js/commons.js') }}?ver={!! time() !!}"></script>
@stop

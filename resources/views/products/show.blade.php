@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver productos
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('products.index') }}"> Regresar</a>
                </div>
            </h3>

        </div>

        <div class="panel-body">

            <div class="row">
                <h4 class="sub-title">Especificaciones</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Codigo:</strong>
                        <input type="text" name="codigo" value="{{ $product->codigo }}"
                            class="form-control" placeholder="Codigo" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Estilo:</strong>
                        <input type="text" name="estilo" value="{{ $product->estilo }}"
                            class="form-control" placeholder="Estilo" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Línea:</strong>
                        <input type="text" name="linea" value="@if ($product->linea) {{ $product->linea->nombre }} @endif"
                            class="form-control" placeholder="Línea" readonly>
                    </div>
                </div>
                {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Talla:</strong>
                        <input type="text" name="talla" value="{{ $product->talla }}"
                            class="form-control"
                            placeholder="Talla" readonly>
                    </div>
                </div> --}}

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Marca:</strong>
                        <input type="text" name="marca" value="@if ($product->marca ) {{ $product->marca->nombre }} @endif"
                            class="form-control" placeholder="Marca" readonly>
                    </div>
                </div>

                {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Color:</strong>
                        <input type="text" name="color" value="{{ $product->color }}"
                            class="form-control" placeholder="Color" readonly>
                    </div>
                </div> --}}


                {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Concepto:</strong>
                        <input type="text" name="concepto" value="{{ $product->concepto }}"
                            class="form-control"
                            placeholder="Concepto" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Composicion:</strong>
                        <input type="text" name="composicion" value="{{ $product->composicion }}"
                            class="form-control"
                            placeholder="Composicion" readonly>
                    </div>
                </div> --}}
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Nombre Corto:</strong>
                        <input type="text" name="nombre_corto" value="{{ $product->nombre_corto }}"
                            class="form-control" placeholder="Nombre Corto" readonly>
                    </div>
                </div>

            </div>

            <div class="row">
                <h4 class="sub-title">Datos del producto</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Temporada:</strong>
                        <input type="text" name="temporada" value="@if ($product->temporada ) {{ $product->temporada->nombre }} @endif"
                            class="form-control"
                            placeholder="Temporada" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Descripción:</strong>
                        <input type="text" name="clasificacion" value="@if ($product->descripcion ) {{ $product->descripcion->nombre }} @endif"
                            class="form-control"
                            placeholder="clasificacion" readonly>
                    </div>
                </div>

                {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Costo bruto:</strong>
                        <input type="text" name="costo_bruto" value="{{ $product->costo_bruto }}"
                            class="form-control"
                            placeholder="Costo bruto" readonly>
                    </div>
                </div> --}}
                {{-- <div class="col-xs-12 col-sm-12 col-md-3">
                    <div class="form-group">
                        <strong>Descuento 1 (%):</strong>
                        <input type="text" name="descuento_1" value="{{ $product->descuento_1 }}"
                            class="form-control"
                            placeholder="Descuento 1 (%)" readonly>
                    </div>
                </div> --}}
                {{-- <div class="col-xs-12 col-sm-12 col-md-3">
                    <div class="form-group">
                        <strong>Descuento 2 (%):</strong>
                        <input type="text" name="descuento-2" value="{{ $product->descuento_2 }}"
                            class="form-control"
                            placeholder="Descuento 2 (%)" readonly>
                    </div>
                </div> --}}

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Proveedor:</strong>
                        <input type="text" name="proveedor" value="{{ $product->proveedor }}"
                            class="form-control"
                            placeholder="Proveedor" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                      <strong>Precio:</strong>
                      <input type="number" min="0" step="0.01" name="precio" value="{{ $product->precio }}"
                        class="form-control"
                        placeholder="Precio" readonly>
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
                            class="form-control" placeholder="Suela" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Nombre de suela:</strong>
                        <input type="text" name="nombre_suela" value="{{ $product->nombre_suela }}"
                            class="form-control"
                            placeholder="Nombre de suela" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Forro:</strong>
                        <input type="text" name="forro" value="{{ $product->forro }}"
                            class="form-control" placeholder="Forro" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Horma:</strong>
                        <input type="text" name="horma" value="{{ $product->horma }}"
                            class="form-control" placeholder="Horma" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Plantilla:</strong>
                        <input type="text" name="planilla" value="{{ $product->planilla }}"
                            class="form-control"
                            placeholder="Plantilla" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Tacón:</strong>
                        <input type="text" name="tacon" value="{{ $product->tacon }}"
                            class="form-control" placeholder="Tacón" readonly>
                    </div>
                </div>

            </div>

            {{-- <div class="row">
                <h4 class="sub-title">Costo Netos</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <strong>Incial ($):</strong>
                        <input type="text" name="inicial" value="{{ $product->inicial }}"
                            class="form-control" placeholder="Inicial" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <strong>Promedio ($):</strong>
                        <input type="text" name="promedio" value="{{ $product->promedio }}"
                            class="form-control"
                            placeholder="Promedio ($)" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <strong>Actual($):</strong>
                        <input type="text" name="actual" value="{{ $product->actual }}"
                            class="form-control" readonly>
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
                                <input type="checkbox" name="bloqueo_devolucion" @if($product->bloqueo_devolucion == 1) checked="true" @endif disabled="disabled">
                                <span class="read-switch slider round"></span>
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
                               disabled="disabled"
                        >
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
                        @endif
                    </div>
                </div>
            </div>


            <div class="row mt-5">
                <hr class="espaciador">

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Cargar imagen:</strong>
                        <input disabled="disabled" type="file" min="0" name="cargar_image" value="{{ old('cargar_image') }}"
                            class="form-control {{ $errors->has('cargar_image') ? 'is-invalid' : '' }}" placeholder="$">
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                    <div class="owl-carousel owl-theme ml-4">


                        @foreach($imagenes as $imagen)
                            <div class="item">

                                <img src="{{ asset('galeria/' . $imagen->ruta) }}" onclick="modal('{{ asset('galeria/' . $imagen->ruta) }}')" class="img-thumbnail" alt="Imagen">

                                <div class="col-md-6 mt-3">
                                    <input
                                        type="checkbox" disabled
                                        class="imagen-checkbox"
                                        data-imagen-id="{{ $imagen->id }}"
                                        data-producto-id="{{ $product->id }}"
                                        {{ $imagen->estatus ? 'checked' : '' }}
                                    >

                                </div>
                            </div>
                        @endforeach
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
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td class="text-center">
                                    <div style="margin-right: 15px;">
                                        <label class="text-md-right" for="color_1">
                                        </label>
                                        <label class="switch">
                                            <input type="checkbox" name="aplica" disabled="disabled">
                                            <span class="read-switch slider round"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Vendedor</th>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td class="text-center">
                                    <div style="margin-right: 15px;">
                                        <label class="text-md-right" for="color_1">
                                        </label>
                                        <label class="switch">
                                            <input type="checkbox" name="aplica" disabled="disabled">
                                            <span class="read-switch slider round"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Comprador</th>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td id=""><input class="form-control" readonly type="number" id="" name="" placeholder="$"></td>
                                <td class="text-center">
                                    <div style="margin-right: 15px;">
                                        <label class="text-md-right" for="color_1">
                                        </label>
                                        <label class="switch">
                                            <input type="checkbox" name="aplica" disabled="disabled">
                                            <span class="read-switch slider round"></span>
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
                            <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                Activación de producto</label>
                            <label class="switch">
                                <input type="checkbox" name="estatus" disabled="disabled" @if($product->estatus == 1) checked="true" @endif>
                                <span class="read-switch slider round"></span>
                            </label>
                        </div>
                        <span>
                            <small>OFF / ON swich para activacion o desactivacion de producto por defecto se
                                creara en activo
                        </small>
                        </span>
                    </div>

                </div>
            </div>


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


        $(document).ready(function(){

            $(".owl-carousel").owlCarousel({
                loop: false,
                margin: 20,
                center: false,
                nav: false,
                dots: true,
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


        function SeleccionOff(){
            $('.imagen-checkbox').prop('checked', false);
        }

        let lastCheckedCheckbox;
        let lastCheckedState;

        $('.imagen-checkbox').on('change', function() {

            const imagenId = $(this).data('imagen-id');
            const productoId = $(this).data('producto-id');

            // Si se seleccionó el checkbox, mostrar la alerta
            if (this.checked) {
                const vari = this;
                Swal.fire({
                    title: 'Confirmar selección',
                    text: '¿Estás seguro de que deseas establecer esta imagen como predeterminada?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Establecer como predeterminado',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'POST',
                            url: '{{ route('actualizar.estados.imagenes') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                producto_id: productoId,
                                imagen_id: imagenId
                            },
                            success: function(data) {
                                SeleccionOff();
                                $(vari).prop('checked', true);
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    } else {
                        // Si el usuario cancela la alerta, desactivar el checkbox
                        //lastCheckedCheckbox.checked = false;
                        $(this).prop('checked', false);

                    }
                });
            }else{
                $(this).prop('checked', true);
            }
        });



    </script>
@stop

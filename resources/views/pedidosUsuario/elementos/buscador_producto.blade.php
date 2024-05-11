<div class="row mt-4">
    <h4 class="sub-title">Busqueda de articulo</h4>
    <hr class="espaciador">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="buscador-producto">
            <input id="buscar_producto" type="text" name="buscar_producto"
                value="{{ request()->input('buscar_producto') }}" autocomplete="off"
                class="form-control {{ $errors->has('buscar_producto') ? 'is-invalid' : '' }}"
                placeholder="Buscar Producto">
            <div class="autocomplete-producto">
                <div id="myInputautocomplete-list-producto" class="autocomplete-items-producto">
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <h4 class="sub-title">Pedido: <span id="numero_pedido"></span> - <span class="success-box"
                    id="estatus_pedido"></span></h4>
            <hr class="espaciador">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="table-responsive" style="height: 250px;">
                    <input type="hidden" name="pedido_id" value="" id="pedido_id">
                    <table id="empresas" class="table table-striped nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="text-center">Estilo</th>
                                <th class="text-center">Marca</th>
                                <th class="text-center">Color</th>
                                <th class="text-center">Acabado</th>
                                <th class="text-center">Talla</th>
                                <th class="text-center">Precio socio</th>
                                <th class="text-center">% Descuento</th>
                                <th class="text-center">Costo neto</th>
                                <th class="text-center">Total existencias</th>
                                <th class="text-center">Existencia tienda</th>
                                <th class="text-center">Cantidad solicitada</th>
                                <th class="text-center">Cantidad pendiente</th>
                                <th class="text-center">Cancelar</th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="row mt-4">
            <h4 class="sub-title">Observaciones del pedido:</h4>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <textarea name="observacion" class="form-control" id="exampleFormControlTextarea1" rows="5">{{ old('observacion') }}</textarea>
                </div>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="row">
                    <h4 class="sub-title">Vales y descuentos</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Cupón de descuento:</strong>
                            <input type="text" id="cupon" name="cupon" class="form-control"
                                placeholder="Cupón de descuento" pattern="[A-Z0-9]*"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Vale a fovor:</strong>
                            <input type="text" id="vale" name="vale" class="form-control"
                                placeholder="Vale a fovor" pattern="[A-Z0-9]*"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <span id="aplicar_cupones" class="btn btn-info" onClick="aplicarCupones()">Aplicar vales y
                                cupones</span>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <h4 class="sub-title">Envio</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Tipo de envio:</strong><br>
                            <select required style="width: 100%;" class="js-example-basic-single form-control"
                                name="tipo_envio" id="tipo_envio" data-is-admin="{{ \Auth::user()->isAdmin() ? 'true' : 'false' }}">
                                <option value="">Seleccione un metodo de envio</option>
                                <option value="tienda">Buscar en tienda</option>
                                <option value="domicilio">Envio a domicilio</option>
                            </select>
                        </div>
                    </div>

                    <div id="elemento_envio_domicilio" class="d-none">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Dirección - Alias:</strong><br>
                                <select style="width: 100%;" class="js-example-basic-single form-control"
                                    name="direccion_cliente" id="direccion_cliente">

                                </select>
                            </div>
                        </div>
                        @if (\Auth::user()->isAdmin())
                            <div class="col-xs-12 col-sm-12 col-md-8 mt-5">
                                <div class="form-group">
                                    <h5>Total de cajas (Para el Admin)</h5>
                                </div>
                            </div>

                            <div
                                class="col-xs-12 col-sm-12 col-md-4 mt-5 @if (!\Auth::user()->isAdmin()) d-none @endif">
                                <div class="form-group">
                                    <input class="js-example-basic-single form-control" type="number"
                                        name="total_cajas" id="total_cajas" value="1" min="1">
                                </div>
                            </div>
                        @endif

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-5">
                            <div class="form-group">
                                <p>
                                    Leyenda sobre el manejo de la paqueteria, todo envio
                                    tiene por default; 1 costo de envio, depende del volumen de compra.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @include('pedidosUsuario.elementos.total_pedido')

        </div>
        <hr><br><br>

    </div>

    <div class="row row-no-gutters seccion-final">
        <div class="col-xs-12 col-sm-6 col-md-7">
            <span id="button-eliminar" class="btn btn-warning ml-1 notifi-distribuidor d-none">Eliminar</span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-5">
            <a class="btn btn-danger pull-right" href="{{ route('pedidos.index') }}">Cancelar</a>
            <input id="accion_pedido" type="hidden" name="accion_pedido" value="" readonly>

            <button id="btn-solicitar" class="btn btn-info ml-1 pull-rigt d-none">Solicitar Pedido</button>


        </div>
    </div>
</div>

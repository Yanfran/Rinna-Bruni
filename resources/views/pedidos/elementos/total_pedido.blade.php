<div class="col-xs-12 col-sm-12 col-md-6">
    <div class="row">
        <h4 class="sub-title">Total del pedido</h4>
        <hr class="espaciador">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="table-responsive">
                <table class="table table-striped nowrap" style="width: 100%">
                    <tr>
                        <th>Concepto:</th>
                        <td>Costo</td>
                    </tr>
                    <tr>
                        <th>Total en artículos:</th>
                        <td id="monto_total">$0.00</td>
                        <input type="hidden" id="monto_total_input" name="monto_total_input" value="0">
                    </tr>
                    <tr>
                        <th>Cupón de descuento:</th>
                        <td id="monto_cupon" style="color: red;">-$0.00</td>
                        <input type="hidden" id="monto_cupon_input" name="monto_cupon_input" value="0">
                    </tr>
                    <tr>
                        <th>Vale a favor:</th>
                        <td id="monto_vale" style="color: red;">-$0.00</td>
                        <input type="hidden" id="monto_vale_input" name="monto_vale_input" value="0">
                    </tr>
                    <tr>
                        <th>Paquetería:</th>
                        <td id="monto_paqueteria">$0.00</td>
                        <input type="hidden" id="monto_paqueteria_input" name="monto_paqueteria_input" value="0">
                    </tr>
                    {{-- <tr>
                        <th id="porcentaje_monto_descuento">Descuento de cliente aplicado:</th>
                        <td id="monto_descuento_cliente" style="color: red;">-$0.00</td>
                        <input type="hidden" id="monto_descuento_cliente_input" name="monto_descuento_cliente_input"
                            value="0">
                    </tr> --}}
                    <tr>
                        <th class="total_pagar">Total a pagar:</th>
                        <td class="total_pagar_span" id="total_a_pagar">$0.00</td>
                        <input type="hidden" id="total_a_pagar_input" name="total_a_pagar_input" value="0">
                    </tr>
                </table>
            </div>
        </div>
        <hr class="espaciador">
        <div id="elemento-forma_pago" class="d-none">
            <div class="row">
                <h4 class="sub-title mt-5">Método de pago</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        {{-- <strong>Forma de pago:</strong><br> --}}
                        <select style="width: 100%;" class="js-example-basic-single form-control"
                            name="metodo_pago" id="metodo_pago" data-is-admin="{{ \Auth::user()->isAdmin() ? 'true' : 'false' }}">
                            <option value="">Seleccione un método de pago</option>
                            <option value="Mercado Pago">Mercado pago</option>
                            <option value="Transferencia bancaria">Transferencia bancaria</option>
                            <option value="Pago en efectivo">Pago en efectivo</option>

                        </select>
                    </div>
                    <br>
                    <div id="containerVaucher" class="d-none">
                        <div class="form-group">
                            <label class="form-label">Imagen Vaucher</label>
                            <input type="file"
                                   name="vaucher"
                                   id="vaucher"
                                   class="form-control validar-img-maximo"
                                   accept="image/jpeg, image/png"
                                   data-max-width="1024"   {{-- px --}}
                                   data-max-height="1024" {{-- px --}}
                                   data-max-size="200"    {{-- Kb --}}
                                   data-container-img="previewVaucher"
                                   data-container-error="invalidImage"
                            >
                        </div>
                        <span style="color:red !important;" id="invalidImage" class="d-none text-danger p-3"></span>
                        <div class="form-group">
                            <img id="previewVaucher"
                                 style="max-width: 100%; height: auto;"
                                 name="previewVaucher"
                                 class="img-thumbnail"
                                 src="{{ asset('images/default_image.jpg') }}"
                            >
                        </div>
                    </div>
                    <div class="form-group">
                        <input style="transform: scale(1.5);" type="checkbox" id="condiciones" name="condiciones" >
                        {{-- &nbsp;Aceptar <button style="padding:0 !important" type="button" class="btn btn-link">Terminos y Condiciones</button> --}}
                        &nbsp;Aceptar <a href="#" onclick="alert('Redireccionar a link de términos y condiciones')" class="btn-link">Términos y Condiciones</a>
                    </div>
                    <div id="botones_pago_container" class="form-group d-none">
                        <div id="wallet_container"></div>{{-- Boton de MercadoPago --}}
                        <button id="btnEnviarPedido" name="btnEnviarPedido" type="submit" class="btn btn-warning btn-lg btn-block d-none"><i class="fas fa-tasks"></i> Enviar Pedido</button>
                    </div>
                </div>
            </div>

            {{-- <div class="row check-terminos">
                <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="contenedor-select">
                            <label class="switch">
                                <input type="checkbox" name="estatus" checked="true">
                                <span class="slider round"></span>
                            </label>
                            <label class="text-md-right" for="color_1">
                                <span class="requerido">*</span>
                                Aceptar <a href="#">Términos y Condiciones del servicio</a>
                            </label>
                        </div>
                        <span><small>OFF / ON switch para aceptar los términos</small></span>
                </div>
            </div> --}}
        </div>

    </div>
</div>


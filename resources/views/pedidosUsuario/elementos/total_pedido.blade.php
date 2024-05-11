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
                    <tr>
                        <th>Descuento de cliente aplicado:</th>
                        <td id="monto_descuento_cliente" style="color: red;">-$0.00</td>
                        <input type="hidden" id="monto_descuento_cliente_input" name="monto_descuento_cliente_input"
                            value="0">
                    </tr>
                    <tr>
                        <th class="total_pagar">Total a pagar:</th>
                        <td class="total_pagar_span" id="total_a_pagar">$0.00</td>
                        <input type="hidden" id="total_a_pagar_input" name="total_a_pagar_input" value="0">
                    </tr>
                </table>
            </div>
        </div>

        <div id="elemento-forma_pago" class="dd-none">
            <div class="row mt-1">
                <h4 class="sub-title mt-5">Método de pago</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Forma de pago:</strong><br>
                        <select style="width: 100%;" class="js-example-basic-single form-control" name="metodo_pago"
                            id="metodo_pago" data-is-admin="{{ \Auth::user()->isAdmin() ? 'true' : 'false' }}">
                            <option value="">Seleccione un método de pago</option>
                            <option value="Mercado Pago">Mercado pago</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="row mt-1">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group text-start"> <!-- Agrega la clase "text-start" aquí -->
                        <input id="input-20" type="file">
                    </div>
                </div>
            </div>

            <div class="row check-terminos">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
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
                </div>
            </div>
        </div>

    </div>
</div>

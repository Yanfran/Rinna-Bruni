
<div class="row mt-3">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="buscador-cliente">
            {{--  <span class="input-group-addon" id="basic-addon1"><i class="fas fa-search"></i></span> --}}
            <input id="buscar_cliente" type="text" name="buscar_cliente"
                value="{{ request()->input('buscar_cliente') }}" autocomplete="off"
                class="form-control {{ $errors->has('buscar_cliente') ? 'is-invalid' : '' }}"
                placeholder="Buscar cliente">
            <div class="autocomplete">
                <div id="myInputautocomplete-list" class="autocomplete-items">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xs-12 col-sm-12 col-md-10">
        <div class="row">
            <h4 class="sub-title">Datos del cliente</h4>
            <input id="id_usuario" type="hidden" name="id_usuario">
            <input id="descuento_usuario" type="hidden" name="descuento_usuario">
            <input id="bloqueo_pedido" type="hidden" name="bloqueo_pedido" value="1">
            <hr class="espaciador">
            <div class="col-xs-12 col-sm-6">
                <h5 class="ml-3">No del cliente: <p class="no-cliente"></p>
                </h5>

            </div>
            <div class="col-xs-12 col-sm-6">
                <h5 class="ml-3">Telefono fijo: <p class="telefono-fijo"></p>
                </h5>

            </div>

            <div class="col-xs-12 col-sm-6">
                <h5 class="ml-3">Nombre: <p class="nombre"></p>
                </h5>

            </div>
            <div class="col-xs-12 col-sm-6">
                <h5 class="ml-3">Movil: <p class="movil"></p>
                </h5>

            </div>

            <div class="col-xs-12 col-sm-6">
                <h5 class="ml-3">Correo: <p class="correo"></p>
                </h5>

            </div>
        </div>
    </div>

</div>


<div class="panel-body">
    <span class="titulo-registro"><h5>{{ trans('empresas.label_datos_config') }}</h5></span>
    <hr>

    <div class="form-group row">
        <div class="col-md-6">
            <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                {{ trans('empresas.label_color_1') }}</label>

            <div id="color_1" data-format="alias" class="input-group colorpicker-component">
                <input type="text" name="color_1" required="" value="{{ $empresas->getColorPrimario() }}" class="form-control" placeholder="click Here ->" />
                <span class="input-group-addon"><i></i></span>
            </div>
        </div>
        <div class="col-md-6">
            <label class="text-md-right" for="color_2"><span class="requerido">* </span>
                {{ trans('empresas.label_color_2') }}</label>
            <div id="color_2" data-format="alias" class="input-group colorpicker-component">
                <input type="text" name="color_2" required="" value="{{ $empresas->getColorSecundario() }}" class="form-control" placeholder="click Here ->" />
                <span class="input-group-addon"><i></i></span>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label class="text-md-right" for="logo">{{ trans('empresas.label_logo') }}</label>
            <input type="file" name="logo" class="dropify"
                @if(empty($empresas->getLogo()))
                required=""
                @endif
                data-default-file="{{ asset('uploads/logos/'.$empresas->getLogo()) }}" data-show-remove="false" />
        </div>
    </div>

    <h3>Configuración Mercado Pago</h3>
    <hr>

    <div class="form-group row">
        <div class="col-md-6">
            <label class="text-md-right" for="mp_public_key">Clave pública de Mercado Pago</label>
            <input type="text" name="mp_public_key" class="form-control" value="{{ $empresas->mp_public_key ?? '' }}" required />
        </div>
        <div class="col-md-6">
            <label class="text-md-right" for="mp_access_token">Clave secreta de Mercado Pago</label>
            <input type="text" name="mp_access_token" class="form-control" value="{{ $empresas->mp_access_token ?? '' }}" required />
        </div>
    </div>

    <h3>Costos y paquetería</h3>
    <hr>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                Costos constante para calcular el costo de la paqueteria en los pedidos</label>
            <div id="costo_paqueteria" class="input-group colorpicker-component">
                <input type="number" min="0" step="0.01" name="costo_paqueteria" required="" value="{{ $empresas->costo_paqueteria ?? '' }}" class="form-control" />
            </div>
        </div>
    </div>

    <h3>Sesiones o inactividad</h3>
    <hr>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                Tiempo para cerrar sesión por inactividad (tiempo calculado en minutos)</label>
            <div id="inactividad" class="input-group colorpicker-component">
                <input type="number" min="0" name="inactividad" required="" value="{{ $empresas->inactividad ?? '' }}" class="form-control" />
            </div>
        </div>
    </div>


    <hr>
    <div class="form-group row mb-0 text-r">
        <button class="btn-orange pull-right primario" type="submit">
            {{ trans('empresas.label_register') }}</button>
        {{-- <a href="{{ route('empresas.index') }}" class="btn-black pull-right secundario">
            {{ trans('empresas.label_regresar') }}</a> --}}
    </div>
</div>

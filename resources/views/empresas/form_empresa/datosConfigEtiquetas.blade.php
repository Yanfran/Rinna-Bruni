<div class="panel-body">
  	<span class="titulo-registro">
  		<h5>{{ trans('empresas.label_datos_config_etiquetas') }}</h5>
  	</span>
    <hr>

      <div class="col-md-4">
                    <label class="text-md-right" for="estatus">
                    {{ trans('empresas.label_etiqueta_impuesto') }}</label>

                     <input autofocus="" class="form-control{{ $errors->has('impuesto_nombre') ? ' is-invalid' : '' }}" id="impuesto_nombre" name="impuesto_nombre" required="" type="text" value="{{ $empresas->getImpuestoNombre() }}">
                     @if ($errors->has('impuesto_nombre'))
                        <span class="invalid-feedback" role="alert">
                            <strong>
                                {{ $errors->first('impuesto_nombre') }}
                            </strong>
                        </span>
                    @endif
                      <span><small>{{ trans('empresas.label_nota_etiqueta_identificacion') }}</small></span>
                   


                   
               </div>

</div>

    <div class="panel-body">

             <span class="titulo-registro"><h5>{{ trans('empresas.label_datos_empresa') }}</h5></span>
              <hr>


            <div class="form-group row">
                <div class="col-md-4">
                    <label class="text-md-right" for="nombre"><span class="requerido">* </span>
                    {{ trans('empresas.label_nombre') }}</label>
                    <input autofocus="" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" id="nombre" name="nombre" required="" type="text" value="{{ $empresas->getNombre() }}">
                     @if ($errors->has('nombre'))
                        <span class="invalid-feedback" role="alert">
                            <strong>
                                {{ $errors->first('nombre') }}
                            </strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="text-md-right" for="email"><span class="requerido">* </span>
                    {{ trans('empresas.label_email') }}</label>
                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" required="" type="email" value="{{ $empresas->getEmail() }}">
                     @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                                {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>


                <div class="col-md-4">
                    <label class="text-md-right" for="estatus">
                    {{ trans('empresas.label_estatus') }}</label>
                    <select name="estatus" class="form-control" id="estatus">
                        @if(!empty($empresas->getEstatusValue()))
                        <option  value="{{ $empresas->getEstatusValue() }}">{{ $empresas->getEstatus() }}</option>
                            @if($empresas->getEstatusValue() == 0)
                             <option value="1">{{ trans('empresas.select_activo') }}</option>
                            @else
                            <option  value="0">{{ trans('empresas.select_inactivo') }}</option>
                            @endif
                        @else
                         <option value="">{{ trans('empresas.select_seleccione') }}</option>
                        <option class="form-control" id="estatus" value="0">{{ trans('empresas.select_inactivo') }}</option>
                        <option class="form-control" id="estatus" value="1">{{ trans('empresas.select_activo') }}</option>
                        @endif
                        
                     
                    </select>


                   
               </div>

                <div class="col-md-4">
                    <label class="text-md-right" for="dominio">
                    {{ trans('empresas.label_dominio') }}</label>
                    <input autofocus="" class="form-control" id="dominio" name="dominio" type="text" value="{{ $empresas->getSubDominio() }}">
               </div>

                <div class="col-md-4">
                    <label class="text-md-right" for="telefono_1"><span class="requerido">* </span>
                    {{ trans('empresas.label_telefono_1') }}</label>
               <input name="telefono_1"  required="" type="text" class="form-control input-medium bfh-phone" data-format="dd (ddd) ddd-dddd" placeholder="+58 (000) 000-0000" value="{{ $empresas->getTelefono_1() }}">
               </div>
               <div class="col-md-4">
                    <label class="text-md-right" for="telefono_2">
                    {{ trans('empresas.label_telefono_2') }}</label>
                     <input name="telefono_2" type="text" class="form-control input-medium bfh-phone" data-format="dd (ddd) ddd-dddd" placeholder="58 (000) 000-0000" value="{{ $empresas->getTelefono_2() }}">
               </div>
                <div class="col-md-12">
                    <label class="text-md-right" for="telefono_2">
                    {{ trans('empresas.label_direccion') }}</label>
                     <textarea class="form-control" name="direccion">{{ $empresas->getDireccion() }}</textarea> 
               </div>
           </div>

       </div>
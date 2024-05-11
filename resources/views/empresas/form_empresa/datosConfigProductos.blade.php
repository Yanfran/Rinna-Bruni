
<div class="panel-body panel-2">
  	<span class="titulo-registro">
  		<h5>{{ trans('empresas.label_datos_config_general') }}</h5>
  	</span>
    <hr>

    <div class="form-group row">

               <div class="col-md-5">
                 	<div class="contenedor-select">
               		
               	
	                    <label class="text-md-right" for="color_1"><span class="requerido">* </span>
	                    {{ trans('empresas.label_precio_prospecto') }}</label>

	                 
	                        <!-- Rounded switch -->
    								<label class="switch">
    								  <input type="checkbox" name="visivilidad_precio" 
                        
                        @if($empresas->getVisivilidadPrecio() == 1)
                        checked="true" 
                        @endif
          
                      >
    								  <span class="slider round"></span>
    								</label>


    				    </div>
    				    <span><small>{{ trans('empresas.label_nota_apagado_precios') }}</small></span>
                        
          </div>
<div class="col-md-2"></div>

       <div class="col-md-5">
                  <div class="contenedor-select">
                  
                
                      <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                      {{ trans('empresas.label_precio_cfdi') }}</label>

                   
                          <!-- Rounded switch -->
                    <label class="switch">
                      <input type="checkbox" name="visivilidad_cfdi" id="checkCfdi"
                        
                        @if($empresas->getVisivilidadCfdi() == 1)
                        checked="true" 
                        @endif
          
                      >
                      <span class="slider round"></span>
                    </label>


                </div>
                <span><small>{{ trans('empresas.label_nota_apagado_cfdi') }}</small></span>
                        
          </div>

    </div>



<div class="form-group row">

               <div class="col-md-5">
                  <div class="contenedor-select">
                  
                
                      <label class="text-md-right" for="dato_entrega"><span class="requerido">* </span>
                      {{ trans('empresas.label_edicion_factura') }}</label>

                   
                  <!-- Rounded switch -->
                    <label class="switch">
                      <input type="checkbox" name="dato_factura" 
                        
                        @if($empresas->getEdicionFactura() == 0)
                        checked="true" 
                        @endif
          
                      >
                      <span class="slider round"></span>
                    </label>


                </div>
                <span><small>{{ trans('empresas.label_nota_factura') }}</small></span>
                        
          </div>



<div class="col-md-2"></div>

       <div class="col-md-5">
                  <div class="contenedor-select">
                  
                
                      <label class="text-md-right" for="dato_entrega"><span class="requerido">* </span>
                      {{ trans('empresas.label_edicion_entrega') }}</label>

                   
                          <!-- Rounded switch -->
                    <label class="switch">
                      <input type="checkbox" name="dato_entrega" id="checkEntrega"
                        
                        @if($empresas->getEdicionEntrega() == 0)
                        checked="true" 
                        @endif
          
                      >
                      <span class="slider round"></span>
                    </label>


                </div>
                <span><small>{{ trans('empresas.label_nota_entrega') }}</small></span>
                        
          </div>

    </div>









</div>

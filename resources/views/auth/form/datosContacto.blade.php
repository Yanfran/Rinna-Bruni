<div class="col-md-12">
	<span>{{ trans('registro.label_datos_contacto') }}</span>
	<hr>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('primerNombre') ? ' is-invalid' : '' }}"
		       id="primerNombre"
		       name="primerNombre"
		       placeholder="{{ trans('registro.label_primer_nombre') }} *"
		       required=""
		       type="text"
		       value="{{ old('primerNombre') }}">
		@if ($errors->has('primerNombre'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('primerNombre') }}
                                   
                                </span>
		@endif
	</div>
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('apellido') ? ' is-invalid' : '' }}"
		       id="apellido"
		       name="apellido"
		       placeholder="{{ trans('registro.label_apellido') }} *"
		       required=""
		       type="text"
		       value="{{ old('apellido') }}">
		@if ($errors->has('apellido'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('apellido') }}
                                    
                                </span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('segundoNombre') ? ' is-invalid' : '' }}"
		       id="segundoNombre"
		       name="segundoNombre"
		       placeholder="{{ trans('registro.label_segundo_nombre') }}"
		       type="text"
		       value="{{ old('segundoNombre') }}">
		@if ($errors->has('segundoNombre'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('segundoNombre') }}
                                    
                                </span>
		@endif
	</div>
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('posicion') ? ' is-invalid' : '' }}"
		       id="posicion"
		       name="posicion"
		       placeholder="{{ trans('registro.label_posicion') }}"
		       value="{{ old('posicion') }}"
		       type="text">
		@if ($errors->has('posicion'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('posicion') }}
                                    
                                </span>
		@endif
	</div>

</div>
<div class="col-md-6">
	<div class="form-group">
		<input name="telefonoContacto"
		       type="text"
		       min="12"
		       max="12" 
		       class="form-control input-medium phone"
		       value="{{ old('telefonoContacto') }}"
		       required=""
		       placeholder="{{ trans('registro.label_telefono') }} *">
		@if ($errors->has('telefonoContacto'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('telefonoContacto') }}
                                    
                                </span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input name="extContatco"
		       type="text"
		       min="5"
		       max="5" 
		       class="form-control input-medium ext"
		       required=""
		       value="{{ old('extContatco') }}"
		       placeholder="ext. *">
		@if ($errors->has('extContacto'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('extContacto') }}
                                    
                                </span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input name="movilContatco"
               type="text"
               class="form-control input-medium movil"
               value="{{ old('movilContatco') }}"
               placeholder="{{ trans('registro.label_movil') }} 000 000-0000">
		@if ($errors->has('movilContatco'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('movilContatco') }}
                                    
                                </span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input name="emailContacto"
		       type="email"
		       class="form-control input-medium"
		       required=""
		       value="{{ old('emailContacto') }}"
		       placeholder="{{ trans('registro.label_email_contacto') }} *">
		@if ($errors->has('emailContacto'))
			<span class="invalid-feedback" role="alert">
                                    
                                        {{ $errors->first('emailContacto') }}
                                    
                                </span>
		@endif
	</div>
</div>

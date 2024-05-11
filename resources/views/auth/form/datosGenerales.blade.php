<div class="col-md-12">
    <span>
        {{ trans('registro.label_datos_generales') }}
    </span>
	<hr>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('productosFabricacion') ? ' is-invalid' : '' }}"
		       id="productosFabricacion"
		       name="productosFabricacion"
		       placeholder="{{ trans('registro.label_productos_fabricacion') }}"
		       type="text"
		       value="{{ old('productosFabricacion') }}">
		@if ($errors->has('productosFabricacion'))
			<span class="invalid-feedback" role="alert">
                {{ $errors->first('productosFabricacion') }}
            </span>
		@endif
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		<label class="etiqueta">{{ trans('registro.label_horarioD') }}</label>
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('horarioDesde') ? ' is-invalid' : '' }}"
		       id="horarioDesde"
		       name="horarioDesde"
		       type="time"
		       value="{{ old('horarioDesde') }}">
		@if ($errors->has('horarioDesde'))
			<span class="invalid-feedback" role="alert">
                {{ $errors->first('horarioDesde') }}
            </span>
		@endif
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		<label class="etiqueta">{{ trans('registro.label_horarioH') }}</label>
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('horarioHasta') ? ' is-invalid' : '' }}"
		       id="horarioHasta"
		       name="horarioHasta"
		       type="time"
		       value="{{ old('horarioHasta') }}">
		@if ($errors->has('horarioHasta'))
			<span class="invalid-feedback" role="alert">
                {{ $errors->first('horarioHasta') }}
            </span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('competidores') ? ' is-invalid' : '' }}"
		       id="competidores"
		       name="competidores"
		       placeholder="{{ trans('registro.label_competidores') }}"
		       type="text"
		       value="{{ old('competidores') }}">
		@if ($errors->has('competidores'))
			<span class="invalid-feedback" role="alert">
                {{ $errors->first('competidores') }}
            </span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('productosCotizacion') ? ' is-invalid' : '' }}"
		       id="productosCotizacion"
		       name="productosCotizacion"
		       placeholder="{{ trans('registro.label_venta_productos') }}"
		       type="text"
		       value="{{ old('productosCotizacion') }}">
		@if ($errors->has('productosCotizacion'))
			<span class="invalid-feedback" role="alert">
                {{ $errors->first('productosCotizacion') }}
            </span>
		@endif
	</div>
</div>
<!-- pais -->
<div class="col-md-6">
	<div class="form-group">
		<select name="pais_id" class="form-control" id="pais_id" required>
			@if(!empty($empresa->ListaPais()))
				@if(old('pais_id') != null)
					<option selected value="{{ old('pais_id') }}">{{ $empresa->getPaisNombre(old('pais_id')) }}</option>
					@foreach($empresa->ListaPais() as $k)
						@if(old('pais_id') != $k->getID())
							<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
						@endif
					@endforeach
				@else
					<option value="">{{ trans('registro.input_select_pais') }} *</option>
					@foreach($empresa->ListaPais() as $k)
						<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
					@endforeach
				@endif
			@else
				<option value="">{{ trans('registro.label_sin_registro_paises') }}</option>
			@endif
		</select>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<select name="estado_id" class="form-control" id="estado_id" required>
			@if(old('estado_id') != null)
				<option selected
				        value="{{ old('estado_id') }}">{{ $empresa->getEstadoNombre(old('estado_id')) }}</option>
				@foreach($empresa->ListaEstado(old('pais_id')) as $k)
					@if(old('estado_id') != $k->getID())
						<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
					@endif
				@endforeach
			@else
				<option value="">{{ trans('registro.input_select_estado') }} *</option>
			@endif
		</select>
		<span class="msg-estado">{{ trans('registro.label_msg_estado') }}</span>
	</div>
</div>
<!-- giros -->
<div class="col-md-6">
	<div class="form-group">
		<select name="giro_id" class="form-control" id="giro_id" required>
			@if(!empty($empresa->ListaGiros()))
				@if(old('giro_id') != null)
					<option selected value="{{ old('giro_id') }}">{{ $empresa->getGiroNombre(old('giro_id')) }}</option>
					@foreach($empresa->ListaGiros() as $k)
						@if(old('giro_id') != $k->getID())
							<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
						@endif
					@endforeach
				@else
					<option value="">{{ trans('registro.input_select_giro') }} *</option>
					@foreach($empresa->ListaGiros() as $k)
						<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
					@endforeach
				@endif
			@else
				<option value="">{{ trans('registro.label_msg_giro') }}</option>
			@endif
		
		</select>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<select name="subgiro_id" class="form-control" id="subgiro_id" required>
			@if(old('subgiro_id') != null)
				<option selected
				        value="{{ old('subgiro_id') }}">{{ $empresa->getSubgiroNombre(old('subgiro_id')) }}</option>
				@foreach($empresa->ListaSubgiro(old('giro_id')) as $k)
					@if(old('subgiro_id') != $k->getID())
						<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
					@endif
				@endforeach
			@else
				<option value="">{{ trans('registro.input_select_subgiro') }} *</option>
			@endif
		</select>
		<span class="msg-estado">{{ trans('registro.label_msg_subgiro') }}</span>
	</div>
</div>

<!-- tamaño empresa -->
<div class="col-md-6">
	<div class="form-group">
		<select name="tam_id" class="form-control" id="tam_id" required>
			@if(!empty($empresa->ListaTam()))
				@if(old('tam_id') != null)
					<option selected value="{{ old('tam_id') }}">{{ $empresa->getTamNombre(old('tam_id')) }}</option>
					@foreach($empresa->ListaTam() as $k)
						@if(old('tam_id') != $k->getID())
							<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
						@endif
					@endforeach
				@else
					<option value="">{{ trans('registro.input_select_tan') }} *</option>
					@foreach($empresa->ListaTam() as $k)
						<option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
					@endforeach
				@endif
			@else
				<option value="">{{ trans('registro.label_msg_giro') }}</option>
			@endif
		
		</select>
	</div>
</div>
<div class="col-md-6"></div>

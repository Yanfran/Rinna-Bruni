<div class="col-md-6">
	<div class="form-group">
		<input autocomplete="off"
		       autofocus=""
		       class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}"
		       id="nombre"
		       name="nombre"
		       placeholder="{{ trans('empresas.label_nombre_persona') }} *"
		       required=""
		       type="text"
		       value="{{ old('nombre') }}">
		@if ($errors->has('nombre'))
			<span class="invalid-feedback" role="alert">
{{ $errors->first('nombre') }}
</span>
		@endif
	</div>
	<div class="form-group">
		<input autocomplete="new-password"
		       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
		       id="password"
		       name="password"
		       placeholder="{{ trans('registro.label_pass') }} *"
		       required=""
		       type="password">
		@if ($errors->has('password'))
			<span class="invalid-feedback" role="alert">
{{ $errors->first('password') }}
</span>
		@endif
		<span class="error" id="error_pass_p">
</span>
	</div>
	<div class="form-group">
		<input autocomplete="off"
		       class="form-control{{ $errors->has('razonSocial') ? ' is-invalid' : '' }}"
		       id="razonSocial"
		       name="razonSocial"
		       placeholder="{{ trans('registro.label_denominacion') }} *"
		       required=""
		       type="text"
		       value="{{ old('razonSocial') }}">
		@if ($errors->has('razonSocial'))
			<span class="invalid-feedback" role="alert">
{{ $errors->first('razonSocial') }}
</span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input autocomplete="off"
		       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
		       id="email"
		       name="email"
		       placeholder="{{ trans('registro.label_email') }} *"
		       required=""
		       type="email"
		       value="{{ old('email') }}">
		<span class="error" id="error_email">
</span>
		@if ($errors->has('email'))
			<span class="invalid-feedback" role="alert">
{{ $errors->first('email') }}
</span>
		@endif
	</div>
	<div class="form-group">
		<input class="form-control"
		       id="password-confirm"
		       name="password_confirmation"
		       placeholder="{{ trans('registro.label_confirm_pass') }} *"
		       required=""
		       type="password">
		<span class="error" id="error_pass">
</span>
		</input>
	</div>
	<div class="form-group">
		<input autocomplete="off"
		       class="form-control{{ $errors->has('descripcionGiro') ? ' is-invalid' : '' }}"
		       id="descripcionGiro"
		       name="descripcionGiro"
		       placeholder="{{ trans('registro.label_descripcion_giro') }} *"
		       required=""
		       type="text"
		       value="{{ old('descripcionGiro') }}">
		@if ($errors->has('descripcionGiro'))
			<span class="invalid-feedback" role="alert">
{{ $errors->first('descripcionGiro') }}
</span>
		@endif
	</div>
</div>
<div class="col-md-6">
	<br>
	<div class="form-group check">
		<input name="terminos" required="" type="checkbox" value="1"
		       @if(old('terminos') != null)
		       checked="true"
				@endif
		>
		{{ trans('registro.label_terminos') }}
		<br>
		</br>
		</input>
	</div>
	</br>
</div>
<div class="col-md-6">
	<div class="form-group">
		<input class="btnRegister" id="register" type="submit" value="{{ trans('registro.btn_register') }}"/>
	</div>
</div>
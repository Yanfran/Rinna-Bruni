
<div class="row mt-3">
	<div class="col-xs-12 col-sm-12 col-md-10">
		<div class="row">
			<?php $distribuidor = json_decode($usuario, true); ?>
			<h4 class="sub-title">Datos del distribuidor</h4>
			<input id="id_usuario" type="hidden" name="id_usuario" value="{{ $distribuidor['id'] }}">
			<input id="descuento_usuario" type="hidden" name="descuento_usuario" value="{{ $distribuidor['descuento'] }}">
			<input id="bloqueo_pedido" type="hidden" name="bloqueo_pedido" value="{{ $distribuidor['bloqueo_pedido'] }}">
			<hr class="espaciador">
			<div class="col-xs-12 col-sm-6">
				<h5 class="ml-3">No del cliente: <p class="no-cliente">{{ $distribuidor['numero_afiliacion'] }}</p>
				</h5>

			</div>
			<div class="col-xs-12 col-sm-6">
				<h5 class="ml-3">Telefono fijo: <p class="telefono-fijo">{{ $distribuidor['telefono_fijo'] }}</p>
				</h5>

			</div>

			<div class="col-xs-12 col-sm-6">
				<h5 class="ml-3">Nombre: <p class="nombre">{{ $distribuidor['name']. ' ' . $distribuidor['apellido_paterno']. ' ' . $distribuidor['apellido_materno'] }}</p>
				</h5>

			</div>
			<div class="col-xs-12 col-sm-6">
				<h5 class="ml-3">Móvil: <p class="movil">{{ $distribuidor['celular'] }}</p>
				</h5>
			</div>

			<div class="col-xs-12 col-sm-6">
				<h5 class="ml-3">Correo: <p class="correo">{{ $distribuidor['email'] }}</p>
				</h5>

			</div>
		</div>
	</div>

</div>

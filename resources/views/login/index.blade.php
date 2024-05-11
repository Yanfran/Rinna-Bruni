@extends('layouts.home')
@section('css')
@stop

@section('contenido')
	@include('vendor.flash.message')
	<div class="flex-center position-ref full-height">

		<div class="content">
			<div class="title m-b-md">
				<img class="logo-index" src="{!! url('uploads/logos/').'/' !!}{{ $oficina->getLogo() }}">
				<br>

			</div>
			<div class='seleccion-emp'>
				<span>Seleccione Oficina o Sucursal</span>
			</div>

			<div class="links">
				<ul>
					@foreach($empresas as $k)

						<a href="{{ route('login', ['id' => $k->getID()]) }}" title="">
							<li class='contenedor-img'>
								<img class='imagen-empresa'
								     src="{!! url('uploads/logos/').'/' !!}{{ $k->getLogo() }}">{{ $k->getNombre() }}

							</li>
						</a>
					@endforeach
				</ul>
			</div>
			@if(session()->has('success'))
				<div class="alert alert-success">
					<strong>{{ session()->get('success') }}</strong>
				</div>
			@elseif(session()->has('error'))
				<div class="alert alert-danger">
					<strong>{{ session()->get('error') }}</strong>
				</div>
			@endif
		</div>

	</div>


@stop
@section('js')
	<script>

	</script>
@stop

@extends('layouts.login')
@php
	$x = 0;
	$class = 'active';
	$class_slider = 'active';

@endphp
@section('css')
	<style type="text/css" media="screen">

		.login-block {
			background: {{ $empresa->getColorSecundario() }};
			background: -webkit-linear-gradient(to bottom, {{ $empresa->getColorSecundario() }}, {{ $empresa->getColorSecundario() }});
			background: linear-gradient(to bottom, {{ $empresa->getColorSecundario() }}, {{ $empresa->getColorSecundario() }});

		}

		.login-sec h2 {
			color: {{ $empresa->getColorPrimario() }};
			font-size: 22px;
			font-weight: 600;
			text-align: center;
		}

		.primario {
			background: {{ $empresa->getColorPrimario() }};
		}
	</style>
@stop
@section('contenido')




	<section class="login-block">
@if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger">
            {{ session()->get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
    </div>
    @endif

		<div class="container">
			<div class="row">
				<div class="col-md-4 login-sec">
					<h2>
						<img class="img-logo"
						     src="{!! url('uploads/logos/').'/' !!}{{ $empresa->getLogo() }}"
						     alt=""><br>
						<span>{{ trans('registro.titulo_panel') }}</span>
					</h2>

					<form method="POST" action="{{ route('login-post') }}" aria-label="{{ __('Login') }}">
						@csrf


						<input type="hidden" name="empresas_id" value="{{ $empresa->getID() }}">
						<div class="form-group ">
							<label for="email" class="text-uppercase">CORREO ELECTRÓNICO / USUARIO</label>
							<input id="email" type="text"
							       class="form-control" name="email"
							       value="{{ old('email') }}" required autofocus>

						</div>
						<div class="form-group">
							<label for="password" class="text-uppercase">{{ trans('registro.label_pass') }}</label>
							<input id="password" type="password"
							       class="form-control"
							       name="password">

						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="remember"
								       id="remember" {{ old('remember') ? 'checked' : '' }}>

								<label class="form-check-label" for="remember">
									{{ trans('registro.label_remember') }}
								</label>
							</div>
						</div>
						{{-- @if(!empty($empresa))
							<div class="form-group">
								<a class="btn btn-link pull-left"
								   href="#">
									{{ trans('registro.label_registrate') }}
								</a>
							</div>
						@endif --}}
						<div class="form-group">

							<div style="margin-left: -13px !important;">
								@if(isset($empresa))
									<a class="btn btn-link"
										href="{{ route('password_by_com',['id' => $empresa->id]) }}">
										{{ trans('registro.label_pass_resolve') }}
									</a>
								@else
									<a class="btn btn-link float-l" href="{{ route('password.request') }}">
										{{ trans('registro.label_pass_resolve') }}
									</a>
								@endif
							</div>
							

							<button type="submit" class="btn btn-primary primario pull-right" style="margin-top: -40px;">
								{{ trans('registro.btn_entrar') }}
							</button>
						</div>
					</form>
				</div>

				<div class="col-md-8 banner-sec">
					<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							@foreach($slider as $k)
								<li data-target="#carouselExampleIndicators" data-slide-to="{{ $x }}"
								    class="{{ $class }}">
								</li>
								@php $x++; $class = 'active' @endphp
							@endforeach


						</ol>
						<div class="carousel-inner" role="listbox">
							@foreach($slider as $key)
								<div class="carousel-item {{ $class_slider }}">
									<img alt="First slide" class="d-block img-fluid"
									     src="{{  asset($key->getImagen()) }}">
									<div class="carousel-caption d-none d-md-block">
										<div class="banner-text">
											<h2>
												{{ $key->getTitulo() }}
											</h2>
											<h6>{{ $key->getDescripcion() }}</h6>
										</div>

									</div>
								</div>
								@php $class_slider = null; @endphp
							@endforeach

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

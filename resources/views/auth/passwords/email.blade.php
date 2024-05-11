@extends('layouts.login')
@php
	$x = 0;
	$class = 'active';
	$class_slider = 'active';
  //if(!isset($errors))$errors = new Collection();
@endphp
@section('css')
	<style media="screen" type="text/css">
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
		
		.form-group.imput {
			margin-top: 80px !important;
			margin-bottom: 100px;
		}
	</style>
@stop
@section('contenido')
	<section class="login-block">
		<div class="container">
			<div class="row">
				<div class="col-md-4 login-sec">
					<h2>
						<img alt="" class="img-logo" src="{!! url('uploads/logos/').'/' !!}{{ $empresa->getLogo() }}">
						<br>
							{{trans('registro.label_recuperacion_tittle') }}
						</br>
						</img>
					</h2>
					@if (session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif
					@include('vendor.flash.message')
					
					<form action="{{ route('password.email') }}"
					      aria-label="{{trans('registro.label_recuperacion_tittle') }}"
					      method="POST">
						@csrf
						<div class="form-group imput ">
							<label class="text-uppercase" for="email">
								{{trans('registro.label_email') }}
							</label>
							<input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
							       id="email"
							       name="email"
							       required=""
							       type="email"
							       value="{{ old('email') }}">
							@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
                                <strong>
                                    {{ $errors->first('email') }}
                                </strong>
                            </span>
								@endif
								</input>
						</div>
						<div class="form-group">
							<div class="pull-left">
								<a href="{{ route('login', ['id' => $empresa->getID()]) }}" title="">
									{{trans('registro.btn_regresar') }}
								</a>
							</div>
							<div class="pull-right">
								<button class="btn btn-primary primario" type="submit">
									{{trans('registro.label_enviar') }}
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-8 banner-sec">
					<div class="carousel slide" data-ride="carousel" id="carouselExampleIndicators">
						<ol class="carousel-indicators">
							@foreach($slider as $k)
								<li class="{{ $class }}"
								    data-slide-to="{{ $x }}"
								    data-target="#carouselExampleIndicators">
								</li>
								@php $x++; $class = 'active' @endphp
							@endforeach
						</ol>
						<div class="carousel-inner" role="listbox">
							@foreach($slider as $key)
								<div class="carousel-item {{ $class_slider }}">
									<img alt="First slide"
									     class="d-block img-fluid"
									     src="{{  asset($key->getImagen()) }}">
									<div class="carousel-caption d-none d-md-block">
										<div class="banner-text">
											<h2>
												{{ $key->getTitulo() }}
											</h2>
											<h6>
												{{ $key->getDescripcion() }}
											</h6>
										</div>
									</div>
									</img>
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

@extends('layouts.login')
@section('css')
	@if(isset($empresa))
		<style type="text/css" media="screen">
			.container.login-block.reset {
				background: #cacaca !important;
			}
			
			.row.justify-content-center.login-block.reset {
				background: #cacaca !important;
			}
		</style>
	@endif
@stop
@section('contenido')
	<div class="container login-block reset" style="background: #cacaca !important;">
		<div class="row justify-content-center login-block reset" style="background: #cacaca !important;">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header login-sec">
						<h2 class="text-center">
							@if(isset($empresa))
								<img class="img-logo"
								     src="{!! url('uploads/logos/').'/' !!}{{ $empresa->getLogo() }}"
								     alt="">
							@endif
							{{trans('registro.reset_pass') }}
						</h2>
					</div>
					<div class="card-body">
						{{-- @include('vendor.flash.message') --}}
				

						@if (session('success'))
							<div class="alert alert-success">
								{{ session('success') }}
							</div>
						@endif
						
						<form method="POST" action="{{ route('password.request') }}"
						      aria-label="{{trans('registro.reset_pass') }}">
							@csrf
							<input type="hidden" name="token" value="{{ $token }}">
							<div class="form-group row">
								<label for="email"
								       class="col-md-4 col-form-label text-md-right">{{trans('registro.label_email') }}</label>
								<div class="col-md-6">
									<input id="email" type="email"
									       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
									       name="email" value="{{ $email ?? old('email') }}" required autofocus>
									@if ($errors->has('email'))
										<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="password"
								       class="col-md-4 col-form-label text-md-right">{{trans('registro.label_pass') }}</label>
								<div class="col-md-6">
									<input id="password" type="password"
									       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
									       name="password" required>
									@if ($errors->has('password'))
										<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="password-confirm"
								       class="col-md-4 col-form-label text-md-right">{{trans('registro.label_confirm_pass') }}</label>
								<div class="col-md-6">
									<input id="password-confirm" type="password" class="form-control"
									       name="password_confirmation" required>
								</div>
							</div>
							<div class="form-group row mb-0">
								<div class="col-md-6 offset-md-4">
									<button type="submit" class="btn btn-primary primario">
										{{trans('registro.btn_retear') }}
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

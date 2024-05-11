@extends('layouts.login')
@section('css')
    @if(isset($empresa))
        <style media="screen"
               type="text/css">
            img.img-logo {
                margin-top: 15px;
                width: 100%;
            }
            
            body {
                margin-top: -40px;
            }
            
            .register {
                background: {{ $empresa->getColorSecundario() }};
            }
            
            .btnRegister {
                background: {{ $empresa->getColorPrimario() }};
            }
            
            a.btn-entrar {
                background: white;
                padding: 10px 40px;
                color: #4d4d4e;
                font-size: 14px;
                font-weight: 600;
                border-radius: 20px;
                text-decoration: none;
            }
            
            .form-group {
                margin-bottom: 1.5rem;
            }
            
            .error {
                font-size: 11px;
                position: absolute;
            }
            
            span.invalid-feedback {
                position: absolute;
                font-size: 11px;
            }
            
            span.msg-estado {
                font-size: 10px;
                position: absolute;
                color: #909090;
            }
            
            label.etiqueta {
                position: absolute;
                margin-top: -18px;
                font-size: 13px;
                color: grey;
            }
        </style>
    @endif
@stop
@section('contenido')

    <div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img alt=""
                     class="img-logo"
                     src="{!! url('uploads/logos/').'/' !!}{{ $empresa->getLogo() }}">
                </img>
                <h3>
                    {{ trans('registro.label_welcome') }}
                </h3>
                <p>
                    {{ trans('registro.label_msg_welcome') }} {{ $empresa->getNombre() }}
                </p>
                <a class="btn-entrar"
                   href="{{ route('login', ['id' => $empresa->getID()]) }}"
                   title="">
                    {{ trans('registro.btn_entrar') }}
                </a>
                <br/>
            </div>
            <div class="col-md-9 register-right">
                <div class="tab-content"
                     id="myTabContent">
                    <div aria-labelledby="home-tab"
                         class="tab-pane fade show active"
                         id="home"
                         role="tabpanel">
                        <h3 class="register-heading">
                            {{ trans('registro.label_registro') }}
                        </h3>
                                 @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                                       
                            <form action="{{ route('register') }}"
                              aria-label="{{ trans('empresas.label_register') }}"
                              autocomplete="off"
                              enctype="multipart/form-data"
                              method="POST">
                            @csrf
                            <input id="empresa_id"
                                   name="empresa_id"
                                   type="hidden"
                                   value="{{ $empresa->getID() }}">
                            <div class="row register-form">
                                @include('auth.form.datosCuenta')
                                @include('auth.form.datosContacto')
                                @include('auth.form.datosGenerales')
                                @include('auth.form.btn')
                            </div>
                            </input>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

    <script>
        $(document).ready(function () {
            $('.phone').mask('(00) 000 000 0000');
            $('.ext').mask('00000');
            $('.movil').mask('(000) 000 000 ' + '{{\App\Http\Controllers\Functions::RellenarCeros('0',42)}}');
        });
        $('select[name="pais_id"]').on('change', function () {
            var paisId = $(this).val();
            if (paisId && paisId != "{{ trans('registro.input_select_pais') }}") {
                $.ajax({
                    url: '/ajax/estado/' + paisId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#estado_id').empty();
                        $('#estado_id').append('<option value="">{{ trans("registro.input_select_estado") }} *</option>');
                        if (data.length == 0) {
                            $('#tag').append(`{{ trans("registro.label_error_estado") }}  </small>
                                <small>
                                 <a href="/estado" title="">{{ trans("registro.label_administrar_estado") }}</a>`);
                        }
                        $.each(data, function (key, value) {
                            $('#estado_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                $('#estado_id').empty();
                $('#estado_id').append('<option value="">{{ trans("registro.input_select_estado") }} *</option>');
                $('#tag').empty();
            }
        });
        $('#giro_id').on('change', function () {
            var giroId = $(this).val();
            if (giroId && giroId != "{{ trans('registro.input_select_giro') }}") {
                $.ajax({
                    url: '/ajax/subgiro/' + giroId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#subgiro_id').empty();
                        $('#subgiro_id').append('<option value="">{{ trans("registro.input_select_subgiro") }} *</option>');
                        if (data.length == 0) {
                            $('#tag_giro').append(`{{ trans("registro.label_error_subgiro") }}  </small>
                                    <small>
                                    <a href="/subgiro" title="">{{ trans("registro.label_administrar_subgiro") }}</a>`);
                        }
                        $.each(data, function (key, value) {
                            $('#subgiro_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                $('#subgiro_id').empty();
                $('#subgiro_id').append('<option value="">{{ trans("registro.input_select_subgiro") }} *</option>');
                $('#tag_giro').empty();
            }
        });
        $('#password').blur(function () {
            var password = $('#password').val();
            if (password.length < 8) {
                $('#error_pass_p').html('<label class="text-danger">{{ trans("registro.label_error_pass_8") }}</label>');
                $('#password').addClass('is-invalid');
                $('#register').attr('disabled', 'disabled');
            } else {
                $('#error_pass_p').empty();
                $('#password').removeClass('is-invalid');
                $('#password').addClass('is-valid');
                $('#register').attr('disabled', false);
            }
        });
        $('#password-confirm').blur(function () {
            var password = $('#password').val();
            var confirm = $('#password-confirm').val();
            if (password != confirm) {
                $('#error_pass').html('<label class="text-danger">{{ trans("registro.label_error_pass") }}</label>');
                $('#password-confirm').addClass('is-invalid');
                $('#password').addClass('is-invalid');
                $('#register').attr('disabled', 'disabled');
            } else {
                if (password.length < 8) {
                    $('#error_pass').empty();
                    $('#error_pass_p').html('<label class="text-danger">{{ trans("registro.label_error_pass_8") }}</label>');
                    $('#password').addClass('is-invalid');
                    $('#register').attr('disabled', 'disabled');
                    return false;
                } else {
                    $('#error_pass').empty();
                    $('#password-confirm').removeClass('is-invalid');
                    $('#password').removeClass('is-invalid');
                    $('#password-confirm').addClass('is-valid');
                    $('#password').addClass('is-valid');
                    $('#register').attr('disabled', false);
                }
            }
        });
        $('#email').blur(function () {
            var error_email = '';
            var email = $('#email').val();
            var _token = $('input[name="_token"]').val();
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!filter.test(email)) {
                $('#error_email').html('<label class="text-danger">{{ trans("registro.label_email_invalid") }}</label>');
                $('#email').addClass('is-invalid');
                $('#register').attr('disabled', 'disabled');
            } else {
                $.ajax({
                    url: '/ajax/email/' + email,
                    type: "GET",
                    success: function (result) {
                        if (result == 'unique') {
                            $('#error_email').html('<label class="text-success">{{ trans("registro.label_email_disponible") }}</label>');
                            $('#email').removeClass('is-invalid');
                            $('#email').addClass('is-valid');
                            $('#register').attr('disabled', false);
                        } else {
                            $('#error_email').html('<label class="text-danger">{{ trans("registro.label_email_no_disponible") }}</label>');
                            $('#email').addClass('is-invalid');
                            $('#register').attr('disabled', 'disabled');
                        }
                    }
                })
            }
        });
    </script>
@endsection

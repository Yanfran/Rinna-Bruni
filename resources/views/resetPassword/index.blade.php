@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Restrablecer contraseña:</h3>

        </div>

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oops!</strong> Hubo algunos problemas con tus datos.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="panel-body">

            <form action="{{ route('resetPassword.update') }}" method="POST" autocomplete="off">
                @csrf

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <input type="hidden" name="password-actual" value="{{ $data->password }}">
                        <input type="hidden" name="userId" value="{{ $data->id }}">
                        <div class="col-xs-12 col-sm-12 col-md-12">                            
                            <div class="form-group">
                                <strong>Clave de acceso anterior* :</strong>
                                <input type="password" name="password-actual-verificar" id="password-1"
                                    class="form-control {{ $errors->has('password-actual-verificar') ? 'is-invalid' : '' }}"
                                    placeholder="Clave de acceso anterior"
                                >
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-show show-password-1" data-target="#password-1">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>                            
                        </div>                   
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nueva clave de acceso* :</strong>
                                <input type="password" name="nuevo-password" id="password-2"
                                    class="form-control {{ $errors->has('cnuevo-password') ? 'is-invalid' : '' }}"
                                    placeholder="Nueva clave de acceso"
                                >
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-show show-password-2" data-target="#password-2">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>                   
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Verificar clave de acceso* :</strong>
                                <input type="password" name="confirm-password" id="password-3"
                                    class="form-control {{ $errors->has('confirm-password') ? 'is-invalid' : '' }}"
                                    placeholder="Verificar clave de acceso"
                                >
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-show show-password-3" data-target="#password-3">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>                   
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                        </div>
                    </div>
                </div>

            </form>             
        </div>
    </div>


<p class="text-center text-primary"><small>-</small></p>
@endsection

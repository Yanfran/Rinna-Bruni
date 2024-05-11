
<div class="panel panel-default">
    <div class="panel-heading">

        <h3>{{ $title }}
            @can($ruta . '-list')
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route($ruta . '.index') }}"> Regresar</a>
                </div>
            @endcan
        </h3>

    </div>

    <div class="panel-body">
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

        <form action="{{ route($ruta . '.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>{{ trans('empresas.label_estatus') }}</label>
                        <select name="estatus" class="form-control" id="estatus">
                            <option class="form-control" value="0">{{ trans('empresas.select_inactivo') }}
                            </option>
                            <option selected class="form-control" value="1">{{ trans('empresas.select_activo') }}
                            </option>

                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>

        </form>
    </div>
</div>

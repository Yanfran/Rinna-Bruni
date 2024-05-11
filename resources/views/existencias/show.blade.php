@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver existencia
                @can('existencias-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('existencias.index') }}"> Regresar</a>
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

            <form action="{{ route('existencias.update', 1) }}" method="POST">
                @csrf

                @method('PUT')




                    <div class="row">
                        @foreach ($existencias as $key => $existencia)
                        @if($key == 0)
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nombre de producto:</strong>
                                    <input  name="id[{{ $key }}]" class="form-control" type="hidden" value="{{ $existencia->id }}">
                                    <input  name="product_id" class="form-control" type="hidden" value="{{ $existencia->product_id }}">
                                    <input readonly
                                           class="form-control"
                                           type="text"
                                           value="{{ $existencia->getProduct()->codigo }}{{" - " . $existencia->getProduct()->estilo}}@if($existencia->getProduct()->linea){{" - " . $existencia->getProduct()->linea->nombre}}@endif{{" - " . $existencia->getProduct()->nombre_corto}}">
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Tienda* :</strong>
                                @if($existencia->getTienda($existencia->tienda_id) )
                                    <input class="form-control" type="text" value="{{ $existencia->getTienda($existencia->tienda_id)->nombre }}" readonly>
                                @else
                                    <input class="form-control" type="text" value="No encontrada" readonly>
                                @endif

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Cantidad disponible* :</strong>
                                <input class="form-control" type="text" value="{{ $existencia->cantidad }}" readonly>

                            </div>
                        </div>

                        @endforeach
                </div>


        </div>

        </form>
    </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

        });

        function add() {
            //nada
        }
    </script>

@stop

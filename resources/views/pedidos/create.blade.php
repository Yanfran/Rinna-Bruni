@extends('layouts.app')

@section('css')
    <style>
        div#myInputautocomplete-list {
            padding-bottom: 15px;
            background: #ededed;
            padding-left: 10px;
            padding-top: 15px;
        }

        div#myInputautocomplete-list-producto {
            padding-bottom: 15px;
            background: #ededed;
            padding-left: 10px;
            padding-top: 15px;
        }

        span.modificar_link {
            padding-left: 10px;
            color: blue;
            text-decoration: underline;
        }

        span.eliminar_link {
            padding-left: 10px;
            color: blue;
            text-decoration: underline;
        }

        span#estatus_pedido {
            background: #abe5ab;
            padding: 1px 10px;
            border-radius: 8px;
        }

        th.total_pagar {
            font-size: 18px;
        }

        td#total_a_pagar {
            font-size: 18px;
            font-weight: 700;
        }

        a.link-blue {
            color: blue;
            text-decoration: revert;
        }

        .box-vaucher {
            width: 450px;
            height: 300px;
            background: #CCC;
            overflow: hidden;
        }

        .box-vaucher img {
            width: 100%;
            height: auto;
        }

        @supports (object-fit: cover) {
            .box-vaucher img {
                height: 100%;
                object-fit: cover;
                object-position: center center;
            }
        }
    </style>
@stop
@section('contenido')
    @include('pedidos.elementos.spinner')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Agregar pedido
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('pedidos.index') }}"> Regresar</a>
                </div>
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

            <form id="form-pedido" action="{{ route('pedidos.solicitar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('pedidos.elementos.buscador_cliente')
                @include('pedidos.elementos.buscador_producto')

            </form>

        </div>
    </div>
    @include('pedidos.elementos.modals')

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@include('pedidos.elementos.js')

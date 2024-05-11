@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.2/dist/min/dropzone.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.2/dist/min/dropzone.min.js"></script>
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
        .input-group {
            display: flex !important;
        }
    </style>
@stop

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Agregar pedido
                {{-- @can('pedido-list') --}}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('pedidos.index') }}"> Regresar</a>
                </div>
                {{-- @endcan --}}
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

            <form action="{{ route('pedidos.solicitar') }}" method="POST">
                @csrf

                @include('pedidosUsuario.elementos.buscador_cliente')
                @include('pedidosUsuario.elementos.buscador_producto')


            </form>


                <div id="wallet_container" class="d-none"></div>



        </div>
    </div>
    @include('pedidosUsuario.elementos.modals')

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@include('pedidosUsuario.elementos.js')

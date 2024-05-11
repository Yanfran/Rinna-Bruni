<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

        .portada {
            width: 100%;
            height: 100%;
        }
        .img-producto {
            position: relative;
            top: 0;
            width: 100%;
            max-width: 350px;
            height: 400px;
            /*display: block;*/
            /*margin: 0 auto;*/
        }
        .row-table {
            margin:0;
            width: 100%;
        }
        .col-table {
            width:50%;
            margin:0px;
            padding: 10px;
        }
        #container-product {
            height: 460px;
            background-repeat: no-repeat;
            background-size: auto 460px;
            background-position: center top;
            background-attachment: fixed;

            /*background-size: 100% 100%;*/
        }
        .saltodepagina {
            page-break-after: always;
        }
        .container-info {
            margin-top: 300px;
            padding: 0px;
            background-color: black;
            color: white;
            width: 40%;
        }
        .info {
            padding:5px;
        }
        .container-precio {
            margin-top: 5px;
            margin-left: 240px;
            background-color: #FFD400;
            color: black;
            width: 35%;
            border-radius: 15 0 15 0;
            transform: rotate(-8deg);
        }
        .precio {
            padding:0px;
            font-size: 36px;
            text-align: center;
        }
        #titulo-pagina {
            width: 50%;
        }
        .letra-titulo {
            font-size: 46px;
            text-align: right;
            font-weight: lighter;
        }
        .letra-subtitulo {
            margin-top:-10px;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }
        #marca{
            width: 50%;
            text-align: right;
        }
        .letra-marca {
            padding:5px;
            font-size: 36px;
        }
        .letra-linea {
            padding:5px;
            font-size: 14px;
        }
        .letra-tallas {
            color: rgba(255, 255, 255, 0.601);
            font-size: 14px;
        }
        hr {
            padding: 0;
            margin: 0;
        }
        .paginas-productos{
            height: 100%;
            padding: 0;
            margin: 0;
            background-image: url('images/background.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
        html {
            padding: 0;
            margin: 0;

        }

    </style>
</head>
<body>

    {{-- Portada --}}
    <div style="page-break-after: always;">
        @if( trim($catalogo->url_imagen_portada) !== '' )
            <img class="portada"
            src="{{ storage_path('app/public/catalogos/' . $catalogo->id . '/') . $catalogo->url_imagen_portada }}">
        @endif
    </div>

    <div style="page-break-after: always;" class="paginas-productos">

        <table style="padding:0px 0px 0px 0px;" border="0" cellspacing="0" width="100%">
            {{-- @foreach ($catalogo->productos->chunk(2) as $key => $group) --}}
            @foreach (array_chunk($catalogo->productos, 2) as $key => $group)
                    @if ($key % 2 == 0)
                        <tr>
                            <td id="titulo-pagina">
                                <div class="letra-titulo"><i>LIQUIDACION</i></div>
                                <div class="letra-subtitulo"><strong>DE FIN DE TEMPORADA</strong></div>
                            </td>
                            <td id="marca">
                                <div class="letra-marca">RINNA</span>
                            </td>
                        </tr>
                    @endif
                    {{-- </tr> --}}
                    <tr class="row-table">
                        @foreach ($group as $item)
                            <td class="col-table">

                                <div style="background-image: url('{{url('storage/products_imgs_catalogs/' . $item['url_imagen_catalogo']) }}')" id="container-product">
                                    {{-- @isset($item->primeraImagen)
                                        <img class="img-producto" src="{{ asset('galeria/' . $item->primeraImagen->ruta) }}">
                                        @endisset --}}
                                    <div class="container-precio">
                                        <div class="precio">
                                            <strong><i>${{ $item['precio']}}</i></strong>
                                        </div>
                                    </div>

                                    <div  class="container-info">
                                        <div class="infom letra-linea">
                                            {{-- <strong><i>{{ $item->id}} - {{ $item->linea->nombre}}</i></strong> --}}
                                            <strong><i>{{$item['estilo']}}</i></strong>
                                        </div>
                                        <hr>
                                        <div class="info">
                                            <i>{{$item['codigo'] && substr($item['codigo'], -1) === '-' ? substr($item['codigo'], 0, -1) : $item['codigo']}}</i>
                                            <br>
                                            <span class="letra-tallas">
                                                {{ $item['tallasString'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        @endforeach

                    </tr>
            @endforeach
        </table>
    </div>

    {{-- Portada Final --}}
    <div>
        @if( trim($catalogo->url_imagen_final) !== '' )
            <img class="portada"
            src="{{ storage_path('app/public/catalogos/' . $catalogo->id . '/') . $catalogo->url_imagen_final }}">
        @endif
    </div>

</body>
</html>

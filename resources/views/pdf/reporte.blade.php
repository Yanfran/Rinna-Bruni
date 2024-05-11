
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0">    
    <title> Reporte </title>      
    <style> 
        body{
            font-family: Arial, Helvetica, sans-serif;
        }
        .titulo-33{float: left; margin-left: 100px; margin-top: 50px;}
        .titulo-4{float: left; margin-right: 70px; margin-left: 40px; margin-top: -15px;}
        .sessio-2{position: absolute; margin-top: 100px;}
        .descricion{height: 10px; color: #000000}
        .sessio-3{position: absolute; margin-left: 330px; margin-top: 100px;}        
        .sessio-4{position: absolute; margin-left: 550px; margin-top: 100px;}
        .table{position: absolute; margin-top: 50px; width: 100%;}
        table {font-size: 12px; font-family: arial, sans-serif; border-collapse: collapse; width: 100%;}
        td, th {border: 1px solid #dddddd; text-align: left; padding: 8px;}
        tr:nth-child(even) {background-color: #dddddd;}
        .texto-precio-total {
            text-align: right;
            margin-right: 20px;
            margin-top: 50px;
        }
        img {
            width: 100px;
            height: 150px;
        }
        .header {
            text-align: left;
        }
        .header tr td {
            border: 0px;
        }
        .body tr td {
            border: 0px;
        }
        .body tr:nth-child(even) { 
            background-color: #fff; 
        }
        .body p {
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body>

    <table class="header">
        <tbody>
            <tr>
                <td>
                    <img src="{{ public_path('uploads/logos/ripsqnS9_400x400.jpg') }}">
                </td>
                <td>
                    <h2>Pedido N°. {{ $pedido->id ?? '' }}</h2>
                </td>
                <td>
                    <h2>| {{ $pedido->id ?? '' }}*</h2>
                </td>
                <td>
                    <h2>PEDIDO INTERNET</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="body">
        <tbody>
            <tr>
                <td>
                    <p class="descricion">
                        <b>Nombre:</b>
                        @if(isset($distribuidor))
                            <span>
                                {{ $distribuidor->name}} {{ $distribuidor->apellido_paterno}} {{$distribuidor->apellido_materno}}
                            </span>
                        @else
                            <span>
                                No disponible
                            </span>
                        @endif
                    </p>
                </td>
                <td>
                    <p class="descricion"><b>ID Cliente:</b> <span>{{ $distribuidor->id ?? 'No disponible' }}</span></p>
                </td>
                <td>
                    <p class="descricion"><b>Forma de envio:</b> <span>Recoger en Tienda</span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="descricion"><b>Calle:</b> <span>{{ $direccion->calle ?? 'No disponible'}}</span></p>
                </td>
                <td>
                    <p class="descricion"><b>Número:</b> <span></span></p>
                </td>
                <td>
                    <h2 class="titulo-33">{{ $pedido->id ?? 'No disponible' }}</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="descricion"><b>Colonia:</b> <span>{{ $localidad->nombre ?? 'No disponible' }}</span></p>
                </td>
                <td>
                    <p class="descricion"><b>CP:</b> <span>{{ $direccion->cp ?? 'No disponible' }}</span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="descricion"><b>Municipio:</b> <span>{{ $municipio->nombre ?? 'No disponible' }}</span></p>
                </td>
                <td>
                    <p class="descricion"><b>Estado:</b> <span>{{ $estado->nombre ?? 'No disponible' }}</span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="descricion"><b>Telefono:</b> <span>{{ $direccion->celular ?? 'No disponible' }}</span></p>
                </td>
                <td>
                    <p class="descricion"><b>Email:</b> <span>{{ $estado->correo ?? 'No disponible' }}</span></p>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="table">
        <table>
            <tr>
              <th>Identificador</th>
              <th>Estilo</th>
              <th>Marca</th>
              <th>Color</th>
              <th>Talla</th>
              <th>Monto Total</th>
              <th>Descuento</th>
              <th>Monto Neto</th>
            </tr>

            @if($pedido->productosPedidos)
                @foreach ($pedido->productosPedidos as $item)
                <tr>
                    <td>{{ $item->product->codigo ?? 'No disponible' }}</td>
                    <td>{{ $item->product->estilo ?? 'No disponible' }}</td>
                    <td>{{ $item->product->marca->nombre ?? 'No disponible' }}</td>
                    <td>{{ $item->product->color ?? 'No disponible' }}</td>
                    <td>{{ $item->product->talla ?? 'No disponible' }}</td>
                    <td>${{ number_format($item->monto, 2) }}</td>
                    <td>- ${{ number_format($item->descuento, 2) }}</td>
                    <td>${{ number_format($item->neto, 2) }}</td>
                </tr>
                @endforeach
            @endif

        </table>

        <div class="texto-precio-total">Total en Articulos: ${{ number_format($pedido->monto_total, 2)}}</div>
        
        <div class="texto-precio-total">Descuento: - ${{ number_format($pedido->monto_descuento_cliente, 2)}}</div>
        
        <div class="texto-precio-total">Total a pagar: ${{ number_format($pedido->monto_neto, 2)}}</div>
    </div>

    </body>
</html>

       
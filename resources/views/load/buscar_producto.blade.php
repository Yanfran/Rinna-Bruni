@foreach ($productos as $producto)
    <div class="docname">
        <div class="search-content-2" onclick="cargar_datos_producto({{ json_encode($producto) }})">
            <p style="padding-left: 15px;" onmouseover="this.style.backgroundColor='white'; this.style.cursor='pointer';" onmouseout="this.style.backgroundColor='transparent';">
                {!! str_ireplace($key, '<b>' . $key . '</b>',$producto->nombre_corto) !!}:
                {!! str_ireplace($key, '<b>' . $key . '</b>',$producto->estilo) !!}:
                @if($producto->linea)
                    {!! str_ireplace($key, '<b>' . $key . '</b>', $producto->linea->nombre)!!}
                @endif
                @if($producto->marca)
                    {!! str_ireplace($key, '<b>' . $key . '</b>', $producto->marca->nombre)!!}
                @endif
                {!! str_ireplace($key, '<b>' . $key . '</b>',$producto->codigo) !!}
            </p>
        </div>
    </div>
@endforeach

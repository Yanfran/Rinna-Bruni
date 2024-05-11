@foreach ($clientes as $cliente)
    <div class="docname">
        <div class="search-content-2" onclick="cargar_datos({{ json_encode($cliente) }})">
            <p style="padding-left: 15px;" onmouseover="this.style.backgroundColor='white'; this.style.cursor='pointer';" onmouseout="this.style.backgroundColor='transparent';">
                {!! str_ireplace($key, '<b>' . $key . '</b>', $cliente->numero_afiliacion) !!}:
                {!! str_ireplace($key, '<b>' . $key . '</b>', $cliente->name) !!}
                {!! str_ireplace($key, '<b>' . $key . '</b>', $cliente->apellido_paterno) !!}
                {!! str_ireplace($key, '<b>' . $key . '</b>', $cliente->apellido_materno) !!}
            </p>
        </div>
    </div>
@endforeach

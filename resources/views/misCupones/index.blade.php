@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Mis cupones</h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">
            <table id="empresas" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Monto/Porcentaje</th>
                        <th class="text-center">Cantidad de uso</th>
                        {{-- <th class="text-center">Fecha inicio</th> --}}
                        <th class="text-center">Vence</th>
                                                
                    </tr>
                </thead>
                <tbody>   
                    
                    @php
                        $cupones = \Auth::user()->cupones;
                    @endphp
            
                    @foreach($cupones as $cupon)
                        <tr>
                            <td class="text-center">{{ $cupon->codigo }}</td>
                            <td class="text-center">
                                @if($cupon->monto)
                                    $@money($cupon->monto)
                                @else
                                    {{ $cupon->porcentaje }} %
                                @endif
                            </td>
                            <td class="text-center">{{ $cupon->cantidad_usos }}</td>                            
                            <td class="text-center">{{ $cupon->fecha_fin->format('d-m-y') }}</td>                                                        
                        </tr>
                    @endforeach
                </tbody>

            </table>
            

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

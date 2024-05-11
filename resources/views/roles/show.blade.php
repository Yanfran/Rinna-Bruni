@extends('layouts.app')

@section('contenido')

    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver rol
                {{-- @can('localidad-create') --}}
                <span class="float-r">
                    <a class="btn btn-primary btn-catalogo" href="{{ route('roles.index') }}" title="">
                        Regresar
                    </a>
                </span>
                {{-- @endcan  --}}
            </h3>

        </div>               

        <div class="panel-body">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        {{ $role->name }}
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tr>                            
                                <th>Pantallas</th>                            
                                <th>Ver</th>                            
                                <th>Crear</th>
                                <th>Editar</th>
                                <th>Eliminar</th>                                
                            </tr>

                            @foreach($screenFinal as $item)

                            
                            <tr>                                
                                <td>{{ $item['name'] }}</td>      
                                @foreach($item['details'] as $detail)
                                    <td>
                                        @if(in_array($detail['id'], $rolePermissions))
                                            <label class="text-danger check-roles">                                                
                                                <i class="fas fa-check"></i>                                               
                                            </label>
                                        @else
                                            <span class="text-danger check-roles"><i class="fa fa-times"></i></span>
                                        @endif
                                    </td>
                                @endforeach                            
                            </tr>                                                   
                            
                            @endforeach
                            
            
                            
                        </table>
                    </div>
                </div>
            


                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </div>           


        </div>
    </div>
@endsection

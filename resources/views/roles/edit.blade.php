@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar Rol
                {{-- @can('localidad-create') --}}
                <span class="float-r">
                    <a class="btn btn-primary btn-catalogo" href="{{ route('roles.index') }}" title="">
                        Regresar
                    </a>
                </span>
                {{-- @endcan  --}}
            </h3>

        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel-body">            
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        @if($role->id == 1 OR $role->id == 2 OR $role->id == 3)                                                
                            <input type="text" name="name" value="{{ $role->name }}" placeholder="Name" class="form-control" >                            
                        @else
                        <input type="text" name="name" value="{{ $role->name }}" placeholder="Name" class="form-control" >                        
                        @endif
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
                                        @if(in_array($detail['name'], array_column($item['details'], 'name')))

                                            <label class="check-roles">
                                                <input type="checkbox" name="permission[]" value="{{ $detail['id'] }}" {{ in_array($detail['id'], $rolePermissions) ? 'checked' : '' }} class="name">                                                
                                            </label>
                                            
                                        @else
                                            No
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
            </form>
        </div>
    </div>


    <p class="text-center text-primary"><small>-</small></p>
@endsection

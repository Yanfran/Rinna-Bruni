@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Crear nuevo rol
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
                <strong>Whoops!</strong> Hubo algunos problemas con tu entrada.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel-body">    
            {{-- {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}         --}}
            <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        <input type="text" name="name" placeholder="Name" class="form-control">                                                    
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
                                                <input type="checkbox" name="permission[]" value="{{ $detail['id'] }}" class="name">
                                                {{-- {{ Form::checkbox('permission[]', $detail['id'], false, ['class' => 'name']) }} --}}
                                                {{-- {{ $detail['name'] }} --}}
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
            {{-- {!! Form::close() !!} --}}
            </form>
        </div>
    </div>


    <p class="text-center text-primary"><small>-</small></p>
@endsection

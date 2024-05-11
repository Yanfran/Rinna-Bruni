@extends('layouts.app')

@section('contenido')

    
        <div class="row justify-content-center">

            <div class="col-md-2"></div>

            <div class="col-md-8 centered-text">

                <div class="row">
                    
                    <div class="col-md-12">
                        <h1>Bienvenido</h1>
                    </div>

                    <div class="col-md-12">
                        <h1>{{\Illuminate\Support\Str::words(Auth::user()->name, 2)}}</h1>
                    </div>

                </div>


            </div>

            <div class="col-md-2"></div>
        
        </div>
    

@endsection

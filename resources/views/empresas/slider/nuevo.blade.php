@extends('layouts.app')
@section('css')
<style media="screen" type="text/css">
    .panel {
        width: 99% !important;

    }
    span.color-red {
    color: red;
}
</style>
@stop
@section('contenido')
<div class="panel panel-default">
    <div class="panel-heading">
        <h4>
          @if(empty($slider->getTitulo()))
          {{ trans('empresas.titulo_panel_registro_slider') }}
          @else
          {{ trans('empresas.titulo_panel_edicion_slider') }}
          @endif
          
          
        </h4>
    </div>
    <div class="panel-body">
        <form action="{{ route('sliderStore') }}" aria-label="{{ trans('empresas.label_register') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input  id="id" name="id"  type="hidden" value="{{ $slider->getID() }}">
            <div class="form-group row">
                <div class="col-md-3">
                    <label class="text-md-right" for="titulo_es"><span class="requerido">* </span>
                    {{ trans('empresas.th_titulo_es') }}</label>
                    <input autofocus="" class="form-control" id="titulo_es" name="titulo_es" required="" type="text" value="{{ $slider->getTituloEs() }}">

                </div>
                <div class="col-md-3">
                    <label class="text-md-right" for="titulo_en"><span class="requerido">* </span>
                    {{ trans('empresas.th_titulo_en') }}</label>
                    <input autofocus="" class="form-control" id="titulo_en" name="titulo_en" required="" type="text" value="{{ $slider->getTituloEn() }}">

                </div>
                <div class="col-md-3">
                    <label class="text-md-right" for="estatus">
                    {{ trans('empresas.th_estatus') }}</label>
                    <select name="estatus" class="form-control" id="estatus">
                        @if(!empty($slider->getEstatusValue()))
                        <option  value="{{ $slider->getEstatusValue() }}">{{ $slider->getEstatus() }}</option>
                            @if($slider->getEstatusValue() == 0)
                            <option value="1">{{ trans('empresas.select_activo') }}</option>
                            @else
                            <option  value="0">{{ trans('empresas.select_inactivo') }}</option>
                            @endif
                        @else
                         <option value="">{{ trans('empresas.select_seleccione') }}</option>
                        <option class="form-control" id="estatus" value="0">{{ trans('empresas.select_inactivo') }}</option>
                        <option class="form-control" id="estatus" value="1">{{ trans('empresas.select_activo') }}</option>
                        @endif
                    </select>
                   
               </div>
               <div class="col-md-3">
                    @if(\Auth::user()->rol == 0)
                    <label class="text-md-right" for="empresa_id">
                    {{ trans('empresas.label_empresas') }}</label>
                    <select name="empresa_id" class="form-control">
                        @if(!empty($dataEmpresa))
                          <option value="{{ $dataEmpresa->getID() }}"> {{ $dataEmpresa->getNombre() }}</option>
                        @endif
                        @foreach($empresas as $k)
                          @if(!empty($dataEmpresa))
                            @if($dataEmpresa->getID() != $k->getID())
                                 <option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
                            @endif
                          @else
                            <option value="{{ $k->getID() }}">{{ $k->getNombre() }}</option>
                          @endif
                        @endforeach
                    </select>     
                    @else
                     <label class="text-md-right" for="empresa_id">
                     {{ trans('empresas.label_empresa') }}</label>
                     <input class="form-control" id="empresa_name" name="empresa_name" type="text" value="{{ $empresas->getNombre() }}" readonly="true">
                     <input  id="empresa_id" name="empresa_id"  type="hidden" value="{{ $empresas->getID() }}">
                    @endif
                                 
                </div>
              </div>

            <div class="form-group row">
                  <div class="col-md-6">
                    <label class="text-md-right" for="descripcion_es">
                    {{ trans('empresas.th_descripcion_es') }}</label>
                    <textarea class="form-control" required="" name="descripcion_es">{{ $slider->getDescripcionEs() }}</textarea>

                </div>
                 <div class="col-md-6">
                    <label class="text-md-right" for=descripcion__en">
                    {{ trans('empresas.th_descripcion_en') }}</label>
                    <textarea class="form-control" required="" name="descripcion_en">{{ $slider->getDescripcionEn() }}</textarea>

                </div>
           </div>
         

            <div class="form-group row">
            
               <div class="col-md-12">

                    <label class="text-md-right" for="logo">
                    {{ trans('empresas.th_slider') }}</label>
                    <input type="file" name="imagen" class="dropify" 
                        @if(empty($slider->getImagen()))
                        required=""
                        @endif
                        data-default-file="{{ asset($slider->getImagen()) }}" data-show-remove="false" />
                        <span class="color-red">{{ trans('empresas.img_description') }}</span>
                </div>
            </div>
            <hr>      
            <div class="form-group row mb-0 text-r">
                   <button class="btn-orange pull-right primario" type="submit">
                        {{ trans('empresas.label_register') }}</button>
                    <a href="{{ route('slider') }}" class="btn-black pull-right secundario">
                        {{ trans('empresas.label_regresar') }}</a>
            </div>
        </form>
    </div>
</div>
@stop
@section('js')
<script>
     $(document).ready( function () {
      // Basic instantiation:

    $('.dropify').dropify({
    messages: {
        'default': '{{ trans("empresas.label_drag_drog") }}',
        'replace': '{{ trans("empresas.label_drag_remplace") }}',
        'remove':  '{{ trans("empresas.label_remove") }}',
        'error':   '{{ trans("empresas.label_drag_error") }}'
    }
});
     });
</script>
@stop

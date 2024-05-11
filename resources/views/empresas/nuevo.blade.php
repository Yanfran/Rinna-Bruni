@extends('layouts.app')
@section('css')
<style media="screen" type="text/css">
    .panel {
        width: 99% !important;

    }
    .form-group.row {
    margin-bottom: 40px;
}
.col-md-12.fecha-token {
    margin-top: 15px;
}
textarea.form-control.text-token {
    height: 110px;
    color: #17b304;
    background: #f3f3f3;
}
span.fecha-ec {
    color: red;
}
</style>
@stop
@section('contenido')
<div class="panel panel-default">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="panel-heading">
        <h4>

         Configuración



        </h4>
    </div>

       <form action="{{ route('empresas.store') }}" aria-label="{{ trans('empresas.label_register') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input  id="id" name="id"  type="hidden" value="{{ $empresas->getID() }}">




              @include('empresas.form_empresa.datosConfig')




      </form>

</div>
@stop
@section('js')
<script>

     $(document).ready( function () {
      // Basic instantiation:
        $('#color_1').colorpicker({
            colorSelectors: {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#FF0000',
                'default': '#777777',
                'primary': '#337ab7',
                'success': '#5cb85c',
                'info': '#5bc0de',
                'warning': '#f0ad4e',
                'danger': '#d9534f'
            }
        });
        $('#color_2').colorpicker({
            colorSelectors: {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#FF0000',
                'default': '#777777',
                'primary': '#337ab7',
                'success': '#5cb85c',
                'info': '#5bc0de',
                'warning': '#f0ad4e',
                'danger': '#d9534f'
            }
        });


    $('.dropify').dropify({
    messages: {
        'default': '{{ trans("empresas.label_drag_drog") }}',
        'replace': '{{ trans("empresas.label_drag_remplace") }}',
        'remove':  '{{ trans("empresas.label_remove") }}',
        'error':   '{{ trans("empresas.label_drag_error") }}'
    }
});
     });


$('#checkCfdi').change(function(){


    if($('#checkCfdi').is(':checked')){


  @if($empresas->getVisivilidadCfdi() == 0)
             $.confirm({
                   title: '{{ trans("empresas.js_cfdi") }}',
                   content: '{{ trans("empresas.js_cfdi_con") }}',
                   buttons: {
                       procesar: {
                           text: '{{ trans("confirmaciones.continuar") }}',
                           btnClass: 'btn-green',
                           keys: ['enter', 'shift'],
                           action: function() {

                              //document.getElementById('form-send').submit();
                           }
                       },
                       cancelar: {
                           text: '{{ trans("confirmaciones.cancelar") }}',
                           //btnClass: 'btn-green',
                           action: function() {

                            $('#checkCfdi')[0].checked = false;


                           }
                       }
                   }
               });
    @endif



    }else{

      @if($empresas->getVisivilidadCfdi() == 1)

       $.confirm({
                   title: '{{ trans("empresas.js_cfdi") }}',
                   content: '{{ trans("empresas.js_cfdi_con_false") }}',
                   buttons: {
                       procesar: {
                           text: 'Continuar',
                           btnClass: 'btn-green',
                           keys: ['enter', 'shift'],
                           action: function() {

                              //document.getElementById('form-send').submit();
                           }
                       },
                       cancelar: {
                           text: 'Cancelar',
                           //btnClass: 'btn-green',
                           action: function() {

                            $('#checkCfdi')[0].checked = true;


                           }
                       }
                   }
               });
      @endif


    }




});
</script>
@stop

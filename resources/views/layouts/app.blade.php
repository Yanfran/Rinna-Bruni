<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') </title>

    <link href="{{ asset('css/boopstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
        integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <link href="{{ asset('plugins/color/css/bootstrap-colorpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/phone/css/bootstrap-formhelpers.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/dropi/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/easy-autocomplete.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/themes/default.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />


    {{-- Carousel --}}
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.green.min.css" integrity="sha512-C8Movfk6DU/H5PzarG0+Dv9MA9IZzvmQpO/3cIlGIflmtY3vIud07myMu4M/NTPJl8jmZtt/4mC9bAioMZBBdA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
            crossorigin="anonymous">

    {{-- Carousel --}}

    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" rel="stylesheet" type="text/css">
    <script>
        window.token = "{!! csrf_token() !!}";

    </script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    @php

    $primario =     \App\Helpers\GlobalHelper::getEmpresa(1)->colorPrimario;
    $secundario =   \App\Helpers\GlobalHelper::getEmpresa(1)->colorSecundario;
    @endphp
    <style type="text/css" media="screen">
        .header-cart {
            margin-top: -10px;
            margin-right: -10px;
            text-align: center;
            height: 65px;
        }

        @if ($primario != null)
            .primario,
            #sidebar ul li a:hover {
                background: {!! $primario !!};
            }
        @endif
        @if ($secundario != null)
            .secundario,
            #sidebar {
                background: {!! $secundario !!};
            }
        @endif
        .panel {
            width: 99% !important;
        }

        .m-b-10 {
            margin-bottom: 10px;
        }


        select:invalid {
                color: gray;
        }

    </style>
    {{-- @include('js.nuevo_pedido_css') --}}

    @yield('css')
</head>

<body class="main-layout">
    <div id="divWrapper" class="wrapper">
        <!-- Sidebar izquierdo menu-->
        @include('templates.menu-lateral')
        <!-- contenido interno -->
        <div id="content">
            <!-- menu horizontal usuarios-->
            @include('templates.menu-header')
            <div class="line">
            </div>
            <!-- Div de contenido -->
            <div class="container-fluid">
                <div class="row">
                    @yield('contenido')
                </div>
            </div>
            <!-- fin contenido interno -->

        </div>
        <!-- fin wraper -->
    </div>


    <!-- Bootstrap Js CDN -->
    <script src="{{ asset('js/all.js') }}"></script>
    <script src="{{ asset('js/jquery-3.3.js') }}"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

    {{--  script que da comflicto con el modal --}}
    {{-- <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/color/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('plugins/phone/js/bootstrap-formhelpers.js') }}"></script>
    <script src="{{ asset('plugins/dropi/js/dropify.js') }}"></script>
    <script src="{{ asset('js/base.js') }}"></script>
    <script src="{{ asset('js/mask.js') }}"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/jquery.easy-autocomplete.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>

    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>

        var AppUrl = '{{ env('APP_URL') }}'; /* url app para rutas ajax */

        $(".autocomplete").hide();
        $(".autocomplete-producto").hide();
        axios.defaults.headers.common['X-CSRF-TOKEN'] = window.token;
        axios.defaults.headers.common['csrftoken'] = window.token;
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                if (settings.type == 'POST' || settings.type == 'PUT' || settings.type == 'DELETE') {
                    xhr.setRequestHeader("X-CSRF-TOKEN", window.token);
                }
            }
        });

    </script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/filetype.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/themes/fa5/theme.min.js"></script>
    <script src="{{ asset('js/locales/es.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            // Inicializar el campo de carga de archivos con localización en español
            $("#input-b3").fileinput({
                language: 'es',
                showUpload: false, // No mostrar botón de subida
                showCaption: true,
                browseClass: "btn btn-primary", // Establecer la clase del botón de buscar
                msgPlaceholder: "Seleccionar {files} para subir..." // Mensaje de marcador de posición
                // Agrega más opciones de configuración según tus necesidades
            });


        });
    </script>
    @include('notificaciones.notificaciones')
    @yield('js')
    @yield('js_partial')
</body>
@yield('modal')

</html>

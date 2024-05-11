function addEntrega() {
       $('#mensagge-entrega').empty();
       var count = $("input[name='countEntrega[]']");
       var calle = $("input[name='calle[]']");
       var num = $("input[name='num[]']");
       var numInt = $("input[name='numInt[]']");
       var colonia = $("input[name='colonia[]']");
       var ciudad = $("input[name='ciudad[]']");
       var municipio = $("input[name='municipio[]']");
       var codPostal = $("input[name='codPostal[]']");
       var paisEntrega = $("input[name='paisEntrega[]']");
       var estadoEntrega = $("input[name='estadoEntrega[]']");
       var empresaID = $("input[name='empresa_id']").val();
       
        for (var i = 0; i < calle.length; i++) {
             var data = $(calle[i]).val();
             if (data == null || data == undefined || data == '') {
                 $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < num.length; i++) {
             var data = $(num[i]).val();
             if (data == null || data == undefined || data == '') {
                 $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
       
         for (var i = 0; i < municipio.length; i++) {
             var data = $(municipio[i]).val();
             if (data == null || data == undefined || data == '') {
                 $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < ciudad.length; i++) {
             var data = $(ciudad[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < colonia.length; i++) {
             var data = $(colonia[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < codPostal.length; i++) {
             var data = $(codPostal[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < paisEntrega.length; i++) {
             var data = $(paisEntrega[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < estadoEntrega.length; i++) {
             var data = $(estadoEntrega[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-entrega').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
       var co = count.length;
       if ($('#entrega' + co).length) {
           co = co + 2;
       }
       var html = '<div id="entrega' + co + '" class="tab-pane fade">';
       html += '<input name="countEntrega[]" value="' + co + '" type="hidden">';
       html += '<div id="entrega" class="tab-pane fade in active">';
       html += '<input  name="countEntrega[]" value="0" type="hidden">';
       html += '<div class="form-group row tab-form">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="calleEntrega"><span class="requerido">* </span>Calle</label>';
       html += '<input  name="calleEntrega[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-3">';
       html += '<label class="text-md-right" for="numEntrega"><span class="requerido">* </span>Num.</label>';
       html += '<input  name="numEntrega[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-3">';
       html += '<label class="text-md-right" for="numIntEntrega">Num. Int</label>';
       html += '<input  name="numIntEntrega[]" type="text" class="form-control input-medium">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="coloniaEntrega"><span class="requerido">* </span>Colonia</label>';
       html += '<input  name="coloniaEntrega[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="ciudadEntrega"><span class="requerido">* </span>Ciudad</label>';
       html += '<input  name="ciudadEntrega[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="municipioEntrega"><span class="requerido">* </span>Municipio</label>';
       html += '<input  name="municipioEntrega[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="codPostalEntrega"><span class="requerido">* </span>Codigo Postal</label>';
       html += '<input  name="codPostalEntrega[]" type="number" class="form-control input-medium" required="">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="paisEntrega"><span class="requerido">* </span>Pais</label>';
       //select pais
       html += '<select name="paisEntrega[]" data-index="' + co + '" onchange="cambiarEstadoEntrega(this)" class="form-control paisEntrega"  id="paisEntrega[]">';
       html += '<option value="">Seleccione..</option>';
       html += '</select>';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="estadoEntrega"><span class="requerido">* </span>Estado</label>';
       html += '<select name="estadoEntrega[]" class="estadoEntrega form-control" data-index="' + co + '" id="estadoEntrega[]" required="">';
       html += '<option value="">Seleccione un pais</option>';
       html += '</select>';
       html += '<small class="red" id="tagEstadoEntrega"></small>';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-12">';
       html += '<label class="text-md-right" for="descripcionEntrega">Indicaciones del Domicilio</label>';
       html += '<textarea name="descripcionEntrega[]" class="form-control"></textarea>';
       html += '</div>';
       html += '</div>';
       html += '</div>';
       html += '</div>';
       $('#click-addEntrega').remove();
       $('#tab-contenedorEntrega').append(html);
       $('#tab-controlEntrega').append('<li class="li-tab entrega" id="liEntrega' + co + '"><a id="linkEntrega' + co + '" data-toggle="tab" href="#entrega' + co + '">Direccion Entrega</a><span class="cerrar-tab" onclick="removeEntrega(' + co + ')">x</span></li>');
       $('#tab-controlEntrega').append('<li id="click-addEntrega"><a class="agregar-btn" onclick="addEntrega();">Agregar Direccion de Entrega</a></li>');
       $('#linkEntrega' + co).trigger('click')
       getPaisEntrega(empresaID, co);
       
   }

   function getPaisEntrega(empresaID, co){

    $.ajax({
           url: '/ajax/pais/' + empresaID,
           type: "GET",
           dataType: "json",
           success: function(data) {
               $.each(data, function(key, value) {
               $(".paisEntrega[data-index='" + co + "']").append('<option value="' + key + '">' + value + '</option>');
               });
           },
       });
   }

   function removeEntrega(count) {
       $('#liEntrega' + count).remove();
       $('#entrega' + count).remove();
       $('#click-addEntrega').remove();
       $('#tab-controlEntrega').append('<li id="click-addEntrega"><a class="agregar-btn" onclick="addEntrega();">Agregar Direccion de Entrega</a></li>');
       $('#linkEntrega').trigger('click');
   }

   function cambiarEstadoEntrega(el) {
       var paisId = $(el).val();
       var index = $(el).attr('data-index');
       if (paisId && paisId != 'Seleccione...') {
           $.ajax({
               url: '/ajax/estado/' + paisId,
               type: "GET",
               dataType: "json",
               success: function(data) {
                   $(".estadoEntrega[data-index='" + index + "']").empty();
                   $(".estadoEntrega[data-index='" + index + "']").append('<option value="">Seleccione una Opcion</option>');
                   if (data.length == 0) {
                       $('#tagEstadoFacura' + index).append('Los Estados para este pais se encuentran Inactivos o no se han registrado ninguno.  </small><small><a href="/estado" title="">Administrar Estados</a>');
                   }
                   $.each(data, function(key, value) {
                       $(".estadoEntrega[data-index='" + index + "']").append('<option value="' + key + '">' + value + '</option>');
                   });
               },
           });
       } else {
           $(".estadoEntrega[data-index='" + index + "']").empty().append('<option></option>').empty();
       }
   }

   function removeEntregaDB(count, id) {
       $.confirm({
           title: 'Eliminar Direccion de Entrega',
           content: 'Tenga en cuenta que si elimina este elemento lo estara borrando por completo!',
           buttons: {
               eliminar: {
                   text: 'Eliminar',
                   btnClass: 'btn-red',
                   keys: ['enter', 'shift'],
                   action: function() {
                       $.ajax({
                           url: '/prospecto/eliminarEntrega/' + id,
                           type: "GET",
                           dataType: "json",
                           success: function(data) {
                               if (data == 'succsess') {
                                   $.alert('Se ha eliminado la direccion de entrega de forma satisfactoria');

                                      $('#liEntrega' + count).remove();
                                      $('#entrega' + count).remove();
                                      $('#click-addEntrega').remove();
                                      $('#tab-controlEntrega').append('<li id="click-addEntrega"><a class="agregar-btn" onclick="addEntrega();">Agregar Direccion de Entrega</a></li>');
                                      $('#linkEntrega').trigger('click');
                               }
                           },
                       });
                   }
               },
               cancelar: {
                   text: 'Cancelar',
                   action: function() {}
               }
           }
       });
   }
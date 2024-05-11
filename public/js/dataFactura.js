   function addFactura() {
       $('#mensagge-factura').empty();
       var count = $("input[name='countFactura[]']");
       var calle = $("input[name='calle[]']");
       var num = $("input[name='num[]']");
       var numInt = $("input[name='numInt[]']");
       var colonia = $("input[name='colonia[]']");
       var ciudad = $("input[name='ciudad[]']");
       var municipio = $("input[name='municipio[]']");
       var codPostal = $("input[name='codPostal[]']");
       var paisFactura = $("input[name='paisFactura[]']");
       var estadoFactura = $("input[name='estadoFactura[]']");

       var empresaID = $("input[name='empresa_id']").val();
       
    for (var i = 0; i < calle.length; i++) {
             var data = $(calle[i]).val();
             if (data == null || data == undefined || data == '') {
                 $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < num.length; i++) {
             var data = $(num[i]).val();
             if (data == null || data == undefined || data == '') {
                 $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
        
         for (var i = 0; i < municipio.length; i++) {
             var data = $(municipio[i]).val();
             if (data == null || data == undefined || data == '') {
                 $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < ciudad.length; i++) {
             var data = $(ciudad[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < colonia.length; i++) {
             var data = $(colonia[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < codPostal.length; i++) {
             var data = $(codPostal[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < paisFactura.length; i++) {
             var data = $(paisFactura[i]).val();
             if (data == null || data == undefined || data == '') {
                  $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         for (var i = 0; i < estadoFactura.length; i++) {
             var data = $(estadoFactura[i]).val();
             if (data == null || data == undefined || data == '') {
                 $('#mensagge-factura').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
                 return false;
             }
         }
         
       var co = count.length;
       if ($('#factura' + co).length) {
           co = co + 2;
       }
       var html = '<div id="factura' + co + '" class="tab-pane fade">';
       html += '<input name="countFactura[]" value="' + co + '" type="hidden">';
       html += '<div class="form-group row tab-form">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="calle"><span class="requerido">* </span>Calle</label>';
       html += '<input  name="calle[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-3">';
       html += '<label class="text-md-right" for="num"><span class="requerido">* </span>Num.</label>';
       html += '<input  name="num[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-3">';
       html += '<label class="text-md-right" for="numInt">Num. Int</label>';
       html += '<input  name="numInt[]" type="text" class="form-control input-medium">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="colonia"><span class="requerido">* </span>Colonia</label>';
       html += '<input  name="colonia[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="ciudad"><span class="requerido">* </span>Ciudad</label>';
       html += '<input  name="ciudad[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="municipio"><span class="requerido">* </span>Municipio</label>';
       html += '<input  name="municipio[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="codPostal"><span class="requerido">* </span>Codigo Postal</label>';
       html += '<input  name="codPostal[]" type="number" class="form-control input-medium" required="">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="paisFactura"><span class="requerido">* </span>Pais</label>';
       //select pais
       html += '<select name="paisFactura[]" data-index="' + co + '" onchange="cambiarEstado(this)" class="form-control paisFactura"  id="paisFactura[]">';
       html += '<option value="">Seleccione..</option>';
       html += '</select>';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="estadoFactura"><span class="requerido">* </span>Estado</label>';
       html += '<select name="estadoFactura[]" class="estadoFactura form-control" data-index="' + co + '" id="estadoFactura[]" required="">';
       html += '<option value="">Seleccione un pais</option>';
       html += '</select>';
       html += '<small class="red" id="tagEstadoFacura"></small>';
       html += '</div>';
       html += '</div>';
       html += '</div>';
       html += '</div>';
       $('#click-addFactura').remove();
       $('#tab-contenedorFactura').append(html);
       $('#tab-controlFactura').append('<li class="li-tab factura" id="liFactura' + co + '"><a id="linkFactura' + co + '" data-toggle="tab" href="#factura' + co + '">Direccion Factura</a><span class="cerrar-tab" onclick="removeFactura(' + co + ')">x</span></li>');
       $('#tab-controlFactura').append('<li id="click-addFactura"><a class="agregar-btn" onclick="addFactura();">Agregar Direccion de Factura</a></li>');
       $('#linkFactura' + co).trigger('click')
       getPais(empresaID, co);
       
   }

   function getPais(empresaID, co){

    $.ajax({
           url: '/ajax/pais/' + empresaID,
           type: "GET",
           dataType: "json",
           success: function(data) {
               $.each(data, function(key, value) {
               $(".paisFactura[data-index='" + co + "']").append('<option value="' + key + '">' + value + '</option>');
               });
           },
       });
   }

   function removeFactura(count) {
       $('#liFactura' + count).remove();
       $('#factura' + count).remove();
       $('#click-addFactura').remove();
       $('#tab-controlFactura').append('<li id="click-addFactura"><a class="agregar-btn" onclick="addFactura();">Agregar Direccion de Factura</a></li>');
       $('#linkFactura').trigger('click');
   }

   function cambiarEstado(el) {
       var paisId = $(el).val();
       var index = $(el).attr('data-index');
       if (paisId && paisId != 'Seleccione...') {
           $.ajax({
               url: '/ajax/estado/' + paisId,
               type: "GET",
               dataType: "json",
               success: function(data) {
                   $(".estadoFactura[data-index='" + index + "']").empty();
                   $(".estadoFactura[data-index='" + index + "']").append('<option value="">Seleccione una Opcion</option>');
                   if (data.length == 0) {
                       $('#tagEstadoFacura' + index).append('Los Estados para este pais se encuentran Inactivos o no se han registrado ninguno.  </small><small><a href="/estado" title="">Administrar Estados</a>');
                   }
                   $.each(data, function(key, value) {
                       $(".estadoFactura[data-index='" + index + "']").append('<option value="' + key + '">' + value + '</option>');
                   });
               },
           });
       } else {
           $(".estadoFactura[data-index='" + index + "']").empty().append('<option></option>').empty();
       }
   }

   function removeFacturaDB(count, id) {
       $.confirm({
           title: 'Eliminar Direccion de Factura',
           content: 'Tenga en cuenta que si elimina este elemento lo estara borrando por completo!',
           buttons: {
               eliminar: {
                   text: 'Eliminar',
                   btnClass: 'btn-red',
                   keys: ['enter', 'shift'],
                   action: function() {
                       $.ajax({
                           url: '/prospecto/eliminarFactura/' + id,
                           type: "GET",
                           dataType: "json",
                           success: function(data) {
                               if (data == 'succsess') {
                                   $.alert('Se ha eliminado la direccion de factura de forma satisfactoria');
                                    $('#liFactura' + count).remove();
                                    $('#factura' + count).remove();
                                    $('#click-addFactura').remove();
                                    $('#tab-controlFactura').append('<li id="click-addFactura"><a class="agregar-btn" onclick="addFactura();">Agregar Direccion de Factura</a></li>');
                                    $('#linkFactura').trigger('click');
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
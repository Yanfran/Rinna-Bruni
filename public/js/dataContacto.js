   function addContacto() {
       $('#mensagge-contacto').empty();
       var count = $("input[name='countContacto[]']");
       var primerNombre = $("input[name='primerNombre[]']");
       var apellido = $("input[name='apellido[]']");
       var telefonoContacto = $("input[name='telefonoContacto[]']");
       var emailContacto = $("input[name='emailContacto[]']");
       for (var i = 0; i < primerNombre.length; i++) {
           var data = $(primerNombre[i]).val();
           if (data == null || data == undefined || data == '') {
               $('#mensagge-contacto').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
               return false;
           }
       }
       for (var i = 0; i < apellido.length; i++) {
           var data = $(apellido[i]).val();
           if (data == null || data == undefined || data == '') {
               $('#mensagge-contacto').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
               return false;
           }
       }
       for (var i = 0; i < telefonoContacto.length; i++) {
           var data = $(telefonoContacto[i]).val();
           if (data == null || data == undefined || data == '') {
              $('#mensagge-contacto').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
               return false;
           }
       }
       for (var i = 0; i < emailContacto.length; i++) {
           var data = $(emailContacto[i]).val();
           var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          
            if (!filter.test(data)) {
                $('#mensagge-contacto').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
               return false;
             }

           if (data == null || data == undefined || data == '') {
               $('#mensagge-contacto').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong></strong>Complete el formulario, antes de registrar uno nuevo</div>');
               return false;
           }
       }
       var co = count.length;
       if ($('#contacto' + co).length) {
           co = co + 2;
       }
       var html = '<div id="contacto' + co + '" class="tab-pane fade">';
       html += '<input name="countContacto[]" value="' + co + '" type="hidden">';
       html += '<div class="form-group row tab-form">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="primerNombre"><span class="requerido">* </span>Primer Nombre</label>';
       html += '<input  name="primerNombre[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="segundoNombre">Segundo Nombre</label>';
       html += '<input  name="segundoNombre[]" type="text" class="form-control input-medium">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="apellido"><span class="requerido">* </span>Apellido</label>';
       html += '<input  name="apellido[]" type="text" class="form-control input-medium" required="">';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="posicion">Posicion</label>';
       html += '<input  name="posicion[]" type="text" class="form-control input-medium">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-4">';
       html += '<label class="text-md-right" for="telefonoContacto"><span class="requerido">* </span> Telefono de contacto</label>';
       html += '<input  name="telefonoContacto[]" type="text" class="form-control input-medium phone" placeholder="000 000-0000" required="">';
       html += '</div>';
       html += '<div class="col-md-2">';
       html += '<label class="text-md-right" for="extContacto">Ext.</label>';
       html += '<input  name="extContacto[]" type="text" class="form-control input-medium ext"  placeholder="00">';
       html += '</div>';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="movilContacto">Movil de contacto.</label>';
       html += '<input  name="movilContacto[]" type="text" class="form-control input-medium phone" placeholder="000 000-0000">';
       html += '</div>';
       html += '</div>';
       html += '<div class="form-group row">';
       html += '<div class="col-md-6">';
       html += '<label class="text-md-right" for="emailContacto"><span class="requerido">* </span>Correo electronico de contacto</label>';
       html += '<input  name="emailContacto[]" type="email" class="form-control input-medium" required="">';
       html += '</div>';
       html += '</div>';
       html += '</div>';

       $('#click-addContacto').remove();
       $('#tab-contenedorContacto').append(html);
       $('#tab-controlContacto').append('<li class="li-tab" id="liContacto' + co + '"><a id="linkContacto' + co + '" data-toggle="tab" href="#contacto' + co + '">Contacto</a><span class="cerrar-tab" onclick="removeContacto(' + co + ')">x</span></li>');
       $('#tab-controlContacto').append('<li id="click-addContacto"><a class="agregar-btn" onclick="addContacto(' + co + ');">Agregar Contacto</a></li>');
       $('#linkContacto' + co).trigger('click')
       mask();

   }

   function removeContacto(count) {
       $('#liContacto' + count).remove();
       $('#contacto' + count).remove();
       $('#click-addContacto').remove();
       $('#tab-controlContacto').append('<li id="click-addContacto"><a class="agregar-btn" onclick="addContacto();">Agregar Contacto</a></li>');
       $('#linkContacto').trigger('click');
   }

   function removeContactoDB(count, id) {
       $.confirm({
           title: 'Eliminar contacto',
           content: 'Tenga en cuenta que si elimina este elemento lo estara borrando por completo!',
           buttons: {
               eliminar: {
                   text: 'Eliminar',
                   btnClass: 'btn-red',
                   keys: ['enter', 'shift'],
                   action: function() {
                       $.ajax({
                           url: '/prospecto/eliminarContacto/' + id,
                           type: "GET",
                           dataType: "json",
                           success: function(data) {
                               if (data == 'succsess') {
                                   $.alert('Se ha eliminado el contacto de forma satisfactoria');
                                   $('#liContacto' + count).remove();
                                   $('#contacto' + count).remove();
                                   $('#click-addContacto').remove();
                                   $('#tab-controlContacto').append('<li id="click-addContacto"><a class="agregar-btn" onclick="addContacto();">Agregar Contacto</a></li>');
                                   $('#linkContacto').trigger('click');
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
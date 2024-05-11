
$(document).ready(function() {

    cargarfiltroBusqueda("filtroProductosCatalogo","productosCatalogo");

    $('#btnAgregarProductos').on('click',function(e){
        event.preventDefault();

        let template = getTemplateModalProducts();

        Swal.fire({
            position: "top",
            //target: document.getElementById('panelCrearCatalogo'),
            title: 'Seleccione los Productos',
            html: template,
            showCloseButton: true,
            showConfirmButton: true,
            customClass: {
                popup: 'modal-dialog',
                content: 'modal-content',
                closeButton: 'close',
            },
            width: '80%',
            heightAuto: false,
            scrollbarPadding: false,
            showCancelButton: true,
            confirmButtonColor: '#57BB59',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Agregar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,

        }).then((result) => {
            if (result.isConfirmed) {
                cargarProductosSeleccionados();
                cargarfiltroBusqueda("filtroProductosCatalogo","productosCatalogo");
                //Swal.fire("Saved!", "", "success");
            }
        });

    });


    //Dialogo para borrar desde los listados

    $('.delete-btn').on('click', function(e) {
        e.preventDefault();

        var form = $(this).closest('form');

        Swal.fire({
            title: 'Confirmar eliminación',
            text: '¿Estás seguro de que deseas eliminar este elemento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });


});


function mapearProductosSeleccionados(){

    let filas = $("#seleccionProductos tbody > tr");

    arr = [];

    filas.each(function(i,a){
        if($(this).find("td input[type='checkbox']").is(':checked')) {

            arr.push(
                {
                   id        : $(this).find("td:first").text(),
                   codigo    : $(this).find("td.col-codigo").text(),
                   estilo    : $(this).find("td.col-estilo").text(),
                   linea     : $(this).find("td.col-linea").text(),
                   tallas    : $(this).find("td.col-talla").text(),
                   marca     : $(this).find("td.col-marca").text(),
                   temporada : $(this).find("td.col-temporada").text(),
                   precio    : $(this).find("td.col-precio").text(),
                   ids       : $(this).find("td.col-ids").text(),
                }

            );

        }
    });

    return arr;

}

function cargarProductosSeleccionados() {
    let tablaCatalogo = $("#productosCatalogo");
    let filas = mapearProductosSeleccionados();

    //console.log(filas);

    let rowTemplate = getRowTableCatalogoTemplate();

    if(filas.length >= 1) {
         filas.forEach(function(fila){

            let row = rowTemplate;

            row = row.replace('__ID__','<input name="productos[]" type="text" value="'+fila.codigo+'" readonly>');
            row = row.replace('__CODIGO__',fila.codigo);
            row = row.replace('__ESTILO__',fila.estilo);
            row = row.replace('__LINEA__',fila.linea);
            row = row.replace('__TALLAS__',fila.tallas + '<input name="tallas[]" type="hidden" value="'+fila.tallas+'" readonly>');
            row = row.replace('__MARCA__',fila.marca);
            row = row.replace('__TEMPORADA__',fila.temporada);
            row = row.replace('__PRECIO__',fila.precio);

            if (fila.ids == "" || fila.ids == null || fila.ids == undefined) {
                row = row.replace('__IDS__','<input name="ids[]" type="hidden" value="'+fila.id+'" readonly>');
            } else {
                row = row.replace('__IDS__','<input name="ids[]" type="hidden" value="'+fila.ids+'" readonly>');
            }

            if( siExisteProducto(fila.id) ) {
                alert("El Producto: "+fila.estilo+" ya fue agregado al catalogo");
            } else {
                $("#productosCatalogo tbody").append(row);
            }

         });

    }

}


function getRowTableCatalogoTemplate() {
    return `
        <tr>
            <td class="d-none">__ID__</td>
            <td style="width:10% !important">__CODIGO__</td>
            <td style="width:15% !important">__ESTILO__</td>
            <td style="width:15% !important">__LINEA__</td>
            <td style="width:10% !important">__TALLAS__</td>
            <td style="width:10% !important">__MARCA__</td>
            <td style="width:10% !important">__TEMPORADA__</td>
            <td style="width:15% !important" class="text-right">__PRECIO__</td>

            <td style="width:10% !important" class="text-center">
            <button type="button" class="btn" onclick="$(this).closest('tr').remove()">
                <i style="color: red; !important"class="far fa-trash-alt"></i>
            </button>
            </td>
            <td class="d-none">__IDS__</td>

        </tr> `;

}

//Preview create and edit brand
var _URL = window.URL || window.webkitURL;

$(".validar-img").change(function() {
    let maxWidth = $(this).attr('data-max-width');
    let maxHeight = $(this).attr('data-max-height');
    let containerImagen = $(this).attr('data-container-img');
    let containerError = $(this).attr('data-container-error');
    let maxSize = $(this).attr('data-max-size')
    let maxSizeBytes = maxSize * 1024; /*Kbytes  a bytes*/
    let fileInput = this;


    if (maxWidth === undefined) {
        maxWidth = 200;
    } else {
        maxWidth = parseInt(maxWidth);
    }

    if (maxHeight === undefined) {
        maxHeight = 200;
    } else {
        maxHeight = parseInt(maxHeight);
    }

    var file, img;
    if((file = this.files[0])) {
        img = new Image();
        img.parfile = file;
        var objectUrl = _URL.createObjectURL(file);
        img.onload = function() {
            if( this.width != maxWidth || this.height != maxHeight ) {
                $(fileInput).val("");
                $('#' + containerImagen).attr('src', '');
                $('#' + containerError).html('Las medidas permitidas para la imagen son ' + maxWidth +'px X ' + maxHeight + 'px');
                $('#' + containerError).removeClass('d-none');
                _URL.revokeObjectURL(objectUrl);

                return false;
            } else {

                if( ! validSize( fileInput, maxSizeBytes ) ) {
                    $(fileInput).val("");
                    $('#' + containerImagen).attr('src', '');
                    $('#' + containerError).html('El tamaño del archivo debe ser menor o igual a ' + maxSize + ' Kbytes');
                    $('#' + containerError).removeClass('d-none');

                    _URL.revokeObjectURL(objectUrl);

                    return false;
                }
                else {

                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#' + containerImagen).attr('src', e.target.result);
                    }

                    reader.readAsDataURL(file); // convert to base64 string

                    $('#' + containerError).addClass('d-none');
                    _URL.revokeObjectURL(objectUrl);
                }

            }
        }
        img.src = objectUrl;

    }

});


function validSize(fileInput, maxSize) {
    let isOk=true;

    $(fileInput).each(function(){
        let size = fileInput.files[0].size;
        isOk = maxSize > size;
        return isOk;
    });

    return isOk;
}

function getFilteredProducts() {

    var estilo = $('#estilo').val();
    var temporadaId=$('#temporada').val();
    var lineaId=$('#linea').val();
    var descripcionId=$('#descripcion').val();

    let rowTemplate = getRowTableProductosFiltradosTemplate();

    toggleLoadingResultados()

    $.ajax({
        type:"GET",
        data:{estilo:estilo,temporadaId:temporadaId, lineaId:lineaId, descripcionId:descripcionId},
        url: AppUrl+"/catalogos/modal/getFilteredProducts",
        success: function(data){

            var objProduct=null;

            console.log(data);

            if(data.length<=0){
                html="";
                Swal.fire({
                    icon: 'warning',
                    title: 'Busqueda',
                    text: 'No se encontraron productos con los filtros suministrados.'
                });
            }
            else {

              let producto = null;

              $("#seleccionProductos > tbody").empty();

              const claves = Object.keys(data);
              const longitud = claves.length;

              //console.log('longitud',longitud);

              for(var i=0;i<longitud;i++){

                    let row = rowTemplate;

                    const clave = claves[i];
                    const producto = data[clave];


                    //console.log('producto',producto);

                    //row = row.replace('__ID__',producto.id);

                    let codigoFormateado = producto.codigo;

                    if( ultimoCaracterEsGuion(producto.codigo) ) {
                        codigoFormateado = producto.codigo.slice(0, -1);
                    }

                    if( ! siExisteProducto(codigoFormateado) ) {//Si ya esta en el catalogo no cargarlo

                        row = row.replace('__CODIGO__', codigoFormateado);

                        row = row.replace('__ESTILO__',producto.estilo);
                        if (typeof producto.linea !== 'undefined' && producto.linea !== null) {
                            row = row.replace('__LINEA__',producto.linea.nombre);
                        } else {
                            row = row.replace('__LINEA__','');
                        }

                        let tallas = "";
                        for (let j = 0; j < producto.tallas.length; j++) {
                            tallas = tallas + producto.tallas[j] + "-";
                        }

                        let ids = ""

                        for (let j = 0; j < producto.ids.length; j++) {
                             ids = ids + producto.ids[j] + "-";
                        }

                        row = row.replace('__IDS__',ids.slice(0, -1));

                        row = row.replace('__TALLAS__',tallas.slice(0, -1));


                        if (typeof producto.marca !== 'undefined' && producto.marca !== null) {
                            row = row.replace('__MARCA__',producto.marca.nombre);
                        } else {
                            row = row.replace('__MARCA__','');
                        }

                        if (typeof producto.temporada !== 'undefined' && producto.temporada !== null) {
                            row = row.replace('__TEMPORADA__',producto.temporada.nombre);
                        } else {
                            row = row.replace('__TEMPORADA__','');
                        }

                        row = row.replace('__PRECIO__',producto.precio  ?? '');

                        $("#seleccionProductos tbody").append(row);

                    } else {
                        hayProductosRepetidos = true;
                    }


              }

            }//end if data.lenght

            toggleLoadingResultados();

            cargarfiltroBusqueda("filtro","seleccionProductos");

            cargarCheckTodos();



        }
    });
  }

  function getRowTableProductosFiltradosTemplate() {
    return `
        <tr>
            <td style="width:0% !important" class="text-left d-none">__ID__</td>
            <td style="width:7% !important" class="text-left col-codigo">__CODIGO__</td>
            <td style="width:15% !important" class="text-left col-estilo">__ESTILO__</td>
            <td style="width:15% !important" class="text-left col-linea">__LINEA__</td>
            <td style="width:10% !important" class="text-center col-talla">__TALLAS__</td>
            <td style="width:10% !important" class="text-center col-marca">__MARCA__</td>
            <td style="width:10% !important" class="text-center col-temporada">__TEMPORADA__</td>
            <td style="width:5% !important" class="text-right col-precio">__PRECIO__</td>
            <td style="width:10%" class="text-center">
                <input class="form-check-input" type="checkbox" value="" >
            </td>
            <td class="d-none col-ids">__IDS__</td>
        </tr> `;

   }

   function quitarFiltros(){
        $('#estilo').val("");

        $('#temporada').val("0");
        $('#linea').val("0").change();
        $('#descripcion').val("0").change();

        $("#seleccionProductos > tbody").empty();
   }


//   function siExisteProducto(codigoProducto) {
//     const filaProducto = $('#productosCatalogo tbody tr').filter(function() {
//       return $(this).find('td').eq(1).text() === codigoProducto;
//     });

//     return filaProducto.length > 0; // Devuelve true si se encuentra el producto, false en caso contrario
//   }

  function siExisteProducto(codigoProducto) {
    const filasProducto = $('#productosCatalogo tbody tr').filter(function() {
      const inputProducto = $(this).find('input'); // Busca el input en la fila actual
      return inputProducto.val().toLowerCase() === codigoProducto.toLowerCase(); // Compara el valor del input
    });

    return filasProducto.length > 0; // Devuelve true si se encuentra el producto, false en caso contrario
  }

  function toggleLoadingResultados(){
    if ($("#divLoading").hasClass("d-none")) {
        $("#divLoading").removeClass("d-none");
        $("#container-filtro").addClass("d-none");
        $("#seleccionProductos").addClass("d-none")
        $('.buscar-filtro').prop('disabled', true);
        $('.buscar-filtro2').prop('disabled', true);
        $('.swal2-confirm').prop('disabled', true);
        $('.swal2-cancel').prop('disabled', true);

    } else {
        $("#divLoading").addClass("d-none");
        $("#seleccionProductos").removeClass("d-none")
        $("#container-filtro").removeClass("d-none");
        $('.buscar-filtro').prop('disabled', false);
        $('.buscar-filtro2').prop('disabled', false);
        $('.swal2-confirm').prop('disabled', false);
        $('.swal2-cancel').prop('disabled', false);

    }


  }

  function cargarfiltroBusqueda(input,tabla) {

    const tablaFiltrar = $("#"+tabla+" tbody");
    const filtro = $("#"+input);

    filtro.on("keyup", function() {
        const termino = $(this).val().toLowerCase();

        tablaFiltrar.find("tr").each(function() {
          const contenido = $(this).text().toLowerCase();

          if (contenido.indexOf(termino) === -1) {
            $(this).hide();
          } else {
            $(this).show();
          }
        });
      })

}

function cargarCheckTodos() {
    $("#checkTodos").on('click', function() {
        let isChecked = $(this).prop('checked');
        $('input.form-check-input').prop('checked', isChecked);
    });
}

$('#borrarTodo').click(function() {

    if( $("#productosCatalogo tbody tr").length > 0 ) {

        Swal.fire({
            title: 'Confirmar',
            text: '¿Estás seguro de que desea borrar todos los productos del catalogo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#productosCatalogo tbody tr').remove();
            }
        });

    }

});

function ultimoCaracterEsGuion(cadena) {
    const longitudCadena = cadena.length;
    const ultimoCaracter = cadena.charAt(longitudCadena - 1);
    return ultimoCaracter === "-";
  }










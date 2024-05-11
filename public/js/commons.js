
//Dialogo para borrar desde los listados
$(document).ready(function() {
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

//Preview create and edit brand
var _URL = window.URL || window.webkitURL;

$(".validar-img").change(function() {
    let maxWidth = $(this).attr('data-max-width');
    let maxHeight = $(this).attr('data-max-height');
    let containerImagen = $(this).attr('data-container-img');
    let containerError = $(this).attr('data-container-error');
    let maxSize = $(this).attr('data-max-size');
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

$(".validar-img-maximo").change(function() {
    let maxWidth = $(this).attr('data-max-width');
    let maxHeight = $(this).attr('data-max-height');
    let containerImagen = $(this).attr('data-container-img');
    let containerError = $(this).attr('data-container-error');
    let maxSize = $(this).attr('data-max-size');
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
            if( this.width > maxWidth || this.height > maxHeight ) {
                $(fileInput).val("");
                $('#' + containerImagen).attr('src', '');
                $('#' + containerError).html('Las medidas maximas permitidas para la imagen son ancho: ' + maxWidth +'px X largo: ' + maxHeight + 'px');
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

function validarBytesArchivos(inputElement, maxTamanoBytes,containerError) {
    const archivos = inputElement.files;
    for (let i = 0; i < archivos.length; i++) {
        const archivo = archivos[i];
        const tamanoBytes = archivo.size;
        const tamanoMB = tamanoBytes / (1024 * 1024); // Convertir a megabytes

        // Verificar el tipo de archivo (por ejemplo, solo imágenes)
        const extensionesPermitidas = /\.(jpg|jpeg|png)$/i; // Puedes modificar esto según tus necesidades
        if (!extensionesPermitidas.test(archivo.name)) {
            $('#' + containerError).html(`El archivo "${archivo.name}" no es una imagen válida.`);
            $('#' + containerError).removeClass('d-none');
            return false;
        }

        // Verificar el tamaño del archivo
        if (tamanoBytes > maxTamanoBytes) {
            $('#' + containerError).html(`El archivo "${archivo.name}" debe pesar menos de ${maxTamanoBytes / 1024} Kb.`);
            $('#' + containerError).removeClass('d-none');
            return false;
        }
    }
    $('#' + containerError).html(``);
    $('#' + containerError).addClass('d-none');
    return true; // Todos los archivos son válidos
}

$(".img-zoom").on("click", function() {

    let urlImg = $(this).attr('src');

    Swal.fire({
        width: 500,
        imageUrl: urlImg,
        imageAlt: "voucher image"
    });

    //activar zoom en la imagen del modal
    if(Swal.isVisible()) {
        $('img.swal2-image')
        .wrap('<span style="display:inline-block; border:solid 1px #ddd;border-radius:4px;cursor: zoom-in; height:670px; padding-bottom:20px"></span>')
        .css('display', 'block')
        .parent()
        .zoom(
            {
                magnify: 1.5,
                on: 'grab'
            }
        );
    }
});




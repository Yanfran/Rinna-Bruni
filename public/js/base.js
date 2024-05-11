$(document).ready(function () {
    $("#sidebarCollapse").on("click", function () {
        $("#sidebar").toggleClass("active");
        $(this).toggleClass("active");
    });

    // FORMATER INPUT PARA TELEFONO
    $("input[data-mask]").inputmask();
    var input = $(".miles");
    var value = input.val();
    if (typeof value !== "undefined") {
        var cleanedValue = value.replace(/,/g, "");
        // Resto del código...
    } else {
        var cleanedValue = 0.00;
    }
    var number = parseFloat(cleanedValue);

    if (!isNaN(number)) {
        var formattedNumber = formatNumber(number);
        input.val(formattedNumber);
    } else {
        input.val("");
    }
});

 document.addEventListener('DOMContentLoaded', function() {
    // const usernameField = document.getElementById('email');
    const passwordField = document.getElementById('password-1');
    const passwordField2 = document.getElementById('password-2');

    // usernameField.addEventListener('focus', function() {
    //   this.removeAttribute('readonly');
    // });

    //TODO: comentado por Rafael porque daba error
    // passwordField.addEventListener('focus', function() {
    //   this.removeAttribute('readonly');
    // });

    // passwordField2.addEventListener('focus', function() {
    //     this.removeAttribute('readonly');
    // });
    //TODO END
  });

// FORMATEAR INPUT PORCENTAJE

// FORMATER INPUT POR MILES
var input = $(".miles");

input.on("input", function () {
    var input = $(this);
    var value = input.val();
    var cleanedValue = value.replace(/,/g, "");
    var number = parseFloat(cleanedValue);

    if (!isNaN(number)) {
        var formattedNumber = formatNumber(number);
        input.val(formattedNumber);
    } else {
        input.val("");
    }
});

function formatNumber(number) {
    var parts = number.toFixed(0).split(".");
    var integerPart = parts[0];

    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    return integerPart;
}

// FECHA NO FUTURAS
var fechaInputs = document.getElementsByClassName("fecha_no_futuras");
var fechaActual = new Date().toISOString().split("T")[0];
for (var i = 0; i < fechaInputs.length; i++) {
    fechaInputs[i].max = fechaActual;
}


// FECHA NO FUTURAS
// var fechaInputs = document.getElementsByClassName("fecha_no_futuras");


// var today = new Date();
// var maxDate = new Date(today.getFullYear() - 53, today.getMonth(), today.getDate());
// var minDate = new Date(today.getFullYear() - 53, today.getMonth(), today.getDate());

// for (var i = 0; i < fechaInputs.length; i++) {
//     fechaInputs[i].min = maxDate.toISOString().split("T")[0];
//     fechaInputs[i].max = today.toISOString().split("T")[0];
// }





// OCULTAR Y MOSTRAR CLAVE
$(".show-password-1").on("click", function () {
    var passwordField = $("#password-1");
    var passwordFieldType = passwordField.attr("type");
    if (passwordFieldType === "password") {
        passwordField.attr("type", "text");
        $(this).html('<i class="fa fa-eye-slash"></i>');
    } else {
        passwordField.attr("type", "password");
        $(this).html('<i class="fa fa-eye"></i>');
    }
});

$(".show-password-2").on("click", function () {
    var passwordFieldTwo = $("#password-2");
    var passwordFieldTypeTwo = passwordFieldTwo.attr("type");
    if (passwordFieldTypeTwo === "password") {
        passwordFieldTwo.attr("type", "text");
        $(this).html('<i class="fa fa-eye-slash"></i>');
    } else {
        passwordFieldTwo.attr("type", "password");
        $(this).html('<i class="fa fa-eye"></i>');
    }
});

$(".show-password-3").on("click", function () {
    var passwordFieldTwo = $("#password-3");
    var passwordFieldTypeTwo = passwordFieldTwo.attr("type");
    if (passwordFieldTypeTwo === "password") {
        passwordFieldTwo.attr("type", "text");
        $(this).html('<i class="fa fa-eye-slash"></i>');
    } else {
        passwordFieldTwo.attr("type", "password");
        $(this).html('<i class="fa fa-eye"></i>');
    }
});

/// CODIGO POSTAL
var input = document.getElementById("postal");
if (input) {
    // input.addEventListener('input',function(){
    // if (this.value.length > 99999)
    //     this.value = this.value.slice(0,99999);
    // })

    input.addEventListener("input", function () {
        var maxDigits = 5;
        if (this.value.length > maxDigits) {
            this.value = this.value.slice(0, maxDigits);
        }
    });
}

function Numeros(e) {
    tecla = document.all ? e.keyCode : e.which;
    if (tecla == 8) {
        return true;
    }
    // Patron de entrada, en este caso solo acepta numeros
    patron = /[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}


function Letras(e) {
  const key = e.keyCode || e.which;
  const tecla = String.fromCharCode(key).toLowerCase();
  const letras = "áéíóúabcdefghijklmnñopqrstuvwxyz";
  const especiales = "8-37-39-46";
  let tecla_especial = false;
  for (let i in especiales) {
      if (key == especiales[i]) {
          tecla_especial = true;
          break;
      }
  }
  if (letras.indexOf(tecla) == -1 && !tecla_especial) {
      return false;
  }
}



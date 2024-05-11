     $(document).ready(function() {
         $('select[name="empresa_id"]').on('change', function() {
             var empresaId = $(this).val();
             if (empresaId && empresaId != 'Seleccione...') {
                 $.ajax({
                     url: '/ajax/cargo/' + empresaId,
                     type: "GET",
                     dataType: "json",
                     success: function(data) {
                         $('select[name="cargo_id"]').empty();
                         $('select[name="cargo_id"]').append('<option value="">Seleccione una opcion</option>');
                         $.each(data, function(key, value) {
                             $('select[name="cargo_id"]').append('<option value="' + key + '">' + value + '</option>');
                         });
                     },
                 });
             } else {
                 $('select[name="cargo_id"]').empty();
                 $('select[name="cargo_id"]').append('<option value="">Seleccione una empresa</option>');
             }
         });
         $('select[name="empresa_id"]').on('change', function() {
             var empresaId = $(this).val();
             if (empresaId && empresaId != 'Seleccione...') {
                 $.ajax({
                     url: '/ajax/division/' + empresaId,
                     type: "GET",
                     dataType: "json",
                     success: function(data) {
                         $('select[name="division_id"]').empty();
                         $('select[name="division_id"]').append('<option value="">Seleccione una opcion</option>');
                         $.each(data, function(key, value) {
                             $('select[name="division_id"]').append('<option value="' + key + '">' + value + '</option>');
                         });
                     },
                 });
             } else {
                 $('select[name="division_id"]').empty();
                 $('select[name="division_id"]').append('<option value="">Seleccione una empresa</option>');
             }
         });
         $('#password').blur(function() {
             var password = $('#password').val();
             if (password.length < 8) {
                 $('#error_pass_p').html('<label class="text-danger">El password Debe de tener 8 o mas Caracteres</label>');
                 $('#password').addClass('is-invalid');
                 $('#register').attr('disabled', 'disabled');
             } else {
                 $('#error_pass_p').empty();
                 $('#password').removeClass('is-invalid');
                 $('#password').addClass('is-valid');
                 $('#register').attr('disabled', false);
             }
         });
         $('#password-confirm').blur(function() {
             var password = $('#password').val();
             var confirm = $('#password-confirm').val();
             if (password != confirm) {
                 $('#error_pass').html('<label class="text-danger">El password no conside</label>');
                 $('#password-confirm').addClass('is-invalid');
                 $('#password').addClass('is-invalid');
                 $('#register').attr('disabled', 'disabled');
             } else {
                 $('#error_pass').empty();
                 $('#password-confirm').removeClass('is-invalid');
                 $('#password').removeClass('is-invalid');
                 $('#password-confirm').addClass('is-valid');
                 $('#password').addClass('is-valid');
                 $('#register').attr('disabled', false);
             }
         });
         $('#password_new').blur(function() {
             var password = $('#password_new').val();
             if (password.length < 8) {
                 $('#error_pass_n').html('<label class="text-danger">El password Debe de tener 8 o mas Caracteres</label>');
                 $('#password_new').addClass('is-invalid');
                 $('#register').attr('disabled', 'disabled');
             } else {
                 $('#error_pass_n').empty();
                 $('#password_new').removeClass('is-invalid');
                 $('#password_new').addClass('is-valid');
                 $('#register').attr('disabled', false);
             }
         });
         $('#password-new-confirm').blur(function() {
             var password = $('#password_new').val();
             var confirm = $('#password-new-confirm').val();
             if (password != confirm) {
                 $('#error_pass_n_c').html('<label class="text-danger">El password no conside</label>');
                 $('#password_new').removeClass('is-valid');
                 $('#password-new-confirm').addClass('is-invalid');
                 $('#password_new').addClass('is-invalid');
                 $('#register').attr('disabled', 'disabled');
             } else {
                 $('#error_pass_n_c').empty();
                 $('#password-new-confirm').removeClass('is-invalid');
                 $('#password_new').removeClass('is-invalid');
                 $('#password-new-confirm').addClass('is-valid');
                 $('#password_new').addClass('is-valid');
                 $('#register').attr('disabled', false);
             }
         });
         $('#email').blur(function() {
             var error_email = '';
             var email = $('#email').val();
             var _token = $('input[name="_token"]').val();
             var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
             if (!filter.test(email)) {
                 $('#error_email').html('<label class="text-danger">Correo electronico invalido</label>');
                 $('#email').addClass('is-invalid');
                 $('#register').attr('disabled', 'disabled');
             } else {
                 $.ajax({
                     url: "ajax/validateEmail",
                     method: "POST",
                     data: {
                         email: email,
                         _token: _token
                     },
                     success: function(result) {
                         if (result == 'unique') {
                             $('#error_email').html('<label class="text-success">Correo electronico disponible</label>');
                             $('#email').removeClass('is-invalid');
                             $('#email').addClass('is-valid');
                             $('#register').attr('disabled', false);
                         } else {
                             $('#error_email').html('<label class="text-danger">Correo electronico no disponible</label>');
                             $('#email').addClass('is-invalid');
                             $('#register').attr('disabled', 'disabled');
                         }
                     }
                 })
             }
         });
     });
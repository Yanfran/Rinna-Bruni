$(document).ready(function() {
    $(".select").select2({
        placeholder: "Seleccione..."
    });
    $(".select_estado").select2({
        placeholder: "Seleccione un pais"
    });
    $(".select_sub").select2({
        placeholder: "Seleccione un giro"
    });
    $('select[name="pais_id"]').on('change', function() {
        var paisId = $(this).val();
        if (paisId && paisId != 'Seleccione...') {
            $.ajax({
                url: '/ajax/estado/' + paisId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#estado_id').empty();
                    $('#estado_id').append('<option></option>');
                    if (data.length == 0) {
                        $('#tag').append('Los Estados para este pais se encuentran Inactivos o no se han registrado ninguno.  </small><small><a href="/estado" title="">Administrar Estados</a>');
                    }
                    $.each(data, function(key, value) {
                        $('#estado_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        } else {
            $('#estado_id').empty();
            $('#estado_id').append('<option></option>');
            $('#tag').empty();
        }
    });
    $('#giro_id').on('change', function() {
        var giroId = $(this).val();
        if (giroId && giroId != 'Seleccione...') {
            $.ajax({
                url: '/ajax/subgiro/' + giroId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#subgiro_id').empty();
                    $('#subgiro_id').append('<option></option>');
                    if (data.length == 0) {
                        $('#tag_giro').append('Los Sub giros para este giro de empresa se encuentran Inactivos o no se han registrado ninguno.  </small><small><a href="/subgiro" title="">Administrar Sub giros</a>');
                    }
                    $.each(data, function(key, value) {
                        $('#subgiro_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        } else {
            $('#subgiro_id').empty();
            $('#subgiro_id').append('<option></option>');
            $('#tag_giro').empty();
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
            if (password.length < 8) {
                $('#error_pass_p').html('<label class="text-danger">El password Debe de tener 8 o mas Caracteres</label>');
                $('#password').addClass('is-invalid');
                $('#register').attr('disabled', 'disabled');
                return false;
            } else {
                $('#error_pass').empty();
                $('#password-confirm').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');
                $('#password-confirm').addClass('is-valid');
                $('#password').addClass('is-valid');
                $('#register').attr('disabled', false);
            }
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
                url: "{{ route('validateEmail') }}",
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
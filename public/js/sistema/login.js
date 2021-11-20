function validarUsuario() {
    var r = 0;
    if ($("#login").val() == '' || $("#password").val() == '') {
        new PNotify({
            title: 'ERROR',
            text: 'Completa los campos',
            type: 'error',
            styling: 'bootstrap3'
        });
        return true;
    } else {
        new PNotify({
            title: 'POPGYM',
            text: 'POPGYM',
            type: 'success',
            styling: 'bootstrap3'
        });
        return false;
    }
}

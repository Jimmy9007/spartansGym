function confirmarCerrar() {
    Swal.fire({
        title: 'DESEAS CERRAR EL FORMULARIO?',
        text: "JOSANDRO",
        icon: 'warning',
//        imageUrl: 'https://dobleclick.net.co/wp-content/uploads/2019/07/logo.png',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: '<i class="fa fa-close"></i> Confirmar',
        cancelButtonText: 'Cancelar',
    }).then(function (result) {
        if (result.value) {
            return false;
        } else {
            $('#modalFormulario').modal('toggle');
        }

    });
}
function bloqueoAjax() {
    $.blockUI(
            {
                message: $('#msgBloqueo'),
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .85,
                    color: '#fff',
                    'z-index': 10000
                }
            }
    );
    $('.blockOverlay').attr('style', $('.blockOverlay').attr('style') + 'z-index: 1100 !important');
}


function verAgregarUsuario() {
    $.get('add', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idUsuario) {
    $.get('detail', {idUsuario: idUsuario}, setFormulario);
    bloqueoAjax();
}
function verEditar(idUsuario) {
    $.get('edit', {idUsuario: idUsuario}, setFormulario);
    bloqueoAjax();
}
function verActivarUsuario() {
    $.get('verActivarUsuario', {}, setFormulario);
    bloqueoAjax();
}
function activarUsuario(idUsuario) {
    Swal.fire({
        title: 'DESEAS ACTIVAR EL USUARIO DEL SISTEMA?',
        text: "No podras revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#blue',
        cancelButtonColor: '#aaa',
        confirmButtonText: '<i class="fa fa-close"></i> Confirmar',
        cancelButtonText: 'Cancelar',
    }).then(function (result) {
        if (result.value) {
            $.get('activarUsuarioNow', {idUsuario: idUsuario});
            bloqueoAjax();
            window.location.reload();
        } else {
            return false;
        }
    });
}
function eliminarUsuario() {
    var pk_usuario_id = $('#pk_usuario_id').val();
    Swal.fire({
        title: 'DESEAS ELIMINAR EL USUARIO DEL SISTEMA?',
        text: "No podras revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: '<i class="fa fa-close"></i> Confirmar',
        cancelButtonText: 'Cancelar',
    }).then(function (result) {
        if (result.value) {
            $.get('eliminarUsuario', {pk_usuario_id: pk_usuario_id});
            bloqueoAjax();
            window.location.reload();
        } else {
            return false;
        }
    });
}
//--------------------
function setFormulario(datos) {
    //console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------
function confirmAdd() {
    if (confirm("DESEA REGISTRAR ESTE USUARIO?")) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}
//------------------------------------------------------------------------------
function detalleUsuario(idUsuario) {
    $.post('getDetalleUsuario', {idUsuario: idUsuario}, getDetalleUsuario);
    bloqueoAjax();
}
function medidasUsuario(idUsuario) {
     $.get('getMedidasUsuario', {idUsuario: idUsuario}, getMedidasUsuario);
     bloqueoAjax();
}
function getDetalleUsuario(datos) {
    $("#divDetalle").html(datos);
    $("#dlgDetalles").modal('show');
}
function getMedidasUsuario(datos) {
    $("#divMedidas").html(datos);
    $("#dlgMedidas").modal('show');
}
//------------------------------------------------------------------------------
function getLogin(idClienteEmpleado) {
    $("#loginRegistro").val('');
    $("#password").val('');
    $("#passwordseguro").val('');
    $("#nombreApellido").val('');
    $("#genero").val('');
    if (idClienteEmpleado !== '') {
        $("#loginRegistro").attr('readonly', true);
        $("#loginRegistro").attr('required', false);
        $("#nombreApellido").attr('readonly', true);
        $("#nombreApellido").attr('required', false);
        $("#genero").attr('readonly', true);
        $("#genero").attr('required', false);
        $.get('getLogin', {idClienteEmpleado: idClienteEmpleado}, setLogin, 'json');
    } else {
        $("#loginRegistro").attr('readonly', false);
        $("#loginRegistro").attr('required', true);
        $("#nombreApellido").attr('readonly', false);
        $("#nombreApellido").attr('required', true);
        $("#genero").attr('readonly', false);
        $("#genero").attr('required', true);
    }
}
function setLogin(datos) {
    if (parseInt(datos['error']) === 1) {
        alert("SE HA PRESENTADO UN INCONVENIENTE AL TRATAR DE OBTENER EL LOGIN DE USUARIO. POR FAVOR, INTENTE DE NUEVO. EN CASO DE PERSISTIR EL INCONVENIENTE COMUNIQUESE CON EL ADMINISTRADOR");
        location.reload();
        return;
    }
    $("#loginRegistro").val(datos['login']);
    $("#nombreApellido").val(datos['nombreApellido']);
    $("#genero").val(datos['genero']);
    $("#password").val('@' + datos['login'] + '#');
    $("#passwordseguro").val('@' + datos['login'] + '#');
}

function verificarPassword() {
    if ($("#password").val() !== '') {
        if ($("#password").val().length < 6) {
            alert("EL PASSWORD DEBE TENER AL MENOS 6 CARACTERES");
            $("#password").attr('type', 'password');
            $("#passwordseguro").attr('type', 'password');
            $("#password").val('');
            $("#password").focus();
            return;
        }
    }
    if ($("#password").val() !== '' && $("#passwordseguro").val() !== '') {
        if ($("#password").val() !== $("#passwordseguro").val()) {
            alert("EL PASSWORD Y SU CONFIRMACION NO COINCIDEN");
            $("#password").attr('type', 'password');
            $("#passwordseguro").attr('type', 'password');
            $("#password").val('');
            $("#passwordseguro").val('');
            $("#password").focus();
        }
    }
}

function mostrarPassword(mostrar, input) {
    if (mostrar) {
        $("#password").attr('type', 'text');
    } else {
        $("#password").attr('type', 'password');
    }
}

function mostrarPasswordConfirm(mostrar) {
    if (mostrar) {
        $("#passwordseguro").attr('type', 'text');
    } else {
        $("#passwordseguro").attr('type', 'password');
    }
}

function guardarNuevoPassword() {
    alert("vamos en la funcion de java script guardarNuevoPassword");
    if (confirm("PARA QUE EL CAMBIO DE CONTRASEÑA SEA REGISTRADO LA SESION ACTUAL DEBE CERRARSE \n ¿ DESEA REGISTRAR EL CAMBIO DE CONTRASEÑA ?")) {
        $.post('../../usuarios/usuarios/cambiarcontrasena', $("#formCambiarcontrasena").serialize(), setNuevoPassword, 'json');
        bloqueoAjax();
    }
    return false;
}

function setNuevoPassword(respuesta) {
    switch (parseInt(respuesta['error'])) {
        case 0:
            alert("LA CONTRASEÑA FUE ACTUALIZADA EN JOSANDRO");
            location.href = '/josandro/login/login/cerrarSesion';
            break;
        case 1:
            alert("SE HA PRESENTADO UN ERROR, LA CONTRASEÑA NO FUE ACTUALIZADA");
            $('#modalFormulario').modal('hide');
            break;
        case 2:
            alert("ERROR, LA CONTRASEÑA ACTUAL ES INCORRECTA");
            $('#modalFormulario').modal('hide');
            break;
    }
    return false;
}

function verOcultarClaves() {
    if ($("#password").attr('type') === 'text') {
        $("#password").attr('type', 'password');
        $("#passwordseguro").attr('type', 'password');
        $("#verOcultarClaves").html('<i class="fa fa-eye"></i> Ver Claves');
    } else {
        $("#password").attr('type', 'text');
        $("#passwordseguro").attr('type', 'text');
        $("#verOcultarClaves").html('<i class="fa fa-eye-slash"></i> Ocultar Claves');
    }
}
function verHuella(idUsuario) {
    $.get('verhuella', {idUsuario: idUsuario}, setFormulario);
    bloqueoAjax();
}

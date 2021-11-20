//------------------------------------------------------------------------------
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
                    'z-index': 2000
                }
            }
    );
}

//------------------------------------------------------------------------------
function editPerfil(idPerfil) {
    $.get('/administracion/perfil/edit', {idPerfil: idPerfil}, setFormulario);
    bloqueoAjax();
}
function editFotoperfil(idPerfil) {
    $.get('/administracion/perfil/editfoto', {idPerfil: idPerfil}, setFormulario);
    bloqueoAjax();
}
function verCambiarcontrasena() {
    $.get('/administracion/usuario/cambiarcontrasena', {}, setFormulario);
}
function setFormulario(datos) {
//    console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}
//----------------------------------------------------------------------------------

//mensajes
function init_compose(idUsuario) {
    $.get('/administracion/perfil/chat', {idUsuario: idUsuario}, init_wysiwyg);
    "undefined" != typeof $.fn.slideToggle && (console.log("init_compose"), $("#compose, .compose-close").click(function () {
        $(".compose").slideToggle()
    }))
}


function init_wysiwyg(datos) {
    $("#editor").html(datos);
    function b(a, b) {
        var c = "";
        "unsupported-file-type" === a ? c = "Unsupported format " + b : console.log("error uploading file", a, b), $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button><strong>File upload error</strong> ' + c + " </div>").prependTo("#alerts")
    }
    "undefined" != typeof $.fn.wysiwyg && (console.log("init_wysiwyg"), $(".editor-wrapper").each(function () {
        var a = $(this).attr("id");
        $(this).wysiwyg({toolbarSelector: '[data-target="#' + a + '"]', fileUploadError: b})
    }), window.prettyPrint, prettyPrint())
}

$("body").popover({selector: "[data-popover]", trigger: "click hover", delay: {show: 50, hide: 400}}), $(document).ready(function () {
    init_wysiwyg(), init_compose()
});
//fin mensajes

function verificarPassword() {
    if ($("#password").val() !== '') {
        if ($("#password").val().length < 6) {
            alert("EL PASSWORD DEBE TENER AL MENOS 6 CARACTERES");
            $("#password").attr('type', 'password');
            $("#passwordConfirm").attr('type', 'password');
            $("#password").val('');
            $("#password").focus();
            return;
        }
    }
    if ($("#password").val() !== '' && $("#passwordConfirm").val() !== '') {
        if ($("#password").val() !== $("#passwordConfirm").val()) {
            alert("EL PASSWORD Y SU CONFIRMACION NO COINCIDEN");
            $("#password").attr('type', 'password');
            $("#passwordConfirm").attr('type', 'password');
            $("#password").val('');
            $("#passwordConfirm").val('');
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
        $("#passwordConfirm").attr('type', 'text');
    } else {
        $("#passwordConfirm").attr('type', 'password');
    }
}

function guardarNuevoPassword() {
    if (confirm("PARA QUE EL CAMBIO DE CONTRASEÑA SEA REGISTRADO LA SESION ACTUAL DEBE CERRARSE \n ¿ DESEA REGISTRAR EL CAMBIO DE CONTRASEÑA ?")) {
        $.post('/administracion/usuario/cambiarcontrasena', $("#formCambiarcontrasena").serialize(), setNuevoPassword, 'json');
        bloqueoAjax();
    }
    return false;
}

function setNuevoPassword(respuesta) {
    switch (parseInt(respuesta['error'])) {
        case 0:
            alert("LA CONTRASEÑA FUE ACTUALIZADA EN POPGYM");
            location.href = '/login/login/cerrarSesion';
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
        $("#passwordConfirm").attr('type', 'password');
        $("#verOcultarClaves").html('<i class="fa fa-eye"></i> Ver Claves');
    } else {
        $("#password").attr('type', 'text');
        $("#passwordConfirm").attr('type', 'text');
        $("#verOcultarClaves").html('<i class="fa fa-eye-slash"></i> Ocultar Claves');
    }
}

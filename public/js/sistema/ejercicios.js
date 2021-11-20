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
function verAgregarEjercicios() {
    $.get('/administracion/ejercicios/add', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idEjercicios) {
    $.get('/administracion/ejercicios/detail', {idEjercicios: idEjercicios}, setFormulario);
    bloqueoAjax();
}
function verEditar(idEjercicios) {
    $.get('/administracion/ejercicios/edit', {idEjercicios: idEjercicios}, setFormulario);
    bloqueoAjax();
}
//--------------------
function setFormulario(datos) {
    //console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------
function detalleEjercicio(idEjercicio) {
    $.post('/administracion/ejercicios/getDetalleEjercicio', {idEjercicio: idEjercicio}, getDetalleEjercicio);
    bloqueoAjax();
    
}
function getDetalleEjercicio(datos) {
     $("#divDetalle").html(datos);
    $("#dlgDetalles").modal('show');
}
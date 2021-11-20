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
function verAgregarAsistencia() {
    $.get('/administracion/asistencia/add', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idAsistencia) {
    $.get('/administracion/asistencia/detail', {idAsistencia: idAsistencia}, setFormulario);
}
function verEditar(idAsistencia) {
    $.get('/administracion/asistencia/edit', {idAsistencia: idAsistencia}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    $("#divContenido").html(datos);
    $("#tblUsuarios").DataTable({
        responsive: true,
        "iDisplayLength": 25,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sLengthMenu": "Mostrar: _MENU_ registros por pagina",
            "sZeroRecords": "NO SE HA ENCONTRADO INFORMACION",
            "sInfo": "Mostrando <b>_START_</b> a <b>_END_</b> registros <br>TOTAL REGISTROS: <b>_TOTAL_</b> Registros</b>",
            "sInfoEmpty": "Mostrando 0 A 0 registros",
            "sInfoFiltered": "(Filtrados de un total de <b>_MAX_</b> registros)",
            "sLoadingRecords": "CARGANDO...",
            "sProcessing": "EN PROCESO...",
            "sSearch": "Buscar:",
            "sEmptyTable": "NO HAY INFORMACION DISPONIBLE PARA LA TABLA",
            "oPaginate": {
                "sFirst": "Inicio",
                "sLast": "Fin",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "aaSorting": [[0, "asc"]]
    });
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function seleccionarUsuario() {
    $.get('/administracion/usuario/seleccionarUsuario', {}, setSeleccionarUsuarios);
}

function setSeleccionarUsuarios(datos) {
    $("#divSeleccionar").html(datos);
    $("#tblUsuarios").DataTable({
        responsive: true,
        "iDisplayLength": 25,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sLengthMenu": "Mostrar: _MENU_ registros por pagina",
            "sZeroRecords": "NO SE HA ENCONTRADO INFORMACION",
            "sInfo": "Mostrando <b>_START_</b> a <b>_END_</b> registros <br>TOTAL REGISTROS: <b>_TOTAL_</b> Registros</b>",
            "sInfoEmpty": "Mostrando 0 A 0 registros",
            "sInfoFiltered": "(Filtrados de un total de <b>_MAX_</b> registros)",
            "sLoadingRecords": "CARGANDO...",
            "sProcessing": "EN PROCESO...",
            "sSearch": "Buscar:",
            "sEmptyTable": "NO HAY INFORMACION DISPONIBLE PARA LA TABLA",
            "oPaginate": {
                "sFirst": "Inicio",
                "sLast": "Fin",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "aaSorting": [[0, "asc"]]
    });
    $('#modalSeleccionar').modal('show');
}

function selectUsuario(idUsuario) {
    $("#fk_usuario_id").val(idUsuario);
    $.get('/administracion/asistencia/mensaje', {idUsuario: idUsuario}, );
}
function detalleAsistencia(idAsistencia) {
    $.post('/administracion/asistencia/getDetalleAsistencia', {idAsistencia: idAsistencia}, getDetalleAsistencia);

}
function getDetalleAsistencia(datos) {
    $("#divDetalle").html(datos);
    $("#dlgDetalles").modal('show');
}

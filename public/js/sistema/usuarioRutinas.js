function verAgregarUsuarioRutinas() {
    $.get('/administracion/usuariorutinas/add', {}, setFormulario);
}
function verAgregarRutinasAutomaticamente(idUsuario) {
    $.get('/administracion/rutinas/rutinaAutomatica', {idUsuario: idUsuario}, setFormulario);
}
function verDetalle(idRutinas) {
    $.get('/administracion/rutinas/detail', {idRutinas: idRutinas}, setFormulario);
}
function verEditar(idRutinas) {
    $.get('/administracion/rutinas/edit', {idRutinas: idRutinas}, setFormulario);
}
//--------------------
function verConfiguracion(idRutinas) {
    $.get('/administracion/rutinas/configuracion', {idRutinas: idRutinas}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}


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
    $("#idUsuarioSelect").val(idUsuario);
    $.get('/administracion/usuario/getUsuario', {idUsuario: idUsuario}, setUsuario);
}
function setUsuario(datos) {
    $("#modalSeleccionar").modal('hide');
    $("#divInfoUsuario").html(datos);
    $("#divInfoUsuario").show('slow');
}

function validarGuardar() {
    if ($("#pk_usuario_id").val() === '') {
        alert('DEBE SELECCIONAR UN USUARIO PARA CONTINUAR');
        $("#selectUsu").focus();
        return false;
    }
    if ($("#pk_rutina_id").val() === "") {
        alert('DEBE SELECCIONAR UNA RUTINA PARA CONTINUAR');
        $("#selectRut").focus();
        return false;
    }
    return confirm("¿ DESEA REGISTRAR LA RUTINA ?");
}




function seleccionarRutina() {
    $.get('/administracion/rutinas/seleccionarRutina', {}, setSeleccionarRutinas);
}
function setSeleccionarRutinas(datos) {
    $("#divSeleccionar").html(datos);
    $("#tblRutinas").DataTable({
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

function selectRutina(idRutina) {
    $("#idRutinaSelect").val(idRutina);
    $.get('/administracion/rutinas/getRutina', {idRutina: idRutina}, setRutina);
}
function setRutina(datos) {
    $("#modalSeleccionar").modal('hide');
    $("#divInfoRutina").html(datos);
    $("#divInfoRutina").show('slow');
    $("#pk_rutina_id").val($("#pk_rutina_id").val());
}

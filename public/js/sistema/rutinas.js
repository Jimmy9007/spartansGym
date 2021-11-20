function verAgregarRutinas() {
    $.get('/administracion/rutinas/add', {}, setFormulario);
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

//------------------------------------------------------------------------------


function anexarEjercicios(idRutina) {
    location.href = 'anexarEjercicios/' + idRutina;
}

//------------------------------------------------------------------------------

function seleccionarEjercicios(idRutina) {
    $.get('/administracion/ejercicios/seleccionarEjercicios', {idRutina: idRutina}, setSeleccionarRutinas);
}
function setSeleccionarRutinas(datos) {
    //console.log(datos)
    $("#divAnexarEjercicios").html(datos);
    $("#tblSeleccionarEjercicios").DataTable({
        "responsive": "true",
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

    $('#modalAnexarEjercicios').modal('show');
}


function setEjerciciosFormulario(datos) {
    if (parseInt(datos['OK']) > 0) {
        alert("EJERCICIO ANEXADA A LA RUTINA");
    } else {
        alert("SE HA PRESENTADO UN ERROR DE SISTEMA. << EJERCICIO NO ANEXADA >>");
    }
    window.location.reload();
}

function seleccionarInstructor() {
    $.get('/administracion/instructor/seleccionarInstructor', {}, setSeleccionarInstructores);
}
function setSeleccionarInstructores(datos) {
    $("#divSeleccionar").html(datos);
    $("#tblInstructores").DataTable({
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

function selectInstructor(idInstructor) {
    $.get('/administracion/instructor/getInstructor', {idInstructor: idInstructor}, setInstructor);
}
function setInstructor(datos) {
    $("#modalSeleccionar").modal('hide');
    $("#divInfoInstructor").html(datos);
    $("#divInfoInstructor").show('slow');
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
    $.get('/administracion/usuario/getUsuario', {idUsuario: idUsuario}, setUsuario);
}
function setUsuario(datos) {
    $("#modalSeleccionar").modal('hide');
    $("#divInfoUsuario").html(datos);
    $("#divInfoUsuario").show('slow');
    $("#fk_usuario_id").val($("#pk_usuario_id").val());
    $("#fk_instructor_id").val($("#pk_instructor_id").val());
}

function validarGuardar() {
    if ($("#fk_usuario_id").val() === '') {
        alert('DEBE SELECCIONAR UN USUARIO PARA CONTINUAR');
        $("#selectUsu").focus();
        return false;
    } else {
        return confirm("¿ DESEA REGISTRAR LA RUTINA ?");
    }

}

function setInfoEjercicio(idEjercicio) {
    $("#idEjercicioSelect").val(idEjercicio);
    $("#idRutinaSelect").val($("#pk_rutina_id").val());
    $("#repeticion").val('');
    $("#orden").val('');
    $("#divSetEjercicio input:checkbox:checked").each(function () {
        $(this).attr('checked', false);
    });
    $("#modalSetEjercicio").modal('show');
}

function selectEjercicios(idEjercicios, idRutinas) {
    if (confirm("¿DESEA ANEXAR ESTE EJERCICIO AL ACTUAL RUTINA?")) {
        $.get('/administracion/formulario/setEjerciciosFormulario', {idEjercicios: idEjercicios, idRutinas: idRutinas}, setEjerciciosFormulario, 'json');
    } else {
        alert("PREGUNTA << NO >> ANEXADA");
    }
}

function validarAnexarEjercicio() {
    if ($("#divSetEjercicio input:checkbox:checked").length == 0) {
        alert("DEBE SELECCIONAR AL MENOS UN DIA PARA EL EJERCICIO");
        return false;
    }
    return confirm("¿ DESEA ANEXAR ESTE EJERCICIO A LA RUTINA ACTUAL ?");
}
function setRutinaAutomaticamente() {
    if ($("#rutinaAutomaticamente").is(':checked')) {
        $("#pk_rutina_id").val(0);
        $("#DESCRIP_RUTINA").val('Hola Mundo');
    } else {
        $("#pk_rutina_id").val($("#fk_rutina_id_auto").val());
        $("#DESCRIP_RUTINA").val($("#DESCRIP_RUTINA_old").val());
    }
}
//para imprimir el detalle del ejercicio
function detalleEjercicio(idEjercicios) {//cuando presiona ver detalle del HTML
    $.post('/administracion/rutinas/getDetalleEjercicio', {idEjercicios: idEjercicios}, getDetalleEjercicio);

}
//para imprimir el detalle del ejercicio
function getDetalleEjercicio(datos) {
    $("#divDetalle").html(datos);
    $("#dlgDetalles").modal('show');
}

function eliminarEjercicio(idEjercicio) {
    var idRutina = $("#pk_rutina_id").val();
    if (confirm("¿ DESEA ELIMINAR ESTE EJERCICIO ?")) {
        $.post('/administracion/rutinas/deleteEjercicio', {idEjercicio: idEjercicio, idRutina: idRutina}, setEliminar, 'json');
        location.reload();
    }
}
function setEliminar(datos) {
    if (datos['eliminado']) {
        alert(" EJERCICIO ELIMINADO DEL SISTEMA ");
        location.reload();
    } else {
        alert(" EL EJERCICIO NO FUE ELIMINADO \n\n SE HA PRESENTADO UN COMPORTAMIENTO INESPERADO EN EL SISTEMA \n EN CASO DE PERSISTIR ESTE COMPORTAMIENTO COMUNIQUESE CON EL ADMINISTRADOR");
        location.reload();
    }
}

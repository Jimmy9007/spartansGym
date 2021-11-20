function verAgregarTurno() {
    $.get('/administracion/turno/add', {}, setFormulario);
}
function verDetalle(idTurno) {
    $.get('/administracion/turno/detail', {idTurno: idTurno}, setFormulario);
}
function verEditar(idTurno) {
    $.get('/administracion/turno/edit', {idTurno: idTurno}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------
function operacionHoras() {
    var hi = new Date($("#fechaInicio").val());
    var hf = new Date($("#fechaFinal").val());

    var diff_in_millisenconds = hf - hi;
    var thm = diff_in_millisenconds / 60000;

    $("#horasTurno").val(thm);

    var vh = $("#valorHora").val();
    var vht = (vh * thm) / 60;
    $("#pagoTotal").val(vht);
}

function downloadReporte() {
    if ($("#fk_usuario_id").val() == '') {
        alert("Seleccione un empleado");
        $("#fk_usuario_id").focus();
        false;
    } else {
        location.href = "?fechaReporteInicial=" + $("#fechaReporteInicial").val() + "&fechaReporteFinal=" + $("#fechaReporteFinal").val() + "&inst=" + $("#fk_usuario_id").val();
    }
}
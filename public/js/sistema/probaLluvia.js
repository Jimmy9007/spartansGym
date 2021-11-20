function verAgregarProbaLluvia() {
    $.get('/administracion/proballuvia/add', {}, setFormulario);
}
function verDetalle(idProbaLluvia) {
    $.get('/administracion/proballuvia/detail', {idProbaLluvia: idProbaLluvia}, setFormulario);
}
function verEditar(idProbaLluvia) {
    $.get('/administracion/proballuvia/edit', {idProbaLluvia: idProbaLluvia}, setFormulario);
}

function eliminar(idProbaLluvia) {
    if (confirm("¿ DESEA ELIMINAR ESTA PROBABILIDAD ?")) {
        $.post('/administracion/proballuvia/delete', {idProbaLluvia: idProbaLluvia}, setEliminarr, 'json');
    }
}
function setEliminarr(datos) {
    if (datos['eliminado']) {
        alert(" PROBABILIDAD ELIMINADA DEL SISTEMA ");
        location.reload();
    } else {
        alert(" LA PERSONA NO FUE ELIMINADA \n\n SE HA PRESENTADO UN COMPORTAMIENTO INESPERADO EN EL SISTEMA \n EN CASO DE PERSISTIR ESTE COMPORTAMIENTO COMUNIQUESE CON EL ADMINISTRADOR");
    }
}
//--------------------
function setFormulario(datos) {
//console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function knnlluvia() {
    var dias = $("#dias").val();
    var lluviosos = $("#lluviosos").val();
    $.post('/administracion/proballuvia/knnProbaLluvia', {dias: dias, lluviosos: lluviosos}, setFormulariosx, 'json');
}

function setFormulariosx(datos) {            
    $("#probabilidad").val(datos['Probabilidad']);
}


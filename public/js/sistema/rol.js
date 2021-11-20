function verAgregarRol() {
    $.get('/administracion/rol/add', {}, setFormulario);
}
function verDetalle(idRol) {
    $.get('/administracion/rol/detail', {idRol: idRol}, setFormulario);
}
function verEditar(idRol) {
    $.get('/administracion/rol/edit', {idRol: idRol}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

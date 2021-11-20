function verAgregarEntreno() {
    $.get('/administracion/entreno/add', {}, setFormulario);
}
function verDetalle(idEntreno) {
    $.get('/administracion/entreno/detail', {idEntreno: idEntreno}, setFormulario);
}
function verEditar(idEntreno) {
    $.get('/administracion/entreno/edit', {idEntreno: idEntreno}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function verAgregarPersonalizado() {
    $.get('/administracion/personalizado/add', {}, setFormulario);
}
function verDetalle(idPersonalizado) {
    $.get('/administracion/personalizado/detail', {idPersonalizado: idPersonalizado}, setFormulario);
}
function verEditar(idPersonalizado) {
    $.get('/administracion/personalizado/edit', {idPersonalizado: idPersonalizado}, setFormulario);
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
    $("#divAnexarUsuario").html(datos); //pone los datos
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
    $('#modalAnexarUsuario').modal('show'); //este muetra mustra los datosyy
}

function selectUsuario(idUsuario) {
    $.get('/administracion/usuario/getUsuario', {idUsuario: idUsuario}, setUsuario);
}
function setUsuario(datos) {
    $("#modalAnexarUsuario").modal('hide');
    $("#divInfoUsuario").html(datos);
    $("#divInfoUsuario").show('slow');
    $("#fk_usuario_id").val($("#pk_usuario_id").val());
}
function validarGuardar() {
    if ($("#fk_usuario_id").val() === '') {
        alert('DEBE SELECCIONAR UN USUARIO PARA CONTINUAR');
//        $("#selectUsu").focus();
        return false;
    }
    return confirm("¿ DESEA REGISTRAR EL PERSONALIZADO ?");
}

//------------------------------------------------------------------------------

var map = null;
var infoWindow = null;
var marker = null;

function openInfoWindow() {
    var markerLatLng = marker.getPosition();
    infoWindow.setContent([
        'La posicion del marcador es: <br>',
        'Latitud: ',
        markerLatLng.lat(),
        '<br>Longitud: ',
        markerLatLng.lng(),
        '<br>Arrastrame y haz click para actualizar la posicion.'
    ].join(''));
    infoWindow.open(map, marker);
}

function closeInfoWindow() {
    infoWindow.close();
}

function abrirMapa() {
    map = null;
    if ($("#actionMap").attr('class') === 'cerrado') {
        $("#map_canvas").show('slow');
        setTimeout(cargarMapa, 500);
        $("#actionMap").attr('class', 'abierto');
        $("#txtImgMaps").html('Cerrar Google Maps');
    } else {
        $("#map_canvas").hide('slow');
        $("#actionMap").attr('class', 'cerrado');
        $("#txtImgMaps").html('Abrir Google Maps');
    }
}

function cargarMapa() {
    var myLatlng = new google.maps.LatLng(2.447633, -76.616641);
    var myOptions = {
        zoom: 8,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map($("#map_canvas").get(0), myOptions);
    map.addListener('click', function (e) {
        placeMarkerAndPanTo(e.latLng, map);
    });
}

function placeMarkerAndPanTo(latLng, map) {
    eliminarMarkets();
    marker = new google.maps.Marker({
        position: latLng,
        map: map
    });
    $("#latitud").val(latLng.lat());
    $("#longitud").val(latLng.lng());
//    map.panTo(latLng);
//    console.log(latLng.lng() + ' ' + latLng.lat());
}

function eliminarMarkets() {
    if (marker !== null) {
        marker.setMap(null);
    }
}

function limpiarCoordenadas() {
    $("#latitud").val('');
    $("#longitud").val('');
    eliminarMarkets();
}

//------------------------------------------------------------------------------
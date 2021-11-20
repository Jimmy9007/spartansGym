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
function confirmarCerrar() {
    Swal.fire({
        title: 'DESEAS CERRAR EL FORMULARIO?',
        text: "JOSANDRO",
        icon: 'warning',
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
//-----------------------------GUARDAR------------------------------------------
function verAgregarVenta() {
    $.get('registrar', {}, setFormulario);
    bloqueoAjax();
}
function setFormulario(datos) {
    //console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}
function buscarProducto() {
    $.get('/administracion/producto/seleccionarProducto', {}, setSeleccionarProductos);
}
function selectProducto(idProducto) {
    $.get('/administracion/producto/getProducto', {idProducto: idProducto}, setProducto);
}
function agregar(idProducto, nombre, precio) {
    var id = $('#incremental').val();
    var cantidad = $('#cantidadVenta').val();
    var tabla = '<tr id="fila_' + id + '">' +
            '<td><p id="idProductoTxt_' + id + '"></p><fieldset hidden><input type="number" id="idProducto_' + id + '" name="idProducto_' + id + '" readonly></fieldset></td></td>' +
            '<td><p id="nombre_' + id + '"></p></td>' +
            '<td><p id="precio_' + id + '"></p></td>' +
            '<td><input type="number" min="1" class="form-control" placeholder="Ingrese Cantidad" onchange="calcular(' + id + ')" id="cantidad_' + id + '" name="cantidad_' + id + '" required></td>' +
            '<td class="cell"><p id="montoTxt_' + id + '"></p><fieldset hidden><input type="number" id="monto_' + id + '" name="monto_' + id + '" readonly></fieldset></td>' +
            '<td><a href="javascript:quitarCobro(' + id + ')" title="Quitar cobro"><i class="fa fa-trash"></i></a></td>' +
            '</tr>';
    if ($('#trInicial').length > 0) {
        $('#tblCobros tbody').html(tabla);
    } else {
        $('#tblCobros').append(tabla);
    }
    $('#cantidad_' + id).val(cantidad);
    $('#modalAddProducto').modal('hide');
    $('#modalAnexarProducto').modal('hide');
    $('#idProducto_' + id).val(idProducto);
    $('#idProductoTxt_' + id).text(idProducto);
    $('#nombre_' + id).text(nombre);
    $('#precio_' + id).text(precio);
    $('#incremental').val(parseInt($('#incremental').val()) + 1);
    calcular(id);
}
function calcular(id) {
    var nValor = $('#precio_' + id).text();
    var nCantidad = $('#cantidad_' + id).val();
    var valorTotal = nCantidad * nValor;
    $('#montoTxt_' + id).text(valorTotal);
    $('#monto_' + id).val(valorTotal);
    var sumTotal = 0;
    $(".cell").each(function (i) {
        sumTotal = sumTotal + parseFloat($(this).text());
    });
    $('#total').text(sumTotal);

}
function quitarCobro(id) {
    $("#fila_" + id).remove();
    calcular(id);
}
function registrarVenta() {
    if ($('#total').text() == 0 || $('#total').text() == 'NaN') {
        Swal.fire('FALTA IMPLEMENTAR PRODUCTOS', 'SPARTANS', 'error');
        return false;
    } else {
        var confirmar = confirm("DESEA REGISTRAR LA VENTA ?");
        if (confirmar == true) {
            bloqueoAjax();
            return true;
        } else {
            return false;
        }
    }
}
//-------------------------------EDITAR-----------------------------------------
function anexarArticulo(idVenta) {
    location.href = 'anexarArticulo/' + idVenta;
}
function seleccionarProducto(idVenta) {
    $.get('/administracion/producto/seleccionarProductoEditar', {idVenta: idVenta}, setSeleccionarProductos);
    $("#idVentaSelect").val(idVenta);
}
function selectProductoEditar(idProducto) {
    $.get('/administracion/producto/getProductoEditar', {idProducto: idProducto}, setProducto);
}
function validarGuardar() {
    if ($("#fk_producto_id").val() == '') {
        alert('FALTA IMPLEMENTAR PRODUCTOS');
        return false;
    }
    if ($("#cantidadVenta").val() == '') {
        alert('DEBE INGRESAR CANTIDAD');
        return false;
    }
}
//-----------------------------ELIMINAR-----------------------------------------
function eliminarArticulo(idVenta, idProducto) {
    Swal.fire({
        title: 'DESEAS ELIMINAR EL PRODUCTO?',
        text: "SPARTANS",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: '<i class="fa fa-close"></i> Confirmar',
        cancelButtonText: 'Cancelar',
    }).then(function (result) {
        if (result.value) {
            $.get('/administracion/venta/eliminarArticulo', {idVenta: idVenta, idProducto: idProducto}, setEliminar, 'json');
            bloqueoAjax();
        } else {
            return false;
        }
    });
}
function eliminar(idVenta) {
    Swal.fire({
        title: 'DESEA ELIMINAR ESTA VENTA ?',
        text: "SPARTANS",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: '<i class="fa fa-close"></i> Confirmar',
        cancelButtonText: 'Cancelar',
    }).then(function (result) {
        if (result.value) {
            $.get('delete', {idVenta: idVenta}, setEliminar, 'json');
            bloqueoAjax();
        } else {
            return false;
        }
    });
}
function setEliminar(datos) {
    if (datos['eliminado']) {
        window.location.reload();
    } else {
        Swal.fire('ERROR AL ELIMINAR', 'SPARTANS', 'error');
    }
}
//------------------------------------------------------------------------------
function setSeleccionarProductos(datos) {
    $("#divAnexarProducto").html(datos);//pone los datos
    $("#tblProductos").DataTable({
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
    $('#modalAnexarProducto').modal('show');//este muetra mustra los datos
}
function setProducto(datos) {
    $("#divAddProducto").html(datos);//pone los datos
    $('#modalAddProducto').modal('show');//este muetra mustra los datos
    $("#fk_producto_id").val($("#pk_producto_id").val());
    $("#divInfoProducto").show('slow');
    limpiarCampo();
}
function limpiarCampo() {
    $("#cantidadVenta").val("");
    $("#valorTotal").val("");
    $("#ganancia").val("");
}
function operacionesVenta() {
    var cantidad = $("#cantidadVenta").val();
    var precio = $("#precioVenta").val();
    var precioCosto = $("#precioCosto").val();

    var vTotal = cantidad * precio;
    var vGanancia = (precio - precioCosto) * cantidad;
    $("#valorTotal").val(vTotal);
    $("#ganancia").val(vGanancia);

}
//------------------------------------------------------------------------------
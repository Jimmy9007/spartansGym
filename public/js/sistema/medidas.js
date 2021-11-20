function verAgregarMedidas() {
    $.get('/administracion/medidas/add', {}, setFormulario);
}
function verDetalle(idMedidas) {
    $.get('/administracion/medidas/detail', {idMedidas: idMedidas}, setFormulario);
}
function verEditar(idMedidas) {
    $.get('/administracion/medidas/edit', {idMedidas: idMedidas}, setFormulario);
}
//--------------------
function verConfiguracion(idMedidas) {
    $.get('/administracion/medidas/configuracion', {idMedidas: idMedidas}, setFormulario);
}
//--------------------
function setFormulario(datos) {
//console.log(datos);
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

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
    limpiarCampo();
}

function validarGuardar() {
    if ($("#fk_usuario_id").val() === '') {
        alert('DEBE SELECCIONAR UN USUARIO PARA CONTINUAR');
//        $("#selectUsu").focus();
        return false;
    }
    return confirm("¿ DESEA REGISTRAR LA MEDIDA ?");
}
function calcularIMC() {
    var estaura = $("#ESTATURA").val();
    var peso = $("#PESO").val();
    var IMC = peso / Math.pow(estaura, 2);
    if (IMC < 17) {
        $("#IMC").val("Desnutrido");
    } else if ((IMC == 17) || (IMC < 18.5)) {
        $("#IMC").val("Bajo De Peso");
    } else if ((IMC == 18.5) || (IMC < 25)) {
        $("#IMC").val("Peso Normal");
    } else if ((IMC == 25) || (IMC < 30)) {
        $("#IMC").val("Sobrepeso");
    } else if ((IMC == 30) || (IMC < 35)) {
        $("#IMC").val("Obesidad Tipo 1");
    } else if ((IMC == 35) || (IMC < 40)) {
        $("#IMC").val("Obesidad Tipo 2");
    } else if (IMC >= 40) {
        $("#IMC").val("Obesidad Tipo 3");
    }
}

function ponerValorPliegues() {
    if (parseInt($("#ponerPliegues").val()) === 1) {

        $("#divTricipital").show('slow');
        $("#divTricipital").attr("required", "true");
        $("#divSubescapular").show('slow');
        $("#divSubescapular").attr("required", "true");
        $("#divSupraliaco").show('slow');
        $("#divSupraliaco").attr("required", "true");
        $("#divPlieAbdominal").show('slow');
        $("#divPlieAbdominal").attr("required", "true");
        $("#divCuadrcipital").show('slow');
        $("#divCuadrcipital").attr("required", "true");
        $("#divPeroneal").show('slow');
        $("#divPeroneal").attr("required", "true");
        $("#divPorGrasa").show('slow');
        $("#divPorGrasa").attr("required", "true");
        $("#divPGC").show('slow');
        $("#divPGC").attr("required", "true");
        $("#divPGK").show('slow');
        $("#divPGK").attr("required", "true");
        $("#divPMK").show('slow');
        $("#divPMK").attr("required", "true");
        limpiarCampo();
    }
    if (parseInt($("#ponerPliegues").val()) === 2) {

        $("#divTricipital").hide("slow");
        $("#divTricipital").removeAttr("required");
        $("#divSubescapular").hide("slow");
        $("#divSubescapular").removeAttr("required");
        $("#divSupraliaco").hide("slow");
        $("#divSupraliaco").removeAttr("required");
        $("#divPlieAbdominal").hide("slow");
        $("#divPlieAbdominal").removeAttr("required");
        $("#divCuadrcipital").hide('slow');
        $("#divCuadrcipital").removeAttr("required");
        $("#divPeroneal").hide('slow');
        $("#divPeroneal").removeAttr("required");
        $("#divPorGrasa").show('slow');
        $("#divPorGrasa").attr("required", "true");
        $("#divPGC").show('slow');
        $("#divPGC").attr("required", "true");
        $("#divPGK").show('slow');
        $("#divPGK").attr("required", "true");
        $("#divPMK").show('slow');
        $("#divPMK").attr("required", "true");
    } else if (parseInt($("#ponerPliegues").val()) === 0) {

        $("#divTricipital").hide("slow");
        $("#divTricipital").removeAttr("required");
        $("#divSubescapular").hide("slow");
        $("#divSubescapular").removeAttr("required");
        $("#divSupraliaco").hide("slow");
        $("#divSupraliaco").removeAttr("required");
        $("#divPlieAbdominal").hide("slow");
        $("#divPlieAbdominal").removeAttr("required");
        $("#divCuadrcipital").hide('slow');
        $("#divCuadrcipital").removeAttr("required");
        $("#divPeroneal").hide('slow');
        $("#divPeroneal").removeAttr("required");
        $("#divPorGrasa").hide("slow");
        $("#divPorGrasa").removeAttr("required");
        $("#divPGC").hide("slow");
        $("#divPGC").removeAttr("required");
        $("#divPGK").hide("slow");
        $("#divPGK").removeAttr("required");
        $("#divPMK").hide("slow");
        $("#divPMK").removeAttr("required");
        limpiarCampoNo();
    }
}

//function calcularPorcentajeGrasa() {
//    var plieTriceps = $("#tricipital").val();
//    var SUBESCAP = $("#subescapular").val();
//    var SILIACO = $("#supraliaco").val();
//    var plieAbdominal = $("#plieAbdominal").val();
//    var plieMuslAnter = $("#cuadricipital").val();
//    var plieMedialPierna = $("#peroneal").val();
//    var sexo = $("#SEXO").val();
//    var fechaNaci = $("#FECHA_NAC_USU").val();
//    var FN = new Date(fechaNaci).getYear();
//    var hoy = new Date().getYear();
//    var edad = hoy - FN;
//
//    if ((sexo == 'Masculino') && (edad == 18 || edad <= 30)) {
//        var porcentajeGrasa = (parseFloat(plieTriceps) + parseFloat(SUBESCAP) + parseFloat(SILIACO) + parseFloat(plieAbdominal) + parseFloat(plieMuslAnter) + parseFloat(plieMedialPierna)) * 0.097 + 3.64;
//        $("#porGrasa").val(porcentajeGrasa);
//    } else if ((sexo == 'Masculino') && (edad > 30)) {
//        var porcentajeGrasa = (parseFloat(plieTriceps) + parseFloat(SUBESCAP) + parseFloat(SILIACO) + parseFloat(plieAbdominal) + parseFloat(plieMuslAnter) + parseFloat(plieMedialPierna)) * 0.1066 + 4.975;
//        $("#porGrasa").val(porcentajeGrasa);
//    } else if ((sexo == 'Femenino') && ((edad == 18) || (edad <= 30))) {
//        var porcentajeGrasa = (parseFloat(plieTriceps) + parseFloat(SUBESCAP) + parseFloat(SILIACO) + parseFloat(plieAbdominal) + parseFloat(plieMuslAnter) + parseFloat(plieMedialPierna)) * 0.217 - 4.47;
//        $("#porGrasa").val(porcentajeGrasa);
//    } else if ((sexo == 'Femenino') && (edad > 30)) {
//        var porcentajeGrasa = (parseFloat(plieTriceps) + parseFloat(SUBESCAP) + parseFloat(SILIACO) + parseFloat(plieAbdominal) + parseFloat(plieMuslAnter) + parseFloat(plieMedialPierna)) * 0.224 - 2.8;
//        $("#porGrasa").val(porcentajeGrasa);
//    }
//
//
//}

function calcularPorcentajeGrasa() {
    var sexo = $("#SEXO").val();
    var fechaNaci = $("#FECHA_NAC_USU").val();
    var FN = new Date(fechaNaci).getYear();
    var hoy = new Date().getYear();
    var edad = hoy - FN;
    var peso = $("#PESO").val();

    if (parseInt($("#ponerPliegues").val()) === 1) {
        var plieTriceps = $("#tricipital").val();
        var SUBESCAP = $("#subescapular").val();
        var SILIACO = $("#supraliaco").val();
        var plieAbdominal = $("#plieAbdominal").val();
        var plieMuslAnter = $("#cuadricipital").val();
        var plieMedialPierna = $("#peroneal").val();


        if (sexo == 'Masculino') {
            var porcentajeGrasa = 3.64 + (parseFloat(plieTriceps) + parseFloat(SUBESCAP) + parseFloat(SILIACO) + parseFloat(plieAbdominal) + parseFloat(plieMuslAnter) + parseFloat(plieMedialPierna)) * 0.097;
            $("#porGrasa").val(porcentajeGrasa);
            if (porcentajeGrasa < 4) {
                $("#PGC").val("Modelo Fitness");
            } else if (porcentajeGrasa <= 4 || porcentajeGrasa < 7) {
                $("#PGC").val("Atleta");
            } else if ((edad < 24) && (porcentajeGrasa <= 7 || porcentajeGrasa < 9)) {
                $("#PGC").val("Excelente");
            } else if ((edad < 24) && (porcentajeGrasa <= 9 || porcentajeGrasa < 15)) {
                $("#PGC").val("Bueno");
            } else if ((edad < 24) && (porcentajeGrasa <= 15 || porcentajeGrasa < 20)) {
                $("#PGC").val("Normal");
            } else if ((edad < 24) && (porcentajeGrasa <= 20 || porcentajeGrasa < 24)) {
                $("#PGC").val("Sobrepeso");
            } else if (edad < 24 && porcentajeGrasa >= 24) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 9 || porcentajeGrasa < 10)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 10 || porcentajeGrasa < 17)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 17 || porcentajeGrasa < 21)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 21 || porcentajeGrasa < 25)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa >= 25)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 11 || porcentajeGrasa < 12)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 12 || porcentajeGrasa < 18)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 18 || porcentajeGrasa < 22)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 22 || porcentajeGrasa < 26)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa >= 26)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 12 || porcentajeGrasa < 13)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 13 || porcentajeGrasa < 19)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 19 || porcentajeGrasa < 23)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 23 || porcentajeGrasa < 27)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa >= 27)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 13 || porcentajeGrasa < 14)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 14 || porcentajeGrasa < 20)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 20 || porcentajeGrasa < 24)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 24 || porcentajeGrasa < 28)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa >= 28)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 15 || porcentajeGrasa < 16)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 16 || porcentajeGrasa < 22)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 22 || porcentajeGrasa < 26)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 26 || porcentajeGrasa < 29)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa >= 29)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 17 || porcentajeGrasa < 18)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 18 || porcentajeGrasa < 24)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 24 || porcentajeGrasa < 27)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 27 || porcentajeGrasa < 30)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa >= 30)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 19 || porcentajeGrasa < 20)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 20 || porcentajeGrasa < 25)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 25 || porcentajeGrasa < 29)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 29 || porcentajeGrasa < 31)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa >= 31)) {
                $("#PGC").val("Obeso");
            } else if ((edad >= 60) && (porcentajeGrasa <= 20 || porcentajeGrasa < 21)) {
                $("#PGC").val("Excelente");
            } else if ((edad >= 60) && (porcentajeGrasa <= 21 || porcentajeGrasa < 26)) {
                $("#PGC").val("Bueno");
            } else if ((edad >= 60) && (porcentajeGrasa <= 26 || porcentajeGrasa < 30)) {
                $("#PGC").val("Normal");
            } else if ((edad >= 60) && (porcentajeGrasa <= 30 || porcentajeGrasa < 32)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad >= 60) && (porcentajeGrasa >= 32)) {
                $("#PGC").val("Obeso");
            }

        } else {
            var porcentajeGrasa = 4.56 + (parseFloat(plieTriceps) + parseFloat(SUBESCAP) + parseFloat(SILIACO) + parseFloat(plieAbdominal) + parseFloat(plieMuslAnter) + parseFloat(plieMedialPierna)) * 0.143;
            $("#porGrasa").val(porcentajeGrasa);
            if (porcentajeGrasa < 9) {
                $("#PGC").val("Modelo Fitness");
            } else if (porcentajeGrasa <= 9 || porcentajeGrasa < 15) {
                $("#PGC").val("Atleta");
            } else if ((edad < 24) && (porcentajeGrasa <= 15 || porcentajeGrasa < 16)) {
                $("#PGC").val("Excelente");
            } else if ((edad < 24) && (porcentajeGrasa <= 16 || porcentajeGrasa < 21)) {
                $("#PGC").val("Bueno");
            } else if ((edad < 24) && (porcentajeGrasa <= 21 || porcentajeGrasa < 26)) {
                $("#PGC").val("Normal");
            } else if ((edad < 24) && (porcentajeGrasa <= 26 || porcentajeGrasa < 31)) {
                $("#PGC").val("Sobrepeso");
            } else if (edad < 24 && porcentajeGrasa >= 31) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 16 || porcentajeGrasa < 17)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 17 || porcentajeGrasa < 22)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 22 || porcentajeGrasa < 27)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 27 || porcentajeGrasa < 32)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa >= 32)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 17 || porcentajeGrasa < 18)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 18 || porcentajeGrasa < 23)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 23 || porcentajeGrasa < 28)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 28 || porcentajeGrasa < 33)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa >= 33)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 19 || porcentajeGrasa < 20)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 20 || porcentajeGrasa < 24)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 24 || porcentajeGrasa < 29)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 29 || porcentajeGrasa < 34)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa >= 34)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 21 || porcentajeGrasa < 22)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 22 || porcentajeGrasa < 25)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 25 || porcentajeGrasa < 30)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 30 || porcentajeGrasa < 35)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa >= 35)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 23 || porcentajeGrasa < 24)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 24 || porcentajeGrasa < 27)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 27 || porcentajeGrasa < 32)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 32 || porcentajeGrasa < 37)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa >= 37)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 25 || porcentajeGrasa < 26)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 26 || porcentajeGrasa < 29)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 29 || porcentajeGrasa < 34)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 34 || porcentajeGrasa < 38)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa >= 38)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 26 || porcentajeGrasa < 27)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 27 || porcentajeGrasa < 30)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 30 || porcentajeGrasa < 35)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 35 || porcentajeGrasa < 39)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa >= 39)) {
                $("#PGC").val("Obeso");
            } else if ((edad >= 60) && (porcentajeGrasa <= 27 || porcentajeGrasa < 28)) {
                $("#PGC").val("Excelente");
            } else if ((edad >= 60) && (porcentajeGrasa <= 28 || porcentajeGrasa < 31)) {
                $("#PGC").val("Bueno");
            } else if ((edad >= 60) && (porcentajeGrasa <= 31 || porcentajeGrasa < 36)) {
                $("#PGC").val("Normal");
            } else if ((edad >= 60) && (porcentajeGrasa <= 36 || porcentajeGrasa < 40)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad >= 60) && (porcentajeGrasa >= 40)) {
                $("#PGC").val("Obeso");
            }
        }

        var PGK = porcentajeGrasa * peso * 0.01;
        $("#PGK").val(PGK);
        var PMK = peso - PGK;
        $("#PMK").val(PMK);
    } else {
        var peso = $("#PESO").val();
        var porcentajeGrasa = $("#porGrasa").val();
        if (sexo == 'Masculino') {
            if (porcentajeGrasa < 4) {
                $("#PGC").val("Modelo Fitness");
            } else if (porcentajeGrasa <= 4 || porcentajeGrasa < 7) {
                $("#PGC").val("Atleta");
            } else if ((edad < 24) && (porcentajeGrasa <= 7 || porcentajeGrasa < 9)) {
                $("#PGC").val("Excelente");
            } else if ((edad < 24) && (porcentajeGrasa <= 9 || porcentajeGrasa < 15)) {
                $("#PGC").val("Bueno");
            } else if ((edad < 24) && (porcentajeGrasa <= 15 || porcentajeGrasa < 20)) {
                $("#PGC").val("Normal");
            } else if ((edad < 24) && (porcentajeGrasa <= 20 || porcentajeGrasa < 24)) {
                $("#PGC").val("Sobrepeso");
            } else if (edad < 24 && porcentajeGrasa >= 24) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 9 || porcentajeGrasa < 10)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 10 || porcentajeGrasa < 17)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 17 || porcentajeGrasa < 21)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 21 || porcentajeGrasa < 25)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa >= 25)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 11 || porcentajeGrasa < 12)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 12 || porcentajeGrasa < 18)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 18 || porcentajeGrasa < 22)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 22 || porcentajeGrasa < 26)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa >= 26)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 12 || porcentajeGrasa < 13)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 13 || porcentajeGrasa < 19)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 19 || porcentajeGrasa < 23)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 23 || porcentajeGrasa < 27)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa >= 27)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 13 || porcentajeGrasa < 14)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 14 || porcentajeGrasa < 20)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 20 || porcentajeGrasa < 24)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 24 || porcentajeGrasa < 28)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa >= 28)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 15 || porcentajeGrasa < 16)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 16 || porcentajeGrasa < 22)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 22 || porcentajeGrasa < 26)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 26 || porcentajeGrasa < 29)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa >= 29)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 17 || porcentajeGrasa < 18)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 18 || porcentajeGrasa < 24)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 24 || porcentajeGrasa < 27)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 27 || porcentajeGrasa < 30)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa >= 30)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 19 || porcentajeGrasa < 20)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 20 || porcentajeGrasa < 25)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 25 || porcentajeGrasa < 29)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 29 || porcentajeGrasa < 31)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa >= 31)) {
                $("#PGC").val("Obeso");
            } else if ((edad >= 60) && (porcentajeGrasa <= 20 || porcentajeGrasa < 21)) {
                $("#PGC").val("Excelente");
            } else if ((edad >= 60) && (porcentajeGrasa <= 21 || porcentajeGrasa < 26)) {
                $("#PGC").val("Bueno");
            } else if ((edad >= 60) && (porcentajeGrasa <= 26 || porcentajeGrasa < 30)) {
                $("#PGC").val("Normal");
            } else if ((edad >= 60) && (porcentajeGrasa <= 30 || porcentajeGrasa < 32)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad >= 60) && (porcentajeGrasa >= 32)) {
                $("#PGC").val("Obeso");
            }

        } else {
            if (porcentajeGrasa < 9) {
                $("#PGC").val("Modelo Fitness");
            } else if (porcentajeGrasa <= 9 || porcentajeGrasa < 15) {
                $("#PGC").val("Atleta");
            } else if ((edad < 24) && (porcentajeGrasa <= 15 || porcentajeGrasa < 16)) {
                $("#PGC").val("Excelente");
            } else if ((edad < 24) && (porcentajeGrasa <= 16 || porcentajeGrasa < 21)) {
                $("#PGC").val("Bueno");
            } else if ((edad < 24) && (porcentajeGrasa <= 21 || porcentajeGrasa < 26)) {
                $("#PGC").val("Normal");
            } else if ((edad < 24) && (porcentajeGrasa <= 26 || porcentajeGrasa < 31)) {
                $("#PGC").val("Sobrepeso");
            } else if (edad < 24 && porcentajeGrasa >= 31) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 16 || porcentajeGrasa < 17)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 17 || porcentajeGrasa < 22)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 22 || porcentajeGrasa < 27)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa <= 27 || porcentajeGrasa < 32)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 24 || edad < 30) && (porcentajeGrasa >= 32)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 17 || porcentajeGrasa < 18)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 18 || porcentajeGrasa < 23)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 23 || porcentajeGrasa < 28)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa <= 28 || porcentajeGrasa < 33)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 30 || edad < 35) && (porcentajeGrasa >= 33)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 19 || porcentajeGrasa < 20)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 20 || porcentajeGrasa < 24)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 24 || porcentajeGrasa < 29)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa <= 29 || porcentajeGrasa < 34)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 35 || edad < 40) && (porcentajeGrasa >= 34)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 21 || porcentajeGrasa < 22)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 22 || porcentajeGrasa < 25)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 25 || porcentajeGrasa < 30)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa <= 30 || porcentajeGrasa < 35)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 40 || edad < 45) && (porcentajeGrasa >= 35)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 23 || porcentajeGrasa < 24)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 24 || porcentajeGrasa < 27)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 27 || porcentajeGrasa < 32)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa <= 32 || porcentajeGrasa < 37)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 45 || edad < 50) && (porcentajeGrasa >= 37)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 25 || porcentajeGrasa < 26)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 26 || porcentajeGrasa < 29)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 29 || porcentajeGrasa < 34)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa <= 34 || porcentajeGrasa < 38)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 50 || edad < 55) && (porcentajeGrasa >= 38)) {
                $("#PGC").val("Obeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 26 || porcentajeGrasa < 27)) {
                $("#PGC").val("Excelente");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 27 || porcentajeGrasa < 30)) {
                $("#PGC").val("Bueno");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 30 || porcentajeGrasa < 35)) {
                $("#PGC").val("Normal");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa <= 35 || porcentajeGrasa < 39)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad <= 55 || edad < 60) && (porcentajeGrasa >= 39)) {
                $("#PGC").val("Obeso");
            } else if ((edad >= 60) && (porcentajeGrasa <= 27 || porcentajeGrasa < 28)) {
                $("#PGC").val("Excelente");
            } else if ((edad >= 60) && (porcentajeGrasa <= 28 || porcentajeGrasa < 31)) {
                $("#PGC").val("Bueno");
            } else if ((edad >= 60) && (porcentajeGrasa <= 31 || porcentajeGrasa < 36)) {
                $("#PGC").val("Normal");
            } else if ((edad >= 60) && (porcentajeGrasa <= 36 || porcentajeGrasa < 40)) {
                $("#PGC").val("Sobrepeso");
            } else if ((edad >= 60) && (porcentajeGrasa >= 40)) {
                $("#PGC").val("Obeso");
            }
        }
        var PGK = porcentajeGrasa * peso * 0.01;
        $("#PGK").val(PGK);
        var PMK = peso - PGK;
        $("#PMK").val(PMK);

    }
}

function limpiarCampo() {
    $("#tricipital").val("");
    $("#subescapular").val("");
    $("#supraliaco").val("");
    $("#plieAbdominal").val("");
    $("#cuadrcipital").val("");
    $("#peroneal").val("");
    $("#porGrasa").val("");
}
function limpiarCampoNo() {
    $("#tricipital").val("");
    $("#subescapular").val("");
    $("#supraliaco").val("");
    $("#plieAbdominal").val("");
    $("#cuadrcipital").val("");
    $("#peroneal").val("");
    $("#porGrasa").val("0");
    $("#PGC").val("");
    $("#PGK").val("");
    $("#PMK").val("");
}
/**
 * JS Errores, para mostrar y dar tratamiento a errores.
 * Software Gestión de movilidad©
 * @author Ing. Luis Alberto Pérez González.
 * @version 3.3.6
 * @package js
 * @final
 */

        /**
         * Variable publica que contiene el mensaje de error de la respuesta JSON recibida del servidor.
         * @var {String}
         */
        var mensajeError = null;

/**
 * Variable publica que contiene el contador de tiempo de cierre de una alerta emergente.
 * @var {String}
 */
var counterTimer = 1;

/**
 * Variable publica que contiene el limite de tiempo de una alerta.
 * @var {String}
 */
var timeOff = 5;

/**
 * Variable publica que contiene la funcion timeout.
 * @var {String}
 */
var timeout;

/**
 * Variable publica que contiene texto adicional para el usuario cuando hay errores identificables.
 * @var {String}
 */
var adicional = null;

/**
 * Funcion para mostrar la ventana Modal.
 * @param {String} codigoHTML El codigo a mostrar en el mensaje.
 * @return {void} No se retorna ningun valor
 */
function mostrarVentanaModal(codigoHTML)
{
    $('#divAvisos').html(sanitizarHTML(codigoHTML));
    $('#divModalAvisos').modal('handleUpdate');
    $('#divModalAvisos').modal('show');
}

/**
 * Funcion para mostrar una alerta.
 * @param {String} codigoHTML El codigo a mostrar en el mensaje.
 * @return {void} No se retorna ningun valor
 */
function mostrarAlerta(codigoHTML, tipo)
{
    if (timeout) {
        clearTimeout(timeout);
    }
    counterTimer = 1;
    switch (tipo) {
        case "success":
            $("#messageAlert").attr("class", "alert alert-success");
            timeOff = 5;
            break;
        case "warning":
            $("#messageAlert").attr("class", "alert alert-warning");
            timeOff = 15;
            break;
        case "error":
            $("#messageAlert").attr("class", "alert alert-danger");
            timeOff = 25;
            break;
        default:
            $("#messageAlert").attr("class", "alert alert-default");
            timeOff = 40;
            break;
    }
    $('#messageAlert').html(sanitizarHTML(codigoHTML));
    $('#messageAlert').slideDown('slow');
    closeAlert();
}

/**
 * Funcion para mostrar una alerta emergente.
 * @param {String} codigoHTML El codigo a mostrar en el mensaje.
 * @return {void} No se retorna ningun valor
 */
function alertaEmergente(codigoHTML)
{
    $('#mensaje').html(sanitizarHTML(codigoHTML));
    $('#alertEmergente').modal({backdrop: false});
    $('#alertEmergente').modal('show');
    if (timeout) {
        clearTimeout(timeout);
    }
    counterTimer = 1;
    closeModalAlert();
}

/**
 * Funcion para cerrar un modal por tiempo
 * @returns {void}
 */
function closeModalAlert() {
    if (counterTimer == 5) {
        $('#alertEmergente').modal('hide');
    } else {
        counterTimer++;
        timeout = setTimeout(closeAlert, 950);
    }
}

/**
 * Funcion para cerrar un alert dependiendo de la seleccion
 * @returns {undefined}
 */
function closeAlert() {
    if (counterTimer == timeOff) {
        $('#messageAlert').slideUp('slow');
    } else {
        counterTimer++;
        timeout = setTimeout(closeAlert, 950);
    }
}

/**
 * Funcion para mostrar los errores personalizados.
 * @param {Object} elError Objeto del tipo JSON con el error recibido del Servidor
 * @param {Object} estatusRespuesta Objeto del tipo JSON con el estatus recibido del Servidor
 * @param {Object} jqXHR Objeto del tipo JSON con el error recibido del Servidor
 * @return {void} No se retorna ningun valor
 */
function mostrarError(elError, estatusRespuesta, jqXHR)
{
    mensajeError = '';
    //mensajeError += '<strong>Codigo de Apache: ' + jqXHR.status + ' ' + jqXHR.statusText + '<br />';
    //estatusPeticion(jqXHR);
    switch (elError.code) {
        case - 32000:
            mensajeError += claveError(elError.message); //elError.message;
            break;
        case - 32600:
            mensajeError += 'Petición invalida.';
            break;
        case - 32601:
            mensajeError += 'El metodo en el servicio web<br />No se encontro.';
            break;
        case - 32602:
            mensajeError += 'Parametros invalidos.';
            break;
        case - 32603:
            mensajeError += 'Error Interno.';
            break;
        case - 32700:
            mensajeError += 'Error de sintaxis.';
            break;
        default:
            mensajeError += 'Error ' + elError.code + '<br />' + elError.message;
            break;
    }
    errorHTTP(jqXHR);
    mensajeError += adicional;
    console.log(jqXHR.responseJSON.error.message);
    mostrarVentanaModal(mensajeError);
}

/**
 * Funcion para mostrar los errores personalizados en un alert.
 * @param {Object} elError Objeto del tipo JSON con el error recibido del Servidor
 * @param {Object} estatusRespuesta Objeto del tipo JSON con el estatus recibido del Servidor
 * @param {Object} jqXHR Objeto del tipo JSON con el error recibido del Servidor
 * @return {void} No se retorna ningun valor
 */
function mostrarErrorAlert(elError, estatusRespuesta, jqXHR)
{
    //mensajeError = '<strong>Traza del Error ' + idLlamada + '</strong><br />';
    mensajeError = '<strong>Error ' + jqXHR.status + ': <br />';
    //estatusPeticion(jqXHR);
    switch (elError.code) {
        case - 32000:
            mensajeError += claveError(elError.message); //elError.message;
            break;
        case - 32600:
            mensajeError += 'Petición invalida.';
            break;
        case - 32601:
            mensajeError += 'El metodo en el servicio web no se encontro.';
            break;
        case - 32602:
            mensajeError += 'Parametros invalidos.';
            break;
        case - 32603:
            mensajeError += 'Error Interno.';
            break;
        case - 32700:
            mensajeError += 'Error de sintaxis.';
            break;
        default:
            mensajeError += elError.code + '<br />' + elError.message;
            break;
    }
    errorHTTP(jqXHR);
    mensajeError += adicional;
    console.log(jqXHR.responseJSON.error.message);
    mostrarAlerta(mensajeError, "error")
}

/**
 * Funcion para mostrar los Errores de la peticionJSON.
 * @param {Object} jqXHR Objeto del tipo JSON con el error recibido del Servidor
 * @param {Object} estatusError Objeto del tipo JSON con el error recibido del Servidor
 * @param {Object} textoError Objeto del tipo JSON con el error del Servidor
 * @return {void} No se devuelve ningun valor
 */
function mostrarErrorJSON(jqXHR, estatusError, textoError)
{
    mensajeError = '';
    //mensajeError += '<strong>Codigo de Apache:<strong> ' + jqXHR.status + ' ' + jqXHR.statusText + '<br />';
    //estatusPeticion(jqXHR);
    switch ($.trim(estatusError)) {
        case 'timeout':
            mensajeError += 'El tiempo de Espera, se agoto.<br />Probablemente, existen intermitencias en su conexion a Internet.';
            break;
        case 'error':
            mensajeError += 'Se recibio una respuesta.<br />Pero devolvio el siguiente error:<br />' + textoError;
            break;
        case 'abort':
            mensajeError += 'Su navegador aborto la conexión al Servidor.<br />Por razones desconocidas.';
            break;
        case 'parsererror':
            mensajeError += 'Se recibio una respuesta.<br />Pero esta corrupta la misma, o incompleta.';
            break;
        default:
            mensajeError += 'Error desconocido: ' + $.trim(estatusError) + ':<br />' + textoError;
            break;
    }
    errorHTTP(jqXHR);
    mensajeError += adicional;
    //console.log(jqXHR.responseText);
    mostrarVentanaModal(mensajeError);
}

/**
 * Funcion para mostrar el Estatus al procesar la Peticion JSON.
 * @param {Object} jqXHR Objeto del tipo JSON con el error recibido del Servidor
 * @return {void} No se retorna ningun valor
 */
function estatusPeticion(jqXHR)
{
    switch (jqXHR.readyState) {
        case 0:
            mensajeError += '<strong>Estado:</strong> Petición no completa (readyState:0)<br />';
            break;
        case 1:
            mensajeError += '<strong>Estado:</strong> Conexión si se establecio (readyState:1)<br />';
            break;
        case 2:
            mensajeError += '<strong>Estado:</strong> Petición si se recibio (readyState:2)<br />';
            break;
        case 3:
            mensajeError += '<strong>Estado:</strong> Petición en procesamiento (readyState:3)<br />';
            break;
        case 4:
            mensajeError += '<strong>Estado:</strong> Petición finalizada y con respuesta (readyState:4)<br />';
            break;
        default:
            mensajeError += '<strong>Estado:</strong> Desconocido (readyState: ' + jqXHR.readyState + ')<br />';
            break;
    }
    mensajeError += '<br />';
}

/**
 * Funcion para mostrar el tipo de error HTTP al procesar la Peticion JSON.
 * @param {Object} jqXHR Objeto del tipo JSON con el error recibido del Servidor
 * @return {void} No se retorna ningun valor
 */
function errorHTTP(jqXHR)
{
    switch (jqXHR.status) {
        case 403:
            adicional = '<br />El acceso a este <i>SERVIDOR</i> se encuentra restringido';
            break;
        case 404:
            adicional = '<br />La página o URL no existe en este <i>SERVIDOR</i>';
            break;
        default:
            adicional = '';
            break;
    }
    return;
}

/**
 * Funcion para mostrar el tipo de error MySQL
 * @param {String} clave
 * @returns {String}
 */
function claveError(clave)
{
    var aux = Number(clave.substring(4, 8));
    if ($.isNumeric(aux)) {
        // Error Numerico de MySQL:
        switch (aux) {
            case 1054:
                return 'la columna para el criterio de comparacion no existe en la tabla de la base de datos.';
                break;
            case 1062:
                return 'YA existe un valor asi.<br />NO se aceptan duplicados.';
                break;
            case 1064:
                return 'Error de edicion.<br />Estructura de guardado incorrecta.';
                break;
            case 1136:
                return 'Formato incorrecto.<br />Faltan columnas por declarar en la estructura SQL.';
                break;
            case 1146:
                return 'La tabla de consulta no existe.<br />La tabla de referencia no existe en la base de datos.';
                break;
            case 1136:
                return 'Formato incorrecto.<br />Faltan columnas por declarar en la estructura SQL.';
                break;
            case 1406:
                return 'Excedes el tamaño permitido para el tipo de columna';
                break;
            case 1452:
                return 'Debes de completar los datos antes de enviar.';
                break;
            case 5011:
                return 'La conexion no tiene permisos de consultar contenido.';
                break;
            default:
                return 'Codigo de error de la base desconocido<br />Es necesario reportarlo al administrador: ' + aux;
                break;
        }
    } else {
        return clave;
    }
}
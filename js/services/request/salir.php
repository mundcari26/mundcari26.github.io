<?php

/**
 * Clase Salir, para brindar funcionalidades para Accesar a la Aplicacion.
 * Software PIT©
 * @author Ing. Luis Alberto Pérez González.
 * @version 1
 * @package peticion
 * @final
 */
final Class Salir {

    /**
     * Constructor
     * @access public
     * @return void
     */
    public function __construct() {
        session_start();
    }

    /**
     * Termina la sesion del Empleado
     * @access public
     * @return String Una cadena aleatoria de 40 caracteres.
     */
    public function terminarSesion() {
        $_SESSION["idUsuario"] = null;
        $_SESSION["usuario"] = null;
        $_SESSION["ipUsuario"] = null;
        $_SESSION["tokenUsuario"] = null;
        unset($_SESSION["idUsuario"]);
        unset($_SESSION["usuario"]);
        unset($_SESSION["ipUsuario"]);
        unset($_SESSION["tokenUsuario"]);
        session_unset();
        session_destroy();
        return sha1(md5(microtime()));
    }

    /**
     * Manda a pantalla, cuando se invoca la clase a cadena
     * @access public
     * @return string
     */
    public function __toString() {
        return '¿Que esperavas ver?';
    }

    /**
     * Evitar clonar la Calse
     * @access public
     * @throws Exception
     */
    public function __clone() {
        throw new Exception('Solo tengo clones de Dipper Pines.');
    }

    /**
     * El destructor de la clase Base.
     * @access public
     * @return void
     */
    public function __destruct() {
        //Vacio por el momento.
    }

}

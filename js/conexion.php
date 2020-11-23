<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Connection {
    
    /**
     * Objeto de Conexion.
     * @access protected
     * @var Object
     */
    protected $conexion;
    
    /**
     * Declaracion de objeto de conexion.
     * @access public
     * @return void
     */
    public function __construct() {
        error_reporting(E_ALL);
        $this->conexion = new mysqli mysqli("localhost","root","","mundodecaricatura");
        //$this->conexion = new mysqli("localhost", "root", "", "pit_v2");
        if (mysqli_connect_error()) {
            throw new Exception('La conexion al servidor no pudo ser completada, ointentelo nuevamente.');
            return;
        }
        if (!$this->conexion->set_charset("utf8")) {
            throw new Exception("Error al configurar conjunto de caracteres UTF8.<br />Conjunto actual:<br />" . $this->conexion->character_set_name());
        }
    }
    
    /**
     * Retorna el objeto de conexion.
     * @access public
     * @return void
     */
    public function getConnection() {
        return $this->conexion;
    }
    
    /**
     * Imprime en pantalla advertencia, cuando la clase es invocada a cadena.
     * @access public
     * @return string
     */
    public function __toString()
    {
        return 'El contenido de este archivo se encuentra temporalmente bloqueado. <br />Co: 403<br />Status:Forbbiden';
    }
    
    /**
     * Evita clonar la Clase.
     * @access public
     * @throws Exception
     */
    public function __clone()
    {
        throw new Exception("El archivo solicitado no permite la clonacion, intente nuevamente.");
    }

    /**
     * El Destructor de la Clase Base.
     * @access public
     * @return void
     */
    public function __destruct()
    {
        // Vacio por el momento.
    }

}

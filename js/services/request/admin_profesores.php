<?php

require_once 'base.php';
require_once 'peticiones.php';

/**
 * Clase Admin profesores, para brindar funcionalidades para Interactuar con la Aplicacion.
 * Software PIT©
 * @author Ing. Luis Alberto Pérez González.
 * @version 3.3.6
 * @package servicios
 * @final
 */
final Class Admin_profesores extends Base implements peticiones {

    /**
     * Atributo privado para el query SQL.
     * @access private
     * @var string 
     */
    private $_sql;

    /**
     * Atributo privado para el tipo de devolucion para el ResultSet.
     * @access private
     * @var int
     */
    private $_tipo;

    /**
     * Atributo privado para el ResultSet de MySQL.
     * @access private
     * @var object
     */
    private $_resultSet;

    /**
     * Atributo privado para un Registro del ResultSet de MySQL.
     * @access private
     * @var object
     */
    private $_registro;

    /**
     * Atributo privado con el criterio, para las busquedas.
     * @access private
     * @var String
     */
    private $_criterio;

    /**
     * Atributo privado con el nombre de UNA columna, para la busqueda
     * @access private
     * @var String
     */
    private $_columna;

    /**
     * Atributo privado con los nombres de TODAS las columnas
     * @access private
     * @return array
     */
    private $_columnas;

    /**
     * Constructor de la clase Distritos, para invocar el Constructor de la Clase heredada.
     * @access public
     */
    public function __construct() {
        if (parent::validaSesionUsuario()) {
            parent::__construct();
            $this->_columnas = array('');
        } else {
            throw new Exception("No tienes permisos.");
            return;
        }
    }

    /**
     * Funcion para listar los Registros de la tabla cregion3.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @return object El Juego de Resultados.
     */
    public function listar($tipo) {
        $this->_tipo = intval($tipo);
        $this->_sql = "select t0.*,t1.id as id_division,t1.short_name from profesores t0, divisiones t1 where t0.id_division=t1.id and " .
                "t0.id > 1 and t0.active = b'1' order by t0.nombre,t0.apellido_p,t0.apellido_m asc;";
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para listar los Registros de la tabla divisiones.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @return object El Juego de Resultados.
     */
    public function comboDivisiones($tipo) {
        $this->_tipo = intval($tipo);
        $this->_sql = "select id,nombre as label from divisiones where active=b'1' order by nombre asc;";
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para buscar Registros en la tabla cregion3.
     * @access public
     * @param String $criterio El criterio a buscar en la tabla.
     * @param String $columna En que Columna se va a buscar en la tabla.
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @return object El juego de Resultados
     */
    public function buscar($criterio, $columna, $tipo) {
        return false;
    }

    /**
     * Funcion para insetar un Registro en la tabla tbra_off_address.
     * @access public
     * @param object $registro Es el registro que sera insertado.
     * @return int El ID del Registro insertado.
     */
    public function insertar($registro) {
        if (is_array($registro)) {
            $registro = (object) $registro;
        }
        $id = $this->agregarProfesor($registro);
        if ($id > 0) {
            $user = $this->formatear($registro->usuario, "Encriptalo");
            $pass = $this->formatear($registro->contrasena, "Encriptalo");
            $this->_sql = sprintf("INSERT INTO sesion VALUES(null,%s,%s,%s,b'1');", $user, $pass, $id);
            $sesion = $this->sentenciaSQL($this->_sql, 4);
            if ($sesion > 0) {
                return $id;
            } else {
                throw new Exception("El profesor no ha podido ser registrado.");
            }
        } else {
            throw new Exception('El registro no pudo ser agregado.');
        }
    }

    /**
     * Funcion para registrar a un nuevo profesor
     * @param object $registro El objeto con todos los datos del profesor que se esta registrando
     * @return int
     */
    private function agregarProfesor($registro) {
        $nombre = $this->formatear($registro->nombre, "Cadena");
        $apellido_p = $this->formatear($registro->apellido_p, "Cadena");
        $apellido_m = $this->formatear($registro->apellido_m, "Cadena");
        $fecha_nacimiento = $this->formatear($registro->fecha_nacimiento, "Cadena");
        $id_division = $this->formatear($registro->id_division, "Cadena");
        $this->_sql = sprintf("INSERT INTO profesores VALUES(null,%s,%s,%s,%s,%s,b'1');", $nombre, $apellido_p, $apellido_m, $fecha_nacimiento, $id_division);
        return $this->sentenciaSQL($this->_sql, 4);
    }

    /**
     * Funcion para actualizar una sedes
     * @access public
     * @param object $registro El registro a actualizarse.
     * @return int El numero de Registros afectados.
     */
    public function actualizar($registro) {
        if (is_array($registro)) {
            $registro = (object) $registro;
        }
        $statusDatos = $this->actualizarProfesor($registro);
        $idProf = $this->formatear($registro->id, "Entero");
        if (strlen($registro->usuario) > 0 && strlen($registro->contrasena) > 0) {
            $user = $this->formatear($registro->usuario, "Encriptalo");
            $pass = $this->formatear($registro->contrasena, "Encriptalo");
            $this->_sql = sprintf("UPDATE sesion SET u=%s, p=%s WHERE id_profesor=%s and active = 1;", $user, $pass, $idProf);
        } else if (strlen($registro->usuario) > 0 && strlen($registro->contrasena) == 0) {
            $user = $this->formatear($registro->usuario, "Encriptalo");
            $this->_sql = sprintf("UPDATE sesion SET u=%s WHERE id_profesor=%s and active = 1;", $user, $idProf);
        } else if (strlen($registro->usuario) == 0 && strlen($registro->contrasena) > 0) {
            $pass = $this->formatear($registro->contrasena, "Encriptalo");
            $this->_sql = sprintf("UPDATE sesion SET p=%s WHERE id_profesor=%s and active = 1;", $pass, $idProf);
        }
        $statusUser = $this->sentenciaSQL($this->_sql, 5);
        if ($statusDatos == 1 || $statusUser == 1) {
            return 1;
        } else {
            throw new Exception('Error al actualizar el registro.');
        }
    }

    /**
     * Funcion para actualizar los datos del profesor
     * @param object $registro El objeto con los datos del profesor
     * return int
     */
    private function actualizarProfesor($registro) {
        $id = $this->formatear($registro->id, "Entero");
        $nombre = $this->formatear($registro->nombre, "Cadena");
        $apellido_p = $this->formatear($registro->apellido_p, "Cadena");
        $apellido_m = $this->formatear($registro->apellido_m, "Cadena");
        $fecha_nacimiento = $this->formatear($registro->fecha_nacimiento, "Cadena");
        $id_division = $this->formatear($registro->id_division, "Cadena");
        $this->_sql = sprintf("UPDATE profesores SET nombre=%s,apellido_p=%s,apellido_m=%s,fecha_nacimiento=%s,id_division=%s WHERE id=%s LIMIT 1;", $nombre, $apellido_p, $apellido_m, $fecha_nacimiento, $id_division, $id);
        return $this->sentenciaSQL($this->_sql, 5);
    }

    /**
     * Funcion para eliminar un Registro en la tabla cregion3.
     * @access public
     * @param object $registro
     * @return int El Numero de Registros afectados.
     */
    public function borrar($registro) {
        return false;
    }

    /**
     * Funcion para dar de baja un Registro en la tabla tbranch_office.
     * @access public
     * @param object $registro
     * @return int El Numero de Registros afectados.
     */
    public function bajar($registro) {
        $id = intval($registro);
        $this->_sql = sprintf("UPDATE profesores SET active=b'0' WHERE id=%s LIMIT 1;", $id);
        $aux = $this->sentenciaSQL($this->_sql, 5);
        if ($aux == 1) {
            return 1;
        } else {
            throw new Exception('El registro no ha sido dado de baja.');
        }
    }

    /**
     * El destructor de la Clase Empresas
     */
    public function __destruct() {
        error_reporting(E_ALL);
    }

}

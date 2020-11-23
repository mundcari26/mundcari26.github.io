<?php

require_once 'base.php';
require_once 'peticiones.php';

/**
 * Clase Principal, para brindar funcionalidades para Accesar a la Aplicacion.
 * Software PIT©
 * @author Ing. Luis Alberto Pérez González.
 * @version 3.3.6
 * @package servicios
 * @final
 */
final Class Principal extends Base implements peticiones {

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
        return false;
    }

    /**
     * Funcion para listar los Registros de la tabla cregion3.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @return object El Juego de Resultados.
     */
    public function getDivision($tipo) {
        $this->_tipo = intval($tipo);
        $this->_sql = "select *,(select count(t0.id_alumno) as total_alumnos from alumnos_grupo t0,grupos t1,carreras t2,divisiones t3,alumnos t4 ".
                "where t0.id_grupo=t1.id and t1.id_carrera=t2.id and t2.id_division=t3.id and t0.id_alumno=t4.id and t3.id = dv.id) as total_alumnos ".
                "from divisiones dv where dv.id >= 1;";
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para listar los Registros de la tabla cregion3.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @return object El Juego de Resultados.
     */
    public function getCarreras($tipo) {
        $this->_tipo = intval($tipo);
        $this->_sql = "select cs.nombre_carrera,cs.clave_carrera,cs.color,(select count(t0.id_alumno) as total_alumnos from alumnos_grupo t0,grupos t1,".
                "carreras t2 where t0.id_grupo=t1.id and t1.id_carrera=t2.id and t2.id=cs.id) as alumnos_carrera from ".
                "carreras cs where cs.id>=1 order by cs.nombre_carrera asc;";
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
        return false;
    }

    /**
     * Funcion para actualizar una sedes
     * @access public
     * @param object $registro El registro a actualizarse.
     * @return int El numero de Registros afectados.
     */
    public function actualizar($registro) {
        return false;
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
        return false;
    }

    /**
     * El destructor de la Clase Empresas
     */
    public function __destruct() {
        error_reporting(E_ALL);
    }

}

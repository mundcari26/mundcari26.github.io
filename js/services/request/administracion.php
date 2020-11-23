<?php

require_once 'base.php';

/**
 * Clase Administracion, para brindar funcionalidades para Accesar a la Aplicacion.
 * Software PIT©
 * @version 3.3.6
 * @package servicios
 * @final
 */
final Class Administracion extends Base {

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
     * Constructor de la clase Distritos, para invocar el Constructor de la Clase heredada.
     * @access public
     */
    public function __construct() {
        if (parent::validaSesionUsuario()) {
            parent::__construct();
        } else {
            throw new Exception("No tienes permisos.");
            return;
        }
    }

    /**
     * Funcion para listar las divisiones registradas.
     * @access public
     * @return object El Juego de Resultados.
     */
    public function listar_divisiones() {
        $this->_sql = "SELECT id,short_name FROM divisiones WHERE active=b'1' ORDER BY short_name ASC;";
        return $this->sentenciaSQL($this->_sql, 2);
    }

    /**
     * Funcion para listar las divisiones registradas.
     * @access public
     * @return object El Juego de Resultados.
     */
    public function listar_periodos_anio() {
        $date = date('Y');
        $this->_sql = sprintf("SELECT id,nombre_cuatrimestre as periodo,ehe FROM cuatrimestre WHERE nombre_cuatrimestre LIKE '%s-%%' ORDER BY nombre_cuatrimestre ASC;", $date);
        return $this->sentenciaSQL($this->_sql, 2);
    }

    /**
     * Funcion para obtener la configuracion de aplicacion de cuestionario.
     * @access public
     * @return object El Juego de Resultados.
     */
    public function obtenerConfiguracion() {
        $listaDivisiones = $this->listar_divisiones();
        $nDivs = count($listaDivisiones);
        $asociaciones = array();
        for ($i = 0; $i < $nDivs; $i++) {
            $c1 = intval($listaDivisiones[$i]["id"]);
            $this->_sql = sprintf("SELECT t0.id,t0.nombre,t0.ehe FROM grupos t0, carreras t1 WHERE t1.id=t0.id_carrera and t1.id_division=%s and t0.active=b'1';", $c1);
            array_push($asociaciones, array("div" => $listaDivisiones[$i]["short_name"], "data" => $this->sentenciaSQL($this->_sql, 2)));
        }
        array_push($asociaciones, array("periodos" => $this->listar_periodos_anio()));
        return $asociaciones;
    }

    /**
     * Funcion para guardar las preferencias de aplicacion de cuestionarios.
     * @access public
     * @return object El Juego de Resultados.
     */
    public function guardarPrefs($registros) {
        $_aux = $this->sentenciaSQL("begin;", 0);
        try {
            $nAp = "'" . intval($registros[0]["nAp"]) . "'";
            $updateNap = sprintf("UPDATE configs SET valor=%s WHERE clave='nAp' LIMIT 1;", $nAp);
            $statusUpNap = $this->sentenciaSQL($updateNap, 5);
            $_aux = $this->sentenciaSQL("UPDATE cuatrimestre SET ehe=b'0' WHERE id>=1;", 5);
            $idPeriodo = intval($registros[1]["id_periodo"]);
            $updatePeriodo = sprintf("UPDATE cuatrimestre SET ehe=b'1', active_from=CURRENT_TIMESTAMP WHERE id=%s;", $idPeriodo);
            $statusUpNap = $this->sentenciaSQL($updatePeriodo, 5);

            $nGroups = count($registros);
            $total = 0;
            $_aux = $this->sentenciaSQL("UPDATE grupos SET ehe=b'0' WHERE active=b'1' and id>=1;", 5);
            for ($i = 2; $i < $nGroups; $i++) {
                $id = intval($registros[$i]["id"]);
                $toUp = sprintf("UPDATE grupos SET ehe=b'1' WHERE id=%s and active=b'1' LIMIT 1;", $id);
                $statusUpGroup = $this->sentenciaSQL($toUp, 5);
                if ($statusUpGroup == 1) {
                    $total++;
                }
            }
            if (($nGroups-2) == $total) {
                $_aux = $this->sentenciaSQL("commit;", 0);
                return 1;
            } else {
                throw new Exception("Error al actualiar los grupos de aplicación.");
            }
        } catch (Exception $ex) {
            $_aux = $this->sentenciaSQL("rollback;", 0);
            throw new Exception($ex->getMessage());
        }
    }

}

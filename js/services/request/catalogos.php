<?php

require_once 'base.php';

/**
 * Clase Catalogos, para brindar funcionalidades a la Aplicacion.
 * Software PIT©
 * @author Ing. Luis Alberto Pérez González.
 * @version 3.3.6
 * @package servicios
 * @final
 */
final Class Catalogos extends Base {

    /**
     * Funcion para listar las divisiones disponibles.
     * @access public
     * @return object El Juego de Resultados.
     */
    public function getPreguntas() {
        $this->_sql = "SELECT idBanco1, Pregunta, Rcorrecta, incorrectaA, incorrectaB , incorrectaC FROM banco1;";
        return $this->sentenciaSQL($this->_sql, 2);
    }

    /**
     * Funcion para listar las carreras disponibles de la division seleccionada.
     * @access public
     * @return object El Juego de Resultados.
     */
    public function getCarreras($idDivision) {
        $idDivision = intval($idDivision);
        $this->_sql = sprintf("select id,nombre_carrera from carreras where active=b'1' and id_division=%s order by nombre_carrera asc;", $idDivision);
        return $this->sentenciaSQL($this->_sql, 2);
    }

    /**
     * Funcion para listar los grupos disponibles de la carrera seleccionada.
     * @access public
     * @return object El Juego de Resultados.
     */
    public function getGrupos($idCarrera) {
        $idCarrera = intval($idCarrera);
        $this->_sql = sprintf("select id,nombre from grupos where ehe=b'1' and active=b'1' and id_carrera=%s order by nombre asc;", $idCarrera);
        return $this->sentenciaSQL($this->_sql, 2);
    }

}

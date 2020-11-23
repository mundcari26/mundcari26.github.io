<?php

require_once 'base.php';
require_once 'peticiones.php';

/**
 * Clase Estadisticas, para brindar funcionalidades para Accesar a la Aplicacion.
 * Software PIT©
 * @author Ing. Luis Alberto Pérez González.
 * @version 3.3.6
 * @package servicios
 * @final
 */
final Class Estadisticas extends Base implements peticiones {

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
     * Constante que tiene el numero de preguntas en comparacion
     * @access private
     * @final
     * @var int 
     */
    private $_num_pregs = 20;
    private $_num_alums_test;
    private $_relInter = null;
    private $_inters = array(
        array("Muy alto", "rgba(112,173,71,0.7)"),
        array("Alto", "rgba(169,208,142,0.7)"),
        array("Por encima del promedio", "rgba(198,224,180,0.7)"),
        array("Promedio alto", "rgba(225,225,0,0.7)"),
        array("Promedio", "rgba(225,225,102,0.7)"),
        array("Promedio bajo", "rgba(225,225,153,0.7)"),
        array("Por debajo del promedio", "rgba(225,51,0,0.7)"),
        array("Bajo", "rgba(225,0,0,0.7)"),
        array("Muy bajo", "rgba(204,0,0,0.7)")
    );
    private $_total_organizacion = array(
        "ma" => array(20, 20),
        "a" => array(19, 19),
        "pep" => array(18, 18),
        "pa" => array(16, 17),
        "p" => array(14, 15),
        "pb" => array(12, 13),
        "pdp" => array(11, 11),
        "b" => array(10, 10),
        "mb" => array(0, 9)
    );
    private $_total_tecnicas = array(
        "ma" => array(20, 20),
        "a" => array(18, 19),
        "pep" => array(17, 17),
        "pa" => array(16, 16),
        "p" => array(14, 15),
        "pb" => array(13, 13),
        "pdp" => array(12, 12),
        "b" => array(11, 11),
        "mb" => array(0, 10)
    );
    private $_total_motivacion = array(
        "ma" => array(20, 20),
        "a" => array(19, 19),
        "pep" => array(18, 18),
        "pa" => array(17, 17),
        "p" => array(16, 16),
        "pb" => array(15, 15),
        "pdp" => array(13, 14),
        "b" => array(12, 12),
        "mb" => array(0, 11)
    );
    private $_total_habilidades = array(
        "ma" => array(58, 60),
        "a" => array(54, 57),
        "pep" => array(51, 53),
        "pa" => array(47, 50),
        "p" => array(42, 46),
        "pb" => array(38, 41),
        "pdp" => array(34, 37),
        "b" => array(31, 33),
        "mb" => array(0, 30)
    );

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
     * Funcion para listar las divisiones registradas.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @return object El Juego de Resultados.
     */
    public function divisiones($tipo) {
        $this->_tipo = intval($tipo);
        $this->_sql = "select id,nombre from divisiones where id >= 1 order by nombre asc;";
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para listar las carreras asociadas a la division selecionada.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @param int $idDiv El id de la division seleccionada.
     * @return object El Juego de Resultados.
     */
    public function carreras($tipo, $idDiv) {
        $this->_tipo = intval($tipo);
        $idDiv = intval($idDiv);
        $this->_sql = sprintf("select id,concat(nombre_carrera,' (',clave_carrera,')') as carrera from carreras where id_division=%s "
                . "order by concat(nombre_carrera,' (',clave_carrera,')') asc;", $idDiv);
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para listar los grupos asociados a la carrera selecionada.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @param int $idCar El id de la carrera seleccionada.
     * @return object El Juego de Resultados.
     */
    public function grupos($tipo, $idCar) {
        $this->_tipo = intval($tipo);
        $idCar = intval($idCar);
        $this->_sql = sprintf("select id,nombre as grupo from grupos where id_carrera=%s order by nombre asc;", $idCar);
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para listar los periodos disponibles por el grupo seleccionado.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @param int $idGrupo El id del grupo a comparar.
     * @return object El Juego de Resultados.
     */
    public function periodos($tipo, $idGrupo) {
        $this->_tipo = intval($tipo);
        $idGrupo = intval($idGrupo);
        $this->_sql = sprintf("select distinct t0.id,t0.nombre_cuatrimestre as periodo from cuatrimestre t0,alumno_encuestas t1," .
                "alumnos_grupo t2 where t1.id_alumno=t2.id_alumno and t1.id_cuatrimestre=t0.id and t2.id_grupo=%s;", $idGrupo);
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para listar los periodos disponibles por el grupo seleccionado.
     * @access public
     * @param int $tipo Se utiliza para establecer el tipo de devolucion.
     * @param int $idDiv El id de la division a comparar.
     * @return object El Juego de Resultados.
     */
    public function periodosDiv($tipo, $idDiv) {
        $this->_tipo = intval($tipo);
        $idDiv = intval($idDiv);
        $this->_sql = sprintf("select distinct t0.id,t0.nombre_cuatrimestre as periodo from cuatrimestre t0,".
                "alumno_encuestas t1,alumnos_grupo t2, grupos t3, carreras t4 where t1.id_alumno=t2.id_alumno and ".
                "t1.id_cuatrimestre=t0.id and t2.id_grupo=t3.id and t3.id_carrera=t4.id and t4.id_division=%s;", $idDiv);
        return $this->sentenciaSQL($this->_sql, $this->_tipo);
    }

    /**
     * Funcion para calcular los resultados obtenidos por el grupo.
     * @access public
     * @param int $data El juego de valores seleccionados por el usuario.
     * @return object El Juego de Resultados.
     */
    public function restGrupo($data) {
        if (is_array($data)) {
            $data = (object) $data;
        }
        $itr = 0;
        $_generalizacion = array("nAlumnos" => 0, "data" => array(), "intrForm" => array());
        $_toIntrForm = array();
        try {
            $idGrupo = intval($data->idGrupo);
            $idCuatri = intval($data->idPer);
            $n_app = intval($data->n_app);
            $this->_sql = sprintf('select count(DISTINCT t0.id_alumno) as nAl 
                                   from alumnos_grupo t0, alumnos t1, alumno_encuestas t2, alumno_respuestas t3
                                   where t1.id=t0.id_alumno
                                   AND t1.id=t2.id_alumno
                                   AND t3.id_alumno_encuesta=t2.id
                                   AND t3.n_app = %s
                                   AND t0.id_grupo = %s
                                   AND t0.id_cuatrimestre= %s ', $n_app, $idGrupo, $idCuatri);
            $nAlums = $this->sentenciaSQL($this->_sql, 7);
            if (!isset($nAlums)) {
                throw new Exception("No existen datos para evaluar.");
            }
            $_generalizacion["nAlumnos"] = intval($nAlums["nAl"]);
            //$_generalizacion["nAlumnos"] = 5; demo
            if ($_generalizacion["nAlumnos"] == 0) {
                throw new Exception("Este grupo no ha realizado la encuesta.");
                exit();
            }
            $this->_sql = sprintf('SELECT f.id, f.nombre_formulario, COUNT( ar.respuesta) as total 
                                   FROM formularios f , preguntas p , alumno_respuestas ar , alumno_encuestas ae , alumnos a , alumnos_grupo ag
                                   WHERE f.id = p.id_formulario 
                                   AND p.id = ar.id_pregunta 
                                   AND ae.id = ar.id_alumno_encuesta
                                   AND a.id = ae.id_alumno
                                   AND a.id = ag.id_alumno
                                   AND ar.respuesta = "No"
                                   AND ag.id_grupo ='.$idGrupo.'
                                   AND ar.n_app = '.$n_app.'
                                   AND ag.id_cuatrimestre = '.$idCuatri.'
                                   GROUP BY f.id
                                   ORDER BY f.id');
            $results = $this->sentenciaSQL($this->_sql, 2);
            /* $results = array(
              array('id' => '1', 'nombre_formulario' => 'Encuesta para Organización del Estudio', 'total' => 68),
              array('id' => '2', 'nombre_formulario' => 'Encuesta sobre Tecnicas de Estudio', 'total' => 74),
              array('id' => '3', 'nombre_formulario' => 'Encuesta sobre Motivación de Estudio', 'total' => 79)
              ); */
            //procesar
            $_eqTot = ($this->_num_pregs * $_generalizacion["nAlumnos"]);
            // -> organizacion
            $this->calcRelInters("organizacion", $_generalizacion["nAlumnos"]);
            $inVal = intval($results[0]["total"]);
            foreach ($this->_relInter as $val) {
                $limMen = $val[0];
                $limMay = $val[1];
                if ($inVal >= $limMen && $inVal <= $limMay) {
                    array_push($_toIntrForm, array("title" => "Organización del estudio", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                }
                $itr++;
            }
            $itr = 0;
            // -> tecnicas
            $this->calcRelInters("tecnicas", $_generalizacion["nAlumnos"]);
            $inVal = intval($results[1]["total"]);
            foreach ($this->_relInter as $val) {
                $limMen = $val[0];
                $limMay = $val[1];
                if ($inVal >= $limMen && $inVal <= $limMay) {
                    array_push($_toIntrForm, array("title" => "Tecnicas de estudio", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                }
                $itr++;
            }
            $itr = 0;
            // -> motivacion
            $this->calcRelInters("motivacion", $_generalizacion["nAlumnos"]);
            $inVal = intval($results[2]["total"]);
            foreach ($this->_relInter as $val) {
                $limMen = $val[0];
                $limMay = $val[1];
                if ($inVal >= $limMen && $inVal <= $limMay) {
                    array_push($_toIntrForm, array("title" => "Motivación del estudio", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                }
                $itr++;
            }
            $itr = 0;
            // -> habilidades
            $this->calcRelInters("habilidades", $_generalizacion["nAlumnos"]);
            $inVal = intval($results[0]["total"]) + intval($results[1]["total"]) + intval($results[2]["total"]);
            foreach ($this->_relInter as $val) {
                $limMen = $val[0];
                $limMay = $val[1];
                if ($inVal >= $limMen && $inVal <= $limMay) {
                    array_push($_toIntrForm, array("title" => "Evaluacion del grupo", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                }
                $itr++;
            }
            $_generalizacion["intrForm"] = $_toIntrForm;
            return $_generalizacion;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Funcion para calcular los resultados obtenidos por el grupo.
     * @access public
     * @param int $data El juego de valores seleccionados por el usuario.
     * @return object El Juego de Resultados.
     */
    public function restAlumnsGrupo($data) {
        if (is_array($data)) {
            $data = (object) $data;
        }
        $itr = 0;
        $_generalizacion = array("nAlumnos" => 0, "data" => array(), "intrForm" => array());
        $alumno = array();
        try {
            $idGrupo = intval($data->idGrupo);
            $idCuatri = intval($data->idPer);
            $n_app = intval($data->n_app);
            $this->_sql = sprintf('select count(DISTINCT t0.id_alumno) as nAl 
                                   from alumnos_grupo t0, alumnos t1, alumno_encuestas t2, alumno_respuestas t3
                                   where t1.id=t0.id_alumno
                                   AND t1.id=t2.id_alumno
                                   AND t3.id_alumno_encuesta=t2.id
                                   AND t3.n_app = %s
                                   AND t0.id_grupo = %s
                                   AND t0.id_cuatrimestre= %s ', $n_app, $idGrupo, $idCuatri);
            $nAlums = $this->sentenciaSQL($this->_sql, 7);
            if (!isset($nAlums)) {
                throw new Exception("No existen datos para evaluar.");
            }
            $_generalizacion["nAlumnos"] = intval($nAlums["nAl"]);
            if ($_generalizacion["nAlumnos"] == 0) {
                throw new Exception("Este grupo no ha realizado la encuesta.");
                exit();
            }
            $this->_sql = "select t0.id,group_concat(t2.id separator ',') as pregs from formularios t0,encuestas t1," .
                    "preguntas t2 where t1.id=t0.id_encuesta and t2.id_formulario=t0.id group by t0.id;";
            $pregsXform = $this->sentenciaSQL($this->_sql, 2);
            $pOrgn = strval($pregsXform[0]["pregs"]);
            $pTecs = strval($pregsXform[1]["pregs"]);
            $pMots = strval($pregsXform[2]["pregs"]);
            $this->_sql = sprintf('SELECT t0.matricula,CONCAT(t0.apellido_p," ", IF( t0.apellido_m IS NULL," ",CONCAT(t0.apellido_m, " ")),t0.nombre) AS full_name,t1.id AS id_encuesta,(SELECT COUNT(o.respuesta)FROM  alumno_respuestas o WHERE o.respuesta like "No" AND o.id_alumno_encuesta = t1.id AND o.id_pregunta BETWEEN 1 AND 20) AS organizacion,(SELECT COUNT(o.respuesta) FROM alumno_respuestas o WHERE o.respuesta like "No" AND o.id_alumno_encuesta = t1.id AND o.id_pregunta BETWEEN 21 AND 40) AS tecnicas,(SELECT COUNT(o.respuesta) FROM alumno_respuestas o WHERE o.respuesta like "No" AND o.id_alumno_encuesta = t1.id AND o.id_pregunta BETWEEN 41 AND 60 ) AS motivacion FROM alumnos t0,alumno_encuestas t1, alumnos_grupo t2, alumno_respuestas t3 WHERE t0.id = t1.id_alumno AND t0.id = t2.id_alumno AND t1.id= t3.id_alumno_encuesta AND t2.id_grupo=(%s) AND t1.id_cuatrimestre=(%s) AND t3.n_app=(%s) GROUP BY t0.matricula,CONCAT(t0.apellido_p," ", IF( t0.apellido_m IS NULL," ",CONCAT(t0.apellido_m," ")),t0.nombre),t1.id;', $idGrupo, $idCuatri,$n_app);
            $results = $this->sentenciaSQL($this->_sql, 2);
            //procesar
            $_eqTot = ($this->_num_pregs * $_generalizacion["nAlumnos"]);
            for ($it = 0; $it < count($results); $it++) {
                $itr = 0;
                $_toIntrForm = array();
                // -> organizacion
                $inVal = intval($results[$it]["organizacion"]);
                foreach ($this->_total_organizacion as $key => $val) {
                    $limMen = $val[0];
                    $limMay = $val[1];
                    if ($inVal >= $limMen && $inVal <= $limMay) {
                        array_push($_toIntrForm, array("title" => "Organización del estudio", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                    }
                    $itr++;
                }
                $itr = 0;
                // -> tecnicas
                $inVal = intval($results[$it]["tecnicas"]);
                foreach ($this->_total_tecnicas as $key => $val) {
                    $limMen = $val[0];
                    $limMay = $val[1];
                    if ($inVal >= $limMen && $inVal <= $limMay) {
                        array_push($_toIntrForm, array("title" => "Tecnicas de estudio", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                    }
                    $itr++;
                }
                $itr = 0;
                // -> motivacion
                $inVal = intval($results[$it]["motivacion"]);
                foreach ($this->_total_motivacion as $key => $val) {
                    $limMen = $val[0];
                    $limMay = $val[1];
                    if ($inVal >= $limMen && $inVal <= $limMay) {
                        array_push($_toIntrForm, array("title" => "Motivación del estudio", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                    }
                    $itr++;
                }
                $itr = 0;
                // -> habilidades
                $inVal = intval($results[$it]["organizacion"]) + intval($results[$it]["tecnicas"]) + intval($results[$it]["motivacion"]);
                foreach ($this->_total_habilidades as $key => $val) {
                    $limMen = $val[0];
                    $limMay = $val[1];
                    if ($inVal >= $limMen && $inVal <= $limMay) {
                        array_push($_toIntrForm, array("title" => "Evaluacion general", "eval" => $inVal, "atrs" => $this->_inters[$itr]));
                    }
                    $itr++;
                }
                array_push($alumno, array("alumno" => $results[$it]["full_name"], "matricula" => $results[$it]["matricula"], "evals" => $_toIntrForm));
            }
            $_generalizacion["intrForm"] = $alumno;
            return $_generalizacion;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * 
     * @param String $tipo_calc El tipo de calculo que realizada
     * @param int $nAlums El numero de alumnos que participan en el test
     */
    public function calcRelInters($tipo_calc, $nAlums) {
        $nAlums = intval($nAlums);
        $this->_relInter = array();
        switch ($tipo_calc) {
            case "organizacion":
                foreach ($this->_total_organizacion as $key => $pot) {
                    $val1 = ($pot[0] * $nAlums) - ($pot[0] == 0 ? 0 : $nAlums - 1);
                    $val2 = $pot[1] * $nAlums;
                    array_push($this->_relInter, array($val1, $val2));
                }
                break;
            case "tecnicas":
                foreach ($this->_total_tecnicas as $key => $pot) {
                    $val1 = ($pot[0] * $nAlums) - ($pot[0] == 0 ? 0 : $nAlums - 1);
                    $val2 = $pot[1] * $nAlums;
                    array_push($this->_relInter, array($val1, $val2));
                }
                break;
            case "motivacion":
                foreach ($this->_total_motivacion as $key => $pot) {
                    $val1 = ($pot[0] * $nAlums) - ($pot[0] == 0 ? 0 : $nAlums - 1);
                    $val2 = $pot[1] * $nAlums;
                    array_push($this->_relInter, array($val1, $val2));
                }
                break;
            case "habilidades":
                foreach ($this->_total_habilidades as $key => $pot) {
                    $val1 = ($pot[0] * $nAlums) - ($pot[0] == 0 ? 0 : $nAlums - 1);
                    $val2 = $pot[1] * $nAlums;
                    array_push($this->_relInter, array($val1, $val2));
                }
                break;
            default: throw new Exception("Formato no especificado.");
                break;
        }
        return true;
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

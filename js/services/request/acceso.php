<?php

require_once 'base.php';

/**
 * Clase Acceso, para brindar funcionalidades para Accesar a la Aplicacion.
 * Software PIT©
 * @author Ing. Luis Alberto Pérez González.
 * @version 3.3.6
 * @package servicios
 * @final
 */
final Class Acceso extends Base {

    /**
     * Atributo privado para el query SQL.
     * @access private
     * @var string
     */
    private $_sql;

    /**
     * Atributo privado para el usuario.
     * @access private
     * @var string
     */
    private $_usuario;

    /**
     * Atributo privado para la Contraseña.
     * @access private
     * @var string
     */
    private $_contrasena;

    /**
     * Atributo privado para el id.
     * @access private
     * @var integer
     */
    private $_id;

    /**
     * Atributo privado para un registro de una tabla de MySQL.
     * @access private
     * @var object
     */
    private $_registro = null;

    /**
     * Atributo privado para todos los caracteres de la Validacion.
     * @access private
     * @var array
     */
    private $_arrayCaracteres;

    /**
     * Atributo privado para los caracteres usados en la Validacion.
     * @access private
     * @var String
     */
    private $_caracteres;

    /**
     * Generacion de Validacion
     * @return String Cadena aleatoria de 4 caracteres
     */
    public function traerValidacion() {
        $this->_arrayCaracteres = array("b", "c", "d", "f", "g", "h", "j", "k", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "y", "z");
        shuffle($this->_arrayCaracteres);
        $this->_caracteres = '';
        for ($indice = 0; $indice < 4; $indice++) {
            $this->_caracteres .= $this->_arrayCaracteres[rand(0, count($this->_arrayCaracteres) - 1)];
        }
        $_SESSION['validacion'] = $this->_caracteres;
        return $_SESSION['validacion'];
    }

    /**
     * Verifica que el usuario exista, sea unico y este activo.
     * @access public
     * @param string $usuario El usuario del empleado
     * @param string $contrasena La contraseña del empleado
     * @param string $validacion La validacion
     * @return string Una cadena aleatoria de 40 caracteres
     */
    public function verificarUsuario($usuario, $contrasena, $validacion) {
        if (isset($_SESSION["idUsuario"])) {
            throw new Exception("El Usuario ya tiene una Sesión abierta");
            return;
        }
        if ($_SESSION['validacion'] != trim($validacion)) {
            throw new Exception("La validación, es Incorrecta.<br />Se ha generado una nueva.");
        }
        $this->_id = 0;
        $this->_usuario = $this->formatear($usuario, "Encriptalo");
        $this->_contrasena = $this->formatear($contrasena, "Encriptalo");
        $this->_sql = sprintf("SELECT t0.id,concat(t1.nombre,' ',t1.apellido_p,(if(t1.apellido_m is null,'',".
                "concat(' ',t1.apellido_m)))) as usuario FROM sesion t0,profesores t1 WHERE t0.id_profesor=t1.id and ".
                "(u=%s AND p=%s) and t0.active=b'1' LIMIT 1;", $this->_usuario, $this->_contrasena);
        $this->_registro = $this->sentenciaSQL($this->_sql, 7);
        $this->_id = $this->_registro['id'];
        if ($this->_id > 0) {
            $_SESSION["idUsuario"] = $this->_id;
            $_SESSION["usuario"] = $this->_registro['usuario'];
            $_SESSION["ipUsuario"] = $_SERVER['REMOTE_ADDR'];
            $_SESSION["tokenUsuario"] = md5(sha1(session_id() . $_SERVER['REMOTE_ADDR'] . $_SESSION["idUsuario"]));
            $_SESSION["validacion"] = null;
            unset($_SESSION["validacion"]);
            return sha1(md5(microtime())); //Retornamos una cadena aleatoria de 40 caracteres
        } else {
            if (PRODUCCION) {
                throw new Exception("Error en sus Permisos del Servidor.");
            } else {
                throw new Exception("Error en sus Permisos del Servidor:<br />" . $this->_sql);
            }
            return;
        }
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

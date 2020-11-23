<?php

date_default_timezone_set('America/Mexico_City');
define('PRODUCCION', false);
define('HOY', date('Y') . "-" . date('m') . "-" . date('d'));
define('HORA', date('H') . ":" . date('i') . ":" . date('s'));
/*
if (PRODUCCION) {
    define('SERVIDOR', 'p:mysql.hostinger.mx');
    define('USUARIO', 'u720929568_pit');
    define('CONTRASENA', 'dB_P1t_2o18.*');
    define('BASE', 'u720929568_pit');
} else {*/
    define('SERVIDOR', 'p:localhost');
    define('USUARIO', 'root');
    define('CONTRASENA', '');
    define('BASE', 'mundodecaricatura');/*
}


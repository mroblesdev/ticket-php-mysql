<?php

/**
 * Este script PHP establece una conexión a la base de datos 'store' en el servidor local.
 * Si la conexión es exitosa, proporciona un objeto $mysqli para interactuar con la base de datos.
 * Si hay un error de conexión, imprime un mensaje de error y termina la ejecución del script.
 *
 * @link https://github.com/mroblesdev
 * @author mroblesdev
 */

$mysqli = new mysqli('localhost', 'root', '', 'mi_tienda');

if ($mysqli->connect_error) {
    echo 'Error de Conexión ' . $mysqli->connect_error;
    exit;
}

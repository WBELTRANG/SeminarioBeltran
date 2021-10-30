<?php

$host = "localhost";
$bd = "trasnporte";
$usuario = "walter";
$contrasenia = "walter";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd", $usuario, $contrasenia);

} catch (Exception $ex) 
{
        echo $ex->getMessage();
}
?>

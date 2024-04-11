<?php session_start();

require_once 'config.php';
require_once '../functions.php';

comprobarSession();

$conexion = conexion($bd_config);
if(!$conexion){
    header('Location: ../error.php');
}

$id = limpiarDatos($_GET['id']);

if(!$id){
    header('Location:'. RUTABLOG . '/admin');
}

$statement = $conexion->prepare('DELETE FROM articulos WHERE id = :id');
$statement->execute(array('id'=>$id));

header('Location:'.RUTABLOG . '/admin');
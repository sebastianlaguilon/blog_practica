<?php session_start();

require_once 'config.php';
require_once '../functions.php';

comprobarSession();

$conexion = conexion($bd_config);
if(!$conexion){
    header('Location: ../error.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $titulo = limpiarDatos($_POST['titulo']);
    $extrato = limpiarDatos($_POST['extrato']);
    $texto = $_POST['texto'];
    $thumb = $_FILES['thumb']['tmp_name'];

    $archivo_subido = '../'. $blog_config['carpeta_imagenes'] . $_FILES['thumb']['name'];

    move_uploaded_file($thumb, $archivo_subido);

    $statement = $conexion->prepare(
        'INSERT INTO articulos (id, titulo, extrato, texto, thumb)
         VALUES (null, :titulo, :extrato, :texto, :thumb)'
    );

    $statement->execute(array(
        ':titulo'=> $titulo,
        ':extrato'=>$extrato,
        ':texto'=>$texto,
        ':thumb'=>$_FILES['thumb']['name']
    ));

    header('Location: '. RUTABLOG . '/admin');
}

require '../views/nuevo.view.php';

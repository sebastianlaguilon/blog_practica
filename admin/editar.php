<?php session_start();

require_once 'config.php';
require_once '../functions.php';

comprobarSession();

$conexion = conexion($bd_config);
if(!$conexion){
    header('Location: ../error.php');
}

if($_SERVER['REQUEST_METHOD']== 'POST'){
    $titulo = limpiarDatos($_POST['titulo']);
    $extrato = limpiarDatos($_POST['extrato']);
    $texto = $_POST['texto'];
    $id = limpiarDatos($_POST['id']);
    $thumb_guardada = $_POST['thumb-guardada'];
    $thumb = $_FILES['thumb'];

    if(empty($thumb['name'])){
        $thumb = $thumb_guardada;
    }else{
        $archivo_subido = '../'.$blog_config['carpeta_imagenes']. $_FILES['thumb']['name'];
        move_uploaded_file($_FILES['thumb']['tmp_name'], $archivo_subido);
        $thumb = $_FILES['thumb']['name'];
    }

    $statement = $conexion->prepare('UPDATE articulos SET titulo = :titulo, extrato = :extrato, 
                            texto = :texto, thumb = :thumb WHERE id = :id');
    
    $statement->execute(array(
        ':titulo' => $titulo,
        ':extrato'=>$extrato,
        ':texto'=>$texto,
        ':thumb'=>$thumb,
        ':id'=>$id
    ));

    header('Location:'. RUTABLOG . '/admin');

}else{
    $id_articulo = id_articulo($_GET['id']);

    if(empty($id_articulo)){
        header('Location:' . RUTABLOG . '/admin');
    }

   $post = obtener_post_por_id($conexion, $id_articulo);

   if(!$post){
    header('Location:'. RUTABLOG. '/admin');
   }
   $post = $post[0];
}



require '../views/editar.view.php';
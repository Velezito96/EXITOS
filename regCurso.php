<?php
    $nombre=isset($_POST["nombre"])? $_POST["nombre"]:"";
    $descripcion=isset($_POST["descripcion"])? $_POST["descripcion"]:"";
    $imagen=isset($_FILES["imagen"]["name"])? $_FILES["imagen"]["name"]:"";
    if (isset($imagen) && $imagen!=""){
        $tipo=$_FILES['imagen']['type'];
        $temp=$_FILES['imagen']['tmp_name'];
        if(!((strpos($tipo,'gif') || strpos($tipo,'jpeg') || strpos($tipo,'jpg') || strpos($tipo,'png')))){
            echo ("Solo se permite archivos jpg, jpeg y png");
        }else{
            include_once 'connect.php';
            $conexion=conexionBD();
            $pdo=$conexion->prepare("INSERT INTO cursos(nombre,descripcion,imagen) VALUES(?,?,?)");
            $pdo->bindParam(1,$nombre);
            $pdo->bindParam(2,$descripcion);
            $pdo->bindParam(3,$imagen);
            $pdo->execute() or die(print($pdo->errorInfo()));
            move_uploaded_file($temp,'fotosCursos/'.$imagen);
            echo json_encode("true");
        }
    }
?>
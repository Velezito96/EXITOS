<?php
    /*if(!defined("OK")){
        die("Prohibido");
    }*/
    
    include_once 'connect.php';
    $conexion=conexionBD();
    
        $res = $conexion->query('SELECT * FROM cursos') or die(print($conexion->errorInfo()));

        $data=[];
        while($item=$res->fetch(PDO::FETCH_OBJ)){
            $data[]=[
                "idCurso"=>$item->idCurso,
                "nombreCurso"=>$item->nombreCurso,
                "descripcion"=>$item->descripcion,
                "imagen"=>$item->imagen
            ];
        }
        echo json_encode($data);

        $res=null;
        $conexion=null;
?>
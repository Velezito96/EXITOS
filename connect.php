<?php 
function conexionBD(){
    try{
        $conexion=new PDO('mysql:host=localhost;dbname=exitosde_Saber',
        'root',
        '',
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        
        return $conexion;

    }catch(PDOException $error){
        echo 'Error conectando a la BBDD. '.$error->getMessage();
        return $error->getMessage();
        die();
    }
}
?>

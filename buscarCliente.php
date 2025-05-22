<?php
    $numeroIdentificacion=isset($_POST["numeroDNI"])? $_POST["numeroDNI"]:"";
    include_once 'connect.php';
    $conexion=conexionBD();
    $cuenta = $conexion->query("SELECT count(idCliente) FROM cliente WHERE numeroDNI='".$numeroIdentificacion."'");
    $total = $cuenta->fetchColumn();
    if ($total>0){
        $res = $conexion->query("SELECT * FROM cliente where numeroDNI='".$numeroIdentificacion."'");
        $data=[];
        while($item=$res->fetch(PDO::FETCH_OBJ)){
        $data[]=[
            "apellidosNombres"=>$item->apellidosNombres,
            "telefono"=>$item->telefono,
            "email"=>$item->email
            ];
        }
        echo json_encode($data);
        $res=null;
        $conexion=null;
    }else{
        $data=[];
        echo json_encode($data);
        $res=null;
        $conexion=null;
    }
?>

<?php
    $numero=isset($_POST["numero"])? $_POST["numero"]:"";
    include_once 'connect.php';
    $conexion=conexionBD();
        $res = $conexion->query("SELECT apellidosNombres,tipoIdentificacion,numero
        FROM certificado INNER JOIN cliente ON certificado.idCliente=cliente.idCliente 
        WHERE cliente.numero='".$numero."'") or die(print($conexion->errorInfo()));
        $data=[];
        while($item=$res->fetch(PDO::FETCH_OBJ)){
            $data[]=[
                "apellidosNombres"=>$item->apellidosNombres,
                "tipoIdentificacion"=>$item->tipoIdentificacion,
                "numero"=>$item->numero
            ];
        }
        echo json_encode($data);
        $res=null;
        $conexion=null;
?>



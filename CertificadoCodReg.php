<?php
$tipoCliente=isset($_POST["idCliente"])? $_POST["idCliente"]:"";
    include_once 'connect.php';
    $conexion=conexionBD();
        $res = $conexion->query('SELECT * FROM certificado c INNER JOIN clientes cli ON c.idCliente=cli.id where cli.numeroDNI="12345678"') or die(print($conexion->errorInfo()));
        $data=[];
        while($item=$res->fetch(PDO::FETCH_OBJ)){
            $data[]=[
                "nombreCurso"=>$item->nombreCurso,
                "fechaInicio"=>$item->fechaInicio,
                "fechaFin"=>$item->fechaFin,
                "numHoras"=>$item->numHoras,
                "codigoRegistro"=>$item->codigoRegistro,
                "apellidos"=>$item->apellidos,
                "nombres"=>$item->nombres,
                "tipoDocumento"=>$item->tipoDocumento,
                "numeroDNI"=>$item->numeroDNI
            ];
        }
        echo json_encode($data);
        $res=null;
        $conexion=null;
?>
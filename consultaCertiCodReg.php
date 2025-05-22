
<?php
    $numero=isset($_POST["numero"])? $_POST["numero"]:"";
    include_once 'connect.php';
    $conexion=conexionBD();
        $res = $conexion->query("SELECT apellidosNombres,nombreCurso,fechaInicio,fechaFin,numHoras,fechaEntrega,codRegistro,codQr,urls
        FROM certificado INNER JOIN cliente ON certificado.idCliente=cliente.idCliente INNER JOIN cursos ON certificado.idCurso=cursos.idCurso 
        WHERE certificado.codRegistro='".$numero."'") or die(print($conexion->errorInfo()));
        $data=[];
        while($item=$res->fetch(PDO::FETCH_OBJ)){
            $data[]=[
                "apellidosNombres"=>$item->apellidosNombres,
                "nombreCurso"=>$item->nombreCurso,
                "fechaInicio"=>$item->fechaInicio,
                "fechaFin"=>$item->fechaFin,
                "numHoras"=>$item->numHoras,
                "fechaEntrega"=>$item->fechaEntrega,
                "codRegistro"=>$item->codRegistro,
                "codBarras"=>$item->codQr,
                "urls"=>$item->urls
            ];
        }
        echo json_encode($data);
        $res=null;
        $conexion=null;
?>



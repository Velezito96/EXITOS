
<?php
    $numero=isset($_POST["numero"])? $_POST["numero"]:"";
    include_once 'connect.php';
    $conexion=conexionBD();
        $res = $conexion->query("SELECT nombreCurso,fechaInicio,fechaFin,nota,numHoras,codRegistro
        FROM certificado INNER JOIN cliente ON certificado.idCliente=cliente.idCliente INNER JOIN cursos ON certificado.idCurso=cursos.idCurso 
        WHERE cliente.numero='".$numero."'") or die(print($conexion->errorInfo()));
        $data=[];
        while($item=$res->fetch(PDO::FETCH_OBJ)){
            $data[]=[
                "nombreCurso"=>$item->nombreCurso,
                "fechaInicio"=>$item->fechaInicio,
                "fechaFin"=>$item->fechaFin,
                "nota"=>$item->nota,
                "numHoras"=>$item->numHoras,
                "codRegistro"=>$item->codRegistro
            ];
        }
        echo json_encode($data);
        $res=null;
        $conexion=null;
?>



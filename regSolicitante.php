<?php
    $tipoCliente=isset($_POST["tipoCliente"])? $_POST["tipoCliente"]:"";
    $tipoIdentificacion=isset($_POST["tipoIdentificacion"])? $_POST["tipoIdentificacion"]:"";
    $numeroDNI=isset($_POST["numeroDNI"])? $_POST["numeroDNI"]:"";
    $apellidosNombres=isset($_POST["apellidosNombres"])? $_POST["apellidosNombres"]:"";
    $direccion=isset($_POST["direccion"])? $_POST["direccion"]:"";
    $telefono=isset($_POST["telefono"])? $_POST["telefono"]:"";
    $email=isset($_POST["email"])? $_POST["email"]:"";
    $idCurso=isset($_POST["idCurso"])? $_POST["idCurso"]:"";
   

    $idCliente=0;
    include_once 'connect.php';
    $conexion=conexionBD();
    $res = $conexion->query("SELECT count(idCliente) FROM cliente WHERE numeroDNI='".$numeroDNI."'");
        $total = $res->fetchColumn();
    if($total==0){
        $pdo=$conexion->prepare("INSERT INTO cliente(tipoCliente,tipoIdentificacion,numeroDNI,apellidosNombres,telefono,email) VALUES(?,?,?,?,?,?)");
        $pdo->bindParam(1,$tipoCliente);
        $pdo->bindParam(2,$tipoIdentificacion);
        $pdo->bindParam(3,$numeroDNI);
        $pdo->bindParam(4,$apellidosNombres);
        $pdo->bindParam(5,$telefono);
        $pdo->bindParam(6,$email);
        $pdo->execute() or die(print($pdo->errorInfo()));
        echo json_encode("true");
        regSolicitud($conexion,$numeroDNI,$idCurso);
        }else{
            $res = $conexion->query("SELECT idCliente FROM cliente WHERE numeroDNI='".$numeroDNI."'");
            $data=[];
            while($item=$res->fetch(PDO::FETCH_OBJ)){
                $data[]=[
                    $idCliente=$item->idCliente,
                ];
            }
            $pdo=$conexion->prepare("UPDATE cliente SET numeroDNI=:numeroDNI, apellidosNombres=:apellidosNombres, telefono=:telefono, email=:email WHERE idCliente=".$idCliente." ");
            $pdo->bindParam(':numeroDNI',$numeroDNI);
            $pdo->bindParam(':apellidosNombres',$apellidosNombres);
            $pdo->bindParam(':telefono',$telefono);
            $pdo->bindParam(':email',$email);
            $pdo->execute() or die(print($pdo->errorInfo()));
            echo json_encode("true");
            regSolicitud($conexion,$numeroDNI,$idCurso);
    };
    function regSolicitud($conexion,$numeroDNI,$idCurso){
        $res = $conexion->query("SELECT idCliente FROM cliente WHERE numeroDNI='".$numeroDNI."'");
        $data=[];
        while($item=$res->fetch(PDO::FETCH_OBJ)){
            $data[]=[
                $idCliente=$item->idCliente,
            ];
        }
        date_default_timezone_set('America/Lima');
        $fecha=date("Y-m-d");
        $estado="2";
        $pdu=$conexion->prepare("INSERT INTO solicitud(idCliente,idCurso,fecha,estado) VALUES(?,?,?,?)");
        $pdu->bindParam(1,$idCliente);
        $pdu->bindParam(2,$idCurso);
        $pdu->bindParam(3,$fecha);
        $pdu->bindParam(4,$estado);     
        $pdu->execute() or die(print($pdu->errorInfo()));
    }

?>
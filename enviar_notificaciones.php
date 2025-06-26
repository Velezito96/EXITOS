<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Verificar si el usuario ha iniciado sesión y tiene privilegios de profesor o administrador
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idCliente = $_SESSION['idCliente']; 

// Verificar si el usuario tiene privilegios de profesor o administrador
$sqlCliente = "SELECT idCliente, idCargo FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->execute(['usuario' => $usuario]);
$cliente = $stmtCliente->fetch();
$idCargo = $cliente['idCargo'];

if ($idCargo != 2 && $idCargo != 1) { // Solo profesor (2) o administrador (1) pueden enviar notificaciones
    die("Acceso denegado.");
}

// Manejo de inserción de notificaciones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mensaje'])) {
        $mensaje = $_POST['mensaje'];
        $fecha = date('Y-m-d H:i:s');  // Fecha y hora actual

        // Insertar la nueva notificación solo para estudiantes y apoderados (idCargo = 3 y 4)
        $sqlInsert = "
            INSERT INTO notificaciones (idCliente, mensaje, fecha)
            SELECT idCliente, :mensaje, :fecha 
            FROM cliente WHERE idCargo IN (3, 4)  -- Solo estudiantes (3) y apoderados (4)
        "; 
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            'mensaje' => $mensaje,
            'fecha' => $fecha
        ]);

        // Mostrar mensaje de éxito
        $mensaje = "<div class='alert alert-success' role='alert'>¡Notificación enviada correctamente a los alumnos y apoderados!</div>";
    } else {
        $mensaje = "<div class='alert alert-warning' role='alert'>Faltan campos requeridos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Enviar Notificación</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Enviar Notificación a los Alumnos y Apoderados</h2>

    <?php if (isset($mensaje)) echo $mensaje; ?>

    <!-- Formulario para agregar notificación -->
    <form action="enviar_notificacion.php" method="POST">
        <div class="form-group">
            <label for="mensaje">Mensaje:</label>
            <textarea class="form-control" name="mensaje" id="mensaje" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Enviar Notificación</button>
    </form>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

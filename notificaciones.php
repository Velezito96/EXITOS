<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];

$sql = "SELECT mensaje, fecha FROM notificaciones WHERE idCliente = ? ORDER BY fecha DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idCliente]);
$notificaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Notificaciones - Aula Virtual</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Notificaciones</h1>

    <?php if (count($notificaciones) > 0): ?>
        <ul class="list-group">
            <?php foreach ($notificaciones as $noti): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($noti['mensaje']); ?>
                    <br>
                    <small class="text-muted"><?php echo htmlspecialchars($noti['fecha']); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tienes notificaciones nuevas.</p>
    <?php endif; ?>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] == 3) {
    header("Location: login.php");
    exit();
}

$idForo = $_GET['idForo'] ?? null;
if (!$idForo) {
    echo "Foro no especificado.";
    exit();
}

// Obtener foro
$stmt = $pdo->prepare("SELECT titulo FROM foros WHERE idForo = ?");
$stmt->execute([$idForo]);
$foro = $stmt->fetch();

if (!$foro) {
    echo "Foro no encontrado.";
    exit();
}

// Obtener respuestas
$stmt = $pdo->prepare("SELECT r.*, c.apellidosNombres AS nombre FROM respuestas_foro r JOIN cliente c ON r.idCliente = c.idCliente WHERE r.idForo = ? ORDER BY r.fechaCreacion ASC");
$stmt->execute([$idForo]);
$respuestas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuestas al Foro: <?php echo htmlspecialchars($foro['titulo']); ?></title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h3>Respuestas al Foro: <?php echo htmlspecialchars($foro['titulo']); ?></h3>
    <?php if (count($respuestas) > 0): ?>
        <?php foreach ($respuestas as $r): ?>
            <div class="border rounded p-3 mb-3">
                <strong><?php echo htmlspecialchars($r['nombre']); ?></strong>
                <span class="text-muted small float-end"><?php echo date('d/m/Y H:i', strtotime($r['fechaCreacion'])); ?></span>
                <p class="mt-2"><?php echo nl2br(htmlspecialchars($r['contenido'])); ?></p>
                <!-- Espacio para evaluación o retroalimentación -->
                <div class="mt-2">
                    <label>Observaciones o retroalimentación:</label>
                    <textarea class="form-control" rows="2" placeholder="Comentarios para el estudiante..."></textarea>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Aún no hay respuestas en este foro.</div>
    <?php endif; ?>
    <a href="ver_tema.php?idForo=<?php echo $idForo; ?>" class="btn btn-secondary mt-4">Volver al foro</a>
</body>
</html>

<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];
$idForo = $_GET['idForo'] ?? null;

if (!$idForo) {
    echo "Foro no especificado.";
    exit();
}

// Obtener datos del foro
$stmt = $pdo->prepare("SELECT idForo, titulo, descripcion, fechaLimite AS fecha_fin, NOW() AS fecha_inicio, bloquearAntes AS requiere_respuesta_previa FROM foros WHERE idForo = ?");
$stmt->execute([$idForo]);
$foro = $stmt->fetch();

if (!$foro) {
    echo "Foro no encontrado.";
    exit();
}

// Comprobar si el foro está activo
$ahora = date('Y-m-d H:i:s');
$activo = ($foro['fecha_inicio'] <= $ahora && $foro['fecha_fin'] >= $ahora);

// Verificar si ya respondió
$stmt = $pdo->prepare("SELECT * FROM respuestas_foro WHERE idForo = ? AND idCliente = ?");
$stmt->execute([$idForo, $idCliente]);
$respuestaPrevia = $stmt->fetch();

// Insertar nueva respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $activo && !$respuestaPrevia) {
    $comentario = trim($_POST['comentario'] ?? '');
    if (!empty($comentario)) {
        $stmt = $pdo->prepare("INSERT INTO respuestas_foro (idForo, idCliente, contenido, fechaCreacion) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$idForo, $idCliente, $comentario]);
        header("Location: ver_tema.php?idForo=$idForo");
        exit();
    }
}

// Obtener todas las respuestas si ya respondió o si no hay restricción
$mostrarRespuestas = ($respuestaPrevia || !$foro['requiere_respuesta_previa']);
if ($mostrarRespuestas) {
    $stmt = $pdo->prepare("SELECT r.*, c.apellidosNombres AS nombre FROM respuestas_foro r JOIN cliente c ON r.idCliente = c.idCliente WHERE r.idForo = ? ORDER BY r.fechaCreacion ASC");
    $stmt->execute([$idForo]);
    $respuestas = $stmt->fetchAll();
} else {
    $respuestas = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($foro['titulo']); ?></title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
<?php if ($_SESSION['idCargo'] != 3): ?>
    <a href="ver_respuestas_foro.php?idForo=<?php echo $idForo; ?>" class="btn btn-outline-success float-end">Ver comentarios de alumnos</a>
<?php endif; ?>
    <p class="text-muted"><?php echo nl2br(htmlspecialchars($foro['descripcion'])); ?></p>
    <p><strong>Disponible:</strong> <?php echo $foro['fecha_inicio']; ?> a <?php echo $foro['fecha_fin']; ?></p>
    <p><strong>Estado:</strong> <?php echo $activo ? 'Abierto' : 'Cerrado'; ?></p>

    <?php if ($activo && !$respuestaPrevia): ?>
        <form method="post" class="mb-4">
            <label class="form-label">Tu comentario:</label>
            <textarea name="comentario" class="form-control" rows="3" required></textarea>
            <button type="submit" class="btn btn-primary mt-2">Publicar</button>
        </form>
    <?php elseif (!$activo): ?>
        <div class="alert alert-warning">Este foro ya está cerrado.</div>
    <?php endif; ?>

    <?php if ($mostrarRespuestas && count($respuestas) > 0): ?>
        <h5>Respuestas de los participantes:</h5>
        <?php foreach ($respuestas as $r): ?>
            <div class="border rounded p-3 mb-3">
                <strong><?php echo htmlspecialchars($r['nombre']); ?></strong>
                <span class="text-muted small">(<?php echo date('d/m/Y H:i', strtotime($r['fechaCreacion'])); ?>)</span>
                <p class="mt-2"><?php echo nl2br(htmlspecialchars($r['contenido'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php elseif (!$respuestaPrevia && $foro['requiere_respuesta_previa']): ?>
        <div class="alert alert-info">Responde al foro para ver los comentarios de tus compañeros.</div>
    <?php endif; ?>

    <a href="foro.php" class="btn btn-secondary mt-3">Volver a los foros</a>
</body>
</html>

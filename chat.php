<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idUsuario = $_SESSION['idCliente'];
$idCargo = $_SESSION['idCargo'];

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Validar acceso al curso
$stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos g WHERE g.idCurso = ? AND (g.idProfesor = ? OR EXISTS (SELECT 1 FROM matricula m WHERE m.idCurso = ? AND m.idCliente = ?))");
$stmt->execute([$idCurso, $idUsuario, $idCurso, $idUsuario]);
if ($stmt->fetchColumn() == 0) {
    echo "No tienes acceso a este curso.";
    exit();
}

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje'])) {
    $mensaje = trim($_POST['mensaje']);
    $stmt = $pdo->prepare("INSERT INTO chat_curso (idCurso, idUsuario, mensaje, fecha) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$idCurso, $idUsuario, $mensaje]);
}

// Obtener mensajes
$stmt = $pdo->prepare("SELECT cc.mensaje, cc.fecha, cl.apellidosNombres FROM chat_curso cc JOIN cliente cl ON cc.idUsuario = cl.idCliente WHERE cc.idCurso = ? ORDER BY cc.fecha DESC LIMIT 50");
$stmt->execute([$idCurso]);
$mensajes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat del Curso</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h3 class="mb-4">Chat del Curso</h3>

    <div class="border p-3 mb-4" style="max-height: 400px; overflow-y: auto; background-color: #f9f9f9;">
        <?php foreach (array_reverse($mensajes) as $m): ?>
            <div class="mb-2">
                <strong><?php echo htmlspecialchars($m['apellidosNombres']); ?>:</strong>
                <span class="text-muted small"><?php echo date("d/m/Y H:i", strtotime($m['fecha'])); ?></span><br>
                <span><?php echo nl2br(htmlspecialchars($m['mensaje'])); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="post">
        <div class="mb-3">
            <textarea name="mensaje" class="form-control" placeholder="Escribe tu mensaje..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
        <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Volver</a>
    </form>

    <script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

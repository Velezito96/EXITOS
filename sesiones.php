<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idProfesor = $_SESSION['idCliente'];

if (!$idCurso) {
    $_SESSION['error'] = "Curso no especificado.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Validar acceso del profesor
$stmt = $pdo->prepare("SELECT c.nombreCurso FROM cursos c JOIN grupos g ON g.idCurso = c.idCurso WHERE c.idCurso = ? AND g.idProfesor = ?");
$stmt->execute([$idCurso, $idProfesor]);
$nombreCurso = $stmt->fetchColumn();

if (!$nombreCurso) {
    $_SESSION['error'] = "Acceso denegado al curso.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Obtener sesiones existentes
$stmtSesiones = $pdo->prepare("SELECT * FROM sesiones WHERE idCurso = ? ORDER BY numero ASC");
$stmtSesiones->execute([$idCurso]);
$sesiones = $stmtSesiones->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clases y Sesiones - <?php echo htmlspecialchars($nombreCurso); ?></title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Sesiones del Curso: <?php echo htmlspecialchars($nombreCurso); ?></h2>

    <a href="crear_sesion.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-success mb-3">+ Nueva Sesión</a>

    <?php if (count($sesiones) > 0): ?>
        <div class="accordion" id="listaSesiones">
            <?php foreach ($sesiones as $s): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?php echo $s['idSesion']; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $s['idSesion']; ?>">
                            Sesión <?php echo $s['numero']; ?>: <?php echo htmlspecialchars($s['titulo']); ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $s['idSesion']; ?>" class="accordion-collapse collapse" data-bs-parent="#listaSesiones">
                        <div class="accordion-body">
                            <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($s['descripcion'])); ?></p>
                            <?php if ($s['archivo']): ?>
                                <p><strong>Material:</strong> <a href="archivos/<?php echo $s['archivo']; ?>" target="_blank">Descargar</a></p>
                            <?php endif; ?>
                            <?php if ($s['link']): ?>
                                <p><strong>Enlace:</strong> <a href="<?php echo $s['link']; ?>" target="_blank"><?php echo $s['link']; ?></a></p>
                            <?php endif; ?>
                            <a href="editar_sesion.php?idSesion=<?php echo $s['idSesion']; ?>&idCurso=<?php echo $idCurso; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_sesion.php?idSesion=<?php echo $s['idSesion']; ?>&idCurso=<?php echo $idCurso; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas eliminar esta sesión?')">Eliminar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No hay sesiones registradas todavía.</div>
    <?php endif; ?>

    <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary mt-4">Volver al Panel del Curso</a>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

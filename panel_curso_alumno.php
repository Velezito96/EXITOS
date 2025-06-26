<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 3) {
    header("Location: login.php");
    exit("Acceso no autorizado.");
}

$idAlumno = $_SESSION['idCliente'];
$idCurso = $_GET['idCurso'] ?? null;

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Validar matrícula
$stmt = $pdo->prepare("SELECT COUNT(*) FROM matricula WHERE idCurso = ? AND idCliente = ? AND estado = 'Activo'");
$stmt->execute([$idCurso, $idAlumno]);
if ($stmt->fetchColumn() == 0) {
    echo "No estás matriculado en este curso.";
    exit();
}

// Obtener nombre del curso
$stmt = $pdo->prepare("SELECT nombreCurso FROM cursos WHERE idCurso = ?");
$stmt->execute([$idCurso]);
$nombreCurso = $stmt->fetchColumn();

// Obtener sesiones
$stmt = $pdo->prepare("SELECT * FROM sesiones WHERE idCurso = ? ORDER BY numero ASC");
$stmt->execute([$idCurso]);
$sesiones = $stmt->fetchAll();

// Obtener tareas activas
$stmt = $pdo->prepare("SELECT idTarea, titulo, fechaLimite FROM tareas WHERE idCurso = ? AND fechaLimite > NOW()");
$stmt->execute([$idCurso]);
$tareas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($nombreCurso); ?> - Aula Virtual</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2 class="mb-3"><?php echo htmlspecialchars($nombreCurso); ?></h2>
    <p>Bienvenido al curso. Aquí encontrarás el material, tareas y actividades asignadas.</p>

    <!-- Accesos rápidos -->
    <div class="d-flex flex-wrap gap-2 my-3">
        <a href="foro.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-outline-secondary">
            <i class="bi bi-chat-dots"></i> Foro
        </a>
        <a href="encuesta.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-outline-info">
            <i class="bi bi-bar-chart-line"></i> Encuestas
        </a>
    </div>

    <!-- Sesiones -->
    <h4 class="mt-4">Sesiones del Curso</h4>
    <?php if ($sesiones): ?>
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

                            <?php if ($s['descripcion']): ?>
                                <p><strong>Descripción:</strong><br><?php echo nl2br(htmlspecialchars($s['descripcion'])); ?></p>
                            <?php endif; ?>

                            <?php if ($s['archivo']): ?>
                                <p><strong>Material de ayuda:</strong><br>
                                    <a href="uploads/<?php echo $s['archivo']; ?>" 
                                       download="<?php echo basename($s['archivo']); ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                       Descargar <?php echo strtoupper(pathinfo($s['archivo'], PATHINFO_EXTENSION)); ?>
                                    </a>
                                </p>
                            <?php endif; ?>

                            <?php if ($s['link']): ?>
                                <p><strong>Enlace complementario:</strong><br>
                                    <a href="<?php echo $s['link']; ?>" target="_blank"><?php echo $s['link']; ?></a>
                                </p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Aún no hay sesiones publicadas.</div>
    <?php endif; ?>

    <!-- Tareas -->
    <h4 class="mt-4">Tareas Activas</h4>
    <?php if ($tareas): ?>
        <ul class="list-group mb-3">
            <?php foreach ($tareas as $t): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?php echo htmlspecialchars($t['titulo']); ?></span>
                    <span class="badge bg-warning text-dark">Hasta: <?php echo date("d/m/Y H:i", strtotime($t['fechaLimite'])); ?></span>
                    <a href="ver_tarea.php?idTarea=<?php echo $t['idTarea']; ?>" class="btn btn-sm btn-primary">Ver</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info">No tienes tareas activas en este momento.</div>
    <?php endif; ?>

    <a href="gestionar_cursos_alumno.php" class="btn btn-outline-dark mt-4">
        <i class="bi bi-arrow-left"></i> Volver a Mis Cursos
    </a>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

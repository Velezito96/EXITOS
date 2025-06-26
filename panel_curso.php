<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    $_SESSION['error'] = "Acceso no autorizado.";
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

// Verificación de acceso
$esAdmin = $_SESSION['idCargo'] == 1;

if ($esAdmin) {
    $stmt = $pdo->prepare("SELECT nombreCurso FROM cursos WHERE idCurso = ?");
    $stmt->execute([$idCurso]);
    $nombreCurso = $stmt->fetchColumn();
} else {
    $stmt = $pdo->prepare("
        SELECT c.nombreCurso 
        FROM cursos c 
        JOIN grupos g ON g.idCurso = c.idCurso 
        WHERE c.idCurso = ? AND g.idProfesor = ?
    ");
    $stmt->execute([$idCurso, $idProfesor]);
    $nombreCurso = $stmt->fetchColumn();
}

if (!$nombreCurso) {
    $_SESSION['error'] = "Acceso denegado a este curso.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Obtener profesor asignado (solo visible para admin)
$nombreProfesor = '';
if ($esAdmin) {
    $stmt = $pdo->prepare("
        SELECT cl.apellidosNombres 
        FROM grupos g 
        JOIN cliente cl ON g.idProfesor = cl.idCliente 
        WHERE g.idCurso = ? 
        LIMIT 1
    ");
    $stmt->execute([$idCurso]);
    $nombreProfesor = $stmt->fetchColumn();
}

// Cantidad de alumnos
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM matricula WHERE idCurso = ?");
$stmtCount->execute([$idCurso]);
$cantidadAlumnos = $stmtCount->fetchColumn();

// Tareas activas
$stmtTareas = $pdo->prepare("SELECT COUNT(*) FROM tareas WHERE idCurso = ? AND fechaLimite > NOW()");
$stmtTareas->execute([$idCurso]);
$tareasActivas = $stmtTareas->fetchColumn();

// Sesiones creadas
$stmtTotalSesiones = $pdo->prepare("SELECT COUNT(*) FROM sesiones WHERE idCurso = ?");
$stmtTotalSesiones->execute([$idCurso]);
$totalSesiones = $stmtTotalSesiones->fetchColumn();

// Últimas sesiones
$stmtSesiones = $pdo->prepare("
    SELECT numero, titulo, archivo 
    FROM sesiones 
    WHERE idCurso = ? 
    ORDER BY numero ASC 
    LIMIT 5
");
$stmtSesiones->execute([$idCurso]);
$ultimasSesiones = $stmtSesiones->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - <?php echo htmlspecialchars($nombreCurso); ?></title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        #sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 1rem;
            border-right: 1px solid #dee2e6;
        }
        #content {
            flex-grow: 1;
            padding: 2rem;
        }
        .sidebar-link {
            display: block;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
            color: #000;
            text-decoration: none;
            border-radius: 0.25rem;
        }
        .sidebar-link:hover {
            background-color: #e2e6ea;
            text-decoration: none;
        }
    </style>
</head>
<body>
<nav id="sidebar">
    <h5><?php echo htmlspecialchars($nombreCurso); ?></h5>
    <a href="evaluaciones.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-clipboard-data"></i> Evaluaciones</a>
    <a href="registrar_asistencia.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-clipboard-check"></i> Asistencia</a>
    <a href="notas.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-pencil-square"></i> Notas</a>
    <a href="sesiones.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-calendar-event"></i> Clases y Sesiones</a>
    <a href="foro.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-chat-dots"></i> Foro de Debate</a>
    <a href="crear_evaluacion.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-ui-checks-grid"></i> Crear Evaluación</a>
    <a href="crear_encuesta.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-reception-4"></i> Encuestas</a>
    <a href="chat.php?idCurso=<?php echo $idCurso; ?>" class="sidebar-link"><i class="bi bi-chat-left-text-fill"></i> Chat</a>
    <a href="dashboard.php" class="sidebar-link text-danger"><i class="bi bi-arrow-left"></i> Volver</a>
</nav>

<div id="content">
    <h3>Panel del Curso: <?php echo htmlspecialchars($nombreCurso); ?></h3>
    <p>Desde aquí puedes acceder a las herramientas para gestionar tu curso.</p>

    <?php if ($esAdmin && $nombreProfesor): ?>
        <p><strong>Profesor asignado:</strong> <?php echo htmlspecialchars($nombreProfesor); ?></p>
    <?php endif; ?>

    <p><strong>Alumnos matriculados:</strong> <?php echo $cantidadAlumnos; ?></p>
    <p><span class="badge bg-warning text-dark">Tareas activas: <?php echo $tareasActivas; ?></span></p>
    <p><strong>Sesiones creadas:</strong> <?php echo $totalSesiones; ?></p>

    <h4 class="mt-5">Sesiones Recientes</h4>
    <a href="crear_sesion.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-success btn-sm mb-3">
        <i class="bi bi-plus-circle"></i> Nueva Sesión
    </a>

    <?php if (count($ultimasSesiones) > 0): ?>
        <ul class="list-group mb-3">
            <?php foreach ($ultimasSesiones as $s): ?>
                <li class="list-group-item">
                    <strong>Sesión <?php echo $s['numero']; ?>:</strong> <?php echo htmlspecialchars($s['titulo']); ?>
                    <?php if ($s['archivo']): ?>
                        - <a href="archivos/<?php echo $s['archivo']; ?>" target="_blank">Material</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="sesiones.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-outline-primary btn-sm">Ver todas las sesiones</a>
    <?php else: ?>
        <div class="alert alert-info">No hay sesiones registradas todavía.</div>
    <?php endif; ?>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

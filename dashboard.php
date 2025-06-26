<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Validación de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Obtener datos del usuario
$sqlCliente = "SELECT idCliente, idCargo FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->execute(['usuario' => $usuario]);
$cliente = $stmtCliente->fetch();

if (!$cliente) {
    header("Location: login.php");
    exit("Usuario no encontrado.");
}

// Guardar info útil en sesión (si no existe ya)
$_SESSION['idCliente'] = $cliente['idCliente'];
$_SESSION['idCargo'] = $cliente['idCargo'];

$idCliente = $cliente['idCliente'];
$idCargo = $cliente['idCargo'];

// Función para controlar roles
function tieneRol(...$roles) {
    return in_array($_SESSION['idCargo'], $roles);
}

// Cursos matriculados
$sqlMatriculados = "
    SELECT c.nombreCurso, c.descripcion, n.descripcion AS nivel 
    FROM cursos c
    JOIN matricula m ON c.idCurso = m.idCurso
    JOIN niveles n ON m.idNivel = n.idNivel
    WHERE m.idCliente = :idCliente AND m.estado = 'Activo'
";
$stmtMatriculados = $pdo->prepare($sqlMatriculados);
$stmtMatriculados->execute(['idCliente' => $idCliente]);
$cursosMatriculados = $stmtMatriculados->fetchAll();

// Cursos no matriculados
$sqlNoMatriculados = "
    SELECT c.idCurso, c.nombreCurso, c.descripcion 
    FROM cursos c
    WHERE c.idCurso NOT IN (
        SELECT idCurso FROM matricula WHERE idCliente = :idCliente AND estado = 'Activo'
    )
";
$stmtNoMatriculados = $pdo->prepare($sqlNoMatriculados);
$stmtNoMatriculados->execute(['idCliente' => $idCliente]);
$cursosRecomendados = $stmtNoMatriculados->fetchAll();

// Frase motivacional
$frases = [
    "Aprender es la llave del éxito.",
    "Cada paso cuenta para alcanzar tus metas.",
    "El conocimiento transforma vidas.",
    "Sé constante, los resultados llegarán.",
    "Tu esfuerzo hoy será tu recompensa mañana."
];
$fraseMotivacional = $frases[array_rand($frases)];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Aula Virtual</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="./css/dashboard.css" rel="stylesheet" />
</head>
<body>

<!-- Menú lateral -->
<nav id="sidebar">
    <div class="logo-container">
        <img src="../img/portada.jpg" alt="Logo Aula Virtual" />
    </div>
    <a href="dashboard.php" class="active"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="cursos_disponibles.php"><i class="bi bi-journal-plus"></i> Matricularme</a>
    <a href="listar_cursos.php"><i class="bi bi-journal-bookmark-fill"></i> Cursos</a>
    <a href="progreso.php"><i class="bi bi-bar-chart-line-fill"></i> Progreso</a>
    <a href="notificaciones.php"><i class="bi bi-bell-fill"></i> Notificaciones</a>

    <?php if ($_SESSION['idCargo'] == 3): ?>
    <a href="gestionar_cursos_alumno.php"><i class="bi bi-book-half"></i> Mis Cursos</a>
    <?php endif; ?>


    <?php if (tieneRol(1)): ?>
        <a href="registrar_usuario.php"><i class="bi bi-person-plus-fill"></i> Registrar Usuario</a>
        <a href="gestionar_horarios.php"><i class="bi bi-calendar-event"></i> Gestionar Horarios</a>
        <a href="agregar_profesor.php"><i class="bi bi-person-badge-fill"></i> Gestión de Profesores</a>
    <?php endif; ?>

    <?php if (tieneRol(1, 2)): ?>
        <a href="gestionar_cursos.php"><i class="bi bi-kanban-fill"></i> Gestionar Cursos</a>
        <a href="asignar_profesor.php"><i class="bi bi-people"></i> Cursos por Asginacion</a>
    <?php endif; ?>
</nav>

<!-- Contenido principal -->
<div id="content">

    <!-- Barra superior -->
    <header id="topbar" class="d-flex justify-content-end p-2">
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" id="userMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4"></i>
                <span><?php echo htmlspecialchars($usuario); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuLink">
                <li><a class="dropdown-item" href="ver_perfil.php">Ver perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </header>

    <!-- Contenido dinámico -->
    <main class="p-4">
        <div class="alert alert-info text-center">
            <strong><?php echo htmlspecialchars($fraseMotivacional); ?></strong>
        </div>

        <h2 class="mb-3">Tus Cursos Matriculados</h2>
        <?php if ($cursosMatriculados): ?>
            <?php foreach ($cursosMatriculados as $curso): ?>
                <?php
                    $nivel = $curso['nivel'];
                    $nivelClass = match($nivel) {
                        'Básico' => 'bg-success',
                        'Intermedio' => 'bg-warning text-dark',
                        'Avanzado' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                ?>
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($curso['nombreCurso']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                        </div>
                        <span class="badge <?php echo $nivelClass; ?>"><?php echo $nivel; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No tienes cursos matriculados aún. ¡Explora y matricúlate!</p>
        <?php endif; ?>

        <h2 class="mt-4 mb-3">Cursos Disponibles para Matricularte</h2>
        <?php if ($cursosRecomendados): ?>
            <?php foreach ($cursosRecomendados as $curso): ?>
                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($curso['nombreCurso']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Ya estás matriculado en todos los cursos disponibles.</p>
        <?php endif; ?>

        <div class="d-flex gap-3 mt-4">
            <a href="mis_cursos.php" class="btn btn-outline-success">
                <i class="bi bi-folder-check"></i> Mis Cursos
            </a>
            <a href="cursos_disponibles.php" class="btn btn-outline-primary">
                <i class="bi bi-journal-plus"></i> Matricularme
            </a>
        </div>
    </main>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

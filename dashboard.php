<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
require 'connect.php';
$pdo = conexionBD();

$usuario = $_SESSION['usuario'];

// Obtener idCliente y idCargo para mostrar menú según rol
$sqlCliente = "SELECT idCliente, idCargo FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->execute(['usuario' => $usuario]);
$cliente = $stmtCliente->fetch();
if (!$cliente) {
    die("Usuario no encontrado");
}
$idCliente = $cliente['idCliente'];
$idCargo = $cliente['idCargo'];

// Obtener cursos matriculados con fortalezas y niveles
$sqlMatriculados = "
    SELECT c.nombreCurso, c.descripcion, n.descripcion AS nivel 
    FROM solicitud s
    JOIN cursos c ON s.idCurso = c.idCurso
    JOIN niveles n ON s.idNivel = n.idNivel
    WHERE s.idCliente = :idCliente AND s.estado = 1
";
$stmtMatriculados = $pdo->prepare($sqlMatriculados);
$stmtMatriculados->execute(['idCliente' => $idCliente]);
$cursosMatriculados = $stmtMatriculados->fetchAll();

// Obtener cursos no matriculados para mostrar como recomendados
$sqlNoMatriculados = "
    SELECT c.idCurso, c.nombreCurso, c.descripcion 
    FROM cursos c
    WHERE c.idCurso NOT IN (
        SELECT idCurso FROM solicitud WHERE idCliente = :idCliente AND estado = 1
    )
";
$stmtNoMatriculados = $pdo->prepare($sqlNoMatriculados);
$stmtNoMatriculados->execute(['idCliente' => $idCliente]);
$cursosRecomendados = $stmtNoMatriculados->fetchAll();

// Frases motivacionales
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
            <img src="..\img\portada.jpg" alt="Logo Aula Virtual" />
        </div>
        <a href="dashboard.php" class="active"><i class="bi bi-house-door-fill"></i> Inicio</a>
        <a href="cursos_disponibles.php"><i class="bi bi-journal-plus"></i> Matricularme</a>
        <a href="cursos.php"><i class="bi bi-journal-bookmark-fill"></i> Cursos</a>
        <a href="progreso.php"><i class="bi bi-bar-chart-line-fill"></i> Progreso</a>
        <a href="notificaciones.php"><i class="bi bi-bell-fill"></i> Notificaciones</a>

        <?php if ($idCargo == 1): // Solo administrador ?>
            <a href="registrar_usuario.php"><i class="bi bi-person-plus-fill"></i> Registrar Usuario</a>
        <?php endif; ?>
    </nav>

    <!-- Contenido principal -->
    <div id="content">
        <!-- Barra superior con usuario -->
        <header id="topbar">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" id="userMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-4"></i>
                    <span><?php echo htmlspecialchars($usuario); ?></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuLink">
                    <li><a class="dropdown-item" href="#">Ver perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </header>

        <!-- Contenido dinámico principal -->
        <main>
            <div class="mensaje-motivacional">
                <?php echo htmlspecialchars($fraseMotivacional); ?>
            </div>

            <h2>Tus Cursos Matriculados</h2>
            <?php if(count($cursosMatriculados) > 0): ?>
                <?php foreach ($cursosMatriculados as $curso): ?>
                    <div class="curso-card">
                        <i class="curso-icon bi bi-journal-bookmark-fill"></i>
                        <div class="curso-info">
                            <h4><?php echo htmlspecialchars($curso['nombreCurso']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                        </div>
                        <div class="nivel-badge"><?php echo htmlspecialchars($curso['nivel']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tienes cursos matriculados aún. ¡Explora y matricúlate!</p>
            <?php endif; ?>

            <h2>Cursos Disponibles para Matricularte</h2>
            <?php if(count($cursosRecomendados) > 0): ?>
                <?php foreach ($cursosRecomendados as $curso): ?>
                    <div class="curso-card">
                        <i class="curso-icon bi bi-journal-bookmark"></i>
                        <div class="curso-info">
                            <h4><?php echo htmlspecialchars($curso['nombreCurso']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Estás matriculado en todos los cursos disponibles.</p>
            <?php endif; ?>

            <div class="botones">
                <a href="mis_cursos.php" class="btn-accion">Mis Cursos</a>
                <a href="cursos_disponibles.php" class="btn-accion">Matricularme en más cursos</a>
            </div>
        </main>
    </div>

    <script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

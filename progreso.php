<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];

// Obtener progreso del alumno
$sql = "SELECT c.nombreCurso, p.porcentaje 
        FROM progreso p 
        JOIN cursos c ON p.idCurso = c.idCurso
        WHERE p.idCliente = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idCliente]);
$progresos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Progreso - Aula Virtual</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Tu Progreso en los Cursos</h1>

    <?php if (count($progresos) > 0): ?>
        <?php foreach ($progresos as $prog): ?>
            <div class="mb-3">
                <h4><?php echo htmlspecialchars($prog['nombreCurso']); ?></h4>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo intval($prog['porcentaje']); ?>%;" aria-valuenow="<?php echo intval($prog['porcentaje']); ?>" aria-valuemin="0" aria-valuemax="100">
                        <?php echo intval($prog['porcentaje']); ?>%
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4">
            <p>No has comenzado ningún curso. ¡Matricúlate y empieza hoy!</p>
            <a href="cursos_disponibles.php" class="btn btn-primary">Ver cursos disponibles</a>
        </div>
    <?php endif; ?>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 3) {
    header("Location: login.php");
    exit("Acceso denegado.");
}

$idAlumno = $_SESSION['idCliente'];

// Obtener los cursos en los que el alumno está matriculado
$stmt = $pdo->prepare("
    SELECT c.idCurso, c.nombreCurso, c.descripcion
    FROM matricula m
    JOIN cursos c ON m.idCurso = c.idCurso
    WHERE m.idCliente = :id AND m.estado = 'Activo'
");
$stmt->execute(['id' => $idAlumno]);
$cursos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos - Aula Virtual</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2 class="mb-4">Mis Cursos Matriculados</h2>

    <?php if (count($cursos) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($cursos as $curso): ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($curso['nombreCurso']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                            <a href="panel_curso_alumno.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn btn-primary">
                                Ingresar al Curso
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No estás matriculado en ningún curso actualmente.</div>
    <?php endif; ?>
</body>
</html>

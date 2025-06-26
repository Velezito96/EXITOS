<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el idCurso desde la URL
$idCurso = isset($_GET['idCurso']) ? $_GET['idCurso'] : null;
if (!$idCurso) {
    die('Curso no especificado.');
}

// Obtener los horarios del curso
$sql = "
    SELECT diaSemana, horaInicio, horaFin
    FROM horarios
    WHERE idCurso = :idCurso
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['idCurso' => $idCurso]);
$horarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario del Curso</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .horario-card {
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .horario-card h4 {
            margin: 0;
            color: #225f27;
        }
        .horario-card p {
            margin: 0;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Horario del Curso</h2>

        <?php if(count($horarios) > 0): ?>
            <?php foreach ($horarios as $horario): ?>
                <div class="horario-card">
                    <h4><?php echo $horario['diaSemana']; ?></h4>
                    <p><strong>Hora de Inicio:</strong> <?php echo $horario['horaInicio']; ?></p>
                    <p><strong>Hora de Fin:</strong> <?php echo $horario['horaFin']; ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay horarios disponibles para este curso.</p>
        <?php endif; ?>
    </div>

    <script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

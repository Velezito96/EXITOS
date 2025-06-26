<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el idCliente desde la sesión
$idCliente = $_SESSION['idCliente']; // Asegúrate de que este valor está correctamente configurado en la sesión

// Obtener los cursos matriculados para el alumno
$sql = "
    SELECT c.idCurso, c.nombreCurso, c.descripcion, m.fechaMatricula, n.descripcion AS nivel
    FROM cursos c
    JOIN matricula m ON c.idCurso = m.idCurso
    JOIN niveles n ON m.idNivel = n.idNivel
    WHERE m.idCliente = :idCliente
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['idCliente' => $idCliente]);
$cursosMatriculados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .curso-card {
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .curso-card h4 {
            margin: 0;
            color: #225f27;
        }
        .curso-card p {
            margin: 0;
            color: #555;
        }
        .btn-horarios {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-horarios:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mis Cursos Matriculados</h2>

        <?php if(count($cursosMatriculados) > 0): ?>
            <?php foreach ($cursosMatriculados as $curso): ?>
                <div class="curso-card">
                    <h4><?php echo htmlspecialchars($curso['nombreCurso']); ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                    <p><strong>Fecha de Matrícula:</strong> <?php echo htmlspecialchars($curso['fechaMatricula']); ?></p>
                    <p><strong>Nivel:</strong> <?php echo htmlspecialchars($curso['nivel']); ?></p>
                    
                    <!-- Botón para mostrar horarios -->
                    <a href="horario.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn-horarios">
                        Ver Horarios
                    </a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tienes cursos matriculados aún.</p>
        <?php endif; ?>
    </div>

    <script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

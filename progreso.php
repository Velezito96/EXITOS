<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];

// Obtener cursos activos
$sqlCursos = "
    SELECT c.idCurso, c.nombreCurso 
    FROM cursos c
    JOIN matricula m ON m.idCurso = c.idCurso
    WHERE m.idCliente = :idCliente AND m.estado = 'Activo'
";
$stmtCursos = $pdo->prepare($sqlCursos);
$stmtCursos->execute(['idCliente' => $idCliente]);
$cursos = $stmtCursos->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Progreso del Curso - Aula Virtual</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Tu Progreso en los Cursos</h1>

    <?php if (count($cursos) > 0): ?>
        <?php foreach ($cursos as $curso): ?>
            <?php
                $idCurso = $curso['idCurso'];

                // Consultar asistencias por tipo
                $stmt = $pdo->prepare("
                    SELECT tipo, COUNT(*) AS cantidad
                    FROM asistencia
                    WHERE idCliente = ? AND idCurso = ?
                    GROUP BY tipo
                ");
                $stmt->execute([$idCliente, $idCurso]);
                $tipos = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

                $asistio = $tipos['asistio'] ?? 0;
                $tardanza = $tipos['tardanza'] ?? 0;
                $justificada = $tipos['justificada'] ?? 0;
                $falta = $tipos['falta'] ?? 0;

                $sesionesRegistradas = $asistio + $tardanza + $justificada + $falta;
                $totalSesiones = 15; // Fijo

                $puntos = $asistio + $justificada + ($tardanza * 0.5);
                $porcentaje = round(($puntos / $totalSesiones) * 100);

                $badge = ($porcentaje >= 80) ? 'bg-success' :
                         (($porcentaje >= 50) ? 'bg-warning text-dark' : 'bg-danger');
            ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($curso['nombreCurso']); ?></h5>
                    <p>
                        Asistencias: <?php echo $asistio; ?> |
                        Justificadas: <?php echo $justificada; ?> |
                        Tardanzas: <?php echo $tardanza; ?> |
                        Faltas: <?php echo $falta; ?> <br>
                        Sesiones registradas: <?php echo $sesionesRegistradas; ?> / <?php echo $totalSesiones; ?>
                    </p>
                    <div class="progress">
                        <div class="progress-bar <?php echo $badge; ?>"
                             role="progressbar"
                             style="width: <?php echo $porcentaje; ?>%;"
                             aria-valuenow="<?php echo $porcentaje; ?>"
                             aria-valuemin="0"
                             aria-valuemax="100">
                             <?php echo $porcentaje; ?>%
                        </div>
                    </div>
                    
                </div>
            </div>
                
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No estás matriculado en ningún curso activo.
        </div>
    <?php endif; ?>
    <div class="d-flex justify-content-between mb-3">
    <a href="dashboard.php" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
    </a>

    <a href="historial_asistencia.php" class="btn btn-outline-secondary">
        <i class="bi bi-bar-chart-fill"></i> Ir a Progreso
    </a>
</div>

</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

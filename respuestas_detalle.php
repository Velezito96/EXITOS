<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idEncuesta = $_GET['idEncuesta'] ?? null;
$idUsuario = $_SESSION['idCliente'];

if (!$idEncuesta) {
    exit("Encuesta no especificada.");
}

// Validar permiso
$stmt = $pdo->prepare("SELECT * FROM encuestas WHERE idEncuesta = ?");
$stmt->execute([$idEncuesta]);
$encuesta = $stmt->fetch();

if (!$encuesta || ($_SESSION['idCargo'] != 1 && $encuesta['idProfesor'] != $idUsuario)) {
    exit("No tienes permiso.");
}

// Obtener respuestas individuales
$stmt = $pdo->prepare("
    SELECT cl.apellidosNombres, eo.texto AS opcion, er.idOpcion, er.idCliente
    FROM encuesta_respuestas er
    JOIN cliente cl ON cl.idCliente = er.idCliente
    JOIN encuesta_opciones eo ON eo.idOpcion = er.idOpcion
    WHERE er.idEncuesta = ?
    ORDER BY cl.apellidosNombres
");
$stmt->execute([$idEncuesta]);
$respuestas = $stmt->fetchAll();

// Agrupar por alumno
$respuestasPorAlumno = [];
foreach ($respuestas as $r) {
    $respuestasPorAlumno[$r['apellidosNombres']][] = $r['opcion'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuestas por Alumno</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h3>Respuestas individuales: <?php echo htmlspecialchars($encuesta['titulo']); ?></h3>

    <?php if (empty($respuestasPorAlumno)): ?>
        <div class="alert alert-info">Aún no hay respuestas registradas.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Opción(es) seleccionada(s)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($respuestasPorAlumno as $alumno => $opciones): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($alumno); ?></td>
                        <td><?php echo implode(", ", array_map("htmlspecialchars", $opciones)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="resultados_encuesta.php?idEncuesta=<?php echo $idEncuesta; ?>" class="btn btn-secondary">Volver</a>
</body>
</html>

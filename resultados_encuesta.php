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

$stmt = $pdo->prepare("SELECT * FROM encuestas WHERE idEncuesta = ?");
$stmt->execute([$idEncuesta]);
$encuesta = $stmt->fetch();

if (!$encuesta || ($_SESSION['idCargo'] != 1 && $encuesta['idProfesor'] != $idUsuario)) {
    exit("Sin permiso.");
}

$stmt = $pdo->prepare("
    SELECT eo.texto, COUNT(er.idRespuesta) as votos
    FROM encuesta_opciones eo
    LEFT JOIN encuesta_respuestas er ON eo.idOpcion = er.idOpcion
    WHERE eo.idEncuesta = ?
    GROUP BY eo.idOpcion
");
$stmt->execute([$idEncuesta]);
$resultados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados Encuesta</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h3><?php echo htmlspecialchars($encuesta['titulo']); ?></h3>

    <ul class="list-group mb-3">
        <?php foreach ($resultados as $r): ?>
            <li class="list-group-item d-flex justify-content-between">
                <?php echo htmlspecialchars($r['texto']); ?>
                <span class="badge bg-primary"><?php echo $r['votos']; ?> voto(s)</span>
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="post" action="cerrar_encuesta.php" class="d-inline" onsubmit="return confirm('¿Cerrar esta encuesta?');">
        <input type="hidden" name="idEncuesta" value="<?php echo $idEncuesta; ?>">
        <button class="btn btn-warning">Cerrar Encuesta</button>
    </form>

    <form method="post" action="eliminar_encuesta.php" class="d-inline" onsubmit="return confirm('¿Eliminar esta encuesta? Esta acción no se puede deshacer.');">
        <input type="hidden" name="idEncuesta" value="<?php echo $idEncuesta; ?>">
        <button class="btn btn-danger">Eliminar Encuesta</button>
    </form>

    <a href="panel_curso.php?idCurso=<?php echo $encuesta['idCurso']; ?>" class="btn btn-secondary">Volver</a>
</body>
</html>

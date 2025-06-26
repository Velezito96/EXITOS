<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idUsuario = $_SESSION['idCliente'];

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Si no es admin, validar que sea profesor asignado
if ($_SESSION['idCargo'] != 1) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos WHERE idCurso = ? AND idProfesor = ?");
    $stmt->execute([$idCurso, $idUsuario]);
    if ($stmt->fetchColumn() == 0) {
        echo "No tienes permiso para este curso.";
        exit();
    }
}

// Obtener encuestas del profesor
$stmt = $pdo->prepare("SELECT * FROM encuestas WHERE idCurso = ? AND idProfesor = ? ORDER BY fechaCreacion DESC");
$stmt->execute([$idCurso, $idUsuario]);
$encuestas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Encuestas</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h3>Mis Encuestas en el Curso</h3>

    <a href="crear_encuesta.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-success mb-3">+ Nueva Encuesta</a>

    <?php if (count($encuestas) === 0): ?>
        <div class="alert alert-info">Aún no has creado encuestas.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha de creación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($encuestas as $e): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($e['titulo']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($e['fechaCreacion'])); ?></td>
                        <td>
                            <?php echo ($e['fechaCierre'] && strtotime($e['fechaCierre']) < time()) ? 'Cerrada' : 'Activa'; ?>
                        </td>
                        <td>
                            <a href="resultados_encuesta.php?idEncuesta=<?php echo $e['idEncuesta']; ?>" class="btn btn-sm btn-outline-primary">Resultados</a>
                            <a href="respuestas_detalle.php?idEncuesta=<?php echo $e['idEncuesta']; ?>" class="btn btn-sm btn-outline-info">Ver respuestas</a>

                            <?php if (!$e['fechaCierre'] || strtotime($e['fechaCierre']) > time()): ?>
                                <form action="cerrar_encuesta.php" method="post" class="d-inline">
                                    <input type="hidden" name="idEncuesta" value="<?php echo $e['idEncuesta']; ?>">
                                    <button type="submit" class="btn btn-sm btn-warning">Cerrar</button>
                                </form>
                            <?php endif; ?>

                            <form action="eliminar_encuesta.php" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta encuesta?');">
                                <input type="hidden" name="idEncuesta" value="<?php echo $e['idEncuesta']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Volver</a>
</body>
</html>

<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idProfesor = $_SESSION['idCliente'];

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Guardar selección de color
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['colorFondo'])) {
    $color = $_POST['colorFondo'];

    $stmt = $pdo->prepare("SELECT id FROM tema_curso WHERE idCurso = ? AND idProfesor = ?");
    $stmt->execute([$idCurso, $idProfesor]);

    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE tema_curso SET colorFondo = ? WHERE idCurso = ? AND idProfesor = ?");
        $update->execute([$color, $idCurso, $idProfesor]);
    } else {
        $insert = $pdo->prepare("INSERT INTO tema_curso (idCurso, idProfesor, colorFondo) VALUES (?, ?, ?)");
        $insert->execute([$idCurso, $idProfesor, $color]);
    }

    $mensaje = "Tema actualizado correctamente.";
}

// Obtener el color actual
$stmt = $pdo->prepare("SELECT colorFondo FROM tema_curso WHERE idCurso = ? AND idProfesor = ?");
$stmt->execute([$idCurso, $idProfesor]);
$colorActual = $stmt->fetchColumn() ?? '#ffffff';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Elegir Tema de Curso</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body style="background-color: <?php echo htmlspecialchars($colorActual); ?>;">
<div class="container mt-5">
    <h3>Elegir color de fondo para el curso</h3>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="colorFondo" class="form-label">Selecciona un color:</label>
        <input type="color" name="colorFondo" id="colorFondo" value="<?php echo htmlspecialchars($colorActual); ?>" class="form-control form-control-color w-auto">
        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
        <a href="gestionar_cursos.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>
</body>
</html>

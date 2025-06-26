<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Solo administradores pueden acceder
if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 1) {
    header("Location: login.php");
    exit();
}

// Obtener cursos y profesores
$cursos = $pdo->query("SELECT idCurso, nombreCurso FROM cursos")->fetchAll();
$profesores = $pdo->query("SELECT idCliente, apellidosNombres FROM cliente WHERE idCargo = 2")->fetchAll();

// Guardar asignación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCurso = $_POST['idCurso'];
    $idProfesor = $_POST['idProfesor'];
    $grupo = $_POST['nombreGrupo'];
    $aula = $_POST['aula'];

    $stmt = $pdo->prepare("INSERT INTO grupos (idCurso, idProfesor, nombreGrupo, aula, cuposDisponibles) VALUES (?, ?, ?, ?, 30)");
    $stmt->execute([$idCurso, $idProfesor, $grupo, $aula]);

    $mensaje = "Profesor asignado exitosamente al curso.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Profesor a Curso</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Asignar Profesor a Curso</h3>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Curso</label>
            <select name="idCurso" class="form-select" required>
                <option value="">-- Selecciona un curso --</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['idCurso']; ?>"><?php echo htmlspecialchars($curso['nombreCurso']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Profesor</label>
            <select name="idProfesor" class="form-select" required>
                <option value="">-- Selecciona un profesor --</option>
                <?php foreach ($profesores as $prof): ?>
                    <option value="<?php echo $prof['idCliente']; ?>"><?php echo htmlspecialchars($prof['apellidosNombres']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Grupo</label>
            <input type="text" name="nombreGrupo" class="form-control" placeholder="Ej. A" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Aula</label>
            <input type="text" name="aula" class="form-control" placeholder="Ej. 301" required>
        </div>

        <button type="submit" class="btn btn-primary">Asignar Profesor</button>
        <a href="dashboard.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

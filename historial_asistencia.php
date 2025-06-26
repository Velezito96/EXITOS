<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];

// Obtener cursos activos del alumno
$sqlCursos = "
    SELECT c.idCurso, c.nombreCurso 
    FROM cursos c
    JOIN matricula m ON c.idCurso = m.idCurso
    WHERE m.idCliente = :idCliente AND m.estado = 'Activo'
";
$stmt = $pdo->prepare($sqlCursos);
$stmt->execute(['idCliente' => $idCliente]);
$cursos = $stmt->fetchAll();

$asistencias = [];
$cursoSeleccionado = $_POST['idCurso'] ?? null;

if ($cursoSeleccionado) {
    $stmt = $pdo->prepare("
        SELECT fecha, tipo, descripcion
        FROM asistencia
        WHERE idCliente = :idCliente AND idCurso = :idCurso
        ORDER BY fecha ASC
    ");
    $stmt->execute(['idCliente' => $idCliente, 'idCurso' => $cursoSeleccionado]);
    $asistencias = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Asistencia</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4 text-center">Historial de Asistencia</h1>

    <form method="post" class="mb-4">
        <label for="idCurso" class="form-label">Selecciona un curso:</label>
        <select name="idCurso" id="idCurso" class="form-select" required onchange="this.form.submit()">
            <option value="">-- Elegir curso --</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo $curso['idCurso']; ?>" <?php if ($cursoSeleccionado == $curso['idCurso']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($curso['nombreCurso']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($cursoSeleccionado): ?>
        <?php if (count($asistencias) > 0): ?>
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Descripción / Tema</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asistencias as $i => $row): ?>
                        <?php
                            $estado = ucfirst($row['tipo']);
                            $fecha = date("d/m/Y", strtotime($row['fecha']));
                            $desc = $row['descripcion'] ?? '-';

                            $badge = match($row['tipo']) {
                                'asistio' => 'badge bg-success',
                                'justificada' => 'badge bg-primary',
                                'tardanza' => 'badge bg-warning text-dark',
                                'falta' => 'badge bg-danger',
                                default => 'badge bg-secondary'
                            };
                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $fecha; ?></td>
                            <td><span class="<?php echo $badge; ?>"><?php echo $estado; ?></span></td>
                            <td><?php echo htmlspecialchars($desc); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No hay registros de asistencia aún para este curso.
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="d-flex justify-content-between mb-3">
    <a href="dashboard.php" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left-circle"></i> Volver al Inicio
    </a>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

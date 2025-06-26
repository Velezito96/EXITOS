<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Simulación de sesión del profesor
$idProfesor = $_SESSION['idProfesor'] ?? 2;

$sqlCursos = "
    SELECT c.idCurso, c.nombreCurso 
    FROM cursos c
    JOIN grupos g ON g.idCurso = c.idCurso
    WHERE g.idProfesor = :idProfesor
";
$stmtCursos = $pdo->prepare($sqlCursos);
$stmtCursos->execute(['idProfesor' => $idProfesor]);
$cursos = $stmtCursos->fetchAll();

// Registro de asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asistencia'])) {
    $idCurso = $_POST['idCurso'];
    $fecha = $_POST['fecha'];
    $descripcion = trim($_POST['descripcion'] ?? '');
    $asistencias = $_POST['asistencia'];

    foreach ($asistencias as $idCliente => $tipo) {
        // Evitar duplicado por fecha
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM asistencia WHERE idCliente = ? AND idCurso = ? AND fecha = ?");
        $stmtCheck->execute([$idCliente, $idCurso, $fecha]);
        if ($stmtCheck->fetchColumn() == 0) {
            $stmtInsert = $pdo->prepare("
                INSERT INTO asistencia (idCliente, idCurso, fecha, tipo, descripcion)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmtInsert->execute([$idCliente, $idCurso, $fecha, $tipo, $descripcion]);
        }
    }

    $mensaje = "Asistencia registrada correctamente para el $fecha.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Asistencia</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Asistencia</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
        <!-- Selección de curso -->
        <div class="mb-3">
            <label for="idCurso" class="form-label">Curso:</label>
            <select name="idCurso" id="idCurso" class="form-select" required onchange="this.form.submit()">
                <option value="">-- Selecciona un curso --</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['idCurso']; ?>" <?php if (!empty($_POST['idCurso']) && $_POST['idCurso'] == $curso['idCurso']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($curso['nombreCurso']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if (!empty($_POST['idCurso'])): ?>
        <form method="post">
            <input type="hidden" name="idCurso" value="<?php echo intval($_POST['idCurso']); ?>">

            <!-- Fecha y descripción -->
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de la clase:</label>
                <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Tema / Descripción de la sesión:</label>
                <input type="text" name="descripcion" class="form-control" placeholder="Ej: Álgebra - Ecuaciones cuadráticas">
            </div>

            <!-- Lista de alumnos -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmtAlumnos = $pdo->prepare("
                        SELECT cl.idCliente, cl.apellidosNombres 
                        FROM matricula m 
                        JOIN cliente cl ON m.idCliente = cl.idCliente 
                        WHERE m.idCurso = :idCurso AND m.estado = 'Activo'
                    ");
                    $stmtAlumnos->execute(['idCurso' => $_POST['idCurso']]);
                    $alumnos = $stmtAlumnos->fetchAll();

                    foreach ($alumnos as $alumno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumno['apellidosNombres']); ?></td>
                            <td>
                                <select name="asistencia[<?php echo $alumno['idCliente']; ?>]" class="form-select">
                                    <option value="asistio">Asistió</option>
                                    <option value="tardanza">Tardanza</option>
                                    <option value="justificada">Falta justificada</option>
                                    <option value="falta">Faltó</option>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Registrar Asistencia</button>
        </form>
    <?php endif; ?>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['idCliente'];
$esAdmin = ($_SESSION['idCargo'] == 1);

// Obtener nombre del usuario para mostrar en el sidebar
$stmtNombre = $pdo->prepare("SELECT apellidosNombres FROM cliente WHERE idCliente = :id");
$stmtNombre->execute(['id' => $idUsuario]);
$nombreUsuario = $stmtNombre->fetchColumn();

// Obtener profesores si es admin
$profesores = [];
$idProfesorSeleccionado = $idUsuario;
if ($esAdmin) {
    $profesores = $pdo->query("SELECT idCliente, apellidosNombres FROM cliente WHERE idCargo = 2")->fetchAll();
    if (isset($_GET['idProfesor'])) {
        $idProfesorSeleccionado = $_GET['idProfesor'];
    } else {
        $idProfesorSeleccionado = $profesores[0]['idCliente'] ?? null;
    }
}

// Obtener cursos visibles
if ($idProfesorSeleccionado) {
    $stmt = $pdo->prepare("SELECT DISTINCT c.idCurso, c.nombreCurso, c.descripcion FROM cursos c JOIN grupos g ON g.idCurso = c.idCurso WHERE g.idProfesor = :idProfesor");
    $stmt->execute(['idProfesor' => $idProfesorSeleccionado]);
    $cursos = $stmt->fetchAll();
} else {
    $cursos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Docente</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        #content {
            flex-grow: 1;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div id="content">
        <h5>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></h5>
        <h3 class="mt-4">Cursos Asignados</h3>

        <?php if ($esAdmin && count($profesores) > 0): ?>
            <form method="get" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label for="idProfesor" class="form-label">Seleccionar profesor</label>
                        <select name="idProfesor" id="idProfesor" class="form-select" onchange="this.form.submit()">
                            <?php foreach ($profesores as $prof): ?>
                                <option value="<?php echo $prof['idCliente']; ?>" <?php echo ($prof['idCliente'] == $idProfesorSeleccionado) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prof['apellidosNombres']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        <?php endif; ?>

        <?php if (count($cursos) > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($cursos as $curso): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($curso['nombreCurso']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                <a href="panel_curso.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn btn-primary">Gestionar Curso</a>
                                <a href="seleccionar_tema.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn btn-outline-secondary btn-sm mt-2">Elegir Tema</a>
                                <a href="evaluaciones.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn btn-outline-primary btn-sm mt-2">Evaluaciones</a>
                                <a href="registrar_asistencia.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn btn-outline-success btn-sm mt-2">Asistencia</a>
                                <a href="todos_los_recursos.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn btn-outline-info btn-sm mt-2">Recursos</a>
                                <a href="crear_tarea.php?idCurso=<?php echo $curso['idCurso']; ?>" class="btn btn-outline-warning btn-sm mt-2">Tareas</a>

                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay cursos asignados.</div>
        <?php endif; ?>
    </div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Verificar el rol de usuario (1: Administrador)
$sqlUsuario = "SELECT idCargo FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmtUsuario = $pdo->prepare($sqlUsuario);
$stmtUsuario->execute(['usuario' => $usuario]);
$user = $stmtUsuario->fetch();

if (!$user || $user['idCargo'] != 1) {
    echo "No tienes permisos para acceder a esta página.";
    exit();
}

// Obtener los cursos disponibles
$sqlCursos = "SELECT idCurso, nombreCurso FROM cursos";
$stmtCursos = $pdo->prepare($sqlCursos);
$stmtCursos->execute();
$cursos = $stmtCursos->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Horarios</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Agregar Horarios</h2>

        <!-- Formulario para seleccionar el curso y agregar horarios -->
        <form method="POST" action="verificacion_horario.php">
            <div class="mb-3">
                <label for="idCurso" class="form-label">Seleccionar Curso</label>
                <select class="form-control" name="idCurso" id="idCurso" required>
                    <option value="">Selecciona un curso</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?php echo $curso['idCurso']; ?>"><?php echo htmlspecialchars($curso['nombreCurso']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="diaSemana" class="form-label">Selecciona el Día de la Semana</label>
                <select class="form-control" name="diaSemana" id="diaSemana" required>
                    <option value="">Selecciona un día</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="horaInicio" class="form-label">Hora de Inicio</label>
                <input type="time" class="form-control" name="horaInicio" id="horaInicio" required>
            </div>

            <div class="mb-3">
                <label for="horaFin" class="form-label">Hora de Fin</label>
                <input type="time" class="form-control" name="horaFin" id="horaFin" required>
            </div>

            <button type="submit" class="btn btn-primary">Verificar Horario</button>
        </form>
    </div>

    <script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

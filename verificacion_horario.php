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

// Obtener los datos del formulario
$idCurso = $_POST['idCurso'];
$diaSemana = $_POST['diaSemana'];
$horaInicio = $_POST['horaInicio'];
$horaFin = $_POST['horaFin'];

// Mostrar la vista previa de los horarios
echo "<h2>Verificación del Horario</h2>";
echo "<p><strong>Curso:</strong> $idCurso</p>";
echo "<p><strong>Día de la Semana:</strong> $diaSemana</p>";
echo "<p><strong>Hora de Inicio:</strong> $horaInicio</p>";
echo "<p><strong>Hora de Fin:</strong> $horaFin</p>";

// Formulario para confirmar y agregar el horario a la base de datos
?>
<form method="POST" action="insertar_horario.php">
    <input type="hidden" name="idCurso" value="<?php echo $idCurso; ?>">
    <input type="hidden" name="diaSemana" value="<?php echo $diaSemana; ?>">
    <input type="hidden" name="horaInicio" value="<?php echo $horaInicio; ?>">
    <input type="hidden" name="horaFin" value="<?php echo $horaFin; ?>">
    <button type="submit" class="btn btn-success">Confirmar y Agregar Horario</button>
</form>

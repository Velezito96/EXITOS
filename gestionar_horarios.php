<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idCliente = $_SESSION['idCliente']; 

// Verificar si el usuario tiene privilegios de administrador
$sqlCliente = "SELECT idCliente, idCargo FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->execute(['usuario' => $usuario]);
$cliente = $stmtCliente->fetch();
$idCargo = $cliente['idCargo'];

if ($idCargo != 1) { // Solo administrador puede gestionar horarios
    die("Acceso denegado.");
}

// Variables de mensaje
$mensaje = '';

// Manejo de inserción de horarios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idGrupo'], $_POST['diaSemana'], $_POST['horaInicio'], $_POST['horaFin'], $_POST['idCurso'], $_POST['idProfesor'])) {
    $idGrupo = $_POST['idGrupo'];
    $diaSemana = $_POST['diaSemana'];
    $horaInicio = $_POST['horaInicio'];
    $horaFin = $_POST['horaFin'];
    $idCurso = $_POST['idCurso'];
    $idProfesor = $_POST['idProfesor'];

    // Verificar que no haya cruce de horarios
    $sqlVerificar = "
        SELECT * FROM horarios
        WHERE idGrupo = :idGrupo
        AND diaSemana = :diaSemana
        AND (
            (horaInicio < :horaFin AND horaFin > :horaInicio)
        )
    ";
    $stmtVerificar = $pdo->prepare($sqlVerificar);
    $stmtVerificar->execute([
        'idGrupo' => $idGrupo,
        'diaSemana' => $diaSemana,
        'horaInicio' => $horaInicio,
        'horaFin' => $horaFin
    ]);
    $existeCruce = $stmtVerificar->fetch();

    if ($existeCruce) {
        $mensaje = "<div class='alert alert-danger' role='alert'>Error: El horario seleccionado se cruza con otro horario en el mismo grupo.</div>";
    } else {
        // Obtener el aula asociada al grupo
        $sqlAula = "SELECT aula FROM grupos WHERE idGrupo = :idGrupo";
        $stmtAula = $pdo->prepare($sqlAula);
        $stmtAula->execute(['idGrupo' => $idGrupo]);
        $aula = $stmtAula->fetchColumn();

        // Insertar el nuevo horario
        $sqlInsert = "
            INSERT INTO horarios (idGrupo, diaSemana, horaInicio, horaFin, idCurso, idProfesor, aula)
            VALUES (:idGrupo, :diaSemana, :horaInicio, :horaFin, :idCurso, :idProfesor, :aula)
        ";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            'idGrupo' => $idGrupo,
            'diaSemana' => $diaSemana,
            'horaInicio' => $horaInicio,
            'horaFin' => $horaFin,
            'idCurso' => $idCurso,
            'idProfesor' => $idProfesor,
            'aula' => $aula
        ]);

        $mensaje = "<div class='alert alert-success' role='alert'>¡Horario agregado correctamente!</div>";
    }
}

// Manejo de modificación de horarios
if (isset($_GET['action']) && isset($_GET['idHorario'])) {
    $idHorario = $_GET['idHorario'];

    if ($_GET['action'] == 'edit') {
        // Obtener los datos del horario para editar
        $sqlHorario = "SELECT * FROM horarios WHERE idHorario = :idHorario LIMIT 1";
        $stmtHorario = $pdo->prepare($sqlHorario);
        $stmtHorario->execute(['idHorario' => $idHorario]);
        $horario = $stmtHorario->fetch();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idGrupo'], $_POST['diaSemana'], $_POST['horaInicio'], $_POST['horaFin'], $_POST['idCurso'], $_POST['idProfesor'])) {
            $idGrupo = $_POST['idGrupo'];
            $diaSemana = $_POST['diaSemana'];
            $horaInicio = $_POST['horaInicio'];
            $horaFin = $_POST['horaFin'];
            $idCurso = $_POST['idCurso'];
            $idProfesor = $_POST['idProfesor'];

            // Verificar que no haya cruce de horarios
            $sqlVerificar = "
                SELECT * FROM horarios
                WHERE idGrupo = :idGrupo
                AND diaSemana = :diaSemana
                AND (
                    (horaInicio < :horaFin AND horaFin > :horaInicio)
                )
            ";
            $stmtVerificar = $pdo->prepare($sqlVerificar);
            $stmtVerificar->execute([
                'idGrupo' => $idGrupo,
                'diaSemana' => $diaSemana,
                'horaInicio' => $horaInicio,
                'horaFin' => $horaFin
            ]);
            $existeCruce = $stmtVerificar->fetch();

            if ($existeCruce) {
                $mensaje = "<div class='alert alert-danger' role='alert'>Error: El horario seleccionado se cruza con otro horario en el mismo grupo.</div>";
            } else {
                // Actualizar el horario
                $sqlUpdate = "
                    UPDATE horarios SET idGrupo = :idGrupo, diaSemana = :diaSemana, horaInicio = :horaInicio, horaFin = :horaFin, idCurso = :idCurso, idProfesor = :idProfesor
                    WHERE idHorario = :idHorario
                ";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    'idGrupo' => $idGrupo,
                    'diaSemana' => $diaSemana,
                    'horaInicio' => $horaInicio,
                    'horaFin' => $horaFin,
                    'idCurso' => $idCurso,
                    'idProfesor' => $idProfesor,
                    'idHorario' => $idHorario
                ]);
                $mensaje = "<div class='alert alert-success' role='alert'>¡Horario actualizado correctamente!</div>";
            }
        }
    }
}

// Manejo de eliminación de horarios
if (isset($_GET['action']) && isset($_GET['idHorario'])) {
    $idHorario = $_GET['idHorario'];

    if ($_GET['action'] == 'delete') {
        // Eliminar el horario de la base de datos
        $sqlDelete = "DELETE FROM horarios WHERE idHorario = :idHorario";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute(['idHorario' => $idHorario]);

        // Mostrar mensaje de eliminación exitosa
        $mensaje = "<div class='alert alert-danger' role='alert'>¡Horario eliminado correctamente!</div>";
    }
}

// Obtener los grupos y cursos disponibles para mostrar en el formulario
$sqlGrupos = "SELECT * FROM grupos";
$stmtGrupos = $pdo->query($sqlGrupos);
$grupos = $stmtGrupos->fetchAll();

$sqlCursos = "SELECT * FROM cursos";
$stmtCursos = $pdo->query($sqlCursos);
$cursos = $stmtCursos->fetchAll();

$sqlProfesores = "SELECT idProfesor, nombre FROM profesores";
$stmtProfesores = $pdo->query($sqlProfesores);
$profesores = $stmtProfesores->fetchAll();

// Obtener horarios existentes para mostrar en la tabla
$sqlHorarios = "SELECT h.*, g.nombreGrupo, c.nombreCurso, p.nombre AS nombreProfesor 
                FROM horarios h
                JOIN grupos g ON h.idGrupo = g.idGrupo
                JOIN cursos c ON h.idCurso = c.idCurso
                JOIN profesores p ON h.idProfesor = p.idProfesor";
$stmtHorarios = $pdo->query($sqlHorarios);
$horarios = $stmtHorarios->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Horarios</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Gestionar Horarios</h2>

    <!-- Mostrar el mensaje -->
    <?php if (isset($mensaje)) { echo $mensaje; } ?>

    <!-- Formulario para agregar horarios -->
    <form action="gestionar_horarios.php" method="POST">
        <div class="form-group">
            <label for="idGrupo">Grupo:</label>
            <select class="form-control" name="idGrupo" id="idGrupo" required>
                <option value="">Selecciona un grupo</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?php echo $grupo['idGrupo']; ?>"><?php echo $grupo['nombreGrupo']; ?> - Aula: <?php echo $grupo['aula']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="diaSemana">Día de la Semana:</label>
            <select class="form-control" name="diaSemana" id="diaSemana" required>
                <option value="">Selecciona un día</option>
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
                <option value="Sábado">Sábado</option>
            </select>
        </div>

        <div class="form-group">
            <label for="horaInicio">Hora de Inicio:</label>
            <input type="time" class="form-control" name="horaInicio" id="horaInicio" required>
        </div>

        <div class="form-group">
            <label for="horaFin">Hora de Fin:</label>
            <input type="time" class="form-control" name="horaFin" id="horaFin" required>
        </div>

        <div class="form-group">
            <label for="idCurso">Curso:</label>
            <select class="form-control" name="idCurso" id="idCurso" required>
                <option value="">Selecciona un curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['idCurso']; ?>"><?php echo $curso['nombreCurso']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="idProfesor">Profesor:</label>
            <select class="form-control" name="idProfesor" id="idProfesor" required>
                <option value="">Selecciona un profesor</option>
                <?php foreach ($profesores as $profesor): ?>
                    <option value="<?php echo $profesor['idProfesor']; ?>"><?php echo $profesor['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Horario</button>
    </form>

    <br>

    <!-- Listado de horarios -->
    <h3>Horarios Agregados</h3>
    <table class="table">
    <table class="table">
    <thead>
        <tr>
            <th>Día</th>
            <th>Hora de Inicio</th>
            <th>Hora de Fin</th>
            <th>Curso</th>
            <th>Grupo</th>
            <th>Aula</th>
            <th>Profesor</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Consulta para obtener los horarios
        $sqlHorarios = "SELECT h.*, g.nombreGrupo, c.nombreCurso, p.nombre AS nombreProfesor 
                        FROM horarios h
                        JOIN grupos g ON h.idGrupo = g.idGrupo
                        JOIN cursos c ON h.idCurso = c.idCurso
                        JOIN profesores p ON h.idProfesor = p.idProfesor";
        $stmtHorarios = $pdo->query($sqlHorarios);
        $horarios = $stmtHorarios->fetchAll();
        
        if (count($horarios) > 0):
            // Si hay horarios, los mostramos en la tabla
            foreach ($horarios as $horario):
        ?>
            <tr>
                <td><?php echo $horario['diaSemana']; ?></td>
                <td><?php echo $horario['horaInicio']; ?></td>
                <td><?php echo $horario['horaFin']; ?></td>
                <td><?php echo $horario['nombreCurso']; ?></td>
                <td><?php echo $horario['nombreGrupo']; ?></td>
                <td><?php echo $horario['aula']; ?></td>
                <td><?php echo $horario['nombreProfesor']; ?></td>
                <td>
                    <!-- Botón de Modificar (Fondo amarillo) -->
                    <a href="gestionar_horarios.php?idHorario=<?php echo $horario['idHorario']; ?>&action=edit" 
                       class="btn btn-warning btn-sm">Modificar</a>
                    
                    <!-- Botón de Eliminar (Fondo rojo) -->
                    <a href="gestionar_horarios.php?idHorario=<?php echo $horario['idHorario']; ?>&action=delete" 
                       class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este horario?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No hay horarios registrados</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

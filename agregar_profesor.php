<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Verificar si el usuario está logueado y tiene privilegios de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['idCargo'] != 1) {
    header("Location: login.php");
    exit();
}

// Si el formulario es enviado por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['telefono'], $_POST['dni'], $_POST['descripcion'], $_POST['gradoAcademico'], $_FILES['foto'], $_POST['idCurso'])) {
        
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $dni = $_POST['dni'];
        $descripcion = $_POST['descripcion'];
        $gradoAcademico = $_POST['gradoAcademico'];
        
        // Subir foto
        $foto = $_FILES['foto']['name'];
        $rutaFoto = "uploads/" . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $rutaFoto); // Guardar la foto en el servidor
        
        $idCurso = $_POST['idCurso']; // Curso al que se asignará el profesor

        // Verificar si el DNI ya está registrado
        $stmtVerificarDni = $pdo->prepare("SELECT * FROM profesores WHERE dni = :dni");
        $stmtVerificarDni->execute(['dni' => $dni]);
        $profesorExistente = $stmtVerificarDni->fetch();

        if ($profesorExistente) {
            echo "Error: El DNI ya está registrado para otro profesor.";
        } else {
            // Insertar el nuevo profesor en la tabla profesores
            $sqlProfesor = "INSERT INTO profesores (nombre, apellido, email, telefono, dni, descripcion, gradoAcademico, foto) 
                            VALUES (:nombre, :apellido, :email, :telefono, :dni, :descripcion, :gradoAcademico, :foto)";
            $stmtProfesor = $pdo->prepare($sqlProfesor);
            $stmtProfesor->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono,
                'dni' => $dni,
                'descripcion' => $descripcion,
                'gradoAcademico' => $gradoAcademico,
                'foto' => $rutaFoto
            ]);
            
            // Obtener el id del nuevo profesor insertado
            $idProfesor = $pdo->lastInsertId();

            // Insertar la relación entre el profesor y el curso
            $sqlProfesorCurso = "INSERT INTO profesor_curso (idProfesor, idCurso) VALUES (:idProfesor, :idCurso)";
            $stmtProfesorCurso = $pdo->prepare($sqlProfesorCurso);
            $stmtProfesorCurso->execute([
                'idProfesor' => $idProfesor,
                'idCurso' => $idCurso
            ]);

            echo "Profesor agregado correctamente y asignado al curso.";
        }
    } else {
        echo "Faltan campos requeridos.";
    }
}

// Obtener los cursos disponibles para mostrar en el formulario
$sqlCursos = "SELECT * FROM cursos";
$stmtCursos = $pdo->query($sqlCursos);
$cursos = $stmtCursos->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Profesor</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Agregar Profesor</h2>
    <form action="agregar_profesor.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>

        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" class="form-control" name="apellido" id="apellido" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" class="form-control" name="telefono" id="telefono" required>
        </div>

        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" class="form-control" name="dni" id="dni" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" name="descripcion" id="descripcion" required></textarea>
        </div>

        <div class="form-group">
            <label for="gradoAcademico">Grado Académico:</label>
            <input type="text" class="form-control" name="gradoAcademico" id="gradoAcademico" required>
        </div>

        <div class="form-group">
            <label for="foto">Foto del Profesor:</label>
            <input type="file" class="form-control" name="foto" id="foto" required>
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

        <button type="submit" class="btn btn-primary">Agregar Profesor</button>
    </form>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

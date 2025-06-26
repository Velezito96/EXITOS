<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idSesion = $_GET['idSesion'] ?? null;
$idCurso = $_GET['idCurso'] ?? null;
$idProfesor = $_SESSION['idCliente'];

if (!$idSesion || !$idCurso) {
    $_SESSION['error'] = "Parámetros incompletos.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Validar acceso del profesor al curso
$stmt = $pdo->prepare("SELECT c.nombreCurso FROM cursos c JOIN grupos g ON g.idCurso = c.idCurso WHERE c.idCurso = ? AND g.idProfesor = ?");
$stmt->execute([$idCurso, $idProfesor]);
$nombreCurso = $stmt->fetchColumn();
if (!$nombreCurso) {
    $_SESSION['error'] = "Acceso denegado al curso.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Obtener la sesión actual
$stmt = $pdo->prepare("SELECT * FROM sesiones WHERE idSesion = ? AND idCurso = ?");
$stmt->execute([$idSesion, $idCurso]);
$sesion = $stmt->fetch();

if (!$sesion) {
    $_SESSION['error'] = "Sesión no encontrada.";
    header("Location: sesiones.php?idCurso=$idCurso");
    exit();
}

// Procesar edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'] ?? $sesion['numero'];
    $titulo = $_POST['titulo'] ?? $sesion['titulo'];
    $descripcion = $_POST['descripcion'] ?? $sesion['descripcion'];
    $link = $_POST['link'] ?? $sesion['link'];

    $archivo = $sesion['archivo'];
    if (!empty($_FILES['archivo']['name'])) {
        $nombreArchivo = time() . '_' . basename($_FILES['archivo']['name']);
        $ruta = 'archivos/' . $nombreArchivo;
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
            $archivo = $nombreArchivo;
        }
    }

    $stmt = $pdo->prepare("UPDATE sesiones SET numero = ?, titulo = ?, descripcion = ?, archivo = ?, link = ? WHERE idSesion = ?");
    $stmt->execute([$numero, $titulo, $descripcion, $archivo, $link, $idSesion]);

    $_SESSION['success'] = "Sesión actualizada correctamente.";
    header("Location: sesiones.php?idCurso=$idCurso");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sesión</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <h2>Editar Sesión del Curso: <?php echo htmlspecialchars($nombreCurso); ?></h2>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="numero" class="form-label">Número de Sesión</label>
            <input type="number" name="numero" class="form-control" value="<?php echo htmlspecialchars($sesion['numero']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($sesion['titulo']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($sesion['descripcion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="archivo" class="form-label">Reemplazar archivo (opcional)</label>
            <input type="file" name="archivo" class="form-control">
            <?php if ($sesion['archivo']): ?>
                <small class="text-muted">Actual: <a href="archivos/<?php echo $sesion['archivo']; ?>" target="_blank"><?php echo $sesion['archivo']; ?></a></small>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Enlace complementario</label>
            <input type="url" name="link" class="form-control" value="<?php echo htmlspecialchars($sesion['link']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="sesiones.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Cancelar</a>
    </form>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

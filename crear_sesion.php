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
    $_SESSION['error'] = "Curso no especificado.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'] ?? 0;
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $link = $_POST['link'] ?? '';

    $archivo = null;

    if (!empty($_FILES['archivo']['name'])) {
        // Validar tipo de archivo permitido
        $permitidos = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' // .pptx
        ];

        if (!in_array($_FILES['archivo']['type'], $permitidos)) {
            $_SESSION['error'] = "Tipo de archivo no permitido. Solo PDF, DOCX o PPTX.";
            header("Location: crear_sesion.php?idCurso=$idCurso");
            exit();
        }

        // Asegurar carpeta y nombre único
        $nombreArchivo = uniqid('sesion_', true) . '_' . basename($_FILES['archivo']['name']);
        $ruta = 'uploads/' . $nombreArchivo;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
            $archivo = $nombreArchivo;
        } else {
            $_SESSION['error'] = "No se pudo subir el archivo. Código: " . $_FILES['archivo']['error'];
            header("Location: crear_sesion.php?idCurso=$idCurso");
            exit();
        }
    }

    // Validar URL si existe
    if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
        $_SESSION['error'] = "El enlace ingresado no es válido.";
        header("Location: crear_sesion.php?idCurso=$idCurso");
        exit();
    }

    // Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO sesiones (idCurso, numero, titulo, descripcion, archivo, link) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$idCurso, $numero, $titulo, $descripcion, $archivo, $link]);

    $_SESSION['success'] = "Sesión creada exitosamente.";
    header("Location: sesiones.php?idCurso=$idCurso");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Sesión</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <h2>Nueva Sesión</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="numero" class="form-label">Número de Sesión</label>
            <input type="number" name="numero" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="archivo" class="form-label">Archivo (PDF, DOCX, PPTX)</label>
            <input type="file" name="archivo" class="form-control">
        </div>

        <div class="mb-3">
            <label for="link" class="form-label">Enlace Complementario (opcional)</label>
            <input type="url" name="link" class="form-control" placeholder="https://...">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="sesiones.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Cancelar</a>
    </form>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

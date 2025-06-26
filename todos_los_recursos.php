<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

// Obtener todos los recursos con datos del curso y del profesor
$stmt = $pdo->query("
    SELECT rm.*, 
           c.nombreCurso, 
           cl.apellidosNombres AS profesor
    FROM recursos_multimedia rm
    JOIN cursos c ON rm.idCurso = c.idCurso
    JOIN cliente cl ON rm.idProfesor = cl.idCliente
    ORDER BY rm.fecha DESC
");
$recursos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Todos los Recursos Multimedia</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Todos los Recursos Multimedia</h3>

    <?php if (count($recursos) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Curso</th>
                    <th>Profesor</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Acción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recursos as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['nombreCurso']); ?></td>
                        <td><?php echo htmlspecialchars($r['profesor']); ?></td>
                        <td><?php echo htmlspecialchars($r['titulo']); ?></td>
                        <td><?php echo ucfirst($r['tipo']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($r['ruta']); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                Ver
                            </a>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($r['fecha'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">No se han registrado recursos multimedia aún.</div>
    <?php endif; ?>

    <a href="gestionar_cursos.php" class="btn btn-secondary mt-3">Volver</a>
</div>
</body>
</html>

<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idUsuario = $_SESSION['idCliente'];

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Validar si el profesor tiene acceso
if ($_SESSION['idCargo'] != 1) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos WHERE idCurso = ? AND idProfesor = ?");
    $stmt->execute([$idCurso, $idUsuario]);
    if ($stmt->fetchColumn() == 0) {
        echo "No tienes permiso para este curso.";
        exit();
    }
}

// Obtener alumnos matriculados
$stmt = $pdo->prepare("
    SELECT cl.idCliente, cl.apellidosNombres, nc.nota1, nc.nota2, nc.nota3, nc.nota4
    FROM matricula m
    JOIN cliente cl ON cl.idCliente = m.idCliente
    LEFT JOIN notas_curso nc ON nc.idAlumno = cl.idCliente AND nc.idCurso = m.idCurso
    WHERE m.idCurso = ?
    ORDER BY cl.apellidosNombres
");
$stmt->execute([$idCurso]);
$alumnos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notas del Curso</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
    <style>
        .aprobado { color: blue; font-weight: bold; }
        .desaprobado { color: red; font-weight: bold; }
    </style>
</head>
<body class="container py-4">
    <h3>Notas de los Alumnos</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Alumno</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Nota 3</th>
                <th>Nota 4</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $a): 
                $n1 = $a['nota1'];
                $n2 = $a['nota2'];
                $n3 = $a['nota3'];
                $n4 = $a['nota4'];

                $notasValidas = array_filter([$n1, $n2, $n3, $n4], fn($n) => is_numeric($n));
                $promedio = count($notasValidas) ? array_sum($notasValidas) / 4 : null;
                $clase = ($promedio !== null && $promedio >= 10.5) ? 'aprobado' : 'desaprobado';
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['apellidosNombres']); ?></td>
                    <td><?php echo is_numeric($n1) ? number_format($n1, 1) : '-'; ?></td>
                    <td><?php echo is_numeric($n2) ? number_format($n2, 1) : '-'; ?></td>
                    <td><?php echo is_numeric($n3) ? number_format($n3, 1) : '-'; ?></td>
                    <td><?php echo is_numeric($n4) ? number_format($n4, 1) : '-'; ?></td>
                    <td class="<?php echo $promedio !== null ? $clase : ''; ?>">
                        <?php echo $promedio !== null ? number_format($promedio, 2) : 'N/A'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Volver</a>
</body>
</html>

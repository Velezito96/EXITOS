<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['idCliente'];

// Obtener datos del profesor
$stmt = $pdo->prepare("
    SELECT c.apellidosNombres, c.numeroDNI, c.email, c.direccion, c.telefono, ca.Descripcion AS cargo, c.foto
    FROM cliente c
    LEFT JOIN cargo ca ON c.idCargo = ca.idCargo
    WHERE c.idCliente = :id
");
$stmt->execute(['id' => $id]);
$perfil = $stmt->fetch();

if (!$perfil) {
    echo "Perfil no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Profesor</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Mi Perfil</h3>

    <div class="row">
        <div class="col-md-4">
            <?php if (!empty($perfil['foto'])): ?>
                <img src="<?php echo htmlspecialchars($perfil['foto']); ?>" alt="Foto de perfil" class="img-thumbnail">
            <?php else: ?>
                <div class="text-muted">No hay foto de perfil.</div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <table class="table table-striped">
                <tr><th>Nombre</th><td><?php echo htmlspecialchars($perfil['apellidosNombres']); ?></td></tr>
                <tr><th>DNI</th><td><?php echo htmlspecialchars($perfil['numeroDNI']); ?></td></tr>
                <tr><th>Email</th><td><?php echo htmlspecialchars($perfil['email']); ?></td></tr>
                <tr><th>Dirección</th><td><?php echo htmlspecialchars($perfil['direccion']); ?></td></tr>
                <tr><th>Teléfono</th><td><?php echo htmlspecialchars($perfil['telefono']); ?></td></tr>
                <tr><th>Cargo</th><td><?php echo htmlspecialchars($perfil['cargo']); ?></td></tr>
            </table>
            <a href="gestionar_cursos.php" class="btn btn-secondary mt-3">Volver al Panel</a>
        </div>
    </div>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'connect.php';  // Asegúrate de incluir esta línea para la conexión a la base de datos

// Inicializar la conexión PDO
$pdo = conexionBD();

// Asegurarse de que el ID del cliente está en la sesión
if (!isset($_SESSION['idCliente'])) {
    echo "No se ha encontrado el ID del cliente en la sesión.";
    exit;
}

$idCliente = $_SESSION['idCliente'];

// Consulta para obtener la información del usuario
$sql = "SELECT * FROM cliente WHERE idCliente = :idCliente";
$stmt = $pdo->prepare($sql);
$stmt->execute(['idCliente' => $idCliente]);
$cliente = $stmt->fetch();

if ($cliente) {
    // Asignación de los valores obtenidos de la base de datos
    $nombres = $cliente['apellidosNombres'];  // Nombre completo
    $dni = $cliente['numeroDNI'];  // DNI
    $direccion = $cliente['direccion'];  // Dirección
    $telefono = $cliente['telefono'];  // Teléfono
    $email = $cliente['email'];  // Correo electrónico
    $foto = $cliente['foto'];  // Foto de perfil (si tiene)
} else {
    echo "Usuario no encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Perfil</title>
    <link rel="stylesheet" href="css/styles.css">  <!-- Referencia a tu archivo de estilos CSS -->
</head>
<body>
    <!-- Cabecera del perfil -->
    <div class="profile-header">
        <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil" class="profile-img">
        <h2><?php echo htmlspecialchars($nombres); ?></h2>
    </div>

    <!-- Información del perfil -->
    <div class="profile-info">
        <p><strong>DNI:</strong> <?php echo htmlspecialchars($dni); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($email); ?></p>
    </div>
</body>
</html>

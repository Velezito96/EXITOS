<?php
require 'connect.php';
$pdo = conexionBD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $clave = trim($_POST['clave']);
    $idCargo = $_POST['idCargo'];

    if (empty($usuario) || empty($clave) || empty($idCargo)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        $verificar = $pdo->prepare("SELECT * FROM cliente WHERE usuario = ?");
        $verificar->execute([$usuario]);
        if ($verificar->rowCount() > 0) {
            $mensaje = "El nombre de usuario ya existe.";
        } else {
            $claveHasheada = password_hash($clave, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO cliente (usuario, clave, idCargo) VALUES (?, ?, ?)");
            if ($stmt->execute([$usuario, $claveHasheada, $idCargo])) {
                $mensaje = "✅ Usuario registrado correctamente.";
            } else {
                $mensaje = "❌ Error al registrar usuario.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #e6f2e6, #b3ddb3);
        }

        .card {
            background-color: white;
            border-radius: 15px;
        }

        .btn-custom {
            background-color: #225f27;
            color: white;
            border: none;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: #1b4d1f;
        }

        a {
            color: #225f27;
        }

        a:hover {
            color: #1b4d1f;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow p-4">
            <h4 class="mb-3 text-center">Registrar Nuevo Usuario</h4>

            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-info"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre de Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="clave" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="idCargo" class="form-select" required>
                        <option value="">Seleccione un rol</option>
                        <option value="1">Administrador</option>
                        <option value="2">Profesor</option>
                        <option value="3">Alumno</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-custom">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require 'connect.php';
$pdo = conexionBD();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $usuario = trim($_POST['usuario']);
    $clave = trim($_POST['clave']);
    $idCargo = $_POST['idCargo'];
    $tipoCliente = $_POST['tipoCliente'];
    $tipoIdentificacion = $_POST['tipoIdentificacion'];
    $numeroDNI = $_POST['numeroDNI'];
    $apellidosNombres = $_POST['apellidosNombres'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    // Validaciones básicas
    if (empty($usuario) || empty($clave) || empty($idCargo) || empty($apellidosNombres) || empty($direccion) || empty($telefono) || empty($email)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // Validar si el nombre de usuario ya existe
        $verificar = $pdo->prepare("SELECT * FROM cliente WHERE usuario = ?");
        $verificar->execute([$usuario]);
        if ($verificar->rowCount() > 0) {
            $mensaje = "El nombre de usuario ya existe.";
        } else {
            // Si no existe, insertar el nuevo cliente
            $claveHasheada = password_hash($clave, PASSWORD_DEFAULT);

            // Si la foto no se sube, asigna null
            $foto = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                // Guardar la foto si fue cargada
                $foto = 'uploads/' . basename($_FILES['foto']['name']);
                move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
            }

            // Insertar el cliente en la base de datos
            $stmt = $pdo->prepare("INSERT INTO cliente (usuario, clave, idCargo, tipoCliente, tipoIdentificacion, numeroDNI, apellidosNombres, direccion, telefono, email, foto) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $usuario,
                $claveHasheada,
                $idCargo,
                $tipoCliente,
                $tipoIdentificacion,
                $numeroDNI,
                $apellidosNombres,
                $direccion,
                $telefono,
                $email,
                $foto
            ]);

            // Mensaje de éxito
            $mensaje = "✅ Usuario registrado correctamente.";
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

            <form action="" method="POST" enctype="multipart/form-data">
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

                <div class="mb-3">
                    <label class="form-label">Tipo de Cliente</label>
                    <select name="tipoCliente" class="form-select" required>
                        <option value="1">Natural</option>
                        <option value="2">Jurídico</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Identificación</label>
                    <input type="text" name="tipoIdentificacion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Número de DNI</label>
                    <input type="text" name="numeroDNI" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellidos y Nombres</label>
                    <input type="text" name="apellidosNombres" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto de Perfil (Opcional)</label>
                    <input type="file" name="foto" class="form-control">
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

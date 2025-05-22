<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Aula Virtual</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #e6f2e6, #b3ddb3); /* Fondo verde */
        }

        .card {
            background-color: white; /*  para el cuadro blanco */
            border-radius: 15px;
        }

        .btn-custom {
            background-color: #225f27;
            color: white;
            border: none;
            width: 100%; /* Ocupa todo el ancho para mejor presentación */
        }

        .btn-custom:hover {
            background-color: #1b4d1f;
        }

        a {
            color: #225f27;
        }

        a:hover {
            text-decoration: underline;
            color: #1b4d1f;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="text-center">
                <img src="..\img\portada.jpg" class="img-fluid rounded mb-3" alt="Portada">
                <h4 class="mb-3">Accede a tu aula virtual</h4>
            </div>

            <div class="card shadow p-4">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <form action="procesar_login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="clave" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-custom">Ingresar</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="olvidar_contraseña.php">¿Olvidaste tu contraseña?</a>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

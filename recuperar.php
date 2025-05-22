<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h4 class="text-center mb-4">Recuperar Contraseña</h4>

                <form action="procesar_recuperacion.php" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-2">Enviar</button>
                    <a href="login.php" class="btn btn-secondary w-100">Volver</a>
                </form>

            </div>
        </div>
    </div>
</div>
<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

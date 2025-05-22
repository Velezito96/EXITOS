<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
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
        }

        .btn-custom:hover {
            background-color: #1b4d1f;
        }

        .btn-exit {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-exit:hover {
            background-color: #bb2d3b;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow p-4">
                <h4 class="text-center mb-4">Recuperar Contraseña</h4>

                <form action="procesar_recuperacion.php" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-custom me-2">Enviar</button>
                        <a href="login.php" class="btn btn-exit">Salir</a>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

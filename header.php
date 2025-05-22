<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1.0,minimun-scale=1.0">
    <title>EXITOS DEL SABER</title>
    <link rel="stylesheet" href="./BOOTSTRAP/css/estilos.css">
    <link rel="stylesheet" href="./BOOTSTRAP/css/bootstrap.min.css">
    <link rel="stylesheet" href="./BOOTSTRAP/css/docs.css">    
    <link rel="stylesheet" href="./BOOTSTRAP/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./BOOTSTRAP/fontawesome/fontawesome/css/all.css">
</head>
<body>
    <nav class="navbar bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">
                <img class="card-img-top" src="./img/logos/logo.png" alt="" width="350" height="50">
            </a>
            <ul class="nav justify-content-end align-items-center">
                <li class="nav-link">
                    <a class="fa-solid fa-graduation-cap me-3" href="frmConsultaCertificado.php" data-bs-toggle="modal" data-bs-target="#formConsultaModal">
                        <span class="letra" style="font-family:system-ui; font-size:60%"> Consulta Certificado</span>
                    </a>

                    <?php if (isset($_SESSION['usuario'])): ?>
                        <span class="me-2 letra" style="font-family:system-ui; font-size:60%">👤 
                            Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
                        </span>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm me-2">Cerrar Sesión</a>
                    <?php else: ?>
                        <a class="fa-solid fa-user me-3" href="login.php">
                            <span class="letra" style="font-family:system-ui; font-size:60%"> Iniciar Sesión</span>
                        </a>
                    <?php endif; ?>

                    <a class="fa-solid fa-phone-volume" href="https://convocatorias.regioncajamarca.gob.pe/convocatorias/" target="_blank">
                        <span class="letra" style="font-family:system-ui; font-size:50%"> Contáctenos</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

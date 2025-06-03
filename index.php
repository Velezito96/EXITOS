<?php 
    session_start();
    include("./header.php");    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aula Virtual - Inicio</title>
    <link rel="stylesheet" href="./BOOTSTRAP/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/estilos.css"> <!-- Opcional: tus estilos -->
    <style>
        body {
            background-color: #e6f2e6;
        }
        .btn-iniciar {
            background-color: #b3ddb3 !important;
            color: #000 !important;
            border: none !important;
        }
        .btn-iniciar:hover {
            background-color: #9bd49b !important;
            color: #000 !important;
        }
        .user-greeting {
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<section class="margin-section">
    <?php include("./carrusel.php"); ?>
</section>

<div class="containers">
    <div class="left">
        <div class="row row-cols-1 row-cols-md-4 g-4" id="contenedorDatos"></div>
    </div>
    <div class="right">
        <div class="card-footer text-muted" style="padding: 5%">          
            <a class="btn btn-primarys" data-bs-toggle="modal" data-bs-target="#frm_M_consulta">
                <div class="col-auto">
                    <i class="bi bi-mortarboard-fill"></i> 
                </div>
                Consulta Certificados
            </a>
        </div>
    </div>
</div>

<?php
    include("frmSolicitante.php");
    include("frmCertificado.php");
?>

<!-- Botón Iniciar Sesión o Saludo con Cerrar Sesión -->
<div class="text-center user-greeting">
    <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="login.php" class="btn btn-iniciar">Iniciar Sesión</a>
    <?php else: ?>
        <p>Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 
            <a href="logout.php" class="btn btn-iniciar ms-2">Cerrar sesión</a>
        </p>
    <?php endif; ?>
</div>

<script src="./BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
<script src="./js/listarCursos.js"></script>

<?php include("./footer.php"); ?>
</body>
</html>

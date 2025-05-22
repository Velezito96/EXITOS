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

<!-- Botón de Acceso/Login -->
<div class="text-center mt-4">
    <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="login.php" class="btn btn-success">Acceder</a>
    <?php else: ?>
        <p>Hola, <?php echo $_SESSION['usuario']; ?>. 
            <a href="panel.php" class="btn btn-primary">Ir al Panel</a> 
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </p>
    <?php endif; ?>
</div>

<script src="./BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
<script src="./js/listarCursos.js"></script>

<?php include("./footer.php"); ?>
</body>
</html>

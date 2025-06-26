<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
$pdo = conexionBD();

$usuario = $_SESSION['usuario'];

// Obtener idCliente según usuario
$sqlCliente = "SELECT idCliente FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->execute(['usuario' => $usuario]);
$cliente = $stmtCliente->fetch();

if (!$cliente) {
    echo "Error: Usuario no encontrado.";
    exit();
}
$idCliente = $cliente['idCliente'];

// Obtener cursos
$sqlCursos = "SELECT * FROM cursos";
$stmtCursos = $pdo->query($sqlCursos);
$cursos = $stmtCursos->fetchAll();

// Obtener niveles
$sqlNiveles = "SELECT * FROM niveles";
$stmtNiveles = $pdo->query($sqlNiveles);
$niveles = $stmtNiveles->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cursos Disponibles - Aula Virtual</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #e6f2e6;
            padding: 20px;
        }
        .course-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .btn-matricular {
            background-color: #b3ddb3;
            border: none;
            color: black;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-matricular:hover {
            background-color: #9bd49b;
            color: black;
            text-decoration: none;
        }
        select {
            width: 100%;
            padding: 6px;
            margin-top: 6px;
            margin-bottom: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h1>Cursos Disponibles</h1>
    <div class="container">
        <?php foreach ($cursos as $curso): ?>
            <div class="course-card">
                <h3><?php echo htmlspecialchars($curso['nombreCurso']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                <form method="POST" action="matricular.php" style="display:inline;">
                    <input type="hidden" name="idCurso" value="<?php echo $curso['idCurso']; ?>">
                    <input type="hidden" name="idCliente" value="<?php echo $idCliente; ?>">

                    <label for="nivel_<?php echo $curso['idCurso']; ?>">Selecciona nivel:</label>
                    <select name="idNivel" id="nivel_<?php echo $curso['idCurso']; ?>" required>
                        <option value="">-- Seleccione un nivel --</option>
                        <?php foreach ($niveles as $nivel): ?>
                            <option value="<?php echo $nivel['idNivel']; ?>"><?php echo htmlspecialchars($nivel['descripcion']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn-matricular">
                        <i class="bi bi-pencil-square"></i> Matricularme
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-4">Volver al Dashboard</a>
</body>
</html>
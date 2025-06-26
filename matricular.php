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

// Obtener datos del formulario
$idCurso = $_POST['idCurso'];
$idNivel = $_POST['idNivel'];

// Iniciar una transacción
$pdo->beginTransaction();

try {
    // Verificar si el cliente ya está matriculado en el curso
    $sqlVerificar = "SELECT * FROM matricula WHERE idCliente = :idCliente AND idCurso = :idCurso";
    $stmtVerificar = $pdo->prepare($sqlVerificar);
    $stmtVerificar->execute(['idCliente' => $idCliente, 'idCurso' => $idCurso]);

    if ($stmtVerificar->rowCount() > 0) {
        throw new Exception("Ya estás matriculado en este curso.");
    }

    // Insertar la matrícula si no está matriculado
    $sqlMatricular = "INSERT INTO matricula (idCliente, idCurso, idNivel, estado) VALUES (:idCliente, :idCurso, :idNivel, 'Activo')";
    $stmtMatricular = $pdo->prepare($sqlMatricular);
    $stmtMatricular->execute(['idCliente' => $idCliente, 'idCurso' => $idCurso, 'idNivel' => $idNivel]);

    // Si todo fue exitoso, hacer commit de la transacción
    $pdo->commit();

    $_SESSION['matricula_exito'] = "Te has matriculado correctamente en el curso.";
    header("Location: dashboard.php");
    exit();
} catch (Exception $e) {
    // Si hay un error, hacer rollback de la transacción
    $pdo->rollBack();
    $_SESSION['error'] = $e->getMessage();
    header("Location: cursos_disponibles.php");
    exit();
}
?>

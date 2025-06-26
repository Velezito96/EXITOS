<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 3) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];
$idEvaluacion = $_POST['idEvaluacion'] ?? null;

if (!$idEvaluacion || !isset($_POST['idPregunta'])) {
    echo "Datos incompletos.";
    exit();
}

$preguntasIds = $_POST['idPregunta'];
$nota = 0;
$puntajeTotal = 0;
$respuestasAlumno = [];

foreach ($preguntasIds as $idPregunta) {
    $respuestaAlumno = $_POST["respuesta_$idPregunta"] ?? '';

    $stmt = $pdo->prepare("SELECT respuesta, tipo, valor FROM preguntas WHERE idPregunta = ? AND idEvaluacion = ?");
    $stmt->execute([$idPregunta, $idEvaluacion]);
    $pregunta = $stmt->fetch();

    if ($pregunta) {
        $tipo = $pregunta['tipo'];
        $correcta = strtolower(trim($pregunta['respuesta']));
        $valor = floatval($pregunta['valor']);
        $puntajeTotal += $valor;

        $respuestaLimpia = strtolower(trim($respuestaAlumno));

        if ($tipo === 'opcion' || $tipo === 'vf') {
            if ($respuestaLimpia === $correcta) {
                $nota += $valor;
            }
        }

        $respuestasAlumno[] = [
            'idPregunta' => $idPregunta,
            'respuesta' => $respuestaAlumno,
            'correcta' => $correcta,
            'valor' => $valor,
            'tipo' => $tipo
        ];
    }
}

$notaFinal = ($puntajeTotal > 0) ? round(($nota * 20) / $puntajeTotal, 2) : 0;

// Guardar resultado final
$stmt = $pdo->prepare("INSERT INTO resultados (idEvaluacion, idCliente, notaFinal) VALUES (?, ?, ?)");
$stmt->execute([$idEvaluacion, $idCliente, $notaFinal]);

$_SESSION['examen_resultado'] = [
    'nota' => $notaFinal,
    'respuestas' => $respuestasAlumno
];

header("Location: resultado_examen.php?idEvaluacion=$idEvaluacion");
exit();

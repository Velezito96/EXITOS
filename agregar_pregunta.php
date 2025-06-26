<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idEvaluacion = $_GET['idEvaluacion'] ?? null;
$idProfesor = $_SESSION['idCliente'];

if (!$idEvaluacion) {
    $_SESSION['error'] = "Evaluación no especificada.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Validar acceso a la evaluación
$stmt = $pdo->prepare("SELECT e.titulo, e.idCurso FROM evaluaciones e JOIN grupos g ON g.idCurso = e.idCurso WHERE e.id = ? AND g.idProfesor = ?");
$stmt->execute([$idEvaluacion, $idProfesor]);
$evaluacion = $stmt->fetch();

if (!$evaluacion) {
    $_SESSION['error'] = "Acceso denegado a esta evaluación.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Insertar nuevas preguntas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = intval($_POST['cantidad']);
    for ($i = 1; $i <= $cantidad; $i++) {
        $tipo = $_POST["tipo_$i"] ?? 'opcion';
        $texto = $_POST["texto_$i"] ?? '';
        $respuesta = $_POST["respuesta_$i"] ?? '';
        $valor = floatval($_POST["valor_$i"] ?? 1);
        $opcionA = $_POST["opcionA_$i"] ?? null;
        $opcionB = $_POST["opcionB_$i"] ?? null;
        $opcionC = $_POST["opcionC_$i"] ?? null;
        $opcionD = $_POST["opcionD_$i"] ?? null;

        if ($texto && $respuesta) {
            $stmt = $pdo->prepare("INSERT INTO preguntas (idEvaluacion, tipo, texto, opcionA, opcionB, opcionC, opcionD, respuesta, valor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$idEvaluacion, $tipo, $texto, $opcionA, $opcionB, $opcionC, $opcionD, $respuesta, $valor]);
        }
    }
    $_SESSION['success'] = "Preguntas agregadas correctamente.";
    header("Location: banco_preguntas.php?idEvaluacion=$idEvaluacion");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Preguntas - <?php echo htmlspecialchars($evaluacion['titulo']); ?></title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
    <script>
        function generarCampos() {
            const cantidad = document.getElementById("cantidad").value;
            const container = document.getElementById("preguntasContainer");
            container.innerHTML = "";

            for (let i = 1; i <= cantidad; i++) {
                const bloque = document.createElement("div");
                bloque.className = "mb-4 border rounded p-3";
                bloque.innerHTML = `
                    <h5>Pregunta ${i}</h5>
                    <div class="mb-2">
                        <textarea name="texto_${i}" class="form-control" placeholder="Enunciado" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Tipo de pregunta:</label>
                        <select name="tipo_${i}" class="form-select" onchange="toggleOpciones(this, ${i})">
                            <option value="opcion">Alternativas</option>
                            <option value="vf">Verdadero / Falso</option>
                            <option value="abierta">Respuesta Libre</option>
                        </select>
                    </div>
                    <div id="opciones_${i}" class="mb-2">
                        <input type="text" name="opcionA_${i}" class="form-control mb-1" placeholder="Opción A">
                        <input type="text" name="opcionB_${i}" class="form-control mb-1" placeholder="Opción B">
                        <input type="text" name="opcionC_${i}" class="form-control mb-1" placeholder="Opción C">
                        <input type="text" name="opcionD_${i}" class="form-control mb-1" placeholder="Opción D">
                    </div>
                    <div class="mb-2">
                        <label>Respuesta correcta:</label>
                        <input type="text" name="respuesta_${i}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Valor de la pregunta (puntos):</label>
                        <input type="number" step="0.1" name="valor_${i}" class="form-control" value="1" required>
                    </div>
                `;
                container.appendChild(bloque);
            }
        }

        function toggleOpciones(select, i) {
            const contenedor = document.getElementById(`opciones_${i}`);
            contenedor.style.display = select.value === 'opcion' ? 'block' : 'none';
        }
    </script>
</head>
<body class="container py-4">
    <h2>Agregar Preguntas a: <?php echo htmlspecialchars($evaluacion['titulo']); ?></h2>

    <form method="post">
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad de preguntas</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" max="10" required>
        </div>
        <button type="button" onclick="generarCampos()" class="btn btn-primary mb-4">Generar Campos</button>

        <div id="preguntasContainer"></div>

        <button type="submit" class="btn btn-success">Guardar Preguntas</button>
        <a href="banco_preguntas.php?idEvaluacion=<?php echo $idEvaluacion; ?>" class="btn btn-secondary">Cancelar</a>
    </form>
<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>

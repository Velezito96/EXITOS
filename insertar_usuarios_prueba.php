<?php
require 'connect.php';
$conexion = conexionBD();


$usuarios = [
    [
        'usuario' => 'admin01',
        'clave' => password_hash('admin123', PASSWORD_DEFAULT),
        'idCargo' => 1
    ],
    [
        'usuario' => 'empleado01',
        'clave' => password_hash('trabajo123', PASSWORD_DEFAULT),
        'idCargo' => 2
    ],
    [
        'usuario' => 'alumno01',
        'clave' => password_hash('alumno123', PASSWORD_DEFAULT),
        'idCargo' => 3
    ],
    [
        'usuario' => 'apoderado01',
        'clave' => password_hash('apoderado123', PASSWORD_DEFAULT),
        'idCargo' => 4
    ]
];

try {
    $stmt = $conexion->prepare("INSERT INTO cliente (usuario, clave, idCargo) VALUES (:usuario, :clave, :idCargo)");

    foreach ($usuarios as $u) {
        $stmt->execute($u);
    }

    echo "Usuarios de prueba insertados correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

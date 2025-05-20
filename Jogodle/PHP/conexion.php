<?php
// Conexion y Datos de la base de datos
$hostBD = '127.0.0.1';
$nombreBD = 'Jogodle';
$usuarioBD = 'root';
$contrasenaBD = '';

try {
    $conn = new PDO("mysql:host=$hostBD;dbname=$nombreBD;charset=utf8mb4", $usuarioBD, $contrasenaBD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
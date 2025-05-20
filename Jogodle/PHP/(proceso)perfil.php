<?php
// Proceso de iniciar sesion y cargar perfil
session_start();
require __DIR__ . '/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: sesion.php');
    exit();
}

$id = $_SESSION['usuario_id'];
$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$nuevaContra = $_POST['nueva_contra'];
$foto = $_FILES['foto'];

$foto_nombre = null;

try {
    // Procesar imagen si se subió
    if (!empty($foto['tmp_name'])) {
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $foto_nombre = "perfil_$id." . $ext;
        move_uploaded_file($foto['tmp_name'], __DIR__ . "/../imagenes/$foto_nombre");
    }

    // Actualizar datos
    $query = "UPDATE usuario SET nombre = :nombre, email = :email";
    $params = [
        ':nombre' => $nombre,
        ':email' => $email,
        ':id' => $id
    ];

    if (!empty($nuevaContra)) {
        $query .= ", contra = :contra";
        $params[':contra'] = password_hash($nuevaContra, PASSWORD_DEFAULT);
    }

    if ($foto_nombre) {
        $query .= ", foto = :foto";
        $params[':foto'] = $foto_nombre;
    }

    $query .= " WHERE id = :id";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    header("Location: perfil.php");
    exit();
} catch (PDOException $e) {
    die("Error actualizando perfil: " . $e->getMessage());
}

<?php
//Se inicia sesion para acceder al perfil
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: sesion.php');
    exit();
}

require __DIR__ . '/conexion.php';

try {
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$usuario) {
        throw new Exception("El usuario no se encontró");
    }
} catch (PDOException $e) {
    die("Error al cargar perfil: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>JOGODLE - Perfil</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <style>
        body {
            background-color: #333;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            color: #333;
            text-align: center;
            margin: 0;
        }
        .perfil-container {
            background: #b2faff;
            padding: 30px;
            margin: 40px auto;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 0 10px #000;
        }
        img.foto-perfil {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #333;
        }
        input, button {
            padding: 8px;
            margin: 5px 0;
            width: 90%;
            border-radius: 8px;
            border: none;
        }
        .btn-logout {
            background: #ff5252;
            color: white;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!--Contenido del perfil -->
    <h1 class="tituloPixel">JOGODLE</h1>

    <div class="perfil-container">
        <h1>Mi Perfil</h1>
        <img src="../Imagenes/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" class="foto-perfil">
        
        <form action="(proceso)perfil.php" method="POST" enctype="multipart/form-data">
            <h3>Actualizar Datos:</h3>
            <input type="text" name="nombre" placeholder="Nuevo nombre (opcional)" value="<?= htmlspecialchars($usuario['nombre']) ?>" required><br>
            <input type="email" name="email" placeholder="Nuevo email (opcional)" value="<?= htmlspecialchars($usuario['email']) ?>" required><br>
            <input type="password" name="nueva_contra" placeholder="Nueva contraseña (opcional)"><br>
            <h3>Actualizar foto de perfil</h3>
            <input type="file" name="foto"><br>
            <button type="submit">Actualizar perfil</button>
        </form>

        <h3>Datos recopilados de minijuegos</h3>
        <p>Intentos: <?= htmlspecialchars($usuario['intentos']) ?></p>
        <p>Ganadas: <?= htmlspecialchars($usuario['ganadas']) ?></p>

        <a href="inicio.php" class="inicio">
        <button>
            <p>Regresar al Inicio</p>
        </button>
        </a>

        <a href="(proceso)logout.php" class="btn-logout">Cerrar sesión</a>
    </div>

</body>
</html>

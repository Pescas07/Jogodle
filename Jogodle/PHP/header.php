<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Adivina el Videojuego'; ?></title>
    <link rel="stylesheet" href="../Jogodle/CSS/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../Jogodle/JS/logica.js" defer></script>
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <h1><a href="../index.php"><i class="fas fa-gamepad"></i> Adivina el Videojuego</a></h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="../minijuegos/adivinar_imagen/">Por Imagen</a></li>
                    <li><a href="../minijuegos/adivinar_palabras/">Por Palabras</a></li>
                    <li><a href="../minijuegos/adivinar_estadisticas/">Por Stats</a></li>
                    <?php if(isset($_SESSION['usuario'])): ?>
                        <li><a href="../perfil.php">Perfil</a></li>
                        <li><a href="../logout.php">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="../login.php">Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="content-wrapper">
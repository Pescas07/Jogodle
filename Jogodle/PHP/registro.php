<!--Contenido del formulario del registro -->
<!DOCTYPE html>
<html>
<head>
    <title>JOGODLE - Registro</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
    <h1 class="tituloPixel">JOGODLE</h1>

    <div class="formSesion">
        <h1 class="titulo-pixelado">Regístrate</h1>
        <?php if (isset($_SESSION['error_registro'])): ?>
            <p class="error"><?= $_SESSION['error_registro'] ?></p>
            <?php unset($_SESSION['error_registro']); ?>
        <?php endif; ?>
        
        <form action="(proceso)registro.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <br>
            <input type="email" name="email" placeholder="Correo" required>
            <br>
            <input type="password" name="contra" placeholder="Contraseña" required>
            <br>
            <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
            <br>
            <button type="submit" name="registro">Crear cuenta</button>
        </form>

    <div class="regreso">
        <p>¿Ya tienes cuenta? <br> Inicia sesión <a href="sesion.php">aquí</a></p>

        <a href="inicio.php" class="inicio">
        <button>
            <p>Regresar al Inicio</p>
        </button>
    </a>
    </div>

    </div>
</body>
</html>
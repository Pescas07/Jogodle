<?php
//Proceso de la sesion del cliente
session_start();

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['correo']);
    $contra = trim($_POST['contra']);

    if (empty($email) || empty($contra)) {
        echo "Se tiene que introducir datos en todos los campos";
        exit();
    }

    //Se realiza la sentencia

    $select = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $conn->prepare($select);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contra, $usuario['contra'])) {

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        
        header("Location: perfil.php");
        exit();
    } else {
        echo "Datos incorrectos. Inténtelo de nuevo.";
    }
}
?>
<!--Contenido del formulario del inicio de sesion -->
<!DOCTYPE html>
<html>
<head>
    <title>JOGODLE - Iniciar sesión</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
    <h1 class="tituloPixel">JOGODLE</h1>

    <div class="formSesion">
        <h1 class="titulo-pixelado">Iniciar sesión</h1>
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="post">
            <input type="email" name="correo" placeholder="Correo" required><br>
            <input type="password" name="contra" placeholder="Contraseña" required><br>
            <button type="submit" name="login">Iniciar sesión</button>
        </form>

        <p>Si no te has registrado, <br> regístrate <a href="registro.php">aquí</a></p>

        <a href="inicio.php" class="inicio">
        <button>
            <p>Regresar al Inicio</p>
        </button>
    </a>
    </div>

</body>
</html>

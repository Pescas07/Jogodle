<?php
include 'conexion.php';
// Se guardan los datos en variables
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contra = trim($_POST['contra']);
    $confirmar = $_POST['confirmar'];

    //Se comprueba que los datos estan introducidos
    if (empty($nombre) || empty($email) || empty($contra) || empty($confirmar)) {
        echo "Hace falta rellenar todos los datos";
        exit();
    }

    if ($contra !== $confirmar) {
        echo "Las contraseñas no coinciden";
        exit();
    }

    if (substr(strtolower($email), -10) !== '@gmail.com') {
        echo "Tiene que ser un correo válido";
        exit();
    }


    //Se oculta la contraseña
    $contraOculta = password_hash($contra, PASSWORD_BCRYPT);

    //Se insertan los datos
    $insert = "INSERT INTO usuario (nombre, email, contra) 
               VALUES (:nombre, :email, :contra)";
    $stmt = $conn->prepare($insert);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contra', $contraOculta);

    //Se ejecuta, si sale bien se accede a la página, si no salta un mensaje
    if($stmt->execute()) {
        header("Location: perfil.php");
        exit();
    } else {
        echo "Error. No se pudo registrar el usuario correctamente.";
    }
}
?>



<?php
session_start();
require __DIR__ . '/conexion.php'; 

// Verificar si el usuario está logueado
$usuarioLogueado = isset($_SESSION['usuario_id']);
$nombreUsuario = $_SESSION['nombre'] ?? '';
$fotoUsuario = $_SESSION['foto'] ?? '';

// 
if (!$usuarioLogueado && isset($_COOKIE['usuario_recordado'])) {
    $usuarioLogueado = true;
    $nombreUsuario = $_COOKIE['nombre_usuario'] ?? 'Usuario';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOGODLE</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
    <h1 class="tituloPixel">JOGODLE</h1>

    <div class="botonArDer">
      <?php if ($usuarioLogueado): ?>
        <a href="perfil.php">
            <button class="inicioSesion">
                <span><?php echo htmlspecialchars($nombreUsuario); ?></span>
            </button>
        </a>
    <?php else: ?>
      <a href="sesion.php">
      <button class="inicioSesion">
          <span class="icono">👤</span> Inicia Sesion
      </button>
      </a>
      <?php endif; ?>
    </div>

  <section class="general">
    <section class="minijogos">
        <div class="columna">
            <h2>📅 Casual</h2>
            <a href="personaje.php">
            <div class="jogo">👾 <b>Personaje</b><br><span>-Adivinar el juego por el personaje-</span></div> 
          </a>
            <a href="palabras.php">
            <div class="jogo">🎮 <b>Palabras</b><br><span>-Adivinar por las palabras clave-</span></div> 
          </a>
            <a href="stats.php"> 
            <div class="jogo">🔑 <b>Estadísticas</b><br><span>-Adivinar por estadísticas-</span></div> 
          </a>
        </div>

        <div class="columna">
            <h2>♾️ Competitivo</h2>
            <a href="">
            <div class="jogo facil">👾 <b>Personaje (En proceso)</b><br><span>-Recompensa: 5 Puntos-</span></div> 
          </a>
            <a href="">
            <div class="jogo normal">🎮 <b>Palabras (En proceso)</b><br><span>-Recompensa: 10 Puntos-</span></div> 
          </a>
            <a href=""> 
            <div class="jogo dificil">🔑 <b>Estadísticas (En proceso)</b><br><span>-Recompensa: 20 Puntos-</span></div> 
          </a>
        </div>

    </section>

    <div class="menuOpciones">
      <a href="glosario.php">
        <button class="botonOpcion">📖 Glosario</button>
      </a>
    </div>

  </div>
  </section>

  <script scr="footer.php"></script>
</div>
</body>
</html>

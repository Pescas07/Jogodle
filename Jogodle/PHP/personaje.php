<?php
require __DIR__ . '/../PHP/conexion.php';
session_start();

// Reiniciar juego
if (isset($_POST['reiniciar'])) {
    unset($_SESSION['juego_actual'], $_SESSION['intentos'], $_SESSION['acertado'], $_SESSION['nombre_acertado']);
    header("Location: personaje.php");
    exit;
}

// Seleccionar nuevo personaje si no hay uno en sesión
if (!isset($_SESSION['juego_actual'])) {
    // Obtener personaje aleatorio con su juego asociado
    $sql = "SELECT p.id, p.jogo_id, p.nombre AS nombre_personaje, p.imagen_ruta, 
                   j.nombre AS nombre_juego
            FROM personaje p
            JOIN jogo j ON p.jogo_id = j.id
            WHERE p.imagen_ruta IS NOT NULL
            ORDER BY RAND() LIMIT 1";
    
    $stmt = $conn->query($sql);
    $personaje = $stmt->fetch(PDO::FETCH_ASSOC);

    // Guardar en sesión
    $_SESSION['juego_actual'] = [
        'id' => $personaje['id'],
        'jogo_id' => $personaje['jogo_id'],
        'nombre_personaje' => $personaje['nombre_personaje'],
        'imagen_ruta' => $personaje['imagen_ruta'],
        'nombre_juego' => $personaje['nombre_juego']
    ];
    $_SESSION['intentos'] = 0;
}

// Usar datos desde la sesión
$personaje = $_SESSION['juego_actual'];
$intentos = $_SESSION['intentos'];

// Procesar respuesta del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta'])) {
    $respuesta_usuario = strtolower(trim($_POST['respuesta']));
    $respuesta_correcta = strtolower(trim($personaje['nombre_juego']));

    if ($respuesta_usuario === $respuesta_correcta) {
        $_SESSION['acertado'] = true;
        $_SESSION['nombre_acertado'] = $personaje['nombre_juego'];
        unset($_SESSION['mensaje']);

        if (isset($_SESSION['usuario_id'])) {
            $stmt = $conn->prepare("UPDATE usuario SET intentos = intentos + 1, ganadas = ganadas + 1 WHERE id = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
        }

        header("Location: personaje.php");
        exit;
    } else {
            // Contar intentos
        $_SESSION['intentos']++;

        if ($_SESSION['intentos'] >= 3) {
            $_SESSION['mensaje'] = "❌ ¡Fallaste! El juego era: " . $personaje['nombre_juego'];

            if (isset($_SESSION['usuario_id'])) {
                $stmt = $conn->prepare("UPDATE usuario SET intentos = intentos + 1 WHERE id = ?");
                $stmt->execute([$_SESSION['usuario_id']]);
            }

            unset($_SESSION['juego_actual'], $_SESSION['intentos']);
            header("Location: personaje.php");
            exit;
        } else {
            $_SESSION['mensaje'] = "Incorrecto. Vuelvelo a intentar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOGODLE - Personaje</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .contenedorJogo {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .contenedorImagen {
            margin: 20px auto;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        
        .imagenPersonaje {
            max-width: 100%;
            height: auto;
            max-height: 300px;
            border-radius: 5px;
            transition: filter 0.5s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .intentos {
            color: #e74c3c;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .mensaje {
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .mensaje-correcto {
            background-color: #d4edda;
            color: #155724;
        }
        
        .mensaje-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .search-container-jogodle { 
            position: relative;
            margin: 20px auto;
            max-width: 400px;
        }
        
        #nombreJuegoInput {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
                .sugerencias-lista {
            background: white;
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            position: absolute;
            width: 100%;
            z-index: 10;
        }

        .sugerencia-item {
            padding: 10px;
            cursor: pointer;
        }

        .sugerencia-item:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>
        <!--Pagina con el minijogo, el formulario y demas -->
    <h1 class="tituloPixel">JOGODLE PERSONAJE</h1>

    <div class="contenedorJogo">
        <h1>Adivina el Jogo por el Personaje</h1>
        
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje <?php echo strpos($_SESSION['mensaje'], '❌') !== false ? 'mensaje-error' : ''; ?>">
                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['acertado']) && $_SESSION['acertado'] === true): ?>
            <div class="mensaje mensaje-correcto">
                <h2>🎉 ¡Correcto!</h2>
                <p>El juego era: <?php echo htmlspecialchars($_SESSION['nombre_acertado']); ?></p>
                <form method="post">
                    <button type="submit" name="reiniciar" class="btn">Jugar de Nuevo</button>
                </form>
            </div>
        <?php unset($_SESSION['mensaje']);
        else: ?>
            <div class="contenedorImagen">
                <img src="../ImagenesJogo/<?php echo htmlspecialchars($personaje['imagen_ruta']); ?>" 
                     class="imagenPersonaje">
            </div>
            
            <form method="POST" id="formAdivinarJuego">
                <div class="search-container-jogodle">
                    <input type="text" 
                           name="respuesta" 
                           id="nombreJuegoInput" 
                           placeholder="¿Qué juego es?" 
                           required
                           autocomplete="off">
                    <div id="sugerenciasJuegos" class="sugerencias-lista"></div>
                </div>
                <input type="hidden" name="jogo_id" value="<?php echo htmlspecialchars($personaje['id']); ?>">
                <button type="submit" class="btn">Adivinar</button>
            </form>
            
            <p class="intentos">Intentos: <?php echo $intentos; ?>/3</p>
            
            <div class="contenedorBotones">
                <form method="post">
                    <button type="submit" name="reiniciar" class="btn">Reiniciar</button>
                </form>
                <a href="inicio.php" class="btn">Menú Principal</a>
            </div>

        <?php endif; ?>
    </div>
               <!--Contenido para la barra de sugerencia -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBusqueda = document.getElementById('nombreJuegoInput');
        const contenedorSugerencias = document.getElementById('sugerenciasJuegos');
        const formAdivinarJuego = document.getElementById('formAdivinarJuego');

        if (inputBusqueda && contenedorSugerencias && formAdivinarJuego) {
            inputBusqueda.addEventListener('input', async function() {
                const textoEntrada = this.value.trim();
                contenedorSugerencias.innerHTML = '';

                if (textoEntrada.length < 1) {
                    contenedorSugerencias.style.display = 'none';
                    return;
                }

                try {
                    const respuesta = await fetch(`busquedaJogos.php?term=${encodeURIComponent(textoEntrada)}`);
                    const titulosDesdePHP = await respuesta.json();

                    if (titulosDesdePHP.length > 0) {
                        titulosDesdePHP.forEach(titulo => {
                            const itemSugerencia = document.createElement('div');
                            itemSugerencia.classList.add('sugerencia-item');
                            itemSugerencia.textContent = titulo;

                            itemSugerencia.addEventListener('click', function() {
                                inputBusqueda.value = this.textContent;
                                contenedorSugerencias.style.display = 'none';
                            });
                            contenedorSugerencias.appendChild(itemSugerencia);
                        });
                        contenedorSugerencias.style.display = 'block';
                    } else {
                        contenedorSugerencias.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error al obtener sugerencias:', error);
                    contenedorSugerencias.style.display = 'none';
                }
            });

            // Cerrar sugerencias al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!inputBusqueda.contains(event.target) && !contenedorSugerencias.contains(event.target)) {
                    contenedorSugerencias.style.display = 'none';
                }
            });
        }
    });
    </script>
</body>
</html>
<?php
require __DIR__ . '/../PHP/conexion.php';
session_start();

// Si se presiona "Jugar de Nuevo"
if (isset($_POST['reiniciar'])) {
    unset($_SESSION['juego_actual'], $_SESSION['intentos'], $_SESSION['acertado'], $_SESSION['nombre_acertado'], $_SESSION['mensaje']);
    header("Location: palabras.php");
    exit;
}

// Si no hay juego actual en sesión, seleccionar uno nuevo
if (!isset($_SESSION['juego_actual'])) {
    // Obtener juego aleatorio
    $sql_juego = "SELECT id, nombre FROM jogo ORDER BY RAND() LIMIT 1";
    $result_juego = $conn->query($sql_juego);
    $juego = $result_juego->fetch();

    // Obtener 3 palabras clave
    $sql_palabras = "SELECT palabra FROM palabras WHERE jogo_id = ? ORDER BY RAND() LIMIT 3";
    $stmt = $conn->prepare($sql_palabras);
    $stmt->bindValue(1, $juego['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result_palabras = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Guardar en sesión
    $_SESSION['juego_actual'] = [
        'id' => $juego['id'],
        'nombre' => $juego['nombre'],
        'palabras' => $result_palabras,
    ];
    $_SESSION['intentos'] = 0;
}

// Usar datos desde la sesión
$juego = $_SESSION['juego_actual'];
$palabras = $juego['palabras'];
$intentos = $_SESSION['intentos'] ?? 0;

// Si el usuario ha enviado una respuesta:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta'])) {
    $respuesta_usuario = strtolower(trim($_POST['respuesta']));
    $id_juego_actual = (int)$juego['id'];

    // Obtener nombre correcto del juego
    $stmt = $conn->prepare("SELECT nombre FROM jogo WHERE id = ?");
    $stmt->execute([$id_juego_actual]);
    $juego_correcto = $stmt->fetch();

    if ($juego_correcto) {
        $nombre_real = strtolower(trim($juego_correcto['nombre']));

        if ($respuesta_usuario === $nombre_real) {
            $_SESSION['acertado'] = true;
            $_SESSION['nombre_acertado'] = $juego_correcto['nombre'];
        } else {
            $_SESSION['intentos']++;

            if ($_SESSION['intentos'] >= 3) {
                $_SESSION['mensaje'] = "❌ Te quedaste sin intentos. El juego era: " . $juego_correcto['nombre'];
                unset($_SESSION['juego_actual'], $_SESSION['intentos']);
                header("Location: palabras.php");
                exit;
            } else {
                $_SESSION['mensaje'] = "Incorrecto. Vuelvelo a intentar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>JOGODLE - Palabras</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <style>
        /* Estilos del propio jogo */
        .contenedorJogo {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .contenedorPalabra {
            margin: 20px auto;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 8px;
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
    <h1 class="tituloPixel">JOGODLE PALABRAS</h1>

    <div class="contenedorJogo">
        <h1>Adivina el Jogo por las Palabras</h1>

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
        <?php else: ?>

            <div class="contenedorPalabra">
                <?php foreach ($palabras as $palabra): ?>
                    <div class="palabra-clave"><?php echo htmlspecialchars($palabra); ?></div>
                <?php endforeach; ?>
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
                <button type="submit" class="btn">Adivinar</button>
            </form>

            <p class="intentos">Intentos: <?php echo $_SESSION['intentos']; ?>/3</p>

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
    document.addEventListener('DOMContentLoaded', function () {
        const inputBusqueda = document.getElementById('nombreJuegoInput');
        const contenedorSugerencias = document.getElementById('sugerenciasJuegos');

        inputBusqueda.addEventListener('input', async function () {
            const textoEntrada = this.value.trim();
            contenedorSugerencias.innerHTML = '';

            if (textoEntrada.length < 1) {
                contenedorSugerencias.style.display = 'none';
                return;
            }

            try {
                const respuesta = await fetch(`busquedaJogos.php?term=${encodeURIComponent(textoEntrada)}`);
                const resultados = await respuesta.json();

                if (resultados.length > 0) {
                    resultados.forEach(titulo => {
                        const item = document.createElement('div');
                        item.classList.add('sugerencia-item');
                        item.textContent = titulo;
                        item.onclick = () => {
                            inputBusqueda.value = titulo;
                            contenedorSugerencias.style.display = 'none';
                        };
                        contenedorSugerencias.appendChild(item);
                    });
                    contenedorSugerencias.style.display = 'block';
                } else {
                    contenedorSugerencias.style.display = 'none';
                }
            } catch (err) {
                console.error('Error:', err);
                contenedorSugerencias.style.display = 'none';
            }
        });

        document.addEventListener('click', function (event) {
            if (!inputBusqueda.contains(event.target) && !contenedorSugerencias.contains(event.target)) {
                contenedorSugerencias.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>

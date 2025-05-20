<?php
require __DIR__ . '/../PHP/conexion.php';
session_start();

// Reiniciar jogo
if (isset($_POST['reiniciar'])) {
    unset($_SESSION['stats_jogo_objetivo'], $_SESSION['stats_intentos'], $_SESSION['stats_resultados'], $_SESSION['stats_acertado'], $_SESSION['stats_fin']);
    header("Location: stats.php");
    exit;
}

// Seleccionar un jogo aleatorio si no hay uno en sesión
if (!isset($_SESSION['stats_jogo_objetivo'])) {
    $stmt = $conn->query("SELECT id, nombre FROM jogo ORDER BY RAND() LIMIT 1");
    $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['stats_jogo_objetivo'] = [
        'id' => $jogo['id'],
        'nombre_jogo' => $jogo['nombre']
    ];
    $_SESSION['stats_intentos'] = 0;
    $_SESSION['stats_resultados'] = [];
}

// Función para obtener stats por tipo
function obtenerStats($conn, $jogo_id) {
    $stmt = $conn->prepare("SELECT tipo, valor FROM statsJogo WHERE jogo_id = ?");
    $stmt->execute([$jogo_id]);

    $stats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats[$row['tipo']][] = strtolower(trim($row['valor']));
    }
    return $stats;
}

// Proceso del intento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta'])) {
    $respuesta_usuario = strtolower(trim($_POST['respuesta']));
    $stmt = $conn->prepare("SELECT id, nombre FROM jogo WHERE LOWER(nombre) = ?");
    $stmt->execute([$respuesta_usuario]);
    $jogo_intento = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Contar intentos
    if ($jogo_intento) {
        $_SESSION['stats_intentos']++;

        $id_objetivo = $_SESSION['stats_jogo_objetivo']['id'];
        $id_intento = $jogo_intento['id'];

        $stats_objetivo = obtenerStats($conn, $id_objetivo);
        $stats_intento = obtenerStats($conn, $id_intento);

        $comparacion = [];
        $tipos = ['plataforma', 'año', 'perspectiva', 'modoJogo'];

        foreach ($tipos as $tipo) {
            $valores_objetivo = $stats_objetivo[$tipo] ?? [];
            $valores_intento = $stats_intento[$tipo] ?? [];

            if (empty($valores_intento)) {
                $comparacion[$tipo] = ['valor' => 'N/A', 'color' => 'rojo'];
                continue;
            }

            // Comparacion de valores
            $match = array_intersect($valores_intento, $valores_objetivo);
            if (!empty($match)) {
                if (count($match) === count($valores_objetivo) && count($match) === count($valores_intento)) {
                    $comparacion[$tipo] = ['valor' => implode(', ', $valores_intento), 'color' => 'verde'];
                } 
            } else {
                $comparacion[$tipo] = ['valor' => implode(', ', $valores_intento), 'color' => 'rojo'];
            }
        }

        $_SESSION['stats_resultados'][] = [
            'nombre' => $jogo_intento['nombre'],
            'comparacion' => $comparacion
        ];

        if ($jogo_intento['id'] === $id_objetivo) {
            $_SESSION['stats_acertado'] = true;
        } elseif ($_SESSION['stats_intentos'] >= 7) {
            $_SESSION['stats_fin'] = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>JOGODLE - Estadisticas</title>
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

        #nombreJogoInput {
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

        .tablaStats {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .tablaStats th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
            padding: 15px;
            text-align: center;
            position: sticky;
            top: 0;
        }

        .tablaStats td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s;
        }

        .tablaStats td.verde {
            background-color:rgb(101, 234, 132);
            color: #155724;
        }

        .tablaStats td.rojo {
            background-color:rgb(255, 82, 96);
            color: #721c24;
        }

    </style>
</head>
<body>
       <!--Pagina con el minijogo, el formulario y demas -->
    <h1 class="tituloPixel">JOGODLE ESTADISTICAS</h1>

    <div class="contenedorJogo">
            <h1>Adivina el Jogo por sus Estadísticas</h1>

        <?php if (!empty($_SESSION['stats_fin'])): ?>
            <div class="mensaje mensaje-error">
            <h2>❌ Se acabaron los intentos</h2>
            <p>El jogo correcto era: <?php echo htmlspecialchars($_SESSION['stats_jogo_objetivo']['nombre_jogo']); ?></p>
        <form method="post">
            <button type="submit" name="reiniciar" class="btn">Jugar de Nuevo</button>
        </form>
    </div>

        <?php elseif (!empty($_SESSION['stats_acertado'])): ?>
            <div class="mensaje mensaje-correcto">
                <h2>🎉 ¡Correcto!</h2>
                <p>El jogo era: <?php echo htmlspecialchars($_SESSION['stats_jogo_objetivo']['nombre_jogo']); ?></p>
                <form method="post">
                    <button type="submit" name="reiniciar" class="btn">Jugar de Nuevo</button>
                </form>
            </div>
            
        <?php else: ?>
            <form method="POST" id="formAdivinarJogo">
                <div class="search-container-jogodle">
                    <input type="text" id="nombreJogoInput" name="respuesta" placeholder="¿Qué juego es?" required autocomplete="off">
                <div id="sugerenciasJogos" class="sugerencias-lista"></div>
                </div>
                <button type="submit" class="btn">Adivinar</button>
            </form>

            <p class="intentos">Intentos: <?= $_SESSION['stats_intentos'] ?>/7</p>

            <div class="contenedorBotones">
                <form method="post">
                    <button type="submit" name="reiniciar" class="btn">Reiniciar</button>
                </form>
                <a href="inicio.php" class="btn">Menú Principal</a>
            </div>

            <?php if (!empty($_SESSION['stats_resultados'])): ?>
                <table class="tablaStats">
                    <tr>
                        <th>Jogo</th>
                        <th>Plataforma</th>
                        <th>Año</th>
                        <th>Perspectiva</th>
                        <th>Modo de jogo</th>
                    </tr>
                    <?php foreach ($_SESSION['stats_resultados'] as $resultado): ?>
                        <tr>
                            <td><?= htmlspecialchars($resultado['nombre']) ?></td>
                            <?php foreach (['plataforma', 'año', 'perspectiva', 'modoJogo'] as $tipo): ?>
                                <td class="<?= $resultado['comparacion'][$tipo]['color'] ?>">
                                    <?= htmlspecialchars($resultado['comparacion'][$tipo]['valor']) ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
                   <!--Contenido para la barra de sugerencia -->
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBusqueda = document.getElementById('nombreJogoInput');
        const contenedorSugerencias = document.getElementById('sugerenciasJogos');
        const formAdivinarJogo = document.getElementById('formAdivinarJogo');

        if (inputBusqueda && contenedorSugerencias && formAdivinarJogo) {
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

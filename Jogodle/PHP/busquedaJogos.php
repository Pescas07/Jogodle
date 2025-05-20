<?php
// Logica para y conexion con la base de datos para que la barra de sugerencias funcione
require __DIR__ . '/../PHP/conexion.php';

header('Content-Type: application/json');
$sugerencias = [];

$terminoBusqueda = isset($_GET['term']) ? trim($_GET['term']) : '';

if (empty($conn)) {
    echo json_encode(['error' => 'La conexión a la base de datos no está disponible. Revisa conexion.php.']);
    exit;
}

if (!empty($terminoBusqueda)) {
    try {
        $sql = "SELECT nombre FROM jogo WHERE nombre LIKE :termino ORDER BY nombre ASC LIMIT 10";
        $stmt = $conn->prepare($sql);
        
        $parametroLike = "%" . $terminoBusqueda . "%";
        $stmt->bindParam(':termino', $parametroLike, PDO::PARAM_STR);
        
        $stmt->execute();
        
        $sugerencias = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Ocurrió un error']);
        exit;
    }
}

echo json_encode($sugerencias);
?>
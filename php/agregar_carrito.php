<?php
header('Content-Type: application/json');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Intentar leer JSON primero, luego fallback a POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        $data = $_POST;
    }
    
    $id = trim($data['id'] ?? '');
    $nombre = trim($data['nombre'] ?? '');
    $precio = floatval($data['precio'] ?? 0);
    $cantidad = intval($data['cantidad'] ?? 1);

    // Validar datos básicos
    if ($id === '' || $nombre === '' || $precio <= 0 || $cantidad <= 0) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    // Inicializar carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Revisar si el producto ya está en el carrito
    $encontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] === $id) {
            $item['cantidad'] += $cantidad;
            $encontrado = true;
            break;
        }
    }
    unset($item);

    // Si no se encontró, agregar nuevo producto
    if (!$encontrado) {
        $_SESSION['carrito'][] = [
            'id' => $id,
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
    }

    echo json_encode(['success' => true, 'message' => "Producto agregado: $nombre"]);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>

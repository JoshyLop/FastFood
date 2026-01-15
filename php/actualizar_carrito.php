<?php
header('Content-Type: application/json');

// Obtener carrito de sesión
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    
    $id = $data['id'] ?? null;
    $cantidad = intval($data['cantidad'] ?? 1);
    
    if ($id === null || $cantidad < 1) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }
    
    // Actualizar cantidad
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] = $cantidad;
        echo json_encode(['success' => true, 'message' => 'Cantidad actualizada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

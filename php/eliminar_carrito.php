<?php
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    
    if ($id === null) {
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
    }
    
    if (isset($_SESSION['carrito'][$id])) {
        unset($_SESSION['carrito'][$id]);
        echo json_encode(['success' => true, 'message' => 'Producto eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

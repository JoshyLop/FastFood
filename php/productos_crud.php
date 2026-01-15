<?php
header('Content-Type: application/json');
session_start();

// Verificar que sea admin
if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;
$pdo = new PDO('sqlite:' . __DIR__ . '/../db/fastfood.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'listar') {
        $stmt = $pdo->query("SELECT * FROM productos ORDER BY categoria, nombre");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $productos]);
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'crear') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, categoria, precio, descripcion, disponible) 
                             VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $data['nombre'],
            $data['categoria'],
            floatval($data['precio']),
            $data['descripcion'],
            1
        ]);
        
        echo json_encode(['success' => $result, 'message' => $result ? 'Producto creado' : 'Error al crear']);
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'actualizar') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, categoria = ?, precio = ?, descripcion = ?, disponible = ? 
                             WHERE id = ?");
        $result = $stmt->execute([
            $data['nombre'],
            $data['categoria'],
            floatval($data['precio']),
            $data['descripcion'],
            intval($data['disponible'] ?? 1),
            intval($data['id'])
        ]);
        
        echo json_encode(['success' => $result, 'message' => $result ? 'Producto actualizado' : 'Error al actualizar']);
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'eliminar') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $result = $stmt->execute([intval($data['id'])]);
        
        echo json_encode(['success' => $result, 'message' => $result ? 'Producto eliminado' : 'Error al eliminar']);
    }
    
    else {
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

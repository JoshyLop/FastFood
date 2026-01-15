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
        $stmt = $pdo->query("SELECT * FROM repartidores ORDER BY estado DESC, nombre");
        $repartidores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $repartidores]);
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'crear') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("INSERT INTO repartidores (nombre, telefono, estado, latitud, longitud) 
                             VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $data['nombre'],
            $data['telefono'],
            'disponible',
            floatval($data['latitud'] ?? 20.9671),
            floatval($data['longitud'] ?? -101.6864)
        ]);
        
        echo json_encode(['success' => $result, 'message' => $result ? 'Repartidor creado' : 'Error al crear']);
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'actualizar_estado') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("UPDATE repartidores SET estado = ? WHERE id = ?");
        $result = $stmt->execute([
            $data['estado'],
            intval($data['id'])
        ]);
        
        echo json_encode(['success' => $result, 'message' => $result ? 'Estado actualizado' : 'Error al actualizar']);
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'actualizar_ubicacion') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("UPDATE repartidores SET latitud = ?, longitud = ? WHERE id = ?");
        $result = $stmt->execute([
            floatval($data['latitud']),
            floatval($data['longitud']),
            intval($data['id'])
        ]);
        
        echo json_encode(['success' => $result, 'message' => $result ? 'Ubicación actualizada' : 'Error al actualizar']);
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'eliminar') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("DELETE FROM repartidores WHERE id = ?");
        $result = $stmt->execute([intval($data['id'])]);
        
        echo json_encode(['success' => $result, 'message' => $result ? 'Repartidor eliminado' : 'Error al eliminar']);
    }
    
    else {
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

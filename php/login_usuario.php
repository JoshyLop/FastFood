<?php
header('Content-Type: application/json');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    $email = trim($data['email'] ?? $data['correo'] ?? '');
    $password = $data['password'] ?? $data['contrasena'] ?? '';

    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../db/fastfood.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT id, nombre, email, rol FROM usuarios WHERE email = ? AND contrasena = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $password]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            echo json_encode(['success' => true, 'message' => "¡Bienvenido {$usuario['nombre']}!", 'redirect' => 'inicio.php']);
        } else {
            $response = ['success' => false, 'message' => 'Email o contraseña incorrectos'];
            echo json_encode($response);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

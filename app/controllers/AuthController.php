<?php
/**
 * CONTROLADOR DE AUTENTICACIÓN
 */

require_once __DIR__ . '/Controller.php';

class AuthController extends Controller {

    public function __construct() {
        $this->loadModel('Usuario');
    }

    /**
     * Registra un nuevo usuario
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validaciones
        $errors = $this->validate(
            ['nombre' => $nombre, 'email' => $email, 'password' => $password],
            ['nombre' => 'required', 'email' => 'email', 'password' => 'required']
        );

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 400);
        }

        if ($password !== $confirmPassword) {
            $this->json(['success' => false, 'message' => 'Las contraseñas no coinciden'], 400);
        }

        if (strlen($password) < 6) {
            $this->json(['success' => false, 'message' => 'La contraseña debe tener mínimo 6 caracteres'], 400);
        }

        $result = $this->model->register($nombre, $email, $password);
        
        if ($result['success']) {
            $_SESSION['usuario_id'] = $result['id'];
            $_SESSION['rol'] = 'cliente';
            
            $this->json([
                'success' => true,
                'message' => 'Registro exitoso',
                'redirect' => '/index.html'
            ]);
        }

        $this->json(['success' => false, 'message' => $result['message']], 400);
    }

    /**
     * Inicia sesión
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Email y contraseña requeridos'], 400);
        }

        $usuario = $this->model->authenticate($email, $password);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            $redirect = $usuario['rol'] === 'admin' 
                ? '/admin/admin_dashboard.php' 
                : '/index.html';

            $this->json([
                'success' => true,
                'message' => 'Bienvenido ' . $usuario['nombre'],
                'redirect' => $redirect
            ]);
        }

        $this->json(['success' => false, 'message' => 'Email o contraseña incorrectos'], 401);
    }

    /**
     * Cierra sesión
     */
    public function logout() {
        session_destroy();
        $this->redirect('/index.html');
    }

    /**
     * Obtiene datos del usuario actual
     */
    public function perfil() {
        $this->requireLogin();
        
        $usuarioId = $this->getUsuarioActual();
        $usuario = $this->model->getById($usuarioId);

        $this->json([
            'success' => true,
            'usuario' => [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo'],
                'rol' => $usuario['rol']
            ]
        ]);
    }
}
?>

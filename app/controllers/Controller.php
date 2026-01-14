<?php
/**
 * CLASE BASE PARA TODOS LOS CONTROLADORES
 */

class Controller {
    protected $model;
    protected $view;

    /**
     * Carga un modelo
     */
    protected function loadModel($modelName) {
        $modelPath = __DIR__ . "/../models/{$modelName}.php";
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            $this->model = new $modelName();
            return true;
        }
        
        return false;
    }

    /**
     * Carga una vista
     */
    protected function loadView($viewName, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . "/../views/{$viewName}.php";
        
        if (file_exists($viewPath)) {
            include $viewPath;
            return true;
        }
        
        return false;
    }

    /**
     * Redirige a una URL
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    /**
     * Retorna JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Verifica sesión del usuario
     */
    protected function requireLogin() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('/usuario/login.html');
        }
    }

    /**
     * Verifica que sea administrador
     */
    protected function requireAdmin() {
        $this->requireLogin();
        
        if ($_SESSION['rol'] !== 'admin') {
            $this->json(['success' => false, 'message' => 'Acceso denegado'], 403);
        }
    }

    /**
     * Obtiene usuario de la sesión
     */
    protected function getUsuarioActual() {
        return $_SESSION['usuario_id'] ?? null;
    }

    /**
     * Valida datos POST
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            if ($rule === 'required' && empty($data[$field])) {
                $errors[$field] = "El campo {$field} es requerido";
            }
            
            if ($rule === 'email' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "El campo {$field} debe ser un email válido";
            }
        }
        
        return $errors;
    }
}
?>

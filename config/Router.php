<?php
/**
 * ROUTER - ENRUTADOR DE LA APLICACIÓN
 */

class Router {
    private $routes = [];

    /**
     * Define una ruta GET
     */
    public function get($path, $controller, $action) {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Define una ruta POST
     */
    public function post($path, $controller, $action) {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Ejecuta la ruta actual
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover la raíz de la aplicación
        $basePath = '/FastFood';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        if (empty($path)) {
            $path = '/';
        }

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Ruta no encontrada']);
            return;
        }

        $route = $this->routes[$method][$path];
        $controller = $route['controller'];
        $action = $route['action'];

        $controllerPath = __DIR__ . "/../controllers/{$controller}.php";
        
        if (!file_exists($controllerPath)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Controlador no encontrado']);
            return;
        }

        require_once $controllerPath;
        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $action)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Acción no encontrada']);
            return;
        }

        $controllerInstance->$action();
    }
}
?>

<?php
/**
 * CONTROLADOR DE PEDIDOS
 */

require_once __DIR__ . '/Controller.php';

class PedidoController extends Controller {

    public function __construct() {
        $this->loadModel('Pedido');
    }

    /**
     * Obtiene pedidos del usuario
     */
    public function misPedidos() {
        $this->requireLogin();
        
        $usuarioId = $this->getUsuarioActual();
        $pedidos = $this->model->getUserPedidos($usuarioId);

        $this->json([
            'success' => true,
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Obtiene detalles de un pedido
     */
    public function detalles() {
        $this->requireLogin();
        
        $pedidoId = $_GET['id'] ?? null;

        if (!$pedidoId) {
            $this->json(['success' => false, 'message' => 'ID de pedido requerido'], 400);
        }

        $pedido = $this->model->getPedidoCompleto($pedidoId);

        if (!$pedido) {
            $this->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        // Verificar que perteneza al usuario
        if ($pedido['id_usuario'] != $this->getUsuarioActual()) {
            $this->json(['success' => false, 'message' => 'No tienes acceso'], 403);
        }

        $detalles = $this->model->getDetalles($pedidoId);

        $this->json([
            'success' => true,
            'pedido' => $pedido,
            'detalles' => $detalles
        ]);
    }

    /**
     * Crea un nuevo pedido
     */
    public function crear() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        $usuarioId = $this->getUsuarioActual();
        $total = $_POST['total'] ?? 0;
        $detalles = json_decode($_POST['detalles'] ?? '[]', true);

        if (empty($detalles) || $total <= 0) {
            $this->json(['success' => false, 'message' => 'Datos inválidos'], 400);
        }

        $result = $this->model->crearPedidoCompleto($usuarioId, $total, $detalles);

        if ($result['success']) {
            $this->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
                'pedido_id' => $result['pedido_id'],
                'redirect' => "/usuario/seguimiento.php?pedido_id={$result['pedido_id']}"
            ]);
        }

        $this->json(['success' => false, 'message' => $result['message']], 400);
    }

    /**
     * Obtiene estado del pedido (para cliente)
     */
    public function estado() {
        $pedidoId = $_GET['pedido_id'] ?? null;

        if (!$pedidoId) {
            $this->json(['success' => false, 'message' => 'ID requerido'], 400);
        }

        $pedido = $this->model->getPedidoCompleto($pedidoId);

        if (!$pedido) {
            $this->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        $estados = [
            1 => ['nombre' => 'Recibido', 'progreso' => 25],
            2 => ['nombre' => 'Cocinando', 'progreso' => 50],
            3 => ['nombre' => 'En Camino', 'progreso' => 75],
            4 => ['nombre' => 'Entregado', 'progreso' => 100]
        ];

        $this->json([
            'success' => true,
            'pedido' => [
                'id' => $pedido['id'],
                'total' => $pedido['total'],
                'estado' => $pedido['estado'],
                'estado_nombre' => $estados[$pedido['estado']]['nombre'],
                'progreso' => $estados[$pedido['estado']]['progreso'],
                'tiempo_estimado' => $pedido['tiempo_estimado'],
                'tiempo_restante' => $pedido['tiempo_restante'],
                'fecha_pedido' => $pedido['fecha_pedido'],
                'fecha_entrega' => $pedido['fecha_entrega']
            ]
        ]);
    }
}
?>

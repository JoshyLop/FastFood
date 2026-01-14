<?php
/**
 * CONTROLADOR DE ADMINISTRADOR
 */

require_once __DIR__ . '/Controller.php';

class AdminController extends Controller {

    public function __construct() {
        $this->loadModel('Pedido');
    }

    /**
     * Obtiene todos los pedidos (para admin)
     */
    public function obtenerPedidos() {
        $this->requireAdmin();

        $pedidos = $this->model->getPedidosActivos();

        $this->json([
            'success' => true,
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Actualiza estado de pedido
     */
    public function actualizarPedido() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        $pedidoId = $_POST['pedido_id'] ?? null;
        $estado = $_POST['estado'] ?? null;
        $tiempo = $_POST['tiempo_estimado'] ?? null;

        if (!$pedidoId || !$estado) {
            $this->json(['success' => false, 'message' => 'Datos incompletos'], 400);
        }

        if ($estado < 1 || $estado > 4) {
            $this->json(['success' => false, 'message' => 'Estado inválido'], 400);
        }

        $success = $this->model->cambiarEstado($pedidoId, $estado, $tiempo);

        if ($success) {
            $this->json([
                'success' => true,
                'message' => 'Pedido actualizado correctamente'
            ]);
        }

        $this->json(['success' => false, 'message' => 'Error al actualizar'], 400);
    }

    /**
     * Obtiene estadísticas
     */
    public function estadisticas() {
        $this->requireAdmin();

        $totalPedidos = $this->model->count();
        $pedidosActivos = $this->model->count('estado < 4');
        $pedidosEntregados = $this->model->count('estado = 4');

        $this->json([
            'success' => true,
            'estadisticas' => [
                'total_pedidos' => $totalPedidos,
                'pedidos_activos' => $pedidosActivos,
                'pedidos_entregados' => $pedidosEntregados
            ]
        ]);
    }
}
?>

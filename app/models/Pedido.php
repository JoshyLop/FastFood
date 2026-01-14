<?php
/**
 * MODELO DE PEDIDOS
 */

require_once __DIR__ . '/Model.php';

class Pedido extends Model {
    protected $table = 'pedidos';

    /**
     * Obtiene pedidos del usuario
     */
    public function getUserPedidos($userId, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE id_usuario = ? ORDER BY fecha_pedido DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->query($sql, [$userId]);
    }

    /**
     * Obtiene pedidos con detalles
     */
    public function getPedidoCompleto($id) {
        $sql = "SELECT 
                    p.*,
                    u.nombre as nombre_cliente,
                    u.correo
                FROM {$this->table} p
                INNER JOIN usuarios u ON p.id_usuario = u.id
                WHERE p.id = ? LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene detalles del pedido
     */
    public function getDetalles($pedidoId) {
        $sql = "SELECT 
                    dp.*,
                    p.nombre,
                    p.imagen
                FROM detalle_pedidos dp
                INNER JOIN productos p ON dp.id_producto = p.id
                WHERE dp.id_pedido = ?";
        
        return $this->query($sql, [$pedidoId]);
    }

    /**
     * Crea un pedido con detalles
     */
    public function crearPedidoCompleto($userId, $total, $detalles) {
        try {
            // Crear pedido
            $pedidoId = $this->create([
                'id_usuario' => $userId,
                'total' => $total,
                'estado' => 1,
                'tiempo_estimado' => 20,
                'tiempo_restante' => 20
            ]);

            if (!$pedidoId) {
                return ['success' => false, 'message' => 'Error al crear pedido'];
            }

            // Agregar detalles
            $sql = "INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) 
                    VALUES (?, ?, ?, ?, ?)";
            
            foreach ($detalles as $detalle) {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $pedidoId,
                    $detalle['id_producto'],
                    $detalle['cantidad'],
                    $detalle['precio_unitario'],
                    $detalle['subtotal']
                ]);
            }

            return ['success' => true, 'pedido_id' => $pedidoId];

        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Actualiza estado
     */
    public function cambiarEstado($id, $estado, $tiempo = null) {
        $data = ['estado' => $estado];
        
        if ($tiempo !== null) {
            $data['tiempo_estimado'] = $tiempo;
            $data['tiempo_restante'] = $tiempo;
        }

        return $this->update($id, $data);
    }

    /**
     * Obtiene pedidos activos (sin entregar)
     */
    public function getPedidosActivos() {
        return $this->where('estado < 4', [], 'estado ASC, fecha_pedido DESC');
    }

    /**
     * Obtiene total de ventas
     */
    public function getTotalVentas($desde = null, $hasta = null) {
        $sql = "SELECT SUM(total) as total FROM {$this->table} WHERE estado = 4";
        
        if ($desde && $hasta) {
            $sql .= " AND fecha_pedido BETWEEN ? AND ?";
            $result = $this->query($sql, [$desde, $hasta]);
        } else {
            $result = $this->query($sql);
        }

        return $result[0]['total'] ?? 0;
    }
}
?>

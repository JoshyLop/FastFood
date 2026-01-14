<?php
/**
 * MODELO DE PRODUCTOS
 */

require_once __DIR__ . '/Model.php';

class Producto extends Model {
    protected $table = 'productos';

    /**
     * Obtiene productos por categoría
     */
    public function getByCategory($categoria) {
        return $this->where('categoria = ? AND disponible = 1', [$categoria]);
    }

    /**
     * Obtiene todos los productos disponibles
     */
    public function getDisponibles($order = 'categoria') {
        return $this->where('disponible = 1', [], $order);
    }

    /**
     * Busca productos
     */
    public function search($query) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (nombre LIKE ? OR descripcion LIKE ?) 
                AND disponible = 1";
        
        $searchTerm = "%{$query}%";
        return $this->query($sql, [$searchTerm, $searchTerm]);
    }

    /**
     * Obtiene precio de producto
     */
    public function getPrice($id) {
        $producto = $this->getById($id);
        return $producto ? $producto['precio'] : 0;
    }

    /**
     * Cambia disponibilidad
     */
    public function toggleDisponibilidad($id) {
        $producto = $this->getById($id);
        
        if (!$producto) {
            return false;
        }

        return $this->update($id, [
            'disponible' => $producto['disponible'] ? 0 : 1
        ]);
    }
}
?>

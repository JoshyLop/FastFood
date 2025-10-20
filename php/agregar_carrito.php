<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim($_POST['id'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 1);

    // Validar datos básicos
    if ($id === '' || $nombre === '' || $precio <= 0 || $cantidad <= 0) {
        echo "Datos inválidos.";
        exit;
    }

    // Inicializar carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Revisar si el producto ya está en el carrito
    $encontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] === $id) {
            $item['cantidad'] += $cantidad;
            $encontrado = true;
            break;
        }
    }
    unset($item); // Libera la referencia

    // Si no se encontró, agregar nuevo producto
    if (!$encontrado) {
        $_SESSION['carrito'][] = [
            'id' => $id,
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
    }

    echo "Producto agregado al carrito: $nombre";
} else {
    echo "Solicitud no válida.";
}
?>

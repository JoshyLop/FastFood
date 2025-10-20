<?php
session_start();
include 'conexion.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$carrito = $_SESSION['carrito'] ?? [];

if (!$usuario_id || count($carrito) === 0) {
    echo "<script>alert('No hay sesión activa o el carrito está vacío.'); window.location.href='../carrito/carrito.php';</script>";
    exit();
}

foreach ($carrito as $item) {
    $producto_id = $item['id'];
    $cantidad = $item['cantidad'];
    $precio = $item['precio'];

    $sql = "INSERT INTO Compras (UsuarioID, ProductoID, Cantidad, PrecioUnitario)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $usuario_id, $producto_id, $cantidad, $precio);
    $stmt->execute();
    $stmt->close();
}

// Vaciar el carrito
unset($_SESSION['carrito']);

header("Location: ../usuario/compra_exitosa.php");
exit();

?>

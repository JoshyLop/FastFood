<?php
session_start();
include 'conexion.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$carrito = $_SESSION['carrito'] ?? [];

$nombre = $_POST['nombre'];
$numero = $_POST['numero'];
$cvv = $_POST['cvv'];
$expira = $_POST['expira'];

if (!$usuario_id) {
    echo "<script>alert('Debes iniciar sesión para comprar.'); window.location.href='../usuario/login.html';</script>";
    exit;
}

if ($nombre && $numero && $cvv && $expira && count($carrito) > 0) {
    foreach ($carrito as $item) {
        $producto_id = $item['id'];
        $cantidad = $item['cantidad'];
        $precio = $item['precio'];

        $sql = "INSERT INTO Compras (UsuarioID, ProductoID, Cantidad, PrecioUnitario)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $usuario_id, $producto_id, $cantidad, $precio);
        $stmt->execute();
    }

    $_SESSION['carrito'] = []; // Vaciar carrito después de guardar
    header("Location: ../usuario/compra_exitosa.php");
    exit;
} else {
    echo "<script>alert('Error en los datos de pago o carrito vacío'); window.history.back();</script>";
}
?>

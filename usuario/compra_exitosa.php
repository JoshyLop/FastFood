<?php
session_start();
include '../php/conexion.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Cliente';

if (!$usuario_id) {
    echo "<script>alert('Debes iniciar sesión para ver esta página.'); window.location.href='login.html';</script>";
    exit();
}

// Consulta de las últimas compras realizadas
$sql = "SELECT C.FechaCompra, P.Nombre, C.Cantidad, C.PrecioUnitario
        FROM Compras C
        JOIN Productos P ON C.ProductoID = P.ID
        WHERE C.UsuarioID = ?
        ORDER BY C.FechaCompra DESC
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Compra Exitosa!</title>
    <link rel="stylesheet" type="text/css" href="../css/Estilos.css">
    <style>
        .mensaje h1, .mensaje h2, .mensaje h3, .mensaje td, .mensaje th, .mensaje p {
            color: black !important;
        }
    </style>
</head>
<body class="inicio">
    <center><img src="../img/logo.JPEG" width="100"></center>

    <div class="mensaje">
        <h1>¡Gracias por tu compra, <?php echo htmlspecialchars($usuario_nombre); ?>! 🎉</h1>
        <h2>Tu pedido ha sido procesado correctamente.</h2>

        <div class="botones">
            <a href="inicio.php"><button>Volver al Inicio</button></a>
            <a href="historial.php"><button>Ver Historial de Compras</button></a>
        </div>

        <h3>Resumen de tu compra más reciente:</h3>

        <?php if ($resultado->num_rows > 0): ?>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()):
                $subtotal = $fila['Cantidad'] * $fila['PrecioUnitario'];
            ?>
            <tr>
                <td><?php echo $fila['FechaCompra']; ?></td>
                <td><?php echo htmlspecialchars($fila['Nombre']); ?></td>
                <td><?php echo $fila['Cantidad']; ?></td>
                <td>$<?php echo number_format($fila['PrecioUnitario'], 2); ?></td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p style="color: red; text-align: center;">No se encontró información reciente de compra.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
session_start();
include '../php/conexion.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$usuario_nombre = $_SESSION['usuario_nombre'] ?? null;

if (!$usuario_id) {
    echo "<script>alert('Debes iniciar sesión para ver tu historial.'); window.location.href='login.html';</script>";
    exit();
}

// Consulta las compras del usuario
$sql = "SELECT C.FechaCompra, P.Nombre, C.Cantidad, C.PrecioUnitario
        FROM Compras C
        JOIN Productos P ON C.ProductoID = P.ID
        WHERE C.UsuarioID = ?
        ORDER BY C.FechaCompra DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Compras</title>
    <link rel="stylesheet" type="text/css" href="../css/Estilos.css">
</head>
<body class="inicio">
    <center><img src="../img/logo.JPEG" width="100"></center>

    <nav class="navegacion">
        <ul class="menu">
            <li><a href="inicio.php">INICIO</a></li>
            <li><a href="../comida/comidas.html">COMIDA</a></li>
            <li><a href="../postre/Postre.html">POSTRES</a></li>
            <li><a href="../bebida/Bebida.html">BEBIDAS</a></li>
            <li><a href="login.html">LOGIN</a></li>
            <li><a href="registro.html">REGISTRO</a></li>
            <li><a href="../carrito/carrito.php">CARRITO 🛒 (<span id="contador-carrito">0</span>)</a></li>
        </ul>
    </nav>

    <center>
        <h1>Historial de Compras</h1>

        <?php if ($usuario_nombre): ?>
            <h3 class="saludo">Hola, <?php echo htmlspecialchars($usuario_nombre); ?> 👋</h3>
        <?php endif; ?>

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
            <p style="color:white;">No hay compras registradas aún.</p>
        <?php endif; ?>

        <br>
        <a href="inicio.php"><button>Volver al Inicio</button></a>
    </center>

    <!-- Actualiza el contador del carrito -->
    <script>
    function actualizarContadorCarrito() {
        fetch('../php/contar_carrito.php')
            .then(res => res.json())
            .then(data => {
                const contador = document.getElementById('contador-carrito');
                if (contador) {
                    contador.textContent = data.total || 0;
                }
            })
            .catch(err => console.error('Error al contar carrito:', err));
    }

    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
    </script>
</body>
</html>

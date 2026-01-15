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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 Historial de Compras - FastFood</title>
    <link rel="stylesheet" href="../css/Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .history-table {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }
        .history-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .history-table th {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            color: white;
            padding: 1rem;
            text-align: left;
        }
        .history-table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .history-table tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="../index.html" class="navbar-logo">
                <i class="fas fa-burger"></i> FastFood
            </a>
            <ul class="navbar-menu">
                <li><a href="inicio.php">Dashboard</a></li>
                <li><a href="../comida/comidas.html">Comida</a></li>
                <li><a href="../bebida/Bebida.html">Bebidas</a></li>
                <li><a href="../postre/Postre.html">Postres</a></li>
            </ul>
            <div class="navbar-right">
                <a href="../carrito/carrito.php" class="btn btn-secondary btn-small">
                    <i class="fas fa-shopping-cart"></i> Carrito
                </a>
                <a href="../usuario/cerrar_sesion.php" class="btn btn-primary btn-small">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 2rem; margin-bottom: 4rem;">
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">📋 Historial de Compras</h1>
            <?php if ($usuario_nombre): ?>
                <p style="color: #64748b;">Bienvenido, <strong><?php echo htmlspecialchars($usuario_nombre); ?></strong> 👋</p>
            <?php endif; ?>
        </div>

        <?php if ($resultado->num_rows > 0): ?>
            <div class="history-table">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-box"></i> Producto</th>
                            <th><i class="fas fa-cube"></i> Cantidad</th>
                            <th><i class="fas fa-dollar-sign"></i> Precio Unitario</th>
                            <th><i class="fas fa-receipt"></i> Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): 
                            $subtotal = $fila['Cantidad'] * $fila['PrecioUnitario'];
                        ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($fila['FechaCompra'])); ?></td>
                                <td><strong><?php echo htmlspecialchars($fila['Nombre']); ?></strong></td>
                                <td><?php echo $fila['Cantidad']; ?></td>
                                <td>$<?php echo number_format($fila['PrecioUnitario'], 2); ?></td>
                                <td><strong style="color: var(--primary);">$<?php echo number_format($subtotal, 2); ?></strong></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
                <h2 style="color: #64748b; margin-bottom: 0.5rem;">Sin compras registradas</h2>
                <p style="color: #94a3b8; margin-bottom: 2rem;">Aún no has realizado ninguna compra. ¡Comienza a explorar nuestro menú!</p>
                <a href="../index.html" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Explorar Menú
                </a>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="inicio.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; padding: 2rem;">
        <p>&copy; 2024 FastFood - Todos los derechos reservados</p>
    </footer>

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

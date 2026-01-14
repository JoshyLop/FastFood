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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✅ ¡Compra Exitosa! - FastFood</title>
    <link rel="stylesheet" href="../css/Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .success-animation {
            animation: bounce 0.6s ease-in-out;
        }
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .order-summary {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-summary th {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: white;
            padding: 1rem;
            text-align: left;
        }
        .order-summary td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .order-summary tr:last-child td {
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
            <div class="navbar-right">
                <a href="inicio.php" class="btn btn-primary btn-small">
                    <i class="fas fa-home"></i> Ir al Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 2rem; margin-bottom: 4rem;">
        <!-- Mensaje de éxito -->
        <div class="card" style="text-align: center; padding: 3rem; margin-bottom: 2rem; border-left: 4px solid #16a34a;">
            <div class="success-animation" style="font-size: 5rem; margin-bottom: 1rem;">✅</div>
            <h1 style="font-size: 2.5rem; color: #16a34a; margin-bottom: 0.5rem;">¡Compra Exitosa!</h1>
            <p style="font-size: 1.125rem; color: #64748b; margin-bottom: 2rem;">
                Gracias por tu compra, <strong><?php echo htmlspecialchars($usuario_nombre); ?></strong>. Tu pedido ha sido procesado correctamente.
            </p>
            <div style="background: #f0fdf4; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                <p style="color: #16a34a; margin: 0;">
                    <i class="fas fa-info-circle"></i> Tu pedido será entregado en los próximos 30-45 minutos
                </p>
            </div>
        </div>

        <!-- Botones de acción -->
        <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 3rem; flex-wrap: wrap;">
            <a href="inicio.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Volver al Dashboard
            </a>
            <a href="historial.php" class="btn btn-secondary">
                <i class="fas fa-history"></i> Ver Historial
            </a>
            <a href="../comida/comidas.html" class="btn btn-secondary">
                <i class="fas fa-shopping-bag"></i> Hacer Otro Pedido
            </a>
        </div>

        <!-- Resumen de compra reciente -->
        <?php if ($resultado->num_rows > 0): ?>
            <div class="card">
                <h2 style="font-size: 1.75rem; margin-bottom: 1.5rem;">📦 Resumen de tu Compra</h2>
                <div class="order-summary">
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
                                <td><?php echo date('d/m/Y H:i', strtotime($fila['FechaCompra'])); ?></td>
                                <td><strong><?php echo htmlspecialchars($fila['Nombre']); ?></strong></td>
                                <td><?php echo $fila['Cantidad']; ?></td>
                                <td>$<?php echo number_format($fila['PrecioUnitario'], 2); ?></td>
                                <td><strong style="color: var(--primary);">$<?php echo number_format($subtotal, 2); ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Información de seguimiento -->
            <div class="card" style="margin-top: 2rem;">
                <h2 style="font-size: 1.75rem; margin-bottom: 1.5rem;">🚚 Seguimiento del Pedido</h2>
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="text-align: center; flex: 1;">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">✅</div>
                            <p style="font-weight: bold; color: #16a34a;">Confirmado</p>
                            <p style="color: #64748b; font-size: 0.9rem;">Tu pedido fue confirmado</p>
                        </div>
                        <div style="text-align: center; flex: 1;">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🍳</div>
                            <p style="font-weight: bold; color: #f97316;">Preparando</p>
                            <p style="color: #64748b; font-size: 0.9rem;">Están preparando tu comida</p>
                        </div>
                        <div style="text-align: center; flex: 1;">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🚗</div>
                            <p style="font-weight: bold; color: #f97316;">En camino</p>
                            <p style="color: #64748b; font-size: 0.9rem;">Tu pedido está en camino</p>
                        </div>
                        <div style="text-align: center; flex: 1;">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📦</div>
                            <p style="font-weight: bold; color: #64748b;">Entregado</p>
                            <p style="color: #64748b; font-size: 0.9rem;">Entrega completada</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <p style="color: var(--danger); font-size: 1.125rem;">
                    <i class="fas fa-exclamation-circle"></i> No se encontró información reciente de compra.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; padding: 2rem;">
        <p>&copy; 2024 FastFood - Todos los derechos reservados</p>
    </footer>
</body>
</html>

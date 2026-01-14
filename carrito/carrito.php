<?php
session_start();
$carrito = $_SESSION['carrito'] ?? [];
$usuario = $_SESSION['usuario_nombre'] ?? null;
$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛒 Carrito - FastFood</title>
    <link rel="stylesheet" href="../css/Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cart-table {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .cart-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart-table th {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            color: white;
            padding: 1rem;
            text-align: left;
        }
        .cart-table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .cart-table tr:last-child td {
            border-bottom: none;
        }
        .payment-card {
            background: white;
            border-radius: 0.5rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }
        .empty-cart {
            text-align: center;
            padding: 3rem;
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
                <li><a href="../index.html">Inicio</a></li>
                <li><a href="../comida/comidas.html">Comida</a></li>
                <li><a href="../bebida/Bebida.html">Bebidas</a></li>
                <li><a href="../postre/Postre.html">Postres</a></li>
            </ul>
            <div class="navbar-right">
                <a href="../usuario/login.html" class="btn btn-primary btn-small">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 2rem; margin-bottom: 4rem;">
        <div style="margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">🛒 Tu Carrito</h1>
            <?php if ($usuario): ?>
                <p style="color: #64748b;">Hola, <strong><?php echo htmlspecialchars($usuario); ?></strong> 👋</p>
            <?php endif; ?>
        </div>

        <?php if (count($carrito) > 0): ?>
            <!-- Tabla del carrito -->
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrito as $item):
                            $subtotal = $item['precio'] * $item['cantidad'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($item['nombre']); ?></strong></td>
                                <td>$<?php echo number_format($item['precio'], 2); ?></td>
                                <td><?php echo $item['cantidad']; ?></td>
                                <td><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                                <td>
                                    <button class="btn btn-secondary btn-small" onclick="alert('Funcionalidad en desarrollo')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Resumen del pedido -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                <div class="card">
                    <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Resumen del Pedido</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
                        <span>Subtotal:</span>
                        <strong>$<?php echo number_format($total, 2); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
                        <span>Envío:</span>
                        <strong>$5.00</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.25rem;">
                        <span><strong>Total:</strong></span>
                        <strong style="color: var(--primary);">$<?php echo number_format($total + 5, 2); ?></strong>
                    </div>
                </div>

                <div class="card">
                    <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Detalles de Envío</h3>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                        <p style="margin: 0; color: #64748b;"><strong>Dirección:</strong></p>
                        <p style="margin: 0.5rem 0 0;">Ingresa tu dirección en el checkout</p>
                    </div>
                    <button class="btn btn-primary" style="width: 100%; text-align: center;">
                        <i class="fas fa-credit-card"></i> Proceder al Pago
                    </button>
                </div>
            </div>

            <!-- Formulario de pago -->
            <div class="payment-card" style="max-width: 600px;">
                <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem;">💳 Información de Pago</h3>
                <form action="../php/procesar_pago.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Nombre en la tarjeta</label>
                        <input type="text" name="nombre" placeholder="Juan Pérez" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Número de tarjeta</label>
                        <input type="text" name="numero" placeholder="4532 1234 5678 9010" maxlength="16" required class="form-input">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Vencimiento (MM/AA)</label>
                            <input type="text" name="expira" placeholder="12/25" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">CVV</label>
                            <input type="text" name="cvv" placeholder="123" maxlength="3" required class="form-input">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                        Pagar $<?php echo number_format($total + 5, 2); ?>
                    </button>
                </form>
            </div>

            <!-- Vaciar carrito -->
            <div style="text-align: center; margin-top: 2rem;">
                <form action="../php/vaciar_carrito.php" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-secondary" style="color: var(--danger); border-color: var(--danger);">
                        <i class="fas fa-trash"></i> Vaciar Carrito
                    </button>
                </form>
            </div>

        <?php else: ?>
            <div class="empty-cart card">
                <div style="font-size: 5rem; margin-bottom: 1rem;">🛒</div>
                <h2 style="color: #64748b; margin-bottom: 0.5rem;">Tu carrito está vacío</h2>
                <p style="color: #94a3b8; margin-bottom: 2rem;">No hay productos en tu carrito. ¡Comienza a explorar nuestro menú!</p>
                <a href="../index.html" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Explorar Menú
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; padding: 2rem;">
        <p>&copy; 2024 FastFood - Todos los derechos reservados</p>
    </footer>
</body>
</html>
            <!-- Vaciar carrito -->
            <form action="../php/vaciar_carrito.php" method="POST" style="margin-top: 10px;">
                <button type="submit" style="background-color: crimson; color: white;">Vaciar Carrito</button>
            </form>
        <?php else: ?>
            <p style="color: white;">Tu carrito está vacío.</p>
        <?php endif; ?>
    </center>

    <!-- Seguimiento de pedido -->
    <div class="mapa-centro">
        <h2>Seguimiento de tu pedido</h2>
        <div id="estado-envio">Cargando...</div>
        <button onclick="consultarSeguimiento()">Actualizar estado</button>
    </div>

    <script>
        function consultarSeguimiento() {
            fetch('../php/api_seguimiento.php')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('estado-envio').innerText =
                        `Estado: ${data.estado} | Tiempo estimado: ${data.tiempo_estimado}`;
                })
                .catch(err => {
                    console.error('Error al consultar el estado:', err);
                    document.getElementById('estado-envio').innerText = 'No se pudo cargar el estado.';
                });
        }
    </script>
</body>

</html>
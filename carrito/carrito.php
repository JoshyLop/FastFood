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
    <title>Carrito</title>
    <link rel="stylesheet" type="text/css" href="../css/Estilos.css">
</head>

<body class="inicio">
    <center><img src="../img/logo.JPEG" width="100"></center>

    <nav class="navegacion">
        <ul class="menu">
            <li><a href="../usuario/inicio.php">INICIO</a></li>
            <li><a href="../comida/comidas.html">COMIDA</a></li>
            <li><a href="../postre/Postre.html">POSTRES</a></li>
            <li><a href="../bebida/Bebida.html">BEBIDAS</a></li>
            <li><a href="../usuario/login.html">LOGIN</a></li>
            <li><a href="../usuario/registro.html">REGISTRO</a></li>
            <li><a href="../carrito/carrito.php">CARRITO 🛒 (<span id="contador-carrito">0</span>)</a></li>
            <li><a href="../usuario/historial.php">HISTORIAL</a></li>
        </ul>
    </nav>

    <center>
        <h1>Tu Carrito</h1>

        <?php if ($usuario): ?>
            <h3 class="saludo">Hola, <?php echo htmlspecialchars($usuario); ?> 👋</h3>
        <?php endif; ?>

        <?php if (count($carrito) > 0): ?>
            <table border="1" width="70%">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($carrito as $item):
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <h3>Total a pagar: $<?php echo number_format($total, 2); ?></h3>

            <!-- Formulario de pago -->
            <div class="pago-tarjeta">
                <h2>Pagar con tarjeta</h2>
                <form action="../php/procesar_pago.php" method="POST">
                    <div class="campo-form">
                        <input type="text" name="nombre" placeholder="Nombre en tarjeta 💳" required>
                    </div>
                    <div class="campo-form">
                        <input type="text" name="numero" placeholder="Número de tarjeta" maxlength="16" required>
                    </div>
                    <div class="campo-form">
                        <input type="text" name="cvv" placeholder="CVV" maxlength="3" required>
                    </div>
                    <div class="campo-form">
                        <input type="text" name="expira" placeholder="MM/AA" required>
                    </div>
                    <div class="campo-form">
                        <button type="submit">Pagar ahora</button>
                    </div>
                </form>
            </div>





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
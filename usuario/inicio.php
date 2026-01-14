<?php
session_start();
$usuario = $_SESSION['usuario_nombre'] ?? null;

if (!$usuario) {
    echo "<script>alert('Debes iniciar sesión. Redirigiendo...'); window.location.href='login.html';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏠 Dashboard - FastFood</title>
    <link rel="stylesheet" href="../css/Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="cerrar_sesion.php" class="btn btn-primary btn-small">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 2rem; margin-bottom: 4rem;">
        <!-- Header con bienvenida -->
        <div style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); color: white; padding: 3rem; border-radius: 0.5rem; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; margin: 0; color: white;">👋 Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h1>
            <p style="margin-top: 0.5rem; opacity: 0.95;">¡Nos alegra verte nuevamente en FastFood!</p>
        </div>

        <!-- Grid de opciones rápidas -->
        <div class="grid grid-3" style="margin-bottom: 3rem;">
            <a href="../comida/comidas.html" style="text-decoration: none;">
                <div class="card text-center">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🍔</div>
                    <h3>Hacer Pedido</h3>
                    <p style="color: #64748b; font-size: 0.95rem;">Explora nuestro menú y haz tu pedido</p>
                </div>
            </a>
            <a href="historial.php" style="text-decoration: none;">
                <div class="card text-center">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                    <h3>Mi Historial</h3>
                    <p style="color: #64748b; font-size: 0.95rem;">Ver mis compras anteriores</p>
                </div>
            </a>
            <a href="../carrito/carrito.php" style="text-decoration: none;">
                <div class="card text-center">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🛒</div>
                    <h3>Mi Carrito</h3>
                    <p style="color: #64748b; font-size: 0.95rem;">Ver y gestionar mi carrito</p>
                </div>
            </a>
        </div>

        <!-- Sección de contacto y sugerencias -->
        <div class="grid grid-2" style="margin-bottom: 3rem;">
            <!-- Mapa -->
            <div class="card">
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">📍 Nuestra Sucursal</h3>
                <div style="border-radius: 0.5rem; overflow: hidden; height: 300px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3732.1922697392387!2d-101.6863601255883!3d20.967076080718308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842b94c58ac9059d%3A0x1ea122b024ffca56!2sLe%C3%B3n%2C%20Gto.!5e0!3m2!1ses!2smx!4v1711832400000"
                        width="100%" height="100%" style="border: none;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Formulario de sugerencias -->
            <div class="card">
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">💬 Envíanos tu Comentario</h3>
                <form style="display: flex; flex-direction: column; gap: 1rem;">
                    <textarea 
                        class="form-input" 
                        style="resize: vertical; min-height: 200px;" 
                        placeholder="Cuéntanos tu experiencia, sugerencias o dudas..."
                    ></textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Comentario
                    </button>
                </form>
            </div>
        </div>

        <!-- Redes sociales -->
        <div class="card text-center">
            <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Síguenos en Redes Sociales</h3>
            <div style="display: flex; justify-content: center; gap: 2rem;">
                <a href="https://es-la.facebook.com/" class="btn btn-primary">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="https://www.instagram.com/?hl=es-la" class="btn btn-primary">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a href="https://web.whatsapp.com/" class="btn btn-primary">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; padding: 2rem;">
        <p>&copy; 2024 FastFood - Todos los derechos reservados</p>
    </footer>
</body>
</html>
</body>

</html>
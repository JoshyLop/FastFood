<?php
session_start();
$usuario = $_SESSION['usuario_nombre'] ?? null;
$rol = $_SESSION['usuario_rol'] ?? 'cliente';

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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="../../app/views/index.html" class="flex items-center gap-2 text-2xl font-bold text-red-600">
                    <span>🍔</span> FastFood
                </a>
                <div class="hidden md:flex gap-6">
                    <a href="inicio.php" class="text-gray-700 hover:text-red-600 font-medium">Dashboard</a>
                    <a href="../comida/comidas.html" class="text-gray-700 hover:text-red-600 font-medium">Comida</a>
                    <a href="../bebida/Bebida.html" class="text-gray-700 hover:text-red-600 font-medium">Bebidas</a>
                    <a href="../postre/Postre.html" class="text-gray-700 hover:text-red-600 font-medium">Postres</a>
                </div>
                <div class="flex gap-4">
                    <a href="../carrito/carrito.php" class="px-4 py-2 border-2 border-red-600 text-red-600 rounded-lg font-medium hover:bg-red-50">
                        🛒 Carrito
                    </a>
                    <a href="cerrar_sesion.php" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                        Salir
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gray-50 min-h-screen">
        <!-- Header con bienvenida -->
        <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php if ($rol === 'admin'): ?>
                    <h1 class="text-4xl font-bold mb-2">⚙️ Panel Administrativo</h1>
                    <p class="text-red-100">Gestiona tu negocio FastFood desde aquí</p>
                <?php else: ?>
                    <h1 class="text-4xl font-bold mb-2">👋 Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h1>
                    <p class="text-red-100">¡Nos alegra verte nuevamente en FastFood!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Grid de opciones según rol -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <?php if ($rol === 'admin'): ?>
                    <!-- Admin Dashboard -->
                    <a href="../admin/productos.php" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">🍕</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Gestionar Productos</h3>
                            <p class="text-gray-600">Agregar, editar y eliminar productos</p>
                        </div>
                    </a>
                    <a href="../admin/repartidores.php" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">🚗</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Gestionar Repartidores</h3>
                            <p class="text-gray-600">Ver ubicación en mapa, estado</p>
                        </div>
                    </a>
                    <a href="#usuarios" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">👥</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Gestionar Usuarios</h3>
                            <p class="text-gray-600">Ver y gestionar usuarios registrados</p>
                        </div>
                    </a>
                    <a href="#reportes" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">📊</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Reportes</h3>
                            <p class="text-gray-600">Ver estadísticas y ganancias</p>
                        </div>
                    </a>
                    <a href="#configuracion" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">⚙️</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Configuración</h3>
                            <p class="text-gray-600">Ajusta la configuración del negocio</p>
                        </div>
                    </a>
                    <a href="cerrar_sesion.php" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition border-2 border-red-600">
                            <div class="text-5xl mb-4">🚪</div>
                            <h3 class="text-xl font-bold text-red-600 mb-2">Cerrar Sesión</h3>
                            <p class="text-gray-600">Salir del panel</p>
                        </div>
                    </a>
                <?php else: ?>
                    <!-- Client Dashboard -->
                    <a href="../comida/comidas.html" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">🍔</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Hacer Pedido</h3>
                            <p class="text-gray-600">Explora nuestro menú y haz tu pedido</p>
                        </div>
                    </a>
                    <a href="historial.php" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">📋</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Mi Historial</h3>
                            <p class="text-gray-600">Ver mis compras anteriores</p>
                        </div>
                    </a>
                    <a href="../carrito/carrito.php" class="no-underline">
                        <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                            <div class="text-5xl mb-4">🛒</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Mi Carrito</h3>
                            <p class="text-gray-600">Ver y gestionar mi carrito</p>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Sección de contacto y sugerencias (solo para clientes) -->
            <?php if ($rol !== 'admin'): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mapa -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">📍 Nuestra Sucursal</h3>
                    <div class="rounded-lg overflow-hidden" style="height: 300px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3732.1922697392387!2d-101.6863601255883!3d20.967076080718308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842b94c58ac9059d%3A0x1ea122b024ffca56!2sLe%C3%B3n%2C%20Gto.!5e0!3m2!1ses!2smx!4v1711832400000"
                            width="100%" height="100%" style="border: none;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <!-- Formulario de sugerencias -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">💬 Envíanos tu Comentario</h3>
                    <form class="flex flex-col gap-4">
                        <textarea 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none" 
                            style="min-height: 150px;" 
                            placeholder="Cuéntanos tu experiencia, sugerencias o dudas..."
                        ></textarea>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            ✈️ Enviar Comentario
                    </button>
                    </form>
                </div>
            </div>

            <!-- Redes sociales -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center mt-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Síguenos en Redes Sociales</h3>
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="https://es-la.facebook.com/" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        👍 Facebook
                    </a>
                    <a href="https://www.instagram.com/?hl=es-la" class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-medium transition">
                        📷 Instagram
                    </a>
                    <a href="https://web.whatsapp.com/" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                        💬 WhatsApp
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-6 mt-12">
        <p>&copy; 2024 FastFood - Todos los derechos reservados</p>
    </footer>

    <script src="../../js/index.js"></script>
</body>
</html>

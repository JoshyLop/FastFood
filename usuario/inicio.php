<?php
session_start();
$usuario = $_SESSION['usuario_nombre'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
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
            <li><a href="historial.php">HISTORIAL</a></li>
        </ul>
    </nav>

    <center>
        <h1>Bienvenido a Fast-Food, tu portal de comida rápida</h1>

        <?php if ($usuario): ?>
            <div class="saludo">
                <h3>Hola, <?php echo htmlspecialchars($usuario); ?> 👋</h3>
                <form action="cerrar_sesion.php" method="POST">
                    <button type="submit">Cerrar Sesión</button>
                </form>
            </div>
        <?php endif; ?>


        <h3>Encuentra mayor información en nuestras redes sociales</h3>

        <a href="https://es-la.facebook.com/"><img src="../img/Icono/face.png" width="45"></a>
        <a href="https://www.instagram.com/?hl=es-la"><img src="../img/Icono/Instagram.jpg" width="45"></a>
        <a href="https://web.whatsapp.com/"><img src="../img/Icono/Wh.jpg" width="45"></a>
    </center>

        <div class="mapa-centro">
            <h1>Te esperamos en nuestra sucursal</h1>
            <div class="mapa-contenedor">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3732.1922697392387!2d-101.6863601255883!3d20.967076080718308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842b94c58ac9059d%3A0x1ea122b024ffca56!2sLe%C3%B3n%2C%20Gto.!5e0!3m2!1ses!2smx!4v1711832400000"
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <textarea placeholder="Déjanos un comentario, duda o sugerencia."></textarea><br>
            <button>Enviar</button>
        </div>
    </div>
</body>

</html>
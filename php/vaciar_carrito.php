<?php
session_start();
unset($_SESSION['carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito Vaciado</title>
    <script>
        alert('Carrito vaciado correctamente');
        window.location.href = '../carrito/carrito.php';
    </script>
</head>
<body>
</body>
</html>

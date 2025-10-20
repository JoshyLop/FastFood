<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    $sql = "SELECT ID, Nombre FROM Usuarios WHERE Correo = ? AND Contraseña = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $contrasena);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Usuario encontrado
        $stmt->bind_result($id, $nombre);
        $stmt->fetch();

        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nombre'] = $nombre;

        echo "<script>alert('¡Bienvenido $nombre!'); window.location.href='../usuario/inicio.php';</script>";
    } else {
        echo "<script>alert('Correo o contraseña incorrectos.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Acceso no permitido.";
}

<?php
include 'conexion.php'; // Conecta a la base de datos

// Verifica que los datos vengan del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y obtener los datos
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $domicilio = $_POST['domicilio'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $fechaNacimiento = $_POST['fecha'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $genero = $_POST['genero'] ?? '';

    // Evitar registros con campos vacíos
    if ($nombre && $apellidos && $correo && $contrasena) {
        $sql = "INSERT INTO Usuarios (Nombre, Apellidos, Domicilio, Correo, Telefono, FechaNacimiento, Contraseña, Ciudad, Genero)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $nombre, $apellidos, $domicilio, $correo, $telefono, $fechaNacimiento, $contrasena, $ciudad, $genero);

        if ($stmt->execute()) {
            echo "<script>alert('¡Registro exitoso!'); window.location.href='../usuario/login.html';</script>";
        } else {
            echo "<script>alert('Error: el correo ya existe o los datos son inválidos.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Por favor completa todos los campos obligatorios.'); window.history.back();</script>";
    }

    $conn->close();
} else {
    echo "Acceso no permitido.";
}
?>

<?php
// Base de datos usando xampp
$servidor = "localhost";
$usuario = "root";
$contrasena = ""; // No esta configurada una contraseña
$basedatos = "FastFood";

// Crear conexión
$conn = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Mostramos que la conexion fue exitosa
echo "Conexión exitosa a la base de datos";
?>

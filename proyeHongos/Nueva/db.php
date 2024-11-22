<?php
// Datos de conexión
$servidor = "localhost"; // o la dirección IP del servidor
$usuario = "root"; // tu nombre de usuario de MySQL
$contraseña = ""; // tu contraseña de MySQL
$base_de_datos = "hongos"; // el nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $contraseña, $base_de_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión exitosa";
?>
<?php
// Conexión a la base de datos
$host = "localhost"; // Cambiar si es necesario
$usuario = "root";
$password = "";
$base_datos = "hongos";

$conn = new mysqli($host, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la fecha del formulario
$Fecha = $_POST['Fecha'];

// Consulta SQL para filtrar por fecha
$sql = "SELECT * FROM hongos_integral WHERE DATE(Fecha) = '$Fecha'";

$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    // Mostrar los resultados
    echo "<h2>Resultados para la fecha: $fecha</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Valor de Humedad</th><th>Fecha y Hora</th></tr>";

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr><td>" . $fila['id'] . "</td><td>" . $fila['valor_humedad'] . "</td><td>" . $fila['fecha_registro'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron registros para la fecha seleccionada.";
}

$conn->close();
?>
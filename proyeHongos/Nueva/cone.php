<?php
$servername = "localhost";
$username = "root";
$password = " ";
$dbname = "hongos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Definir la fecha y las horas para el filtro
$fecha = '2024-10-21';
$hora_inicio = '08:00:00';
$hora_fin = '12:00:00';

// Preparar la consulta
$sql = "SELECT * FROM hongos_integral WHERE Fecha = ? AND Hora BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fecha, $hora_inicio, $hora_fin);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Mostrar resultados
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Fecha: " . $row["Fecha"] . " - Hora: " . $row["Hora"] . " - Temp1: " . $row["Temp1"] . "<br>";
    }
} else {
    echo "0 resultados";
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>

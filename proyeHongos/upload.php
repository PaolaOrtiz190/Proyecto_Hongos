<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir archivo TXT</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Selecciona un archivo TXT:</label>
        <input type="file" name="file" id="file" accept=".txt" required>
        <button type="submit">Subir</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];

        // Verifica que el archivo sea un TXT
        if (pathinfo($fileName, PATHINFO_EXTENSION) !== 'txt') {
            die("Por favor sube un archivo TXT.");
        }

        // Lee el contenido del archivo
        $fileContent = file_get_contents($fileTmpPath);
        $lines = explode("\n", trim($fileContent));

        // Configura la conexión a la base de datos
        $servername = "localhost"; // Cambia esto si es necesario
        $username = "root";   // Cambia esto por tu usuario de MySQL
        $password = "";  // Cambia esto por tu contraseña de MySQL
        $dbname = "hongos";         // Nombre de la base de datos

        // Crea la conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Prepara la consulta
        $stmt = $conn->prepare("INSERT INTO hongos (fecha, hora, temp1, temp2, temp3, temp4, temp5, t_ext, hum_ext, tempAvg, hnumAvg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($lines as $line) {
            $data = explode(",", trim($line)); // Suponiendo que los datos están separados por comas

            if (count($data) == 11) { // Asegúrate de que hay 11 elementos
                $stmt->bind_param("ssddddddddd", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10]);
                $stmt->execute();
            }
        }

        echo "Datos guardados correctamente.";

        // Cierra la conexión
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>

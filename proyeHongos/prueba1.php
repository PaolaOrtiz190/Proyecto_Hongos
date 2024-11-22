<?php
    include_once('db.php');
   
    $query="SELECT * FROM hongos_integral;";
    $resultado= $conn->query($query);

    echo "<table border='1'>
    <tr>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Temp1</th>
        <th>Temp2</th>
        <th>Temp3</th>
        <th>Temp4</th>
        <th>Temp5</th>
        <th>T_ext</th>
        <th>Hum_ext</th>
        <th>TempAvg</th>
        <th>HumAvg</th>
    </tr>";
    while ($row = $resultado->fetch_assoc()){
    echo "<tr>
    <td>" . $row['fecha'] . "</td>
    <td>" . $row['hora'] . "</td>
    <td>" . $row['temp1'] . "</td>
    <td>" . $row['temp2'] . "</td>
    <td>" . $row['temp3'] . "</td>
    <td>" . $row['temp4'] . "</td>
    <td>" . $row['temp5'] . "</td>
    <td>" . $row['t_ext'] . "</td>
    <td>" . $row['hum_ext'] . "</td>
    <td>" . $row['tempAvg'] . "</td>
    <td>" . $row['humAvg'] . "</td>
  </tr>";
}
echo "</table>";

$conn->close();
?>

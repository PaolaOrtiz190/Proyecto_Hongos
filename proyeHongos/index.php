<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Hongos</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i|Roboto+Mono:300,400,700|Roboto+Slab:300,400,700"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/material.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/loader.css">
</head>

<body>
   <h1> <center> Datos </center></h1>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">

                    <div class="row">
                        <div class="col">
                            <input type="date" name="fecha" class="form-control" placeholder="Fecha de Inicio"
                                required>
                        </div>
                        <div class="col">
                            <input type="date" name="fechaFin" class="form-control" placeholder="Fecha Final" required>
                        </div>
                        <div class="col">
                            <span class="btn btn-dark mb-2" id="filtro">Filtrar</span>

                        </div>
                        <form action="upload.php" method="post">
        <button class="btn btn-dark mb-2" type="submit">Archivo</button>
    </form>
                    </div>

                </div>

                <div class="col-md-12 text-center mt-5">
                    <span id="loaderFiltro"> </span>
                </div>
                <div class="table-responsive resultadoFiltro">
                    <table class="table table-hover" id="tableHongos">
                        <thead>
                            <tr>
                                <th scope="col">FECHA</th>
                                <th scope="col">HORA</th>
                                <th scope="col">TEMPERATURA 1</th>
                                <th scope="col">TEMPERATURA 2</th>
                                <th scope="col">TEMPERATURA 3</th>
                                <th scope="col">TEMPERATURA 4</th>
                                <th scope="col">TEMPERATURA 5</th>
                                <th scope="col">TEMPERATURA EXTERNA</th>
                                <th scope="col">HUMEDAD EXTERNA</th>
                                <th scope="col">TEMPERATURA EXTERNA</th>
                                <th scope="col">HUMEDAD EXTERNA</th>
                            </tr>
                        </thead>
                        <?php
                        include('config.php');
                        $sqlHongos = ('SELECT * FROM hongos ORDER BY fecha ASC');
                        $query = mysqli_query($con, $sqlHongos);
                        
                          while ($dataRow = mysqli_fetch_array($query)) { ?>
                          <tbody>
                            <tr>
                             
                              <td><?php echo $dataRow['fecha'] ; ?></td>
                              <td><?php echo $dataRow['hora'] ; ?></td>
                              <td><?php echo $dataRow['temp1'] ; ?></td>
                              <td><?php echo $dataRow['temp2'] ; ?></td>
                              <td><?php echo $dataRow['temp3'] ; ?></td>
                              <td><?php echo $dataRow['temp4'] ; ?></td>
                              <td><?php echo $dataRow['temp5'] ; ?></td>
                              <td><?php echo $dataRow['t_ext'] ; ?></td>
                              <td><?php echo $dataRow['hum_ext'] ; ?></td>
                              <td><?php echo $dataRow['tempAvg'] ; ?></td>
                              <td><?php echo $dataRow['humAvg'] ;?></td>

                          </tr>
                          </tbody>
                        <?php } ?>
                    </table>
                 
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.js"
                    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
                <script src="js/material.min.js"></script>
                <script>
                    $(function () {
                        setTimeout(function () {
                            $('body').addClass('loaded');
                        }, 1000);


                        //FILTRANDO REGISTROS
                        $("#filtro").on("click", function (e) {
                            e.preventDefault();

                            loaderF(true);

                            var f_ingreso = $('input[name=fecha]').val();
                            var f_fin = $('input[name=fechaFin]').val();
                            console.log(f_ingreso + '' + f_fin);

                            if (f_ingreso != "" && f_fin != "") {
                                $.post("filtro.php", { f_ingreso, f_fin }, function (data) {
                                    $("#tableHongos").hide();
                                    $(".resultadoFiltro").html(data);
                                    loaderF(false);
                                });
                            } else {
                                $("#loaderFiltro").html('<p style="color:red;  font-weight:bold;">Debe seleccionar ambas fechas</p>');
                            }
                        });


                        function loaderF(statusLoader) {
                            console.log(statusLoader);
                            if (statusLoader) {
                                $("#loaderFiltro").show();
                                $("#loaderFiltro").html('<img class="img-fluid" src="img/cargando.svg" style="left:50%; right: 50%; width:50px;">');
                            } else {
                                $("#loaderFiltro").hide();
                            }
                        }
                    });
                </script>
</body>

</html>
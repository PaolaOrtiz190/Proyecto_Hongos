<?php
  include_once('db.php');
       	
	$datetime=$_POST['datetime'];
	$temperatura=$_POST['temperatura'];
	$humidities=$_POST['humidities'];  
    $tempExterior=$_POST['tempExterior']; 
    $humExterior=$_POST['humExterior']; 
	echo "";
 	 $conectar=conn();
	  $sql="insert into hongos_integral(date,temperatura,humidities,tempExterior,humExterior,headerWritten) values ('".$_POST["date"]."','".$_POST[temp]."','.$_POST[precio].','.$_POST[prenda].')";
          $resul= mysqli_query($conectar,$sql) or trigger_error("Query Failed! SQL Error:".mysql_error($conectar),E_USER_ERROR);
	  echo "$sql";
	

?>
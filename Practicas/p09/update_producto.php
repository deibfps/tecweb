<?php
/* MySQL Conexion*/
$link = mysqli_connect("localhost", "root", "distrito123", "marketzone");
if($link === false){
die("ERROR: No pudo conectarse con la DB. " . mysqli_connect_error());
}
// Se actualiza el campo 'email' de la tabla 'personas' donde el 'id' es 3
$sql = "UPDATE personas SET email='josem_nuevo@mail.com' WHERE id=3";
if(mysqli_query($link, $sql)){
echo "Registro actualizado.";
} else {
echo "ERROR: No se ejecuto $sql. " . mysqli_error($link);
}
// Cierra la conexion
mysqli_close($link);
?>
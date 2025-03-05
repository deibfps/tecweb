<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();

    // SE VERIFICA SI SE RECIBIÓ EL PARÁMETRO DE BÚSQUEDA
    if( isset($_POST['query']) ) {
        $query = $conexion->real_escape_string($_POST['query']);

        // CONSULTA SQL USANDO LIKE PARA PERMITIR BÚSQUEDA PARCIAL EN NOMBRE, MARCA O DETALLES
        $sql = "SELECT * FROM productos 
                WHERE nombre LIKE '%$query%' 
                OR marca LIKE '%$query%' 
                OR detalles LIKE '%$query%'";

        if ($result = $conexion->query($sql)) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($conexion));
        }
        $conexion->close();
    } 

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>
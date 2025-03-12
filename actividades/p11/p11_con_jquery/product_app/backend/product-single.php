<?php
 include_once __DIR__.'/database.php';
 
 //SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
 $id = $_POST['id'];
 $data = array(
     'status'  => 'error',
     'message' => 'Producto no encontrado' //Mensaje por defecto si no se encuentra
 );
 
 if (!empty($id)) {
     //SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
     $sql = "SELECT * FROM productos WHERE id = $id";
     $result = $conexion->query($sql);
 
     if ($result && $result->num_rows > 0) {
         //El producto fue encontrado
         $row = $result->fetch_assoc();
         $data = array(
             'status' => 'success',
             'product' => array(
                 'nombre' => $row['nombre'],
                 'precio' => $row['precio'],
                 'unidades' => $row['unidades'],
                 'modelo' => $row['modelo'],
                 'marca' => $row['marca'],
                 'detalles' => $row['detalles'],
                 'imagen' => $row['imagen'],
                 'id' => $row['id']
             )
         );
     } else {
         //Producto no encontrado
         $data['message'] = 'Producto no encontrado';
     }
 
     //Liberar el resultado
     $result->free();
 }
 
 //Cierra la conexion
 $conexion->close();
 
 //SE HACE LA CONVERSION DE ARRAY A JSON
 echo json_encode($data, JSON_PRETTY_PRINT);
 ?>
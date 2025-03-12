<?php
     include_once __DIR__.'/database.php';
 
     // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
     $producto = file_get_contents('php://input');
     $data = array(
         'status'  => 'error',
         'message' => 'Error en la validación de los datos'
     );
 
     if(!empty($producto)) {
         // SE TRANSFORMA EL STRING DEL JSON A OBJETO
         $jsonOBJ = json_decode($producto);
 
 
         // Validar que el producto no exista previamente en la base de datos
         $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
         $result = $conexion->query($sql);
 
         if ($result->num_rows == 0) {
             $conexion->set_charset("utf8");
 
             // Preparar la consulta de inserción
             $sql = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
             
             if($conexion->query($sql)){
                 $data['status'] =  "success";
                 $data['message'] =  "Producto agregado";
             } else {
                 $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($conexion);
             }
         } else {
             $data['message'] = 'Ya existe un producto con ese nombre';
         }
 
         $result->free();
         // Cierra la conexión
         $conexion->close();
     } else {
         $data['message'] = 'No se recibieron datos';
     }
 
     // SE HACE LA CONVERSIÓN DE ARRAY A JSON
     echo json_encode($data, JSON_PRETTY_PRINT);
 ?>
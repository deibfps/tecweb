<?php
 include_once __DIR__.'/database.php';
 
 // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
 $producto = file_get_contents('php://input');
 $data = array(
     'status'  => 'error',
     'message' => 'Datos incompletos o inválidos'
 );
 
 if (!empty($producto)) {
     // SE TRANSFORMA EL STRING DEL JSON A OBJETO
     $jsonOBJ = json_decode($producto);
 
     // Asegurarse de que los campos necesarios existen
     if (isset($jsonOBJ->id) && isset($jsonOBJ->nombre)) {
         $id = $jsonOBJ->id;
         $nombre = $jsonOBJ->nombre;
 
         // Verificar si el nombre del producto ya existe (excluyendo el producto actual)
         $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND id != {$id} AND eliminado = 0";
         $result = $conexion->query($sql);
 
         if ($result->num_rows == 0) {
             $conexion->set_charset("utf8");
 
             // Actualizar el producto en la base de datos
             $sql = "UPDATE productos SET 
                 nombre = '{$nombre}', 
                 marca = '{$jsonOBJ->marca}', 
                 modelo = '{$jsonOBJ->modelo}', 
                 precio = {$jsonOBJ->precio}, 
                 detalles = '{$jsonOBJ->detalles}', 
                 unidades = {$jsonOBJ->unidades}, 
                 imagen = '{$jsonOBJ->imagen}' 
                 WHERE id = {$id}";
 
             if ($conexion->query($sql) === TRUE) {
                 $data['status'] = "success";
                 $data['message'] = "Producto editado exitosamente";
             } else {
                 $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($conexion);
             }
         } else {
             $data['message'] = "Ya existe un producto con ese nombre";
         }
 
         $result->free();
     }
 }
 
 // Cierra la conexión
 $conexion->close();
 
 // SE HACE LA CONVERSIÓN DE ARRAY A JSON
 header('Content-Type: application/json');
 echo json_encode($data, JSON_PRETTY_PRINT);
 ?>
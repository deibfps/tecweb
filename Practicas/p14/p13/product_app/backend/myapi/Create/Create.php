<?php
namespace tecweb\MyApi\CREATE;

use tecweb\MyApi\DataBase;

require_once __DIR__.'/../DataBase.php';

class Create extends DataBase{

    public function add($producto){
        // Inicializamos la respuesta de error
        $this->response = array(
            'status'  => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );
    
        if(!empty($producto)) {
            // SE TRANSFORMA EL STRING DEL JASON A OBJETO
            $jsonOBJ = json_decode($producto);
            // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
            $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);
            
            if ($result->num_rows == 0) {
                // Si no existe el producto, se inserta
                $this->conexion->set_charset("utf8");
                $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                        VALUES ('{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
                if($this->conexion->query($sql)){
                    // Se actualiza la respuesta de éxito
                    $this->response['status'] =  "success";
                    $this->response['message'] =  "Producto agregado correctamente";
                } else {
                    $this->response['message'] = "ERROR: No se ejecutó la consulta $sql. " . mysqli_error($this->conexion);
                }
            }
            
            // Liberar el recurso de la consulta
            $result->free();
        }
    
        // Cerrar la conexión
        $this->conexion->close();
    
        // Retornamos la respuesta al final
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
    
    // Método para eliminar un producto
    public function delete($id) {
        $this->response = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );

        // Verificamos si el ID no está vacío
        if (!empty($id)) {
            // Hacemos la consulta para actualizar el estado de "eliminado"
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
            if ($this->conexion->query($sql)) {
                $this->response['status'] = "success";
                $this->response['message'] = "Producto eliminado";
            } else {
                $this->response['message'] = "ERROR: No se ejecutó la consulta $sql. " . mysqli_error($this->conexion);
            }
        }

        // Cerramos la conexión
        $this->conexion->close();
    }
}
?>
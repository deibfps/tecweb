<?php
    namespace tecweb\MyApi\UPDATE;

    use tecweb\MyApi\DataBase;

    require_once __DIR__.'/../DataBase.php';

    class Update extends DataBase{

        public function __construct($db) {
            parent::__construct($db);
        }
        
        public function edit($productoJSON) {
        $this->response = array(
            'status'  => 'error',
            'message' => 'Error en la actualización del producto'
        );

        // Verificamos que el JSON recibido no esté vacío
        if (!empty($productoJSON)) {
            // Decodificamos el JSON recibido
            $jsonOBJ = json_decode($productoJSON);

            // Verificamos que los datos necesarios estén presentes
            if (isset($jsonOBJ->id) && isset($jsonOBJ->nombre) && isset($jsonOBJ->marca) && isset($jsonOBJ->modelo) && isset($jsonOBJ->precio) && isset($jsonOBJ->unidades)) {
                // Establecemos el charset de la conexión
                $this->conexion->set_charset("utf8");

                // Preparamos la consulta SQL para actualizar el producto
                $sql = "UPDATE productos 
                        SET nombre = '{$jsonOBJ->nombre}', 
                            marca = '{$jsonOBJ->marca}', 
                            modelo = '{$jsonOBJ->modelo}', 
                            precio = {$jsonOBJ->precio}, 
                            detalles = '{$jsonOBJ->detalles}', 
                            unidades = {$jsonOBJ->unidades}, 
                            imagen = '{$jsonOBJ->imagen}' 
                        WHERE id = '{$jsonOBJ->id}' AND eliminado = 0";

                // Ejecutamos la consulta
                if ($this->conexion->query($sql)) {
                    $this->response['status'] = "success";
                    $this->response['message'] = "Producto actualizado correctamente";
                } else {
                    $this->response['message'] = "ERROR: No se ejecutó la consulta $sql. " . mysqli_error($this->conexion);
                }
            } else {
                $this->response['message'] = 'Datos incompletos para la actualización';
            }
        } else {
            $this->response['message'] = 'No se recibió información para actualizar';
        }
    }

    }
?>
<?php
    namespace TECWEB\MYAPI\UPDATE;

    use TECWEB\MYAPI\DataBase;
    require_once __DIR__.'/../DataBase.php';

    class Update extends DataBase{

        public function __construct($db) {
            parent::__construct($db);
        }
        
        public function edit($producto){
            $this->response = array(
                'status'  => 'error',
                'message' => 'Error en la actualización del producto'
            );
        
            if(!empty($producto)) {
                // SE TRANSFORMA EL STRING DEL JSON A OBJETO
                $jsonOBJ = json_decode($producto);
        
                // Verificamos que los datos necesarios existan antes de proceder
                if (isset($jsonOBJ->id) && isset($jsonOBJ->nombre) && isset($jsonOBJ->marca) && isset($jsonOBJ->modelo) && isset($jsonOBJ->precio) && isset($jsonOBJ->unidades)) {
        
                    // Establecemos el conjunto de caracteres a UTF-8
                    $this->conexion->set_charset("utf8");
        
                    // Construimos la sentencia SQL para actualizar el producto
                    $sql = "UPDATE productos SET nombre = '{$jsonOBJ->nombre}', marca = '{$jsonOBJ->marca}', modelo = '{$jsonOBJ->modelo}', precio = {$jsonOBJ->precio}, detalles = '{$jsonOBJ->detalles}', unidades = {$jsonOBJ->unidades}, imagen = '{$jsonOBJ->imagen}' WHERE id = '{$jsonOBJ->id}' AND eliminado = 0";
        
                    // Ejecutamos la consulta
                    if ($this->conexion->query($sql)) {
                        $this->response['status'] = "success";
                        $this->response['message'] = "Producto actualizado correctamente";
                    } else {
                        // Mensaje en caso de error al ejecutar la consulta
                        $this->response['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($this->conexion);
                    }
                } else {
                    $this->response['message'] = 'Datos incompletos para la actualización';
                }
        
                // Cierra la conexión
                $this->conexion->close();
            } else {
                $this->response['message'] = 'No se recibió información para actualizar';
            }
        
        }

    }
?>
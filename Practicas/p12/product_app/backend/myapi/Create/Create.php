<?php
    namespace TECWEB\MYAPI\CREATE;

    use TECWEB\MYAPI\DataBase;
    require_once __DIR__.'/../DataBase.php';

    class Create extends DataBase{

        public function __construct($db) {
            parent::__construct($db);
        }

        public function add($producto){
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
                    $this->conexion->set_charset("utf8");
                    $sql = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
                    if($this->conexion->query($sql)){
                        $this->response['status'] =  "success";
                        $this->response['message'] =  "Producto agregado";
                    } else {
                        $this->response['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
                    }
                }
        
                $result->free();
                // Cierra la conexion
                $this->conexion->close();
            }
        }
    }
?>
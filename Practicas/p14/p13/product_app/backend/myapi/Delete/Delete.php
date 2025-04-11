<?php
namespace tecweb\MyApi\DELETE;

use tecweb\MyApi\DataBase;

require_once __DIR__.'/../DataBase.php';

class Delete extends DataBase{

    public function __construct($db) {
        parent::__construct($db);
    }

    public function delete($id) {
        $this->response = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );

        
        if (!empty($id)) {
            
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
            if ($this->conexion->query($sql)) {
                $this->response['status'] = "success";
                $this->response['message'] = "Producto eliminado";
            } else {
                $this->response['message'] = "ERROR: No se ejecutó la consulta $sql. " . mysqli_error($this->conexion);
            }
        }
        $this->conexion->close();
    }
}

?>
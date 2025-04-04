<?php
    namespace TECWEB\MYAPI\DELETE;

    use TECWEB\MYAPI\DataBase;
    require_once __DIR__.'/../DataBase.php';

    class Delete extends DataBase{

        public function __construct($db) {
            parent::__construct($db);
        }

        public function delete_dat($string){
            $this->response = array(
                'status'  => 'error',
                'message' => 'La consulta falló'
            );
            // SE VERIFICA HABER RECIBIDO EL ID
            if( isset($string)) {
                $id = $string;
                // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
                $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
                if ( $this->conexion->query($sql) ) {
                    $this->response['status'] =  "success";
                    $this->response['message'] =  "Producto eliminado";
                } else {
                    $this->response['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
                }
                $this->conexion->close();
            } 

        }
    }
?>
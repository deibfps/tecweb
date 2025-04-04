<?php
namespace TECWEB\MYAPI;

abstract class DataBase {
    protected $conexion = NULL;
    protected $response = [];
    
    public function __construct($db = 'marketzone', $user = 'root', $pass = 'distrito123') {
        $this->conexion = @mysqli_connect(
            'localhost',
            $user,
            $pass,
            $db
        );
        if (!$this->conexion) {
            die('Error de conexión (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
    }

    public function getData(){
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
}
?>
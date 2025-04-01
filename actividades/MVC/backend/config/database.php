<?php
namespace TECWEB\MYAPI;

class Database {
    private static $host = 'localhost';
    private static $user = 'root';
    private static $password = 'distrito123';
    private static $database = 'marketzone';
    protected $conexion;

    public function __construct() {
        $this->conexion = new \mysqli(self::$host, self::$user, self::$password, self::$database);

        if ($this->conexion->connect_error) {
            die(json_encode(['status' => 'error', 'message' => '¡Error en la conexión a la base de datos!']));
        }
        $this->conexion->set_charset("utf8");
    }
}
?>

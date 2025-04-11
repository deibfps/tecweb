<?php
    namespace tecweb\MyApi\READ;

    use tecweb\MyApi\DataBase;
    require_once __DIR__.'/../DataBase.php';

    class Read extends DataBase{
        
        public function __construct($db) {
            parent::__construct($db);
        }

        public function list() {
            $this->response = [];
    
            // Realizamos la consulta a la base de datos
            if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
                // Obtenemos los resultados
                $rows = $result->fetch_all(MYSQLI_ASSOC);
    
                if (!is_null($rows)) {
                    // Codificamos a UTF-8 los datos y los mapeamos al arreglo de respuesta
                    foreach ($rows as $num => $row) {
                        foreach ($row as $key => $value) {
                            $this->response[$num][$key] = utf8_encode($value);
                        }
                    }
                }
                $result->free();
            } else {
                die('Query Error: ' . mysqli_error($this->conexion));
            }
        }
    
    
        public function search($search) {
            $this->response = array();
    
            // Verificamos que el parámetro search no esté vacío
            if (!empty($search)) {
                // Establecemos el charset de la conexión
                $this->conexion->set_charset("utf8");
    
                // Preparamos la consulta SQL para realizar la búsqueda
                $sql = "SELECT * FROM productos 
                        WHERE (id = '{$search}' 
                        OR nombre LIKE '%{$search}%' 
                        OR marca LIKE '%{$search}%' 
                        OR detalles LIKE '%{$search}%') 
                        AND eliminado = 0";
    
                // Ejecutamos la consulta
                if ($result = $this->conexion->query($sql)) {
                    // Obtenemos todos los resultados
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
    
                    if (!empty($rows)) {
                        // Mapeamos los resultados y los convertimos a UTF-8
                        foreach ($rows as $num => $row) {
                            foreach ($row as $key => $value) {
                                $this->response[$num][$key] = utf8_encode($value);
                            }
                        }
                    }
    
                    // Liberamos los resultados de la consulta
                    $result->free();
                } else {
                    $this->response['message'] = "Query Error: " . mysqli_error($this->conexion);
                }
    
                // Cerramos la conexión
                $this->conexion->close();
            } else {
                $this->response['message'] = 'No se recibió información para buscar';
            }
        }
    
        public function single($string = _GET['id']){
            // Inicializamos el arreglo para almacenar los datos
            $this->response = array(
                'status'  => 'error',
                'message' => 'No se encontró el producto'
            );
    
            // Verificamos si se ha recibido un ID por GET
            if (isset($string)) {
                $id = intval($string);  // Asegurarse de que el ID sea un entero para evitar inyecciones SQL
    
                // Realizamos la consulta SQL para buscar el producto por ID
                $sql = "SELECT * FROM productos WHERE id = {$id} AND eliminado = 0";
                if ($result = $this->conexion->query($sql)) {
                    // Comprobamos si la consulta trajo algún resultado
                    if ($result->num_rows > 0) {
                        $producto = $result->fetch_assoc();
    
                        // Convertimos los valores a UTF-8 y guardamos el producto en el array
                        foreach($producto as $key => $value) {
                            $this->response['producto'][$key] = utf8_encode($value);
                        }
                        $this->response['status'] = 'success';
                        $this->response['message'] = 'Producto encontrado';
                    } else {
                        $this->response['message'] = 'Producto no encontrado o eliminado';
                    }
                    $result->free();
                } else {
                    $this->response['message'] = "Error en la consulta: " . mysqli_error($this->conexion);
                }
    
                // Cerramos la conexión a la base de datos
                $this->conexion->close();
            } else {
                $this->response['message'] = 'No se proporcionó un ID';
            }
        }
    
    
        public function singleByName($string= _GET['name']){
            // Inicializamos el arreglo para almacenar los datos
            $this->response = array(
                'status'  => 'error',
                'message' => 'No se encontró el producto'
            );
    
            // Verificamos si se ha recibido un ID por GET
            if (isset($string)) {
                // Realizamos la consulta SQL para buscar el producto por ID
                $sql = "SELECT * FROM productos WHERE name = {$string} AND eliminado = 0";
                if ($result = $this->conexion->query($sql)) {
                    // Comprobamos si la consulta trajo algún resultado
                    if ($result->num_rows > 0) {
                        $producto = $result->fetch_assoc();
    
                        // Convertimos los valores a UTF-8 y guardamos el producto en el array
                        foreach($producto as $key => $value) {
                            $this->response['producto'][$key] = utf8_encode($value);
                        }
                        $this->response['status'] = 'success';
                        $this->response['message'] = 'Producto encontrado';
                    } else {
                        $this->response['message'] = 'Producto no encontrado o eliminado';
                    }
                    $result->free();
                } else {
                    $this->response['message'] = "Error en la consulta: " . mysqli_error($this->conexion);
                }
    
                // Cerramos la conexión a la base de datos
                $this->conexion->close();
            } else {
                $this->response['message'] = 'No se proporcionó un ID';
            }
        }

    }
?>
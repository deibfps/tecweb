<?php
    namespace TECWEB\MYAPI\READ;

    use TECWEB\MYAPI\DataBase;
    require_once __DIR__.'/../DataBase.php';

    class Read extends DataBase{
        
        public function __construct($db) {
            parent::__construct($db);
        }

        public function list(){
            $this->response = array();
    
            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            if ( $result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0") ) {
                // SE OBTIENEN LOS RESULTADOS
                $rows = $result->fetch_all(MYSQLI_ASSOC);
        
                if(!is_null($rows)) {
                    // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                    foreach($rows as $num => $row) {
                        foreach($row as $key => $value) {
                            $this->response[$num][$key] = utf8_encode($value);
                        }
                    }
                }
                $result->free();
            } else {
                die('Query Error: '.mysqli_error($this->conexion));
            }
            $this->conexion->close();
    
        }
    
        public function search($string = _GET['search']){
            // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
            $this->response = array();
            // SE VERIFICA HABER RECIBIDO EL ID
            if( isset($string) ) {
                $search = $string;
                // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
                $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
                if ( $result = $this->conexion->query($sql) ) {
                    // SE OBTIENEN LOS RESULTADOS
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
    
                    if(!is_null($rows)) {
                        // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                        foreach($rows as $num => $row) {
                            foreach($row as $key => $value) {
                                $this->response[$num][$key] = utf8_encode($value);
                            }
                        }
                    }
                    $result->free();
                } else {
                    die('Query Error: '.mysqli_error($this->conexion));
                }
                $this->conexion->close();
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
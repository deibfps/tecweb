<?php
namespace TECWEB\MYAPI;

use TECWEB\MYAPI\DataBase;
require_once __DIR__.'/DataBase.php';

class Products extends DataBase{
    private $response = [];
    
    public function __construct($user = 'root', $pass = 'Alaskita123', $db = 'marketzone') {
        parent::__construct($user, $pass, $db);
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

    public function delete($string = _GET['id']){
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

    public function getData(){
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }


}
?>
<?php
namespace tecweb\MyApi;

use tecweb\MyApi\DataBase;

require_once 'DataBase.php';

class Products extends DataBase{
    private $response = [];
    
    public function __construct($db, $user = 'root', $pass = 'distrito123') {
        parent::__construct($db, $user, $pass);
    }

    public function add($producto){
        // Inicializamos la respuesta de error
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
                // Si no existe el producto, se inserta
                $this->conexion->set_charset("utf8");
                $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                        VALUES ('{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
                if($this->conexion->query($sql)){
                    // Se actualiza la respuesta de éxito
                    $this->response['status'] =  "success";
                    $this->response['message'] =  "Producto agregado correctamente";
                } else {
                    $this->response['message'] = "ERROR: No se ejecutó la consulta $sql. " . mysqli_error($this->conexion);
                }
            }
            
            // Liberar el recurso de la consulta
            $result->free();
        }
    
        // Cerrar la conexión
        $this->conexion->close();
    
        // Retornamos la respuesta al final
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
    
    // Método para eliminar un producto
    public function delete($id) {
        $this->response = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );

        // Verificamos si el ID no está vacío
        if (!empty($id)) {
            // Hacemos la consulta para actualizar el estado de "eliminado"
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
            if ($this->conexion->query($sql)) {
                $this->response['status'] = "success";
                $this->response['message'] = "Producto eliminado";
            } else {
                $this->response['message'] = "ERROR: No se ejecutó la consulta $sql. " . mysqli_error($this->conexion);
            }
        }

        // Cerramos la conexión
        $this->conexion->close();
    }

    // Método para editar un producto
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

    // Método para obtener la lista de productos
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

    // Método para realizar la búsqueda de productos
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

    public function getData(){
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }

}   
?>
<?php 
namespace TECWEB\MYAPI;
use TECWEB\MYAPI\DataBase;
require_once __DIR__ . '/DataBase.php';

class Products extends DataBase {

    private $response;

    public function __construct($db, $user = 'root', $pass = 'distrito123') {
        $this -> response = '';
        parent::__construct ($db, $user, $pass);
    }

    public function add($producto) {
        // Inicializar la respuesta predeterminada
        $data = array(
            'status' => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );

        // Verificar si el producto ya existe en la base de datos
        $sql = "SELECT * FROM productos WHERE nombre = '{$producto['nombre']}' AND eliminado = 0";
        $result = $this->conexion->query($sql);

        if ($result->num_rows == 0) {
            $this->conexion->set_charset("utf8");
            $sql = "INSERT INTO productos (id, nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                    VALUES (null, '{$producto['nombre']}', '{$producto['marca']}', '{$producto['modelo']}', {$producto['precio']}, 
                    '{$producto['detalles']}', {$producto['unidades']}, '{$producto['imagen']}', 0)";
            
            if ($this->conexion->query($sql)) {
                $data['status'] = "success";
                $data['message'] = "Producto agregado";
            } else {
                $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($this->conexion);
            }
        }
        $result->free();
        $this->conexion->close();
        $this->response = $data;
    }


    public function delete($id) {
        $data = array(
            'status' => 'error',
            'message' => 'Producto no encontrado'
        );
        $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
        if ($this->conexion->query($sql)) {
            $data['status'] = 'success';
            $data['message'] = 'Producto eliminado';
        } else {
            $data['message'] = 'ERROR: No se ejecutó ' . $sql . '. ' . mysqli_error($this->conexion);
        }
        $this->response = $data;
        $this->conexion->close();
    }

    public function edit($jsonOBJ) {
        $data = array(
            'status' => 'error',
            'message' => 'Datos incompletos o inválidos'
        );

        if (!empty($jsonOBJ)) {
            // Asegurarse de que los campos necesarios existen
            if (isset($jsonOBJ->id) && isset($jsonOBJ->nombre)) {
                $id = $jsonOBJ->id;
                $nombre = $jsonOBJ->nombre;

                // Verificar si el nombre del producto ya existe (excluyendo el producto actual)
                $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND id != {$id} AND eliminado = 0";
                $result = $this->conexion->query($sql);

                if ($result->num_rows == 0) {
                    $this->conexion->set_charset("utf8");

                    // Actualizar el producto en la base de datos
                    $sql = "UPDATE productos SET 
                        nombre = '{$nombre}', 
                        marca = '{$jsonOBJ->marca}', 
                        modelo = '{$jsonOBJ->modelo}', 
                        precio = {$jsonOBJ->precio}, 
                        detalles = '{$jsonOBJ->detalles}', 
                        unidades = {$jsonOBJ->unidades}, 
                        imagen = '{$jsonOBJ->imagen}' 
                        WHERE id = {$id}";

                    if ($this->conexion->query($sql) === TRUE) {
                        $data['status'] = "success";
                        $data['message'] = "Producto editado exitosamente";
                    } else {
                        $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($this->conexion);
                    }
                } else {
                    $data['message'] = "Ya existe un producto con ese nombre";
                }

                $result->free();
            }
        }
        // Responder con el estado de la operación
        $this->response = $data;
    }

    public function list() {
        $data = array();
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            // Obtener los resultados
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if (!is_null($rows)) {
                // Mapear los resultados al arreglo de respuesta
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . mysqli_error($this->conexion));
        }
        $this->response = $data;
    }


    public function search($search) {
        $data = array();
        $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
        
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $data[$num][$key] = utf8_encode($value);
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . mysqli_error($this->conexion));
        }
        // Asignar los resultados a la propiedad de respuesta
        $this->response = $data;
    }


    public function single($id) {
        $data = array();

        // Evitar inyección de SQL con parámetros preparados
        $query = "SELECT * FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $id);  // 'i' indica que el parámetro es un entero
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            // Obtener los resultados y mapearlos al arreglo de respuesta
            if ($row = $result->fetch_assoc()) {
                $data = array(
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'marca' => $row['marca'],
                    'modelo' => $row['modelo'],
                    'precio' => $row['precio'],
                    'detalles' => $row['detalles'],
                    'unidades' => $row['unidades'],
                    'imagen' => $row['imagen']
                );
            }
            $result->free();
        } else {
            die('Query fallida');
        }

        // Asignar los resultados a la propiedad de respuesta
        $this->response = $data;
    }

    public function getByName($nombre) {
        $data = array();
        $query = "SELECT * FROM productos WHERE nombre = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('s', $nombre);  
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $data = array(
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'marca' => $row['marca'],
                    'modelo' => $row['modelo'],
                    'precio' => $row['precio'],
                    'detalles' => $row['detalles'],
                    'unidades' => $row['unidades'],
                    'imagen' => $row['imagen']
                );
            }
            $result->free();
        } else {
            die('Query fallida');
        }
        $this->response = $data;
    }

    public function getData() {
        return json_encode($this->response); 
    }
    
}
?>
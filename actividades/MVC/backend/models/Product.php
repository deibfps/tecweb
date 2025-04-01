<?php
namespace TECWEB\MYAPI;
require_once __DIR__ . '/../config/database.php';

class Product extends Database {
    private $response;

    public function __construct() {
        parent::__construct();
        $this->response = [];
    }

    // Método para agregar un producto
    public function add($producto) {
        $sql = "SELECT * FROM productos WHERE nombre = '{$producto['nombre']}' AND eliminado = 0";
        $result = $this->conexion->query($sql);

        if ($result->num_rows == 0) {
            $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                    VALUES ('{$producto['nombre']}', '{$producto['marca']}', '{$producto['modelo']}', {$producto['precio']}, 
                    '{$producto['detalles']}', {$producto['unidades']}, '{$producto['imagen']}', 0)";
            
            $this->response = ($this->conexion->query($sql)) ?
                ['status' => 'success', 'message' => 'Producto agregado'] :
                ['status' => 'error', 'message' => 'No se pudo agregar el producto'];
        } else {
            $this->response = ['status' => 'error', 'message' => 'Ya existe un producto con ese nombre'];
        }
        $result->free();
    }

    // Método para eliminar un producto
    public function delete($id) {
        $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
        $this->response = ($this->conexion->query($sql)) ?
            ['status' => 'success', 'message' => 'Producto eliminado'] :
            ['status' => 'error', 'message' => 'No se pudo eliminar el producto'];
    }

    // Método para editar un producto
    public function edit($producto) {
        if (!isset($producto->id, $producto->nombre)) {
            $this->response = ['status' => 'error', 'message' => 'Datos incompletos o inválidos'];
            return;
        }

        $sql = "SELECT * FROM productos WHERE nombre = '{$producto->nombre}' AND id != {$producto->id} AND eliminado = 0";
        $result = $this->conexion->query($sql);

        if ($result->num_rows == 0) {
            $sql = "UPDATE productos SET 
                        nombre = '{$producto->nombre}', 
                        marca = '{$producto->marca}', 
                        modelo = '{$producto->modelo}', 
                        precio = {$producto->precio}, 
                        detalles = '{$producto->detalles}', 
                        unidades = {$producto->unidades}, 
                        imagen = '{$producto->imagen}' 
                        WHERE id = {$producto->id}";

            $this->response = ($this->conexion->query($sql)) ?
                ['status' => 'success', 'message' => 'Producto editado'] :
                ['status' => 'error', 'message' => 'Error al editar el producto'];
        } else {
            $this->response = ['status' => 'error', 'message' => 'Ya existe un producto con ese nombre'];
        }
    }

    // Método para listar los productos
    public function list() {
        $result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0");
        $this->response = ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Método para buscar productos por nombre o detalles
    public function search($search) {
        $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
        $result = $this->conexion->query($sql);
        $this->response = ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Método para obtener un solo producto por ID
    public function single($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->response = $result->fetch_assoc() ?: [];
    }

    // Método para obtener un producto por nombre
    public function getByName($nombre) {
        $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE nombre = ?");
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->response = $result->fetch_assoc() ?: [];
    }

    // Método para devolver la respuesta en formato JSON
    public function getData() {
        echo json_encode($this->response);
    }
}
?>

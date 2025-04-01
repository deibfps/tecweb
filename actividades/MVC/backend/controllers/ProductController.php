<?php
require_once '../models/Product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new \TECWEB\MYAPI\Product();
    }

    public function handleRequest() {
        // Obtener la acción desde el parámetro GET
        $action = $_GET['action'] ?? '';
        $id = $_GET['id'] ?? null;
        $search = $_GET['search'] ?? null;
        $data = json_decode(file_get_contents("php://input"));

        switch ($action) {
            case 'list':
                $this->productModel->list(); // Llama al método list() del modelo
                break;
            case 'single':
                $this->productModel->single($id);
                break;
            case 'add':
                $this->productModel->add((array) $data);
                break;
            case 'edit':
                $this->productModel->edit($data);
                break;
            case 'delete':
                $this->productModel->delete($id);
                break;
            case 'search':
                $this->productModel->search($search);
                break;
            case 'name':
                $this->productModel->getByName($search);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
                return;
        }
        // Devuelve los datos en formato JSON
        $this->productModel->getData();
    }
}

// Crear una instancia del controlador y manejar la solicitud
$controller = new ProductController();
$controller->handleRequest();
?>

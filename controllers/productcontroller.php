<?php
// Incluir el modelo de Producto
include '../models/Product.php';

// Función para obtener todos los productos
function getProducts() {
    $product = new Product();
    $products = $product->getAll();
    
    // Retornar los productos en formato JSON
    echo json_encode($products);
}

// Función para agregar un producto
function addProduct($name, $description, $quantity, $price) {
    $product = new Product();
    if ($product->add($name, $description, $quantity, $price)) {
        echo json_encode(['message' => 'Producto agregado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al agregar el producto']);
    }
}

// Función para actualizar un producto
function updateProduct($id, $name, $description, $quantity, $price) {
    $product = new Product();
    if ($product->update($id, $name, $description, $quantity, $price)) {
        echo json_encode(['message' => 'Producto actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el producto']);
    }
}

// Función para eliminar un producto
function deleteProduct($id) {
    $product = new Product();
    if ($product->delete($id)) {
        echo json_encode(['message' => 'Producto eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el producto']);
    }
}

// Función para buscar productos (por nombre, por ejemplo)
function searchProduct($searchTerm) {
    $product = new Product();
    $products = $product->search($searchTerm);
    
    echo json_encode($products);
}

// Comprobamos si el método de la solicitud es GET, POST, PUT o DELETE
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        // Si hay un parámetro de búsqueda
        if (isset($_GET['search'])) {
            searchProduct($_GET['search']);
        } else {
            getProducts();
        }
        break;
    
    case 'POST':
        // Agregar un nuevo producto
        $data = json_decode(file_get_contents('php://input'), true);
        addProduct($data['name'], $data['description'], $data['quantity'], $data['price']);
        break;
    
    case 'PUT':
        // Actualizar un producto existente
        $data = json_decode(file_get_contents('php://input'), true);
        updateProduct($data['id'], $data['name'], $data['description'], $data['quantity'], $data['price']);
        break;
    
    case 'DELETE':
        // Eliminar un producto
        $data = json_decode(file_get_contents('php://input'), true);
        deleteProduct($data['id']);
        break;
    
    default:
        // Método no permitido
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>

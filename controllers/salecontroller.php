<?php
// Incluir el modelo de Venta y Producto
include '../models/Sale.php';
include '../models/Product.php';

// Función para registrar una venta
function registerSale($productId, $quantity, $totalPrice) {
    $sale = new Sale();
    $product = new Product();
    
    // Verificar si hay suficiente stock para la venta
    $productDetails = $product->getById($productId);
    if ($productDetails && $productDetails['quantity'] >= $quantity) {
        // Registrar la venta
        $saleId = $sale->addSale($productId, $quantity, $totalPrice);

        // Actualizar el stock del producto
        $newStock = $productDetails['quantity'] - $quantity;
        $product->updateStock($productId, $newStock);
        
        echo json_encode(['message' => 'Venta registrada exitosamente', 'sale_id' => $saleId]);
    } else {
        echo json_encode(['error' => 'No hay suficiente stock para realizar la venta']);
    }
}

// Función para obtener el historial de ventas
function getSalesHistory() {
    $sale = new Sale();
    $sales = $sale->getAllSales();
    
    echo json_encode($sales);
}

// Función para obtener detalles de una venta específica
function getSaleDetails($saleId) {
    $sale = new Sale();
    $saleDetails = $sale->getSaleById($saleId);
    
    echo json_encode($saleDetails);
}

// Comprobamos el método de la solicitud (GET, POST)
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        // Si se pasa un parámetro 'saleId', obtenemos los detalles de esa venta
        if (isset($_GET['saleId'])) {
            getSaleDetails($_GET['saleId']);
        } else {
            getSalesHistory();
        }
        break;

    case 'POST':
        // Registrar una nueva venta
        $data = json_decode(file_get_contents('php://input'), true);
        registerSale($data['productId'], $data['quantity'], $data['totalPrice']);
        break;
    
    default:
        // Método no permitido
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>

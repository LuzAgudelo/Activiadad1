<?php
// Incluir los modelos necesarios
include '../models/Sale.php';
include '../models/Product.php';

// Función para generar un reporte de ventas
function generateSalesReport($startDate, $endDate) {
    $sale = new Sale();
    $sales = $sale->getSalesByDateRange($startDate, $endDate);

    if (count($sales) > 0) {
        echo json_encode(['sales' => $sales, 'total_sales' => array_sum(array_column($sales, 'total_price'))]);
    } else {
        echo json_encode(['message' => 'No se encontraron ventas en este rango de fechas']);
    }
}

// Función para generar un reporte de stock
function generateStockReport() {
    $product = new Product();
    $products = $product->getAll();

    $report = [];
    foreach ($products as $productDetails) {
        $report[] = [
            'product_id' => $productDetails['id'],
            'product_name' => $productDetails['name'],
            'stock_quantity' => $productDetails['quantity'],
            'price' => $productDetails['price'],
        ];
    }

    echo json_encode(['stock_report' => $report]);
}

// Función para generar un reporte de productos con bajo inventario
function generateLowStockReport($threshold = 10) {
    $product = new Product();
    $lowStockProducts = $product->getLowStockProducts($threshold);

    if (count($lowStockProducts) > 0) {
        echo json_encode(['low_stock_products' => $lowStockProducts]);
    } else {
        echo json_encode(['message' => 'No hay productos con bajo inventario']);
    }
}

// Comprobamos el método de la solicitud (GET)
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        // Generar un reporte de ventas entre dos fechas
        if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
            generateSalesReport($_GET['startDate'], $_GET['endDate']);
        }
        // Generar un reporte de stock
        elseif (isset($_GET['stock'])) {
            generateStockReport();
        }
        // Generar un reporte de productos con bajo inventario
        elseif (isset($_GET['lowStock'])) {
            $threshold = isset($_GET['threshold']) ? $_GET['threshold'] : 10;
            generateLowStockReport($threshold);
        }
        break;

    default:
        // Método no permitido
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>

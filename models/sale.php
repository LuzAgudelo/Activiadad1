<?php
// Incluir la conexión a la base de datos
include '../config/db.php';

class Sale {

    // Función para agregar una nueva venta
    public function addSale($productId, $quantity, $totalPrice) {
        global $conn;
        
        // Comenzamos la transacción
        $conn->begin_transaction();

        try {
            // Insertar la venta en la tabla de ventas
            $sql = "INSERT INTO sales (product_id, quantity, total_price, sale_date) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iii', $productId, $quantity, $totalPrice);
            $stmt->execute();
            $saleId = $stmt->insert_id;

            // Actualizar el stock del producto
            $product = new Product();
            $productDetails = $product->getById($productId);
            $newStock = $productDetails['quantity'] - $quantity;
            $product->updateStock($productId, $newStock);

            // Confirmar la transacción
            $conn->commit();
            return $saleId;
        } catch (Exception $e) {
            // En caso de error, revertir la transacción
            $conn->rollback();
            throw $e;
        }
    }

    // Función para obtener el historial completo de ventas
    public function getAllSales() {
        global $conn;
        $sql = "SELECT s.id, s.product_id, s.quantity, s.total_price, s.sale_date, p.name as product_name
                FROM sales s
                JOIN products p ON s.product_id = p.id
                ORDER BY s.sale_date DESC";
        $result = $conn->query($sql);

        $sales = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sales[] = $row;
            }
        }
        return $sales;
    }

    // Función para obtener detalles de una venta específica
    public function getSaleById($saleId) {
        global $conn;
        $sql = "SELECT s.id, s.product_id, s.quantity, s.total_price, s.sale_date, p.name as product_name
                FROM sales s
                JOIN products p ON s.product_id = p.id
                WHERE s.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $saleId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Función para obtener las ventas realizadas en un rango de fechas
    public function getSalesByDateRange($startDate, $endDate) {
        global $conn;
        $sql = "SELECT s.id, s.product_id, s.quantity, s.total_price, s.sale_date, p.name as product_name
                FROM sales s
                JOIN products p ON s.product_id = p.id
                WHERE s.sale_date BETWEEN ? AND ?
                ORDER BY s.sale_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $sales = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sales[] = $row;
            }
        }
        return $sales;
    }
}
?>

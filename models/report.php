<?php
// Incluir la conexión a la base de datos
include '../config/db.php';

class Report {

    // Función para generar un reporte de ventas en un rango de fechas
    public function generateSalesReport($startDate, $endDate) {
        global $conn;
        $sql = "SELECT s.sale_date, p.name as product_name, s.quantity, s.total_price
                FROM sales s
                JOIN products p ON s.product_id = p.id
                WHERE s.sale_date BETWEEN ? AND ?
                ORDER BY s.sale_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $salesReport = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $salesReport[] = $row;
            }
        }
        return $salesReport;
    }

    // Función para generar un reporte de stock (productos con bajo inventario)
    public function generateStockReport($threshold) {
        global $conn;
        $sql = "SELECT id, name, quantity, price
                FROM products
                WHERE quantity <= ?
                ORDER BY quantity ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $threshold);
        $stmt->execute();
        $result = $stmt->get_result();

        $stockReport = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $stockReport[] = $row;
            }
        }
        return $stockReport;
    }

    // Función para generar un reporte de ventas diarias (un reporte por cada día)
    public function generateDailySalesReport($date) {
        global $conn;
        $sql = "SELECT s.sale_date, p.name as product_name, s.quantity, s.total_price
                FROM sales s
                JOIN products p ON s.product_id = p.id
                WHERE DATE(s.sale_date) = ?
                ORDER BY s.sale_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $dailySalesReport = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dailySalesReport[] = $row;
            }
        }
        return $dailySalesReport;
    }

    // Función para generar un reporte mensual de ventas
    public function generateMonthlySalesReport($month, $year) {
        global $conn;
        $sql = "SELECT s.sale_date, p.name as product_name, s.quantity, s.total_price
                FROM sales s
                JOIN products p ON s.product_id = p.id
                WHERE MONTH(s.sale_date) = ? AND YEAR(s.sale_date) = ?
                ORDER BY s.sale_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $month, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        $monthlySalesReport = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $monthlySalesReport[] = $row;
            }
        }
        return $monthlySalesReport;
    }

    // Función para generar un reporte de ingresos por ventas
    public function generateSalesIncomeReport($startDate, $endDate) {
        global $conn;
        $sql = "SELECT SUM(total_price) as total_income
                FROM sales
                WHERE sale_date BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Función para obtener un resumen de inventario (total de productos, total de valor de inventario)
    public function generateInventorySummaryReport() {
        global $conn;
        $sql = "SELECT SUM(quantity) as total_products, SUM(quantity * price) as total_inventory_value
                FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>

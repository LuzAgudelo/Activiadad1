<?php
// Incluir la conexión a la base de datos
include '../config/db.php';

class Product {

    // Función para obtener todos los productos
    public function getAll() {
        global $conn;
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        $products = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // Función para obtener un producto por su ID
    public function getById($id) {
        global $conn;
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Función para agregar un producto al inventario
    public function add($name, $description, $quantity, $price) {
        global $conn;
        $sql = "INSERT INTO products (name, description, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssii', $name, $description, $quantity, $price);
        return $stmt->execute();
    }

    // Función para actualizar un producto existente
    public function update($id, $name, $description, $quantity, $price) {
        global $conn;
        $sql = "UPDATE products SET name = ?, description = ?, quantity = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssiii', $name, $description, $quantity, $price, $id);
        return $stmt->execute();
    }

    // Función para eliminar un producto
    public function delete($id) {
        global $conn;
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Función para buscar productos por nombre
    public function search($searchTerm) {
        global $conn;
        $sql = "SELECT * FROM products WHERE name LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // Función para obtener los productos con bajo inventario (por debajo de un umbral)
    public function getLowStockProducts($threshold) {
        global $conn;
        $sql = "SELECT * FROM products WHERE quantity <= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $threshold);
        $stmt->execute();
        $result = $stmt->get_result();

        $lowStockProducts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lowStockProducts[] = $row;
            }
        }
        return $lowStockProducts;
    }

    // Función para actualizar la cantidad de un producto en el inventario
    public function updateStock($id, $newStock) {
        global $conn;
        $sql = "UPDATE products SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $newStock, $id);
        return $stmt->execute();
    }
}
?>

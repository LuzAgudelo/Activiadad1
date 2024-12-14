<?php
// Conexión a la base de datos
include('../config/db.php'); // Asegúrate de que la conexión se realiza correctamente

// Función para agregar un producto
function addProduct($name, $description, $quantity, $price) {
    global $db; // Usamos la conexión de la base de datos
    // Preparamos la consulta para insertar el producto
    $query = "INSERT INTO products (name, description, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssii', $name, $description, $quantity, $price); // Vinculamos los parámetros

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Producto agregado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al agregar el producto']);
    }

    $stmt->close(); // Cerramos la consulta
}

// Comprobamos el tipo de solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibimos los datos del producto
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificamos si los datos existen
    if (isset($data['name'], $data['description'], $data['quantity'], $data['price'])) {
        addProduct($data['name'], $data['description'], $data['quantity'], $data['price']);
    } else {
        echo json_encode(['error' => 'Faltan datos']);
    }
}
?>

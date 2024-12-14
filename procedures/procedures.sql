-- Procedimiento para agregar un nuevo producto al inventario
DELIMITER //

CREATE PROCEDURE AddProduct(
    IN p_name VARCHAR(255),
    IN p_description TEXT,
    IN p_price DECIMAL(10,2),
    IN p_quantity INT
)
BEGIN
    INSERT INTO products (name, description, price, quantity, status)
    VALUES (p_name, p_description, p_price, p_quantity, 'active');
END //

-- Procedimiento para actualizar la información de un producto existente
CREATE PROCEDURE UpdateProduct(
    IN p_product_id INT,
    IN p_name VARCHAR(255),
    IN p_description TEXT,
    IN p_price DECIMAL(10,2),
    IN p_quantity INT
)
BEGIN
    UPDATE products
    SET name = p_name,
        description = p_description,
        price = p_price,
        quantity = p_quantity
    WHERE product_id = p_product_id;
END //

-- Procedimiento para procesar una venta y ajustar el stock
CREATE PROCEDURE ProcessSale(
    IN p_product_id INT,
    IN p_quantity INT,
    IN p_total_price DECIMAL(10,2)
)
BEGIN
    DECLARE current_stock INT;
    
    -- Obtener el stock actual del producto
    SELECT quantity INTO current_stock
    FROM products
    WHERE product_id = p_product_id;
    
    -- Verificar si hay suficiente stock
    IF current_stock >= p_quantity THEN
        -- Insertar la venta en la tabla de ventas
        INSERT INTO sales (product_id, quantity, total_price, sale_date)
        VALUES (p_product_id, p_quantity, p_total_price, NOW());
        
        -- Actualizar el stock después de la venta
        UPDATE products
        SET quantity = quantity - p_quantity
        WHERE product_id = p_product_id;

        -- Verificar si el producto tiene bajo inventario y actualizar su estado si es necesario
        IF (current_stock - p_quantity) < 5 THEN
            -- Si el stock es bajo, marcar el producto como 'bajo stock'
            INSERT INTO low_stock_alerts (product_id, alert_date)
            VALUES (p_product_id, NOW());
        END IF;
    ELSE
        -- Si no hay suficiente stock, lanzar un error
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No hay suficiente stock para completar la venta';
    END IF;
END //

-- Procedimiento para generar un reporte de stock de productos
CREATE PROCEDURE GenerateStockReport()
BEGIN
    SELECT product_id, name, quantity, price
    FROM products
    ORDER BY quantity DESC;
END //

-- Procedimiento para generar un reporte de ventas en un rango de fechas
CREATE PROCEDURE GenerateSalesReport(
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    SELECT s.sale_id, p.name AS product_name, s.quantity, s.total_price, s.sale_date
    FROM sales s
    JOIN products p ON s.product_id = p.product_id
    WHERE s.sale_date BETWEEN p_start_date AND p_end_date
    ORDER BY s.sale_date;
END //

-- Procedimiento para generar un reporte de productos con bajo inventario
CREATE PROCEDURE GenerateLowStockReport()
BEGIN
    SELECT p.product_id, p.name, p.quantity
    FROM products p
    WHERE p.quantity < 5
    ORDER BY p.quantity;
END //

DELIMITER ;

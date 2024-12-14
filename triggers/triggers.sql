-- Trigger para alertar sobre productos con bajo inventario
DELIMITER //

CREATE TRIGGER after_stock_update
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    -- Si el stock del producto baja de 5, insertamos una alerta en la tabla low_stock_alerts
    IF NEW.quantity < 5 AND OLD.quantity >= 5 THEN
        INSERT INTO low_stock_alerts (product_id, alert_date)
        VALUES (NEW.product_id, NOW());
    END IF;
END //

-- Trigger para registrar cambios en el stock tras cada transacción de venta
CREATE TRIGGER after_sale
AFTER INSERT ON sales
FOR EACH ROW
BEGIN
    -- Actualizamos la cantidad de stock del producto después de una venta
    UPDATE products
    SET quantity = quantity - NEW.quantity
    WHERE product_id = NEW.product_id;

    -- Si el stock después de la venta es bajo (menos de 5), se genera una alerta
    IF (SELECT quantity FROM products WHERE product_id = NEW.product_id) < 5 THEN
        INSERT INTO low_stock_alerts (product_id, alert_date)
        VALUES (NEW.product_id, NOW());
    END IF;
END //

-- Trigger para actualizar automáticamente el estado del producto a 'inactivo' cuando el stock sea 0
CREATE TRIGGER update_product_status_on_zero_stock
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    -- Si el stock llega a 0, actualizamos el estado del producto a 'inactivo'
    IF NEW.quantity = 0 THEN
        UPDATE products
        SET status = 'inactive'
        WHERE product_id = NEW.product_id;
    END IF;
END //

DELIMITER ;

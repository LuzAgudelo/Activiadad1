-- Habilitar el uso de eventos en MySQL (si aún no está habilitado)
SET GLOBAL event_scheduler = ON;

-- Evento para verificar productos con bajo inventario (diariamente)
CREATE EVENT check_low_inventory
ON SCHEDULE EVERY 1 DAY
STARTS '2024-12-11 00:00:00'
DO
BEGIN
    -- Insertar una alerta para productos con stock por debajo de 5 unidades
    INSERT INTO low_stock_alerts (product_id, alert_date)
    SELECT product_id, NOW()
    FROM products
    WHERE quantity < 5 AND status = 'active';
END //

-- Evento para generar un reporte de ventas semanal
CREATE EVENT generate_weekly_sales_report
ON SCHEDULE EVERY 1 WEEK
STARTS '2024-12-11 00:00:00'
DO
BEGIN
    -- Llamar a un procedimiento almacenado que genere el reporte de ventas
    CALL GenerateSalesReport(
        DATE_SUB(CURDATE(), INTERVAL 7 DAY), -- Fecha de inicio de la semana
        CURDATE() -- Fecha final (hoy)
    );
END //

-- Evento para limpiar registros obsoletos de productos (mensual)
CREATE EVENT clean_old_products
ON SCHEDULE EVERY 1 MONTH
STARTS '2024-12-11 00:00:00'
DO
BEGIN
    -- Eliminar productos que no se han vendido en los últimos 6 meses
    DELETE FROM products
    WHERE product_id NOT IN (
        SELECT DISTINCT product_id
        FROM sales
        WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    ) AND status = 'inactive';
END //

DELIMITER ;

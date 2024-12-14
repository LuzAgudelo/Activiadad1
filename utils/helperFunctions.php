<?php

/**
 * Función para validar si un número es un valor válido para un precio
 * 
 * @param mixed $value El valor a validar
 * @return bool Retorna true si el valor es un precio válido, de lo contrario false
 */
function isValidPrice($value) {
    return is_numeric($value) && $value >= 0;
}

/**
 * Función para validar si una cantidad es válida (entero positivo)
 * 
 * @param mixed $value El valor a validar
 * @return bool Retorna true si la cantidad es válida, de lo contrario false
 */
function isValidQuantity($value) {
    return is_int($value) && $value >= 0;
}

/**
 * Función para dar formato al precio con dos decimales
 * 
 * @param float $price El precio a formatear
 * @return string El precio formateado como cadena
 */
function formatPrice($price) {
    return number_format($price, 2, '.', ',');
}

/**
 * Función para validar si un valor es un texto no vacío
 * 
 * @param mixed $value El valor a validar
 * @return bool Retorna true si el valor es un texto válido (no vacío), de lo contrario false
 */
function isValidText($value) {
    return !empty($value) && is_string($value);
}

/**
 * Función para generar una alerta de error personalizada
 * 
 * @param string $message El mensaje de error
 * @return string El HTML de la alerta
 */
function generateErrorAlert($message) {
    return "<div class='alert alert-danger' role='alert'>$message</div>";
}

/**
 * Función para generar una alerta de éxito personalizada
 * 
 * @param string $message El mensaje de éxito
 * @return string El HTML de la alerta
 */
function generateSuccessAlert($message) {
    return "<div class='alert alert-success' role='alert'>$message</div>";
}

/**
 * Función para obtener la fecha actual formateada
 * 
 * @return string La fecha actual formateada en 'Y-m-d'
 */
function getCurrentDate() {
    return date('Y-m-d');
}

/**
 * Función para obtener la hora actual formateada
 * 
 * @return string La hora actual formateada en 'H:i:s'
 */
function getCurrentTime() {
    return date('H:i:s');
}

/**
 * Función para calcular el total de una venta (precio * cantidad)
 * 
 * @param float $price El precio del producto
 * @param int $quantity La cantidad de productos vendidos
 * @return float El total de la venta
 */
function calculateTotal($price, $quantity) {
    return $price * $quantity;
}

/**
 * Función para generar un reporte en formato HTML
 * 
 * @param array $data Los datos del reporte
 * @param array $headers Las cabeceras del reporte
 * @return string El HTML de la tabla con los datos del reporte
 */
function generateHtmlReport($data, $headers) {
    $html = "<table class='table table-bordered'>";
    
    // Agregar cabeceras
    $html .= "<thead><tr>";
    foreach ($headers as $header) {
        $html .= "<th>$header</th>";
    }
    $html .= "</tr></thead>";

    // Agregar filas de datos
    $html .= "<tbody>";
    foreach ($data as $row) {
        $html .= "<tr>";
        foreach ($row as $cell) {
            $html .= "<td>$cell</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</tbody>";

    $html .= "</table>";

    return $html;
}

/**
 * Función para verificar si un producto tiene bajo inventario (menos de 5 unidades)
 * 
 * @param int $quantity La cantidad de stock de un producto
 * @return bool Retorna true si el inventario está bajo, de lo contrario false
 */
function isLowStock($quantity) {
    return $quantity < 5;
}

/**
 * Función para formatear una fecha en el formato 'd/m/Y'
 * 
 * @param string $date La fecha a formatear
 * @return string La fecha formateada
 */
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

/**
 * Función para enviar un correo electrónico (utilizado para notificaciones)
 * 
 * @param string $to El destinatario del correo
 * @param string $subject El asunto del correo
 * @param string $message El cuerpo del mensaje
 * @return bool Retorna true si el correo fue enviado correctamente, de lo contrario false
 */
function sendEmail($to, $subject, $message) {
    $headers = "From: no-reply@miempresa.com\r\n";
    $headers .= "Reply-To: soporte@miempresa.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($to, $subject, $message, $headers);
}

?>

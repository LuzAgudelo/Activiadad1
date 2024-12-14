-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-12-2024 a las 01:36:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarProducto` (IN `p_id_producto` INT, IN `p_nombre` VARCHAR(255), IN `p_descripcion` TEXT, IN `p_precio` DECIMAL(10,2), IN `p_stock` INT, IN `p_stock_minimo` INT, IN `p_id_categoria` INT, IN `p_id_proveedor` INT, OUT `p_Actualizado` TINYINT(1))   BEGIN
    -- Actualización de los datos del producto
    UPDATE productos 
    SET 
        nombre = p_nombre, 
        descripcion = p_descripcion, 
        precio = p_precio, 
        stock = p_stock, 
        stock_minimo = p_stock_minimo, 
        id_categoria = p_id_categoria, 
        id_proveedor = p_id_proveedor
    WHERE id_producto = p_id_producto;
    
    -- Verificar si se actualizó algún registro
    IF ROW_COUNT() > 0 THEN
        SET p_Actualizado = 1;  -- Se actualizó el producto
    ELSE
        SET p_Actualizado = 0; -- No se actualizó ningún producto
    END IF;
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Electrónica', 'Productos electrónicos como teléfonos, computadoras, etc.'),
(2, 'Ropa', 'Prendas de vestir para todas las edades'),
(3, 'Alimentos', 'Productos alimenticios y bebidas'),
(4, 'Muebles', 'Artículos de mobiliario para el hogar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'Juan Pérez', '555-9876543', 'juanperez@example.com', 'Av. Siempre Viva 123'),
(2, 'Maria González', '555-8765432', 'maria@example.com', 'Calle Ficticia 456'),
(3, 'Carlos Sánchez', '555-7654321', 'carlos@example.com', 'Calle del Sol 789');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `id_inventario` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventarios`
--

INSERT INTO `inventarios` (`id_inventario`, `id_producto`, `cantidad`, `tipo`, `fecha`) VALUES
(1, 1, 100, 'entrada', '2024-12-11 14:35:06'),
(2, 2, 50, 'entrada', '2024-12-11 14:35:06'),
(3, 3, 200, 'entrada', '2024-12-11 14:35:06'),
(4, 4, 150, 'entrada', '2024-12-11 14:35:06'),
(5, 5, 500, 'entrada', '2024-12-11 14:35:06'),
(6, 6, 300, 'entrada', '2024-12-11 14:35:06'),
(7, 7, 80, 'entrada', '2024-12-11 14:35:06'),
(8, 8, 40, 'entrada', '2024-12-11 14:35:06'),
(9, 1, 10, 'salida', '2024-12-11 14:35:06'),
(10, 2, 5, 'salida', '2024-12-11 14:35:06'),
(11, 3, 20, 'salida', '2024-12-11 14:35:06'),
(12, 4, 15, 'salida', '2024-12-11 14:35:06'),
(13, 5, 50, 'salida', '2024-12-11 14:35:06'),
(14, 6, 30, 'salida', '2024-12-11 14:35:06'),
(15, 7, 8, 'salida', '2024-12-11 14:35:06'),
(16, 8, 4, 'salida', '2024-12-11 14:35:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `stock_minimo` int(11) DEFAULT 5,
  `id_categoria` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `stock_minimo`, `id_categoria`, `id_proveedor`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'Smartphone XYZ', 'Smartphone de última generación con cámara de 48MP', 499.99, 100, 10, 1, 1, '2024-12-11 14:35:06', '2024-12-11 14:35:06'),
(2, 'Laptop ABC', 'Laptop ultradelgada con 16GB de RAM y 512GB SSD', 799.99, 50, 5, 1, 1, '2024-12-11 14:35:06', '2024-12-11 14:35:06'),
(3, 'Nuevo Producto', 'Descripción del nuevo producto', 120.50, 150, 10, 2, 1, '2024-12-11 14:35:06', '2024-12-11 17:32:37'),
(4, 'Pantalón Jeans', 'Pantalón de mezclilla para hombres', 39.99, 150, 15, 2, 2, '2024-12-11 14:35:06', '2024-12-11 14:35:06'),
(5, 'Cereal de Avena', 'Cereal saludable de avena con frutos secos', 4.99, 500, 50, 3, 3, '2024-12-11 14:35:06', '2024-12-11 14:35:06'),
(6, 'Jugo Natural', 'Jugo 100% natural de naranja', 2.99, 300, 30, 3, 3, '2024-12-11 14:35:06', '2024-12-11 14:35:06'),
(7, 'Silla Ejecutiva', 'Silla ergonómica para oficina', 129.99, 80, 8, 4, 4, '2024-12-11 14:35:06', '2024-12-11 14:35:06'),
(8, 'Mesa de Comedor', 'Mesa de comedor de madera para 6 personas', 299.99, 40, 4, 4, 4, '2024-12-11 14:35:06', '2024-12-11 14:35:06'),
(9, '', NULL, NULL, 0, 5, NULL, NULL, '2024-12-11 16:33:19', '2024-12-11 16:33:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre`, `telefono`, `email`) VALUES
(1, 'TechPro', '555-1234567', 'contacto@techpro.com'),
(2, 'FashionWear', '555-2345678', 'ventas@fashionwear.com'),
(3, 'GourmetFoods', '555-3456789', 'info@gourmetfoods.com'),
(4, 'HomeFurnish', '555-4567890', 'soporte@homefurnish.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_venta` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `fecha_venta`, `total`) VALUES
(1, 1, '2024-12-11 14:35:06', 599.99),
(2, 2, '2024-12-11 14:35:06', 89.98),
(3, 3, '2024-12-11 14:35:06', 429.98);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `inventarios_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

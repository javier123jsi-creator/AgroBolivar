-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2026 a las 02:46:40
-- Versión del servidor: 10.11.16-MariaDB
-- Versión de PHP: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agrobolivar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `id_productor` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `nombre_visitante` varchar(100) NOT NULL,
  `correo_visitante` varchar(100) DEFAULT NULL,
  `telefono_visitante` varchar(20) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id_mensaje`, `id_productor`, `id_producto`, `nombre_visitante`, `correo_visitante`, `telefono_visitante`, `mensaje`, `fecha_envio`) VALUES
(1, 1, 1, 'Fabian Samboni', 'fabian1@gmail.com', '3143252652', 'Estoy interesado en el productor favor contactarme para negociar', '2026-11-11 03:15:36'),
(2, 3, 4, 'Melisa Perez', 'melisa@gmail.com', '314325266', 'Favor separarme 5 docenas', '2026-11-11 03:47:02'),
(3, 4, 5, 'Luisa Fernandez', 'luisa@gmail.com', '3143258745', 'Estoy interesada en su productor, favor contactarme para que me haga el favor de separar 10 kilos', '2026-11-11 04:05:27'),
(4, 5, 6, 'Javier Samboni', 'javi@gmail.com', '3143040567', 'Buena tarde estoy interesado en su producto, le ofrezco un buen precio de $230.000 por arroba, quedo atento a su contacto.\r\nGracias', '2026-11-11 04:32:28'),
(5, 5, 7, 'Pedro Anacona', 'pedrito@gmail.com', '3256879456', 'Estoy interesado en su productor, favor llamame', '2026-11-11 04:49:14'),
(6, 6, 8, 'Javier Samboni', 'javier123.jsi@gmail.com', '3143040567', 'Buena noches, estoy interesado en comprar tu producto, favor contactarme\r\n\r\nmuchas gracias, quedo atento', '2026-11-12 03:31:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productores`
--

CREATE TABLE `productores` (
  `id_productor` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `vereda` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `contrasena` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productores`
--

INSERT INTO `productores` (`id_productor`, `nombres`, `apellidos`, `telefono`, `correo`, `vereda`, `descripcion`, `fecha_registro`, `contrasena`) VALUES
(1, 'Juan Carlos', 'Samboni Imbachi', '3148821604', 'juancarlos12@gmail.com', 'Vereda Rodrigo', 'Agricultor de Cafe', '2026-11-11 03:00:34', NULL),
(2, 'Liliana', 'Samboni Imbachi', '3125586624', 'liliana@gmail.com', 'Vereda Rodrigo', 'Soy Agricultura de caña de azucar', '2026-11-11 03:40:23', NULL),
(3, 'Camilo', 'Samboni', '3152356242', 'camilo@gmail.com', 'Vereda Rodrigo', 'Agricultor de lulo', '2026-11-11 03:44:34', NULL),
(4, 'Carlos', 'Muñoz', '3245256142', 'caritos@gmail.com', 'El Carmen', 'Agricultor de Fresa', '2026-11-11 03:55:50', '$2y$12$lfKjJnnecryb3IXfkq0clueOddxYGLC5mxhuOhDD5zv9FDrwFgw0y'),
(5, 'Yuri', 'Papamija', '3218466776', 'yuri@gmail.com', 'Mazamorras', 'Café Castilla', '2026-11-11 04:28:18', '$2y$12$re2c1O0CMGhDfoz3VODnIui8hDuw0BtzDVXKjn03PcULhDCAGs1ke'),
(6, 'Ana Juliana', 'Pino', '3003079618', 'anajulia@gmail.com', 'El agrado', 'Productora de Café', '2026-11-12 03:26:20', '$2y$12$nSFvxM6bFRjLhi8Jk9u/nOU0MOIZk8OQya7vbY3E8u4ph707FZV/e');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_productor` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `categoria` varchar(80) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `disponibilidad` varchar(50) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_publicacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_productor`, `nombre_producto`, `categoria`, `descripcion`, `precio`, `disponibilidad`, `imagen`, `fecha_publicacion`) VALUES
(1, 1, 'Cafe', 'Café', 'Café Castilla', 220000.00, 'Disponible', NULL, '2026-11-11 03:08:13'),
(2, 1, 'Platano', 'Plátano', 'Plátano Hartón verde', 50000.00, 'Disponible', 'imagenes/976a5348ed2c8ee01242627b6d413473.jpg', '2026-11-11 03:25:22'),
(3, 2, 'Panela', 'Otros', 'Panela de Caña Tradicional – Calidad Premium Estabilizada', 5000.00, 'Disponible', 'imagenes/e5f0c014574c4d644ae4dbad3b71b881.jpg', '2026-11-11 03:42:28'),
(4, 3, 'Lulo', 'Frutas', 'Lulo fresco de Castilla, recién cosechado.', 3000.00, 'Disponible', 'imagenes/90a6b200e9dbb4d351a8a62d79c7383e.jpg', '2026-11-11 03:45:59'),
(5, 4, 'Fresa', 'Frutas', 'Fresa muy fresca', 2000.00, 'Disponible', 'imagenes/1a3ada7bb0235aced806189bbbe3ad2d.jpg', '2026-11-11 04:04:08'),
(6, 5, 'Café', 'Café', 'Café Castilla', 22000.00, 'Disponible', 'imagenes/655ecac40488cd9a8be10d994d6961f5.jpg', '2026-11-11 04:31:01'),
(7, 5, 'Arveja Verde', 'Otros', 'Arveja', 8000.00, 'Disponible', 'imagenes/ae3a97414d6df55d1b901ce4f59c4a57.jpg', '2026-11-11 04:39:32'),
(8, 6, 'Cafe', 'Café', 'Café Variedad', 220000.00, 'Disponible', 'imagenes/d28b60512b5515def83c1e085824304b.jpg', '2026-11-12 03:30:08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_productor` (`id_productor`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productores`
--
ALTER TABLE `productores`
  ADD PRIMARY KEY (`id_productor`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_productor` (`id_productor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productores`
--
ALTER TABLE `productores`
  MODIFY `id_productor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_productor`) REFERENCES `productores` (`id_productor`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_productor`) REFERENCES `productores` (`id_productor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-07-2025 a las 05:59:30
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
-- Base de datos: `biblioteca_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `cedula` varchar(64) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `telefono` varchar(64) NOT NULL,
  `correo` varchar(128) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) NOT NULL COMMENT '1=activo, 0=inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `cedula`, `nombre`, `telefono`, `correo`, `fecha_creacion`, `estado`) VALUES
(1, '117840141', 'Jeison Cliente 2', '123', 'em.jeison@gmail.com', '2025-07-15 20:10:10', 1),
(2, '108500160', 'MAURICIO RAFAEL SOTO RODRIGUEZ', '2222', 'm.jeison117@outlook.es', '2025-07-15 20:20:43', 1),
(3, '115500155', 'DIEGO ALEJANDRO ALFARO ALFARO', '123', '123@outlook.es', '2025-07-15 20:53:36', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id` int(11) NOT NULL,
  `codigo` varchar(128) DEFAULT NULL,
  `nombre` varchar(256) NOT NULL,
  `autor` varchar(128) NOT NULL,
  `genero` varchar(64) NOT NULL,
  `anno` varchar(4) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=inactivo, 1=disponible, 2=reservado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`id`, `codigo`, `nombre`, `autor`, `genero`, `anno`, `fecha_creacion`, `estado`) VALUES
(1, '123sa', '1984 LIBRO ESTRELLA', 'George Orwell', 'Distopía', '1949', '2025-06-24 20:39:09', 2),
(2, NULL, 'El Principito', 'Antoine de Saint-Exupéry', 'Fantasía', '1943', '2025-06-24 20:39:09', 1),
(3, 'asd1233', 'Cien años de soledad', 'Gabriel García Márquez', 'Realismo mágico', '1967', '2025-06-24 20:39:09', 1),
(4, NULL, 'Orgullo y prejuicio', 'Jane Austen', 'Romance', '1813', '2025-06-24 20:39:09', 1),
(5, NULL, 'El gran Gatsby', 'F. Scott Fitzgerald', 'Ficción', '1925', '2025-06-24 20:39:09', 2),
(6, NULL, 'Crimen y castigo parte 3', 'Fiódor Dostoyevski', 'Filosófico', '1974', '2025-06-24 20:39:09', 1),
(7, NULL, 'El viejo y el mar', 'Ernest Hemingway', 'Aventura', '1952', '2025-06-24 20:39:09', 2),
(8, NULL, 'Test', 'Juan', 'Detective', '1941', '2025-06-24 21:14:23', 0),
(9, NULL, 'test2', 'Juan2', 'Detective2', '2025', '2025-06-24 21:16:04', 0),
(10, NULL, 'Libro de cuentos', 'Juan2', 'Detective2', '2025', '2025-06-24 21:23:16', 2),
(11, NULL, 'jeison m', 'asdasd', 'Detective3', '2025', '2025-06-24 21:23:39', 1),
(12, NULL, 'jeison m', 'asdasd', 'Detective3', '2025', '2025-06-24 21:24:01', 1),
(13, NULL, 'jeison m', 'asdasd', 'Detective3', '2025', '2025-06-24 21:24:10', 1),
(14, '123as', 'Aventuras de Jeison Mena', 'Jeison Mena', 'Accion', '1908', '2025-06-24 21:41:58', 2),
(15, 'test123', 'asda', '1312', '123sads', '2012', '2025-07-29 20:57:20', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `fecha_devolucion` date NOT NULL,
  `fecha_devuelto` datetime DEFAULT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '1=activo, 0=devuelto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id`, `libro_id`, `cliente_id`, `fecha_reserva`, `fecha_devolucion`, `fecha_devuelto`, `estado`) VALUES
(1, 14, 1, '2025-07-30 00:00:00', '2025-08-08', NULL, 1),
(2, 1, 1, '2025-07-30 05:50:34', '2025-07-31', NULL, 1),
(3, 15, 1, '2025-07-30 05:51:30', '2025-07-31', NULL, 1),
(4, 5, 1, '2025-07-30 05:52:12', '2025-08-02', NULL, 1),
(5, 10, 2, '2025-07-30 05:55:01', '2025-08-02', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `correo` varchar(128) NOT NULL,
  `contra` varchar(64) NOT NULL,
  `cedula` varchar(128) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(1) NOT NULL DEFAULT 1 COMMENT '1=activo, 0=inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `correo`, `contra`, `cedula`, `fecha_creacion`, `estado`) VALUES
(1, 'JEISON EDUARDO MENA MARIN', 'jeison@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '117840141', '2025-06-17 21:06:20', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

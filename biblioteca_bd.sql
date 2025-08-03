-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-08-2025 a las 01:50:41
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
  `cedula` int(11) NOT NULL,
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
(1, 503720273, 'DANIEL ANTONIO SEQUEIRA GAITAN', '60577713', 'danielsequeira22@gmail.com', '2025-08-02 21:04:22', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
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
(1, 0, '1984 LIBRO ESTRELLA', 'George Orwell', 'Distopía', '1949', '2025-06-24 20:39:09', 2),
(2, 0, 'El Principito', 'Antoine de Saint-Exupéry', 'Fantasía', '1943', '2025-06-24 20:39:09', 1),
(3, 0, 'Cien años de soledad', 'Gabriel García Márquez', 'Realismo mágico', '1967', '2025-06-24 20:39:09', 1),
(4, 0, 'Orgullo y prejuicio', 'Jane Austen', 'Romance', '1813', '2025-06-24 20:39:09', 1),
(5, 0, 'El gran Gatsby', 'F. Scott Fitzgerald', 'Ficción', '1925', '2025-06-24 20:39:09', 1),
(6, 0, 'Crimen y castigo parte 3', 'Fiódor Dostoyevski', 'Filosófico', '1974', '2025-06-24 20:39:09', 1),
(7, 0, 'El viejo y el mar', 'Ernest Hemingway', 'Aventura', '1952', '2025-06-24 20:39:09', 2),
(8, 0, 'Test', 'Juan', 'Detective', '1941', '2025-06-24 21:14:23', 0),
(9, 0, 'test2', 'Juan2', 'Detective2', '2025', '2025-06-24 21:16:04', 0),
(10, 0, 'Libro de cuentos', 'Juan2', 'Detective2', '2025', '2025-06-24 21:23:16', 1),
(11, 0, 'jeison m', 'asdasd', 'Detective3', '2025', '2025-06-24 21:23:39', 1),
(12, 0, 'jeison m', 'asdasd', 'Detective3', '2025', '2025-06-24 21:24:01', 1),
(13, 0, 'jeison m', 'asdasd', 'Detective3', '2025', '2025-06-24 21:24:10', 1),
(14, 0, 'Aventuras de Jeison Mena', 'Jeison Mena', 'Accion', '1908', '2025-06-24 21:41:58', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `fecha_devolucion` datetime NOT NULL,
  `fecha_devuelto` datetime DEFAULT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '1=activo, 0=devuelto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id`, `libro_id`, `cliente_id`, `fecha_reserva`, `fecha_devolucion`, `fecha_devuelto`, `estado`) VALUES
(1, 1, 1, '2025-08-03 05:04:51', '2025-08-04 00:00:00', NULL, 1);

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
(1, 'Jeison Mena Marin', 'jeison@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '12345678', '2025-06-17 21:06:20', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

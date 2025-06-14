-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2025 a las 23:24:45
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
-- Base de datos: `paqueteria`
--

CREATE DATABASE paqueteria;
USE paqueteria;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `telefono` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nombre`, `rfc`, `telefono`) VALUES
(1, 'Luis Fernández', 'FERL780102T23', '5512345678'),
(2, 'María Gómez', 'GOMM850611H45', '5587654321'),
(3, 'Carlos Ruiz', 'RUIC900720P89', '5523456789'),
(4, 'Ana Torres', 'TOAA820503K11', '5545678901'),
(5, 'Jorge Salinas', 'SAJJ950201R55', '5534567890'),
(6, 'Christopher Rodriguez', 'ROSC030312NC3', '2282825818'),
(7, 'Analine Salazar', 'BEMZ860627DQA', '2281648135'),
(9, 'Carlos Galan', 'RNNV940701VX4', '2284030331');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destino`
--

CREATE TABLE `destino` (
  `iddestino` int(11) NOT NULL,
  `codPostal` varchar(5) NOT NULL,
  `colonia` varchar(50) NOT NULL,
  `calle` varchar(50) NOT NULL,
  `num` varchar(50) NOT NULL,
  `cliente_idcliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `destino`
--

INSERT INTO `destino` (`iddestino`, `codPostal`, `colonia`, `calle`, `num`, `cliente_idcliente`) VALUES
(1, '06700', 'Roma Norte', 'Durango', '45-B', 1),
(2, '02840', 'San Álvaro', 'Cañitas', '12', 2),
(3, '76030', 'Centro', '5 de Mayo', '100', 3),
(4, '50120', 'La Magdalena', 'Libertad', '33A', 4),
(5, '44100', 'Americana', 'Av. Vallarta', '1999', 5),
(6, '91143', 'Framboyanes', 'Naranjas', '3-B', 6),
(7, '91103', 'Lucas Martin', 'Colegio Preparatorio', '1', 7),
(9, '91700', 'Ricardo Flores Magón', 'Avila Camacho', '1263', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `idpaquetes` int(11) NOT NULL,
  `numGuia` varchar(10) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `peso` float NOT NULL,
  `alto` float NOT NULL,
  `ancho` float NOT NULL,
  `profundidad` float NOT NULL,
  `repartidor_idrepartidor` int(11) NOT NULL,
  `destino_iddestino` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`idpaquetes`, `numGuia`, `descripcion`, `peso`, `alto`, `ancho`, `profundidad`, `repartidor_idrepartidor`, `destino_iddestino`) VALUES
(1, 'X3Z8A1B7C9', 'Ropa deportiva', 2.5, 30, 25, 15, 1, 3),
(2, 'A1B2C3D4E5', 'Juguetes varios', 5, 40, 30, 20, 2, 2),
(3, 'F6G7H8I9J0', 'Libros usados', 4.3, 28, 20, 10, 4, 1),
(4, 'K9L8M7N6O5', 'Electrónica', 3.8, 35, 25, 18, 3, 5),
(5, 'P1Q2R3S4T5', 'Calzado', 2, 25, 20, 12, 5, 4),
(6, 'U6V7W8X9Y0', 'Herramientas', 7.2, 50, 40, 30, 1, 2),
(7, 'Z1A2B3C4D5', 'Documentos', 1, 10, 8, 2, 2, 1),
(8, 'E6F7G8H9I0', 'Medicamentos', 1.2, 12, 10, 4, 3, 3),
(9, 'J1K2L3M4N5', 'Accesorios auto', 6.5, 45, 35, 25, 5, 5),
(10, 'O6P7Q8R9S0', 'Artículos cocina', 3, 33, 28, 20, 4, 4),
(11, 'T1U2V3W4X5', 'Peluches', 2.3, 26, 24, 16, 1, 1),
(12, 'Y6Z7A8B9C0', 'Perfumes', 1.5, 15, 10, 8, 2, 5),
(13, 'D1E2F3G4H5', 'Ropa interior', 1.8, 20, 18, 12, 3, 2),
(20, 'W2U1K8O8R6', 'Audifonos', 1.2, 20, 15, 10, 4, 2),
(21, 'Q7O6K9H2M2', 'Pantalones', 2, 12, 15, 24, 6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repartidor`
--

CREATE TABLE `repartidor` (
  `idrepartidor` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `repartidor`
--

INSERT INTO `repartidor` (`idrepartidor`, `nombre`) VALUES
(1, 'Ramiro Méndez'),
(2, 'Elena Rivas'),
(3, 'José Martínez'),
(4, 'Sandra López'),
(5, 'Diego Varela'),
(6, 'Citlalli Davila'),
(7, 'Angel González');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD UNIQUE KEY `rfc_UNIQUE` (`rfc`);

--
-- Indices de la tabla `destino`
--
ALTER TABLE `destino`
  ADD PRIMARY KEY (`iddestino`),
  ADD UNIQUE KEY `cliente_idcliente_UNIQUE` (`cliente_idcliente`),
  ADD KEY `fk_destino_cliente1_idx` (`cliente_idcliente`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`idpaquetes`,`repartidor_idrepartidor`,`destino_iddestino`),
  ADD UNIQUE KEY `numGuia_UNIQUE` (`numGuia`),
  ADD KEY `fk_paquetes_repartidor_idx` (`repartidor_idrepartidor`),
  ADD KEY `fk_paquetes_destino1_idx` (`destino_iddestino`);

--
-- Indices de la tabla `repartidor`
--
ALTER TABLE `repartidor`
  ADD PRIMARY KEY (`idrepartidor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `destino`
--
ALTER TABLE `destino`
  MODIFY `iddestino` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `idpaquetes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `repartidor`
--
ALTER TABLE `repartidor`
  MODIFY `idrepartidor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `destino`
--
ALTER TABLE `destino`
  ADD CONSTRAINT `fk_destino_cliente1` FOREIGN KEY (`cliente_idcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD CONSTRAINT `fk_paquetes_destino1` FOREIGN KEY (`destino_iddestino`) REFERENCES `destino` (`iddestino`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_paquetes_repartidor` FOREIGN KEY (`repartidor_idrepartidor`) REFERENCES `repartidor` (`idrepartidor`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

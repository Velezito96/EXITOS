-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-05-2025 a las 09:12:40
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `exitosde_saber`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `idCargo` int(11) NOT NULL,
  `Descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificado`
--

CREATE TABLE `certificado` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `nota` int(11) NOT NULL,
  `numHoras` int(11) NOT NULL,
  `fechaEntrega` date DEFAULT NULL,
  `codRegistro` char(10) NOT NULL,
  `CodQr` varchar(20) NOT NULL,
  `urls` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `certificado`
--

INSERT INTO `certificado` (`id`, `idCliente`, `idCurso`, `fechaInicio`, `fechaFin`, `nota`, `numHoras`, `fechaEntrega`, `codRegistro`, `CodQr`, `urls`) VALUES
(1, 2, 1, '2022-09-12', '2022-11-11', 16, 100, '2025-04-25', '12345678', 'eds12345678', 'www.exitosSaber'),
(2, 2, 2, '0000-00-00', '0000-00-00', 0, 0, NULL, '', '', 'www'),
(3, 6, 3, '0000-00-00', '0000-00-00', 0, 0, NULL, '', '', 'www.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL,
  `tipoCliente` char(1) NOT NULL COMMENT '1:Natural; 2:Jurídico',
  `tipoIdentificacion` char(20) DEFAULT NULL,
  `numeroDNI` char(8) DEFAULT NULL,
  `apellidosNombres` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` char(9) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `tipoCliente`, `tipoIdentificacion`, `numeroDNI`, `apellidosNombres`, `direccion`, `telefono`, `email`) VALUES
(2, '', 'DNI', '12345678', 'Alarcon Villalobos', 'Juan', '925485258', 'fasdff@gmail.com'),
(3, '', '', '', 'Alarcon Villalobos', 'Juan', '9', 'sad'),
(4, '', '', '', 'Alarcon Villalobos', 'Juan', '8', 'sdf'),
(5, '', '', '', 'Alarcon Villalobos', 'Juan', '5', 'adsf'),
(6, '', '', '12345670', 'perez', 'neiler', '123456789', 'hola'),
(7, '', '', '', '', '', '', ''),
(8, '', '', '', '', '', '', ''),
(9, '', '', '', '', '', '', ''),
(10, '', '', '', '', '', '', 'asdfasdfasdfasdf@hasdfa'),
(11, '', '', '', '', '', '', ''),
(12, '', '', '', 'Alarcon Villalobos', 'Neiler', '999999999', '132456888'),
(13, '', '', '', '', '', '', ''),
(14, '', '', '', '', '', '', ''),
(15, '', '', '', '', '', '', ''),
(16, '', '', '', '', '', '', ''),
(17, '', '', '', 'Alarcon Villalobos', 'Juan', '', ''),
(18, '', '', '', '', 'Juan', '', ''),
(19, '', '', '', '', 'Juan', '', ''),
(20, '', '', '', 'adfa', 'Juan', '', ''),
(21, '', '', '', '', 'adfd', '', ''),
(22, '', '', '', '', 'adfdddddd', '', ''),
(23, '', '', '', '', '', '', ''),
(24, '', '', '', '', '', '', ''),
(25, '', '', '', '', '', '', ''),
(26, '', '', '', '', '', '', ''),
(27, '', '', '', '', '', '', ''),
(28, '', '', '', '', '', '', 'jkljl'),
(29, '', '', '', '', '', '', ''),
(30, '', '', '', 'Alarcon Villalobos', 'Juan', '654123', 'dadfsf'),
(31, '', '', '', '', '', '', ''),
(32, '', '', '', '', '', '', ''),
(33, '', '', '', '', '', '', ''),
(34, '', '', '', '', '', '', ''),
(35, '', '', '', '', '', '', ''),
(36, '', '', '', '', '', '', ''),
(37, '', '', '', '', '', '', ''),
(38, '', '', '', '', '', '', ''),
(39, '', '', '', '', '', '', ''),
(40, '', '', '', 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(41, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(42, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(43, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(44, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(45, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(46, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '142345678', 'dsafsdf@gmail.com'),
(47, '1', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(48, '2', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com'),
(49, '2', NULL, NULL, '', '', '', ''),
(50, '1', NULL, NULL, '', '', '', ''),
(51, '1', NULL, NULL, '', '', '', ''),
(52, '1', NULL, NULL, '', '', '', ''),
(53, '1', NULL, NULL, '', '', '', ''),
(54, '2', NULL, NULL, '', '', '', ''),
(55, '1', NULL, NULL, '', '', '', ''),
(56, '1', NULL, NULL, '', '', '', ''),
(57, '2', NULL, NULL, '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `idCurso` int(11) NOT NULL,
  `nombreCurso` varchar(150) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `imagen` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idCurso`, `nombreCurso`, `descripcion`, `imagen`) VALUES
(1, 'Matemática', 'Inscríbete e ingresa al mundo mágico de los números.dsafasfasdfsaf\r\ndsafasdfdsaf', 'Herramientas digitales.png'),
(2, 'Reparación de celulares', '', NULL),
(3, 'Ofimática', '', NULL),
(4, 'Desarrollo de Sistemas', '', NULL),
(5, 'Electricidad', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `idDepartamento` int(11) NOT NULL,
  `Departamento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalledocventa`
--

CREATE TABLE `detalledocventa` (
  `idDetDocVenta` int(11) NOT NULL,
  `idDocVenta` int(11) NOT NULL,
  `cantidad` double NOT NULL,
  `idProducto` int(11) NOT NULL,
  `PrecioDescuento` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distrito`
--

CREATE TABLE `distrito` (
  `idDistrito` int(11) NOT NULL,
  `idProvincia` int(11) NOT NULL,
  `Distrito` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentoventa`
--

CREATE TABLE `documentoventa` (
  `idDocVenta` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idTrabajador` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Total` double NOT NULL,
  `estado` char(1) NOT NULL COMMENT '1:Activo; 2:Anulado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas`
--

CREATE TABLE `ofertas` (
  `idOfertas` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `descuento` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  `idOfertas` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `precio` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `idProvincia` int(11) NOT NULL,
  `idDepartamento` int(11) NOT NULL,
  `provincia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `idServicios` int(11) NOT NULL,
  `servicio` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`idServicios`, `servicio`, `descripcion`) VALUES
(1, 'Supermercado', 'Realiza tu pedido, confirma tu pago y tu pedido llegará a tu casa.'),
(2, 'Educación continua', 'Elige el curso de tu preferencia y serás acreedor de tu certificado'),
(3, 'Servicios', 'Te ofrecemos servicios de calidad. Elige el que cubra tu necesidad.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `idSolicitud` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `fecha` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`idSolicitud`, `idCliente`, `idCurso`, `fecha`, `estado`) VALUES
(1, 2, 1, 2025, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador`
--

CREATE TABLE `trabajador` (
  `idTrabajador` int(11) NOT NULL,
  `idCargo` int(11) NOT NULL,
  `DNI` char(8) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `Nombres` varchar(50) NOT NULL,
  `dirección` varchar(50) NOT NULL,
  `estadoCivil` char(1) NOT NULL COMMENT '1:Soltero; 2:Casado; 3:Conviviente',
  `NumHijos` char(1) NOT NULL,
  `Estado` int(11) NOT NULL COMMENT '1:Trabajador; 2:No trabajador'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`idCargo`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `certificado`
--
ALTER TABLE `certificado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idCurso` (`idCurso`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`idCurso`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`idDepartamento`);

--
-- Indices de la tabla `detalledocventa`
--
ALTER TABLE `detalledocventa`
  ADD PRIMARY KEY (`idDetDocVenta`),
  ADD KEY `idDocVenta` (`idDocVenta`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD PRIMARY KEY (`idDistrito`),
  ADD KEY `idProvincia` (`idProvincia`);

--
-- Indices de la tabla `documentoventa`
--
ALTER TABLE `documentoventa`
  ADD PRIMARY KEY (`idDocVenta`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idTrabajador` (`idTrabajador`);

--
-- Indices de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`idOfertas`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD KEY `idCategoria` (`idCategoria`),
  ADD KEY `idOfertas` (`idOfertas`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`idProvincia`),
  ADD KEY `idDepartamento` (`idDepartamento`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idServicios`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`idSolicitud`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`idTrabajador`),
  ADD KEY `idCargo` (`idCargo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `idCargo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `certificado`
--
ALTER TABLE `certificado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idCurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `idDepartamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalledocventa`
--
ALTER TABLE `detalledocventa`
  MODIFY `idDetDocVenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `distrito`
--
ALTER TABLE `distrito`
  MODIFY `idDistrito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentoventa`
--
ALTER TABLE `documentoventa`
  MODIFY `idDocVenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `idOfertas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `idProvincia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `idServicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `idSolicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `idTrabajador` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalledocventa`
--
ALTER TABLE `detalledocventa`
  ADD CONSTRAINT `detalledocventa_ibfk_1` FOREIGN KEY (`idDocVenta`) REFERENCES `documentoventa` (`idDocVenta`),
  ADD CONSTRAINT `detalledocventa_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`);

--
-- Filtros para la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD CONSTRAINT `distrito_ibfk_2` FOREIGN KEY (`idProvincia`) REFERENCES `provincia` (`idProvincia`);

--
-- Filtros para la tabla `documentoventa`
--
ALTER TABLE `documentoventa`
  ADD CONSTRAINT `documentoventa_ibfk_1` FOREIGN KEY (`idTrabajador`) REFERENCES `trabajador` (`idTrabajador`),
  ADD CONSTRAINT `documentoventa_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`idOfertas`) REFERENCES `ofertas` (`idOfertas`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`);

--
-- Filtros para la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD CONSTRAINT `provincia_ibfk_1` FOREIGN KEY (`idDepartamento`) REFERENCES `departamento` (`idDepartamento`);

--
-- Filtros para la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD CONSTRAINT `trabajador_ibfk_1` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

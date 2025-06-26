-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2025 a las 01:11:49
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
-- Estructura de tabla para la tabla `alternativas`
--

CREATE TABLE `alternativas` (
  `id` int(11) NOT NULL,
  `idPregunta` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `es_correcta` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `idAsistencia` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `presente` tinyint(1) DEFAULT 0,
  `tipo` enum('asistio','tardanza','justificada','falta') DEFAULT 'falta',
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`idAsistencia`, `idCliente`, `idCurso`, `fecha`, `presente`, `tipo`, `descripcion`) VALUES
(1, 78, 1, '2025-06-15', 0, 'falta', NULL),
(2, 78, 2, '2025-06-15', 0, 'falta', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `idCargo` int(11) NOT NULL,
  `Descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`idCargo`, `Descripcion`) VALUES
(1, 'Administrador'),
(2, 'Trabajador'),
(3, 'Estudiante'),
(4, 'Apoderado');

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
-- Estructura de tabla para la tabla `chat_curso`
--

CREATE TABLE `chat_curso` (
  `id` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `chat_curso`
--

INSERT INTO `chat_curso` (`id`, `idCurso`, `idUsuario`, `mensaje`, `fecha`) VALUES
(1, 1, 79, 'hola', '2025-06-24 17:49:42');

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
  `email` varchar(50) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `idCargo` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `tipoCliente`, `tipoIdentificacion`, `numeroDNI`, `apellidosNombres`, `direccion`, `telefono`, `email`, `usuario`, `clave`, `idCargo`, `foto`) VALUES
(2, '', 'DNI', '12345678', 'Alarcon Villalobos', 'Juan', '925485258', 'fasdff@gmail.com', 'user1', '123', 1, NULL),
(3, '', '', '', 'Alarcon Villalobos', 'Juan', '9', 'sad', NULL, NULL, NULL, NULL),
(4, '', '', '', 'Alarcon Villalobos', 'Juan', '8', 'sdf', NULL, NULL, NULL, NULL),
(5, '', '', '', 'Alarcon Villalobos', 'Juan', '5', 'adsf', NULL, NULL, NULL, NULL),
(6, '', '', '12345670', 'perez', 'neiler', '123456789', 'hola', NULL, NULL, NULL, NULL),
(7, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(8, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(9, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(10, '', '', '', '', '', '', 'asdfasdfasdfasdf@hasdfa', NULL, NULL, NULL, NULL),
(11, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(12, '', '', '', 'Alarcon Villalobos', 'Neiler', '999999999', '132456888', NULL, NULL, NULL, NULL),
(13, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(14, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(15, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(16, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(17, '', '', '', 'Alarcon Villalobos', 'Juan', '', '', NULL, NULL, NULL, NULL),
(18, '', '', '', '', 'Juan', '', '', NULL, NULL, NULL, NULL),
(19, '', '', '', '', 'Juan', '', '', NULL, NULL, NULL, NULL),
(20, '', '', '', 'adfa', 'Juan', '', '', NULL, NULL, NULL, NULL),
(21, '', '', '', '', 'adfd', '', '', NULL, NULL, NULL, NULL),
(22, '', '', '', '', 'adfdddddd', '', '', NULL, NULL, NULL, NULL),
(23, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(24, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(25, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(26, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(27, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(28, '', '', '', '', '', '', 'jkljl', NULL, NULL, NULL, NULL),
(29, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(30, '', '', '', 'Alarcon Villalobos', 'Juan', '654123', 'dadfsf', NULL, NULL, NULL, NULL),
(31, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(32, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(33, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(34, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(35, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(36, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(37, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(38, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(39, '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(40, '', '', '', 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(41, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(42, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(43, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(44, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(45, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(46, '', NULL, NULL, 'Alarcon Villalobos', 'Juan', '142345678', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(47, '1', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(48, '2', NULL, NULL, 'Alarcon Villalobos', 'Juan', '123456789', 'dsafsdf@gmail.com', NULL, NULL, NULL, NULL),
(49, '2', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(50, '1', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(51, '1', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(52, '1', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(53, '1', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(54, '2', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(55, '1', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(56, '1', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(57, '2', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL),
(59, '', NULL, NULL, '', '', '', '', 'alumno01', '$2y$10$5wPSDZjIiokUDUmHbrSMreF08xDPiWPc5UBoTQlUTh1lMWLys96X2', 3, NULL),
(63, '', NULL, NULL, '', '', '', '', 'admin', '$2y$10$Y1GZVtYXqR.zfQkRzI6FqOAyqMWtqiM6EJS5n0TA.tXB0gnkpTAaK', 1, NULL),
(64, '', NULL, NULL, '', '', '', '', 'nuevo_estudiante', '$2y$10$2AqRmZUp2NrcjHZpVfsxuOb7CS.UGc5w2qOTCjv6pZGd5eH9UGaGa', 3, NULL),
(65, '', NULL, NULL, '', '', '', '', 'admin01', '$2y$10$crPdIfJSXLzDb6Q8jCrV5e7b9AXRYP.zd9AoqRIj20iJeVeJZnMyS', 1, NULL),
(66, '', NULL, NULL, '', '', '', '', 'empleado01', '$2y$10$iOrrByvByNXISqLagMfNJeJvXU9rVD2UOo72cIzmpvNh9soEk6OEy', 2, NULL),
(68, '', NULL, NULL, '', '', '', '', 'apoderado01', '$2y$10$LcG.2vh3bx7Mrp3nCxswgeDHZalyKyQ/XtKXaVABY.dkFhLGGfFFS', 4, NULL),
(69, '', NULL, NULL, '', '', '', '', 'javito', '$2y$10$xhg2J./VsumIlP2wL6I2y.6ohe0TQo8PEqGP8KSzjcklD7SqjUE7O', 1, NULL),
(70, '', NULL, NULL, '', '', '', '', 'juan', '$2y$10$sXfvGPCBtARIkv/KqrDqLePEBFXFPvOA3CldPY3zQ3XJkswTe9vYS', 1, NULL),
(71, '', NULL, NULL, '', '', '', '', 'userprueba', '$2y$10$eK8NMfQbav5jeLK7XRbwTehPJ2iMLlHbkv65DXjSQUOHtc8pOdoe.', 3, NULL),
(72, '1', 'dni', '12345678', 'Javier lacerna', 'Los libertadores 227', '955175349', 'jlacerna.velez@gmail.com', 'javier', '$2y$10$Gpb5vwbiVBlS/GYjrWPd3OCQIrriEOY3pdcfyc5IKq76dXt9.twaq', 3, NULL),
(73, '2', 'dni', '12345678', 'suarez pablo', 'Los libertadores 227', '955175349', 'lvelezjavier@uss.edu.pe', 'juanpablo', '$2y$10$TMnb6CWKyHJUV7K8c1bSOezZt/Z3OscVUbS0fzkxMPOT38vlm0LCq', 2, 'uploads/219983.png'),
(74, '1', 'dni', '12345678', 'jesus javier', 'Los libertadores 777', '955175349', 'ejemplo@gmail.com', 'jesus javier', '$2y$10$Ka3GGllSpop3WfRbSgEwV.hr6DlyffL6AW.d0VOmpbUOuPNkLb3W2', 3, 'uploads/219983.png'),
(75, '1', 'dni', '12345678', 'susan sandoval', 'urb el santuario', '955175349', 'susan@gmail.com', 'susan', '$2y$10$PLLP1r9tRBubb9IvejyZvesWlwwVpf2YVSmkAZxl3grw4JrtlPJTK', 3, NULL),
(76, '1', 'dni', '12345678', 'franco', 'el santuario', '123456789', 'franco@franco', 'franco', '$2y$10$DrHMT6rJoe0DBmtPjRZ1o.vQgjk4TmV5qfZ0pZ2gCllG5IexyCsXm', 3, NULL),
(77, '1', 'dni', '12345678', 'carlos bustamante', 'i 17', '123456789', 'carlosbus@gmail.com', 'carlos', '$2y$10$qAzeQx08WdIHNqCNokNA.unoBgRUDmyN0qi0QRGXPNvF/RUTdTUeG', 3, NULL),
(78, '1', 'dni', '12345678', 'jjjj', 'kkkk', '12345678', 'asasa@asas.com', 'prueba1', '$2y$10$2KwTRhl8zmurql3BBbfpn.VT44KuzJcqSfv8y2z8U56L5P8/ziNha', 3, NULL),
(79, '1', 'dni', '87542132', 'Javier lacerna', 'pimentel', '955175349', 'javier_profesor@gmail.com', 'Javier profesor', '$2y$10$cnSbwvp3w/ZiyP/BRAhd5uY6DAyCikWUbuQLpopWaZoSfyCK9vpHe', 2, 'uploads/cute-male-teacher-cartoon-character-free-png.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `idCurso` int(11) NOT NULL,
  `nombreCurso` varchar(150) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `imagen` varchar(80) DEFAULT NULL,
  `idNivel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idCurso`, `nombreCurso`, `descripcion`, `imagen`, `idNivel`) VALUES
(1, 'Matemática', 'Inscríbete e ingresa al mundo mágico de los números.', 'Herramientas digitales.png', NULL),
(2, 'Reparación de celulares', 'Reparar celulares es devolverles vida. Domina la técnica y convierte cada arreglo en una solución.', 'reparacion.png', NULL),
(3, 'Ofimática', 'La ofimática agiliza tareas y abre oportunidades. Domínala y haz que la tecnología trabaje para ti.', 'OFIMATICA.png', NULL),
(4, 'Desarrollo de Sistemas', 'El desarrollo de sistemas transforma ideas en soluciones. Domínalo y crea el futuro con código.\r\n', 'desarrollo.png', NULL),
(5, 'Electricidad', 'La electricidad da luz, energía y movimiento. ¡Domínala y enciende el futuro!.', 'electronica.jpeg', NULL);

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
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `idEncuesta` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  `fechaCierre` datetime DEFAULT NULL,
  `multiple` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`idEncuesta`, `idCurso`, `idProfesor`, `titulo`, `descripcion`, `fechaCreacion`, `fechaCierre`, `multiple`) VALUES
(1, 1, 79, 'primera encuesta', 'matemática o física', '2025-06-24 18:44:41', '2025-06-24 20:56:07', 1),
(2, 1, 79, 'Recuperacion de la clase numero 2', 'Estimados estudiantes veamos los dias disponibles para recueperar la sesion atrasada', '2025-06-24 22:08:41', '2025-06-30 23:59:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta_opciones`
--

CREATE TABLE `encuesta_opciones` (
  `idOpcion` int(11) NOT NULL,
  `idEncuesta` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuesta_opciones`
--

INSERT INTO `encuesta_opciones` (`idOpcion`, `idEncuesta`, `texto`) VALUES
(1, 1, 'si'),
(2, 1, 'no'),
(3, 1, 'quizas'),
(4, 1, 'talvez'),
(5, 1, 'nunca'),
(6, 2, 'lunes'),
(7, 2, 'martes'),
(8, 2, 'miercoes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta_respuestas`
--

CREATE TABLE `encuesta_respuestas` (
  `idRespuesta` int(11) NOT NULL,
  `idEncuesta` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idOpcion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas_tarea`
--

CREATE TABLE `entregas_tarea` (
  `idEntrega` int(11) NOT NULL,
  `idTarea` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `fechaEnvio` datetime NOT NULL DEFAULT current_timestamp(),
  `intentos` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL,
  `intentos_max` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evaluaciones`
--

INSERT INTO `evaluaciones` (`id`, `idCurso`, `idProfesor`, `titulo`, `descripcion`, `fecha_inicio`, `fecha_fin`, `duracion`, `intentos_max`) VALUES
(1, 1, 79, 'examen de prueba de matemtica', 'examen de prueba', '2025-06-17 08:00:00', '2025-06-17 09:00:00', 60, 1),
(2, 1, 79, 'primera evaluacion de prueba', 'prueba para ver', '2025-06-19 14:34:00', '2025-06-20 14:34:00', 60, 2),
(3, 1, 79, 'ejemplo', 'ejemplo prueba', '2025-06-20 23:21:00', '2025-06-20 23:21:00', 60, 1),
(4, 1, 79, 'ejemplo', 'ejemplo', '2025-06-20 23:24:00', '2025-06-20 23:24:00', 60, 1),
(5, 1, 79, 'examen1', 'resolver el examen en menos de 1hora', '2025-06-20 23:32:00', '2025-06-20 23:32:00', 60, 1),
(6, 1, 79, 'hola', 'demostracion', '2025-06-21 16:05:00', '2025-06-21 17:05:00', 60, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion_resultado`
--

CREATE TABLE `evaluacion_resultado` (
  `id` int(11) NOT NULL,
  `idEvaluacion` int(11) DEFAULT NULL,
  `idAlumno` int(11) DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `intentos` int(11) DEFAULT 1,
  `completado` tinyint(1) DEFAULT 1,
  `fecha_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foros`
--

CREATE TABLE `foros` (
  `idForo` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaLimite` datetime DEFAULT NULL,
  `bloquearAntes` tinyint(1) DEFAULT 0,
  `fechaCreacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_inicio` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` datetime NOT NULL DEFAULT current_timestamp(),
  `requiere_respuesta_previa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `foros`
--

INSERT INTO `foros` (`idForo`, `idCurso`, `idProfesor`, `titulo`, `descripcion`, `fechaLimite`, `bloquearAntes`, `fechaCreacion`, `fecha_inicio`, `fecha_fin`, `requiere_respuesta_previa`) VALUES
(1, 1, 79, 'Foro de prueba', 'Este sera el foro de prueba', '2025-06-20 18:27:00', 0, '2025-06-19 23:27:51', '2025-06-19 18:49:37', '2025-06-19 18:49:37', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `idGrupo` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `nombreGrupo` varchar(10) NOT NULL,
  `aula` varchar(20) NOT NULL,
  `cuposDisponibles` int(11) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`idGrupo`, `idCurso`, `idProfesor`, `nombreGrupo`, `aula`, `cuposDisponibles`) VALUES
(5, 1, 1, 'A', '301', 30),
(6, 1, 2, 'B', '302', 30),
(7, 2, 1, 'A', '303', 30),
(8, 2, 2, 'B', '304', 30),
(10, 1, 79, 'A', '301', 30),
(13, 5, 79, 'A', '101', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `idHorario` int(11) NOT NULL,
  `idGrupo` int(11) NOT NULL,
  `diaSemana` varchar(20) NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `aula` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`idHorario`, `idGrupo`, `diaSemana`, `horaInicio`, `horaFin`, `idCurso`, `idProfesor`, `aula`) VALUES
(11, 8, 'Lunes', '10:00:00', '12:00:00', 1, 2, '304'),
(12, 5, 'Viernes', '08:00:00', '10:30:00', 5, 1, '301');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula`
--

CREATE TABLE `matricula` (
  `idMatricula` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idGrupo` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `fechaMatricula` datetime DEFAULT current_timestamp(),
  `estado` varchar(20) DEFAULT 'Pendiente',
  `idNivel` enum('Básico','Intermedio','Avanzado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `matricula`
--

INSERT INTO `matricula` (`idMatricula`, `idCliente`, `idGrupo`, `idCurso`, `fechaMatricula`, `estado`, `idNivel`) VALUES
(1, 78, 0, 1, '2025-06-12 19:49:49', 'Activo', 'Básico'),
(2, 78, 0, 2, '2025-06-12 19:49:55', 'Activo', 'Intermedio'),
(3, 78, 0, 3, '2025-06-12 19:50:01', 'Activo', 'Avanzado'),
(4, 78, 0, 4, '2025-06-12 19:50:38', 'Activo', 'Avanzado'),
(5, 79, 0, 1, '2025-06-16 20:28:38', 'Activo', 'Básico'),
(6, 79, 0, 2, '2025-06-16 20:28:43', 'Activo', 'Avanzado'),
(7, 73, 0, 1, '2025-06-19 23:35:00', 'Activo', 'Básico'),
(8, 75, 0, 1, '2025-06-19 23:35:37', 'Activo', 'Básico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `idNivel` int(11) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`idNivel`, `descripcion`) VALUES
(1, 'Básico'),
(2, 'Intermedio'),
(3, 'Experto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_curso`
--

CREATE TABLE `notas_curso` (
  `id` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idAlumno` int(11) NOT NULL,
  `nota1` decimal(5,2) DEFAULT NULL,
  `nota2` decimal(5,2) DEFAULT NULL,
  `nota3` decimal(5,2) DEFAULT NULL,
  `nota4` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `idNotificacion` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
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
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `idPerfil` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `fotoPerfil` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `idPregunta` int(11) NOT NULL,
  `idEvaluacion` int(11) DEFAULT NULL,
  `tipo` enum('opcion','vf','abierta') NOT NULL DEFAULT 'opcion',
  `texto` text NOT NULL,
  `opcionA` varchar(255) DEFAULT NULL,
  `opcionB` varchar(255) DEFAULT NULL,
  `opcionC` varchar(255) DEFAULT NULL,
  `opcionD` varchar(255) DEFAULT NULL,
  `respuesta` char(1) NOT NULL,
  `valor` decimal(5,2) NOT NULL DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`idPregunta`, `idEvaluacion`, `tipo`, `texto`, `opcionA`, `opcionB`, `opcionC`, `opcionD`, `respuesta`, `valor`) VALUES
(1, 1, 'opcion', '1+1', '1', '2', '3', '5', 'B', 1.00),
(2, 1, 'opcion', '5+5=10?', '', '', '', '', 'V', 1.00),
(3, 4, 'opcion', '1-1', '0', '1', '2', '3', 'A', 10.00),
(4, 4, 'abierta', 'que es la matematica', '', '', '', '', '1', 10.00),
(5, 6, 'abierta', '5+5=10', '', '', '', '', '1', 10.00),
(6, 6, 'opcion', 'hola', 'hola', 'bC', 'd', 'f', 'A', 10.00);

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
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `idProfesor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `dni` char(8) NOT NULL,
  `descripcion` text NOT NULL,
  `gradoAcademico` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`idProfesor`, `nombre`, `apellido`, `email`, `telefono`, `dni`, `descripcion`, `gradoAcademico`, `foto`) VALUES
(1, 'Javier Velez', '', '', NULL, '12345678', 'Profesor de Matemáticas con más de 10 años de experiencia.', 'Ingeniero', NULL),
(2, 'Neiler Perez', '', '', NULL, '87654321', 'Especialista en Ofimática, con enfoque en software empresarial.', 'Técnico', NULL),
(4, 'Jaime Bravo', '', '', NULL, '12123654', 'Coordinador en exitos del saber', 'Ingeniero', 'uploads/profesores/1517494618674.jpg'),
(79, 'Javier', 'Lacerna', 'profesor@gmail.com', '987654321', '11223344', 'Docente de Matemática', 'Licenciado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_curso`
--

CREATE TABLE `profesor_curso` (
  `idProfesorCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso`
--

CREATE TABLE `progreso` (
  `idProgreso` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `porcentaje` int(11) DEFAULT 0
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
-- Estructura de tabla para la tabla `recursos_multimedia`
--

CREATE TABLE `recursos_multimedia` (
  `id` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `tipo` enum('archivo','link') NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ruta` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_alumno`
--

CREATE TABLE `respuestas_alumno` (
  `id` int(11) NOT NULL,
  `idEvaluacion` int(11) DEFAULT NULL,
  `idAlumno` int(11) DEFAULT NULL,
  `idPregunta` int(11) DEFAULT NULL,
  `idAlternativa` int(11) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_foro`
--

CREATE TABLE `respuestas_foro` (
  `idRespuesta` int(11) NOT NULL,
  `idForo` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fechaRespuesta` timestamp NOT NULL DEFAULT current_timestamp(),
  `fechaCreacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados`
--

CREATE TABLE `resultados` (
  `idResultado` int(11) NOT NULL,
  `idEvaluacion` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `notaFinal` decimal(5,2) NOT NULL,
  `fechaEntrega` timestamp NOT NULL DEFAULT current_timestamp()
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
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `idSesion` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `fechaCreacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`idSesion`, `idCurso`, `numero`, `titulo`, `descripcion`, `archivo`, `link`, `fechaCreacion`) VALUES
(1, 1, 1, 'Silabo del curso', 'Conociendo al profesor y presentación del silabo del curso.', NULL, '', '2025-06-18 06:57:00'),
(2, 1, 2, 'Sesion 2', 'clase 2 de la materia de matematica\r\n', NULL, '', '2025-06-19 23:26:41'),
(3, 1, 3, 'clase 3 ', 'clase 3 de matematica', NULL, '', '2025-06-20 04:29:58'),
(4, 1, 4, 'matematica discreta', 'clase introductoria', NULL, '', '2025-06-20 20:59:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `idSolicitud` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `fecha` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `idNivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`idSolicitud`, `idCliente`, `idCurso`, `fecha`, `estado`, `idNivel`) VALUES
(1, 2, 1, 2025, 2, 1),
(2, 65, 1, 20250529, 1, 1),
(3, 65, 1, 20250530, 1, 3),
(4, 59, 1, 20250530, 1, 1),
(5, 59, 3, 20250603, 1, 1),
(6, 59, 2, 20250605, 1, 2),
(7, 74, 1, 20250606, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `idTarea` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaInicio` datetime NOT NULL,
  `fechaLimite` datetime NOT NULL,
  `maxIntentos` int(11) DEFAULT 1,
  `permitirReenvio` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`idTarea`, `idCurso`, `idProfesor`, `titulo`, `descripcion`, `fechaInicio`, `fechaLimite`, `maxIntentos`, `permitirReenvio`) VALUES
(1, 1, 79, 'Tarea1', 'la tarea tendra un plazo hasta el domingo a las 11y 59pm', '2025-06-21 08:00:00', '2025-06-22 23:59:00', 1, 1),
(2, 1, 79, 'Tarea1', 'la tarea tendra un plazo hasta el domingo a las 11y 59pm', '2025-06-21 08:00:00', '2025-06-22 23:59:00', 1, 1),
(3, 1, 79, 'Tarea2', '.', '2025-06-22 23:32:00', '2025-06-24 23:32:00', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tema_curso`
--

CREATE TABLE `tema_curso` (
  `id` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `colorFondo` varchar(20) NOT NULL DEFAULT '#ffffff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tema_curso`
--

INSERT INTO `tema_curso` (`id`, `idCurso`, `idProfesor`, `colorFondo`) VALUES
(1, 1, 65, '#000000'),
(2, 1, 79, '#48962c');

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
  `Estado` int(11) NOT NULL COMMENT '1:Trabajador; 2:No trabajador',
  `usuario` varchar(50) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajador`
--

INSERT INTO `trabajador` (`idTrabajador`, `idCargo`, `DNI`, `apellidos`, `Nombres`, `dirección`, `estadoCivil`, `NumHijos`, `Estado`, `usuario`, `clave`) VALUES
(3, 1, '', '', '', '', '', '', 0, 'admin01', '$2y$10$xRoHTcK3EasM.9IkTeySBebVfx4Sw8wlV7vZ5NTV5amg4hdVKO3.G'),
(4, 2, '', '', '', '', '', '', 0, 'empleado01', '$2y$10$VM9p5qh9z8fnBH1G2HNxZebTR.PjYApZAfeaPBLmHhV.jj64BmUhK');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alternativas`
--
ALTER TABLE `alternativas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPregunta` (`idPregunta`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idAsistencia`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idCurso` (`idCurso`);

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
-- Indices de la tabla `chat_curso`
--
ALTER TABLE `chat_curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCurso` (`idCurso`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `fk_cliente_cargo` (`idCargo`);

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
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`idEncuesta`),
  ADD KEY `idCurso` (`idCurso`),
  ADD KEY `idProfesor` (`idProfesor`);

--
-- Indices de la tabla `encuesta_opciones`
--
ALTER TABLE `encuesta_opciones`
  ADD PRIMARY KEY (`idOpcion`),
  ADD KEY `idEncuesta` (`idEncuesta`);

--
-- Indices de la tabla `encuesta_respuestas`
--
ALTER TABLE `encuesta_respuestas`
  ADD PRIMARY KEY (`idRespuesta`),
  ADD UNIQUE KEY `idEncuesta` (`idEncuesta`,`idCliente`,`idOpcion`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idOpcion` (`idOpcion`);

--
-- Indices de la tabla `entregas_tarea`
--
ALTER TABLE `entregas_tarea`
  ADD PRIMARY KEY (`idEntrega`),
  ADD KEY `idTarea` (`idTarea`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCurso` (`idCurso`);

--
-- Indices de la tabla `evaluacion_resultado`
--
ALTER TABLE `evaluacion_resultado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `foros`
--
ALTER TABLE `foros`
  ADD PRIMARY KEY (`idForo`),
  ADD KEY `idCurso` (`idCurso`),
  ADD KEY `idProfesor` (`idProfesor`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`idGrupo`),
  ADD KEY `idCurso` (`idCurso`),
  ADD KEY `idProfesor` (`idProfesor`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`idHorario`),
  ADD UNIQUE KEY `hora_unica` (`idGrupo`,`diaSemana`,`horaInicio`,`horaFin`);

--
-- Indices de la tabla `matricula`
--
ALTER TABLE `matricula`
  ADD PRIMARY KEY (`idMatricula`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`idNivel`);

--
-- Indices de la tabla `notas_curso`
--
ALTER TABLE `notas_curso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curso_alumno` (`idCurso`,`idAlumno`),
  ADD KEY `idAlumno` (`idAlumno`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`idNotificacion`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`idOfertas`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`idPerfil`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`idPregunta`),
  ADD KEY `idEvaluacion` (`idEvaluacion`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD KEY `idCategoria` (`idCategoria`),
  ADD KEY `idOfertas` (`idOfertas`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`idProfesor`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `profesor_curso`
--
ALTER TABLE `profesor_curso`
  ADD PRIMARY KEY (`idProfesorCurso`),
  ADD KEY `idProfesor` (`idProfesor`),
  ADD KEY `idCurso` (`idCurso`);

--
-- Indices de la tabla `progreso`
--
ALTER TABLE `progreso`
  ADD PRIMARY KEY (`idProgreso`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idCurso` (`idCurso`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`idProvincia`),
  ADD KEY `idDepartamento` (`idDepartamento`);

--
-- Indices de la tabla `recursos_multimedia`
--
ALTER TABLE `recursos_multimedia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCurso` (`idCurso`);

--
-- Indices de la tabla `respuestas_alumno`
--
ALTER TABLE `respuestas_alumno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idEvaluacion` (`idEvaluacion`);

--
-- Indices de la tabla `respuestas_foro`
--
ALTER TABLE `respuestas_foro`
  ADD PRIMARY KEY (`idRespuesta`),
  ADD KEY `idForo` (`idForo`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `resultados`
--
ALTER TABLE `resultados`
  ADD PRIMARY KEY (`idResultado`),
  ADD KEY `idEvaluacion` (`idEvaluacion`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idServicios`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`idSesion`),
  ADD KEY `idCurso` (`idCurso`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`idSolicitud`),
  ADD KEY `fk_nivel` (`idNivel`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`idTarea`),
  ADD KEY `idCurso` (`idCurso`),
  ADD KEY `idProfesor` (`idProfesor`);

--
-- Indices de la tabla `tema_curso`
--
ALTER TABLE `tema_curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCurso` (`idCurso`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`idTrabajador`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `idCargo` (`idCargo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alternativas`
--
ALTER TABLE `alternativas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idAsistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `idCargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT de la tabla `chat_curso`
--
ALTER TABLE `chat_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

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
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `idEncuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `encuesta_opciones`
--
ALTER TABLE `encuesta_opciones`
  MODIFY `idOpcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `encuesta_respuestas`
--
ALTER TABLE `encuesta_respuestas`
  MODIFY `idRespuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entregas_tarea`
--
ALTER TABLE `entregas_tarea`
  MODIFY `idEntrega` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `evaluacion_resultado`
--
ALTER TABLE `evaluacion_resultado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `foros`
--
ALTER TABLE `foros`
  MODIFY `idForo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `idGrupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `idHorario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `matricula`
--
ALTER TABLE `matricula`
  MODIFY `idMatricula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `idNivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `notas_curso`
--
ALTER TABLE `notas_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `idNotificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `idOfertas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `idPerfil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `idPregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `idProfesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `profesor_curso`
--
ALTER TABLE `profesor_curso`
  MODIFY `idProfesorCurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `progreso`
--
ALTER TABLE `progreso`
  MODIFY `idProgreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `idProvincia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recursos_multimedia`
--
ALTER TABLE `recursos_multimedia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas_alumno`
--
ALTER TABLE `respuestas_alumno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas_foro`
--
ALTER TABLE `respuestas_foro`
  MODIFY `idRespuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resultados`
--
ALTER TABLE `resultados`
  MODIFY `idResultado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `idServicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `idSesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `idSolicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `idTarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tema_curso`
--
ALTER TABLE `tema_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `idTrabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alternativas`
--
ALTER TABLE `alternativas`
  ADD CONSTRAINT `alternativas_ibfk_1` FOREIGN KEY (`idPregunta`) REFERENCES `preguntas` (`idPregunta`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`);

--
-- Filtros para la tabla `chat_curso`
--
ALTER TABLE `chat_curso`
  ADD CONSTRAINT `fk_chat_curso_curso` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_curso_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_cargo` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`);

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
-- Filtros para la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD CONSTRAINT `encuestas_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`) ON DELETE CASCADE,
  ADD CONSTRAINT `encuestas_ibfk_2` FOREIGN KEY (`idProfesor`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `encuesta_opciones`
--
ALTER TABLE `encuesta_opciones`
  ADD CONSTRAINT `encuesta_opciones_ibfk_1` FOREIGN KEY (`idEncuesta`) REFERENCES `encuestas` (`idEncuesta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `encuesta_respuestas`
--
ALTER TABLE `encuesta_respuestas`
  ADD CONSTRAINT `encuesta_respuestas_ibfk_1` FOREIGN KEY (`idEncuesta`) REFERENCES `encuestas` (`idEncuesta`) ON DELETE CASCADE,
  ADD CONSTRAINT `encuesta_respuestas_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `encuesta_respuestas_ibfk_3` FOREIGN KEY (`idOpcion`) REFERENCES `encuesta_opciones` (`idOpcion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `entregas_tarea`
--
ALTER TABLE `entregas_tarea`
  ADD CONSTRAINT `entregas_tarea_ibfk_1` FOREIGN KEY (`idTarea`) REFERENCES `tareas` (`idTarea`),
  ADD CONSTRAINT `entregas_tarea_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`);

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`);

--
-- Filtros para la tabla `foros`
--
ALTER TABLE `foros`
  ADD CONSTRAINT `foros_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`) ON DELETE CASCADE,
  ADD CONSTRAINT `foros_ibfk_2` FOREIGN KEY (`idProfesor`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`),
  ADD CONSTRAINT `grupos_ibfk_2` FOREIGN KEY (`idProfesor`) REFERENCES `profesores` (`idProfesor`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`idGrupo`) REFERENCES `grupos` (`idGrupo`);

--
-- Filtros para la tabla `notas_curso`
--
ALTER TABLE `notas_curso`
  ADD CONSTRAINT `notas_curso_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_curso_ibfk_2` FOREIGN KEY (`idAlumno`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`);

--
-- Filtros para la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD CONSTRAINT `perfil_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`idEvaluacion`) REFERENCES `evaluaciones` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`idOfertas`) REFERENCES `ofertas` (`idOfertas`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`);

--
-- Filtros para la tabla `profesor_curso`
--
ALTER TABLE `profesor_curso`
  ADD CONSTRAINT `profesor_curso_ibfk_1` FOREIGN KEY (`idProfesor`) REFERENCES `profesores` (`idProfesor`),
  ADD CONSTRAINT `profesor_curso_ibfk_2` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`);

--
-- Filtros para la tabla `progreso`
--
ALTER TABLE `progreso`
  ADD CONSTRAINT `progreso_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  ADD CONSTRAINT `progreso_ibfk_2` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`);

--
-- Filtros para la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD CONSTRAINT `provincia_ibfk_1` FOREIGN KEY (`idDepartamento`) REFERENCES `departamento` (`idDepartamento`);

--
-- Filtros para la tabla `recursos_multimedia`
--
ALTER TABLE `recursos_multimedia`
  ADD CONSTRAINT `recursos_multimedia_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`);

--
-- Filtros para la tabla `respuestas_alumno`
--
ALTER TABLE `respuestas_alumno`
  ADD CONSTRAINT `respuestas_alumno_ibfk_1` FOREIGN KEY (`idEvaluacion`) REFERENCES `evaluaciones` (`id`);

--
-- Filtros para la tabla `respuestas_foro`
--
ALTER TABLE `respuestas_foro`
  ADD CONSTRAINT `respuestas_foro_ibfk_1` FOREIGN KEY (`idForo`) REFERENCES `foros` (`idForo`) ON DELETE CASCADE,
  ADD CONSTRAINT `respuestas_foro_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resultados`
--
ALTER TABLE `resultados`
  ADD CONSTRAINT `resultados_ibfk_1` FOREIGN KEY (`idEvaluacion`) REFERENCES `evaluaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resultados_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `fk_nivel` FOREIGN KEY (`idNivel`) REFERENCES `niveles` (`idNivel`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`),
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`idProfesor`) REFERENCES `cliente` (`idCliente`);

--
-- Filtros para la tabla `tema_curso`
--
ALTER TABLE `tema_curso`
  ADD CONSTRAINT `tema_curso_ibfk_1` FOREIGN KEY (`idCurso`) REFERENCES `cursos` (`idCurso`);

--
-- Filtros para la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD CONSTRAINT `fk_trabajador_cargo` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`),
  ADD CONSTRAINT `trabajador_ibfk_1` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

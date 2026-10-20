-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 20-10-2026 a las 16:57:06
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `apriscom_aprisco`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabras`
--

CREATE TABLE `cabras` (
  `id_cabra` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `madre` int DEFAULT NULL,
  `padre` int DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `peso_nacer_kg` decimal(5,2) DEFAULT NULL,
  `sexo` enum('MACHO','HEMBRA') COLLATE utf8mb4_general_ci NOT NULL,
  `id_raza` int DEFAULT NULL,
  `color` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_propietario_actual` int DEFAULT NULL,
  `estado` enum('ACTIVA','INACTIVA') COLLATE utf8mb4_general_ci DEFAULT 'ACTIVA',
  `fecha_registro` date DEFAULT NULL,
  `creado_por` int DEFAULT NULL,
  `modificado_por` int DEFAULT NULL,
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cabras`
--

INSERT INTO `cabras` (`id_cabra`, `nombre`, `madre`, `padre`, `fecha_nacimiento`, `peso_nacer_kg`, `sexo`, `id_raza`, `color`, `id_propietario_actual`, `estado`, `fecha_registro`, `creado_por`, `modificado_por`, `foto`) VALUES
(27, 'JAMAICA', NULL, NULL, '2020-11-06', NULL, 'HEMBRA', 2, 'CAFE CON MANCHAS', NULL, 'INACTIVA', '2026-01-27', 12, 12, 'cabras/cabra_6979122f8c63f.jpeg'),
(28, 'JARA', 27, 29, '2021-06-11', NULL, 'HEMBRA', 2, 'NEGRO', NULL, 'ACTIVA', '2026-01-27', 12, 12, 'cabras/cabra_6979128f3940a.jpeg'),
(29, 'GALEGO DE JERICO', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CARAMELO', NULL, 'INACTIVA', '2026-01-27', 12, 12, 'cabras/cabra_697916e0b8773.jpeg'),
(30, 'GALEGA', NULL, 29, '2020-11-02', NULL, 'HEMBRA', 2, 'NEGRO', NULL, 'ACTIVA', '2026-01-27', 12, NULL, 'cabras/cabra_6979176ee8745.jpeg'),
(31, 'RIGOR', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE', NULL, 'INACTIVA', '2026-01-27', 12, 12, 'cabras/cabra_697917b094589.jpeg'),
(32, 'EXIMER KAST DE MUR', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'GRIS', NULL, 'INACTIVA', '2026-01-27', 12, 12, 'cabras/cabra_6979193b2eea5.jpeg'),
(33, 'ATLAS REMUS', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, 'GRIS', NULL, 'ACTIVA', '2026-01-27', 12, 12, 'cabras/cabra_697a6d48df4fe.png'),
(34, 'OLENTANGY FIERY ARCHER', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, '', NULL, 'ACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a66703ea9f.jpeg'),
(35, 'BOOMERANG', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a68408fe95.jpeg'),
(36, 'WILLOW', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a688875620.jpeg'),
(37, 'DKGH TAKE ME TO RIO', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a68f87af9d.jpeg'),
(38, 'DKGH DR. MW BLAZE', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CLARO', NULL, 'ACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a69b400f29.jpeg'),
(39, 'DKGH MW ROSE\'S FINAL RESULT', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CLARO', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a69e3ddadd.jpeg'),
(40, 'PRUITTVILLE\'S N/C MIGHTY ODIN', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a6a0c6c059.jpeg'),
(41, 'DKGH ROYAL MARCUS ETHOS', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a6a3aab63a.jpeg'),
(42, 'DKGH GADO ROOTBEER\'S REBEL', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a6a641f222.jpg'),
(43, 'EVOLUTION ZB ZOLDYCK', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, 'BAYO', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a6a8b4d704.jpeg'),
(44, 'BARRABAS', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, 'CAFE', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a6d696c429.png'),
(45, 'CANTALETA', NULL, 46, '2021-06-11', NULL, 'HEMBRA', 1, 'BAYO', 5, 'INACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a6e324ab20.jpeg'),
(46, 'POSEIDON', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, 'CHAMOIS', NULL, 'INACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a6e81a744f.jpeg'),
(47, 'ENRIQUE DE CARUAO', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, 'BAYO CON MANCHAS', NULL, 'INACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a6ef79c0eb.png'),
(48, 'PANDORA', NULL, 47, '2021-06-11', NULL, 'HEMBRA', 1, 'CHAMOIS', 5, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a6f3d73095.jpeg'),
(49, 'CHERRY GLEN RADICAL HIGHLIGHT', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, 'BAYO', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a7014bfeb8.jpeg'),
(50, 'RINUTE HIGLIGHT PATRICIA', 48, 49, '2023-05-07', NULL, 'HEMBRA', 1, 'BAYO', 5, 'ACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a70ed8a355.jpeg'),
(51, 'RINUTE WILLOW HERA', 28, 36, '2024-03-13', NULL, 'HEMBRA', 2, 'MORO', 5, 'ACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a719a36015.JPG'),
(52, 'MAYA', 28, 36, '2024-03-13', NULL, 'HEMBRA', 2, 'CAFE', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a73103bd78.JPG'),
(53, 'MIA', 30, 36, '2022-10-13', 12.00, 'HEMBRA', 2, 'NEGRO', NULL, 'ACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a738872b93.jpeg'),
(54, 'DKGH TAKE ME TO RIO', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'INACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a740e5b6b3.JPG'),
(55, 'GUADALUPANO', 45, NULL, '2023-01-01', NULL, 'MACHO', 1, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a747f968f7.JPG'),
(56, 'HADES', 48, 43, '2024-03-15', NULL, 'MACHO', 1, 'CHAMOIS', NULL, 'INACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a74c6dda71.JPG'),
(57, 'VENUS', 53, 37, '2024-03-12', NULL, 'HEMBRA', 2, 'NEGRO CON MANCHAS', 5, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a7521ba99b.JPG'),
(58, 'MILAGROS', NULL, 31, '2021-06-11', NULL, 'HEMBRA', 2, 'BAYO', NULL, 'INACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a76276e69c.JPG'),
(59, 'ORFEUS', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, 'BAYO', NULL, 'ACTIVA', '2026-01-28', 12, NULL, 'cabras/cabra_697a84203a6ff.jpeg'),
(60, 'MARGARITA', NULL, 59, '2021-08-08', NULL, 'HEMBRA', 2, 'BAYO', NULL, 'INACTIVA', '2026-01-28', 12, 12, 'cabras/cabra_697a8538a63ee.jpeg'),
(61, 'CRISTAL FOREST AMIGO', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, '', NULL, 'ACTIVA', '2026-01-31', 12, NULL, NULL),
(62, 'RINUTE AMIGO LYRA', 122, 61, '2024-11-08', NULL, 'HEMBRA', 2, 'BAYO', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e41aab9962.png'),
(63, 'ZARZAMORA', NULL, NULL, '2020-01-01', NULL, 'HEMBRA', 1, 'NEGRO', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4294b548b.jpg'),
(64, 'RINUTE GUADALUPANO BANANA', NULL, 55, '2025-01-30', NULL, 'HEMBRA', 1, 'CHAMOIS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4304000f8.jpg'),
(65, 'ACACIA', NULL, NULL, '2023-05-07', NULL, 'HEMBRA', 1, 'CHAMOIS', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4382319e7.jpg'),
(66, 'FREYA', 28, 36, '2024-03-13', NULL, 'HEMBRA', 1, 'NEGRO', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e43c0b73ca.JPG'),
(67, 'LA NEGRA', NULL, NULL, '2020-01-01', NULL, 'HEMBRA', 6, 'BLANCO CON MANCHAS', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4448e9d7f.jpg'),
(68, 'LUNA', 67, 55, '2023-05-07', NULL, 'HEMBRA', 6, 'BAYO', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e447c33e5c.jpg'),
(69, 'SELVA', NULL, NULL, '2023-05-07', NULL, 'HEMBRA', 6, 'CHAMOIS', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e44d075553.jpg'),
(70, 'JACINTA', NULL, NULL, '2023-05-07', NULL, 'HEMBRA', 6, 'BAYO', NULL, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e454c4c427.jpg'),
(71, 'AFRODITA', NULL, 55, '2024-07-16', NULL, 'HEMBRA', 1, 'BAYO', 5, 'ACTIVA', '2026-01-31', 12, NULL, NULL),
(72, 'AZUCENA', NULL, 55, '2025-01-30', NULL, 'HEMBRA', 6, 'BLANCO', 5, 'ACTIVA', '2026-01-31', 12, NULL, NULL),
(73, 'PETUNIA', NULL, 55, '2025-10-30', NULL, 'HEMBRA', 6, 'BLANCA', 5, 'ACTIVA', '2026-01-31', 12, NULL, NULL),
(74, 'CAMELIA', NULL, NULL, '2025-01-30', NULL, 'HEMBRA', 3, 'BLANCO', 5, 'ACTIVA', '2026-01-31', 12, NULL, NULL),
(75, 'CATHALEYA', 53, 38, '2025-02-27', NULL, 'HEMBRA', 2, 'NEGRO CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, NULL, NULL),
(76, 'THALIA', 67, 55, '2024-07-16', NULL, 'HEMBRA', 1, 'NEGRO', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e47db54426.JPG'),
(77, 'CRONOS', NULL, 42, '2023-10-20', NULL, 'MACHO', 2, 'CAFE', 5, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e486d93584.jpg'),
(78, 'RINUTE CRONOS FLORA', 30, 130, '2024-11-08', NULL, 'HEMBRA', 2, 'CAFE', 5, 'ACTIVA', '2026-01-31', 12, 12, NULL),
(79, 'ADONIS', 58, 32, '2024-07-15', NULL, 'MACHO', 2, 'BAYO', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e490e5c145.jpg'),
(80, 'SAMANTHA', NULL, NULL, '2020-01-01', NULL, 'HEMBRA', 2, 'CAFE', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e49a34b477.jpeg'),
(81, 'CANELLA', NULL, 59, '2021-09-03', NULL, 'HEMBRA', 2, 'CAFE', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e49d7f1bab.jpeg'),
(82, 'LUCKY STRIKE', NULL, NULL, '2010-01-01', NULL, 'MACHO', 2, '', NULL, 'INACTIVA', '2026-01-31', 12, 12, NULL),
(83, 'ROMULO', 81, 82, '2024-07-15', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4ae4c7ae8.JPG'),
(84, 'MAWII', 28, 82, '2024-11-11', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4b19b9c04.jpg'),
(85, 'BOYACA', 58, 59, '2021-08-08', NULL, 'HEMBRA', 2, 'BAYO', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4bc233dcf.jpeg'),
(86, 'HORUS', 133, 134, '2024-11-12', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d727991b291.jpeg'),
(87, 'PRINCESA', 27, 59, '2021-09-30', NULL, 'HEMBRA', 2, 'CAFE CON MANCHAS', NULL, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4c6b962c6.jpeg'),
(88, 'APOLO', 132, 131, '2024-11-12', NULL, 'MACHO', 2, 'NEGRO CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d720dbdd1ad.jpeg'),
(89, 'GABY', 28, 32, '2022-10-15', NULL, 'HEMBRA', 2, 'NEGRO', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e4d0c2bfe9.jpeg'),
(90, 'FAUNO', 89, NULL, '2024-02-11', NULL, 'MACHO', 2, 'CAFE', 6, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4d480e19b.jpg'),
(91, 'LOKI', 89, 130, '2024-12-01', NULL, 'MACHO', 2, 'NEGRO', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d7209d483bb.jpeg'),
(92, 'SAMAN', 129, 38, '2025-03-31', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d71af9749c9.jpeg'),
(93, 'ROBLE', 53, 38, '2025-02-27', NULL, 'MACHO', 2, 'NEGRO CON MANCHAS', 5, 'INACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e4dfcb36fd.jpg'),
(94, 'SECUOYA', 65, 56, '2025-07-20', NULL, 'HEMBRA', 1, 'CHAMOIS', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e4e8e93e44.jpg'),
(95, 'MORERA', 57, NULL, '2025-07-27', NULL, 'HEMBRA', 2, 'BAYO CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e4f52c05d6.jpg'),
(96, 'TECA', 51, 42, '2025-07-25', NULL, 'HEMBRA', 2, 'CAFE', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e50d25cec9.jpg'),
(97, 'VALERIANA', 89, 37, '2025-07-27', NULL, 'HEMBRA', 2, 'NEGRO', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e510ca7edd.jpg'),
(98, 'FLOR DE LISS', 89, 37, '2025-07-27', NULL, 'HEMBRA', 2, 'NEGRO', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e513e8b1ea.jpg'),
(99, 'CAOBA', 52, 42, '2025-05-27', NULL, 'HEMBRA', 2, 'CAFE OREJAS BLANCAS', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e517f8f6e8.jpg'),
(100, 'ALHELÍ', 30, 37, '2025-07-27', NULL, 'HEMBRA', 2, 'NEGRO CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e51b8cbd00.jpg'),
(101, 'CEIBA', 28, 82, '2025-07-26', NULL, 'HEMBRA', 2, 'CAFE', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e51e9a2642.jpg'),
(102, 'SUNCHINE REHMA REPRISE', NULL, NULL, '2010-01-01', NULL, 'MACHO', 1, '', NULL, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e539036431.png'),
(103, 'RINUTE REPRISE RINUTE', 48, 102, '2025-07-29', NULL, 'MACHO', 1, 'CHAMOIS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69c84520f2bf5.jpeg'),
(104, 'CEREZO', 136, 39, '2025-07-25', NULL, 'MACHO', 2, 'CAFE', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d7288850fd7.jpeg'),
(105, 'EBANO', 57, 39, '2025-07-25', NULL, 'MACHO', 2, 'NEGRO', 5, 'INACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_697e54574316b.jpg'),
(106, 'HIGUERON', 136, 39, '2025-07-25', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d72812616a5.jpeg'),
(107, 'BARCINO', 30, 37, '2025-07-25', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d72842320cd.jpeg'),
(108, 'LIQUIDAMBAR', 65, 56, '2025-07-20', NULL, 'MACHO', 1, 'CHAMOIS', 5, 'ACTIVA', '2026-01-31', 12, NULL, 'cabras/cabra_697e55ae62cb9.jpg'),
(109, 'ALGARROBO', 30, 37, '2025-07-27', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', 5, 'ACTIVA', '2026-01-31', 12, 12, 'cabras/cabra_69d7298d6f002.jpeg'),
(110, 'LIVINGSTON', NULL, NULL, '2020-01-01', NULL, 'MACHO', 2, 'CAFE', NULL, 'ACTIVA', '2026-03-19', 12, NULL, 'cabras/cabra_69bc373013096.jpeg'),
(111, 'WINGWOOD FARM COOL AGENDA', NULL, NULL, '2021-03-28', NULL, 'MACHO', 2, 'NEGRO CON MANCHAS', NULL, 'ACTIVA', '2026-03-19', 12, NULL, 'cabras/cabra_69bc379fd4574.jpeg'),
(112, 'WINGWOOD FARM COOL ALDEAN', NULL, NULL, '2020-03-24', NULL, 'MACHO', 2, 'CAFE', NULL, 'ACTIVA', '2026-03-19', 12, NULL, 'cabras/cabra_69bc37fb2801e.jpeg'),
(113, 'BLISSBERRY M ROCK', NULL, NULL, '2020-01-01', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-03-19', 12, NULL, 'cabras/cabra_69bc38df94b06.jpeg'),
(114, 'REDWOOD HILLS VISION OF TOMORROW', NULL, NULL, '2021-02-18', NULL, 'MACHO', 2, 'CAFE CON MANCHAS', NULL, 'ACTIVA', '2026-03-19', 12, NULL, 'cabras/cabra_69bc396be2797.jpeg'),
(115, 'RINUTE BOOMERANG AURELIO', 62, 35, '2026-03-28', NULL, 'MACHO', 2, 'Bayo manchado', 5, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69d71e187b591.jpeg'),
(116, 'RINUTE BLAZE AMÉRICA', 78, 38, '2026-03-28', NULL, 'HEMBRA', 2, 'Cafe', 5, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69c85218546c8.jpeg'),
(117, 'RINUTE ETHOS ADÁN', 28, 41, '2026-03-24', NULL, 'MACHO', 2, 'Negro mancha blanca en la fren', 5, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69d71e72b6de2.jpeg'),
(118, 'RINUTE ETHOS ABRAHAM', 28, 41, '2026-03-24', NULL, 'MACHO', 2, 'Negro', 5, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69d71e93c0323.jpeg'),
(119, 'RINUTE ATHLAS REMUS ALEGRÍA', 76, 33, '2026-03-26', NULL, 'HEMBRA', 1, 'CHAMOIS', 5, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69c85a517618e.jpeg'),
(120, 'NAPOLEÓN', NULL, NULL, '2020-01-01', NULL, 'MACHO', 3, 'Blanco', NULL, 'ACTIVA', '2026-03-28', 12, NULL, NULL),
(121, 'ZEUS ARES', NULL, NULL, '2020-01-01', NULL, 'MACHO', 3, 'Blanco', NULL, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69c85f59181fe.jpeg'),
(122, 'MARGARITA', 123, 59, '2023-08-07', NULL, 'HEMBRA', 2, 'Bayo', NULL, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69c86169b08d2.jpeg'),
(123, 'MILAGROS', NULL, 128, '2020-11-06', NULL, 'HEMBRA', 2, 'Bayo', NULL, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69c861d5a25af.jpeg'),
(124, 'ADUKI', NULL, NULL, '2020-01-01', NULL, 'MACHO', 1, '', NULL, 'INACTIVA', '2026-03-28', 12, 12, NULL),
(125, 'RINUTE BLAZE CATHLEYA', 53, 38, '2025-02-27', NULL, 'HEMBRA', 2, 'Negro', 5, 'ACTIVA', '2026-03-28', 12, NULL, NULL),
(126, 'RINUTE GUADALUPANO HIERBA BUENA', NULL, NULL, '2025-12-30', NULL, 'HEMBRA', NULL, 'Chamois', NULL, 'ACTIVA', '2026-03-28', 12, NULL, NULL),
(127, 'RINUTE GUADALUPANO ARBOL DE JADE', 69, 55, '2025-12-30', NULL, 'MACHO', 1, 'Chamois', 5, 'ACTIVA', '2026-03-28', 12, 12, 'cabras/cabra_69d72a15933a8.jpeg'),
(128, 'RIGOR', NULL, NULL, '2020-01-01', NULL, 'MACHO', 2, 'Cafe', NULL, 'ACTIVA', '2026-04-08', 12, NULL, 'cabras/cabra_69d71d1793787.jpeg'),
(129, 'JAMAICA', NULL, 128, '2021-01-08', NULL, 'HEMBRA', 2, 'Café', NULL, 'ACTIVA', '2026-04-08', 12, NULL, 'cabras/cabra_69d71d777a9d5.jpeg'),
(130, 'CRONOS', NULL, 42, '2023-10-20', NULL, 'MACHO', 2, 'Cafe', NULL, 'ACTIVA', '2026-04-08', 12, NULL, 'cabras/cabra_69d7205ed99d8.JPG'),
(131, 'EXIMER KAST DEMUR', NULL, NULL, '2020-01-01', NULL, 'MACHO', 2, 'Bayo', NULL, 'ACTIVA', '2026-04-08', 12, 12, 'cabras/cabra_69d72231f106b.jpeg'),
(132, 'PRINCESA', 129, 59, '2021-03-09', NULL, 'HEMBRA', 2, 'Cafe', NULL, 'ACTIVA', '2026-04-08', 12, 12, 'cabras/cabra_69d722b678940.jpeg'),
(133, 'BOYACA', 123, 59, '2021-08-08', NULL, 'HEMBRA', 2, 'Bayo', NULL, 'ACTIVA', '2026-04-08', 12, 12, 'cabras/cabra_69d72434996e3.jpeg'),
(134, 'LUCKY STRIKE', NULL, NULL, '2020-01-01', NULL, 'MACHO', 2, '', NULL, 'INACTIVA', '2026-04-08', 12, 12, NULL),
(135, 'LUCKY STRIKE', NULL, NULL, '2020-01-01', NULL, 'MACHO', 2, '', NULL, 'ACTIVA', '2026-04-08', 12, NULL, NULL),
(136, 'FREYA', 28, 36, '2024-03-13', NULL, 'HEMBRA', 2, 'Negro', NULL, 'ACTIVA', '2026-04-08', 12, NULL, 'cabras/cabra_69d728f332433.JPG'),
(137, 'Rinute Ethos Alfredo', 89, 41, '2026-03-29', NULL, 'MACHO', 2, 'Café', 5, 'ACTIVA', '2026-05-02', 12, 12, 'cabras/cabra_69f641eb254c4.jpeg'),
(138, 'BEARLY  ARTOIS ADUKI', NULL, NULL, '2022-02-24', NULL, 'MACHO', 1, 'Bayo', NULL, 'INACTIVA', '2026-05-02', 12, 12, 'cabras/cabra_69f67b6c084d9.jpeg'),
(139, 'RINUTE REMUS HANNY', 71, 33, '2026-03-28', 44.92, 'HEMBRA', 1, 'Bayo', 5, 'ACTIVA', '2026-05-02', 12, 12, 'cabras/cabra_6aa43c1a09c5a.jpeg'),
(140, 'RINUTE REMUS ANÍS', 71, 33, '2026-03-28', NULL, 'HEMBRA', 1, 'Bayo', 5, 'ACTIVA', '2026-05-02', 12, 12, 'cabras/cabra_69f6855a3c296.jpeg'),
(141, 'RINUTE REMUS ARTURO', 71, 33, '2026-03-28', NULL, 'MACHO', 1, 'Bayo', 5, 'ACTIVA', '2026-05-02', 12, NULL, 'cabras/cabra_69f685cf82872.jpeg'),
(142, 'RINUTE ETHOS AMANECER', 89, 41, '2026-03-29', NULL, 'HEMBRA', 2, 'Mora', 5, 'ACTIVA', '2026-05-02', 12, NULL, 'cabras/cabra_69f6869963240.jpeg'),
(143, 'RINUTE ETHOS AURORA', 89, 41, '2026-03-02', NULL, 'HEMBRA', 2, 'Negro', 5, 'ACTIVA', '2026-05-02', 12, NULL, 'cabras/cabra_69f686f3b7b46.jpeg'),
(144, 'RINUTE ETHOS ARCOÍRIS', 89, 41, '2026-03-02', NULL, 'HEMBRA', 2, 'Cafe', 5, 'ACTIVA', '2026-05-02', 12, NULL, 'cabras/cabra_69f687604aeeb.jpeg'),
(145, 'pruebaa', 70, 38, '2026-05-11', NULL, 'MACHO', 1, 'blanco', 5, 'ACTIVA', NULL, 12, 12, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canastillas`
--

CREATE TABLE `canastillas` (
  `id_canastilla` int UNSIGNED NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `canastillas`
--

INSERT INTO `canastillas` (`id_canastilla`, `nombre`, `descripcion`, `fecha_registro`) VALUES
(1, 'pruebaa', '', '2026-09-04 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `controles_sanitarios`
--

CREATE TABLE `controles_sanitarios` (
  `id_control` int NOT NULL,
  `id_cabra` int NOT NULL,
  `fecha_control` date NOT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL,
  `peso_nacer_kg` decimal(5,2) DEFAULT NULL,
  `condicion_especial` enum('vacia','preñada','lactante','nacimiento') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `orejas` enum('normal','anormal') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mucosas` enum('normal','anormal') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vitaminacion` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `purga` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `registrado_por` int DEFAULT NULL,
  `c_corporal` enum('1','2','3','4','5') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `genitales` enum('normal','anormal') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ubre` enum('normal','anormal') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_ubre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `drack_score` enum('1','2','3','4','5') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `famacha` enum('1','2','3','4','5') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sin_muda` enum('si','no') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pinzas` enum('1','2') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `primeros_medios` enum('1','2') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `segundos_medios` enum('1','2') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremos` enum('1','2') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desgaste` enum('si','no') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `perdidas_dentales` enum('si','no') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cascos` enum('normal','anormal') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `e_interdigital` enum('normal','anormal') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `controles_sanitarios`
--

INSERT INTO `controles_sanitarios` (`id_control`, `id_cabra`, `fecha_control`, `peso_kg`, `peso_nacer_kg`, `condicion_especial`, `orejas`, `mucosas`, `vitaminacion`, `purga`, `observaciones`, `registrado_por`, `c_corporal`, `genitales`, `ubre`, `foto_ubre`, `drack_score`, `famacha`, `sin_muda`, `pinzas`, `primeros_medios`, `segundos_medios`, `extremos`, `desgaste`, `perdidas_dentales`, `cascos`, `e_interdigital`) VALUES
(1, 62, '2025-09-20', 46.00, 2.50, 'preñada', 'normal', 'normal', '', '', 'casco izquierdo con dolor', 12, '3', '', '', NULL, '1', '2', 'no', '2', '', '', '', 'no', 'no', 'anormal', 'normal'),
(3, 138, '2026-08-19', 45.00, 25.00, 'nacimiento', 'normal', 'normal', 'fbd', 'dfbdb', '', 12, '2', 'anormal', 'normal', NULL, '2', '2', 'no', '1', '1', '2', '1', 'si', 'si', 'normal', 'normal'),
(4, 141, '2026-08-18', 34.00, 10.00, 'vacia', 'normal', 'normal', 'fbd', 'dfbdb', '', 12, '3', 'normal', 'anormal', NULL, '2', '2', 'si', '1', '1', '1', '1', 'si', 'si', 'normal', 'normal'),
(5, 142, '2026-09-04', 12.00, 10.00, 'lactante', 'normal', 'normal', 'fbd', 'dfbdb', '', 12, '4', 'normal', 'normal', NULL, '3', '3', 'si', '2', '2', '2', '1', 'si', 'no', 'normal', 'normal'),
(6, 139, '2026-09-07', 15.00, 45.00, 'vacia', 'normal', 'normal', 'fbd', 'dfbdb', '', 12, '3', 'normal', 'anormal', NULL, '2', '3', 'si', '2', '1', '1', '1', 'si', 'si', 'normal', 'normal'),
(7, 139, '2026-09-15', 34.00, 34.00, 'vacia', 'normal', 'normal', 'fbd', 'dfbdb', '', 12, '3', 'normal', 'normal', NULL, '3', '1', 'si', '1', '2', '1', '2', 'si', 'si', 'normal', 'normal'),
(8, 139, '2026-09-30', 12.00, 12.00, 'preñada', 'normal', 'anormal', 'fbd', '', '', 12, '4', 'normal', 'normal', NULL, '3', '2', 'si', '2', '2', '2', '1', 'no', 'si', 'normal', 'anormal'),
(10, 53, '2026-09-23', 34.00, 34.00, 'lactante', 'normal', 'normal', 'fbd', 'dfbdb', '', 12, '2', 'normal', 'anormal', NULL, '1', '1', 'no', '1', '1', '1', '1', 'si', 'si', 'normal', 'normal'),
(11, 53, '2026-09-28', 25.00, NULL, 'vacia', 'normal', 'normal', '45', '45', '', 12, '1', 'normal', 'normal', NULL, '4', '2', 'no', '1', '2', '1', '1', 'no', 'no', 'normal', 'normal'),
(12, 53, '2026-09-28', 23.00, NULL, 'lactante', 'normal', 'normal', 'fbd', 'dfbdb', '', 12, '3', 'normal', 'normal', NULL, '3', '1', 'si', '1', '2', '1', '1', 'si', 'si', 'normal', 'normal'),
(13, 53, '2026-09-28', 25.00, NULL, 'vacia', 'anormal', 'normal', 'fbd', 'dfbdb', '', 12, '4', 'normal', 'normal', NULL, '1', '2', 'si', '1', '1', '1', '1', 'si', 'si', 'normal', 'anormal'),
(15, 53, '2026-09-28', 25.00, NULL, 'lactante', 'normal', 'normal', 'fbd', '45', '', 12, '3', 'normal', 'normal', NULL, '1', '2', 'si', '2', '1', '1', '2', 'si', 'si', 'normal', 'normal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_produccion_lechera`
--

CREATE TABLE `control_produccion_lechera` (
  `id_control_produccion` int NOT NULL,
  `id_cabra` int NOT NULL,
  `id_lactancia` int DEFAULT NULL,
  `turno_ordeño` enum('MAÑANA','TARDE') NOT NULL,
  `cantidad_litros` decimal(10,2) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `control_produccion_lechera`
--

INSERT INTO `control_produccion_lechera` (`id_control_produccion`, `id_cabra`, `id_lactancia`, `turno_ordeño`, `cantidad_litros`, `fecha_registro`) VALUES
(16, 53, 1, 'MAÑANA', 12.00, '2026-09-23 05:00:00'),
(17, 53, 1, 'TARDE', 10.00, '2026-09-23 05:00:00'),
(18, 53, 1, 'MAÑANA', 50.00, '2026-09-24 05:00:00'),
(19, 53, 1, 'TARDE', 16.00, '2026-09-24 05:00:00'),
(20, 53, 1, 'MAÑANA', 10.00, '2026-09-25 05:00:00'),
(21, 53, 1, 'TARDE', 25.00, '2026-09-25 05:00:00'),
(22, 53, 1, 'MAÑANA', 25.00, '2026-09-26 05:00:00'),
(23, 53, 1, 'TARDE', 15.00, '2026-09-26 05:00:00'),
(24, 53, 1, 'MAÑANA', 10.00, '2026-09-27 05:00:00'),
(25, 53, 1, 'TARDE', 23.00, '2026-09-27 05:00:00'),
(26, 53, 1, 'MAÑANA', 12.00, '2026-09-28 05:00:00'),
(27, 53, 2, 'MAÑANA', 45.00, '2026-09-28 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_cabras`
--

CREATE TABLE `documentos_cabras` (
  `id_documento` int NOT NULL,
  `id_cabra` int NOT NULL,
  `tipo_documento` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ruta_archivo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_subida` datetime DEFAULT CURRENT_TIMESTAMP,
  `subido_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos_cabras`
--

INSERT INTO `documentos_cabras` (`id_documento`, `id_cabra`, `tipo_documento`, `ruta_archivo`, `fecha_subida`, `subido_por`) VALUES
(1, 33, 'REGISTRO AGDA', 'documentos/doc_697e5f869945c_REGISTRO WILLOW RUN ATLAS REMUS .jpg', '2026-01-31 15:01:10', 12),
(2, 49, 'REGISTRO AGDA', 'documentos/doc_697e5fadb70ee_CHERRY GLEN RADICAL HIGHLIGHT.jpg', '2026-01-31 15:01:49', 12),
(3, 39, 'REGISTRO AGDA', 'documentos/doc_697e5fd54d445_REGISTRO DKGH MV ROSE´S RESULT.jpg', '2026-01-31 15:02:29', 12),
(4, 38, 'REGISTRO AGDA', 'documentos/doc_697e5ff0d0cfb_REGISTRO DKGH DR. MW BLAZE.jpg', '2026-01-31 15:02:56', 12),
(5, 37, 'REGISTRO AGDA', 'documentos/doc_697e6014ed1bb_REGISTRO DKGH TAKE ME TO RIO.jpg', '2026-01-31 15:03:32', 12),
(6, 34, 'REGISTRO AGDA', 'documentos/doc_697e60b1f018a_REGISTRO OLENTAGY FIERY ARCHER.jpg', '2026-01-31 15:06:09', 12),
(7, 40, 'REGISTRO AGDA', 'documentos/doc_697e60d5961a8_REGISTRO PRUITVILLE´S NC MIGHTY ODIN.jpg', '2026-01-31 15:06:45', 12),
(8, 41, 'REGISTRO AGDA', 'documentos/doc_697e611bab022_REGISTRO DKGH ROYAL MARCUS ETHOS.jpg', '2026-01-31 15:07:55', 12),
(9, 42, 'REGISTRO AGDA', 'documentos/doc_697e61372ef16_REGISTRO DKGH GADO ROOTBEER´S REBEL.jpg', '2026-01-31 15:08:23', 12),
(10, 43, 'REGISTRO AGDA', 'documentos/doc_697e615b33a77_ZOLDYCK_PEDIGREE (2).pdf', '2026-01-31 15:08:59', 12),
(11, 102, 'REGISTRO AGDA', 'documentos/doc_697e61db5440a_REGISTRO SUNCHINE REHMA REPRISE.jpg', '2026-01-31 15:11:07', 12),
(12, 139, '', 'documentos/doc_6aa9920e218b5_1_Infografia_inscripcion_componente_practico_laboratorios_salida_de_campo_2021.pdf', '2026-09-15 13:44:30', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos_reproductivos`
--

CREATE TABLE `eventos_reproductivos` (
  `id_evento` int NOT NULL,
  `id_cabra` int NOT NULL,
  `fecha_evento` date NOT NULL,
  `tipo_evento` enum('CELO','MONTA','INSEMINACION','DIAGNOSTICO_GESTACION','GESTANTE','ABORTO','SECADO','VACIA') COLLATE utf8mb4_general_ci NOT NULL,
  `id_semental` int DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `registrado_por` int DEFAULT NULL,
  `id_pajilla` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos_reproductivos`
--

INSERT INTO `eventos_reproductivos` (`id_evento`, `id_cabra`, `fecha_evento`, `tipo_evento`, `id_semental`, `observaciones`, `registrado_por`, `id_pajilla`) VALUES
(1, 62, '2025-10-30', 'INSEMINACION', 35, 'PREÑADA', 12, NULL),
(2, 78, '2025-10-30', 'INSEMINACION', 35, 'PREÑADA', 12, NULL),
(3, 71, '2025-10-30', 'INSEMINACION', 33, 'PREÑADA', 12, NULL),
(4, 76, '2025-10-30', 'INSEMINACION', 33, 'PREÑADA', 12, NULL),
(5, 28, '2025-10-30', 'INSEMINACION', 41, 'PREÑADA', 12, NULL),
(6, 89, '2025-10-20', 'INSEMINACION', 41, 'PREÑADA', 12, NULL),
(7, 50, '2026-03-19', 'INSEMINACION', 110, 'Pajilla sexada ', 12, NULL),
(8, 48, '2026-03-19', 'INSEMINACION', 110, 'Pajilla sexada ', 12, NULL),
(9, 57, '2026-03-19', 'INSEMINACION', 114, 'Pajilla sexada', 12, NULL),
(10, 52, '2026-03-19', 'INSEMINACION', 111, 'Pajilla sexada ', 12, NULL),
(11, 72, '2026-03-19', 'INSEMINACION', 120, '', 12, NULL),
(12, 73, '2026-03-19', 'INSEMINACION', 121, '', 12, NULL),
(13, 68, '2026-03-19', 'INSEMINACION', 124, '', 12, NULL),
(14, 69, '2026-03-19', 'INSEMINACION', 124, '', 12, NULL),
(15, 125, '2026-03-19', 'INSEMINACION', 114, 'Pajilla sexada', 12, NULL),
(16, 51, '2026-04-07', 'MONTA', 91, '', 12, NULL),
(17, 70, '2026-03-19', 'INSEMINACION', 43, 'Inseminación Diego ', 12, NULL),
(18, 70, '2026-04-11', 'DIAGNOSTICO_GESTACION', 43, 'Preñada 2 bebés ', 12, NULL),
(19, 70, '2026-05-02', 'DIAGNOSTICO_GESTACION', 43, 'Preñada se evidencian dos fetos ', 12, NULL),
(20, 68, '2026-05-02', 'DIAGNOSTICO_GESTACION', 138, 'Preñada 2 fetos ', 12, NULL),
(21, 50, '2026-05-02', 'DIAGNOSTICO_GESTACION', 110, 'Vacía  ', 12, NULL),
(22, 48, '2026-05-02', 'DIAGNOSTICO_GESTACION', 110, 'Preñada dos fetos ', 12, NULL),
(23, 57, '2026-05-02', 'DIAGNOSTICO_GESTACION', 114, 'Preñada dos fetos ', 12, NULL),
(24, 72, '2026-05-02', 'DIAGNOSTICO_GESTACION', 120, 'Preñada tres fetos ', 12, NULL),
(25, 75, '2026-05-02', 'DIAGNOSTICO_GESTACION', 114, 'Vacía  ', 12, NULL),
(26, 69, '2026-06-13', 'DIAGNOSTICO_GESTACION', 138, 'Vacía ', 12, NULL),
(27, 73, '2026-06-13', 'DIAGNOSTICO_GESTACION', 121, 'Vacía ', 12, NULL),
(28, 125, '2026-06-13', 'DIAGNOSTICO_GESTACION', 114, 'Vacía ', 12, NULL),
(29, 142, '2026-09-04', 'INSEMINACION', NULL, '', 12, 1),
(30, 144, '2026-09-07', 'INSEMINACION', NULL, '', 12, 2),
(31, 139, '2026-09-17', 'MONTA', 39, '', 12, NULL),
(32, 139, '2026-09-18', 'MONTA', 38, '', 12, NULL);

--
-- Disparadores `eventos_reproductivos`
--
DELIMITER $$
CREATE TRIGGER `validar_evento_reproductivo` BEFORE INSERT ON `eventos_reproductivos` FOR EACH ROW BEGIN
    DECLARE sexo_animal ENUM('MACHO', 'HEMBRA');
    SELECT sexo INTO sexo_animal FROM cabras WHERE id_cabra = NEW.id_cabra;
    IF sexo_animal != 'HEMBRA' AND NEW.tipo_evento IN ('GESTANTE', 'ABORTO', 'SECADO') THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: Solo las hembras pueden tener este evento reproductivo';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_propiedad`
--

CREATE TABLE `historial_propiedad` (
  `id_historial` int NOT NULL,
  `id_cabra` int NOT NULL,
  `id_propietario` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `motivo_cambio` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio_transaccion` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lactancias`
--

CREATE TABLE `lactancias` (
  `id_lactancia` int NOT NULL,
  `id_cabra` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('EN LACTANCIA','SECADA') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EN LACTANCIA',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `numero_lactancia` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lactancias`
--

INSERT INTO `lactancias` (`id_lactancia`, `id_cabra`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`, `numero_lactancia`) VALUES
(1, 53, '2026-09-23', '2026-09-28', 'SECADA', '2026-09-28 20:46:25', '2026-09-28 20:48:29', 1),
(2, 53, '2026-09-28', NULL, 'EN LACTANCIA', '2026-09-28 21:45:53', '2026-09-28 21:45:53', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pajillas`
--

CREATE TABLE `pajillas` (
  `id_pajilla` int UNSIGNED NOT NULL,
  `id_canastilla` int UNSIGNED NOT NULL,
  `nombre_ejemplar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registro_ejemplar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tamano` enum('0.50 ml','0.25 ml') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.50 ml',
  `cantidad` int UNSIGNED DEFAULT '0',
  `dosis_disponibles` int UNSIGNED DEFAULT '0',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pajillas`
--

INSERT INTO `pajillas` (`id_pajilla`, `id_canastilla`, `nombre_ejemplar`, `registro_ejemplar`, `foto`, `tamano`, `cantidad`, `dosis_disponibles`, `observaciones`, `fecha_registro`) VALUES
(1, 1, 'pocholo', '000.20', 'pajillas/pajilla_6a9ace90630f2.png', '0.50 ml', 0, 0, 'ninguna', '2026-09-04 05:00:00'),
(2, 1, 'htrhttyh', '', NULL, '0.25 ml', 0, 0, '', '2026-09-07 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partos`
--

CREATE TABLE `partos` (
  `id_parto` int NOT NULL,
  `id_madre` int NOT NULL,
  `id_padre` int DEFAULT NULL,
  `fecha_parto` date NOT NULL,
  `numero_crias` tinyint DEFAULT '1',
  `peso_total_crias` decimal(5,2) DEFAULT NULL,
  `tipo_parto` enum('SIMPLE','GEMELAR','TRIPLE','MULTIPLE') COLLATE utf8mb4_general_ci DEFAULT 'SIMPLE',
  `dificultad` enum('NORMAL','ASISTIDO','CESAREO') COLLATE utf8mb4_general_ci DEFAULT 'NORMAL',
  `observaciones` text COLLATE utf8mb4_general_ci,
  `registrado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partos`
--

INSERT INTO `partos` (`id_parto`, `id_madre`, `id_padre`, `fecha_parto`, `numero_crias`, `peso_total_crias`, `tipo_parto`, `dificultad`, `observaciones`, `registrado_por`) VALUES
(6, 78, 35, '2026-03-28', 1, 3.40, 'SIMPLE', 'ASISTIDO', 'AURELIO\r\nCría grande poca dilatación.', 12),
(7, 28, 41, '2026-03-24', 3, 10.00, 'TRIPLE', 'ASISTIDO', 'Muere primer cabro - parto de adelanta 4 días ', 12),
(8, 76, 33, '2026-03-27', 1, 4.00, 'SIMPLE', 'ASISTIDO', 'Parto adelantado 1 día, sin complicaciones ', 12),
(9, 62, 35, '2026-03-28', 1, 4.30, 'SIMPLE', 'ASISTIDO', '', 12),
(10, 89, 41, '2026-03-29', 4, 10.00, 'MULTIPLE', 'ASISTIDO', '3 hembras 1 macho \r\nNacieron dos embarazada al tiempo  ', 12),
(11, 139, 88, '2026-09-15', 2, 78.00, 'GEMELAR', 'NORMAL', '', 12),
(12, 139, 35, '2026-09-30', 1, 89.00, 'SIMPLE', 'NORMAL', '', 12);

--
-- Disparadores `partos`
--
DELIMITER $$
CREATE TRIGGER `validar_parto_hembra` BEFORE INSERT ON `partos` FOR EACH ROW BEGIN
    DECLARE sexo_madre ENUM('MACHO', 'HEMBRA');
    SELECT sexo INTO sexo_madre FROM cabras WHERE id_cabra = NEW.id_madre;
    IF sexo_madre != 'HEMBRA' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: Solo las hembras pueden tener partos';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietarios`
--

CREATE TABLE `propietarios` (
  `id_propietario` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `identificacion` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `propietarios`
--

INSERT INTO `propietarios` (`id_propietario`, `nombre`, `identificacion`, `direccion`, `telefono`, `email`, `fecha_registro`) VALUES
(5, 'LUISA FERNANDA NUÑEZ RAMOS', '1083903896', 'vereda camberos', '3104887380', 'apriscomicorral@gmail.com', '2026-01-28'),
(6, 'NICOLAS GAMBOA APACHE', '16547056', 'CORREGIMIENTO LA TULIA BOLIVAR ', '3183881984', '', '2026-01-28'),
(7, 'MESIAS CHAVEZ', '12969968', 'SANDONA', '', '', '2026-01-31'),
(8, 'NILSON RICARDO VALDEZ RODRIGUEZ', '7963841', 'NEIVA', '3143064695', '', '2026-01-31'),
(9, 'DIEGO ALBERTO AVILA', '1075251674', 'SAN VICENTE- EL CORRAL DE LA FORTUNA', '', '', '2026-01-31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `razas`
--

CREATE TABLE `razas` (
  `id_raza` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `razas`
--

INSERT INTO `razas` (`id_raza`, `nombre`) VALUES
(1, 'Alpina'),
(2, 'Anglonubiana'),
(3, 'Saanen'),
(6, 'Cruces');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_ultimo_acceso` timestamp NULL DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `telefono`, `fecha_registro`, `fecha_ultimo_acceso`, `activo`) VALUES
(12, 'LUISA ADMIN', 'apriscomicorral@gmail.com', '$2y$10$nN6JQ7qorW04Gye5qz6zcOPGFjsAJ8zsVzrw4KsvcOhe5bW5Wgz6G', '3014887380', '2025-07-15 14:55:34', '2026-09-29 13:54:18', 1),
(13, 'JOSE RICARDO - VER', 'apriscomicorral2@gmail.com', '$2y$10$0m63ZM/7M2glXM4Lfj1ryetfmHvTXRGUhMBF5dma2AUdxUB/Rz3BG', '3112297792', '2026-01-31 20:23:15', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_pajillas`
--

CREATE TABLE `ventas_pajillas` (
  `id_venta` int UNSIGNED NOT NULL,
  `id_pajilla` int UNSIGNED NOT NULL,
  `cantidad_dosis` int UNSIGNED NOT NULL,
  `fecha_venta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registrado_por` int DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas_pajillas`
--

INSERT INTO `ventas_pajillas` (`id_venta`, `id_pajilla`, `cantidad_dosis`, `fecha_venta`, `registrado_por`, `observaciones`) VALUES
(1, 1, 1, '2026-09-04 14:10:37', 12, 'yurgen'),
(2, 2, 1, '2026-09-11 15:45:20', 12, 'errerer');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cabras`
--
ALTER TABLE `cabras`
  ADD PRIMARY KEY (`id_cabra`),
  ADD KEY `madre` (`madre`),
  ADD KEY `padre` (`padre`),
  ADD KEY `id_raza` (`id_raza`),
  ADD KEY `id_propietario_actual` (`id_propietario_actual`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `modificado_por` (`modificado_por`);

--
-- Indices de la tabla `canastillas`
--
ALTER TABLE `canastillas`
  ADD PRIMARY KEY (`id_canastilla`),
  ADD KEY `idx_canastilla_nombre` (`nombre`);

--
-- Indices de la tabla `controles_sanitarios`
--
ALTER TABLE `controles_sanitarios`
  ADD PRIMARY KEY (`id_control`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `idx_cabra_fecha` (`id_cabra`,`fecha_control`);

--
-- Indices de la tabla `control_produccion_lechera`
--
ALTER TABLE `control_produccion_lechera`
  ADD PRIMARY KEY (`id_control_produccion`),
  ADD KEY `fk_produccion_cabra` (`id_cabra`),
  ADD KEY `idx_control_produccion_lactancia` (`id_lactancia`);

--
-- Indices de la tabla `documentos_cabras`
--
ALTER TABLE `documentos_cabras`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_cabra` (`id_cabra`),
  ADD KEY `subido_por` (`subido_por`);

--
-- Indices de la tabla `eventos_reproductivos`
--
ALTER TABLE `eventos_reproductivos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `id_semental` (`id_semental`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `idx_cabra_evento` (`id_cabra`,`fecha_evento`),
  ADD KEY `fk_evento_pajilla` (`id_pajilla`);

--
-- Indices de la tabla `historial_propiedad`
--
ALTER TABLE `historial_propiedad`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_propietario` (`id_propietario`),
  ADD KEY `idx_cabra_propiedad` (`id_cabra`,`fecha_inicio`);

--
-- Indices de la tabla `lactancias`
--
ALTER TABLE `lactancias`
  ADD PRIMARY KEY (`id_lactancia`),
  ADD UNIQUE KEY `uq_lactancias_cabra_numero` (`id_cabra`,`numero_lactancia`),
  ADD KEY `idx_lactancias_cabra` (`id_cabra`),
  ADD KEY `idx_lactancias_estado` (`estado`);

--
-- Indices de la tabla `pajillas`
--
ALTER TABLE `pajillas`
  ADD PRIMARY KEY (`id_pajilla`),
  ADD KEY `idx_pajilla_canastilla` (`id_canastilla`),
  ADD KEY `idx_pajilla_tamano` (`tamano`),
  ADD KEY `idx_pajilla_dosis` (`dosis_disponibles`);

--
-- Indices de la tabla `partos`
--
ALTER TABLE `partos`
  ADD PRIMARY KEY (`id_parto`),
  ADD KEY `id_padre` (`id_padre`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `idx_madre_fecha` (`id_madre`,`fecha_parto`);

--
-- Indices de la tabla `propietarios`
--
ALTER TABLE `propietarios`
  ADD PRIMARY KEY (`id_propietario`),
  ADD UNIQUE KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `razas`
--
ALTER TABLE `razas`
  ADD PRIMARY KEY (`id_raza`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_activo` (`activo`);

--
-- Indices de la tabla `ventas_pajillas`
--
ALTER TABLE `ventas_pajillas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `idx_venta_pajilla` (`id_pajilla`),
  ADD KEY `idx_venta_fecha` (`fecha_venta`),
  ADD KEY `fk_venta_usuario` (`registrado_por`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cabras`
--
ALTER TABLE `cabras`
  MODIFY `id_cabra` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT de la tabla `canastillas`
--
ALTER TABLE `canastillas`
  MODIFY `id_canastilla` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `controles_sanitarios`
--
ALTER TABLE `controles_sanitarios`
  MODIFY `id_control` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `control_produccion_lechera`
--
ALTER TABLE `control_produccion_lechera`
  MODIFY `id_control_produccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `documentos_cabras`
--
ALTER TABLE `documentos_cabras`
  MODIFY `id_documento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `eventos_reproductivos`
--
ALTER TABLE `eventos_reproductivos`
  MODIFY `id_evento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `historial_propiedad`
--
ALTER TABLE `historial_propiedad`
  MODIFY `id_historial` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `lactancias`
--
ALTER TABLE `lactancias`
  MODIFY `id_lactancia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pajillas`
--
ALTER TABLE `pajillas`
  MODIFY `id_pajilla` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `partos`
--
ALTER TABLE `partos`
  MODIFY `id_parto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `propietarios`
--
ALTER TABLE `propietarios`
  MODIFY `id_propietario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `razas`
--
ALTER TABLE `razas`
  MODIFY `id_raza` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ventas_pajillas`
--
ALTER TABLE `ventas_pajillas`
  MODIFY `id_venta` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cabras`
--
ALTER TABLE `cabras`
  ADD CONSTRAINT `cabras_ibfk_1` FOREIGN KEY (`madre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `cabras_ibfk_2` FOREIGN KEY (`padre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `cabras_ibfk_3` FOREIGN KEY (`id_raza`) REFERENCES `razas` (`id_raza`),
  ADD CONSTRAINT `cabras_ibfk_4` FOREIGN KEY (`id_propietario_actual`) REFERENCES `propietarios` (`id_propietario`),
  ADD CONSTRAINT `cabras_ibfk_5` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `cabras_ibfk_6` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `controles_sanitarios`
--
ALTER TABLE `controles_sanitarios`
  ADD CONSTRAINT `controles_sanitarios_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `controles_sanitarios_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `control_produccion_lechera`
--
ALTER TABLE `control_produccion_lechera`
  ADD CONSTRAINT `fk_control_produccion_lactancia` FOREIGN KEY (`id_lactancia`) REFERENCES `lactancias` (`id_lactancia`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produccion_cabra` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `documentos_cabras`
--
ALTER TABLE `documentos_cabras`
  ADD CONSTRAINT `documentos_cabras_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `documentos_cabras_ibfk_2` FOREIGN KEY (`subido_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `eventos_reproductivos`
--
ALTER TABLE `eventos_reproductivos`
  ADD CONSTRAINT `eventos_reproductivos_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `eventos_reproductivos_ibfk_2` FOREIGN KEY (`id_semental`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `eventos_reproductivos_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_evento_pajilla` FOREIGN KEY (`id_pajilla`) REFERENCES `pajillas` (`id_pajilla`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_propiedad`
--
ALTER TABLE `historial_propiedad`
  ADD CONSTRAINT `historial_propiedad_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `historial_propiedad_ibfk_2` FOREIGN KEY (`id_propietario`) REFERENCES `propietarios` (`id_propietario`);

--
-- Filtros para la tabla `lactancias`
--
ALTER TABLE `lactancias`
  ADD CONSTRAINT `fk_lactancias_cabra` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `pajillas`
--
ALTER TABLE `pajillas`
  ADD CONSTRAINT `fk_pajilla_canastilla` FOREIGN KEY (`id_canastilla`) REFERENCES `canastillas` (`id_canastilla`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `partos`
--
ALTER TABLE `partos`
  ADD CONSTRAINT `partos_ibfk_1` FOREIGN KEY (`id_madre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `partos_ibfk_2` FOREIGN KEY (`id_padre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `partos_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `ventas_pajillas`
--
ALTER TABLE `ventas_pajillas`
  ADD CONSTRAINT `fk_venta_pajilla` FOREIGN KEY (`id_pajilla`) REFERENCES `pajillas` (`id_pajilla`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venta_usuario` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

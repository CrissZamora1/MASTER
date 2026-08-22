-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-08-2026 a las 08:54:35
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
-- Base de datos: `sistema_gestion_casas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_proyectos`
--

CREATE TABLE `asignacion_proyectos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `proyecto_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-livewire-rate-limiter:0a256906fc1af20be2a1dbca4c4a68c9718a72c4', 'i:1;', 1787310341),
('laravel-cache-livewire-rate-limiter:0a256906fc1af20be2a1dbca4c4a68c9718a72c4:timer', 'i:1787310341;', 1787310341);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casas`
--

CREATE TABLE `casas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proyecto_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_casa_id` bigint(20) UNSIGNED NOT NULL,
  `numero_casa` varchar(255) NOT NULL,
  `cluster` varchar(255) DEFAULT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `acabados` tinyint(1) NOT NULL DEFAULT 0,
  `estado` enum('disponible','no_disponible','programada','reprogramada','entregado') NOT NULL DEFAULT 'no_disponible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `casas`
--

INSERT INTO `casas` (`id`, `proyecto_id`, `tipo_casa_id`, `numero_casa`, `cluster`, `anexo`, `acabados`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1', NULL, NULL, 1, 'entregado', '2026-08-20 11:54:48', '2026-08-20 12:52:55'),
(2, 1, 1, '2', NULL, NULL, 1, 'disponible', '2026-08-20 12:56:47', '2026-08-20 12:59:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `casa_id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_cita` varchar(255) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('programada','reprogramada') NOT NULL DEFAULT 'programada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `casa_id`, `cliente_id`, `tipo_cita`, `fecha_hora`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2026-08-21 10:00:00', 'programada', '2026-08-20 12:43:21', '2026-08-20 12:43:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `dpi` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `dpi`, `telefono`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Juan ', 'De la Fuente', '2002-912150-1081', '4124-2422', 'prueba@gmail.com', '2026-08-20 12:41:14', '2026-08-20 12:41:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratistas`
--

CREATE TABLE `contratistas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `especialidad` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas`
--

CREATE TABLE `entregas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cita_id` bigint(20) UNSIGNED DEFAULT NULL,
  `casa_id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_hora_entrega` datetime NOT NULL,
  `resultado` enum('entregada','entregada_con_reclamos','no_entregada') DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entregas`
--

INSERT INTO `entregas` (`id`, `cita_id`, `casa_id`, `cliente_id`, `fecha_hora_entrega`, `resultado`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-08-20 00:50:52', 'entregada', NULL, '2026-08-20 12:51:31', '2026-08-20 12:51:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `garantias`
--

CREATE TABLE `garantias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `meses_duracion` smallint(5) UNSIGNED NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `garantias`
--

INSERT INTO `garantias` (`id`, `nombre`, `meses_duracion`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Cierres de ventanas', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(2, 'Chapas de puertas', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(3, 'Mezcladoras', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(4, 'Llaves de ducha', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(5, 'Cabezas de ducha', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(6, 'Chorros (pila, patio y carport)', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(7, 'Mangueras de mezcladoras', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(8, 'Contrallaves', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(9, 'Pila', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(10, 'Funcionamiento de losa sanitaria', 3, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(11, 'Tuberías de agua potable (Fría y caliente)', 6, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(12, 'Drenajes (Pluvial y Sanitario)', 6, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(13, 'Ductos secos y circuitos eléctricos', 6, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(14, 'Interruptores', 6, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(15, 'Tomacorrientes (110v y 220v)', 6, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(16, 'Fisuras que transmitan filtraciones al interior', 18, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(17, 'Filtraciones de agua en losas', 18, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(18, 'Filtraciones de agua en muros', 18, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(19, 'Ventanería que transmita filtraciones al interior de la casa', 18, NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_19_100504_create_proyectos_table', 1),
(5, '2026_08_19_100509_create_tipo_casas_table', 1),
(6, '2026_08_19_100514_create_casas_table', 1),
(7, '2026_08_19_101818_create_rols_table', 1),
(8, '2026_08_19_102506_create_clientes_table', 1),
(9, '2026_08_19_102547_create_citas_table', 1),
(10, '2026_08_19_102824_create_entregas_table', 1),
(11, '2026_08_19_102923_create_reporte_entregas_table', 1),
(12, '2026_08_19_103440_create_garantias_table', 1),
(13, '2026_08_19_104347_add_campos_a_users_table', 1),
(14, '2026_08_19_174029_create_reclamos_table', 2),
(15, '2026_08_19_174625_create_contratistas_table', 3),
(16, '2026_08_21_080831_create_asignacion_proyectos_table', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `ubicacion`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Vilao', 'Carretera', 'Direccion ', 1, '2026-08-20 11:48:08', '2026-08-20 11:48:08'),
(2, 'Luganda', 'tal', 'tal xd', 1, '2026-08-21 15:09:21', '2026-08-21 15:09:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reclamos`
--

CREATE TABLE `reclamos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_entregas`
--

CREATE TABLE `reporte_entregas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entrega_id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','no_terminado','finalizado') NOT NULL DEFAULT 'pendiente',
  `encargado` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `orden` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `codigo`, `nombre`, `created_at`, `updated_at`, `orden`) VALUES
(1, 'MASTER', 'Master', '2026-08-21 16:48:27', '2026-08-21 16:48:27', 1),
(2, 'SUPER', 'Superintendente', '2026-08-21 14:16:33', '2026-08-21 14:16:33', 2),
(3, 'ADMIN', 'Ingeniero', '2026-08-19 16:46:43', '2026-08-19 16:46:43', 3),
(4, 'SUP', 'Supervisor', '2026-08-19 16:46:43', '2026-08-19 16:46:43', 4),
(5, 'CONT', 'Contratista', '2026-08-19 16:46:43', '2026-08-19 16:46:43', 5),
(6, 'ASIST', 'Coordinador', '2026-08-19 16:46:43', '2026-08-19 16:46:43', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6h0boxQUy8ZwK8WGmndG3YkI5ZIi1mriJBCl1ehD', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 OPR/134.0.0.0 (Edition std-2)', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidW5TZ1VLa1FibjNPdUh0WnlaMGV5RlBBUTNvWkYwbkxuU2gwRG5mOSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiNmZhYThlZGIwM2ZlYjEyOGU1NjdiYjBlYzFhMDYyNTM4ZmIyZjNmZjc2ZmJiMDM4OWMxZDBiYjAyMGUzYmM5ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czozMDoiZmlsYW1lbnQuYWRtaW4ucGFnZXMuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1787365024),
('T3myjTti7biKnFdnLsIRXSLJ3DHtc2ByHZKm7WJM', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 OPR/134.0.0.0 (Edition std-2)', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoielRNbXl5Q2lWbzFIbnJ2TExZYlhORzdHbFByR1hqendka1pjUDRHQiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiNmZhYThlZGIwM2ZlYjEyOGU1NjdiYjBlYzFhMDYyNTM4ZmIyZjNmZjc2ZmJiMDM4OWMxZDBiYjAyMGUzYmM5ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7czo1OiJyb3V0ZSI7czozNjoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnVzZXJzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1787365038),
('WBf0SB4LDPXIvJUOzPd4EzMFs2dZt3F4oSiERQie', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 OPR/134.0.0.0 (Edition std-2)', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiY2w0QXl6WERQeGVUNTZXblFzb2pkdkt0cVBCN2dDS3Frd1pnbE9kbyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiNmZhYThlZGIwM2ZlYjEyOGU1NjdiYjBlYzFhMDYyNTM4ZmIyZjNmZjc2ZmJiMDM4OWMxZDBiYjAyMGUzYmM5ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2Vycy9jcmVhdGUiO3M6NToicm91dGUiO3M6Mzc6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy51c2Vycy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1787365040);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_casas`
--

CREATE TABLE `tipo_casas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proyecto_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `metros` decimal(8,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_casas`
--

INSERT INTO `tipo_casas` (`id`, `proyecto_id`, `nombre`, `metros`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, '2N', NULL, NULL, '2026-08-20 11:52:31', '2026-08-20 11:52:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `rol_id` bigint(20) UNSIGNED DEFAULT NULL,
  `proyecto_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `apellido`, `email`, `rol_id`, `proyecto_id`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', NULL, 'test@example.com', NULL, NULL, 1, NULL, '$2y$12$XN/B8JLEap8obWdc6jVr1.wzMVb1vI2B4l2aLQGlEt1cAtzF3O0Km', NULL, '2026-08-19 16:46:43', '2026-08-19 16:46:43'),
(2, 'admin', NULL, 'admin@gmail.com', 2, NULL, 1, NULL, '$2y$12$wp7f7AyYDj3JcW2qAOVfIuLiQqgG8e4M6KvyAAfkT0SEQ4evW9Nr.', 'KHteEpVlJEtPxiVgnT2BlLV6IgWZUdplO0Q80RYOc3nJtEWkfQ4NP1Q7htX5', '2026-08-20 00:23:17', '2026-08-22 12:53:37'),
(3, 'STEVE', 'TAL', 'prueba1@gmail.com', 4, NULL, 1, NULL, '$2y$12$7vAPPMBoM.B2vU6MZwYhCe79nbi.pW79mh0wLqI2e3Jx926KvkcQi', 'MWcxn3qqocxYr1aGqlrP2d2FwR8mzyv1WrEKk3v1vsQ7e7U2kNqr0jkeP9My', '2026-08-21 15:13:28', '2026-08-21 15:50:56'),
(4, 'ARCANA', 'De la Fuente', 'prueba2@gmail.com', 4, NULL, 1, NULL, '$2y$12$YzFDu/aen5GbJm14B9/78eFdNgGKmRozFf7PVKxOFY19tCuZ5lKq.', NULL, '2026-08-21 15:13:58', '2026-08-21 15:13:58');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignacion_proyectos`
--
ALTER TABLE `asignacion_proyectos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asignacion_proyectos_user_id_proyecto_id_unique` (`user_id`,`proyecto_id`),
  ADD KEY `asignacion_proyectos_proyecto_id_foreign` (`proyecto_id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `casas`
--
ALTER TABLE `casas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `casas_proyecto_id_foreign` (`proyecto_id`),
  ADD KEY `casas_tipo_casa_id_foreign` (`tipo_casa_id`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citas_casa_id_foreign` (`casa_id`),
  ADD KEY `citas_cliente_id_foreign` (`cliente_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contratistas`
--
ALTER TABLE `contratistas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entregas_cita_id_foreign` (`cita_id`),
  ADD KEY `entregas_casa_id_foreign` (`casa_id`),
  ADD KEY `entregas_cliente_id_foreign` (`cliente_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `garantias`
--
ALTER TABLE `garantias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reporte_entregas`
--
ALTER TABLE `reporte_entregas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporte_entregas_entrega_id_foreign` (`entrega_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_codigo_unique` (`codigo`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tipo_casas`
--
ALTER TABLE `tipo_casas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_casas_proyecto_id_foreign` (`proyecto_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_rol_id_foreign` (`rol_id`),
  ADD KEY `users_proyecto_id_foreign` (`proyecto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignacion_proyectos`
--
ALTER TABLE `asignacion_proyectos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `casas`
--
ALTER TABLE `casas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `contratistas`
--
ALTER TABLE `contratistas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entregas`
--
ALTER TABLE `entregas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `garantias`
--
ALTER TABLE `garantias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reporte_entregas`
--
ALTER TABLE `reporte_entregas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_casas`
--
ALTER TABLE `tipo_casas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacion_proyectos`
--
ALTER TABLE `asignacion_proyectos`
  ADD CONSTRAINT `asignacion_proyectos_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignacion_proyectos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `casas`
--
ALTER TABLE `casas`
  ADD CONSTRAINT `casas_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `casas_tipo_casa_id_foreign` FOREIGN KEY (`tipo_casa_id`) REFERENCES `tipo_casas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_casa_id_foreign` FOREIGN KEY (`casa_id`) REFERENCES `casas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `entregas_casa_id_foreign` FOREIGN KEY (`casa_id`) REFERENCES `casas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entregas_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `entregas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporte_entregas`
--
ALTER TABLE `reporte_entregas`
  ADD CONSTRAINT `reporte_entregas_entrega_id_foreign` FOREIGN KEY (`entrega_id`) REFERENCES `entregas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tipo_casas`
--
ALTER TABLE `tipo_casas`
  ADD CONSTRAINT `tipo_casas_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

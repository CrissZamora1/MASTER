/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `asignacion_proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignacion_proyectos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `proyecto_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asignacion_proyectos_user_id_proyecto_id_unique` (`user_id`,`proyecto_id`),
  KEY `asignacion_proyectos_proyecto_id_foreign` (`proyecto_id`),
  CONSTRAINT `asignacion_proyectos_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asignacion_proyectos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `casas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proyecto_id` bigint(20) unsigned NOT NULL,
  `tipo_casa_id` bigint(20) unsigned NOT NULL,
  `numero_casa` varchar(255) NOT NULL,
  `cluster` varchar(255) DEFAULT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `acabados` tinyint(1) NOT NULL DEFAULT 0,
  `estado` enum('disponible','no_disponible','programada','reprogramada','entregado') NOT NULL DEFAULT 'no_disponible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `casas_proyecto_id_numero_casa_unique` (`proyecto_id`,`numero_casa`),
  KEY `casas_tipo_casa_id_foreign` (`tipo_casa_id`),
  CONSTRAINT `casas_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `casas_tipo_casa_id_foreign` FOREIGN KEY (`tipo_casa_id`) REFERENCES `tipo_casas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `citas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `casa_id` bigint(20) unsigned NOT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `tipo_cita` varchar(255) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('programada','reprogramada') NOT NULL DEFAULT 'programada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `citas_casa_id_foreign` (`casa_id`),
  KEY `citas_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `citas_casa_id_foreign` FOREIGN KEY (`casa_id`) REFERENCES `casas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `citas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `dpi` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contratistas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratistas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `especialidad` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contratistas_user_id_foreign` (`user_id`),
  CONSTRAINT `contratistas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `entregas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entregas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cita_id` bigint(20) unsigned DEFAULT NULL,
  `casa_id` bigint(20) unsigned NOT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `fecha_hora_entrega` datetime NOT NULL,
  `resultado` enum('entregada','entregada_con_reclamos','no_entregada') DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entregas_cita_id_foreign` (`cita_id`),
  KEY `entregas_casa_id_foreign` (`casa_id`),
  KEY `entregas_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `entregas_casa_id_foreign` FOREIGN KEY (`casa_id`) REFERENCES `casas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `entregas_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `entregas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `garantias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `garantias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `meses_duracion` smallint(5) unsigned NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proyectos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reclamo_garantia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reclamo_garantia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reclamo_id` bigint(20) unsigned NOT NULL,
  `garantia_id` bigint(20) unsigned NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'pendiente',
  `validado_manualmente` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `contratista_id` bigint(20) unsigned DEFAULT NULL,
  `supervisor_id` bigint(20) unsigned DEFAULT NULL,
  `estado_reparacion` varchar(255) NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `reclamo_garantia_reclamo_id_foreign` (`reclamo_id`),
  KEY `reclamo_garantia_garantia_id_foreign` (`garantia_id`),
  KEY `reclamo_garantia_contratista_id_foreign` (`contratista_id`),
  KEY `reclamo_garantia_supervisor_id_foreign` (`supervisor_id`),
  CONSTRAINT `reclamo_garantia_contratista_id_foreign` FOREIGN KEY (`contratista_id`) REFERENCES `contratistas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reclamo_garantia_garantia_id_foreign` FOREIGN KEY (`garantia_id`) REFERENCES `garantias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reclamo_garantia_reclamo_id_foreign` FOREIGN KEY (`reclamo_id`) REFERENCES `reclamos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reclamo_garantia_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reclamo_garantia_fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reclamo_garantia_fotos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reclamo_garantia_id` bigint(20) unsigned NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reclamo_garantia_fotos_reclamo_garantia_id_foreign` (`reclamo_garantia_id`),
  CONSTRAINT `reclamo_garantia_fotos_reclamo_garantia_id_foreign` FOREIGN KEY (`reclamo_garantia_id`) REFERENCES `reclamo_garantia` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reclamos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reclamos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `casa_id` bigint(20) unsigned DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `fecha_reporte` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reclamos_casa_id_foreign` (`casa_id`),
  CONSTRAINT `reclamos_casa_id_foreign` FOREIGN KEY (`casa_id`) REFERENCES `casas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reporte_entregas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporte_entregas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `entrega_id` bigint(20) unsigned NOT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','no_terminado','finalizado') NOT NULL DEFAULT 'pendiente',
  `encargado` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reporte_entregas_entrega_id_foreign` (`entrega_id`),
  CONSTRAINT `reporte_entregas_entrega_id_foreign` FOREIGN KEY (`entrega_id`) REFERENCES `entregas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reporte_reclamo_fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporte_reclamo_fotos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reporte_reclamo_id` bigint(20) unsigned NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reporte_reclamo_fotos_reporte_reclamo_id_foreign` (`reporte_reclamo_id`),
  CONSTRAINT `reporte_reclamo_fotos_reporte_reclamo_id_foreign` FOREIGN KEY (`reporte_reclamo_id`) REFERENCES `reporte_reclamos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reporte_reclamos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporte_reclamos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reclamo_id` bigint(20) unsigned NOT NULL,
  `reclamo_garantia_id` bigint(20) unsigned DEFAULT NULL,
  `creado_por_user_id` bigint(20) unsigned DEFAULT NULL,
  `contratista_id` bigint(20) unsigned DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `revisado` tinyint(1) NOT NULL DEFAULT 0,
  `revisado_por` bigint(20) unsigned DEFAULT NULL,
  `revisado_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reporte_reclamos_reclamo_id_foreign` (`reclamo_id`),
  KEY `reporte_reclamos_creado_por_user_id_foreign` (`creado_por_user_id`),
  KEY `reporte_reclamos_contratista_id_foreign` (`contratista_id`),
  KEY `reporte_reclamos_revisado_por_foreign` (`revisado_por`),
  KEY `reporte_reclamos_reclamo_garantia_id_foreign` (`reclamo_garantia_id`),
  CONSTRAINT `reporte_reclamos_contratista_id_foreign` FOREIGN KEY (`contratista_id`) REFERENCES `contratistas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reporte_reclamos_creado_por_user_id_foreign` FOREIGN KEY (`creado_por_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reporte_reclamos_reclamo_garantia_id_foreign` FOREIGN KEY (`reclamo_garantia_id`) REFERENCES `reclamo_garantia` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reporte_reclamos_reclamo_id_foreign` FOREIGN KEY (`reclamo_id`) REFERENCES `reclamos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reporte_reclamos_revisado_por_foreign` FOREIGN KEY (`revisado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_codigo_unique` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tipo_casas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_casas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proyecto_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `metros` decimal(8,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_casas_proyecto_id_foreign` (`proyecto_id`),
  CONSTRAINT `tipo_casas_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `rol_id` bigint(20) unsigned DEFAULT NULL,
  `proyecto_id` bigint(20) unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_rol_id_foreign` (`rol_id`),
  KEY `users_proyecto_id_foreign` (`proyecto_id`),
  CONSTRAINT `users_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2026_08_19_100504_create_proyectos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2026_08_19_100509_create_tipo_casas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2026_08_19_100514_create_casas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2026_08_19_101818_create_rols_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2026_08_19_102506_create_clientes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2026_08_19_102547_create_citas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2026_08_19_102824_create_entregas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2026_08_19_102923_create_reporte_entregas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_08_19_103440_create_garantias_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_08_19_104347_add_campos_a_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2026_08_19_174029_create_reclamos_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2026_08_19_174625_create_contratistas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_08_21_080831_create_asignacion_proyectos_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2026_08_22_080540_add_casa_id_to_reclamos_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_08_22_082927_add_unique_numero_casa_to_casas_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_08_23_085950_create_reporte_reclamos_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_08_23_093218_add_missing_columns_to_reclamos_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_08_26_100956_add_revisado_to_reporte_reclamos_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_08_26_120000_create_reclamo_garantia_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_09_03_100418_add_asignacion_a_reclamo_garantia_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_09_03_100418_add_reclamo_garantia_id_to_reporte_reclamos_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2026_09_03_100419_create_reporte_reclamo_fotos_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2026_09_03_105339_create_reclamo_garantia_fotos_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2026_09_03_113748_add_user_id_to_contratistas_table',12);

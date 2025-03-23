-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: marmosys
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `approval_logs`
--

DROP TABLE IF EXISTS `approval_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approval_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `budget_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `motivo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approval_logs_budget_id_foreign` (`budget_id`),
  KEY `approval_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `approval_logs_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`),
  CONSTRAINT `approval_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approval_logs`
--

LOCK TABLES `approval_logs` WRITE;
/*!40000 ALTER TABLE `approval_logs` DISABLE KEYS */;
INSERT INTO `approval_logs` VALUES (1,2,1,'approve',NULL,'2025-03-04 15:21:51','2025-03-04 15:21:51'),(2,1,1,'reject','Cliente n??o aceitou o or??amento','2025-03-04 15:53:49','2025-03-04 15:53:49'),(3,3,1,'approve',NULL,'2025-03-07 00:07:54','2025-03-07 00:07:54'),(4,5,1,'approve',NULL,'2025-03-07 18:17:06','2025-03-07 18:17:06'),(5,4,1,'approve',NULL,'2025-03-07 20:52:44','2025-03-07 20:52:44'),(6,6,1,'approve',NULL,'2025-03-08 01:16:18','2025-03-08 01:16:18'),(7,27,1,'approve',NULL,'2025-03-08 14:10:58','2025-03-08 14:10:58'),(8,40,1,'approve',NULL,'2025-03-08 20:56:33','2025-03-08 20:56:33'),(9,51,1,'approve',NULL,'2025-03-09 00:44:21','2025-03-09 00:44:21'),(10,57,1,'approve',NULL,'2025-03-10 23:53:02','2025-03-10 23:53:02');
/*!40000 ALTER TABLE `approval_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_schedules`
--

DROP TABLE IF EXISTS `backup_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backup_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` time NOT NULL,
  `day` int DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `last_backup` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_backup` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_schedules`
--

LOCK TABLES `backup_schedules` WRITE;
/*!40000 ALTER TABLE `backup_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budget_items`
--

DROP TABLE IF EXISTS `budget_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budget_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `budget_room_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantidade` decimal(10,3) NOT NULL,
  `unidade` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `largura` decimal(10,3) NOT NULL,
  `altura` decimal(10,3) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_items_budget_room_id_foreign` (`budget_room_id`),
  KEY `budget_items_material_id_foreign` (`material_id`),
  CONSTRAINT `budget_items_budget_room_id_foreign` FOREIGN KEY (`budget_room_id`) REFERENCES `budget_rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget_items`
--

LOCK TABLES `budget_items` WRITE;
/*!40000 ALTER TABLE `budget_items` DISABLE KEYS */;
INSERT INTO `budget_items` VALUES (1,1,3,1.000,'m??','Granito Verde Ubatuba',1.000,1.000,320.00,320.00,'2025-03-04 15:19:12','2025-03-04 15:19:12'),(2,2,1,1.000,'m??','Granito Preto S??o Gabriel',1.000,1.000,350.00,350.00,'2025-03-04 15:19:12','2025-03-04 15:19:12'),(3,3,3,1.000,'m??','Granito Verde Ubatuba',1.000,1.000,320.00,320.00,'2025-03-04 15:21:16','2025-03-04 15:21:16'),(4,4,1,1.000,'m??','Granito Preto S??o Gabriel',1.000,1.000,350.00,350.00,'2025-03-04 15:21:16','2025-03-04 15:21:16'),(5,5,3,1.000,'m²','Granito Verde Ubatuba',1.000,1.000,320.00,320.00,'2025-03-07 00:07:44','2025-03-07 00:07:44'),(6,6,3,1.000,'m²','Granito Verde Ubatuba',1.000,1.000,320.00,320.00,'2025-03-07 00:13:14','2025-03-07 00:13:14'),(7,7,3,1.000,'m²','Granito Verde Ubatuba',1.000,1.000,320.00,320.00,'2025-03-07 00:34:19','2025-03-07 00:34:19'),(8,8,3,1.000,'m²','Granito Verde Ubatuba',1.000,1.000,320.00,320.00,'2025-03-07 20:53:26','2025-03-07 20:53:26'),(9,9,3,1.000,'m²','Granito Verde Ubatuba',1.000,1.000,320.00,320.00,'2025-03-08 00:53:20','2025-03-08 00:53:20'),(10,10,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 01:15:25','2025-03-08 01:15:25'),(11,11,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 01:23:18','2025-03-08 01:23:18'),(12,12,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 01:23:18','2025-03-08 01:23:18'),(13,12,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 01:23:18','2025-03-08 01:23:18'),(15,14,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 02:03:43','2025-03-08 02:03:43'),(19,17,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 02:31:28','2025-03-08 02:31:28'),(20,18,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 02:35:34','2025-03-08 02:35:34'),(21,19,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 02:56:13','2025-03-08 02:56:13'),(22,21,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 03:42:45','2025-03-08 03:42:45'),(24,23,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 03:44:46','2025-03-08 03:44:46'),(26,25,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 03:49:54','2025-03-08 03:49:54'),(27,26,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 03:50:34','2025-03-08 03:50:34'),(32,29,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 03:58:33','2025-03-08 03:58:33'),(33,29,4,1.000,'m²','Lixa 280',1.000,1.000,15.00,15.00,'2025-03-08 03:58:33','2025-03-08 03:58:33'),(34,30,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 04:00:02','2025-03-08 04:00:02'),(35,30,4,1.000,'m²','Lixa 280',1.000,1.000,15.00,15.00,'2025-03-08 04:00:02','2025-03-08 04:00:02'),(36,31,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 04:00:02','2025-03-08 04:00:02'),(37,31,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 04:00:02','2025-03-08 04:00:02'),(38,32,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 04:00:02','2025-03-08 04:00:02'),(39,33,4,1.000,'m²','Lixa 280',1.000,1.000,15.00,15.00,'2025-03-08 15:44:24','2025-03-08 15:44:24'),(40,34,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 15:44:24','2025-03-08 15:44:24'),(41,35,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 15:44:24','2025-03-08 15:44:24'),(42,36,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 18:54:44','2025-03-08 18:54:44'),(43,36,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 18:54:44','2025-03-08 18:54:44'),(44,37,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 19:24:38','2025-03-08 19:24:38'),(45,38,6,1.000,'m²','Bancada Supernano 2cm',2.730,0.040,2525.20,275.75,'2025-03-08 20:05:40','2025-03-08 20:05:40'),(46,39,6,1.000,'m²','Bancada Supernano 2cm',1.000,1.000,2525.20,2525.20,'2025-03-08 20:18:33','2025-03-08 20:18:33'),(47,40,7,1.000,'m²','Espelho Solto Supernano 2cm',1.000,1.000,546.00,546.00,'2025-03-08 20:18:33','2025-03-08 20:18:33'),(48,42,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 20:23:43','2025-03-08 20:23:43'),(49,43,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 20:26:31','2025-03-08 20:26:31'),(50,44,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 20:29:45','2025-03-08 20:29:45'),(51,45,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 20:33:03','2025-03-08 20:33:03'),(52,46,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 20:38:50','2025-03-08 20:38:50'),(53,47,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 20:45:20','2025-03-08 20:45:20'),(54,48,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 20:48:51','2025-03-08 20:48:51'),(55,49,8,1.000,'m²','Espelho Colado Supernano 2cm',1.000,1.000,218.40,218.40,'2025-03-08 20:55:59','2025-03-08 20:55:59'),(56,50,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 21:01:02','2025-03-08 21:01:02'),(57,51,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 21:09:53','2025-03-08 21:09:53'),(58,52,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 21:27:28','2025-03-08 21:27:28'),(59,53,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 21:28:21','2025-03-08 21:28:21'),(60,54,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 21:40:57','2025-03-08 21:40:57'),(61,55,1,1.000,'m²','Descrição do produto teste',1.000,1.000,99.90,99.90,'2025-03-08 21:45:44','2025-03-08 21:45:44'),(62,56,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 22:33:13','2025-03-08 22:33:13'),(63,57,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 23:11:32','2025-03-08 23:11:32'),(64,57,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 23:11:32','2025-03-08 23:11:32'),(65,58,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-08 23:27:36','2025-03-08 23:27:36'),(66,59,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-08 23:34:40','2025-03-08 23:34:40'),(67,60,3,1.000,'m²','Disco para lixa 200m',1.000,1.000,50.00,50.00,'2025-03-09 00:42:46','2025-03-09 00:42:46'),(68,61,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-09 16:46:39','2025-03-09 16:46:39'),(69,62,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-09 17:16:59','2025-03-09 17:16:59'),(70,63,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-09 17:45:51','2025-03-09 17:45:51'),(71,64,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-09 18:11:26','2025-03-09 18:11:26'),(72,65,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-09 18:16:17','2025-03-09 18:16:17'),(73,66,2,1.000,'m²','Disco para lixa 200',1.000,1.000,30.00,30.00,'2025-03-09 18:17:21','2025-03-09 18:17:21'),(74,67,8,1.000,'m²','Espelho Colado Supernano 2cm',1.000,1.000,218.40,218.40,'2025-03-09 18:17:21','2025-03-09 18:17:21');
/*!40000 ALTER TABLE `budget_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budget_materials`
--

DROP TABLE IF EXISTS `budget_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budget_materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `preco_venda` decimal(10,2) NOT NULL,
  `preco_custo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estoque_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estoque_atual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unidade_medida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'm??',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `budget_materials_codigo_unique` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget_materials`
--

LOCK TABLES `budget_materials` WRITE;
/*!40000 ALTER TABLE `budget_materials` DISABLE KEYS */;
INSERT INTO `budget_materials` VALUES (1,'GRAN-001','Granito Preto S??o Gabriel','Granito preto de alta qualidade',350.00,0.00,0.00,0.00,'m??',1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL),(2,'MARB-001','M??rmore Branco Carrara','M??rmore italiano de primeira linha',450.00,0.00,0.00,0.00,'m??',1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL),(3,'GRAN-002','Granito Verde Ubatuba','Granito verde brasileiro',320.00,0.00,0.00,0.00,'m??',1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL);
/*!40000 ALTER TABLE `budget_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budget_rooms`
--

DROP TABLE IF EXISTS `budget_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budget_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `budget_id` bigint unsigned NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_rooms_budget_id_foreign` (`budget_id`),
  CONSTRAINT `budget_rooms_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget_rooms`
--

LOCK TABLES `budget_rooms` WRITE;
/*!40000 ALTER TABLE `budget_rooms` DISABLE KEYS */;
INSERT INTO `budget_rooms` VALUES (1,1,'Sala',320.00,'2025-03-04 15:19:12','2025-03-04 15:19:12',NULL),(2,1,'Cozinha',350.00,'2025-03-04 15:19:12','2025-03-04 15:19:12',NULL),(3,2,'Sala',320.00,'2025-03-04 15:21:16','2025-03-04 15:21:16',NULL),(4,2,'Cozinha',350.00,'2025-03-04 15:21:16','2025-03-04 15:21:16',NULL),(5,3,'Sala',320.00,'2025-03-07 00:07:44','2025-03-07 00:07:44',NULL),(6,4,'cozinha',320.00,'2025-03-07 00:13:14','2025-03-07 00:13:14',NULL),(7,5,'Sala',320.00,'2025-03-07 00:34:19','2025-03-07 00:34:19',NULL),(8,6,'Sala',320.00,'2025-03-07 20:53:26','2025-03-07 20:53:26',NULL),(9,7,'Sala',320.00,'2025-03-08 00:53:20','2025-03-08 00:53:20',NULL),(10,8,'Sala',99.90,'2025-03-08 01:15:25','2025-03-08 01:15:25',NULL),(11,9,'Sala',99.90,'2025-03-08 01:23:18','2025-03-08 01:23:18',NULL),(12,9,'Cozinha',80.00,'2025-03-08 01:23:18','2025-03-08 01:23:18',NULL),(14,11,'Sala',50.00,'2025-03-08 02:03:43','2025-03-08 02:03:43',NULL),(17,14,'Sala',30.00,'2025-03-08 02:31:28','2025-03-08 02:31:28',NULL),(18,15,'Sala',99.90,'2025-03-08 02:35:34','2025-03-08 02:35:34',NULL),(19,16,'Sala',99.90,'2025-03-08 02:56:13','2025-03-08 02:56:13',NULL),(21,18,'Sala',0.00,'2025-03-08 03:42:45','2025-03-08 03:42:45',NULL),(23,20,'Sala',0.00,'2025-03-08 03:44:46','2025-03-08 03:44:46',NULL),(25,22,'Sala',0.00,'2025-03-08 03:49:54','2025-03-08 03:49:54',NULL),(26,23,'Sala',0.00,'2025-03-08 03:50:34','2025-03-08 03:50:34',NULL),(29,26,'Sala',0.00,'2025-03-08 03:58:33','2025-03-08 03:58:33',NULL),(30,27,'Sala',0.00,'2025-03-08 04:00:02','2025-03-08 04:00:02',NULL),(31,27,'Cozinha',0.00,'2025-03-08 04:00:02','2025-03-08 04:00:02',NULL),(32,27,'Quarto 1',0.00,'2025-03-08 04:00:02','2025-03-08 04:00:02',NULL),(33,28,'Sala',0.00,'2025-03-08 15:44:24','2025-03-08 15:44:24',NULL),(34,28,'Cozinha',0.00,'2025-03-08 15:44:24','2025-03-08 15:44:24',NULL),(35,28,'Sala de estar',0.00,'2025-03-08 15:44:24','2025-03-08 15:44:24',NULL),(36,29,'Sala',0.00,'2025-03-08 18:54:44','2025-03-08 18:54:44',NULL),(37,30,'Área de Serviço',0.00,'2025-03-08 19:24:38','2025-03-08 19:24:38',NULL),(38,31,'Cozinha',0.00,'2025-03-08 20:05:40','2025-03-08 20:05:40',NULL),(39,32,'Sala',0.00,'2025-03-08 20:18:33','2025-03-08 20:18:33',NULL),(40,32,'Cozinha',0.00,'2025-03-08 20:18:33','2025-03-08 20:18:33',NULL),(42,33,'Sala',0.00,'2025-03-08 20:23:43','2025-03-08 20:23:43',NULL),(43,34,'Sala',0.00,'2025-03-08 20:26:31','2025-03-08 20:26:31',NULL),(44,35,'Sala',0.00,'2025-03-08 20:29:45','2025-03-08 20:29:45',NULL),(45,36,'Sala',0.00,'2025-03-08 20:33:03','2025-03-08 20:33:03',NULL),(46,37,'Sala',0.00,'2025-03-08 20:38:50','2025-03-08 20:38:50',NULL),(47,38,'Sala',0.00,'2025-03-08 20:45:20','2025-03-08 20:45:20',NULL),(48,39,'Sala',0.00,'2025-03-08 20:48:51','2025-03-08 20:48:51',NULL),(49,40,'Sala',0.00,'2025-03-08 20:55:59','2025-03-08 20:55:59',NULL),(50,41,'Sala',0.00,'2025-03-08 21:01:02','2025-03-08 21:01:02',NULL),(51,42,'Sala',0.00,'2025-03-08 21:09:53','2025-03-08 21:09:53',NULL),(52,43,'Sala',0.00,'2025-03-08 21:27:28','2025-03-08 21:27:28',NULL),(53,44,'Sala',0.00,'2025-03-08 21:28:21','2025-03-08 21:28:21',NULL),(54,45,'Sala',0.00,'2025-03-08 21:40:57','2025-03-08 21:40:57',NULL),(55,46,'Sala',0.00,'2025-03-08 21:45:44','2025-03-08 21:45:44',NULL),(56,47,'Sala',0.00,'2025-03-08 22:33:13','2025-03-08 22:33:13',NULL),(57,48,'Sala',0.00,'2025-03-08 23:11:32','2025-03-08 23:11:32',NULL),(58,49,'Sala',0.00,'2025-03-08 23:27:36','2025-03-08 23:27:36',NULL),(59,50,'Sala',0.00,'2025-03-08 23:34:40','2025-03-08 23:34:40',NULL),(60,51,'Sala',0.00,'2025-03-09 00:42:46','2025-03-09 00:42:46',NULL),(61,52,'Sala',0.00,'2025-03-09 16:46:39','2025-03-09 16:46:39',NULL),(62,53,'Sala',0.00,'2025-03-09 17:16:59','2025-03-09 17:16:59',NULL),(63,54,'Sala',0.00,'2025-03-09 17:45:51','2025-03-09 17:45:51',NULL),(64,55,'Sala',0.00,'2025-03-09 18:11:26','2025-03-09 18:11:26',NULL),(65,56,'Sala',0.00,'2025-03-09 18:16:17','2025-03-09 18:16:17',NULL),(66,57,'Sala',0.00,'2025-03-09 18:17:21','2025-03-09 18:17:21',NULL),(67,57,'Cozinha',0.00,'2025-03-09 18:17:21','2025-03-09 18:17:21',NULL);
/*!40000 ALTER TABLE `budget_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budgets`
--

DROP TABLE IF EXISTS `budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` date NOT NULL,
  `previsao_entrega` date NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aguardando_aprovacao',
  `valor_total` decimal(10,2) NOT NULL,
  `desconto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `valor_final` decimal(10,2) NOT NULL,
  `data_validade` date NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `motivo_reprovacao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `budgets_numero_unique` (`numero`),
  KEY `budgets_client_id_foreign` (`client_id`),
  KEY `budgets_user_id_foreign` (`user_id`),
  KEY `budgets_approved_by_foreign` (`approved_by`),
  CONSTRAINT `budgets_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `budgets_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `budgets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budgets`
--

LOCK TABLES `budgets` WRITE;
/*!40000 ALTER TABLE `budgets` DISABLE KEYS */;
INSERT INTO `budgets` VALUES (1,'ORC-202500001','2025-03-04','2025-03-19',1,'reprovado',670.00,0.00,670.00,'2025-04-03',1,'2025-03-04 15:19:12','2025-03-04 15:53:49',NULL,NULL,NULL,NULL,NULL),(2,'ORC-202500002','2025-03-04','2025-03-19',1,'aprovado',670.00,0.00,670.00,'2025-04-03',1,'2025-03-04 15:21:16','2025-03-04 15:21:51',NULL,NULL,NULL,NULL,NULL),(3,'ORC-202500003','2025-03-06','2025-03-20',1,'aprovado',320.00,0.00,320.00,'2025-04-05',1,'2025-03-07 00:07:44','2025-03-07 00:07:52',NULL,NULL,NULL,NULL,NULL),(4,'ORC-202500004','2025-03-06','2025-03-20',1,'aprovado',320.00,0.00,320.00,'2025-04-05',1,'2025-03-07 00:13:14','2025-03-07 20:52:44',NULL,NULL,NULL,NULL,NULL),(5,'ORC-202500005','2025-03-06','2025-03-17',1,'aprovado',320.00,0.00,320.00,'2025-04-05',1,'2025-03-07 00:34:19','2025-03-07 18:17:06',NULL,NULL,NULL,NULL,NULL),(6,'ORC-202500006','2025-03-07','2025-03-27',8,'aprovado',320.00,0.00,320.00,'2025-04-06',1,'2025-03-07 20:53:26','2025-03-08 01:16:18',NULL,NULL,NULL,NULL,NULL),(7,'ORC-202500007','2025-03-07','2025-03-20',2,'aguardando_aprovacao',320.00,0.00,320.00,'2025-04-06',1,'2025-03-08 00:53:20','2025-03-08 00:53:20',NULL,NULL,NULL,NULL,NULL),(8,'ORC-202500008','2025-03-07','2025-03-19',8,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-06',1,'2025-03-08 01:15:25','2025-03-08 01:15:25',NULL,NULL,NULL,NULL,NULL),(9,'ORC-202500009','2025-03-07','2025-03-26',7,'aguardando_aprovacao',179.90,0.00,179.90,'2025-04-06',1,'2025-03-08 01:23:18','2025-03-08 01:23:18',NULL,NULL,NULL,NULL,NULL),(11,'ORC-202500010','2025-03-07','2025-03-19',8,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-06',1,'2025-03-08 02:03:43','2025-03-08 02:03:43',NULL,NULL,NULL,NULL,NULL),(14,'ORC-202500011','2025-03-07','2025-03-19',7,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-06',1,'2025-03-08 02:31:28','2025-03-08 02:31:28',NULL,NULL,NULL,NULL,NULL),(15,'ORC-202500012','2025-03-07','2025-03-25',6,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-06',1,'2025-03-08 02:35:34','2025-03-08 02:35:34',NULL,NULL,NULL,NULL,NULL),(16,'ORC-202500013','2025-03-07','2025-03-19',5,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-06',1,'2025-03-08 02:56:13','2025-03-08 02:56:13',NULL,NULL,NULL,NULL,NULL),(18,'ORC-202500014','2025-03-08','2025-03-20',4,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-07',1,'2025-03-08 03:42:45','2025-03-08 03:42:45',NULL,NULL,NULL,NULL,NULL),(20,'ORC-202500015','2025-03-08','2025-03-13',8,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-07',1,'2025-03-08 03:44:46','2025-03-08 03:44:46',NULL,NULL,NULL,NULL,NULL),(22,'ORC-202500016','2025-03-08','2025-03-19',4,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 03:49:54','2025-03-08 03:49:54',NULL,NULL,NULL,NULL,NULL),(23,'ORC-202500017','2025-03-08','2025-03-12',4,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-07',1,'2025-03-08 03:50:34','2025-03-08 03:50:34',NULL,NULL,NULL,NULL,NULL),(26,'ORC-202500018','2025-03-08','2025-03-20',9,'aguardando_aprovacao',45.00,0.00,45.00,'2025-04-07',1,'2025-03-08 03:58:33','2025-03-08 03:58:33',NULL,NULL,NULL,NULL,NULL),(27,'ORC-202500019','2025-03-08','2025-03-19',9,'aprovado',274.80,0.00,274.80,'2025-04-07',1,'2025-03-08 04:00:02','2025-03-08 14:10:58',NULL,NULL,NULL,NULL,NULL),(28,'ORC-202500020','2025-03-08','2025-03-27',2,'aguardando_aprovacao',144.90,0.00,144.90,'2025-04-07',1,'2025-03-08 15:44:24','2025-03-08 15:44:24',NULL,NULL,NULL,NULL,NULL),(29,'ORC-202500021','2025-03-08','2025-03-20',8,'aguardando_aprovacao',80.00,0.00,80.00,'2025-04-07',1,'2025-03-08 18:54:44','2025-03-08 18:54:44',NULL,NULL,NULL,NULL,NULL),(30,'ORC-202500022','2025-03-08','2025-03-26',9,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 19:24:38','2025-03-08 19:24:38',NULL,NULL,NULL,NULL,NULL),(31,'ORC-202500023','2025-03-08','2025-03-26',5,'aguardando_aprovacao',275.75,0.00,275.75,'2025-04-07',1,'2025-03-08 20:05:40','2025-03-08 20:05:40',NULL,NULL,NULL,NULL,NULL),(32,'ORC-202500024','2025-03-08','2025-03-20',8,'aguardando_aprovacao',3071.20,0.00,3071.20,'2025-04-07',1,'2025-03-08 20:18:33','2025-03-08 20:18:33',NULL,NULL,NULL,NULL,NULL),(33,'ORC-202500025','2025-03-08','2025-03-19',4,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 20:23:43','2025-03-08 20:23:43',NULL,NULL,NULL,NULL,NULL),(34,'ORC-202500026','2025-03-08','2025-03-21',8,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-07',1,'2025-03-08 20:26:31','2025-03-08 20:26:31',NULL,NULL,NULL,NULL,NULL),(35,'ORC-202500027','2025-03-08','2025-03-27',4,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-07',1,'2025-03-08 20:29:45','2025-03-08 20:29:45',NULL,NULL,NULL,NULL,NULL),(36,'ORC-202500028','2025-03-08','2025-04-24',7,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 20:33:03','2025-03-08 20:33:03',NULL,NULL,NULL,NULL,NULL),(37,'ORC-202500029','2025-03-08','2025-03-20',2,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-07',1,'2025-03-08 20:38:50','2025-03-08 20:38:50',NULL,NULL,NULL,NULL,NULL),(38,'ORC-202500030','2025-03-08','2025-03-26',7,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-07',1,'2025-03-08 20:45:20','2025-03-08 20:45:20',NULL,NULL,NULL,NULL,NULL),(39,'ORC-202500031','2025-03-08','2025-03-18',5,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-07',1,'2025-03-08 20:48:51','2025-03-08 20:48:51',NULL,NULL,NULL,NULL,NULL),(40,'ORC-202500032','2025-03-08','2025-03-26',7,'aprovado',218.40,0.00,218.40,'2025-04-07',1,'2025-03-08 20:55:59','2025-03-08 20:56:33',NULL,NULL,1,'2025-03-08 20:56:33',NULL),(41,'ORC-202500033','2025-03-08','2025-03-20',3,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-07',1,'2025-03-08 21:01:02','2025-03-08 21:01:03',NULL,NULL,NULL,NULL,NULL),(42,'ORC-202500034','2025-03-08','2025-03-19',5,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-07',1,'2025-03-08 21:09:53','2025-03-08 21:09:53',NULL,NULL,NULL,NULL,NULL),(43,'ORC-202500035','2025-03-08','2025-03-20',2,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 21:27:28','2025-03-08 21:27:28',NULL,NULL,NULL,NULL,NULL),(44,'ORC-202500036','2025-03-08','2025-03-25',5,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 21:28:21','2025-03-08 21:28:21',NULL,NULL,NULL,NULL,NULL),(45,'ORC-202500037','2025-03-08','2025-03-27',8,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-07',1,'2025-03-08 21:40:57','2025-03-08 21:40:57',NULL,NULL,NULL,NULL,NULL),(46,'ORC-202500038','2025-03-08','2025-03-20',8,'aguardando_aprovacao',99.90,0.00,99.90,'2025-04-07',1,'2025-03-08 21:45:44','2025-03-08 21:45:44',NULL,NULL,NULL,NULL,NULL),(47,'ORC-202500039','2025-03-08','2025-03-20',7,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 22:33:13','2025-03-08 22:33:13',NULL,NULL,NULL,NULL,'Teste de observação'),(48,'ORC-202500040','2025-03-08','2025-03-27',9,'aguardando_aprovacao',60.00,0.00,60.00,'2025-04-07',1,'2025-03-08 23:11:32','2025-03-08 23:11:32',NULL,NULL,NULL,NULL,'Teste de observação'),(49,'ORC-202500041','2025-03-08','2025-03-20',2,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-07',1,'2025-03-08 23:27:36','2025-03-08 23:27:36',NULL,NULL,NULL,NULL,NULL),(50,'ORC-202500042','2025-03-08','2025-03-27',7,'aguardando_aprovacao',50.00,0.00,50.00,'2025-04-07',1,'2025-03-08 23:34:40','2025-03-08 23:34:40',NULL,NULL,NULL,NULL,'Teste no campo de observação !'),(51,'ORC-202500043','2025-03-08','2025-03-20',2,'aprovado',50.00,0.00,50.00,'2025-04-07',1,'2025-03-09 00:42:46','2025-03-09 00:44:21',NULL,NULL,1,'2025-03-09 00:44:21','Teste tet tetstetes'),(52,'ORC-202500044','2025-03-09','2025-03-26',7,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-08',1,'2025-03-09 16:46:39','2025-03-09 16:46:39',NULL,NULL,NULL,NULL,'teste teste teste'),(53,'ORC-202500045','2025-03-09','2025-03-11',2,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-08',1,'2025-03-09 17:16:59','2025-03-09 17:16:59',NULL,NULL,NULL,NULL,'teste teste teste teste teste teste'),(54,'ORC-202500046','2025-03-09','2025-03-20',2,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-08',1,'2025-03-09 17:45:51','2025-03-09 17:45:51',NULL,NULL,NULL,NULL,'teste teste tetstetttteteeee'),(55,'ORC-202500047','2025-03-09','2025-03-13',5,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-08',1,'2025-03-09 18:11:26','2025-03-09 18:11:26',NULL,NULL,NULL,NULL,'Observação teste teste'),(56,'ORC-202500048','2025-03-09','2025-03-13',2,'aguardando_aprovacao',30.00,0.00,30.00,'2025-04-08',1,'2025-03-09 18:16:17','2025-03-09 18:16:17',NULL,NULL,NULL,NULL,'Teste teste teste'),(57,'ORC-202500049','2025-03-09','2025-03-13',5,'aprovado',248.40,0.00,248.40,'2025-04-08',1,'2025-03-09 18:17:21','2025-03-10 23:53:02',NULL,NULL,1,'2025-03-10 23:53:02','teste teste teste teste');
/*!40000 ALTER TABLE `budgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_cnpj` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg_ie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Cliente Teste','cliente@teste.com','85999999999','41374169315',NULL,'Rua Argentina','151',NULL,'Bela Vista','Fortaleza','CE','60442440',1,NULL,'2025-03-04 15:17:50','2025-03-07 19:13:07',NULL),(2,'Danilo Meneses','cliente@teste.com','11999999999','12345678900',NULL,'Rua Teste, 123',NULL,NULL,NULL,'Sãoo Paulo','SP','01234567',1,NULL,'2025-03-04 15:17:50','2025-03-07 18:06:04',NULL),(3,'Danilo de Meneses Esmeraldo','danilo.dsi@gmail.com','85998111111','41374169315',NULL,'Rua Argentina','131','Casa','Bela Vista','Fortaleza','Ce','60442440',1,NULL,'2025-03-07 18:05:45','2025-03-07 18:05:45',NULL),(4,'João Paulo','joao@gmail.com','85998885454','41374169315',NULL,'Rua Argentina','312',NULL,'Bela Vista','Fortaleza','Ce','60442440',1,NULL,'2025-03-07 18:26:43','2025-03-07 18:26:43',NULL),(5,'Francisco José','chcoze@gmail.com','85998115669','41374169315',NULL,'Rua Argentina','121','Casa','Bela Vista','Fortaleza','Ce','60442440',1,NULL,'2025-03-07 18:33:17','2025-03-07 18:33:17',NULL),(6,'Joel Lima','joel@gmail.com','85981555555','41374169315',NULL,'Rua Argentina','212','casa','Bela Vista','Fortaleza','Ce','60442442',1,NULL,'2025-03-07 18:36:37','2025-03-07 18:36:37',NULL),(7,'Davi Rodrigues','dair@gmail.com','85998115669','41374169315',NULL,'Rua Argentina','121',NULL,'Bela Vista','Fortaleza','CE','60442440',1,NULL,'2025-03-07 18:47:31','2025-03-07 19:12:49',NULL),(8,'Felipe Cesar','felipe@gmail.com','85998115669','41374169315',NULL,'Rua Argentina','151',NULL,'Bela Vista','Fortaleza','CE','60442440',1,NULL,'2025-03-07 18:54:38','2025-03-07 19:13:21',NULL),(9,'José Carlos','carlos@gmail.com','85998428838','41374169315',NULL,'Rua Argentina','313','Casa','Bela Vista','Fortaleza','CE','60442440',1,NULL,'2025-03-07 19:11:03','2025-03-07 19:11:03',NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razao_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome_fantasia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_estadual` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_municipal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logradouro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'companies/4qxKt3qtc4OE4N9TqKqNjryXFIG4pQAWwxR1IQMv.png','ANGULAR GRANITOS LTDA','ANGULAR GRANITOS FÁBRICA','29123952000184','067408800',NULL,'8599915207','85999152076','angulargranito@outlook.com',NULL,'61648-290','Rua Quintino Cunha','2950','QUADRA0008 LOTE 022','Tabapuá Brasília II (Jurema)','Caucaia','CE',NULL,NULL,'2025-03-06 00:22:35','2025-03-07 01:08:33');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_settings`
--

DROP TABLE IF EXISTS `company_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome_empresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacoes_orcamento` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_settings`
--

LOCK TABLES `company_settings` WRITE;
/*!40000 ALTER TABLE `company_settings` DISABLE KEYS */;
INSERT INTO `company_settings` VALUES (1,'MarmoSys','00.000.000/0001-00','Endere??o da Empresa','(00) 0000-0000','contato@empresa.com',NULL,NULL,'Or??amento v??lido por 15 dias.','2025-03-04 15:17:50','2025-03-04 15:17:50');
/*!40000 ALTER TABLE `company_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cost_centers`
--

DROP TABLE IF EXISTS `cost_centers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cost_centers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cost_centers`
--

LOCK TABLES `cost_centers` WRITE;
/*!40000 ALTER TABLE `cost_centers` DISABLE KEYS */;
INSERT INTO `cost_centers` VALUES (1,'Administrativo','Despesas administrativas',1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL),(2,'Comercial','Despesas comerciais',1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL),(3,'Operacional','Despesas operacionais',1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL);
/*!40000 ALTER TABLE `cost_centers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_accounts`
--

DROP TABLE IF EXISTS `financial_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `cost_center_id` bigint unsigned DEFAULT NULL,
  `descricao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `tipo` enum('receita','despesa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pendente','pago','cancelado') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_vencimento` date NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `financial_accounts_category_id_foreign` (`category_id`),
  KEY `financial_accounts_cost_center_id_foreign` (`cost_center_id`),
  CONSTRAINT `financial_accounts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `financial_categories` (`id`),
  CONSTRAINT `financial_accounts_cost_center_id_foreign` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_accounts`
--

LOCK TABLES `financial_accounts` WRITE;
/*!40000 ALTER TABLE `financial_accounts` DISABLE KEYS */;
INSERT INTO `financial_accounts` VALUES (1,1,NULL,'Venda #001',1500.00,'receita','pago','2025-04-03','2025-03-10',NULL,'2025-03-04 15:17:50','2025-03-10 23:51:45',NULL),(2,2,NULL,'Aluguel',2000.00,'despesa','pago','2025-03-19','2025-03-10',NULL,'2025-03-04 15:17:50','2025-03-10 23:52:07',NULL);
/*!40000 ALTER TABLE `financial_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_categories`
--

DROP TABLE IF EXISTS `financial_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_categories`
--

LOCK TABLES `financial_categories` WRITE;
/*!40000 ALTER TABLE `financial_categories` DISABLE KEYS */;
INSERT INTO `financial_categories` VALUES (1,'Vendas','receita',NULL,NULL,'Receitas provenientes de vendas',1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL),(2,'Despesas Operacionais','despesa',NULL,NULL,'Despesas relacionadas à operação',1,'2025-03-04 15:17:50','2025-03-07 18:18:08',NULL);
/*!40000 ALTER TABLE `financial_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_goals`
--

DROP TABLE IF EXISTS `financial_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_goals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_meta` decimal(10,2) NOT NULL,
  `valor_atual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `data_inicial` date NOT NULL,
  `data_final` date NOT NULL,
  `status` enum('em_andamento','concluida','cancelada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'em_andamento',
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `percentual` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_goals`
--

LOCK TABLES `financial_goals` WRITE;
/*!40000 ALTER TABLE `financial_goals` DISABLE KEYS */;
INSERT INTO `financial_goals` VALUES (1,'Fundo de Reserva',50000.00,15000.00,'2025-03-04','2026-03-04','em_andamento','Meta para criar fundo de reserva da empresa',30.00,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL),(2,'Redu????o de Custos',10000.00,3000.00,'2025-03-04','2025-09-04','em_andamento','Reduzir custos operacionais',0.00,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL);
/*!40000 ALTER TABLE `financial_goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material_categories`
--

DROP TABLE IF EXISTS `material_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `material_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material_categories`
--

LOCK TABLES `material_categories` WRITE;
/*!40000 ALTER TABLE `material_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `material_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `preco_padrao` decimal(10,2) NOT NULL,
  `unidade_medida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estoque_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estoque_atual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `preco_custo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `preco_venda` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `category_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materials_codigo_unique` (`codigo`),
  KEY `materials_category_id_foreign` (`category_id`),
  CONSTRAINT `materials_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `material_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (1,'Granito Preto S??o Gabriel','PSG001','Granito preto com cristais m??dios',350.00,'m??',10.00,50.00,250.00,350.00,1,NULL,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL),(2,'M??rmore Branco Carrara','MBC001','M??rmore branco italiano',450.00,'m??',10.00,30.00,350.00,450.00,1,NULL,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL);
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2024_02_16_000000_create_users_table',1),(3,'2024_02_17_000000_create_clients_table',1),(4,'2024_02_17_000000_create_products_table',1),(5,'2024_02_17_000001_create_budgets_table',1),(6,'2024_02_17_000002_create_budget_items_table',1),(7,'2024_02_17_000003_add_mes_ano_to_budgets_table',1),(8,'2024_02_17_000003_create_cost_centers_table',1),(9,'2024_02_17_000003_create_financial_categories_table',1),(10,'2024_02_17_000004_create_financial_accounts_table',1),(11,'2024_02_17_000004_create_financial_goals_table',1),(12,'2024_03_04_000000_add_data_pagamento_to_financial_accounts',1),(13,'2024_03_04_000001_add_cost_center_id_to_financial_accounts',1),(14,'2024_03_04_000002_create_budget_materials_table',1),(15,'2024_03_04_000003_create_company_settings_table',1),(16,'2024_03_21_000000_cleanup_budget_tables',1),(17,'2024_03_21_000000_create_budget_structure',1),(18,'2024_03_21_000001_create_material_categories_table',1),(19,'2024_03_21_000002_create_materials_table',1),(20,'2024_03_22_000001_add_approval_fields_to_budgets',1),(21,'2024_03_22_000002_create_approval_logs_table',1),(22,'2025_03_03_164349_add_cor_icone_to_financial_categories_table',1),(24,'2025_03_07_171301_set_default_type_for_products',2),(25,'2025_03_07_add_tipo_to_products',2),(26,'2025_03_07_175114_add_missing_columns_to_products',3),(27,'2025_03_07_add_missing_columns_to_products',3),(28,'2025_03_07_create_stock_movements_table',4),(29,'2025_03_08_003749_alter_budget_items_descricao_column',5),(30,'[timestamp]_update_budget_items_products_foreign_key',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preco_venda` decimal(10,2) NOT NULL,
  `preco_custo` decimal(10,2) NOT NULL,
  `estoque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estoque_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unidade_medida` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fornecedor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_codigo_unique` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'MTR0002','Descrição do produto teste',99.90,50.00,10.00,5.00,'UN',NULL,NULL,1,'Descrição do produto teste','material','2025-03-04 15:17:50','2025-03-07 23:37:49',NULL),(2,'0002','Disco para lixa 200',30.00,10.00,10.00,5.00,'UN','disco de lixa','Fornecedor teste',1,'Disco para lixa 200','material','2025-03-07 20:56:30','2025-03-07 20:56:30',NULL),(3,'PRD0001','Disco para lixa 200m',50.00,100.00,10.00,5.00,'UN','Lixa','Fornecedor',1,'Disco para lixa 200m','material','2025-03-07 21:08:58','2025-03-07 21:08:58',NULL),(4,'MTR0001','Lixa 280',15.00,10.00,10.00,5.00,'UN','Lixa','Fornecedor Teste',1,'Lixa 280','material','2025-03-07 22:16:30','2025-03-07 22:16:30',NULL),(5,'SRV0001','Serviço de Mão de Obra',1000.00,2000.00,10.00,5.00,'UN',NULL,NULL,1,'Serviço de Mão de Obra','service','2025-03-08 01:59:55','2025-03-08 01:59:55',NULL),(6,'PRD0002','Bancada Supernano 2cm',2525.20,1525.00,10.00,5.00,'M²','Bancada',NULL,1,'Bancada Supernano 2cm','product','2025-03-08 19:48:12','2025-03-08 19:48:12',NULL),(7,'PRD0003','Espelho Solto Supernano 2cm',546.00,250.00,10.00,5.00,'M²','Espelho Solto',NULL,1,'Espelho Solto Supernano 2cm','product','2025-03-08 20:03:52','2025-03-08 20:03:52',NULL),(8,'PRD0004','Espelho Colado Supernano 2cm',218.40,100.00,10.00,5.00,'M²','Espelho Colado',NULL,1,'Espelho Colado Supernano 2cm','product','2025-03-08 20:04:48','2025-03-08 20:04:48',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `tipo` enum('entrada','saida') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  `saldo_anterior` decimal(10,2) NOT NULL,
  `saldo_atual` decimal(10,2) NOT NULL,
  `observacao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@admin.com',NULL,'$2y$10$jfEi4DFU9zHQ26hpebjyxO5/.MY9weqP9cSWPuns626aDWWNYtsIC',NULL,1,'2025-03-04 15:17:50','2025-03-04 15:17:50',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-12 19:51:42

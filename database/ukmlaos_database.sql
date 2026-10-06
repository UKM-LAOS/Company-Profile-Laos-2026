-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_cp_laos
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
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `divisi_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `is_unggulan` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_judul_unique` (`judul`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_divisi_id_foreign` (`divisi_id`),
  KEY `blogs_author_id_foreign` (`author_id`),
  CONSTRAINT `blogs_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blogs_divisi_id_foreign` FOREIGN KEY (`divisi_id`) REFERENCES `divisis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,2,1,'Mengenal Ekosistem Open Source Modern di Lingkungan Kampus','mengenal-ekosistem-open-source-modern-di-lingkungan-kampus','informasi','<p>Open source bukan sekadar kode gratis, melainkan filosofi kolaborasi tanpa batas. Di UKM LAOS (Linux and Open Source), mahasiswa diajak untuk berkontribusi langsung pada proyek-proyek teknologi nyata.</p><p>Melalui keterbukaan kode, pengembang pemula dapat mempelajari arsitektur aplikasi skala enterprise dan membangun portofolio berstandar industri.</p>','Panduan pengenalan ekosistem open-source modern bagi mahasiswa pengembang teknologi.',1,'published','2026-09-26 01:02:43',142,'2026-10-06 01:02:43','2026-10-06 01:02:43',NULL),(2,2,1,'Panduan Memulai Inertia.js dengan Vue 3 dan Laravel 11','panduan-memulai-inertia-js-dengan-vue-3-dan-laravel-11','tutorial','<p>Inertia.js menjembatani kesenjangan antara Single Page Application (SPA) dan arsitektur server-driven monolith. Dengan Inertia, Anda tidak perlu lagi membangun REST API terpisah hanya untuk dashboard admin interaktif.</p><p>Tutorial ini membedah konfigurasi awal, setup routing Ziggy, hingga integrasi state management reaktif di Vue 3.</p>','Tutorial komprehensif implementasi Inertia.js bersama Vue 3 dan Laravel 11 terkini.',1,'published','2026-10-01 01:02:43',285,'2026-10-06 01:02:43','2026-10-06 01:02:43',NULL),(3,4,1,'Prinsip Desain Antarmuka Glassmorphism & Aksesibilitas Web','prinsip-desain-antarmuka-glassmorphism-aksesibilitas-web','tips-trik','<p>Tren desain modern seperti Glassmorphism memberikan estetika visual yang futuristik dan memikat. Namun, kontras warna dan keterbacaan tipografi tetap harus menjadi prioritas utama demi standar aksesibilitas WCAG.</p><p>Pelajari trik penggunaan backdrop-filter dan layer opacity yang ramah mata.</p>','Tips memadukan estetika glassmorphism modern dengan standar kontras aksesibilitas.',0,'published','2026-10-04 01:02:43',96,'2026-10-06 01:02:43','2026-10-06 01:02:43',NULL),(4,1,1,'Rilis Resmi Kepengurusan UKM LAOS Periode 2025/2026','rilis-resmi-kepengurusan-ukm-laos-periode-2025-2026','press-release','<p>UKM LAOS dengan bangga mengumumkan susunan fungsionaris baru untuk periode 2025/2026 yang mengusung semangat akselerasi riset open source dan kolaborasi lintas komunitas.</p><p>Mari songsong inovasi baru bersama seluruh anggota keluarga besar UKM LAOS!</p>','Siaran pers pengumuman formasi kepengurusan baru UKM LAOS periode 2025/2026.',0,'published','2026-10-05 01:02:43',310,'2026-10-06 01:02:43','2026-10-06 01:02:43',NULL);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisis`
--

DROP TABLE IF EXISTS `divisis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisis_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisis`
--

LOCK TABLES `divisis` WRITE;
/*!40000 ALTER TABLE `divisis` DISABLE KEYS */;
INSERT INTO `divisis` VALUES (1,'Badan Pengurus Harian','badan-pengurus-harian','Pengarah utama arah gerak, koordinasi internal, dan pengambil kebijakan tertinggi organisasi UKM LAOS.',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33'),(2,'Web Development','web-development','Divisi teknis yang berfokus pada riset, perancangan, dan pengembangan sistem aplikasi web modern open-source.',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33'),(3,'Mobile & IoT Development','mobile-iot-development','Divisi yang berfokus pada eksplorasi ekosistem mobile application lintas platform dan integrasi hardware IoT.',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33'),(4,'Multimedia & UI/UX Design','multimedia-ui-ux-design','Divisi kreatif yang berfokus pada user experience, antarmuka visual, branding identitas, dan media visual organisasi.',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33'),(5,'Keorganisasian & Humas','keorganisasian-humas','Divisi penghubung komunikasi eksternal, kemitraan sponsor, dan pengembangan sumber daya kader organisasi.',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33');
/*!40000 ALTER TABLE `divisis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_01_175050_create_permission_tables',1),(5,'2026_10_06_074830_create_divisis_table',1),(6,'2026_10_06_074951_create_programs_table',1),(7,'2026_10_06_075056_create_blogs_table',1),(8,'2026_10_06_075106_create_penguruses_table',1),(9,'2026_10_06_075127_create_shortlinks_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',2),(2,'App\\Models\\User',3),(3,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penguruses`
--

DROP TABLE IF EXISTS `penguruses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penguruses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sosmed` json DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penguruses`
--

LOCK TABLES `penguruses` WRITE;
/*!40000 ALTER TABLE `penguruses` DISABLE KEYS */;
INSERT INTO `penguruses` VALUES (1,'Ahmad Fathoni','Ketua Umum','2025/2026',NULL,'{\"github\": \"https://github.com/ahmadfathoni\", \"linkedin\": \"https://linkedin.com/in/ahmadfathoni\", \"instagram\": \"https://instagram.com/ahmadfathoni\"}',1,1,'2026-10-06 01:03:32','2026-10-06 01:03:32',NULL),(2,'Siti Nurhaliza','Sekretaris Umum','2025/2026',NULL,'{\"linkedin\": \"https://linkedin.com/in/sitinurhaliza\", \"instagram\": \"https://instagram.com/sitinurhaliza\"}',2,1,'2026-10-06 01:03:32','2026-10-06 01:03:32',NULL),(3,'Dewi Anggraini','Bendahara Umum','2025/2026',NULL,'{\"linkedin\": \"https://linkedin.com/in/dewianggraini\", \"instagram\": \"https://instagram.com/dewianggraini\"}',3,1,'2026-10-06 01:03:32','2026-10-06 01:03:32',NULL),(4,'Budi Prasetyo','Koordinator Divisi Web Development','2025/2026',NULL,'{\"github\": \"https://github.com/budipras\", \"instagram\": \"https://instagram.com/budipras\"}',4,1,'2026-10-06 01:03:32','2026-10-06 01:03:32',NULL),(5,'Rian Ardiansyah','Koordinator Divisi Mobile & IoT','2025/2026',NULL,'{\"github\": \"https://github.com/rianard\", \"linkedin\": \"https://linkedin.com/in/rianard\"}',5,1,'2026-10-06 01:03:32','2026-10-06 01:03:32',NULL),(6,'Maya Kartika','Koordinator Divisi Multimedia & UI/UX','2025/2026',NULL,'{\"linkedin\": \"https://linkedin.com/in/mayakartika\", \"instagram\": \"https://instagram.com/mayakartika\"}',6,1,'2026-10-06 01:03:32','2026-10-06 01:03:32',NULL),(7,'Dimas Maulana','Koordinator Keorganisasian & Humas','2025/2026',NULL,'{\"instagram\": \"https://instagram.com/dimasmaul\"}',7,1,'2026-10-06 01:03:32','2026-10-06 01:03:32',NULL);
/*!40000 ALTER TABLE `penguruses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view_dashboard','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(2,'view_divisions','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(3,'create_divisions','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(4,'edit_divisions','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(5,'delete_divisions','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(6,'view_work_programs','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(7,'create_work_programs','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(8,'edit_work_programs','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(9,'delete_work_programs','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(10,'view_news','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(11,'create_news','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(12,'edit_news','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(13,'delete_news','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(14,'view_committee','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(15,'create_committee','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(16,'edit_committee','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(17,'delete_committee','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(18,'view_users','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(19,'create_users','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(20,'edit_users','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(21,'delete_users','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(22,'view_roles','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(23,'create_roles','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(24,'edit_roles','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(25,'delete_roles','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(26,'view_shortlinks','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(27,'create_shortlinks','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(28,'edit_shortlinks','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(29,'delete_shortlinks','web','2026-10-06 01:01:32','2026-10-06 01:01:32');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `divisi_id` bigint unsigned NOT NULL,
  `judul_program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NUL',
  `open_regis_panitia` date DEFAULT NULL,
  `close_regis_panitia` date DEFAULT NULL,
  `gform_panitia` text COLLATE utf8mb4_unicode_ci,
  `open_regis_peserta` date NOT NULL,
  `close_regis_peserta` date NOT NULL,
  `gform_peserta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `programs_judul_program_unique` (`judul_program`),
  UNIQUE KEY `programs_slug_unique` (`slug`),
  KEY `programs_divisi_id_foreign` (`divisi_id`),
  CONSTRAINT `programs_divisi_id_foreign` FOREIGN KEY (`divisi_id`) REFERENCES `divisis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
INSERT INTO `programs` VALUES (1,2,'LAOS Web & API Intensive Bootcamp','laos-web-api-intensive-bootcamp','Lab Komputer Gd. Fasilkom UNEJ','2026-03-01','2026-03-10','https://forms.gle/laos-panitia-bootcamp','2026-03-15','2026-03-30','https://forms.gle/laos-peserta-bootcamp','2026-10-06 01:01:33','2026-10-06 01:01:33',NULL),(2,4,'Design Sprint & Figma Masterclass','design-sprint-figma-masterclass','Auditorium Fasilkom UNEJ / Hybrid Zoom','2026-04-01','2026-04-08','https://forms.gle/laos-panitia-design','2026-04-10','2026-04-25','https://forms.gle/laos-peserta-design','2026-10-06 01:01:33','2026-10-06 01:01:33',NULL),(3,5,'Open Recruitment Pengurus & Anggota LAOS','open-recruitment-pengurus-anggota-laos','Ruang UKM Gedung PKM UNEJ','2026-08-01','2026-08-10','https://forms.gle/laos-panitia-oprec','2026-08-15','2026-08-31','https://forms.gle/laos-peserta-oprec','2026-10-06 01:01:33','2026-10-06 01:01:33',NULL);
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(22,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(29,2),(1,3),(2,3),(6,3),(10,3),(14,3),(26,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super_admin','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(2,'admin','web','2026-10-06 01:01:32','2026-10-06 01:01:32'),(3,'member','web','2026-10-06 01:01:32','2026-10-06 01:01:32');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shortlinks`
--

DROP TABLE IF EXISTS `shortlinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shortlinks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `destination_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `click_count` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortlinks_short_code_unique` (`short_code`),
  KEY `shortlinks_user_id_foreign` (`user_id`),
  CONSTRAINT `shortlinks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shortlinks`
--

LOCK TABLES `shortlinks` WRITE;
/*!40000 ALTER TABLE `shortlinks` DISABLE KEYS */;
INSERT INTO `shortlinks` VALUES (1,1,'https://forms.gle/laos-recruitment-2026','oprec-2026',128,1,'2027-04-06 01:03:32','2026-10-06 01:03:32','2026-10-06 01:03:32'),(2,1,'https://github.com/UKM-LAOS','github',450,1,NULL,'2026-10-06 01:03:32','2026-10-06 01:03:32'),(3,1,'https://instagram.com/ukmlaos','ig',890,1,NULL,'2026-10-06 01:03:32','2026-10-06 01:03:32'),(4,1,'https://discord.gg/ukmlaos-community','komunitas',312,1,NULL,'2026-10-06 01:03:32','2026-10-06 01:03:32');
/*!40000 ALTER TABLE `shortlinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin UKM','admin@laos.unej.ac.id','2026-10-06 01:01:33','$2y$12$p9XrMtgE1mqZdSwEqhql6uTH52uPVWJc9Jiw2Q6Yqodmey2cQZBnC',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33'),(2,'Super Admin LAOS','superadmin@laos.test','2026-10-06 01:01:33','$2y$12$1ytgKlOZNir.RCFY4IFbSeLjXuCsJDtkunAOt6gfl5dWSmrOGEkiO',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33'),(3,'Administrator LAOS','admin@laos.test','2026-10-06 01:01:33','$2y$12$Eie/i1mGfP8y/vkyclvsB.D0wJs/GN5bmVvgPeaQ7hGyDyym5ebAm',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33'),(4,'Member LAOS','member@laos.test','2026-10-06 01:01:33','$2y$12$EWmJho9nG8jNbVqoT9a4ueXLQ/wF51gwq6Z9I7qhevXTk3yS91PlW',NULL,'2026-10-06 01:01:33','2026-10-06 01:01:33');
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

-- Dump completed on 2026-10-06 15:04:15

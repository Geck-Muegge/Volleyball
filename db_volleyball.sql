-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server-Version:               10.11.16-MariaDB - MariaDB Server
-- Server-Betriebssystem:        Win64
-- HeidiSQL Version:             12.13.0.7147
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Exportiere Datenbank-Struktur für volleyball
CREATE DATABASE IF NOT EXISTS `volleyball` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_german2_ci */;
USE `volleyball`;

-- Exportiere Struktur von Tabelle volleyball.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exportiere Daten aus Tabelle volleyball.cache: ~0 rows (ungefähr)

-- Exportiere Struktur von Tabelle volleyball.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exportiere Daten aus Tabelle volleyball.cache_locks: ~0 rows (ungefähr)

-- Exportiere Struktur von Tabelle volleyball.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Exportiere Daten aus Tabelle volleyball.failed_jobs: ~0 rows (ungefähr)

-- Exportiere Struktur von Tabelle volleyball.gruppe
CREATE TABLE IF NOT EXISTS `gruppe` (
  `gruppenBezeichnung` varchar(5) NOT NULL DEFAULT 'A',
  `isParticipating` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`gruppenBezeichnung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportiere Daten aus Tabelle volleyball.gruppe: ~6 rows (ungefähr)
INSERT INTO `gruppe` (`gruppenBezeichnung`, `isParticipating`) VALUES
	('A', 1),
	('B', 1),
	('C', 1),
	('D', 1),
	('E', 0),
	('F', 0);

-- Exportiere Struktur von Tabelle volleyball.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
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

-- Exportiere Daten aus Tabelle volleyball.job_batches: ~0 rows (ungefähr)

-- Exportiere Struktur von Tabelle volleyball.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
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

-- Exportiere Daten aus Tabelle volleyball.jobs: ~0 rows (ungefähr)

-- Exportiere Struktur von Tabelle volleyball.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exportiere Daten aus Tabelle volleyball.migrations: ~4 rows (ungefähr)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_11_20_194906_create_platzierungs_table', 2);

-- Exportiere Struktur von Tabelle volleyball.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exportiere Daten aus Tabelle volleyball.password_reset_tokens: ~0 rows (ungefähr)

-- Exportiere Struktur von Tabelle volleyball.platzierung
CREATE TABLE IF NOT EXISTS `platzierung` (
  `runde` varchar(50) NOT NULL,
  `team` varchar(100) DEFAULT NULL,
  `platzierung` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`runde`,`platzierung`),
  KEY `fk_team` (`team`),
  CONSTRAINT `fk_team` FOREIGN KEY (`team`) REFERENCES `teams` (`teamName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportiere Daten aus Tabelle volleyball.platzierung: ~0 rows (ungefähr)

-- Exportiere Struktur von Tabelle volleyball.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
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

-- Exportiere Daten aus Tabelle volleyball.sessions: ~1 rows (ungefähr)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('4Mx8N8fwAfFbWWTuq7yNn4j9kWFznZgAgKhztlm5', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRWhncmtVZFdZem9EWm9EdllLYVNzU2ZtV0pmNDBpZEFqcXRjem1laiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3NwaWVsZS9pbmRleCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3BlbmRpbmctcmVzdWx0cyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1795039473);

-- Exportiere Struktur von Tabelle volleyball.spiel
CREATE TABLE IF NOT EXISTS `spiel` (
  `spielid` int(5) NOT NULL,
  `teamA` varchar(100) NOT NULL,
  `teamB` varchar(100) NOT NULL,
  `schiedsrichter` varchar(100) NOT NULL,
  `punkteA` int(11) DEFAULT 0,
  `punkteB` int(11) DEFAULT 0,
  `runde` varchar(5) NOT NULL,
  `feld` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'offen',
  PRIMARY KEY (`spielid`) USING BTREE,
  UNIQUE KEY `ind_spielid` (`spielid`),
  KEY `spiel_teamA_FK` (`teamA`),
  KEY `spiel_teamB_FK` (`teamB`),
  KEY `spiel_schiedsrichter_FK` (`schiedsrichter`),
  CONSTRAINT `FK_teamA` FOREIGN KEY (`teamA`) REFERENCES `teams` (`teamName`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_teamB` FOREIGN KEY (`teamB`) REFERENCES `teams` (`teamName`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportiere Daten aus Tabelle volleyball.spiel: ~16 rows (ungefähr)
INSERT INTO `spiel` (`spielid`, `teamA`, `teamB`, `schiedsrichter`, `punkteA`, `punkteB`, `runde`, `feld`, `status`) VALUES
	(1, 'FI252', '0700 (MDE)', 'Vollybären', 25, 2, 'A', 1, 'beendet'),
	(2, 'BF241', 'Bobo', 'Saschä', 25, 5, 'B', 2, 'beendet'),
	(3, 'Roswitha STR', 'Rauschritter', 'SI242', 17, 0, 'C', 3, 'beendet'),
	(4, 'Team Mogelpackung', 'Test-Test-1-2-3', 'Bube Dame König', 12, 0, 'D', 4, 'beendet'),
	(5, 'FI252', 'Vollybären', '0700 (MDE)', 0, 7, 'A', 1, 'beendet'),
	(6, 'BF241', 'Saschä', 'Bobo', 10, 5, 'B', 2, 'beendet'),
	(7, 'Roswitha STR', 'SI242', 'Rauschritter', 6, 0, 'C', 3, 'beendet'),
	(8, 'Team Mogelpackung', 'Bube Dame König', 'Test-Test-1-2-3', 0, 8, 'D', 4, 'beendet'),
	(9, '0700 (MDE)', 'Vollybären', 'FI252', 0, 6, 'A', 1, 'beendet'),
	(10, 'Bobo', 'Saschä', 'BF241', 7, 0, 'B', 2, 'beendet'),
	(11, 'Rauschritter', 'SI242', 'Roswitha STR', 0, 7, 'C', 3, 'beendet'),
	(12, 'Test-Test-1-2-3', 'Bube Dame König', 'Team Mogelpackung', 12, 0, 'D', 4, 'beendet'),
	(1001, 'Vollybären', 'Bobo', '0700 (MDE)', 0, 0, 'VF1', 1, 'offen'),
	(1002, 'Roswitha STR', 'Team Mogelpackung', 'Rauschritter', 0, 0, 'VF2', 2, 'offen'),
	(1003, 'BF241', 'SI242', 'Saschä', 0, 0, 'VF3', 3, 'offen'),
	(1004, 'Bube Dame König', 'FI252', 'Test-Test-1-2-3', 0, 0, 'VF4', 4, 'offen');

-- Exportiere Struktur von Tabelle volleyball.spiel_live_scores
CREATE TABLE IF NOT EXISTS `spiel_live_scores` (
  `spielid` int(5) NOT NULL,
  `punkteA` int(11) DEFAULT 0,
  `punkteB` int(11) DEFAULT 0,
  PRIMARY KEY (`spielid`),
  CONSTRAINT `spiel_live_scores_ibfk_1` FOREIGN KEY (`spielid`) REFERENCES `spiel` (`spielid`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportiere Daten aus Tabelle volleyball.spiel_live_scores: ~16 rows (ungefähr)
INSERT INTO `spiel_live_scores` (`spielid`, `punkteA`, `punkteB`) VALUES
	(1, 25, 2),
	(2, 25, 5),
	(3, 17, 0),
	(4, 12, 0),
	(5, 0, 7),
	(6, 10, 5),
	(7, 6, 0),
	(8, 0, 8),
	(9, 0, 6),
	(10, 7, 0),
	(11, 0, 7),
	(12, 12, 0),
	(1001, 0, 0),
	(1002, 10, 1),
	(1003, 0, 0),
	(1004, 0, 0);

-- Exportiere Struktur von Tabelle volleyball.spielgruppe
CREATE TABLE IF NOT EXISTS `spielgruppe` (
  `gruppenBez` varchar(1) NOT NULL,
  PRIMARY KEY (`gruppenBez`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportiere Daten aus Tabelle volleyball.spielgruppe: ~4 rows (ungefähr)
INSERT INTO `spielgruppe` (`gruppenBez`) VALUES
	('A'),
	('B'),
	('C'),
	('D');

-- Exportiere Struktur von Tabelle volleyball.teams
CREATE TABLE IF NOT EXISTS `teams` (
  `teamName` varchar(100) NOT NULL,
  `klasse` varchar(100) NOT NULL,
  `isParticipating` tinyint(1) DEFAULT 0,
  `gruppenid` varchar(1) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nummer` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`teamName`),
  UNIQUE KEY `uq_teams_id` (`id`),
  KEY `FK_gruppe` (`gruppenid`),
  CONSTRAINT `FK_gruppe` FOREIGN KEY (`gruppenid`) REFERENCES `spielgruppe` (`gruppenBez`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportiere Daten aus Tabelle volleyball.teams: ~12 rows (ungefähr)
INSERT INTO `teams` (`teamName`, `klasse`, `isParticipating`, `gruppenid`, `id`, `nummer`, `created_at`, `updated_at`) VALUES
	('0700 (MDE)', 'IK241', 1, 'A', 4, 2, NULL, NULL),
	('BF241', 'BF241', 1, 'B', 1, 1, NULL, NULL),
	('Bobo', 'GS251', 1, 'B', 3, 2, NULL, NULL),
	('Bube Dame König', 'ATIW', 1, 'D', 19, 3, NULL, NULL),
	('FI252', 'FI252', 1, 'A', 2, 1, NULL, NULL),
	('Rauschritter', 'SI241', 1, 'C', 7, 2, NULL, NULL),
	('Roswitha STR', 'FI241', 1, 'C', 5, 1, NULL, NULL),
	('Saschä', 'FS243', 1, 'B', 14, 3, NULL, NULL),
	('SI242', 'SI242', 1, 'C', 13, 3, NULL, NULL),
	('Team Mogelpackung', 'FI231', 1, 'D', 6, 1, NULL, NULL),
	('Test-Test-1-2-3', 'BF242', 1, 'D', 8, 2, NULL, NULL),
	('Vollybären', 'BF252', 1, 'A', 11, 3, NULL, NULL);

-- Exportiere Struktur von Tabelle volleyball.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exportiere Daten aus Tabelle volleyball.users: ~0 rows (ungefähr)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Lehrkraft', 'lehrkraft@example.com', NULL, '$2y$12$maDkThDKqKeCAvhoVMa80Od.KV0Hy9r92VXawgh8KryzpP5eq/if2', NULL, '2026-11-04 19:30:08', '2026-11-04 19:30:08');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

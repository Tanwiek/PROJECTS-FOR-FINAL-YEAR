-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: booking_system
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administrateurs`
--

DROP TABLE IF EXISTS `administrateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrateurs`
--

LOCK TABLES `administrateurs` WRITE;
/*!40000 ALTER TABLE `administrateurs` DISABLE KEYS */;
INSERT INTO `administrateurs` VALUES (1,'zan zam','zam@gmail.com','$2y$10$0vMG9MQHPcVpgTWIS9q3Iea5w7wN1v7RTOAM2qKwSn3WSz06hqCr.','2026-03-11 11:43:47'),(2,'admin','admin@gmail.com','$2y$10$/GeJLBl7WeWlhHl0uC807.KYyJQp1i2qTFg3xTrW/n1/Wcq9qWXc6','2026-03-25 11:46:33'),(3,'Lenovo Admin','lenovo@gmail.com','$2y$10$1jQmpCZ/WplWy3RHVxFl2uEVyYlBB36QLav0fFhXhkmbHLHFVWo2i','2026-03-27 12:44:58');
/*!40000 ALTER TABLE `administrateurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'pablo','premo','colon1@gmail.com','0123456789','$2y$10$yQ9ctU759CnCcFAKfqprZ.KMyIIpaxduRzHxiSKiDsuSBYum.jbee','2026-03-11 10:58:17'),(2,'pablo','zan','pablo@gmail.com','0123456789','$2y$10$03Ye6hCjdd4NH1gfNX//7eOdeaYSUYzpzmHTbvc3OoWmNe49bl21C','2026-03-25 11:44:02');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `joursferies`
--

DROP TABLE IF EXISTS `joursferies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `joursferies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_ferie` date NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date_ferie` (`date_ferie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `joursferies`
--

LOCK TABLES `joursferies` WRITE;
/*!40000 ALTER TABLE `joursferies` DISABLE KEYS */;
/*!40000 ALTER TABLE `joursferies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rendezvous`
--

DROP TABLE IF EXISTS `rendezvous`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rendezvous` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `date_rdv` date NOT NULL,
  `heure_rdv` time NOT NULL,
  `motif` text NOT NULL,
  `statut` enum('En attente','Confirm??','Annul??') DEFAULT 'En attente',
  `vu_par_client` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `rendezvous_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rendezvous_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rendezvous`
--

LOCK TABLES `rendezvous` WRITE;
/*!40000 ALTER TABLE `rendezvous` DISABLE KEYS */;
/*!40000 ALTER TABLE `rendezvous` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secretaire`
--

DROP TABLE IF EXISTS `secretaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secretaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secretaire`
--

LOCK TABLES `secretaire` WRITE;
/*!40000 ALTER TABLE `secretaire` DISABLE KEYS */;
INSERT INTO `secretaire` VALUES (1,'sandra','sandra@gmail.com','$2y$10$j46B32nvj4dl92ckbWExRefMRsKhA4g4QJCJBn7hh90XkPOPnEsqm','2026-03-25 12:19:46');
/*!40000 ALTER TABLE `secretaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'assistance voyage','Nous vous accompagnons dans la planification de vos voyages, de la réservation à votre arrivée.','2026-03-11 10:45:51'),(2,'demande de visa','Profitez de notre expertise pour la constitution et le suivi de vos dossiers de visa.','2026-03-11 10:45:51'),(3,'regroupement familiale','Nous vous aidons dans toutes les démarches administratives pour réunir vos proches à l\'étranger.','2026-03-11 10:45:51'),(4,'prestataires de services','Nous vous proposons divers services administratifs pour simplifier vos projets de voyages.','2026-03-11 10:45:51'),(5,'vente de billets d\'avion','Réservez vos billets d\'avion aux meilleurs prix grâce à notre réseau de partenaires.','2026-03-11 10:45:51'),(6,'accompagnement processus','Nous assurons un suivi personnalisé pour chaque étape de votre démarche du début à la fin.','2026-03-11 10:45:51');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-27 13:45:13

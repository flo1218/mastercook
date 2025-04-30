/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: symrecipe
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-0ubuntu0.24.04.2

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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_64C19C1A76ED395` (`user_id`),
  CONSTRAINT `FK_64C19C1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES
(86,333,'Entrée','2025-02-13 15:07:56'),
(87,333,'Plat','2025-02-13 15:07:56'),
(88,333,'Dessert','2025-02-13 15:07:56'),
(90,327,'bfvbnvb','2025-02-18 18:07:11');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

--
-- Table structure for table `category_audit`
--

DROP TABLE IF EXISTS `category_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `category_audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `object_id` varchar(255) NOT NULL,
  `discriminator` varchar(255) DEFAULT NULL,
  `transaction_hash` varchar(40) DEFAULT NULL,
  `diffs` longtext DEFAULT NULL,
  `blame_id` varchar(255) DEFAULT NULL,
  `blame_user` varchar(255) DEFAULT NULL,
  `blame_user_fqdn` varchar(255) DEFAULT NULL,
  `blame_user_firewall` varchar(100) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `type_9d60be1ae31861a527fd590d589be976_idx` (`type`),
  KEY `object_id_9d60be1ae31861a527fd590d589be976_idx` (`object_id`),
  KEY `discriminator_9d60be1ae31861a527fd590d589be976_idx` (`discriminator`),
  KEY `transaction_hash_9d60be1ae31861a527fd590d589be976_idx` (`transaction_hash`),
  KEY `blame_id_9d60be1ae31861a527fd590d589be976_idx` (`blame_id`),
  KEY `created_at_9d60be1ae31861a527fd590d589be976_idx` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_audit`
--

/*!40000 ALTER TABLE `category_audit` DISABLE KEYS */;
INSERT INTO `category_audit` VALUES
(1,'update','78',NULL,'ef6e1218c56074625d264d8bed4f1c2f84068249','{\"name\":{\"new\":\"Entr\\u00e9e1\",\"old\":\"Entr\\u00e9e\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-16 15:22:57'),
(2,'update','78',NULL,'b4071e3dbfff97884db0c7f21ad5709eda4db676','{\"name\":{\"old\":\"Entr\\u00e9e1\",\"new\":\"Entr\\u00e9e1njj\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 14:41:11'),
(3,'update','78',NULL,'64d665b51a70966d078b1bf88dc7a1dcc3988d54','{\"name\":{\"old\":\"Entr\\u00e9e1njj\",\"new\":\"Entr\\u00e9e\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 14:46:23');
/*!40000 ALTER TABLE `category_audit` ENABLE KEYS */;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(180) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` longtext NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES
(173,'Auguste du Francois','teixeira.cecile@torres.org','Demande n°1','Distinctio occaecati perspiciatis nisi. Qui sed aut labore et. Possimus possimus numquam cum dolor nulla.','2025-02-10 17:23:33'),
(174,'Adèle Gillet','itorres@gmail.com','Demande n°2','Dignissimos quia et aliquid temporibus et vitae qui. Facere ut dolorum eos doloremque. Ex esse sint magni nesciunt atque.','2025-02-10 17:23:33'),
(175,'Marguerite-Gabrielle Bonnet','bonneau.edouard@orange.fr','Demande n°3','Saepe voluptas sit et ipsum non impedit nemo deleniti. Occaecati molestiae quos et. Voluptate voluptas ea error ut aut.','2025-02-10 17:23:33'),
(176,'François Bigot','robert40@club-internet.fr','Demande n°4','Aspernatur et est aut rem laborum velit. Est ut vel aut aut debitis nemo. Neque magni natus quia possimus quis.','2025-02-10 17:23:33'),
(177,'Claire Techer','colette.aubry@gmail.com','Demande n°5','Architecto dolor sit similique eos quaerat facilis. Enim error facilis ut excepturi. Iure aut fugit fugit minus sed. Veniam numquam harum debitis quia.','2025-02-10 17:23:33'),
(178,'Admin','admin@mastercook.ch','cxvc','xcvcvx','2025-02-18 18:41:15'),
(179,'Admin','admin@mastercook.ch','cxvc','xcvcvx','2025-02-18 18:47:39'),
(180,'Admin','admin@mastercook.ch','sdf','sdf','2025-02-18 18:50:01');
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20240220130359','2024-09-09 17:48:53',28),
('DoctrineMigrations\\Version20240221123551','2024-09-09 17:48:53',101),
('DoctrineMigrations\\Version20240221152440','2024-09-09 17:48:53',14),
('DoctrineMigrations\\Version20240223134459','2024-09-09 17:48:53',66),
('DoctrineMigrations\\Version20240223140248','2024-09-09 17:48:53',70),
('DoctrineMigrations\\Version20240223155744','2024-09-09 17:48:53',17),
('DoctrineMigrations\\Version20240225165820','2024-09-09 17:48:53',92),
('DoctrineMigrations\\Version20240226140940','2024-09-09 17:48:53',16),
('DoctrineMigrations\\Version20240227103959','2024-09-09 17:48:53',11),
('DoctrineMigrations\\Version20240229185808','2024-09-09 17:48:53',14),
('DoctrineMigrations\\Version20240408143715','2024-09-09 17:48:53',16),
('DoctrineMigrations\\Version20240419122611','2024-09-09 17:48:53',18),
('DoctrineMigrations\\Version20240508100906','2024-09-09 17:48:53',68),
('DoctrineMigrations\\Version20240823123430','2024-09-09 17:48:54',49),
('DoctrineMigrations\\Version20240823134731','2024-09-09 17:48:54',78),
('DoctrineMigrations\\Version20240909153953','2024-09-09 17:48:54',17),
('DoctrineMigrations\\Version20240911094220','2024-09-11 11:42:38',20),
('DoctrineMigrations\\Version20240917104414','2024-09-17 13:29:02',11);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6BAF7870A76ED395` (`user_id`),
  CONSTRAINT `FK_6BAF7870A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4252 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES
(4196,'Bacon',45,'2025-02-10 17:23:31',328),
(4197,'Myrtille',65,'2025-02-10 17:23:31',328),
(4198,'Banane',62,'2025-02-10 17:23:31',328),
(4199,'Beurre',87,'2025-02-10 17:23:31',328),
(4200,'Paupiette',56,'2025-02-10 17:23:31',328),
(4201,'Beurre',21,'2025-02-10 17:23:31',328),
(4202,'Mozzarella',72,'2025-02-10 17:23:31',328),
(4203,'Ananas',82,'2025-02-10 17:23:31',328),
(4204,'Poireau',70,'2025-02-10 17:23:31',328),
(4205,'Lait',93,'2025-02-10 17:23:31',328),
(4206,'Porc',22,'2025-02-10 17:23:32',329),
(4207,'Ananas',43,'2025-02-10 17:23:32',329),
(4208,'Romarin',96,'2025-02-10 17:23:32',329),
(4209,'Bacon',95,'2025-02-10 17:23:32',329),
(4210,'Jambon',15,'2025-02-10 17:23:32',329),
(4211,'Jambon',1,'2025-02-10 17:23:32',329),
(4212,'Dinde',58,'2025-02-10 17:23:32',329),
(4213,'Citron',25,'2025-02-10 17:23:32',329),
(4214,'Fromage',1,'2025-02-10 17:23:32',329),
(4215,'Mûre',74,'2025-02-10 17:23:32',329),
(4216,'Crème',73,'2025-02-10 17:23:32',330),
(4217,'Paupiette',83,'2025-02-10 17:23:32',330),
(4218,'Citrouille',20,'2025-02-10 17:23:32',330),
(4219,'Concombre',34,'2025-02-10 17:23:32',330),
(4220,'Bacon',44,'2025-02-10 17:23:32',330),
(4221,'Épinard',79,'2025-02-10 17:23:32',330),
(4222,'Mangue',54,'2025-02-10 17:23:32',330),
(4223,'Lait',58,'2025-02-10 17:23:32',330),
(4224,'Romarin',16,'2025-02-10 17:23:32',330),
(4225,'Beurre',25,'2025-02-10 17:23:32',330),
(4226,'Poivron',36,'2025-02-10 17:23:33',331),
(4227,'Jambon',14,'2025-02-10 17:23:33',331),
(4228,'Yaourt',95,'2025-02-10 17:23:33',331),
(4229,'Jambon',53,'2025-02-10 17:23:33',331),
(4230,'Oignon',48,'2025-02-10 17:23:33',331),
(4231,'Pastèque',57,'2025-02-10 17:23:33',331),
(4232,'Mozzarella',30,'2025-02-10 17:23:33',331),
(4233,'Bacon',51,'2025-02-10 17:23:33',331),
(4234,'Poulet',55,'2025-02-10 17:23:33',331),
(4235,'Dinde',84,'2025-02-10 17:23:33',331),
(4236,'Lait',82,'2025-02-10 17:23:33',332),
(4237,'Lait',35,'2025-02-10 17:23:33',332),
(4238,'Jambon',41,'2025-02-10 17:23:33',332),
(4239,'Banane',70,'2025-02-10 17:23:33',332),
(4240,'Yaourt',90,'2025-02-10 17:23:33',332),
(4241,'Basil',95,'2025-02-10 17:23:33',332),
(4242,'Bacon',33,'2025-02-10 17:23:33',332),
(4243,'Paupiette',39,'2025-02-10 17:23:33',332),
(4244,'Ananas',12,'2025-02-10 17:23:33',332),
(4245,'Basil',7,'2025-02-10 17:23:33',332),
(4246,'Sel',1,'2025-02-13 15:08:36',333),
(4247,'Poivre',1,'2025-02-13 15:08:42',333),
(4248,'Farine',1,'2025-02-13 15:08:47',333),
(4249,'Lait',1,'2025-02-13 15:08:51',333),
(4250,'Myrtille',1,'2025-02-13 17:28:18',333),
(4251,'fdvdv',4,'2025-03-03 17:29:30',327);
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;

--
-- Table structure for table `ingredient_audit`
--

DROP TABLE IF EXISTS `ingredient_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredient_audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `object_id` varchar(255) NOT NULL,
  `discriminator` varchar(255) DEFAULT NULL,
  `transaction_hash` varchar(40) DEFAULT NULL,
  `diffs` longtext DEFAULT NULL,
  `blame_id` varchar(255) DEFAULT NULL,
  `blame_user` varchar(255) DEFAULT NULL,
  `blame_user_fqdn` varchar(255) DEFAULT NULL,
  `blame_user_firewall` varchar(100) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `type_05f589f5ac1f819e38ca2076731075fd_idx` (`type`),
  KEY `object_id_05f589f5ac1f819e38ca2076731075fd_idx` (`object_id`),
  KEY `discriminator_05f589f5ac1f819e38ca2076731075fd_idx` (`discriminator`),
  KEY `transaction_hash_05f589f5ac1f819e38ca2076731075fd_idx` (`transaction_hash`),
  KEY `blame_id_05f589f5ac1f819e38ca2076731075fd_idx` (`blame_id`),
  KEY `created_at_05f589f5ac1f819e38ca2076731075fd_idx` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient_audit`
--

/*!40000 ALTER TABLE `ingredient_audit` DISABLE KEYS */;
INSERT INTO `ingredient_audit` VALUES
(1,'update','2920',NULL,'8fd93ff1dd48a4bd16da11d40b01b193735bcb31','{\"name\":{\"new\":\"Fraise4\",\"old\":\"Fraise3\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-16 15:23:03'),
(2,'update','2920',NULL,'1e98632046ca073bc6bb9097a052b7d15cf1e9c4','{\"name\":{\"old\":\"Fraise4\",\"new\":\"Fraise\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 14:47:21'),
(3,'update','2920',NULL,'a29a2f1433a8fd27e056d785515fba5fcefc4402','{\"name\":{\"old\":\"Fraise\",\"new\":\"Fraise1\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 14:47:36');
/*!40000 ALTER TABLE `ingredient_audit` ENABLE KEYS */;

--
-- Table structure for table `mark`
--

DROP TABLE IF EXISTS `mark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `mark` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_6674F271A76ED395` (`user_id`),
  KEY `IDX_6674F27159D8A214` (`recipe_id`),
  CONSTRAINT `FK_6674F27159D8A214` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`),
  CONSTRAINT `FK_6674F271A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79973 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mark`
--

/*!40000 ALTER TABLE `mark` DISABLE KEYS */;
INSERT INTO `mark` VALUES
(79912,328,51588,4,'2025-02-10 17:23:31'),
(79913,329,51588,4,'2025-02-10 17:23:32'),
(79914,329,51588,5,'2025-02-10 17:23:32'),
(79915,329,51588,1,'2025-02-10 17:23:32'),
(79916,329,51589,1,'2025-02-10 17:23:32'),
(79917,329,51590,3,'2025-02-10 17:23:32'),
(79918,329,51590,2,'2025-02-10 17:23:32'),
(79919,329,51591,3,'2025-02-10 17:23:32'),
(79922,330,51588,3,'2025-02-10 17:23:32'),
(79923,330,51588,4,'2025-02-10 17:23:32'),
(79924,330,51588,3,'2025-02-10 17:23:32'),
(79925,330,51588,4,'2025-02-10 17:23:32'),
(79926,330,51589,3,'2025-02-10 17:23:32'),
(79927,330,51590,5,'2025-02-10 17:23:32'),
(79928,330,51591,1,'2025-02-10 17:23:32'),
(79929,330,51591,4,'2025-02-10 17:23:32'),
(79930,330,51593,1,'2025-02-10 17:23:32'),
(79931,330,51594,2,'2025-02-10 17:23:32'),
(79932,330,51594,5,'2025-02-10 17:23:32'),
(79933,330,51594,4,'2025-02-10 17:23:32'),
(79935,331,51588,1,'2025-02-10 17:23:33'),
(79936,331,51589,2,'2025-02-10 17:23:33'),
(79937,331,51590,1,'2025-02-10 17:23:33'),
(79938,331,51590,4,'2025-02-10 17:23:33'),
(79939,331,51591,4,'2025-02-10 17:23:33'),
(79940,331,51592,3,'2025-02-10 17:23:33'),
(79941,331,51594,5,'2025-02-10 17:23:33'),
(79942,331,51597,5,'2025-02-10 17:23:33'),
(79943,331,51597,1,'2025-02-10 17:23:33'),
(79944,331,51597,4,'2025-02-10 17:23:33'),
(79945,331,51598,5,'2025-02-10 17:23:33'),
(79949,332,51588,5,'2025-02-10 17:23:33'),
(79950,332,51588,4,'2025-02-10 17:23:33'),
(79951,332,51589,3,'2025-02-10 17:23:33'),
(79952,332,51589,3,'2025-02-10 17:23:33'),
(79953,332,51590,4,'2025-02-10 17:23:33'),
(79954,332,51591,4,'2025-02-10 17:23:33'),
(79955,332,51591,5,'2025-02-10 17:23:33'),
(79956,332,51591,2,'2025-02-10 17:23:33'),
(79957,332,51591,1,'2025-02-10 17:23:33'),
(79958,332,51592,1,'2025-02-10 17:23:33'),
(79959,332,51594,4,'2025-02-10 17:23:33'),
(79960,332,51595,3,'2025-02-10 17:23:33'),
(79961,332,51596,3,'2025-02-10 17:23:33'),
(79962,332,51596,4,'2025-02-10 17:23:33'),
(79963,332,51597,4,'2025-02-10 17:23:33'),
(79964,332,51597,2,'2025-02-10 17:23:33'),
(79965,332,51598,1,'2025-02-10 17:23:33'),
(79966,332,51598,4,'2025-02-10 17:23:33'),
(79967,332,51598,3,'2025-02-10 17:23:33'),
(79968,332,51600,2,'2025-02-10 17:23:33'),
(79969,332,51601,5,'2025-02-10 17:23:33'),
(79970,332,51601,2,'2025-02-10 17:23:33'),
(79971,332,51601,1,'2025-02-10 17:23:33'),
(79972,327,51588,3,'2025-02-18 18:03:57');
/*!40000 ALTER TABLE `mark` ENABLE KEYS */;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `time` int(11) DEFAULT NULL,
  `nb_people` int(11) DEFAULT NULL,
  `difficulty` int(11) DEFAULT NULL,
  `description` longtext NOT NULL,
  `price` double DEFAULT NULL,
  `is_favorite` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int(11) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DA88B137A76ED395` (`user_id`),
  KEY `IDX_DA88B13712469DE2` (`category_id`),
  CONSTRAINT `FK_DA88B13712469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_DA88B137A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51604 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES
(51588,'Moules Marinières',528,NULL,1,'Qui doloremque quia occaecati temporibus et aut. Omnis doloremque optio odio aliquid ut magni.\r\n\r\nConsectetur nulla quos et aut consequuntur adipisci quis. Asperiores ab aliquid saepe ut officiis rerum. Sapiente odit ut aperiam nihil sint.\r\n\r\nEt quaerat itaque aliquid recusandae beatae dolor. Quod pariatur voluptatem est accusamus culpa ipsa. Accusamus amet est et doloribus voluptatem excepturi ex quia.',633,1,'2023-07-13 11:23:12','2025-02-10 17:36:52',328,1,NULL,NULL,'Timothée Labbe'),
(51589,'Tartiflette',1278,1,3,'Temporibus aliquam ut nihil ullam dolore. Distinctio culpa perferendis est dolores. A delectus quo distinctio veniam. Non ex voluptas ad id.\r\n\r\nEst ut blanditiis et nobis quae fuga ipsum. Similique sapiente eos eum ipsam at. Commodi saepe eius neque sint quis magni.\r\n\r\nDolore animi suscipit ut eos aut vel eos. Reiciendis velit labore voluptatem eum repellat. Voluptatem blanditiis eum non et aut eveniet. Nihil illo quae quia alias nulla eos corrupti. Dolore sint odio vel aspernatur.',463,1,'2022-05-09 00:30:16','2025-02-10 17:36:41',328,1,NULL,NULL,'Timothée Labbe'),
(51590,'Salade Grecque',79,4,3,'Consequatur provident veritatis soluta velit. Harum doloribus odit dolor totam porro et blanditiis. Voluptatem asperiores minus autem facilis qui explicabo et.\n\nQui ipsam praesentium aut aut quia voluptas. Ab autem nam rerum aliquid. Dolor recusandae fuga voluptates rerum.\n\nAut est explicabo esse harum voluptate saepe tempora. Dicta illum nam natus consectetur consequatur. Quos vitae ut quaerat ullam aut et.',605,0,'2024-02-10 23:22:00','2025-02-10 17:23:32',329,1,NULL,NULL,'Auguste Fernandes'),
(51591,'Hamburger',595,NULL,NULL,'Consequuntur ut et qui neque nobis quia. Officia est explicabo dolorem est autem reiciendis modi omnis. Aspernatur ratione autem ex quis. Expedita repudiandae itaque libero sit laborum quae perspiciatis.\n\nQuo odit recusandae tempore sit autem. Aut consequatur vel quo expedita soluta repellendus. Non quis praesentium voluptatem dolores at molestiae eum. Consectetur ipsam maxime aut alias fuga nam debitis tempore.\n\nCommodi placeat et ut et nisi. Ut ut expedita reprehenderit sed. Ad sit quo earum eveniet maxime. In est voluptatum ipsa. Dolor eos atque nisi dolor unde mollitia.',735,1,'2023-09-23 04:49:48','2025-02-10 17:23:32',329,0,NULL,NULL,'Auguste Fernandes'),
(51592,'Sandwich Vegan au fromage',848,NULL,1,'Fugit ut amet suscipit recusandae facere. Repellendus quae molestiae dolores aut. Praesentium tenetur iste omnis necessitatibus quia itaque. Nesciunt omnis deserunt numquam hic laborum fugit ea velit.\n\nLaboriosam excepturi eum nemo ut et et. Praesentium veritatis quo quas nihil laboriosam doloremque excepturi maiores. Aspernatur aperiam placeat rem tempore facilis numquam et aut. Veritatis rerum accusamus dignissimos nesciunt quam. Et id ipsum sit blanditiis et ut.\n\nQuae consequuntur natus provident. Voluptas est dolor dolorem et ut molestiae accusamus. Aspernatur iusto facere nihil minima. Sint sapiente ea laboriosam sit velit.',107,0,'2023-04-16 17:20:50','2025-02-10 17:23:32',329,0,NULL,NULL,'Auguste Fernandes'),
(51593,'Moules Marinières',604,1,3,'Officiis est cupiditate temporibus qui sit itaque veniam. Est quis quidem veritatis. Aliquam quaerat atque ut ea possimus atque. Neque sed eaque tempora.\n\nAsperiores esse inventore nulla. Non est ipsa possimus itaque. Porro ad consequatur consectetur. Rerum nemo beatae non nobis.\n\nReprehenderit culpa et architecto magnam alias quam. Perferendis consequatur omnis sit voluptatem. Consectetur dolor tempore at sapiente qui corrupti et.',123,1,'2023-03-23 21:15:53','2025-02-10 17:23:32',330,0,NULL,NULL,'David Gaillard'),
(51594,'Hamburger',1164,NULL,3,'Sit non cupiditate a quo in. Ea ut ut ab. Sint et doloremque exercitationem quod aperiam. Saepe et minima est tenetur consequuntur.\n\nRepudiandae et fugiat non quidem qui et. Autem excepturi velit quae incidunt. Illum quae officiis et dolorem aliquid molestias doloribus. Assumenda accusamus tenetur adipisci.\n\nCupiditate eveniet et voluptatem odit repellendus. Aperiam veniam ut voluptatem ipsam sequi laboriosam. Omnis quos ratione et qui velit sed esse. Expedita dolor sequi ut excepturi.',980,0,'2024-07-19 16:47:16','2025-02-10 17:23:32',330,1,NULL,NULL,'David Gaillard'),
(51595,'Salade Grecque',1215,1,NULL,'Voluptate id non molestias ipsum. Impedit recusandae consequatur quis quia tempora sapiente. Est officia laborum libero facilis.\n\nDolorum commodi repellat aperiam eum. Est voluptatem suscipit voluptatem exercitationem temporibus distinctio quia voluptates. Aut deleniti minima et modi laboriosam quis modi. Ea et ratione asperiores quas aut repudiandae.\n\nAmet odit culpa et est ipsam autem consequatur sit. Aperiam vero voluptatem a incidunt rerum officia debitis sunt. Nisi consectetur quis quidem harum vero nulla.',237,0,'2023-06-11 03:36:30','2025-02-10 17:23:32',330,0,NULL,NULL,'David Gaillard'),
(51596,'Hamburger',670,3,5,'Voluptatem veritatis temporibus magnam expedita ea quo excepturi tenetur. In tempore omnis ut iste libero eius sit. Aut iure quia consequatur maxime rem.\n\nEst incidunt aut fugit cum ab in. Reiciendis quia corporis cupiditate fugit. Nobis adipisci ut vel.\n\nSit ut molestiae ut culpa. Aut aut aut voluptatem accusamus iste earum magnam consequatur. Et corrupti sit et itaque reprehenderit nemo repellat. Voluptas beatae eveniet culpa quam accusantium. Delectus eum velit velit impedit saepe.',282,1,'2024-08-07 16:14:08','2025-02-10 17:23:33',331,1,NULL,NULL,'Patrick-Antoine Techer'),
(51597,'Sandwich Vegan au fromage',651,NULL,NULL,'Ea aut aperiam delectus voluptate maxime. At aspernatur quo quia nihil unde ipsa qui. Hic vel earum et molestiae voluptatem autem est.\n\nSequi impedit rerum deserunt error. Quae voluptatem sequi minima minus asperiores et. Quia et laudantium quia quisquam omnis et odio est. Assumenda molestiae maxime tempore voluptatem sunt ullam.\n\nQui ducimus dolor dolor harum aut et quia. Ut eos nobis enim ut iure non quia. Eligendi aut asperiores minima. Vel velit ex consequatur enim.',716,0,'2024-11-01 00:20:58','2025-02-10 17:23:33',331,0,NULL,NULL,'Patrick-Antoine Techer'),
(51598,'Fromage grillé',554,6,NULL,'Veritatis odio qui quasi consequatur placeat labore. At est delectus nesciunt voluptatem quidem voluptatem. Qui consequatur nihil nulla voluptas exercitationem reprehenderit. Quia quos officia necessitatibus odio.\n\nEligendi aut quis et quis recusandae qui dolore. Delectus ut voluptatem asperiores reprehenderit. Pariatur sunt ab eaque amet nihil odit sint amet. Illum perspiciatis nostrum incidunt.\n\nSequi itaque voluptatem assumenda veritatis sapiente delectus. Dolores labore ex corrupti asperiores provident eos. Quia cupiditate et maxime veniam.',237,0,'2024-12-24 13:05:25','2025-02-10 17:23:33',331,0,NULL,NULL,'Patrick-Antoine Techer'),
(51599,'Cheeseburger',275,9,NULL,'Vitae ea hic aut. Repudiandae temporibus nostrum quia vero tempore est nemo. Et rem incidunt dolorem fugiat laborum velit. Cum aut optio ut sunt.\n\nTemporibus qui voluptas sapiente quibusdam. Ut officiis neque corrupti velit molestiae aliquam molestiae. Reiciendis fugiat qui dolor illo magnam sit placeat.\n\nItaque quam fuga modi quia. Explicabo enim mollitia numquam qui rem perferendis similique. Nostrum error voluptas quod et similique saepe reiciendis. Est dicta at ratione quo possimus.',212,0,'2023-11-05 14:17:58','2025-02-10 17:23:33',332,1,NULL,NULL,'Capucine Collet'),
(51600,'Hamburger',785,NULL,2,'Minima officiis ut quia cumque error ad. Sit nisi nesciunt odit voluptatibus. Qui eos id voluptatem. Architecto amet minima optio assumenda iusto nulla.\n\nFugit maiores ea eius ex totam qui. Sit dicta quia mollitia. Est est saepe nulla voluptas ea aut.\n\nNumquam quo qui hic asperiores occaecati. Soluta illum nostrum dolore quo qui consequatur ipsa. Sapiente tempore vel labore officiis quia laboriosam. Quisquam iure architecto expedita perferendis quo.',285,1,'2022-11-14 05:37:50','2025-02-10 17:23:33',332,0,NULL,NULL,'Capucine Collet'),
(51601,'Sandwich Vegan au fromage',152,6,NULL,'Maxime voluptas modi eligendi velit aliquid. Ipsum minima optio unde nemo. Magnam aut quis doloribus culpa. Explicabo iure quo pariatur voluptas eum ut ut sint.\n\nExpedita nihil laboriosam et molestiae minus. Dolorem culpa voluptatem inventore fugiat temporibus iste neque. Aspernatur ab est nemo. Sed dolor aspernatur maxime enim dolores atque similique cupiditate. Accusantium aut molestiae consectetur ipsa et.\n\nNon sunt quo pariatur quia vitae. Doloremque nisi architecto velit officia omnis quisquam. Nemo odio inventore quis earum voluptatem rerum fugiat. Praesentium earum labore repellendus vel. Sapiente amet unde ad.',129,1,'2022-10-24 14:13:00','2025-02-10 17:23:33',332,0,NULL,NULL,'Capucine Collet'),
(51602,'Raclette',10,4,3,'tets',NULL,1,'2025-02-13 15:09:15','2025-02-13 17:12:32',333,1,NULL,87,'Estoppey Florian'),
(51603,'fthghgh',NULL,NULL,3,'fgfdg',NULL,0,'2025-02-20 15:53:47','2025-03-03 18:04:38',327,0,NULL,90,'Admin');
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;

--
-- Table structure for table `recipe_audit`
--

DROP TABLE IF EXISTS `recipe_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe_audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `object_id` varchar(255) NOT NULL,
  `discriminator` varchar(255) DEFAULT NULL,
  `transaction_hash` varchar(40) DEFAULT NULL,
  `diffs` longtext DEFAULT NULL,
  `blame_id` varchar(255) DEFAULT NULL,
  `blame_user` varchar(255) DEFAULT NULL,
  `blame_user_fqdn` varchar(255) DEFAULT NULL,
  `blame_user_firewall` varchar(100) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `type_0975736b00f08bb93150a511c0072d17_idx` (`type`),
  KEY `object_id_0975736b00f08bb93150a511c0072d17_idx` (`object_id`),
  KEY `discriminator_0975736b00f08bb93150a511c0072d17_idx` (`discriminator`),
  KEY `transaction_hash_0975736b00f08bb93150a511c0072d17_idx` (`transaction_hash`),
  KEY `blame_id_0975736b00f08bb93150a511c0072d17_idx` (`blame_id`),
  KEY `created_at_0975736b00f08bb93150a511c0072d17_idx` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_audit`
--

/*!40000 ALTER TABLE `recipe_audit` DISABLE KEYS */;
INSERT INTO `recipe_audit` VALUES
(1,'update','50870',NULL,'fce6806adfc9651602af2f0d8640e94e55d46860','{\"name\":{\"new\":\"Pate\",\"old\":\"Patestt\"},\"updatedAt\":{\"new\":\"2024-10-16 17:07:45\",\"old\":\"2024-10-16 17:02:15\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-16 15:07:45'),
(2,'update','50870',NULL,'add0fe51b288d3bebd5107349a33efe73dd62844','{\"time\":{\"new\":80,\"old\":90},\"updatedAt\":{\"new\":\"2024-10-16 17:08:16\",\"old\":\"2024-10-16 17:07:45\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-16 15:08:16'),
(3,'update','50870',NULL,'8e1503011bfecb0095950d06ceca9c8e2bd75d2f','{\"time\":{\"new\":10,\"old\":80},\"updatedAt\":{\"new\":\"2024-10-16 17:37:04\",\"old\":\"2024-10-16 17:08:16\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-16 15:37:04'),
(4,'update','50870',NULL,'5dc5748270a681c6830ad56832cc51eecb5a5bb9','{\"description\":{\"new\":\"Magni a eos ut ea illo. Incidunt cupiditate consequatur odio quia aut numquam qui. Similique neque enim adipisci rem officia rem aut officiis. Deleniti atque quia quia quis.\\r\\n\\r\\nFugit veniam eum cupiditate placeat vel doloremque. Aut aut est ipsum corporis id qui. Voluptatum adipisci maxime rerum itaque dolorum. Totam vel harum molestiae necessitatibus.\\r\\n\\r\\nRepellendus nam laudantium qui quas doloremque et illum. Quas cum molestias ipsa explicabo tempore recusandae iusto. Facilis est nisi occaecati provident.22\",\"old\":\"Magni a eos ut ea illo. Incidunt cupiditate consequatur odio quia aut numquam qui. Similique neque enim adipisci rem officia rem aut officiis. Deleniti atque quia quia quis.\\r\\n\\r\\nFugit veniam eum cupiditate placeat vel doloremque. Aut aut est ipsum corporis id qui. Voluptatum adipisci maxime rerum itaque dolorum. Totam vel harum molestiae necessitatibus.\\r\\n\\r\\nRepellendus nam laudantium qui quas doloremque et illum. Quas cum molestias ipsa explicabo tempore recusandae iusto. Facilis est nisi occaecati provident.\"},\"updatedAt\":{\"new\":\"2024-10-16 17:37:20\",\"old\":\"2024-10-16 17:37:04\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-16 15:37:20'),
(5,'update','50870',NULL,'e94a0c7131dde9e61ca53191eb72ce2f3de85116','{\"price\":{\"new\":10,\"old\":726},\"updatedAt\":{\"new\":\"2024-10-16 17:40:46\",\"old\":\"2024-10-16 17:37:20\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-16 15:40:46'),
(6,'update','50870',NULL,'40b463a7eeb119b40bda8f1f35b5d16c11f8d3b8','{\"price\":{\"new\":12,\"old\":10}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:07:56'),
(7,'update','50885',NULL,'7b3eb32a45f8dfbb874c30f638fe710e4fc385f6','{\"name\":{\"new\":\"Petit Cheeseburger1\",\"old\":\"Petit Cheeseburger\"},\"difficulty\":{\"new\":3,\"old\":null},\"description\":{\"new\":\"Ut rerum autem a. Tenetur sunt recusandae molestiae sit non. Corporis deleniti non quia eveniet qui natus amet voluptas.\\r\\n\\r\\nVoluptates quia voluptate molestias omnis ut. Sed rerum quidem non quia iste accusantium. Rerum maxime sunt provident voluptatem fuga rerum inventore. Sequi vitae facilis quia voluptatibus aliquam magnam.\\r\\n\\r\\nIncidunt dolor quo dolor atque cupiditate. Dolor tempora incidunt nam distinctio ut. Quibusdam nihil nobis accusantium vel nulla vitae veritatis rerum.\",\"old\":\"Ut rerum autem a. Tenetur sunt recusandae molestiae sit non. Corporis deleniti non quia eveniet qui natus amet voluptas.\\n\\nVoluptates quia voluptate molestias omnis ut. Sed rerum quidem non quia iste accusantium. Rerum maxime sunt provident voluptatem fuga rerum inventore. Sequi vitae facilis quia voluptatibus aliquam magnam.\\n\\nIncidunt dolor quo dolor atque cupiditate. Dolor tempora incidunt nam distinctio ut. Quibusdam nihil nobis accusantium vel nulla vitae veritatis rerum.\"}}','248','sebastien25@poirier.fr','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:18:28'),
(8,'update','50870',NULL,'f394fd9e3ae126042463fe9acfe91fe434a4a4c1','{\"price\":{\"new\":13,\"old\":12}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:19:24'),
(9,'update','50885',NULL,'fe4015e5efa29b9edd6a2b014457d0780f54d6a4','{\"isPublic\":{\"new\":true,\"old\":false}}','248','sebastien25@poirier.fr','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:23:04'),
(10,'update','50882',NULL,'6970bf094f0739393d9b65224bfbff769a7f40d2','{\"difficulty\":{\"new\":3,\"old\":null},\"description\":{\"new\":\"Quod tenetur est laudantium rem sit possimus. Tenetur fugiat voluptatem voluptates ut dolor delectus. Eum in dicta ut quo adipisci et accusamus. Omnis consequatur dolor et et rerum quibusdam.\\r\\n\\r\\nAut consequatur enim iure amet alias. Aut labore facere enim ad rerum sit. Beatae eos non cum eos. Dolor consectetur accusantium sapiente aut non tempore ut eum.\\r\\n\\r\\nEt perspiciatis 3aut iste ut quasi. Aut quis adipisci porro doloremque eos. Sed non veniam mollitia ipsa aspernatur.\",\"old\":\"Quod tenetur est laudantium rem sit possimus. Tenetur fugiat voluptatem voluptates ut dolor delectus. Eum in dicta ut quo adipisci et accusamus. Omnis consequatur dolor et et rerum quibusdam.\\n\\nAut consequatur enim iure amet alias. Aut labore facere enim ad rerum sit. Beatae eos non cum eos. Dolor consectetur accusantium sapiente aut non tempore ut eum.\\n\\nEt perspiciatis aut iste ut quasi. Aut quis adipisci porro doloremque eos. Sed non veniam mollitia ipsa aspernatur.\"}}','248','sebastien25@poirier.fr','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:23:48'),
(11,'update','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"description\":{\"new\":\"Sed enim repellat aperiam vel iusto cupiditate. Dolorum maiores quia accusantium a et dolorum quae. Eos assumenda odit quos dolor delectus sit cum aut. Repellat magnam aut ratione iusto facere voluptates. Accusamus reiciendis et atque mollitia esse.\\r\\n\\r\\nAutem et nam aliquam qui adipisci aut. Consequatur deserunt libero voluptatem rerum maiores voluptas magni. Et sit magnam dolores voluptatibus.\\r\\n\\r\\nEaque autem fuga et eos. Quos sunt ut voluptatem quidem sit amet sed. Culpa exercitationem nesciunt omnis placeat consectetur accusamus consectetur. In quisquam natus at error eum reiciendis molestiae. Suscipit sint nam impedit.\",\"old\":\"Sed enim repellat aperiam vel iusto cupiditate. Dolorum maiores quia accusantium a et dolorum quae. Eos assumenda odit quos dolor delectus sit cum aut. Repellat magnam aut ratione iusto facere voluptates. Accusamus reiciendis et atque mollitia esse.\\n\\nAutem et nam aliquam qui adipisci aut. Consequatur deserunt libero voluptatem rerum maiores voluptas magni. Et sit magnam dolores voluptatibus.\\n\\nEaque autem fuga et eos. Quos sunt ut voluptatem quidem sit amet sed. Culpa exercitationem nesciunt omnis placeat consectetur accusamus consectetur. In quisquam natus at error eum reiciendis molestiae. Suscipit sint nam impedit.\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(12,'dissociate','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"source\":{\"id\":50943,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50943\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2912,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Bacon\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(13,'dissociate','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"source\":{\"id\":50943,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50943\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2930,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Fromage\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(14,'dissociate','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"source\":{\"id\":50943,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50943\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2931,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Goyave\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(15,'dissociate','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"source\":{\"id\":50943,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50943\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2934,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Pomme de terre\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(16,'dissociate','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"source\":{\"id\":50943,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50943\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2944,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Framboise\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(17,'dissociate','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"source\":{\"id\":50943,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50943\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2951,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Cr\\u00e8me\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(18,'dissociate','50943',NULL,'d9ac77687ad6f98d7dc21ab6dac26ecbe81f5908','{\"source\":{\"id\":50943,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50943\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2956,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Lait\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-17 16:25:27'),
(19,'update','50870',NULL,'33d436f82864f2a2072db62b7f615c228be8ad5b','{\"isFavorite\":{\"new\":true,\"old\":false},\"isPublic\":{\"new\":true,\"old\":false}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-21 19:01:56'),
(20,'associate','50870',NULL,'37a06c949c48c24238ecb302bc7cd2c287e5a064','{\"source\":{\"id\":50870,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50870\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2949,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Persil\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-21 19:02:15'),
(21,'associate','50870',NULL,'37a06c949c48c24238ecb302bc7cd2c287e5a064','{\"source\":{\"id\":50870,\"class\":\"App\\\\Entity\\\\Recipe\",\"label\":\"App\\\\Entity\\\\Recipe#50870\",\"table\":\"recipe\",\"field\":\"ingredients\"},\"target\":{\"id\":2955,\"class\":\"App\\\\Entity\\\\Ingredient\",\"label\":\"Poireau\",\"table\":\"ingredient\",\"field\":null},\"is_owning_side\":true,\"table\":\"recipe_ingredient\"}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-21 19:02:15'),
(22,'update','50870',NULL,'2f5655a945b6aeff823be4ba4f7dd9427c3778c9','{\"name\":{\"old\":\"Pate\",\"new\":\"Pate2\"},\"updatedAt\":{\"old\":\"2024-10-21 19:02:15\",\"new\":\"2024-10-28 15:48:58\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 14:48:58'),
(23,'update','50870',NULL,'039b90fdf7d850cda09583ffb3722af38ed074ee','{\"name\":{\"old\":\"Pate2\",\"new\":\"Pate3\"},\"updatedAt\":{\"old\":\"2024-10-28 15:48:58\",\"new\":\"2024-10-28 15:50:21\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 15:50:21'),
(24,'update','50870',NULL,'668f56fc888369ba739040fe934b5733dfcc57a4','{\"name\":{\"old\":\"Pate3\",\"new\":\"Pate4\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 15:51:46'),
(25,'update','50870',NULL,'7f6734d4626bf7b964ae32a353ddc267c96ec308','{\"name\":{\"old\":\"Pate4\",\"new\":\"Pate5\"}}','247','admin@mastercook.ch','DH\\Auditor\\User\\User','main','127.0.0.1','2024-10-28 15:52:04');
/*!40000 ALTER TABLE `recipe_audit` ENABLE KEYS */;

--
-- Table structure for table `recipe_ingredient`
--

DROP TABLE IF EXISTS `recipe_ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe_ingredient` (
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  PRIMARY KEY (`recipe_id`,`ingredient_id`),
  KEY `IDX_22D1FE1359D8A214` (`recipe_id`),
  KEY `IDX_22D1FE13933FE08C` (`ingredient_id`),
  CONSTRAINT `FK_22D1FE1359D8A214` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_22D1FE13933FE08C` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_ingredient`
--

/*!40000 ALTER TABLE `recipe_ingredient` DISABLE KEYS */;
INSERT INTO `recipe_ingredient` VALUES
(51588,4196),
(51588,4197),
(51588,4202),
(51588,4203),
(51589,4197),
(51589,4198),
(51589,4199),
(51589,4203),
(51589,4204),
(51590,4207),
(51590,4208),
(51590,4210),
(51590,4214),
(51591,4207),
(51591,4208),
(51591,4214),
(51591,4215),
(51592,4208),
(51592,4212),
(51592,4214),
(51593,4217),
(51593,4218),
(51593,4222),
(51593,4224),
(51593,4225),
(51594,4216),
(51594,4218),
(51594,4219),
(51594,4220),
(51594,4225),
(51595,4217),
(51595,4221),
(51596,4230),
(51596,4233),
(51596,4234),
(51596,4235),
(51597,4226),
(51597,4227),
(51597,4230),
(51597,4234),
(51597,4235),
(51598,4227),
(51598,4233),
(51598,4235),
(51599,4240),
(51599,4241),
(51599,4242),
(51599,4243),
(51600,4236),
(51600,4240),
(51600,4241),
(51600,4242),
(51601,4238),
(51601,4242),
(51601,4243),
(51601,4244),
(51602,4246),
(51602,4247),
(51602,4248),
(51602,4249),
(51603,4251);
/*!40000 ALTER TABLE `recipe_ingredient` ENABLE KEYS */;

--
-- Table structure for table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_password_request`
--

/*!40000 ALTER TABLE `reset_password_request` DISABLE KEYS */;
INSERT INTO `reset_password_request` VALUES
(2,333,'ITIzfnp9L58Dko2VhlIP','AQxKsFnBCikGvbySK8wRuOOIqJWEmisfXYNF8bifzIM=','2025-02-20 17:39:34','2025-02-20 18:39:34');
/*!40000 ALTER TABLE `reset_password_request` ENABLE KEYS */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `language` varchar(255) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=334 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(327,'Admin','Admin','admin@mastercook.ch','[\"ROLE_USER\",\"ROLE_ADMIN\"]','$2y$13$LKdjw2Dw7Sv0LsQCImV6l.3fiS3p0XEZYstmS44IVRWfvi0EKZ9Ui','2025-02-10 17:23:30','fr',NULL),
(328,'Timothée Labbe',NULL,'audrey.guyot@wanadoo.fr','[\"ROLE_USER\"]','$2y$13$ioTitBaUw/g8b3B31IQrCuKIWVidUBsp3wgONGsTs2aE3w1Wp7zsy','2025-02-10 17:23:31','fr',NULL),
(329,'Auguste Fernandes',NULL,'dias.theophile@yahoo.fr','[\"ROLE_USER\"]','$2y$13$uBsyvSXV7Uy1.q5TqSBo6u5fsyrBNIEh8him2k4rzgIEhDt2iYd36','2025-02-10 17:23:31','fr',NULL),
(330,'David Gaillard',NULL,'alex.didier@hotmail.fr','[\"ROLE_USER\"]','$2y$13$D/iuF5BSnwleN9nBWYLFiuOpH6g1GKXg3yVgezKi5Cp7hYzylkwRy','2025-02-10 17:23:32','fr',NULL),
(331,'Patrick-Antoine Techer','Sabine','dominique.arnaud@noos.fr','[\"ROLE_USER\"]','$2y$13$bK6u7b/G2EsFgmwOMlGmbO1jp85wz4ZhK/P2wJbakjCmOEsieSkrS','2025-02-10 17:23:32','fr',NULL),
(332,'Capucine Collet',NULL,'renee87@wanadoo.fr','[\"ROLE_USER\"]','$2y$13$iYSyWS.GUP8LJuKRsZS23.AX2JGloTXppclCF6AJ165eg67MeM2zW','2025-02-10 17:23:33','fr',NULL),
(333,'Estoppey Florian','flo1217','estoppey.florian@gmail.com','[\"ROLE_USER\"]','$2y$13$IHdJdh39oujt.md7ssRDUeZcjF5YYhP9NNCs9nPgbyVyUQQFnhD7K','2025-02-13 15:07:56','fr',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

--
-- Temporary table structure for view `view_recipe`
--

DROP TABLE IF EXISTS `view_recipe`;
/*!50001 DROP VIEW IF EXISTS `view_recipe`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_recipe` AS SELECT
 1 AS `id`,
  1 AS `name`,
  1 AS `time`,
  1 AS `nb_people`,
  1 AS `difficulty`,
  1 AS `description`,
  1 AS `price`,
  1 AS `is_favorite`,
  1 AS `is_public`,
  1 AS `created_at`,
  1 AS `updated_at`,
  1 AS `user_id`,
  1 AS `image_name`,
  1 AS `category_id`,
  1 AS `average`,
  1 AS `created_by`,
  1 AS `user_image_name`,
  1 AS `category_name` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'symrecipe'
--

--
-- Final view structure for view `view_recipe`
--

/*!50001 DROP VIEW IF EXISTS `view_recipe`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_recipe` AS select `r`.`id` AS `id`,`r`.`name` AS `name`,`r`.`time` AS `time`,`r`.`nb_people` AS `nb_people`,`r`.`difficulty` AS `difficulty`,`r`.`description` AS `description`,`r`.`price` AS `price`,`r`.`is_favorite` AS `is_favorite`,`r`.`is_public` AS `is_public`,`r`.`created_at` AS `created_at`,`r`.`updated_at` AS `updated_at`,`r`.`user_id` AS `user_id`,`r`.`image_name` AS `image_name`,`r`.`category_id` AS `category_id`,round(avg(`m`.`mark`),2) AS `average`,`u`.`full_name` AS `created_by`,`u`.`image_name` AS `user_image_name`,`c`.`name` AS `category_name` from (((`recipe` `r` left join `mark` `m` on(`m`.`recipe_id` = `r`.`id`)) join `user` `u` on(`r`.`user_id` = `u`.`id`)) left join `category` `c` on(`r`.`category_id` = `c`.`id`)) group by `r`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-30 16:43:06

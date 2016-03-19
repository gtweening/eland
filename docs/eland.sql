-- Host: localhost    Database: ObstacleLogDB
-- ------------------------------------------------------

--
-- Table structure for table `TblCheckpoints`
--
DROP TABLE IF EXISTS `TblCheckpoints`;
CREATE TABLE `TblCheckpoints` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Omschr` char(50) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Omschr` (`Omschr`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;


--
-- Dumping data for table `TblCheckpoints`
--
LOCK TABLES `TblCheckpoints` WRITE;
/*!40000 ALTER TABLE `TblCheckpoints` DISABLE KEYS */;
INSERT INTO `TblCheckpoints` VALUES (3,'Ligt er rommel onder de hindernis?'),(5,'Moeten de touwen worden opgespannen?'),(2,'Zijn de ankerpunten OK?'),(4,'Zijn de knopen OK?'),(7,'zijn de touwen versleten?');
/*!40000 ALTER TABLE `TblCheckpoints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TblMaterials`
--
DROP TABLE IF EXISTS `TblMaterials`;
CREATE TABLE `TblMaterials` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Omschr` char(50) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Omschr` (`Omschr`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;


--
-- Dumping data for table `TblMaterials`
--
LOCK TABLES `TblMaterials` WRITE;
INSERT INTO `TblMaterials` VALUES (66,'draadeind'),(69,'gevlochten ogen aan draadeind'),(57,'inklim-/daaltouw 28mm'),(67,'kunststof buis rond 50cm'),(70,'natuurbalk'),(68,'stalen buis rond 50mm'),(71,'strop'),(1,'Touw 18mm'),(2,'Touw 28mm');
UNLOCK TABLES;

--
-- Table structure for table `TblObstacleCheckpoints`
--
DROP TABLE IF EXISTS `TblObstacleCheckpoints`;
CREATE TABLE `TblObstacleCheckpoints` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Obstacle_id` int(11) NOT NULL,
  `Checkpoint_id` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;


--
-- Table structure for table `TblObstacleChecks`
--
DROP TABLE IF EXISTS `TblObstacleChecks`;
CREATE TABLE `TblObstacleChecks` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Obstacle_id` int(11) NOT NULL,
  `DatCheck` date DEFAULT NULL,
  `ChkSt` tinyint(1) DEFAULT NULL,
  `CheckedBy` varchar(30) DEFAULT NULL,
  `Note` text CHARACTER SET utf8,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;


--
-- Table structure for table `TblObstacleMaterials`
--
DROP TABLE IF EXISTS `TblObstacleMaterials`;
CREATE TABLE `TblObstacleMaterials` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Obstacle_id` int(11) NOT NULL,
  `Material_id` int(11) NOT NULL,
  `Aantal` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;


--
-- Table structure for table `TblObstacles`
--
DROP TABLE IF EXISTS `TblObstacles`;
CREATE TABLE `TblObstacles` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Section_id` int(11) NOT NULL,
  `Volgnr` int(11) NOT NULL,
  `Omschr` varchar(50) DEFAULT NULL,
  `ImgPath` varchar(120) DEFAULT NULL,
  `ChkQ1` tinyint(1) DEFAULT NULL,
  `ChkQ2` tinyint(1) DEFAULT NULL,
  `ChkQ3` tinyint(1) DEFAULT NULL,
  `ChkQ4` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;


--
-- Table structure for table `TblSections`
--
DROP TABLE IF EXISTS `TblSections`;
CREATE TABLE `TblSections` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Naam` varchar(5) NOT NULL,
  `Omschr` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;


--
-- Table structure for table `TblUsers`
--
DROP TABLE IF EXISTS `TblUsers`;
CREATE TABLE `TblUsers` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(128) DEFAULT NULL,
  `salt` char(128) DEFAULT NULL,
  `Admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `TblUsers`
--
LOCK TABLES `TblUsers` WRITE;
INSERT INTO `TblUsers` VALUES  (7,'beheerder@beheer.nl','3aa009f391a233625ed15df8e29c8fda492ff662ffe48965b692636286a48c1b159ee57e2487e06c725fff3ada64e6fd08d07340bb7db00e07450e9a04c7d479','51b1d5dcde9253ee271f52f09e57fe177c572bb248480ec0e8447f60409c21fc7101aca3e822990a5c4e38189fe04eda4e8fd1b6e3b7342f36d422adef2bb131',1);
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






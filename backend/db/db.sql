-- Table structure for `admin_activities`
CREATE TABLE `admin_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(300) NOT NULL,
  `contentInEn` varchar(300) DEFAULT NULL,
  `adminRef_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `adminRef_id` (`adminRef_id`),
  CONSTRAINT `admin_activities_ibfk_1` FOREIGN KEY (`adminRef_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin_activities` VALUES ('25', 'dsadadasdsadsadasdasd', NULL, '4', '2024-06-27 11:33:46');
INSERT INTO `admin_activities` VALUES ('26', 'dsadsadsad', NULL, '5', '2024-06-27 11:33:52');
INSERT INTO `admin_activities` VALUES ('27', 'dasdsadsadsadsa', NULL, '4', '2024-06-27 11:33:57');
INSERT INTO `admin_activities` VALUES ('28', 'dsadasdasdasd', NULL, '5', '2024-06-27 11:34:08');
INSERT INTO `admin_activities` VALUES ('29', 'dsadasdasdasd', NULL, '5', '2024-06-27 11:34:08');
INSERT INTO `admin_activities` VALUES ('30', 'dsadadasdsadsadasdasd', NULL, '4', '2024-06-27 11:33:46');
INSERT INTO `admin_activities` VALUES ('31', 'dsadsadsad', NULL, '5', '2024-06-27 11:33:52');
INSERT INTO `admin_activities` VALUES ('32', 'dasdsadsadsadsa', NULL, '4', '2024-06-27 11:33:57');
INSERT INTO `admin_activities` VALUES ('33', 'dsadasdasdasd', NULL, '5', '2024-06-27 11:34:08');
INSERT INTO `admin_activities` VALUES ('34', 'dsadasdasdasd', NULL, '5', '2024-06-27 11:34:08');

-- Table structure for `admins`
CREATE TABLE `admins` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `adminId` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(500) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `created_at` date DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` VALUES ('4', '6672c1840a967', '1', 'knorr_admin', '0developedbybarley@gmail.com', '$2y$10$LtbEMJPx/OGVO7WjcrbWqOBc7sufmGQcErg5DxPDDcQQerTeMHtla', 'bear', '2024-06-19');
INSERT INTO `admins` VALUES ('5', '6672c1b72a8cf', '3', 'knorr_user', '0developedbybarley@gmail.com', '$2y$10$NIW/tZ1w4P1cMctQi/S4/uEDQg/FgCz/1UuFOBHo90yI5rI.5JzKa', 'man', '2024-06-19');
INSERT INTO `admins` VALUES ('14', '667d5b2c37694', '0', 'Barleysdasddad', 'Barley@gmail.com', '$2y$10$U1WLkh2qzKMGKXKDCYpSmeN/AskkEYXCkyfCEfcASrT0fkNGwnTmS', 'bear', '2024-06-27');

-- Table structure for `feedbacks`
CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_ip` varchar(45) NOT NULL,
  `feedback` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `feedbacks` VALUES ('13', '::1', '0', '2024-07-01 20:13:39');

-- Table structure for `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `class` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `ident_number` int(11) NOT NULL,
  `main_teamRef_id` int(11) DEFAULT NULL,
  `team_sportRef_id` int(11) DEFAULT NULL,
  `duel_sportRef_id` int(11) DEFAULT NULL,
  `chess` tinyint(1) NOT NULL,
  `run` tinyint(1) NOT NULL,
  `transfer` int(11) NOT NULL,
  `vegetarian` tinyint(1) NOT NULL,
  `actimo` tinyint(1) NOT NULL,
  `created_at` date DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `main_teamRef_id` (`main_teamRef_id`),
  KEY `duel_sportRef_id` (`duel_sportRef_id`),
  KEY `team_sportRef_id` (`team_sportRef_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for `visits`
CREATE TABLE `visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `operating_system` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `visit_start` datetime NOT NULL DEFAULT current_timestamp(),
  `visit_end` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `visits` VALUES ('37', 'mrr3m72418tv9hps6f2rtediil', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'Windows NT', 'http://localhost:8080/admin/dashboard', 'Desktop', 'Localhost', '2024-07-01 11:50:00', '2024-07-01 11:50:01');
INSERT INTO `visits` VALUES ('38', 'ooota5554vt1pm9iedfds9jqq5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'Windows NT', 'http://localhost:8080/admin/dashboard', 'Desktop', 'Localhost', '2024-07-01 14:19:15', '2024-07-01 14:23:27');
INSERT INTO `visits` VALUES ('39', '0491omjfgvemicg50o93olh8qn', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'Windows NT', 'http://localhost:8080/admin/dashboard', 'Desktop', 'Localhost', '2024-07-01 14:23:40', '2024-07-01 14:23:46');


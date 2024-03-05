-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 05, 2024 at 09:25 AM
-- Server version: 8.0.31
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api-videos`
--

-- --------------------------------------------------------

--
-- Table structure for table `auteurs`
--

CREATE TABLE `auteurs` (
  `id` int NOT NULL,
  `url_pic` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `verifie` tinyint(1) NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auteurs`
--

INSERT INTO `auteurs` (`id`, `url_pic`, `nom`, `pseudo`, `verifie`, `description`) VALUES
(1, 'https://api.dicebear.com/7.x/thumbs/svg?seed=Dusty', 'Bob billy', 'billyBob', 1, 'Bob de Québec'),
(2, 'https://api.dicebear.com/7.x/thumbs/svg?seed=Garfield', 'Mary Jane', 'MaryGang', 1, 'Mange Prie Aime'),
(3, 'https://api.dicebear.com/7.x/thumbs/svg?seed=Daisy', 'Leo Naldo', 'LeoNaldo88', 1, 'Leo d\'Italie, bonjour le monde!'),
(4, 'https://api.dicebear.com/7.x/thumbs/svg?seed=Bella', 'Corine Bangaran', 'CocoBongo', 0, ''),
(5, 'https://api.dicebear.com/7.x/thumbs/svg?seed=Boots', 'Dean Duckster', 'debugDuck', 0, 'Le canard jaune de debug ne connaît pas de limites.');

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

CREATE TABLE `avis` (
  `id` int NOT NULL,
  `video_id` int DEFAULT NULL,
  `auteur_id` int DEFAULT NULL,
  `reaction` enum('like','dislike') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `commentaire` text COLLATE utf8mb4_general_ci,
  `note` int DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avis`
--

INSERT INTO `avis` (`id`, `video_id`, `auteur_id`, `reaction`, `commentaire`, `note`, `date`) VALUES
(4, 1, 3, 'like', 'commentaires 1', 9, '2003-11-20 19:49:35'),
(5, 1, 2, NULL, 'commentaires 2', NULL, '2003-08-27 21:54:48'),
(8, 3, 5, NULL, 'commentaires 3', 8, '2020-12-25 21:09:02'),
(11, 1, 2, 'like', 'commentaires 6', 5, '2003-11-20 19:49:35'),
(12, 1, 4, 'dislike', 'commentaires 7', 5, '2003-11-20 19:49:35'),
(13, 1, 1, NULL, 'commentaires Test put - modification reaction score', 5, '2003-11-21 14:41:14'),
(15, 1, 1, 'dislike', 'commentaires - test diminution de score.', 5, '2003-11-20 19:49:35'),
(16, 1, 1, 'dislike', 'commentaires - test diminution de score.', 5, '2003-11-20 19:49:35'),
(17, 1, 1, 'dislike', 'commentaires - test diminution de score.', 5, '2003-11-20 19:49:35'),
(18, 1, 1, 'dislike', 'commentaires - test diminution de score.', 5, '2003-11-20 19:49:35'),
(21, 1, 2, 'like', 'commentaires - test ajout de commentaire vidéo 1 ', 5, '2003-11-20 19:49:35'),
(22, 1, 2, 'dislike', 'commentaires - test ajout de commentaire vidéo 1 ', 5, '2003-11-20 19:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Horreur'),
(2, 'Humour'),
(3, 'Comédie'),
(4, 'Animation'),
(5, 'Documentaire');

-- --------------------------------------------------------

--
-- Table structure for table `coordonnees`
--

CREATE TABLE `coordonnees` (
  `id` int NOT NULL,
  `auteur_id` int DEFAULT NULL,
  `courriel` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `twitch` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coordonnees`
--

INSERT INTO `coordonnees` (`id`, `auteur_id`, `courriel`, `facebook`, `instagram`, `twitch`, `site_web`) VALUES
(1, 1, 'bobbilly@gmail.com', 'https://facebook.com/bobbilly', 'https://instagram.com/bobbilly', 'https://www.twitch.tv/bobbilly', 'https://bobbilly.com'),
(2, 2, 'mary420@gmail.com', 'https://facebook.com/mjgang', 'https://instagram.com/mjgangsta', 'https://www.twitch.tv/maryj', 'https://420.com'),
(3, 3, 'lnaldo88@gmail.com', 'https://facebook.com/leonaldo', 'https://instagram.com/leodicapo', 'https://www.twitch.tv/leleo', 'https://entreprisesJ.com'),
(4, 4, 'cBang23@gmail.com', 'https://facebook.com/corineb', 'https://instagram.com/cocobango', '', ''),
(5, 5, 'yellowfever@gmail.com', 'https://facebook.com/deanduck', 'https://instagram.com/deanduck', 'https://www.twitch.tv/deantheduck', '');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int NOT NULL,
  `url_img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `categorie_id` int DEFAULT NULL,
  `auteur_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `duree` int NOT NULL,
  `vues` bigint DEFAULT NULL,
  `score` int DEFAULT NULL,
  `closedcaption` tinyint(1) DEFAULT NULL,
  `subtitle` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `url_img`, `nom`, `description`, `code`, `categorie_id`, `auteur_id`, `date`, `duree`, `vues`, `score`, `closedcaption`, `subtitle`) VALUES
(1, 'https://picsum.photos/200/300?random=13', 'vidéo 1', 'description de film 1', 'ABC008', 3, 3, '2003-02-11', 686, 11498, 5900, 1, 1),
(3, 'https://picsum.photos/200/300?random=3', 'vidéo 34', 'description de film 3', 'ABC003', 5, 5, '2020-07-12', 345, 1, 4500, 1, 0),
(6, 'https://picsum.photos/200/300?random=6', 'vidéo 6', 'description de film 6', 'ABC006', 4, 4, '2015-04-04', 180, 330, 2500, 0, 0),
(20, 'https://picsum.photos/200/300?random=9', 'vidéo test', 'description de film 15', 'ABC011', 5, 3, '2003-02-11', 686, 11498, 5800, 1, 0),
(23, 'https://picsum.photos/200/300?random=9', 'vidéo test 2', 'description de film 15', 'ABC0000', 3, 3, '2003-02-11', 686, 11498, 5800, 1, 0),
(25, 'https://picsum.photos/200/300?random=2', 'vidéo test 2', 'description de film 15', 'eeefef', 3, 3, '2003-02-11', 686, 11498, 5800, 1, 0),
(26, 'https://picsum.photos/200/300?random=8', 'test 3', 'Default Description', 'ABC30321', 1, 2, '2024-03-05', 0, 1234, 1234, 0, 0),
(27, 'https://picsum.photos/200/300?random=8', 'test2', 'description', 'YAYYY', 1, 1, '2024-03-05', 1245, 134, 1335, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auteurs`
--
ALTER TABLE `auteurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Indexes for table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coordonnees`
--
ALTER TABLE `coordonnees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auteurs`
--
ALTER TABLE `auteurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coordonnees`
--
ALTER TABLE `coordonnees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`auteur_id`) REFERENCES `auteurs` (`id`);

--
-- Constraints for table `coordonnees`
--
ALTER TABLE `coordonnees`
  ADD CONSTRAINT `coordonnees_ibfk_1` FOREIGN KEY (`auteur_id`) REFERENCES `auteurs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `videos_ibfk_2` FOREIGN KEY (`auteur_id`) REFERENCES `auteurs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

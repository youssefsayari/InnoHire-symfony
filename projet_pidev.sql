-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2024 at 01:47 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projet_pidev`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(11) NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `description_co` varchar(255) NOT NULL,
  `date_co` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `id_publication`, `id_utilisateur`, `description_co`, `date_co`) VALUES
(13, 6, 2, 'aa', '2024-04-28'),
(14, 8, 2, 'bb', '2024-04-28'),
(16, 8, 2, 'kkk', '2024-04-28'),
(19, 6, 2, 'aa', '2024-05-06');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `etablissement`
--

CREATE TABLE `etablissement` (
  `id_etablissement` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `code_etablissement` int(11) NOT NULL,
  `type_etablissement` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `etablissement`
--

INSERT INTO `etablissement` (`id_etablissement`, `nom`, `lieu`, `code_etablissement`, `type_etablissement`, `image`, `id_utilisateur`) VALUES
(125, 'abc', '36.89969-10.19011', 5247, 'ecole', 'esprit.jpg', 2),
(128, 'njik', '36.89439-10.19044', 2548, 'college', 'tekup.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `etablissement_quiz`
--

CREATE TABLE `etablissement_quiz` (
  `id_etablissement_quiz` int(11) NOT NULL,
  `id_etablissement` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `visibilite` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `messagerie`
--

CREATE TABLE `messagerie` (
  `id_message` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `type` varchar(255) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reciver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messagerie`
--

INSERT INTO `messagerie` (`id_message`, `date`, `type`, `contenu`, `sender_id`, `reciver_id`) VALUES
(18, '2024-05-08 15:29:59', 'text', 'yy', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `audience` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `caption` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `totalReactions` int(11) DEFAULT NULL,
  `nbComments` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id_post`, `id_utilisateur`, `audience`, `date`, `caption`, `image`, `totalReactions`, `nbComments`) VALUES
(6, 2, 'PUBLIC', '2024-04-19 12:41:50', 'ttn', 'eve.jpg', 1, 2),
(8, 2, 'PUBLIC', '2024-03-08 15:36:28', 'job', 'ss4.jpg', 1, 2),
(9, 1, 'PUBLIC', '2024-04-12 03:00:55', 'aa', 'eve.jpg', 1, 0),
(11, 2, 'PUBLIC', '2024-04-19 12:39:58', 'aa', 'télécharger.jpg', 0, 0),
(12, 2, 'PUBLIC', '2024-04-28 07:22:58', '&&&&&&&&&&&&', 'ss4.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id_question` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `choix` varchar(255) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `reponse_correcte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id_question`, `question`, `choix`, `id_quiz`, `reponse_correcte`) VALUES
(5, '6*6', '1)20   2)36        3)29', 7, 2),
(6, 'Qu\'elle est le dérivé de 1/x', '1)1/x2   2)-1/x   3)-1/x2', 7, 3),
(8, 'aleh ?', '1)akeka 2)naarech 3)jsp', 8, 1),
(9, 'les nombres paires sont divisbles par 3 ?', '1)non 2)cas par cas   3)oui', 7, 3),
(10, '9*9 ?', '1)10   2)20   3)81', 7, 3),
(11, '19/5 est un nombre entier ?', '1)oui  2)non   3)peut etre', 7, 2),
(12, '4*4 ?', '1) 12  2)16  3)29', 7, 2),
(13, '12/6 ?', '1)2  2)3 3)4', 7, 1),
(14, '19-5*2', '1)9 2)11 3)1', 7, 1),
(15, '11/2', '1)5.5 2)5 3)5.25', 7, 1),
(16, '12-1+9/2', '1)10 2)11 3)12', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id_quiz` int(11) NOT NULL,
  `code_quiz` int(11) NOT NULL,
  `nom_quiz` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `prix_quiz` int(11) NOT NULL,
  `image_quiz` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id_quiz`, `code_quiz`, `nom_quiz`, `description`, `prix_quiz`, `image_quiz`) VALUES
(7, 1, 'Quiz Math', 'Moyen', 25, 'Math.jpg'),
(8, 2, 'Quiz Francais', 'Moyen', 25, 'francais.jpg'),
(11, 11, 'Quiz Math', 'Facile', 10, 'Anglais.jpg'),
(12, 12, 'Quiz Math', 'Moyen', 30, 'Anglais.jpg'),
(13, 10, 'quiz java', 'Facile', 10, 'Anglais.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_utilisateur`
--

CREATE TABLE `quiz_utilisateur` (
  `id_quiz_utilisateur_id` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_utilisateur`
--

INSERT INTO `quiz_utilisateur` (`id_quiz_utilisateur_id`, `id_quiz`, `utilisateur_id`, `score`) VALUES
(83, 7, 2217, 10),
(84, 7, 2217, 7),
(85, 7, 2217, 7),
(86, 7, 2299, 7);

-- --------------------------------------------------------

--
-- Table structure for table `reclamation`
--

CREATE TABLE `reclamation` (
  `id_reclamation` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reclamation`
--

INSERT INTO `reclamation` (`id_reclamation`, `type`, `titre`, `description`, `date`, `status`, `id_post`, `id_utilisateur`) VALUES
(8, 'Spam', 'Spam', 'Spam', '2024-03-08 15:55:07', 0, 8, 2);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `cin` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `OTP` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `cin`, `nom`, `prenom`, `adresse`, `mdp`, `role`, `image`, `OTP`, `status`) VALUES
(1, 112233, 'Amen', 'Allah', 'AmenAllah@gmail.com', 'test', 0, 'user.jpg', 6664, 1),
(2, 3344, 'youssef', 'sayari', 'YoussefSayari@gmail.com', 'test', 1, 'user2.jpg', 9827, 1),
(3, 9, 'ala', 'gafsi', 'ala', 'test', 2, 'user.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur_like`
--

CREATE TABLE `utilisateur_like` (
  `id_utilisateur_like` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilisateur_like`
--

INSERT INTO `utilisateur_like` (`id_utilisateur_like`, `id_post`, `id_utilisateur`) VALUES
(10, 6, 2),
(13, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id_wallet` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `id_etablissement` int(11) NOT NULL,
  `date_c` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id_wallet`, `balance`, `id_etablissement`, `date_c`, `status`) VALUES
(98, 455, 125, '2024-05-06 12:30:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wallet_quiz`
--

CREATE TABLE `wallet_quiz` (
  `id_wallet_quiz` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `id_wallet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wallet_quiz`
--

INSERT INTO `wallet_quiz` (`id_wallet_quiz`, `id_quiz`, `id_wallet`) VALUES
(59, 7, 97),
(60, 7, 98);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `etablissement`
--
ALTER TABLE `etablissement`
  ADD PRIMARY KEY (`id_etablissement`) USING BTREE,
  ADD UNIQUE KEY `code_etablissement` (`code_etablissement`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `etablissement_quiz`
--
ALTER TABLE `etablissement_quiz`
  ADD PRIMARY KEY (`id_etablissement_quiz`),
  ADD KEY `id_quiz` (`id_quiz`),
  ADD KEY `id_etablissement` (`id_etablissement`);

--
-- Indexes for table `messagerie`
--
ALTER TABLE `messagerie`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `reciver_id` (`reciver_id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id_question`),
  ADD KEY `id_quiz` (`id_quiz`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id_quiz`),
  ADD UNIQUE KEY `code_quiz` (`code_quiz`);

--
-- Indexes for table `quiz_utilisateur`
--
ALTER TABLE `quiz_utilisateur`
  ADD PRIMARY KEY (`id_quiz_utilisateur_id`);

--
-- Indexes for table `reclamation`
--
ALTER TABLE `reclamation`
  ADD PRIMARY KEY (`id_reclamation`),
  ADD KEY `id_publication` (`id_post`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`) USING BTREE;

--
-- Indexes for table `utilisateur_like`
--
ALTER TABLE `utilisateur_like`
  ADD PRIMARY KEY (`id_utilisateur_like`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id_wallet`),
  ADD UNIQUE KEY `id_etablissement_2` (`id_etablissement`),
  ADD KEY `id_etablissement` (`id_etablissement`);

--
-- Indexes for table `wallet_quiz`
--
ALTER TABLE `wallet_quiz`
  ADD PRIMARY KEY (`id_wallet_quiz`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `etablissement`
--
ALTER TABLE `etablissement`
  MODIFY `id_etablissement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `etablissement_quiz`
--
ALTER TABLE `etablissement_quiz`
  MODIFY `id_etablissement_quiz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messagerie`
--
ALTER TABLE `messagerie`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id_quiz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quiz_utilisateur`
--
ALTER TABLE `quiz_utilisateur`
  MODIFY `id_quiz_utilisateur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `reclamation`
--
ALTER TABLE `reclamation`
  MODIFY `id_reclamation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2300;

--
-- AUTO_INCREMENT for table `utilisateur_like`
--
ALTER TABLE `utilisateur_like`
  MODIFY `id_utilisateur_like` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id_wallet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9910;

--
-- AUTO_INCREMENT for table `wallet_quiz`
--
ALTER TABLE `wallet_quiz`
  MODIFY `id_wallet_quiz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `etablissement`
--
ALTER TABLE `etablissement`
  ADD CONSTRAINT `etablissement_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `etablissement_quiz`
--
ALTER TABLE `etablissement_quiz`
  ADD CONSTRAINT `etablissement_quiz_ibfk_1` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissement` (`id_etablissement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `etablissement_quiz_ibfk_2` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messagerie`
--
ALTER TABLE `messagerie`
  ADD CONSTRAINT `messagerie_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messagerie_ibfk_2` FOREIGN KEY (`reciver_id`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reclamation`
--
ALTER TABLE `reclamation`
  ADD CONSTRAINT `reclamation_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reclamation_ibfk_3` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `utilisateur_like`
--
ALTER TABLE `utilisateur_like`
  ADD CONSTRAINT `utilisateur_like_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `utilisateur_like_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissement` (`id_etablissement`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

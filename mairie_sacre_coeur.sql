-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 07 avr. 2026 à 01:00
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mairie_sacre_coeur`
--

-- --------------------------------------------------------

--
-- Structure de la table `declarations`
--

DROP TABLE IF EXISTS `declarations`;
CREATE TABLE IF NOT EXISTS `declarations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `prenom_enfant` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_enfant` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_pere` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_mere` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificat_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'En attente',
  `num_registre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_declaration` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `declarations`
--

INSERT INTO `declarations` (`id`, `user_id`, `prenom_enfant`, `nom_enfant`, `date_naissance`, `lieu_naissance`, `sexe`, `nom_pere`, `nom_mere`, `certificat_path`, `statut`, `num_registre`, `created_at`) VALUES
(6, 18, 'ABLAYE', 'NDIAYE', '2000-10-27', '', 'M', 'MODOU NDIAYE', 'FATOU SARR', '1775493337_69d3e0d9c59d5.png', 'Rejeté', '388/2026', '2026-04-06 16:35:37'),
(7, 17, 'Amy', 'SALL', '2000-11-02', '', 'F', 'BIRAME SALL', 'WEDJI MBAYE', '1775497257_69d3f029e1710.png', 'Validé', '254/2026', '2026-04-06 17:40:57');

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `num_registre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `beneficiaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode_reception` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `habitant_id` int DEFAULT NULL,
  `nom_complet_concerne` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'En attente',
  `date_demande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `user_id`, `num_registre`, `beneficiaire`, `email`, `mode_reception`, `habitant_id`, `nom_complet_concerne`, `motif`, `statut`, `date_demande`) VALUES
(6, 18, '2005/2006', 'AMY SALL', 'amy.sall4@unchk.edu.sn', 'WhatsApp', NULL, NULL, NULL, 'Validé', '2026-04-06 16:01:46'),
(7, 18, '2022/0456', 'COUMBA FALL', 'myabirame2000@gmail.com', 'Email', NULL, NULL, NULL, 'Validé', '2026-04-06 16:04:46'),
(8, 18, '223/3456', 'ANAKHAME', 'mamadou82200@gmail.com', 'WhatsApp', NULL, NULL, NULL, 'Validé', '2026-04-06 17:33:18'),
(9, 19, '223/3456', 'ANAKHAME', 'mamadou82200@gmail.com', 'Email', NULL, NULL, NULL, 'Validé', '2026-04-06 17:38:36'),
(10, 17, '1881/2000', 'AMY SALL', 'amy.sall4@unchk.edu.sn', 'Email', NULL, NULL, NULL, 'En attente', '2026-04-06 17:44:43'),
(11, 17, '1881/2000', 'AMY SALL', 'amy.sall4@unchk.edu.sn', 'Email', NULL, NULL, NULL, 'En attente', '2026-04-06 18:14:41'),
(12, 17, '1881/2000', 'AMY SALL', 'amy.sall4@unchk.edu.sn', 'Email', NULL, NULL, NULL, 'En attente', '2026-04-06 18:17:20');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quartier` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('citoyen','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'citoyen',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `telephone`, `quartier`, `email`, `password`, `role`, `date_creation`) VALUES
(3, 'Amy SALL', '775983919', NULL, 'amy.sall4@unchk.edu.sn', '123', 'admin', '2026-04-03 02:24:50'),
(10, 'AMY SALL', '77 598 39 19', NULL, 'evasallah@gmail.com', 'à&éé', 'citoyen', '2026-04-03 15:26:56'),
(17, 'fatou fall', '78 200 32 83', NULL, 'aminah@gmail.com', '$2y$10$rqjGknvwLqyp9YKO2khB4OSlJn6cB4ucWDkhDi2Z7GFLiFckY7o.a', '', '2026-04-03 19:04:12'),
(18, 'Ablaye ndiaye', '77 598 39 19', NULL, 'myabirame2000@gmail.com', '$2y$10$oGAw3xZk5BFQNuRZs/JpiO6wuiq70womhkwYCUutmeP/pkd6qZp0a', '', '2026-04-06 16:01:05'),
(19, 'ANAKHAM', '78 200 32 83', NULL, 'mamadou82200@gmail.com', '$2y$10$crKEg07lcbeDbOYbrLIQZ.VcHisKWQP9mL03fFYSMoEBFZ4wZc/J2', '', '2026-04-06 17:37:36');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `declarations`
--
ALTER TABLE `declarations`
  ADD CONSTRAINT `fk_user_declaration` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD CONSTRAINT `demandes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

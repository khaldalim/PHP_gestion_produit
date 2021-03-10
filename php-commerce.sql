-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 10 mars 2021 à 12:54
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `php-commerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `created_date`, `nom`) VALUES
(1, '2021-03-09 16:00:23', 'categorie 1'),
(2, '2021-03-09 16:00:44', 'categorie 2'),
(3, '2021-03-09 16:00:44', 'categorie 3');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_code` varchar(6) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` text,
  `price` float(8,2) NOT NULL DEFAULT '0.00',
  `category` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `produit_product_code_uindex` (`product_code`),
  KEY `produit_category_id_fk` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `created_date`, `product_code`, `name`, `description`, `price`, `category`) VALUES
(6, '2021-03-09 16:31:25', 'vda125', 'Produit455', 'descgfd\r\n', 150.50, 3),
(16, '2021-03-09 17:16:03', 'aaa123', 'Produit2', 'dsqdsqds', 45.26, 2),
(18, '2021-03-09 17:17:11', 'aaa127', 'Produit2', 'TEST', 10.33, 1),
(19, '2021-03-09 17:37:08', 'aaa126', 'Produit3', 'dsqd', 454.00, 3),
(21, '2021-03-09 17:52:17', 'dgg123', 'Produit2', 'dsqdgfd', 45.00, 1),
(22, '2021-03-10 09:32:58', 'fkd148', 'Produit454', 'TEST', 125.50, 2),
(25, '2021-03-10 11:52:56', 'afa127', 'Produit2', 'hfgg', 56.00, 2),
(27, '2021-03-10 12:01:31', 'laa127', 'Produit2', 'lkjfdfdsf', 54.00, 1),
(29, '2021-03-10 12:02:39', 'aaa115', 'dsqfds', 'dsqfdsfd', 45.00, 1),
(30, '2021-03-10 12:06:21', 'fda123', 'Produit1', 'dsqds', 66.00, 2),
(31, '2021-03-10 12:07:20', 'aaa125', 'gfdgkjhk', 'gfdg', 6546.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'leo@email.fr', 'password');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_category_id_fk` FOREIGN KEY (`category`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

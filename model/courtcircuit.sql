-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 28 Mars 2016 à 19:42
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `courtcircuit`
--

-- --------------------------------------------------------

--
-- Structure de la table `adhesions`
--

CREATE TABLE IF NOT EXISTS `adhesions` (
  `id_a` int(11) NOT NULL AUTO_INCREMENT,
  `id_c` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `date_paiement` date NOT NULL,
  `tarif` varchar(30) NOT NULL,
  PRIMARY KEY (`id_a`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `login` varchar(30) NOT NULL,
  `pw` varchar(30) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `admins`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) DEFAULT NULL,
  `tag` varchar(250) NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `categories`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id_c` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `email` varchar(250) NOT NULL,
  `mdp` varchar(250) NOT NULL,
  `adresse` varchar(250) NOT NULL,
  `code_postal` varchar(5) NOT NULL,
  `ville` varchar(250) NOT NULL,
  `departement` varchar(30) NOT NULL,
  `telephone` varchar(14) NOT NULL,
  PRIMARY KEY (`id_c`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `clients`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE IF NOT EXISTS `commandes` (
  `id_com` int(11) NOT NULL AUTO_INCREMENT,
  `id_pa` int(11) NOT NULL,
  `id_c` int(11) NOT NULL,
  `date_crea` date NOT NULL,
  `date_liv` date NOT NULL,
  `mode_liv` varchar(30) NOT NULL,
  `mode_paiement` varchar(30) NOT NULL,
  `total` decimal(10,3) NOT NULL,
  `commentaire` text NOT NULL,
  `statut` varchar(50) NOT NULL,
  PRIMARY KEY (`id_com`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `lignes_commande`
--

CREATE TABLE IF NOT EXISTS `lignes_commande` (
  `id_lc` int(11) NOT NULL AUTO_INCREMENT,
  `id_pa` int(11) NOT NULL,
  `id_p` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id_lc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Contenu de la table `lignes_commande`
--

INSERT INTO `lignes_commande` (`id_lc`, `id_pa`, `id_p`, `quantite`) VALUES
(24, 18, 1, 5);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE IF NOT EXISTS `paiement` (
  `id_paiement` int(11) NOT NULL AUTO_INCREMENT,
  `id_com` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `date_paiement` date NOT NULL,
  PRIMARY KEY (`id_paiement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

CREATE TABLE IF NOT EXISTS `paniers` (
  `id_pa` int(11) NOT NULL AUTO_INCREMENT,
  `id_c` int(11) NOT NULL,
  `date_crea` date NOT NULL,
  PRIMARY KEY (`id_pa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `paniers`
--

-- --------------------------------------------------------

--
-- Structure de la table `producteurs`
--

CREATE TABLE IF NOT EXISTS `producteurs` (
  `id_pro` int(11) NOT NULL AUTO_INCREMENT,
  `denom` varchar(250) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `adresse` varchar(250) NOT NULL,
  `departement` varchar(30) NOT NULL,
  `telephone` varchar(14) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_pro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `producteurs`
--

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE IF NOT EXISTS `produits` (
  `id_p` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(250) NOT NULL,
  `prix_achat` decimal(10,2) NOT NULL,
  `prix_vente` decimal(10,2) NOT NULL,
  `tva` decimal(10,2) NOT NULL,
  `id_producteur` int(11) NOT NULL,
  `tag_cat` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `img` text NOT NULL,
  PRIMARY KEY (`id_p`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `produits`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

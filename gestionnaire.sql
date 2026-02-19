-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 29 Septembre 2019 à 18:51
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `gestionnaire`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE IF NOT EXISTS `administrateur` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `login_admin` varchar(25) NOT NULL,
  `motdepasse_admin` varchar(25) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `administrateur`
--

INSERT INTO `administrateur` (`id_admin`, `login_admin`, `motdepasse_admin`) VALUES
(1, 'adam', 'adam');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(15) NOT NULL AUTO_INCREMENT,
  `photo_membre` varchar(255) NOT NULL,
  `civilite` varchar(255) NOT NULL,
  `nom_membre` varchar(255) NOT NULL,
  `prenom_membre` varchar(255) NOT NULL,
  `datenaissance` varchar(255) NOT NULL,
  `lieunaissance` varchar(255) NOT NULL,
  `sexe` varchar(255) NOT NULL,
  `contact_membre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `photo_membre`, `civilite`, `nom_membre`, `prenom_membre`, `datenaissance`, `lieunaissance`, `sexe`, `contact_membre`, `email`, `date`) VALUES
(4, 'IMG-20180917-WA0001.jpg', 'Monsieur', 'Ouattara', 'Mohamed', '1980-01-30', 'Bouake', 'Masculin', '89247410', '', '2019-09-14 14:10:56'),
(5, 'IMG_0017.jpg', 'Monsieur', 'Kone', 'Wawolo Koudouss', '1998-05-09', 'Abobo', 'Masculin', '77765371', 'konekoudouss@gmail.com', '2019-09-14 14:11:43'),
(6, 'IMG_0399.jpg', 'Monsieur', 'souare', 'salim sidy', '1990-08-04', 'tengrela', 'Masculin', '79556026', '', '2019-09-14 14:11:12'),
(7, 'akoss.jpeg', 'Monsieur', 'Yao', 'Divine', '1997-10-20', 'abobo', 'Masculin', '45896547', 'divineprocureur@gmail.com', '2019-09-14 14:08:51'),
(8, '2017-06-09-09-07-02-719.jpg', 'Monsieur', 'Goualie', 'poussi adam', '1994-07-22', 'korhogo', 'Masculin', '58280357', 'goualierpoussiadam@gmail.com', '2019-09-15 20:32:17'),
(9, 'oklm.jpg', 'Monsieur', 'Rosky', 'Adam', '1997-10-20', 'tengrela', 'Masculin', '45764799', 'roskyadam7@gmail.com', '2019-09-14 14:21:15'),
(10, 'CYMERA_20170826_151918.jpg', 'Mademoiselle', 'kone', 'zahara', '2015-05-10', 'Adjame', 'Feminin', '45859874', 'zahara@gmail.com', '2019-09-15 20:32:26');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

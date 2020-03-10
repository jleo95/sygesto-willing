-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 10 mars 2020 à 22:45
-- Version du serveur :  5.7.21
-- Version de PHP :  7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `sygesto-1`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories_client`
--

DROP TABLE IF EXISTS `categories_client`;
CREATE TABLE IF NOT EXISTS `categories_client` (
  `catid` int(11) NOT NULL AUTO_INCREMENT,
  `catlibelle` varchar(150) NOT NULL,
  `catremise` int(11) NOT NULL,
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `cliid` int(11) NOT NULL AUTO_INCREMENT,
  `clinom` varchar(255) NOT NULL,
  `cliprenom` varchar(150) NOT NULL,
  `clitelephone` varchar(50) NOT NULL,
  `cliportable` varchar(50) NOT NULL,
  `cliville` varchar(255) NOT NULL,
  `cliremise` int(11) NOT NULL,
  `clicategory` int(11) NOT NULL,
  `clirealiserpar` int(11) NOT NULL,
  `clidatecreation` datetime NOT NULL,
  PRIMARY KEY (`cliid`),
  KEY `clirealiserpar` (`clirealiserpar`),
  KEY `clicategory` (`clicategory`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`cliid`, `clinom`, `cliprenom`, `clitelephone`, `cliportable`, `cliville`, `cliremise`, `clicategory`, `clirealiserpar`, `clidatecreation`) VALUES
(5, 'Du Pond', 'Bois', '698846669', '78/88/9', 'Yaoundé', 0, 0, 1, '2020-02-27 21:41:02');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `cmdid` int(11) NOT NULL AUTO_INCREMENT,
  `cmddescription` text,
  `cmdpaiement` int(11) NOT NULL,
  `cmdclient` int(11) NOT NULL,
  `cmdrealiserpar` int(11) NOT NULL,
  `cmddate` datetime DEFAULT NULL,
  PRIMARY KEY (`cmdid`),
  KEY `fk_commandes_payement1_idx` (`cmdpaiement`),
  KEY `fk_commandes_user1_idx` (`cmdrealiserpar`),
  KEY `cmdclient` (`cmdclient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commande_detail`
--

DROP TABLE IF EXISTS `commande_detail`;
CREATE TABLE IF NOT EXISTS `commande_detail` (
  `produit` int(11) NOT NULL,
  `commande` int(11) NOT NULL,
  `quantite` float DEFAULT NULL,
  `prixtotal` float DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`produit`,`commande`),
  KEY `fk_produits_has_commandes_commandes1_idx` (`commande`),
  KEY `fk_produits_has_commandes_produits1_idx` (`produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `connexions`
--

DROP TABLE IF EXISTS `connexions`;
CREATE TABLE IF NOT EXISTS `connexions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datedebut` datetime NOT NULL,
  `ipsource` varchar(255) NOT NULL,
  `machinesource` varchar(255) NOT NULL,
  `connexion` varchar(255) NOT NULL,
  `datefin` datetime DEFAULT NULL,
  `deconnexion` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `connexions`
--

INSERT INTO `connexions` (`id`, `datedebut`, `ipsource`, `machinesource`, `connexion`, `datefin`, `deconnexion`, `user`) VALUES
(88, '2020-02-26 14:02:03', '127.0.0.1', 'PC-ADMINSYS.servair.doualair.local', 'Connexion réussi', '1970-01-01 01:00:00', 'Session expirée', 1),
(89, '2020-02-27 14:31:51', '127.0.0.1', 'PC-ADMINSYS.servair.doualair.local', 'Connexion réussi', '2020-02-27 14:39:15', 'Session fermée correctement', 1),
(90, '2020-02-27 14:39:20', '127.0.0.1', 'PC-ADMINSYS.servair.doualair.local', 'Connexion réussi', '2020-02-27 16:55:35', 'Session fermée correctement', 1),
(91, '2020-02-27 17:02:09', '127.0.0.1', 'PC-ADMINSYS.servair.doualair.local', 'Connexion réussi', '1970-01-01 01:00:00', 'Session expirée', 1),
(92, '2020-02-27 21:20:17', '127.0.0.1', 'PC-ADMINSYS.servair.doualair.local', 'Connexion réussi', '2020-02-27 21:28:55', 'Session fermée correctement', 1),
(93, '2020-02-27 21:29:05', '127.0.0.1', 'PC-ADMINSYS.servair.doualair.local', 'Connexion réussi', '2020-02-27 21:38:39', 'Session fermée correctement', 1),
(94, '2020-02-27 21:38:43', '127.0.0.1', 'PC-ADMINSYS.servair.doualair.local', 'Session en cours', NULL, '', 1),
(95, '2020-02-28 09:08:20', '127.0.0.1', 'DESKTOP-P44FHNR', 'Session en cours', NULL, '', 1),
(96, '2020-02-28 10:29:22', '127.0.0.1', 'DESKTOP-P44FHNR', 'Connexion réussi', '1970-01-01 01:00:00', 'Session expirée', 1),
(97, '2020-02-28 13:34:45', '127.0.0.1', 'DESKTOP-P44FHNR', 'Connexion réussi', '1970-01-01 01:00:00', 'Session expirée', 1),
(98, '2020-03-01 06:53:01', '127.0.0.1', 'DESKTOP-P44FHNR', 'Session en cours', NULL, '', 1),
(99, '2020-03-02 18:00:16', '127.0.0.1', 'DESKTOP-P44FHNR', 'Connexion réussi', '1970-01-01 01:00:00', 'Session expirée', 1),
(100, '2020-03-03 04:26:16', '127.0.0.1', 'DESKTOP-P44FHNR', 'Connexion réussi', '1970-01-01 01:00:00', 'Session expirée', 1),
(101, '2020-03-03 08:19:37', '127.0.0.1', 'DESKTOP-P44FHNR', 'Connexion réussi', '1970-01-01 01:00:00', 'Session expirée', 1),
(102, '2020-03-05 16:17:51', '127.0.0.1', 'DESKTOP-P44FHNR', 'Session en cours', NULL, '', 1),
(103, '2020-03-07 13:45:47', '127.0.0.1', 'DESKTOP-P44FHNR', 'Session en cours', NULL, '', 1),
(104, '2020-03-10 14:48:25', '127.0.0.1', 'DESKTOP-P44FHNR', 'Session en cours', NULL, '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

DROP TABLE IF EXISTS `employes`;
CREATE TABLE IF NOT EXISTS `employes` (
  `empid` int(11) NOT NULL AUTO_INCREMENT,
  `empnom` varchar(150) DEFAULT NULL,
  `empprenom` varchar(150) DEFAULT NULL,
  `emptelephone` varchar(45) DEFAULT NULL,
  `empportable` varchar(255) DEFAULT NULL,
  `empsexe` int(11) DEFAULT NULL,
  `empresidence` varchar(155) DEFAULT NULL,
  `empcni` varchar(45) DEFAULT NULL,
  `emprealiserpar` int(11) NOT NULL,
  `empdatecreation` datetime NOT NULL,
  PRIMARY KEY (`empid`),
  KEY `emprealiserpar` (`emprealiserpar`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`empid`, `empnom`, `empprenom`, `emptelephone`, `empportable`, `empsexe`, `empresidence`, `empcni`, `emprealiserpar`, `empdatecreation`) VALUES
(1, 'anto leoba', 'jonathan', '698846669', '62508398', 1, 'ecole de poste', '555HU0000', 1, '0000-00-00 00:00:00'),
(4, 'Du Pond', 'Bois', '698846669', NULL, 2, 'Yaoundé', NULL, 1, '2018-11-03 00:14:02'),
(5, 'Kiki', 'debora', '65589874', NULL, 2, 'Yaounde', NULL, 1, '2018-11-03 00:43:42'),
(6, 'cite', 'debora', '66258786', NULL, 2, 'Walia', NULL, 1, '2018-11-03 00:45:22'),
(7, 'wiiliam', 'du Bois', '66258786', '5869847', 1, 'yaounde', NULL, 1, '2018-11-03 00:48:13');

-- --------------------------------------------------------

--
-- Structure de la table `familles`
--

DROP TABLE IF EXISTS `familles`;
CREATE TABLE IF NOT EXISTS `familles` (
  `famid` int(11) NOT NULL AUTO_INCREMENT,
  `famlibelle` varchar(150) NOT NULL,
  `famembalageBlog` varchar(255) NOT NULL,
  `famrealiserpar` int(11) NOT NULL,
  `famdatecreation` datetime NOT NULL,
  PRIMARY KEY (`famid`),
  KEY `famrealiserpar` (`famrealiserpar`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `familles`
--

INSERT INTO `familles` (`famid`, `famlibelle`, `famembalageBlog`, `famrealiserpar`, `famdatecreation`) VALUES
(5, 'Legume', '', 1, '2020-02-27 15:16:20'),
(6, 'Céréale', '', 1, '2020-02-27 15:19:11'),
(7, 'Conserve', '', 1, '2020-02-27 15:20:24');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
CREATE TABLE IF NOT EXISTS `fournisseurs` (
  `fouid` int(11) NOT NULL AUTO_INCREMENT,
  `founom` varchar(150) DEFAULT NULL,
  `fouprenom` varchar(45) DEFAULT NULL,
  `foutelephone` varchar(45) DEFAULT NULL,
  `fouportable` varchar(45) DEFAULT NULL,
  `fouville` varchar(155) DEFAULT NULL,
  `fourealiserpar` int(11) NOT NULL,
  `foudatecreation` datetime NOT NULL,
  PRIMARY KEY (`fouid`),
  KEY `fourealiserpar` (`fourealiserpar`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`fouid`, `founom`, `fouprenom`, `foutelephone`, `fouportable`, `fouville`, `fourealiserpar`, `foudatecreation`) VALUES
(11, 'ABC ', 'Informatique', '002336299858', '65965358648', 'Douala', 1, '2020-02-27 15:10:39'),
(12, 'Tesla', 'Patisserie', '00233696985', '65866835', 'France', 1, '2020-02-27 15:12:15');

-- --------------------------------------------------------

--
-- Structure de la table `groupemenu`
--

DROP TABLE IF EXISTS `groupemenu`;
CREATE TABLE IF NOT EXISTS `groupemenu` (
  `grpid` int(11) NOT NULL AUTO_INCREMENT,
  `grplibelle` varchar(255) NOT NULL,
  `grpicone` text NOT NULL,
  `url` text NOT NULL,
  `grporder` int(11) NOT NULL,
  PRIMARY KEY (`grpid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `groupemenu`
--

INSERT INTO `groupemenu` (`grpid`, `grplibelle`, `grpicone`, `url`, `grporder`) VALUES
(1, 'Produit', '<i class=\"fa fa-book\"></i> ', 'produit', 1),
(2, 'Commande produits', '<i class=\"fa fa-shopping-basket\"></i>', 'achat', 3),
(3, 'Livraison', '<i class=\"fa fa-cart-arrow-down\"></i> ', 'vente', 2),
(4, 'Stock', '<i class=\"fa fa-database\"></i>', 'stock', 4),
(5, 'Statistique', '<i class=\"fa fa-pie-chart\"></i>', 'statistique', 8),
(6, 'Administration', '<i class=\"fa fa-cog\"></i>', 'parametre', 9),
(7, 'Fournisseur', '<i class=\"fa fa-user\"></i>', 'fournisseur', 6),
(8, 'Client', '<i class=\"fa fa-user\"></i>', 'client', 7),
(9, 'Mouvement', '<i class=\"fa fa-car\"></i>', 'mouvement', 5),
(10, 'Offshore', '<i class=\"fa fa-file-o\"></i>', 'offshore', 10),
(11, 'Email', '<i class=\"fa fa-send\"></i>', 'email', 11);

-- --------------------------------------------------------

--
-- Structure de la table `marques`
--

DROP TABLE IF EXISTS `marques`;
CREATE TABLE IF NOT EXISTS `marques` (
  `marid` int(11) NOT NULL AUTO_INCREMENT,
  `marlibelle` varchar(45) DEFAULT NULL,
  `mardescription` text,
  `mardatecreation` datetime NOT NULL,
  `marrealiserpar` int(11) NOT NULL,
  PRIMARY KEY (`marid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `marques`
--

INSERT INTO `marques` (`marid`, `marlibelle`, `mardescription`, `mardatecreation`, `marrealiserpar`) VALUES
(6, 'Grande format', 'une marque top', '2018-11-01 17:55:42', 1),
(7, 'Gramme', 'ddd', '2018-11-01 18:22:05', 1),
(8, 'mg', 'jljkldjsa', '2018-11-01 18:34:11', 1),
(9, 'Gramme', 'fff', '2018-11-01 18:39:45', 1);

-- --------------------------------------------------------

--
-- Structure de la table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `menid` int(11) NOT NULL AUTO_INCREMENT,
  `menlibelle` varchar(255) NOT NULL,
  `menhref` text NOT NULL,
  `menicone` text NOT NULL,
  `mengroupe` int(11) NOT NULL,
  `menrole` int(11) NOT NULL,
  `menverouillage` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`menid`),
  KEY `fk_menus_goupemenu` (`mengroupe`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `menus`
--

INSERT INTO `menus` (`menid`, `menlibelle`, `menhref`, `menicone`, `mengroupe`, `menrole`, `menverouillage`) VALUES
(1, 'Produits', 'produit', '', 1, 106, 0),
(2, 'Unités de mesure', 'unite', '', 1, 108, 0),
(3, 'Marque', 'marque', '', 1, 110, 1),
(4, 'Famille', 'famille', '', 1, 109, 0),
(5, 'Effectuer une livraison', 'vente/add', '', 3, 301, 0),
(6, 'Mes livraisons', 'vente/mesventes', '', 3, 304, 0),
(7, 'Toutes les livraisons', 'vente/all', '', 3, 305, 0),
(8, 'Toutes les commandes', 'commande', '', 2, 203, 0),
(9, 'Passer une commande', 'commande/ajout', '', 2, 201, 0),
(10, 'Livraison effectuées', 'achat/livres', '', 2, 206, 1),
(11, 'Livraison en cours', 'achat/encours', '', 2, 204, 1),
(12, 'Dépense livraison', 'achat/depense', '', 2, 207, 1),
(13, 'Produits les plus commandés', 'stat/produitplus', '', 5, 501, 0),
(14, 'Produits les moins commandés', 'stat/produitmoins', '', 5, 502, 0),
(15, 'Expiration proches', 'stat/expiration', '', 5, 503, 0),
(16, 'Gestion des utlisateur', 'user', '', 6, 601, 0),
(17, 'Attribution des droits', 'user/droit', '', 6, 602, 1),
(18, 'Gestion des employés ', 'employe', '', 6, 604, 0),
(19, 'Infos société ', 'societe', '', 6, 605, 1),
(20, 'Liste des fournisseurs', 'fournisseur', '', 7, 702, 0),
(21, 'Liste des clients', 'client', '', 8, 802, 0),
(22, 'Stock chargement', 'boutique/stock', '', 4, 406, 0),
(23, 'Stock magasin', 'magasin/stock', '', 4, 405, 0),
(24, 'Mouvement chargement', 'mouvement', '', 9, 901, 0),
(25, 'Mouvement magasin', 'magasin/mouvement', '', 9, 902, 0),
(26, 'Achats', 'stat/ventes', '', 5, 504, 0),
(27, 'Paramètres', 'params', '', 6, 608, 0),
(28, 'Faire un backup', 'backup', '', 6, 613, 0),
(29, 'Email', 'email', '', 11, 2000, 0),
(30, 'Offshore', 'offshore', '', 10, 1000, 0);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements`
--

DROP TABLE IF EXISTS `mouvements`;
CREATE TABLE IF NOT EXISTS `mouvements` (
  `mvtid` int(11) NOT NULL AUTO_INCREMENT,
  `mvtdate` datetime DEFAULT NULL,
  `mvtproduit` int(11) NOT NULL,
  `mvtquantite` float NOT NULL,
  `description` text,
  `livree` int(11) DEFAULT '1' COMMENT '0 si on effectue une sortie et 1 si on effetue un entrée',
  `mvtoffshore` int(11) NOT NULL,
  `mvtrealiserpar` int(11) NOT NULL,
  `mvtoffre` int(11) NOT NULL,
  PRIMARY KEY (`mvtid`),
  KEY `fk_mouvements_users1_idx` (`mvtrealiserpar`),
  KEY `fk_mouvement_offshore` (`mvtoffshore`),
  KEY `fk_mvtoffre` (`mvtoffre`) USING BTREE,
  KEY `mvtproduit` (`mvtproduit`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `mouvements`
--

INSERT INTO `mouvements` (`mvtid`, `mvtdate`, `mvtproduit`, `mvtquantite`, `description`, `livree`, `mvtoffshore`, `mvtrealiserpar`, `mvtoffre`) VALUES
(13, '2020-03-26 00:00:00', 67, 100, NULL, 1, 1, 1, 1),
(14, '2020-03-07 00:00:00', 61, 540, NULL, 1, 3, 1, 3),
(15, '2020-03-07 00:00:00', 66, 5000, NULL, 1, 3, 1, 3),
(16, '2020-03-07 00:00:00', 68, 200, NULL, 1, 3, 1, 3),
(17, '2020-03-26 00:00:00', 67, 100, NULL, 1, 1, 1, 1),
(18, '2020-03-07 00:00:00', 61, 540, NULL, 1, 3, 1, 3),
(19, '2020-03-07 00:00:00', 66, 5000, NULL, 1, 3, 1, 3),
(20, '2020-03-07 00:00:00', 68, 200, NULL, 1, 3, 1, 3),
(21, '2020-03-19 00:00:00', 67, 100, NULL, 1, 1, 1, 1),
(22, '2020-03-10 00:00:00', 67, 100, NULL, 1, 1, 1, 1),
(23, '2020-03-12 00:00:00', 67, 100, NULL, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

DROP TABLE IF EXISTS `offres`;
CREATE TABLE IF NOT EXISTS `offres` (
  `offid` int(11) NOT NULL AUTO_INCREMENT,
  `offdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `offdateLivraison` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `offdescription` varchar(45) DEFAULT NULL,
  `offvalidite` int(11) NOT NULL DEFAULT '0',
  `offpaiement` int(11) DEFAULT NULL,
  `offrealiserpar` int(11) NOT NULL,
  `offshore` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`offid`),
  KEY `fk_offre_payement1_idx` (`offpaiement`),
  KEY `fk_offre_user1_idx` (`offrealiserpar`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`offid`, `offdate`, `offdateLivraison`, `offdescription`, `offvalidite`, `offpaiement`, `offrealiserpar`, `offshore`) VALUES
(5, '2020-03-10 22:25:00', '2020-03-10 21:25:28', NULL, 0, 1, 1, 1),
(6, '2020-03-10 22:37:00', '2020-03-10 21:37:03', NULL, 0, 1, 1, 1),
(7, '2020-03-10 22:37:00', '2020-03-10 21:37:57', NULL, 0, 1, 1, 1),
(8, '2020-03-10 22:40:00', '2020-03-10 21:40:55', NULL, 0, 2, 1, 1),
(9, '2020-03-11 00:00:00', '2020-03-10 21:41:37', NULL, 0, 2, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `offre_detail`
--

DROP TABLE IF EXISTS `offre_detail`;
CREATE TABLE IF NOT EXISTS `offre_detail` (
  `produit` int(11) NOT NULL,
  `offre` int(11) NOT NULL,
  `quantite` float DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`produit`,`offre`),
  KEY `fk_produits_has_offres_offres1_idx` (`offre`),
  KEY `fk_produits_has_offres_produits1_idx` (`produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `offre_detail`
--

INSERT INTO `offre_detail` (`produit`, `offre`, `quantite`, `description`) VALUES
(63, 5, 100, NULL),
(63, 6, 10000, NULL),
(63, 8, 14, NULL),
(64, 5, 7000, NULL),
(65, 9, 14777, NULL),
(67, 5, 15, NULL),
(67, 6, 1477, NULL),
(71, 5, 50000, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `offshores`
--

DROP TABLE IF EXISTS `offshores`;
CREATE TABLE IF NOT EXISTS `offshores` (
  `offid` int(11) NOT NULL,
  `offdescription` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offresponsable` int(11) NOT NULL,
  `offdatedebut` datetime NOT NULL,
  `offdatefin` datetime NOT NULL,
  `offclient` int(11) NOT NULL,
  `offrealiserpar` int(11) NOT NULL,
  `offdatecreation` datetime NOT NULL,
  PRIMARY KEY (`offid`),
  KEY `fk_offshore_client` (`offclient`),
  KEY `fk_offshore_realisateur` (`offrealiserpar`),
  KEY `fk_offshore_responsable` (`offresponsable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `offshores`
--

INSERT INTO `offshores` (`offid`, `offdescription`, `offresponsable`, `offdatedebut`, `offdatefin`, `offclient`, `offrealiserpar`, `offdatecreation`) VALUES
(1, 'offshore d\'urgence de la SNH à faire très att', 1, '2020-02-09 00:00:00', '2020-02-29 00:00:00', 1, 1, '2020-02-26 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `paiid` int(11) NOT NULL AUTO_INCREMENT,
  `pailibelle` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`paiid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`paiid`, `pailibelle`) VALUES
(1, 'Orange Money'),
(2, 'Mtn money'),
(3, 'Cash');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `proid` int(11) NOT NULL AUTO_INCREMENT,
  `prodesignation` varchar(150) NOT NULL,
  `proreference` text,
  `datecreation` date DEFAULT NULL,
  `profamille` int(11) NOT NULL,
  `pronbproduitBlog` float DEFAULT NULL COMMENT 'd''un nombre d''un produit que peut contenir son blog, par exemple le nombre de boite de sandrine dans son carton, le nombre de kilo de riz pour un sac de riz, le nombre de bouteille dans une plalette',
  `prounitemessure` int(11) DEFAULT NULL,
  `proseuilalert` int(11) DEFAULT '5',
  `profournisseur` int(11) NOT NULL,
  `prorealiserpar` int(11) NOT NULL,
  `prodatecreation` datetime NOT NULL,
  PRIMARY KEY (`proid`),
  KEY `fk_produits_unitemesure1_idx` (`prounitemessure`),
  KEY `profamille` (`profamille`),
  KEY `prorealiserpar` (`prorealiserpar`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`proid`, `prodesignation`, `proreference`, `datecreation`, `profamille`, `pronbproduitBlog`, `prounitemessure`, `proseuilalert`, `profournisseur`, `prorealiserpar`, `prodatecreation`) VALUES
(60, 'Btle Tangui', NULL, NULL, 7, 1, 2, 2, 12, 1, '2020-02-27 15:21:46'),
(61, 'Btle Tangui', NULL, NULL, 7, 1, 2, 2, 12, 1, '2020-02-27 15:25:43'),
(62, 'Btle Tangui', NULL, NULL, 7, 1, 1, 2, 12, 1, '2020-02-27 15:27:30'),
(63, 'test', NULL, NULL, 5, 1, 2, 15, 11, 1, '2020-02-27 15:39:48'),
(64, 'Btle Tangui', NULL, NULL, 5, 55, 1, 15, 12, 1, '2020-02-27 15:58:25'),
(65, 'Cable Electrique ', NULL, NULL, 5, 25, 1, 15, 11, 1, '2020-02-28 09:43:41'),
(66, 'Cable Electrique ', NULL, NULL, 5, 25, 1, 15, 11, 1, '2020-02-28 09:43:52'),
(67, 'Cable Electrique ', NULL, NULL, 5, 25, 1, 15, 11, 1, '2020-02-28 09:46:50'),
(68, 'Cable Electrique ', NULL, NULL, 6, 25, 2, 15, 11, 1, '2020-02-28 09:47:31'),
(69, 'Cable Electrique ', NULL, NULL, 6, 25, 1, 15, 12, 1, '2020-03-10 16:53:58'),
(70, 'Cable Electrique ', NULL, NULL, 6, 25, 1, 15, 12, 1, '2020-03-10 16:54:20'),
(71, 'Cable Electrique ', NULL, NULL, 6, 25, 1, 15, 12, 1, '2020-03-10 16:55:59');

-- --------------------------------------------------------

--
-- Structure de la table `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `prfid` int(11) NOT NULL AUTO_INCREMENT,
  `prflibelle` varchar(45) DEFAULT NULL,
  `prfdroitacces` text,
  PRIMARY KEY (`prfid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `profile`
--

INSERT INTO `profile` (`prfid`, `prflibelle`, `prfdroitacces`) VALUES
(1, 'Administrateur', '[\"101\",\"102\",\"103\",\"301\",\"302\",\"303\",\"201\",\"202\",\"403\",\"104\",\"105\",\"106\",\"107\",\"304\",\"305\",\"203\",\"204\",\"205\",\"206\",\"405\",\"406\",\"407\",\"501\",\"502\",\"503\",\"601\",\"602\",\"701\",\"702\",\"801\",\"802\",\"108\",\"604\",\"605\",\"109\",\"110\",\"607\",\"207\"]'),
(2, 'Vendeur', '[\"105\"]');

-- --------------------------------------------------------

--
-- Structure de la table `unitemesure`
--

DROP TABLE IF EXISTS `unitemesure`;
CREATE TABLE IF NOT EXISTS `unitemesure` (
  `uniid` int(11) NOT NULL AUTO_INCREMENT,
  `unilibelle` varchar(45) NOT NULL,
  `uniabv` varchar(10) NOT NULL,
  `unirealiserpar` int(11) NOT NULL,
  `unidatecreation` datetime NOT NULL,
  PRIMARY KEY (`uniid`),
  KEY `unirealiserpar` (`unirealiserpar`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `unitemesure`
--

INSERT INTO `unitemesure` (`uniid`, `unilibelle`, `uniabv`, `unirealiserpar`, `unidatecreation`) VALUES
(1, 'Carton', 'Ca', 1, '2020-02-27 14:43:03'),
(2, 'Unité', 'un', 1, '2020-02-27 15:20:47');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `useid` int(11) NOT NULL AUTO_INCREMENT,
  `uselogin` varchar(100) DEFAULT NULL,
  `usemdp` text,
  `usephoto` text,
  `useemploye` int(11) NOT NULL,
  `usedroits` text,
  `usedatecreation` varchar(45) DEFAULT NULL,
  `useprofile` int(11) NOT NULL,
  `useverouiller` int(11) NOT NULL DEFAULT '1',
  `userealiserpar` int(11) NOT NULL,
  PRIMARY KEY (`useid`),
  KEY `fk_user_employers1_idx` (`useemploye`),
  KEY `fk_users_profile1_idx` (`useprofile`),
  KEY `userealiserpar` (`userealiserpar`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`useid`, `uselogin`, `usemdp`, `usephoto`, `useemploye`, `usedroits`, `usedatecreation`, `useprofile`, `useverouiller`, `userealiserpar`) VALUES
(1, 'admin', '0TEwOSGMcMonmt1ba3tqlsiJB40bd001563085fc35165329ea1ff5c5ecbdbbeefBOSwHPfq7LFs3bK4FRc1UeUJq', NULL, 1, '[\"101\",\"102\",\"103\",\"111\",\"112\",\"113\",\"108\",\"301\",\"302\",\"303\",\"201\",\"202\",\"403\",\"404\",\"104\",\"105\",\"106\",\"176\",\"107\",\"304\",\"305\",\"203\",\"204\",\"205\",\"206\",\"405\",\"406\",\"407\",\"501\",\"502\",\"503\",\"504\",\"601\",\"602\",\"701\",\"702\",\"801\",\"802\",\"108\",\"604\",\"605\",\"109\",\"110\",\"607\",\"608\",\"207\",\"703\",\"704\",\"803\",\"804\",\"610\",\"611\",\"612\",\"901\",\"902\",\"613\",\"1000\",\"2000\"]', NULL, 1, 0, 1),
(2, 'willing', 'willing', NULL, 7, NULL, NULL, 1, 1, 1),
(5, 'Du Pond', 'lyelkCYBNYONILJ2YYzinyu6H7110eda4d09e062aa5e4a390b0a572ac0d2c0220ZFvDi8eiRs6GWZVh6npSOO7xS', NULL, 1, '[\"105\"]', '2018-11-14 18:29:31', 2, 1, 1),
(6, 'Du Pond', 'otTFQM66Y7fyZPOz8QgaL3h317110eda4d09e062aa5e4a390b0a572ac0d2c0220WHRvZu4o1WhzIv173LOXoZ3eR', NULL, 1, '[\"101\",\"102\",\"103\",\"301\",\"302\",\"303\",\"201\",\"202\",\"403\",\"104\",\"105\",\"106\",\"107\",\"304\",\"305\",\"203\",\"204\",\"205\",\"206\",\"405\",\"406\",\"407\",\"501\",\"502\",\"503\",\"601\",\"602\",\"701\",\"702\",\"801\",\"802\",\"108\",\"604\",\"605\",\"109\",\"110\",\"607\",\"207\"]', '2020-02-13 15:54:21', 1, 1, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `fk_commandes_payement1` FOREIGN KEY (`cmdpaiement`) REFERENCES `paiements` (`paiid`),
  ADD CONSTRAINT `fk_commandes_user1` FOREIGN KEY (`cmdrealiserpar`) REFERENCES `users` (`useid`);

--
-- Contraintes pour la table `commande_detail`
--
ALTER TABLE `commande_detail`
  ADD CONSTRAINT `fk_produits_has_commandes_commandes1` FOREIGN KEY (`commande`) REFERENCES `commandes` (`cmdid`),
  ADD CONSTRAINT `fk_produits_has_commandes_produits1` FOREIGN KEY (`produit`) REFERENCES `produits` (`proid`);

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `fk_offre_payement1` FOREIGN KEY (`offpaiement`) REFERENCES `paiements` (`paiid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_offre_user1` FOREIGN KEY (`offrealiserpar`) REFERENCES `users` (`useid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `offre_detail`
--
ALTER TABLE `offre_detail`
  ADD CONSTRAINT `fk_produits_has_offres_offres1` FOREIGN KEY (`offre`) REFERENCES `offres` (`offid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produits_has_offres_produits1` FOREIGN KEY (`produit`) REFERENCES `produits` (`proid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_produit_famille1` FOREIGN KEY (`profamille`) REFERENCES `familles` (`famid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produits_unitemesure1` FOREIGN KEY (`prounitemessure`) REFERENCES `unitemesure` (`uniid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_employers1` FOREIGN KEY (`useemploye`) REFERENCES `employes` (`empid`),
  ADD CONSTRAINT `fk_users_profile1` FOREIGN KEY (`useprofile`) REFERENCES `profile` (`prfid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

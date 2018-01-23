-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 18 Juin 2016 à 00:30
-- Version du serveur :  5.6.26
-- Version de PHP :  5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `programdesigner`
--

-- --------------------------------------------------------

--
-- Structure de la table `cdt_cases`
--

CREATE TABLE IF NOT EXISTS `cdt_cases` (
  `id` int(11) unsigned NOT NULL,
  `prescript` int(10) unsigned NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cdtable` int(11) unsigned NOT NULL,
  `devpos` int(10) unsigned NOT NULL,
  `pos` int(10) unsigned NOT NULL,
  `coltag` enum('tblstart','tblend','colstart','colend','cdt','opand','opor','grpstart','grpend') COLLATE utf8_swedish_ci NOT NULL,
  `type` enum('if','else','na','') COLLATE utf8_swedish_ci NOT NULL,
  `letter` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `target` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prefunc` enum('length','absolute','') COLLATE utf8_swedish_ci DEFAULT NULL,
  `link` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `val` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vparse` enum('var','cst','','') COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `cdt_header`
--

CREATE TABLE IF NOT EXISTS `cdt_header` (
  `id` int(11) unsigned NOT NULL,
  `prescript` int(11) NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `inuse` tinyint(1) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `cmd_input_text`
--

CREATE TABLE IF NOT EXISTS `cmd_input_text` (
  `id` int(11) NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prescript` int(10) unsigned NOT NULL,
  `status` enum('use','notuse','deleted','') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('text','select','','') COLLATE utf8_swedish_ci NOT NULL,
  `vnum` int(10) unsigned NOT NULL,
  `title` varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  `dscr` text COLLATE utf8_swedish_ci NOT NULL,
  `nrows` int(10) unsigned NOT NULL,
  `array` tinyint(1) NOT NULL,
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `cmd_output_text`
--

CREATE TABLE IF NOT EXISTS `cmd_output_text` (
  `oid` int(11) unsigned NOT NULL,
  `prescript` int(10) unsigned NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `otnum` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `dscr` text COLLATE utf8_swedish_ci NOT NULL,
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `prescript_header`
--

CREATE TABLE IF NOT EXISTS `prescript_header` (
  `id` int(11) unsigned NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('straight','','','') COLLATE utf8_swedish_ci NOT NULL,
  `version` tinyint(3) unsigned NOT NULL,
  `title` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `dsc` text COLLATE utf8_swedish_ci NOT NULL,
  `author` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `cmpcnt` smallint(5) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `str_output_blocks`
--

CREATE TABLE IF NOT EXISTS `str_output_blocks` (
  `bid` int(11) NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prescript` int(10) unsigned NOT NULL,
  `oid` int(10) unsigned NOT NULL,
  `block` int(10) unsigned NOT NULL,
  `pos` int(11) NOT NULL,
  `target` enum('brick','cdt','stm','') COLLATE utf8_swedish_ci NOT NULL,
  `cdtable` int(10) unsigned NOT NULL,
  `cdtswitch` int(10) unsigned NOT NULL,
  `type` enum('text','var','cdt','') COLLATE utf8_swedish_ci NOT NULL,
  `newline` tinyint(1) NOT NULL,
  `ovar` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_swedish_ci NOT NULL,
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `str_output_call`
--

CREATE TABLE IF NOT EXISTS `str_output_call` (
  `id` int(11) NOT NULL,
  `prescript` int(11) NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `target` enum('text','file','','') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `callid` int(10) unsigned NOT NULL,
  `oid` int(10) unsigned NOT NULL,
  `onum` int(10) unsigned NOT NULL,
  `pos` int(11) NOT NULL,
  `ovnum` int(11) NOT NULL,
  `ovid` int(11) NOT NULL,
  `vparse` enum('var','cst','','') COLLATE utf8_swedish_ci DEFAULT NULL,
  `vtxt` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `srcvid` int(11) NOT NULL,
  `pre` enum('bold','red','green','') COLLATE utf8_swedish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `str_prescript_body`
--

CREATE TABLE IF NOT EXISTS `str_prescript_body` (
  `id` int(11) unsigned NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prescript` int(10) unsigned NOT NULL,
  `devpos` int(10) unsigned NOT NULL,
  `pos` int(11) unsigned NOT NULL,
  `subpos` int(10) unsigned NOT NULL,
  `inpos` int(10) unsigned NOT NULL,
  `insubpos` int(10) unsigned NOT NULL,
  `target` enum('cmd','cdt','substart','subcmd','subcdt','subend','incdt','stm','substm') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cdtable` int(11) unsigned NOT NULL,
  `cdtpos` int(10) unsigned NOT NULL,
  `cdtfunc` enum('header','swstart','cdtstart','swend','cdtend','cmd','substart','subcmd','subend','cdtprestart','cdtpreend','stm','substm','prestm','na') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cdtlevel` int(10) unsigned NOT NULL,
  `cdtlink` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cmd` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cmdlink` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=680 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `str_processing`
--

CREATE TABLE IF NOT EXISTS `str_processing` (
  `id` int(10) unsigned NOT NULL,
  `prescript` int(10) unsigned NOT NULL,
  `user` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `proc` int(11) unsigned NOT NULL,
  `pos` int(11) unsigned NOT NULL,
  `resnum` int(10) unsigned NOT NULL,
  `respos` int(10) unsigned NOT NULL,
  `target` enum('invar','operstart','outvar','instm','opstm','outstm','start','end','operline','operend') COLLATE utf8_swedish_ci NOT NULL,
  `vartxt` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `outnum` int(10) unsigned NOT NULL,
  `varid` int(11) NOT NULL,
  `opertype` enum('count','math') COLLATE utf8_swedish_ci NOT NULL,
  `operheader` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `operfunc` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `operpin` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ctxt` varchar(150) COLLATE utf8_swedish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=337 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` char(23) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `access` tinyint(3) unsigned NOT NULL,
  `email` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `passhash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logintkn` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `resettkn` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `resetexp` int(10) unsigned NOT NULL,
  `logcount` int(10) unsigned NOT NULL,
  `regip` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lastip` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `regtime` int(10) unsigned NOT NULL,
  `lastlogin` int(10) unsigned NOT NULL,
  `paidexp` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


-- --------------------------------------------------------

--
-- Structure de la table `var_flow`
--

CREATE TABLE IF NOT EXISTS `var_flow` (
  `id` int(11) NOT NULL,
  `prescript` int(11) NOT NULL,
  `outvar` int(11) NOT NULL,
  `invar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `var_ref`
--

CREATE TABLE IF NOT EXISTS `var_ref` (
  `id` int(10) unsigned NOT NULL,
  `prescript` int(10) unsigned NOT NULL,
  `prefix` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `backid` int(10) unsigned NOT NULL,
  `forcenum` tinyint(1) NOT NULL DEFAULT '0',
  `maxlen` int(10) unsigned NOT NULL DEFAULT '0',
  `minlen` int(10) unsigned NOT NULL DEFAULT '0',
  `txt` tinytext COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


--
-- Index pour les tables exportées
--

--
-- Index pour la table `cdt_cases`
--
ALTER TABLE `cdt_cases`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cdt_header`
--
ALTER TABLE `cdt_header`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cmd_input_text`
--
ALTER TABLE `cmd_input_text`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cmd_output_text`
--
ALTER TABLE `cmd_output_text`
  ADD PRIMARY KEY (`oid`);

--
-- Index pour la table `prescript_header`
--
ALTER TABLE `prescript_header`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `str_output_blocks`
--
ALTER TABLE `str_output_blocks`
  ADD PRIMARY KEY (`bid`);

--
-- Index pour la table `str_output_call`
--
ALTER TABLE `str_output_call`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `str_prescript_body`
--
ALTER TABLE `str_prescript_body`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `str_processing`
--
ALTER TABLE `str_processing`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `token` (`logintkn`),
  ADD UNIQUE KEY `resettkn` (`resettkn`);

--
-- Index pour la table `var_ref`
--
ALTER TABLE `var_ref`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `cdt_cases`
--
ALTER TABLE `cdt_cases`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=222;
--
-- AUTO_INCREMENT pour la table `cdt_header`
--
ALTER TABLE `cdt_header`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT pour la table `cmd_input_text`
--
ALTER TABLE `cmd_input_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `cmd_output_text`
--
ALTER TABLE `cmd_output_text`
  MODIFY `oid` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT pour la table `prescript_header`
--
ALTER TABLE `prescript_header`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `str_output_blocks`
--
ALTER TABLE `str_output_blocks`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT pour la table `str_output_call`
--
ALTER TABLE `str_output_call`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT pour la table `str_prescript_body`
--
ALTER TABLE `str_prescript_body`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=680;
--
-- AUTO_INCREMENT pour la table `str_processing`
--
ALTER TABLE `str_processing`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=337;
--
-- AUTO_INCREMENT pour la table `var_ref`
--
ALTER TABLE `var_ref`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

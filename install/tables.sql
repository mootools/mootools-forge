-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 21, 2011 at 09:21 PM
-- Server version: 5.1.44
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `forge_mooforge`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `homepageurl` varchar(255) DEFAULT NULL,
  `about` varchar(255) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `twitter_id` varchar(50) DEFAULT NULL,
  `checkhash` varchar(255) DEFAULT NULL,
  `plugins_count` int(11) DEFAULT '0',
  `confirmed_email` tinyint(4) DEFAULT NULL,
  `admin` tinyint(4) DEFAULT NULL,
  `logged_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=990 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin`
--

CREATE TABLE `plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stable_tag_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `description_clean` text,
  `official` tinyint(4) DEFAULT NULL,
  `docsurl` varchar(255) DEFAULT NULL,
  `demourl` varchar(255) DEFAULT NULL,
  `githubuser` varchar(100) DEFAULT NULL,
  `githubrepo` varchar(100) DEFAULT NULL,
  `howtouse` text,
  `comments_count` int(11) DEFAULT '0',
  `downloads_count` int(11) DEFAULT '0',
  `retrieved_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_title` (`title`),
  UNIQUE KEY `unique_slug` (`slug`),
  UNIQUE KEY `unique_repo` (`githubuser`,`githubrepo`),
  KEY `plugin_FI_1` (`author_id`),
  KEY `plugin_FI_2` (`category_id`),
  KEY `plugin_FI_3` (`stable_tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=604 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_dependency`
--

CREATE TABLE `plugin_dependency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) DEFAULT NULL,
  `plugin_tag_id` int(11) DEFAULT NULL,
  `scope` varchar(200) DEFAULT NULL,
  `version` varchar(200) DEFAULT NULL,
  `component` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_dependency_FI_1` (`plugin_id`),
  KEY `plugin_dependency_FI_2` (`plugin_tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17292 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_screenshot`
--

CREATE TABLE `plugin_screenshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `primary` tinyint(4) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_screenshot_FI_1` (`plugin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3134 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_section`
--

CREATE TABLE `plugin_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_section_FI_1` (`plugin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5265 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_tag`
--

CREATE TABLE `plugin_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `downloads_count` int(11) DEFAULT NULL,
  `current` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`plugin_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1600 ;

-- --------------------------------------------------------

--
-- Table structure for table `term`
--

CREATE TABLE `term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `category` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_title` (`title`),
  UNIQUE KEY `unique_slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=758 ;

-- --------------------------------------------------------

--
-- Table structure for table `term_relationship`
--

CREATE TABLE `term_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) DEFAULT NULL,
  `term_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_rel` (`plugin_id`,`term_id`),
  KEY `term_relationship_FI_2` (`term_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10602 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plugin`
--
ALTER TABLE `plugin`
  ADD CONSTRAINT `plugin_FK_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plugin_FK_2` FOREIGN KEY (`category_id`) REFERENCES `term` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plugin_FK_3` FOREIGN KEY (`stable_tag_id`) REFERENCES `plugin_tag` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `plugin_dependency`
--
ALTER TABLE `plugin_dependency`
  ADD CONSTRAINT `plugin_dependency_FK_1` FOREIGN KEY (`plugin_id`) REFERENCES `plugin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plugin_dependency_FK_2` FOREIGN KEY (`plugin_tag_id`) REFERENCES `plugin_tag` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plugin_screenshot`
--
ALTER TABLE `plugin_screenshot`
  ADD CONSTRAINT `plugin_screenshot_FK_1` FOREIGN KEY (`plugin_id`) REFERENCES `plugin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plugin_section`
--
ALTER TABLE `plugin_section`
  ADD CONSTRAINT `plugin_section_FK_1` FOREIGN KEY (`plugin_id`) REFERENCES `plugin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plugin_tag`
--
ALTER TABLE `plugin_tag`
  ADD CONSTRAINT `plugin_tag_FK_1` FOREIGN KEY (`plugin_id`) REFERENCES `plugin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `term_relationship`
--
ALTER TABLE `term_relationship`
  ADD CONSTRAINT `term_relationship_FK_1` FOREIGN KEY (`plugin_id`) REFERENCES `plugin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `term_relationship_FK_2` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`) ON DELETE CASCADE;

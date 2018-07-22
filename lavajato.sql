-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 22-Jul-2018 às 17:27
-- Versão do servidor: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lavajato`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_customers`
--

CREATE TABLE IF NOT EXISTS `cw_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_birth` datetime DEFAULT NULL,
  `sex` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cellphone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(6) DEFAULT NULL,
  `complement` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neighborhood` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cw_customers`
--

INSERT INTO `cw_customers` (`id`, `customer`, `date_birth`, `sex`, `cellphone`, `phone`, `address`, `number`, `complement`, `neighborhood`, `city`, `state`, `zipcode`, `email`) VALUES
(1, 'Anônimo', NULL, NULL, '', '', '', NULL, '', '', '', '', '', ''),
(2, 'Magno', NULL, 'male', '', '', '', NULL, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_employee`
--

CREATE TABLE IF NOT EXISTS `cw_employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `cellphone` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `number` varchar(5) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `complement` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `neighborhood` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `state` varchar(30) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `zipcode` varchar(9) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `date_birth` date NOT NULL,
  `sexo` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(80) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cpf` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `rg` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `salary` float DEFAULT NULL,
  `office` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_expenses`
--

CREATE TABLE IF NOT EXISTS `cw_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `expense` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` float NOT NULL,
  `expiration_date` datetime NOT NULL,
  `payment_status` tinyint(1) DEFAULT '0',
  `payment_date` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_expenses_categories`
--

CREATE TABLE IF NOT EXISTS `cw_expenses_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cw_expenses_categories`
--

INSERT INTO `cw_expenses_categories` (`id`, `category`, `active`) VALUES
(1, 'IMPOSTO', 1),
(2, 'PRODUTOS LIMPEZA', 1),
(3, 'FUNCIONÁRIOS', 1),
(4, 'ALUGUEL', 1),
(5, 'FERRAMENTAS', 1),
(6, 'MAQUINÁRIOS', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_schedules`
--

CREATE TABLE IF NOT EXISTS `cw_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `time` time NOT NULL,
  `customer_id` int(11) NOT NULL,
  `schedules_status` tinyint(1) DEFAULT NULL COMMENT '0 não realizado 1 realizado',
  `cellphone` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `service_id` int(11) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_services`
--

CREATE TABLE IF NOT EXISTS `cw_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `category_id` int(11) NOT NULL,
  `placa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `cellphone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` float NOT NULL DEFAULT '0' COMMENT 'valor',
  `payment_status` tinyint(1) DEFAULT NULL,
  `date` datetime NOT NULL,
  `start_time` time DEFAULT NULL COMMENT 'hora de inicio',
  `end_time` time DEFAULT NULL COMMENT 'hora de termino',
  `status` int(1) DEFAULT NULL COMMENT '0 cancelado 1 agendado 2 em andamento 3 concluído',
  `comments` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_services_categories`
--

CREATE TABLE IF NOT EXISTS `cw_services_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` float NOT NULL DEFAULT '0',
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cw_services_categories`
--

INSERT INTO `cw_services_categories` (`id`, `category`, `value`, `active`) VALUES
(1, 'LAVAGEM DE CARRO', 30, 1),
(2, 'LAVAGEM DE MOTO', 15, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cw_users`
--

CREATE TABLE IF NOT EXISTS `cw_users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_level` int(2) NOT NULL DEFAULT '2',
  `active` tinyint(1) DEFAULT NULL COMMENT '1 ativo 0 inativo',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`email`),
  KEY `user_nivel` (`user_level`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cw_users`
--

INSERT INTO `cw_users` (`id`, `user_img`, `user_name`, `email`, `password`, `token`, `user_level`, `active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Admin', 'admin@email.com', '$2y$10$J0frFuhMc1Z7tljUs9kbgOw/dH2xSZ721I6gI1RCVw5AVXHJdk4vG', NULL, 1, 1, '2018-07-05 00:00:00', '2018-07-05 00:00:00');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `cw_expenses`
--
ALTER TABLE `cw_expenses`
  ADD CONSTRAINT `cw_expenses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `cw_expenses_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `cw_services`
--
ALTER TABLE `cw_services`
  ADD CONSTRAINT `cw_services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `cw_services_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cw_services_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `cw_customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

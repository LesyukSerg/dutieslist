-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2014 at 09:44 AM
-- Server version: 5.5.25-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `list_of_duties`
--

-- --------------------------------------------------------

--
-- Table structure for table `accept_cookies`
--

DROP TABLE IF EXISTS `accept_cookies`;
CREATE TABLE IF NOT EXISTS `accept_cookies` (
  `login` varchar(100) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `randomtext` varchar(255) NOT NULL,
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `duties`
--

DROP TABLE IF EXISTS `duties`;
CREATE TABLE IF NOT EXISTS `duties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `time_to_done` date NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `duties`
--

INSERT INTO `duties` (`id`, `user_id`, `content`, `time_to_done`, `status`) VALUES
(1, 1, 'Справа номер 1 sdfa', '2014-05-01', 'DONE'),
(3, 1, 'Вот и подошла очередная суббота, а это, как вы наверняка успели догадаться, означает, что настало время проникнуться едкими индустриальными битами. Ведь в эфире предпраздничный эфир проекта Terror Night!\r\nСлушайте, наслаждайтесь и не забывайте пританцовывать.', '2014-05-04', 'NEW'),
(4, 1, 'Нaчaльник тюрьмы обрaщaется к смертнику, сидящему нa электрическом стуле:\r\n- Вaше последнее желaние?\r\n- Пожaлуйстa, держите меня зa руку. Мне тaк будет спокойнее. ', '2014-05-03', 'OLD'),
(5, 1, ' Как-то один друг попросил меня взломать пароль от сервера. Бывший админ уехал, не оставив никаких данных. Нашёл программу, которая показывает пароль за звёздочками, установил, включил - нифига! Всё те же звёздочки. Нашёл другую - опять звёздочки... Только спустя сутки я понял, что пароль - 12 звёздочек!', '2014-05-01', 'OLD'),
(6, 3, 'В детстве, наверное, каждый из нас ездил в деревню к бабушке по программе work and travel ) ', '2014-05-05', 'NEW'),
(7, 3, 'Вышел из дома девушки, через пять минут звонок на мобильный:\r\n- Ты не вышел из своего \\"Вконтакте\\", что мне делать?\r\n- Ну так выйди за меня.\r\n- Я согласна! ', '2014-05-08', 'NEW');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`) VALUES
(1, 'Test2222', 'seranatles@gmail.com', '5485e70048b3f0c9b261a564480ff140'),
(2, 'Test3333', 'seranatles@gmail.com', 'ed205d2f028b631a297d08880b6b7b73'),
(3, 'LeSSeR', 'seranatles@gmail.com', '962710b25f18032ba47a950b7b175c03');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

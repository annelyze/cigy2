-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 11, 2017 at 01:25 PM
-- Server version: 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 7.1.8-2+ubuntu16.04.1+deb.sury.org+4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cigy2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cigy_closedwon_actuals`
--

CREATE TABLE `cigy_closedwon_actuals` (
  `team` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `actual` int(11) NOT NULL,
  `actual_ytd` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cigy_closedwon_actuals`
--

INSERT INTO `cigy_closedwon_actuals` (`team`, `month`, `actual`, `actual_ytd`) VALUES
(0, 8, 0, 3321163),
(1, 8, 0, 1285568),
(2, 8, 0, 958356),
(3, 8, 0, 993480);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

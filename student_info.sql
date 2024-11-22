-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 05:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_info`
--

-- --------------------------------------------------------

--
-- Table structure for table `personal_information`
--

CREATE TABLE `personal_information` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `unc_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_information`
--

INSERT INTO `personal_information` (`id`, `first_name`, `last_name`, `middle_name`, `gender`, `unc_id`) VALUES
(1, 'GEOFF NATHAN', 'ABELLANO', 'CERDON', 'MALE', '19-20976'),
(2, 'MATTHEW SACHEVELL', 'ALIMORONG', 'ACHONDO', 'FEMALE', '13-15183'),
(3, 'NOEL JOSH', 'ASANZA', 'REAPOR', 'MALE', '23-03197'),
(4, 'KEITH NATHAN', 'CASTILLO', 'BASBAS', 'MALE', '16-10547'),
(5, 'HUER JANPAULO', 'COLLANTES', 'GAVARRA', 'MALE', '23-55640'),
(6, 'EMILIO', 'DE BELEN', 'LABORDO', 'MALE', '17-05777'),
(7, 'TROY', 'DELOS ANGELES', 'AGRAVANTE', 'MALE', '13-39521'),
(8, 'ETHIEN', 'DIZON', 'PEÑALOSA', 'MALE', '23-54220'),
(9, 'ETHAN MARCO', 'ESCRIBA', 'TUMAMPIL', 'MALE', '20-36185'),
(10, 'DREX', 'FRANCISCO', 'VINCULADO', 'MALE', '20-15092'),
(11, 'MARK JULIUS', 'GRFEAL', 'MIRANDA', 'MALE', '23-27727'),
(12, 'CHRISTINE JOY', 'MORATA', 'PORTUGUEZ', 'FEMALE', '23-54409'),
(13, 'MACK DENVER', 'NIDEA', 'GRIARTE', 'MALE', '23-26252'),
(14, 'REIGN JOSHUA', 'PARNASO', 'LOPEZ', 'MALE', '23-54162'),
(15, 'LEE CIELO', 'ROBLEADO', 'PAYONGAYONG', 'MALE', '12-00736'),
(16, 'TRISHA REIGN', 'SEVA', 'VILLADARES', 'FEMALE', '19-44650'),
(17, 'BENEDICT', 'VILLAVICENCIO', 'LEDESMA', 'MALE', '18-21794');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `personal_information`
--
ALTER TABLE `personal_information`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `personal_information`
--
ALTER TABLE `personal_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

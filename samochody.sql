-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 27 Paź 2023, 11:17
-- Wersja serwera: 10.4.24-MariaDB
-- Wersja PHP: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `samochody`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `samochody`
--

CREATE TABLE `samochody` (
  `id` int(11) DEFAULT NULL,
  `marka` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `rocznik` varchar(50) DEFAULT NULL,
  `cena` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `samochody`
--

INSERT INTO `samochody` (`id`, `marka`, `model`, `rocznik`, `cena`) VALUES
(1, 'Volkswagen', 'Eos', '2011', '30.000$'),
(2, 'Hyundai', 'Azera', '2012', '250.000$'),
(3, 'Porsche', 'Cayman', '2011', '200.000$'),
(4, 'Audi', 'A6', '1999', '200.000$'),
(5, 'Dodge', 'Magnum', '2006', '250.000$'),
(6, 'BMW', 'M3', '1998', '100.000$'),
(7, 'Chevrolet', 'Silverado 1500', '2010', '100.000$'),
(8, 'Nissan', 'Maxima', '2005', '750.000$'),
(9, 'Plymouth', 'Breeze', '1997', '30.000$'),
(10, 'Honda', 'Element', '2005', ''),
(11, 'Chevrolet', 'Silverado 1500', '2011', ''),
(12, 'Acura', 'Legend', '1990', '250.000$'),
(13, 'Volvo', 'XC90', '2003', '100.000$'),
(14, 'Subaru', 'Impreza', '1997', '250.000$'),
(15, 'Subaru', 'Alcyone SVX', '1992', '750.000$'),
(16, 'Volkswagen', 'Passat', '2006', '100.000$'),
(17, 'Chevrolet', 'Camaro', '1982', ''),
(18, 'Nissan', 'Frontier', '2012', ''),
(19, 'Audi', 'S8', '2001', '30.000$'),
(20, 'Toyota', 'RAV4', '2004', '30.000$');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

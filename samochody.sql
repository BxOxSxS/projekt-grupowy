-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 27 Paź 2023, 11:36
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
  `cena` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `samochody`
--

INSERT INTO `samochody` (`id`, `marka`, `model`, `rocznik`, `cena`) VALUES
(1, 'Saturn', 'Sky', '2008', 486312),
(2, 'Volkswagen', 'Type 2', '1988', 756105),
(3, 'Honda', 'Passport', '1995', 135924),
(4, 'Hyundai', 'Sonata', '2008', 92222),
(5, 'Cadillac', 'Eldorado', '1992', 806578),
(6, 'GMC', 'Envoy XL', '2002', 55357),
(7, 'Mitsubishi', 'Pajero', '1999', 255557),
(8, 'Mitsubishi', 'Montero', '1992', 759407),
(9, 'Acura', 'RSX', '2002', 927486),
(10, 'Infiniti', 'IPL G', '2012', 844244),
(11, 'Mercedes-Benz', 'SLR McLaren', '2008', 188874),
(12, 'Mazda', 'Mazda5', '2008', 339706),
(13, 'GMC', 'Yukon', '1993', 251296),
(14, 'Dodge', 'Ram Van 2500', '1995', 614866),
(15, 'Dodge', 'Nitro', '2010', 55781),
(16, 'Ford', 'Escape', '2011', 146658),
(17, 'Mercury', 'Grand Marquis', '1985', 82598),
(18, 'GMC', 'Yukon', '2004', 137497),
(19, 'Ford', 'F-Series', '2011', 831256),
(20, 'Toyota', 'Prius c', '2012', 198069);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

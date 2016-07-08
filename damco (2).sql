-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2016 at 01:38 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `damco`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE `calendars` (
  `calendar_id` int(11) NOT NULL,
  `url` varchar(180) NOT NULL,
  `deskuser_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`calendar_id`, `url`, `deskuser_id`) VALUES
(4, 'https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics', 37),
(5, 'https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics', 38),
(6, 'https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics', 39),
(7, 'https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics', 40),
(8, 'https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics', 39),
(9, 'https://calendar.google.com/calendar/ical/44ird5e4aofb9n5hjqb37ndgdo%40group.calendar.google.com/private-65a9ed8baeca92f0f21a491b24b0f2a5/basic.ics', 41),
(10, 'https://calendar.google.com/calendar/ical/44ird5e4aofb9n5hjqb37ndgdo%40group.calendar.google.com/private-65a9ed8baeca92f0f21a491b24b0f2a5/basic.ics', 42),
(11, 'https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics', 37),
(12, 'https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics', 43),
(13, 'https://outlook.office365.com/owa/calendar/f7b47bbaffdc4a2697d1d3c6393cbb27@hva.nl/59012f236f03454a9b440e99e71c30ef10245871582045065878/calendar.ics', 43),
(14, 'https://outlook.office365.com/owa/calendar/f7b47bbaffdc4a2697d1d3c6393cbb27@hva.nl/59012f236f03454a9b440e99e71c30ef10245871582045065878/calendar.ics', 44),
(15, 'https://calendar.google.com/calendar/ical/fudol43genagnetigaqgg66ef8%40group.calendar.google.com/private-28a8b50c3fd693b7fab316920af57e79/basic.ics', 44);

-- --------------------------------------------------------

--
-- Table structure for table `custom_calendar`
--

CREATE TABLE `custom_calendar` (
  `custom_calendar_id` int(11) NOT NULL,
  `notofficedate` date NOT NULL,
  `guest` int(11) NOT NULL DEFAULT '0',
  `deskuser_id` int(11) NOT NULL,
  `fromextcal` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `custom_calendar`
--

INSERT INTO `custom_calendar` (`custom_calendar_id`, `notofficedate`, `guest`, `deskuser_id`, `fromextcal`) VALUES
(7, '2016-06-13', 0, 41, 0),
(94, '2016-06-21', 0, 38, 1),
(95, '2016-06-21', 0, 39, 1),
(96, '2016-06-21', 0, 40, 1),
(97, '2016-06-21', 0, 41, 1),
(98, '2016-06-21', 0, 42, 1),
(99, '2016-06-29', 0, 43, 0),
(100, '2016-06-28', 0, 43, 0),
(101, '2016-07-06', 0, 43, 0),
(103, '2016-07-15', 0, 43, 0),
(104, '2016-07-05', 0, 43, 0),
(106, '2016-06-21', 0, 43, 0),
(107, '2016-06-24', 0, 37, 1),
(108, '2016-06-24', 0, 38, 1),
(109, '2016-06-24', 0, 39, 1),
(110, '2016-06-24', 0, 40, 1),
(111, '2016-06-24', 0, 43, 1);

-- --------------------------------------------------------

--
-- Table structure for table `deskusers`
--

CREATE TABLE `deskusers` (
  `name` varchar(50) NOT NULL,
  `fixed` tinyint(1) NOT NULL DEFAULT '0',
  `defaultpresent` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(90) NOT NULL,
  `deskuser_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskusers`
--

INSERT INTO `deskusers` (`name`, `fixed`, `defaultpresent`, `email`, `deskuser_id`) VALUES
('Ceren', 1, 1, 'j.r.derijk@quicknet.nl', 37),
('Abed', 0, 1, 'j.r.derijk@quicknet.nl', 38),
('Joice', 0, 1, 'j.r.derijk@quicknet.nl', 39),
('Jarno', 0, 1, 'j.r.derijk@quicknet.nl', 40),
('Helga', 0, 1, 'j.r.derijk@quicknet.nl', 41),
('Richard', 0, 1, 'j.r.derijk@quicknet.nl', 42),
('Ronald', 0, 1, 'j.r.derijk@quicknet.nl', 43),
('Loki', 0, 1, 'j.r.derijk@quicknet.nl', 44);

-- --------------------------------------------------------

--
-- Table structure for table `flexdesk_settings`
--

CREATE TABLE `flexdesk_settings` (
  `settings_id` int(11) NOT NULL,
  `desks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `flexdesk_settings`
--

INSERT INTO `flexdesk_settings` (`settings_id`, `desks`) VALUES
(1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `occupancy_results`
--

CREATE TABLE `occupancy_results` (
  `occupancy_result_id` int(11) NOT NULL,
  `resultdate` date NOT NULL,
  `desk` int(11) NOT NULL,
  `people` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `occupancy_results`
--

INSERT INTO `occupancy_results` (`occupancy_result_id`, `resultdate`, `desk`, `people`) VALUES
(3, '2016-06-19', 49, 8),
(11, '2016-06-20', 49, 7),
(16, '2016-06-21', 49, 1),
(17, '2016-06-22', 49, 8),
(18, '2016-06-23', 8, 6),
(19, '2016-06-24', 10, 3),
(20, '2016-06-25', 8, 6),
(23, '2016-06-26', 8, 6),
(24, '2016-06-27', 8, 6),
(25, '2016-06-28', 8, 6),
(26, '2016-06-29', 49, 8),
(27, '2016-06-30', 49, 8),
(28, '2016-07-01', 49, 8),
(29, '2016-07-02', 49, 8),
(30, '2016-07-03', 49, 8),
(31, '2016-07-04', 49, 8),
(32, '2016-07-05', 49, 8),
(33, '2016-07-06', 49, 8),
(34, '2016-07-07', 49, 8),
(35, '2016-07-08', 49, 8),
(36, '2016-07-09', 49, 8),
(37, '2016-07-10', 49, 8),
(38, '2016-07-11', 49, 8),
(39, '2016-07-12', 49, 8),
(40, '2016-07-13', 49, 8),
(41, '2016-07-14', 49, 8),
(42, '2016-07-15', 49, 8),
(43, '2016-07-16', 49, 8),
(44, '2016-07-17', 49, 8),
(45, '2016-07-18', 49, 8),
(46, '2016-07-19', 49, 8),
(47, '2016-07-20', 49, 8);

-- --------------------------------------------------------

--
-- Table structure for table `searchterms`
--

CREATE TABLE `searchterms` (
  `searchterm_id` int(11) NOT NULL,
  `term` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `searchterms`
--

INSERT INTO `searchterms` (`searchterm_id`, `term`) VALUES
(2, 'vacation'),
(4, 'home'),
(5, 'thuis');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`calendar_id`);

--
-- Indexes for table `custom_calendar`
--
ALTER TABLE `custom_calendar`
  ADD PRIMARY KEY (`custom_calendar_id`);

--
-- Indexes for table `deskusers`
--
ALTER TABLE `deskusers`
  ADD PRIMARY KEY (`deskuser_id`);

--
-- Indexes for table `flexdesk_settings`
--
ALTER TABLE `flexdesk_settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `occupancy_results`
--
ALTER TABLE `occupancy_results`
  ADD PRIMARY KEY (`occupancy_result_id`);

--
-- Indexes for table `searchterms`
--
ALTER TABLE `searchterms`
  ADD PRIMARY KEY (`searchterm_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendars`
--
ALTER TABLE `calendars`
  MODIFY `calendar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `custom_calendar`
--
ALTER TABLE `custom_calendar`
  MODIFY `custom_calendar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `deskusers`
--
ALTER TABLE `deskusers`
  MODIFY `deskuser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `flexdesk_settings`
--
ALTER TABLE `flexdesk_settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `occupancy_results`
--
ALTER TABLE `occupancy_results`
  MODIFY `occupancy_result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `searchterms`
--
ALTER TABLE `searchterms`
  MODIFY `searchterm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

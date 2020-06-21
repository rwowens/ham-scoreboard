SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `callsign` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `lst_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score` int(11) NOT NULL,
  `mult` int(11) NOT NULL,
  `qso` int(11) NOT NULL,
  `arrl_section` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cq_zone` int(11) DEFAULT NULL,
  `club` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bands` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ops` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `xmtrs` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `power` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contest` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scores_current`
--

CREATE TABLE `scores_current` (
  `callsign` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `lst_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score` int(11) NOT NULL,
  `mult` int(11) NOT NULL,
  `qso` int(11) NOT NULL,
  `arrl_section` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cq_zone` int(11) DEFAULT NULL,
  `club` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bands` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ops` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `xmtrs` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `power` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contest` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores_current`
--
ALTER TABLE `scores_current`
  ADD PRIMARY KEY (`callsign`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

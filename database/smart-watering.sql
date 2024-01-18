SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `smart-watering` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `smart-watering`;

CREATE TABLE `conditions` (
  `ID` int(11) NOT NULL,
  `ruleID` int(11) NOT NULL,
  `value` enum('moisture','light','temperature','humidity','time') NOT NULL,
  `conditionexpr` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `config` (
  `name` varchar(16) NOT NULL,
  `value` varchar(100) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `config` (`name`, `value`, `last_update`) VALUES
('poverEnable', 'true', '2024-01-14 21:35:54'),
('poverToken', 'ahy6np3izgdtieto7ta33ajfi4h9zh', '2024-01-14 21:35:54'),
('poverUserKey', '', '2024-01-14 21:35:54');
CREATE TABLE `latest_measurements` (
`timestamp` timestamp
,`moisture` smallint(6)
,`light` smallint(6)
,`temperature` tinyint(4)
,`humidity` tinyint(4)
,`relay` tinyint(1)
);

CREATE TABLE `measurements` (
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `moisture` smallint(6) NOT NULL,
  `light` smallint(6) NOT NULL,
  `temperature` tinyint(4) NOT NULL,
  `humidity` tinyint(4) NOT NULL,
  `relay` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`ID`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$QJYXSmpNAPv0TPalJlXwO.n1oSn43CDvTVFOTYcSICSNy/28VCrLm');
DROP TABLE IF EXISTS `latest_measurements`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `latest_measurements`  AS SELECT `measurements`.`timestamp` AS `timestamp`, `measurements`.`moisture` AS `moisture`, `measurements`.`light` AS `light`, `measurements`.`temperature` AS `temperature`, `measurements`.`humidity` AS `humidity`, `measurements`.`relay` AS `relay` FROM `measurements` ORDER BY `measurements`.`timestamp` DESC LIMIT 0, 1 ;


ALTER TABLE `conditions`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `config`
  ADD PRIMARY KEY (`name`);

ALTER TABLE `measurements`
  ADD PRIMARY KEY (`timestamp`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);


ALTER TABLE `conditions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

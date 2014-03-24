SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `sendgrid_events`
-- ----------------------------
DROP TABLE IF EXISTS `sendgrid_events`;
CREATE TABLE `sendgrid_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(10) unsigned DEFAULT NULL,
  `smtp_id` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `attempt` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `category` longtext,
  `type` varchar(255) DEFAULT NULL,
  `event` varchar(20) DEFAULT NULL,
  `response` longtext,
  `reason` longtext,
  `url` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip` int(10) DEFAULT NULL,
  `raw_data` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
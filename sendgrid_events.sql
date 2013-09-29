SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `sendgrid_events`
-- ----------------------------
DROP TABLE IF EXISTS `sendgrid_events`;
CREATE TABLE `sendgrid_events` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(150) NOT NULL,
  `attempt` int(11) NOT NULL,
  `timestamp` timestamp NULL default NULL,
  `category` varchar(30) NOT NULL,
  `type` varchar(255) NOT NULL,
  `event` varchar(20) NOT NULL,
  `response` longtext NOT NULL,
  `reason` longtext NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
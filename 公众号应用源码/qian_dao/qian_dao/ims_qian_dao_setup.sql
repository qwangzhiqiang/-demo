# Host: xx.toouyi.com  (Version: 5.5.58-log)
# Date: 2017-12-30 20:46:02
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "ims_qian_dao_setup"
#

DROP TABLE IF EXISTS `ims_qian_dao_setup`;
CREATE TABLE `ims_qian_dao_setup` (
  `setupid` int(11) NOT NULL AUTO_INCREMENT,
  `max` int(11) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `bgimg` varchar(255) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `start` varchar(32) DEFAULT NULL,
  `end` varchar(32) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `hasgrant` int(11) DEFAULT '0',
  PRIMARY KEY (`setupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "ims_qian_dao_setup"
#


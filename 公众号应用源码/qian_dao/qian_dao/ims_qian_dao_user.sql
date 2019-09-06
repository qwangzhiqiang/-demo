# Host: xx.toouyi.com  (Version: 5.5.58-log)
# Date: 2017-12-30 20:46:10
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "ims_qian_dao_user"
#

DROP TABLE IF EXISTS `ims_qian_dao_user`;
CREATE TABLE `ims_qian_dao_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) DEFAULT NULL,
  `tel` varchar(16) DEFAULT NULL,
  `openid` varchar(128) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `bonus` double DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "ims_qian_dao_user"
#


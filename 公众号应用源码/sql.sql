
--
-- 表的结构 `ims_hegou_vip_category`
--

CREATE TABLE IF NOT EXISTS `ims_hegou_vip_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//分类自动编号',
  `name` varchar(32) DEFAULT '' COMMENT '//分类名称',
  `sort` int(11) DEFAULT NULL COMMENT '//分类排序',
  `price` decimal(10,0) DEFAULT NULL COMMENT '//栏目售价',
  `hosturl` varchar(255) DEFAULT NULL COMMENT '//主机域名路径',
  `cover` varchar(255) DEFAULT NULL COMMENT '//分类图标',
  `createtime` varchar(16) DEFAULT NULL COMMENT '//创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='//教程分类表'   AUTO_INCREMENT=1 ;


--
-- 表的结构 `ims_hegou_vip_course`
--

CREATE TABLE IF NOT EXISTS `ims_hegou_vip_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//教程编号',
  `title` varchar(255) DEFAULT '' COMMENT '//教程标题',
  `category` int(11) DEFAULT NULL COMMENT '//教程分类',
  `type` int(11) DEFAULT NULL COMMENT '//教程类型',
  `videourl` varchar(255) DEFAULT NULL COMMENT '//教程视频地址',
  `hosturl` varchar(255) DEFAULT NULL COMMENT '//服务器附件地址',
  `cover` varchar(255) DEFAULT NULL COMMENT '//教程封面',
  `content` text COMMENT '//教程正文',
  `filepath` tinytext COMMENT '//教程资源地址',
  `price` varchar(10) DEFAULT '' COMMENT '//单篇售价',
  `createtime` varchar(16) DEFAULT NULL COMMENT '//教程添加时间',
  `ispay` int(11) DEFAULT '0' COMMENT '//是否付费 0免费 1 收费',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='//教程'   AUTO_INCREMENT=1 ;
------------------------------

--
-- 表的结构 `ims_hegou_vip_order`
--

CREATE TABLE IF NOT EXISTS `ims_hegou_vip_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//订单自动编号',
  `ordernum` varchar(128) DEFAULT '' COMMENT '//订单编号',
  `userid` varchar(255) DEFAULT '' COMMENT '//用户编号',
  `nicheng` varchar(128) DEFAULT NULL COMMENT '//用户昵称',
  `type` varchar(255) DEFAULT '' COMMENT '//产品类型',
  `productnum` varchar(128) DEFAULT '' COMMENT '//产品编号',
  `price` decimal(10,0) DEFAULT NULL COMMENT '//金额',
  `status` int(11) DEFAULT NULL COMMENT '//支付状态',
  `createtime` varchar(255) DEFAULT '' COMMENT '//下单时间',
  `updatetime` varchar(255) DEFAULT '' COMMENT '//更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='//订单'  AUTO_INCREMENT=1 ;


--
-- 表的结构 `ims_hegou_vip_users`
--

CREATE TABLE IF NOT EXISTS `ims_hegou_vip_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//会员自动编号',
  `nicheng` varchar(255) DEFAULT '' COMMENT '//会员昵称',
  `openid` varchar(128) DEFAULT '' COMMENT '//openid',
  `unionid` varchar(128) DEFAULT '' COMMENT '//unionid',
  `tel` varchar(16) DEFAULT '' COMMENT '//会员手机',
  `wexin` varchar(128) DEFAULT '' COMMENT '//微信号',
  `createtime` varchar(16) DEFAULT '' COMMENT '//注册时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='//会员表' AUTO_INCREMENT=1 ;

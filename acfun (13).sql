-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-03-02 00:38:17
-- 服务器版本： 5.7.9
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acfun`
--

-- --------------------------------------------------------

--
-- 表的结构 `codepay_order`
--

DROP TABLE IF EXISTS `codepay_order`;
CREATE TABLE IF NOT EXISTS `codepay_order` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pay_id` varchar(50) NOT NULL COMMENT '用户ID或订单ID',
  `money` decimal(6,2) UNSIGNED NOT NULL COMMENT '实际金额',
  `price` decimal(6,2) UNSIGNED NOT NULL COMMENT '原价',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '支付方式',
  `pay_no` varchar(100) NOT NULL COMMENT '流水号',
  `param` varchar(200) DEFAULT NULL COMMENT '自定义参数',
  `pay_time` bigint(11) NOT NULL DEFAULT '0' COMMENT '付款时间',
  `pay_tag` varchar(100) NOT NULL DEFAULT '0' COMMENT '金额的备注',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `creat_time` bigint(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `main` (`pay_id`,`pay_time`,`money`,`type`,`pay_tag`),
  UNIQUE KEY `pay_no` (`pay_no`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用于区分是否已经处理';

--
-- 转存表中的数据 `codepay_order`
--

INSERT INTO `codepay_order` (`id`, `pay_id`, `money`, `price`, `type`, `pay_no`, `param`, `pay_time`, `pay_tag`, `status`, `creat_time`, `up_time`) VALUES
(1, 'admin', '0.01', '0.01', 1, '2018022821001004720260141937', NULL, 1519815149, '0', 2, 1519815157, '2018-02-28 10:52:37'),
(3, 'admin', '0.01', '0.01', 1, '2018022821001004720260256975', NULL, 1519815726, '0', 2, 1519815733, '2018-02-28 11:02:13'),
(5, 'kongdeci', '0.01', '0.01', 1, '2018022821001004720260574995', NULL, 1519816291, '0', 2, 1519816300, '2018-02-28 11:11:40');

-- --------------------------------------------------------

--
-- 表的结构 `codepay_user`
--

DROP TABLE IF EXISTS `codepay_user`;
CREATE TABLE IF NOT EXISTS `codepay_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
  `money` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `vip` int(1) NOT NULL DEFAULT '0' COMMENT '会员开通',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `codepay_user`
--

INSERT INTO `codepay_user` (`id`, `user`, `money`, `vip`, `status`) VALUES
(1, 'admin', '0.02', 0, 0),
(2, 'kongdeci', '0.01', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_access`
--

DROP TABLE IF EXISTS `think_access`;
CREATE TABLE IF NOT EXISTS `think_access` (
  `role_id` smallint(6) UNSIGNED NOT NULL,
  `node_id` smallint(6) UNSIGNED NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- 转存表中的数据 `think_access`
--

INSERT INTO `think_access` (`role_id`, `node_id`, `id`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(1, 5, 5),
(2, 1, 6),
(2, 2, 7),
(2, 3, 8),
(2, 4, 9),
(1, 6, 10),
(1, 7, 11),
(1, 8, 12),
(1, 9, 13),
(7, 1, 38),
(7, 2, 39),
(7, 3, 40),
(7, 4, 41),
(7, 5, 42),
(7, 6, 43),
(7, 7, 44),
(7, 8, 45);

-- --------------------------------------------------------

--
-- 表的结构 `think_category`
--

DROP TABLE IF EXISTS `think_category`;
CREATE TABLE IF NOT EXISTS `think_category` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `bkname` varchar(60) NOT NULL COMMENT '板块名',
  `parentid` int(11) NOT NULL COMMENT '小版块对应的大板块',
  `bkimg` varchar(64) NOT NULL DEFAULT '/picture/1509501632026.gif',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`cid`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='板块';

--
-- 转存表中的数据 `think_category`
--

INSERT INTO `think_category` (`cid`, `bkname`, `parentid`, `bkimg`, `create_time`, `update_time`, `delete_time`) VALUES
(1, '动画', 0, '/picture/1509501632026.gif', NULL, NULL, NULL),
(2, '国产动画', 1, '/picture/1509501632026.gif', NULL, NULL, NULL),
(3, '电影', 0, '/picture/1509501632026.gif', NULL, NULL, NULL),
(4, '国产电影', 3, '/picture/1509501632026.gif', NULL, NULL, NULL),
(5, '欧美电影', 3, '/picture/1509501632026.gif', NULL, NULL, NULL),
(6, '日韩电影', 3, '/picture/1509501632026.gif', NULL, NULL, NULL),
(7, '我最帅', 1, '/uploads/20180206\\0fb13752e8ae997a21277f54539bff64.gif', NULL, 1517918398, 1517918398);

-- --------------------------------------------------------

--
-- 表的结构 `think_danmu`
--

DROP TABLE IF EXISTS `think_danmu`;
CREATE TABLE IF NOT EXISTS `think_danmu` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) DEFAULT NULL COMMENT '发表弹幕的时间',
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `dname` int(11) DEFAULT NULL COMMENT '弹幕发表人',
  `danmu` text NOT NULL COMMENT '弹幕内容',
  `dvideo` int(11) DEFAULT NULL COMMENT '弹幕对应的视频id',
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COMMENT='弹幕';

--
-- 转存表中的数据 `think_danmu`
--

INSERT INTO `think_danmu` (`did`, `create_time`, `update_time`, `delete_time`, `dname`, `danmu`, `dvideo`) VALUES
(1, NULL, NULL, NULL, 1, '4', 6),
(2, 1517797827, 1517797827, NULL, 1, '4', 6),
(3, 1517797855, 1517797855, NULL, 1, '4', 6),
(4, 1517797879, 1517797879, NULL, 1, '4', 6),
(5, 1517797881, 1517797881, NULL, 1, '4', 6),
(6, 1517797897, 1517797897, NULL, 1, '4', 6),
(7, 1517797897, 1517797897, NULL, 1, '4', 6),
(8, 1517797897, 1517797897, NULL, 1, '4', 6),
(9, 1517797897, 1517797897, NULL, 1, '4', 6),
(10, 1517798775, 1517798775, NULL, 1, '4', 6),
(11, 1517798779, 1517798779, NULL, 1, '4', 6),
(12, 1517798789, 1517798789, NULL, 1, '4', 6),
(13, 1517798790, 1517798790, NULL, 1, '4', 6),
(14, 1517798790, 1517798790, NULL, 1, '4', 6),
(15, 1517798790, 1517798790, NULL, 1, '4', 6),
(16, 1517798790, 1517798790, NULL, 1, '4', 6),
(17, 1517798893, 1517798893, NULL, 1, '4', 6),
(18, 1517798896, 1517798896, NULL, 1, '4', 6),
(19, 1517798897, 1517798897, NULL, 1, '4', 6),
(20, 1517798897, 1517798897, NULL, 1, '4', 6),
(21, 1517798897, 1517798897, NULL, 1, '4', 6),
(22, 1517798897, 1517798897, NULL, 1, '4', 6),
(23, 1517798898, 1517798898, NULL, 1, '4', 6),
(24, 1517798898, 1517798898, NULL, 1, '4', 6),
(25, 1517798898, 1517798898, NULL, 1, '4', 6),
(26, 1517798898, 1517798898, NULL, 1, '4', 6),
(27, 1517798898, 1517798898, NULL, 1, '4', 6),
(28, 1517798898, 1517798898, NULL, 1, '4', 6),
(29, 1517798899, 1517798899, NULL, 1, '4', 6),
(30, 1517799108, 1517799108, NULL, 1, '4', 6),
(31, 1517799110, 1517799110, NULL, 1, '4', 6),
(32, 1517799110, 1517799110, NULL, 1, '4', 6),
(33, 1517799110, 1517799110, NULL, 1, '4', 6),
(34, 1517799110, 1517799110, NULL, 1, '4', 6),
(35, 1517799110, 1517799110, NULL, 1, '4', 6),
(36, 1517799111, 1517799111, NULL, 1, '4', 6),
(37, 1517799185, 1517799185, NULL, 1, '4', 6),
(38, 1517799185, 1517799185, NULL, 1, '4', 6),
(39, 1517799186, 1517799186, NULL, 1, '4', 6),
(40, 1517799186, 1517799186, NULL, 1, '4', 6),
(41, 1517799186, 1517799186, NULL, 1, '4', 6),
(42, 1517799220, 1517799220, NULL, 1, '4', 6),
(43, 1517799220, 1517799220, NULL, 1, '4', 6),
(44, 1517799220, 1517799220, NULL, 1, '4', 6),
(45, 1517799220, 1517799220, NULL, 1, '4', 6),
(46, 1517799775, 1517799775, NULL, 1, '4', 6),
(47, 1517799812, 1517799812, NULL, 1, '4', 6),
(48, 1517799834, 1517799834, NULL, 1, '4', 6),
(49, 1517799953, 1517799953, NULL, 1, '4', 6),
(50, 1517799984, 1517799984, NULL, 1, '4', 6),
(51, 1517799984, 1517799984, NULL, 1, '4', 6),
(52, 1517799984, 1517799984, NULL, 1, '4', 6),
(53, 1517800010, 1517800010, NULL, 1, '4', 6),
(54, 1517801434, 1517801434, NULL, 1, '3', 6),
(55, 1517801434, 1517801434, NULL, 1, '2', 6),
(56, 1517801434, 1517801434, NULL, 1, '1', 6),
(57, 1517801434, 1517801434, NULL, 1, ' 国防部会员价', 6),
(58, 1517801945, 1517801945, NULL, 1, '认为对方如果太阳花', 6),
(59, 1517801945, 1517801945, NULL, 1, '认为对方如果太阳花', 6),
(60, 1517801945, 1517801945, NULL, 1, '认为对方如果太阳花', 6),
(61, 1517801946, 1517801946, NULL, 1, '认为对方如果太阳花', 6),
(62, 1517801946, 1517801946, NULL, 1, '认为对方如果太阳花', 6),
(63, 1517801982, 1517801982, NULL, 1, '认为对方如果太阳花', 6),
(64, 1517802153, 1517802153, NULL, 1, '豆腐乳', 6),
(65, 1517802168, 1517802168, NULL, 1, '豆腐乳', 6),
(66, 1517802206, 1517802206, NULL, 1, '电饭锅', 6),
(67, 1517802231, 1517802231, NULL, 1, '地方', 6),
(68, 1517802250, 1517802250, NULL, 1, '', 6),
(69, 1517802253, 1517802253, NULL, 1, '给他', 6),
(70, 1517802263, 1517802263, NULL, 1, '给他', 6),
(71, 1517802276, 1517802276, NULL, 1, '给他', 6),
(72, 1517802276, 1517802276, NULL, 1, '给他', 6),
(73, 1517816069, 1517816069, NULL, 1, 'sdfgt', 6),
(74, 1517877102, 1517877102, NULL, 1, 'fgvyhuj7', 6),
(75, 1517877109, 1517877109, NULL, 1, 'fgvyhuj7', 6),
(76, 1517883796, 1517883796, NULL, 1, 'defrtyuio', 6),
(77, 1517883880, 1517883880, NULL, 1, 'defrtyuio', 6),
(78, 1517883887, 1517883887, NULL, 1, 'defrtyuio', 6),
(79, 1517883887, 1517883887, NULL, 1, 'defrtyuio', 6),
(80, 1517884040, 1517884040, NULL, 1, 'dcfvgb ', 6),
(81, 1517884049, 1517884049, NULL, 1, 'dcfvgb ', 6),
(82, 1517884115, 1517884115, NULL, 1, 'defrtyu', 6),
(83, 1517908442, 1517908442, NULL, 1, 'dfgth', 7),
(84, 1517926825, 1517926825, NULL, 1, 'asdfgh', 7),
(85, 1518137337, 1518137337, NULL, 1, '库名节能环保GV发', 6);

-- --------------------------------------------------------

--
-- 表的结构 `think_node`
--

DROP TABLE IF EXISTS `think_node`;
CREATE TABLE IF NOT EXISTS `think_node` (
  `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '模块名',
  `title` varchar(50) DEFAULT '板块' COMMENT '类型',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '对应的id',
  `url` varchar(64) DEFAULT NULL COMMENT '模块地址',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='节点表';

--
-- 转存表中的数据 `think_node`
--

INSERT INTO `think_node` (`id`, `name`, `title`, `pid`, `url`) VALUES
(1, '板块管理', '板块', 0, '/admin/node/index'),
(2, '用户管理', '板块', 0, '/admin/user/index'),
(3, '黑名单管理', '板块', 0, '/admin/user/heimingdan'),
(4, '视频管理', '板块', 0, '/admin/video/index'),
(5, '视频回收站', '板块', 0, '/admin/video/bycle'),
(6, '评论管理', '板块', 0, '/admin/pinglun/index'),
(7, '弹幕管理', '板块', 0, '/admin/menu/index'),
(8, '管理员管理', '板块', 0, '/admin/role/index'),
(9, '角色管理', '板块', 0, '/admin/jiaose/index');

-- --------------------------------------------------------

--
-- 表的结构 `think_pinglun`
--

DROP TABLE IF EXISTS `think_pinglun`;
CREATE TABLE IF NOT EXISTS `think_pinglun` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上一级评论的id，一级就是0',
  `nickname` varchar(100) NOT NULL COMMENT '评论人的username',
  `head_pic` varchar(400) NOT NULL COMMENT '评论人头像',
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '评论时间',
  `delete_time` int(11) DEFAULT NULL,
  `content` text NOT NULL COMMENT '评论内容',
  `pvideo` int(11) NOT NULL COMMENT '评论的视频',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_pinglun`
--

INSERT INTO `think_pinglun` (`pid`, `parent_id`, `nickname`, `head_pic`, `update_time`, `create_time`, `delete_time`, `content`, `pvideo`) VALUES
(1, 0, 'ewrfgth', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', NULL, 1517383106, NULL, 'fgh 6ythg hbyth  tgb ', 6),
(2, 1, 'werft', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', NULL, 1517383106, NULL, 'erty5uio', 6),
(3, 2, 'frgthyju', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', NULL, 1517383106, NULL, 'frgtrhjik', 6),
(4, 0, '染发膏他要回家', ' /uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', NULL, 1517383106, NULL, '个体户已经', 6),
(5, 6, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1517906692, 1517906692, NULL, 'dfghnj', 6),
(12, 0, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1517907495, 1517907495, NULL, 'fvgbhnjmk,l.;', 6),
(13, 12, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1517907650, 1517907650, NULL, 'frgthyjukilo', 6),
(14, 0, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1517908457, 1517908457, NULL, 'wsedfrgthjukil', 7),
(15, 14, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1517979127, 1517979127, NULL, '七色风', 7),
(16, 15, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1517979139, 1517979139, NULL, '的飞规划局快乐', 7),
(17, 0, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1517979149, 1517979149, NULL, '的法人个体户就', 7),
(18, 0, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1518137629, 1518137629, NULL, '撒向对方过后', 6),
(19, 0, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1518137644, 1518137644, NULL, '放入共同好友', 2),
(20, 19, 'admin', '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', 1518137653, 1518137653, NULL, '认同感和语句', 2);

-- --------------------------------------------------------

--
-- 表的结构 `think_role`
--

DROP TABLE IF EXISTS `think_role`;
CREATE TABLE IF NOT EXISTS `think_role` (
  `rid` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rolename` varchar(20) NOT NULL COMMENT '管理员的等级名称',
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rid`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='用户组表';

--
-- 转存表中的数据 `think_role`
--

INSERT INTO `think_role` (`rid`, `rolename`, `pid`, `status`, `remark`) VALUES
(1, '超级超级管理员', NULL, NULL, NULL),
(2, '管理员', NULL, NULL, NULL),
(7, '小管理', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `think_role_user`
--

DROP TABLE IF EXISTS `think_role_user`;
CREATE TABLE IF NOT EXISTS `think_role_user` (
  `role_id` mediumint(9) UNSIGNED DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  `ruid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ruid`),
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='用户与用户组关系表';

--
-- 转存表中的数据 `think_role_user`
--

INSERT INTO `think_role_user` (`role_id`, `user_id`, `ruid`) VALUES
(1, '1', 1),
(2, '9', 12),
(2, '10', 13);

-- --------------------------------------------------------

--
-- 表的结构 `think_user`
--

DROP TABLE IF EXISTS `think_user`;
CREATE TABLE IF NOT EXISTS `think_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `sex` int(3) NOT NULL DEFAULT '-1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `touxiang` varchar(128) NOT NULL DEFAULT '/picture/avatar.jpg',
  `email` varchar(64) DEFAULT NULL,
  `shengfen` text,
  `dishi` text,
  `xingming` varchar(11) DEFAULT NULL,
  `shoucang` varchar(255) DEFAULT NULL COMMENT '用户收藏的视频',
  `quanxian` int(2) NOT NULL DEFAULT '0' COMMENT '后台权限是否开启',
  `huiyuan` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_user`
--

INSERT INTO `think_user` (`uid`, `username`, `password`, `phone`, `sex`, `create_time`, `update_time`, `delete_time`, `touxiang`, `email`, `shengfen`, `dishi`, `xingming`, `shoucang`, `quanxian`, `huiyuan`) VALUES
(1, 'admin', 'NjU0MzIx', '17777787662', 1, 1517383106, 1517926746, NULL, '/uploads/20180202\\d98bd942873b9d32fdbce5bc692d4600.jpg', NULL, '北京', '海淀区', '孔德赐', '1,3,2', 1, 1),
(2, 'kongdeci', 'NjU0MzIx', '17777787662', -1, 1517463381, 1517463381, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 1),
(3, 'admin1', 'NjU0MzIx', '17777787662', -1, 1517753902, 1517753902, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0),
(4, 'aaa', 'NjU0MzIx', '17777787662', -1, 1518070431, 1518070431, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0),
(5, 'admin2', 'NjU0MzIx', '17777787662', -1, 1518072743, 1518072743, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0),
(6, 'kong', 'NjU0MzIx', '17777787662', -1, 1518072759, 1518072759, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0),
(7, 'admin3', 'NjU0MzIx', '17777787662', -1, 1518073085, 1518073085, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0),
(8, 'admin4', 'NjU0MzIx', '17777787662', -1, 1518073099, 1518073099, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0),
(9, 'admin5', 'NjU0MzIx', '17777787662', -1, 1518073114, 1518073114, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 1, 0),
(10, 'kongde', 'NjU0MzIx', '17777787662', -1, 1518081489, 1518081489, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0),
(11, '111111', 'MTExMTEx', '17777787662', -1, 1519631916, 1519631916, NULL, '/picture/avatar.jpg', NULL, NULL, NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_video`
--

DROP TABLE IF EXISTS `think_video`;
CREATE TABLE IF NOT EXISTS `think_video` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `vpath` varchar(64) NOT NULL COMMENT '路径',
  `title` text COMMENT '视频名字',
  `content` text COMMENT '简介',
  `vimg` varchar(64) NOT NULL COMMENT '视频封面',
  `fenqu` int(11) NOT NULL COMMENT '视频分类',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '伪删除',
  `uname` int(11) NOT NULL,
  `lunbo` int(2) NOT NULL DEFAULT '0' COMMENT '轮播图',
  `bofangliang` int(11) NOT NULL DEFAULT '0' COMMENT '视频播放次数',
  `danmu` int(11) NOT NULL DEFAULT '0' COMMENT '弹幕数量',
  `pinglun` int(11) NOT NULL DEFAULT '0' COMMENT '评论量',
  `dabankuai` int(11) DEFAULT NULL COMMENT '视频分类的一级分区',
  `tuijian` int(2) NOT NULL DEFAULT '0' COMMENT '是否是推荐视频',
  `shoucangliang` int(11) NOT NULL DEFAULT '0' COMMENT '收藏量',
  `huiyuan` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_video`
--

INSERT INTO `think_video` (`vid`, `vpath`, `title`, `content`, `vimg`, `fenqu`, `create_time`, `update_time`, `delete_time`, `uname`, `lunbo`, `bofangliang`, `danmu`, `pinglun`, `dabankuai`, `tuijian`, `shoucangliang`, `huiyuan`) VALUES
(1, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '测试', '啦啦啦', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 6, 1517386045, 1517386045, NULL, 1, 0, 23, 0, 0, 3, 0, 0, 1),
(2, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '1234再来一次', 'papapap', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 2, 1517386188, 1517386188, NULL, 1, 0, 15, 0, 0, 1, 1, 0, 1),
(3, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', 'lalal', 'lili', '/uploads/20180202\\7f7328a111dbc30f3934b8f2de317ea5.gif', 4, 1517452162, 1517452162, NULL, 1, 0, 1, 0, 0, 3, 0, 0, 0),
(4, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '123', '1234', '/uploads/20180202\\7f7328a111dbc30f3934b8f2de317ea5.gif', 2, 1517470694, 1517470694, NULL, 1, 0, 1, 0, 0, 1, 1, 0, 1),
(5, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '1234', '12345', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 2, 1517470797, 1517470797, NULL, 1, 0, 5, 0, 0, 1, 0, 0, 0),
(6, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', 'wwert', 'qwer', '/uploads/20180202\\7f7328a111dbc30f3934b8f2de317ea5.gif', 2, 1517471403, 1517471403, NULL, 1, 1, 318, 26, 0, 1, 1, 75, 0),
(7, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', 'sdf', '234', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 2, 1517472598, 1517472598, NULL, 1, 1, 14, 2, 0, 1, 0, 4, 0),
(8, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '哈哈哈哈', '啦啦啦', '/uploads/20180202\\7f7328a111dbc30f3934b8f2de317ea5.gif', 5, 1517485545, 1517485545, NULL, 1, 1, 17, 0, 0, 3, 1, 0, 0),
(9, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '我最爱的人是你', '你爱的人却不是我', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 6, 1517485593, 1517485593, NULL, 1, 1, 12, 0, 0, 3, 0, 0, 0),
(10, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '我是标题', '我是简介', '/uploads/20180202\\7f7328a111dbc30f3934b8f2de317ea5.gif', 5, 1517485658, 1517485658, NULL, 1, 1, 5, 0, 0, 3, 0, 5, 0),
(11, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '滴滴滴滴滴滴', '啦啦啦', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 4, 1517558304, 1517558304, NULL, 1, 0, 1, 0, 0, 3, 0, 0, 0),
(12, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '123', '123', '/uploads/20180202\\7f7328a111dbc30f3934b8f2de317ea5.gif', 2, 1517559491, 1517559491, NULL, 1, 0, 0, 0, 0, 1, 0, 0, 0),
(13, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '大的视频', 'QWERTY', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 2, 1517559547, 1517559547, NULL, 1, 0, 0, 0, 0, 1, 0, 0, 0),
(14, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '12345', '123', '/uploads/20180202\\7f7328a111dbc30f3934b8f2de317ea5.gif', 4, 1517578254, 1517578254, NULL, 1, 0, 0, 0, 0, 3, 0, 0, 0),
(15, '/uploads/20180202\\0066d1e1435ad95bbaca3d1bb7ea8db3.mp4', '标题好难写啊！！！！！！！！', '爱德华接口里面客观环境可能里面', '/uploads/20180202\\80faf779f10bc570984516b2727572dd.gif', 2, 1517578337, 1517578337, NULL, 1, 0, 0, 0, 0, 1, 0, 0, 0),
(16, '/uploads/20180207\\8badf691a5e8e8b7d94ead89426eca22.mp4', '好好【good】', '哈哈', '/uploads/20180207\\f239c360d4e76a1556c6e9e14bee8cbf.gif', 4, 1517965578, 1517965578, NULL, 1, 0, 2, 0, 0, 3, 0, 0, 0),
(17, '/uploads/20180226\\c600e9ca34249efffe39e2a96c99d181.mp4', '111', '1111111', '/uploads/20180226\\cf6f5db50503ea59bb0798c77c2d6d07.gif', 6, 1519634030, 1519634030, NULL, 1, 0, 0, 0, 0, 3, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

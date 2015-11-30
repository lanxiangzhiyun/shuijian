-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 11 月 27 日 08:08
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `slackck`
--

-- --------------------------------------------------------

--
-- 表的结构 `mr_admin`
--

CREATE TABLE IF NOT EXISTS `mr_admin` (
  `admin_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `admin_username` varchar(20) NOT NULL COMMENT '管理员用户名',
  `admin_pwd` varchar(70) NOT NULL COMMENT '管理员密码',
  `admin_email` varchar(30) NOT NULL COMMENT '邮箱',
  `admin_realname` varchar(10) DEFAULT NULL COMMENT '真实姓名',
  `admin_tel` varchar(30) DEFAULT NULL COMMENT '电话号码',
  `admin_hits` int(8) NOT NULL DEFAULT '1' COMMENT '登陆次数',
  `admin_ip` varchar(20) DEFAULT NULL COMMENT 'IP地址',
  `admin_addtime` int(11) NOT NULL COMMENT '添加时间',
  `admin_mdemail` varchar(50) NOT NULL DEFAULT '0' COMMENT '传递修改密码参数加密',
  `admin_open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '审核状态',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `mr_admin`
--

INSERT INTO `mr_admin` (`admin_id`, `admin_username`, `admin_pwd`, `admin_email`, `admin_realname`, `admin_tel`, `admin_hits`, `admin_ip`, `admin_addtime`, `admin_mdemail`, `admin_open`) VALUES
(1, 'slackck', 'e10adc3949ba59abbe56e057f20f883e', '876902658@qq.com', '沈利', '15959715286', 45, '127.0.0.1', 112222233, '3fb2389e0e156b63d97272834132843c', 1),
(2, 'xiaoli', 'e10adc3949ba59abbe56e057f20f883e', '274476526@qq.com', '李', '15959715286', 2, '127.0.0.1', 1446683501, '', 1),
(4, '1231', '202cb962ac59075b964b07152d234b70', '987@qq.com', NULL, NULL, 1, NULL, 1448528811, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `mr_auth_group`
--

CREATE TABLE IF NOT EXISTS `mr_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `mr_auth_group`
--

INSERT INTO `mr_auth_group` (`id`, `title`, `status`, `rules`, `addtime`) VALUES
(1, '超级管理员', 1, '1,2,6,10,19,26,3,4,5,15,16,17,18,7,8,11,12,25,9,13,14,22,23,24,27,29,30,28,31,32,33,34,35,36,', 1212451252),
(2, '管理员', 1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,', 1212451252);

-- --------------------------------------------------------

--
-- 表的结构 `mr_auth_group_access`
--

CREATE TABLE IF NOT EXISTS `mr_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mr_auth_group_access`
--

INSERT INTO `mr_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `mr_auth_rule`
--

CREATE TABLE IF NOT EXISTS `mr_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `css` varchar(20) NOT NULL COMMENT '样式',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` tinyint(5) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- 转存表中的数据 `mr_auth_rule`
--

INSERT INTO `mr_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `addtime`) VALUES
(1, 'Sys', '系统设置', 1, 1, 'fa-tachometer', '', 0, 0, 1446535750),
(2, 'Sys/sys', '系统参数设置', 1, 1, '', '', 1, 0, 1446535789),
(3, 'Sys/database', '数据备份/下载', 1, 1, '', '', 1, 0, 1446535805),
(4, 'Sys/import', '数据库下载', 1, 1, '', '', 3, 10, 1446535750),
(5, 'Sys/database', '数据库备份', 1, 1, '', '', 3, 1, 1446535834),
(6, 'Sys/sys', '站点设置', 1, 1, '', '', 2, 0, 1446535843),
(7, 'News', '文章管理', 1, 1, 'fa-folder', '', 0, 0, 1446535875),
(8, 'News/news_list', '文章操作', 1, 1, '', '', 7, 0, 1446535875),
(9, 'News/news_column', '栏目管理', 1, 1, '', '', 7, 0, 1446535750),
(10, 'Sys/wesys', '微信设置', 1, 1, '', '', 2, 0, 1446535750),
(11, 'News/news_list', '文章列表', 1, 1, '', '', 8, 0, 1446535750),
(12, 'News/news_listadd', '添加文章', 1, 1, '', '', 8, 0, 1446535750),
(13, 'News/news_column', '栏目列表', 1, 1, '', '', 9, 0, 1446535750),
(14, 'News/news_columnadd', '添加栏目', 1, 1, '', '', 9, 0, 1446535750),
(15, 'Sys/admin_list', '管理员管理', 1, 1, '', '', 1, 0, 1446535750),
(16, 'Sys/admin_list', '管理员列表', 1, 1, '', '', 15, 0, 1446535750),
(17, 'Sys/admin_group', '用户组列表', 1, 1, '', '', 15, 0, 1446535750),
(18, 'Sys/admin_rule', '权限管理', 1, 1, '', '', 15, 1, 1446535750),
(19, 'Sys/emailsys', '邮件设置', 1, 1, '', '', 2, 0, 1446535750),
(22, 'Help', '帮助中心', 1, 1, 'fa-cogs', '', 0, 500, 1446711367),
(23, 'Help/soft', '软件下载', 1, 1, '', '', 22, 50, 1446711421),
(24, 'Help/soft', '软件下载', 1, 1, '', '', 23, 50, 1446711448),
(25, 'News/news_back', '回收站', 1, 1, '', '', 8, 50, 1447039310),
(26, 'Sys/pay', '支付配置', 1, 1, '', '', 2, 50, 1447231369),
(27, 'Member', '会员管理', 1, 1, 'fa-users', '', 0, 50, 1447231507),
(28, 'Plug', '插件功能', 1, 1, 'fa-plug', '', 0, 400, 1447231590),
(29, 'Member/member_list', '会员列表', 1, 1, '', '', 27, 10, 1447232085),
(30, 'Member/member_score', '积分管理', 1, 1, '', '', 27, 20, 1447232133),
(31, 'Plug/plug_link_list', '友情链接', 1, 1, '', '', 28, 50, 1447232183),
(32, 'Plug/plug_link_list', '链接列表', 1, 1, '', '', 31, 50, 1447639935),
(33, 'Plug/plug_link_add', '添加链接', 1, 0, '', '', 31, 50, 1447639950),
(34, 'Plug/plug_linktype_list', '所属栏目', 1, 1, '', '', 31, 50, 1447640839),
(35, 'We', '微信基本功能', 1, 1, 'fa-weixin', '', 0, 150, 1447842435),
(36, 'We/we_menu', '自定义菜单', 1, 1, '', '', 35, 50, 1447842477),
(37, 'Member/member_list', '会员列表', 1, 1, '', '', 29, 50, 1448413219),
(38, 'Member/member_group', '会员组', 1, 1, '', '', 29, 50, 1448413248),
(39, 'We/we_menu', '自定义菜单', 1, 1, '', '', 36, 50, 1448501584);

-- --------------------------------------------------------

--
-- 表的结构 `mr_column`
--

CREATE TABLE IF NOT EXISTS `mr_column` (
  `c_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `column_name` varchar(36) NOT NULL,
  `column_enname` varchar(50) NOT NULL COMMENT '英文标题',
  `column_type` int(8) NOT NULL,
  `column_leftid` tinyint(3) NOT NULL,
  `column_address` varchar(70) NOT NULL,
  `column_open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否开启',
  `column_order` int(7) NOT NULL,
  `column_title` varchar(36) NOT NULL,
  `column_key` varchar(200) NOT NULL,
  `column_des` varchar(200) NOT NULL,
  `column_content` longtext NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `mr_column`
--

INSERT INTO `mr_column` (`c_id`, `column_name`, `column_enname`, `column_type`, `column_leftid`, `column_address`, `column_open`, `column_order`, `column_title`, `column_key`, `column_des`, `column_content`) VALUES
(1, '新闻中心', '', 1, 0, '', 1, 50, '', '', '', ''),
(2, '国内新闻', '', 3, 1, '', 1, 50, '', '', '', ''),
(3, '国外新闻', '', 1, 1, '', 1, 50, '', '', '', ''),
(4, '国外社会新闻', '', 3, 3, '', 1, 50, '', '', '', ''),
(5, '国外美女新闻', '', 3, 3, '', 1, 50, '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `mr_diyflag`
--

CREATE TABLE IF NOT EXISTS `mr_diyflag` (
  `diyflag_id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `diyflag_value` char(2) NOT NULL COMMENT '值',
  `diyflag_name` char(10) NOT NULL COMMENT '名称',
  `diyflag_order` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`diyflag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `mr_diyflag`
--

INSERT INTO `mr_diyflag` (`diyflag_id`, `diyflag_value`, `diyflag_name`, `diyflag_order`) VALUES
(1, 'h', '头条', 10),
(2, 'c', '推荐', 20),
(3, 'f', '幻灯', 30),
(4, 'a', '特荐', 40),
(5, 's', '滚动', 50),
(6, 'p', '图片', 60),
(7, 'j', '跳转', 70),
(8, 'd', '原创', 80);

-- --------------------------------------------------------

--
-- 表的结构 `mr_member_group`
--

CREATE TABLE IF NOT EXISTS `mr_member_group` (
  `member_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员组ID',
  `member_group_name` varchar(30) NOT NULL COMMENT '会员组名',
  `member_group_open` int(11) NOT NULL DEFAULT '0' COMMENT '会员组是否开放',
  `member_group_toplimit` int(11) NOT NULL DEFAULT '0' COMMENT '积分上限',
  `member_group_bomlimit` int(11) NOT NULL DEFAULT '0' COMMENT '积分下限',
  `member_group_order` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`member_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `mr_member_group`
--

INSERT INTO `mr_member_group` (`member_group_id`, `member_group_name`, `member_group_open`, `member_group_toplimit`, `member_group_bomlimit`, `member_group_order`) VALUES
(1, '普通汇演', 1, 50, 0, 11),
(3, '1', 1, 1, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `mr_member_list`
--

CREATE TABLE IF NOT EXISTS `mr_member_list` (
  `member_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_list_username` varchar(30) NOT NULL COMMENT '登录用户名',
  `member_list_pwd` char(32) NOT NULL COMMENT '登录密码',
  `member_list_groupid` tinyint(2) NOT NULL COMMENT '所属会员组',
  `member_list_petname` varchar(30) NOT NULL COMMENT '昵称',
  `member_list_headpic` varchar(100) NOT NULL COMMENT '会员头像路径',
  `member_list_tel` int(11) NOT NULL COMMENT '手机',
  `member_list_email` varchar(50) NOT NULL COMMENT '邮箱',
  `member_list_open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `member_list_addtime` int(11) NOT NULL COMMENT '添加时间戳',
  PRIMARY KEY (`member_list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mr_member_lvl`
--

CREATE TABLE IF NOT EXISTS `mr_member_lvl` (
  `member_lvl_id` tinyint(3) NOT NULL AUTO_INCREMENT COMMENT '等级ID',
  `member_lvl_name` varchar(20) NOT NULL COMMENT '等级名称',
  PRIMARY KEY (`member_lvl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mr_news`
--

CREATE TABLE IF NOT EXISTS `mr_news` (
  `n_id` int(36) NOT NULL AUTO_INCREMENT,
  `news_title` varchar(255) NOT NULL COMMENT '文章标题',
  `news_titleshort` varchar(100) DEFAULT NULL COMMENT '简短标题',
  `news_columnid` int(11) NOT NULL,
  `news_columnviceid` int(11) DEFAULT NULL COMMENT '副栏目ID',
  `news_key` varchar(100) NOT NULL COMMENT '文章关键字',
  `news_tag` varchar(50) NOT NULL DEFAULT '' COMMENT '文章标签',
  `news_auto` varchar(20) NOT NULL DEFAULT '' COMMENT '作者',
  `news_source` varchar(20) NOT NULL DEFAULT '未知' COMMENT '来源',
  `news_content` longtext NOT NULL COMMENT '新闻内容',
  `news_scontent` varchar(100) NOT NULL DEFAULT '',
  `news_hits` int(11) NOT NULL DEFAULT '200' COMMENT '点击率',
  `news_img` varchar(100) NOT NULL DEFAULT '' COMMENT '大图地址',
  `news_time` int(11) NOT NULL,
  `news_flag` set('h','c','f','a','s','p','j','d') NOT NULL DEFAULT '' COMMENT '文章属性',
  `news_zaddress` varchar(100) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `news_back` int(2) NOT NULL DEFAULT '0' COMMENT '是否为回收站',
  `news_open` varchar(2) DEFAULT '0' COMMENT '0代表审核不通过 1代表审核通过',
  `news_lvtype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '旅游类型1=行程 2=攻略',
  PRIMARY KEY (`n_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `mr_news`
--

INSERT INTO `mr_news` (`n_id`, `news_title`, `news_titleshort`, `news_columnid`, `news_columnviceid`, `news_key`, `news_tag`, `news_auto`, `news_source`, `news_content`, `news_scontent`, `news_hits`, `news_img`, `news_time`, `news_flag`, `news_zaddress`, `news_back`, `news_open`, `news_lvtype`) VALUES
(1, '23423423424', '', 4, 0, '123', '123', '沈利', '龙岩网', '&lt;p&gt;123&lt;/p&gt;', '123', 200, '', 1448591401, 'f,a', '', 0, '1', 0),
(2, '2102', '', 4, 0, '113', '113', '沈利', '龙岩网', '', '', 200, '', 1448596448, 'a', '', 0, '', 0),
(3, '1231231231', '', 4, NULL, '23123', '23123', '沈利', '龙岩网', '&lt;p&gt;23123&lt;/p&gt;', '1231123', 200, '', 1448596582, 'f,a', '', 0, '1', 0);

-- --------------------------------------------------------

--
-- 表的结构 `mr_plug_link`
--

CREATE TABLE IF NOT EXISTS `mr_plug_link` (
  `plug_link_id` int(5) NOT NULL AUTO_INCREMENT,
  `plug_link_name` varchar(50) NOT NULL COMMENT '链接名称',
  `plug_link_url` varchar(200) NOT NULL COMMENT '链接URL',
  `plug_link_typeid` tinyint(4) DEFAULT NULL COMMENT '所属栏目ID',
  `plug_link_qq` varchar(20) NOT NULL COMMENT '联系QQ',
  `plug_link_order` varchar(10) NOT NULL DEFAULT '50' COMMENT '排序',
  `plug_link_addtime` int(11) NOT NULL COMMENT '添加时间',
  `plug_link_open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0禁用1启用',
  PRIMARY KEY (`plug_link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `mr_plug_link`
--

INSERT INTO `mr_plug_link` (`plug_link_id`, `plug_link_name`, `plug_link_url`, `plug_link_typeid`, `plug_link_qq`, `plug_link_order`, `plug_link_addtime`, `plug_link_open`) VALUES
(2, '龙岩网', 'http://www.baidu.com/', 11, '876902658', '50', 1447840512, 0),
(3, '新罗网', 'http://lyft.364000.com/', 1, '876902658', '50', 1447841390, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mr_plug_linktype`
--

CREATE TABLE IF NOT EXISTS `mr_plug_linktype` (
  `plug_linktype_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `plug_linktype_name` varchar(30) NOT NULL COMMENT '所属栏目名称',
  `plug_linktype_order` varchar(10) NOT NULL COMMENT '排序',
  PRIMARY KEY (`plug_linktype_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `mr_plug_linktype`
--

INSERT INTO `mr_plug_linktype` (`plug_linktype_id`, `plug_linktype_name`, `plug_linktype_order`) VALUES
(1, '首页', '1'),
(11, '新闻中心', '50');

-- --------------------------------------------------------

--
-- 表的结构 `mr_sys`
--

CREATE TABLE IF NOT EXISTS `mr_sys` (
  `sys_id` int(36) unsigned NOT NULL,
  `sys_name` char(36) NOT NULL DEFAULT '',
  `sys_url` varchar(36) NOT NULL DEFAULT '',
  `sys_title` varchar(200) NOT NULL,
  `sys_key` varchar(200) NOT NULL,
  `sys_des` varchar(200) NOT NULL,
  `email_open` tinyint(2) NOT NULL COMMENT '邮箱发送是否开启',
  `email_name` varchar(50) NOT NULL COMMENT '发送邮箱账号',
  `email_pwd` varchar(50) NOT NULL COMMENT '发送邮箱密码',
  `email_smtpname` varchar(50) NOT NULL COMMENT 'smtp服务器账号',
  `email_emname` varchar(30) NOT NULL COMMENT '邮件名',
  `email_rename` varchar(30) NOT NULL COMMENT '发件人姓名',
  `wesys_name` varchar(30) NOT NULL COMMENT '微信名称',
  `wesys_number` varchar(30) NOT NULL COMMENT '微信号',
  `wesys_id` varchar(20) NOT NULL COMMENT '微信原始ID',
  `wesys_type` tinyint(2) NOT NULL COMMENT '1=订阅号 2=服务号',
  `wesys_appid` varchar(30) NOT NULL COMMENT '微信appid',
  `wesys_appsecret` varchar(50) NOT NULL COMMENT '微信AppSecret',
  `wesys_token` varchar(50) NOT NULL COMMENT '微信token',
  PRIMARY KEY (`sys_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mr_sys`
--

INSERT INTO `mr_sys` (`sys_id`, `sys_name`, `sys_url`, `sys_title`, `sys_key`, `sys_des`, `email_open`, `email_name`, `email_pwd`, `email_smtpname`, `email_emname`, `email_rename`, `wesys_name`, `wesys_number`, `wesys_id`, `wesys_type`, `wesys_appid`, `wesys_appsecret`, `wesys_token`) VALUES
(1, '龙岩网', 'http://www.364000.com', '龙岩网', '龙岩网', '龙岩网', 1, '876902658@qq.com', 'shenli', 'smtp.qq.com', '876902658', '网站管理员', '护士之家', 'lyzj99', 'dkfje11235_b', 2, 'wxcdfe67b2a574efc7', '6d8a090aedb68c0fdd4f3b42c1717eb7', 'shenli');

-- --------------------------------------------------------

--
-- 表的结构 `mr_we_menu`
--

CREATE TABLE IF NOT EXISTS `mr_we_menu` (
  `we_menu_id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `we_menu_name` varchar(20) NOT NULL COMMENT '菜单名称',
  `we_menu_leftid` int(11) NOT NULL COMMENT '菜单上级ID',
  `we_menu_type` tinyint(2) NOT NULL COMMENT '菜单类型',
  `we_menu_typeval` varchar(200) NOT NULL COMMENT '菜单类型值',
  `we_menu_open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `we_menu_order` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`we_menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `mr_we_menu`
--

INSERT INTO `mr_we_menu` (`we_menu_id`, `we_menu_name`, `we_menu_leftid`, `we_menu_type`, `we_menu_typeval`, `we_menu_open`, `we_menu_order`) VALUES
(1, '关于我们', 0, 1, '', 1, 50),
(2, '公司简介', 1, 2, 'http://www.thinkphp.cn/', 1, 50);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

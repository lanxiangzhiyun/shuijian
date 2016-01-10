/*
Navicat MySQL Data Transfer

Source Server         : localhost:3306
Source Server Version : 50045
Source Host           : localhost:3306
Source Database       : shuijian

Target Server Type    : MYSQL
Target Server Version : 50045
File Encoding         : 65001

Date: 2016-01-10 23:35:46
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `shuijian_activity`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_activity`;
CREATE TABLE `shuijian_activity` (
  `activity_id` mediumint(8) NOT NULL auto_increment,
  `pPolice_id` mediumint(8) NOT NULL,
  `activityName` varchar(60) NOT NULL,
  `activityDesc` text NOT NULL,
  `goods_id` mediumint(8) NOT NULL,
  `cut_price` decimal(10,2) NOT NULL default '0.00',
  `startTime` varchar(50) NOT NULL,
  `startHours` varchar(10) NOT NULL,
  `startMinute` varchar(10) NOT NULL,
  `endTime` varchar(50) NOT NULL,
  `endHours` varchar(10) NOT NULL,
  `endMinute` varchar(10) NOT NULL,
  `startTimeslotHour` varchar(10) NOT NULL,
  `startTimeslotMinute` varchar(10) NOT NULL,
  `endTimeslotHour` varchar(10) NOT NULL,
  `endTimeslotMinute` varchar(10) NOT NULL,
  `is_onactive` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_activity
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_activityrules`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_activityrules`;
CREATE TABLE `shuijian_activityrules` (
  `pPolice_id` mediumint(8) NOT NULL auto_increment,
  `pPoliciesName` varchar(60) NOT NULL,
  `pType` tinyint(2) default NULL,
  `pChannelsCode` varchar(60) NOT NULL,
  `pPermissions` tinyint(2) NOT NULL,
  PRIMARY KEY  (`pPolice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_activityrules
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_admin`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_admin`;
CREATE TABLE `shuijian_admin` (
  `admin_id` tinyint(4) NOT NULL auto_increment,
  `admin_username` varchar(20) NOT NULL,
  `admin_pwd` varchar(70) NOT NULL,
  `admin_email` varchar(30) NOT NULL,
  `admin_realname` varchar(10) NOT NULL,
  `admin_tel` varchar(30) NOT NULL,
  `admin_ip` varchar(20) NOT NULL,
  `admin_addtime` int(11) NOT NULL,
  `admin_lasttime` int(11) NOT NULL,
  `admin_open` tinyint(2) NOT NULL,
  `admin_actionList` text NOT NULL,
  PRIMARY KEY  (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_admin
-- ----------------------------
INSERT INTO shuijian_admin VALUES ('1', 'admin', 'd41d8cd98f00b204e9800998ecf8427e', 'f@126.com', 'admin', '123456', '127.0.0.1', '112222233', '1452411610', '1', '2,3,5');

-- ----------------------------
-- Table structure for `shuijian_article`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_article`;
CREATE TABLE `shuijian_article` (
  `article_id` mediumint(8) NOT NULL auto_increment,
  `title` varchar(150) NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(30) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `is_open` tinyint(1) NOT NULL default '1',
  `add_time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_article
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_authmenu`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_authmenu`;
CREATE TABLE `shuijian_authmenu` (
  `auth_id` tinyint(3) NOT NULL auto_increment,
  `menu_id` tinyint(3) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_authmenu
-- ----------------------------
INSERT INTO shuijian_authmenu VALUES ('1', '2', '1', '1');

-- ----------------------------
-- Table structure for `shuijian_baseproduct`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_baseproduct`;
CREATE TABLE `shuijian_baseproduct` (
  `bproduct_id` mediumint(8) NOT NULL auto_increment,
  `bproduct_name` varchar(120) NOT NULL,
  `cat_id` smallint(5) NOT NULL,
  `type_id` smallint(5) NOT NULL,
  `is_on_sale` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`bproduct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_baseproduct
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_baseproductcost`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_baseproductcost`;
CREATE TABLE `shuijian_baseproductcost` (
  `bcost_id` mediumint(8) NOT NULL auto_increment,
  `bproduct_id` mediumint(8) NOT NULL,
  `stock_number` tinyint(3) NOT NULL,
  `order_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`bcost_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_baseproductcost
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_baseproductstock`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_baseproductstock`;
CREATE TABLE `shuijian_baseproductstock` (
  `bstock_id` mediumint(8) NOT NULL auto_increment,
  `bproduct_id` mediumint(8) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL default '0.00',
  `order_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`bstock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_baseproductstock
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_cart`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_cart`;
CREATE TABLE `shuijian_cart` (
  `rec_id` mediumint(8) NOT NULL auto_increment,
  `user_id` mediumint(8) NOT NULL default '0',
  `session_id` char(32) NOT NULL,
  `goods_id` mediumint(8) NOT NULL default '0',
  `goods_sn` varchar(60) NOT NULL,
  `goods_name` varchar(120) NOT NULL,
  `goods_price` decimal(10,2) NOT NULL default '0.00',
  `goods_number` smallint(5) NOT NULL default '0',
  `goods_attr` text NOT NULL,
  PRIMARY KEY  (`rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_city`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_city`;
CREATE TABLE `shuijian_city` (
  `city_id` tinyint(3) NOT NULL auto_increment,
  `ctity_code` varchar(20) NOT NULL,
  `city_name` varchar(120) NOT NULL,
  PRIMARY KEY  (`city_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_city
-- ----------------------------
INSERT INTO shuijian_city VALUES ('1', 'sh', '上海');
INSERT INTO shuijian_city VALUES ('2', 'gz', '广州');

-- ----------------------------
-- Table structure for `shuijian_deliver`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_deliver`;
CREATE TABLE `shuijian_deliver` (
  `deliver_id` tinyint(3) NOT NULL auto_increment,
  `deliver_name` varchar(120) NOT NULL,
  `deliver_mobile` varchar(20) NOT NULL,
  `deliver_type` tinyint(3) NOT NULL,
  `deliver_shop` int(3) NOT NULL,
  PRIMARY KEY  (`deliver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_deliver
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_goods`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_goods`;
CREATE TABLE `shuijian_goods` (
  `goods_id` mediumint(8) NOT NULL auto_increment,
  `product_id` smallint(5) NOT NULL,
  `shop_id` tinyint(3) NOT NULL,
  `is_on_sale` tinyint(1) NOT NULL default '1',
  `shop_price` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_goodsstock`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_goodsstock`;
CREATE TABLE `shuijian_goodsstock` (
  `stock_id` mediumint(8) NOT NULL auto_increment,
  `goods_id` mediumint(8) NOT NULL,
  `stock_number` tinyint(3) NOT NULL,
  `warning_number` tinyint(3) NOT NULL,
  `goodsStockStatus` tinyint(1) NOT NULL,
  `isSendMessage` tinyint(1) NOT NULL,
  `alarmUserName` varchar(155) NOT NULL,
  `alarmMobile` varchar(30) NOT NULL,
  PRIMARY KEY  (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_goodsstock
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_goods_category`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_goods_category`;
CREATE TABLE `shuijian_goods_category` (
  `cat_id` smallint(5) NOT NULL auto_increment,
  `cat_name` varchar(90) NOT NULL,
  `sort_order` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_goods_category
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_goods_type`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_goods_type`;
CREATE TABLE `shuijian_goods_type` (
  `type_id` smallint(5) NOT NULL auto_increment,
  `type_name` varchar(90) NOT NULL,
  `type_code` varchar(90) NOT NULL,
  PRIMARY KEY  (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_goods_type
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_menu`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_menu`;
CREATE TABLE `shuijian_menu` (
  `menu_id` tinyint(3) NOT NULL auto_increment,
  `menu_name` varchar(120) NOT NULL,
  `menu_level` tinyint(3) NOT NULL default '1',
  `menu_url` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  `menu_pid` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_menu
-- ----------------------------
INSERT INTO shuijian_menu VALUES ('1', '系统设置', '1', '', '1', '0');
INSERT INTO shuijian_menu VALUES ('2', '管理员设置', '2', '/iadmin.php/Sys/admin_list', '1', '1');
INSERT INTO shuijian_menu VALUES ('3', '菜单管理', '2', '/iadmin.php/Menu/menu_list', '1', '1');
INSERT INTO shuijian_menu VALUES ('4', 'test', '2', 'test', '1', '1');
INSERT INTO shuijian_menu VALUES ('5', '店铺管理', '2', '/iadmin.php/Shop/shop_list', '1', '1');

-- ----------------------------
-- Table structure for `shuijian_order_action`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_order_action`;
CREATE TABLE `shuijian_order_action` (
  `action_id` mediumint(8) NOT NULL auto_increment,
  `order_id` mediumint(8) NOT NULL default '0',
  `action_user` varchar(30) NOT NULL,
  `order_status` tinyint(1) NOT NULL default '0',
  `pay_status` tinyint(1) NOT NULL,
  `action_note` varchar(255) NOT NULL,
  `log_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_order_action
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_order_info`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_order_info`;
CREATE TABLE `shuijian_order_info` (
  `order_id` mediumint(8) NOT NULL auto_increment,
  `order_sn` varchar(20) NOT NULL,
  `user_id` mediumint(8) NOT NULL default '0',
  `order_status` tinyint(1) NOT NULL default '0',
  `pay_status` tinyint(1) NOT NULL default '0',
  `consignee` varchar(60) NOT NULL,
  `country` smallint(5) NOT NULL default '0',
  `province` smallint(5) NOT NULL default '0',
  `city` smallint(5) NOT NULL default '0',
  `district` smallint(5) NOT NULL default '0',
  `address` varchar(255) NOT NULL,
  `mobile` varchar(60) NOT NULL,
  `postscript` varchar(255) NOT NULL,
  `shipping_area` varchar(120) NOT NULL,
  `shipping_name` varchar(120) NOT NULL,
  `pay_id` tinyint(3) NOT NULL default '0',
  `pay_name` varchar(120) NOT NULL,
  `inv_payee` varchar(120) NOT NULL,
  `goods_amount` decimal(10,2) NOT NULL default '0.00',
  `shipping_fee` decimal(10,2) NOT NULL default '0.00',
  `coupon_fee` decimal(10,2) NOT NULL default '0.00',
  `money_paid` decimal(10,2) NOT NULL default '0.00',
  `surplus` decimal(10,2) NOT NULL default '0.00',
  `order_amount` decimal(10,2) NOT NULL default '0.00',
  `add_time` int(10) NOT NULL default '0',
  `confirm_time` int(10) NOT NULL default '0',
  `pay_time` int(10) NOT NULL default '0',
  `shipping_time` int(10) NOT NULL default '0',
  `to_buyer` varchar(255) NOT NULL,
  `pay_note` varchar(255) NOT NULL,
  PRIMARY KEY  (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_order_info
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_payment`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_payment`;
CREATE TABLE `shuijian_payment` (
  `pay_id` tinyint(3) NOT NULL auto_increment,
  `pay_code` varchar(20) NOT NULL,
  `pay_name` varchar(120) NOT NULL,
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) NOT NULL default '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_payment
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_products`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_products`;
CREATE TABLE `shuijian_products` (
  `product_id` mediumint(8) NOT NULL auto_increment,
  `cat_id` smallint(5) NOT NULL,
  `product_name` varchar(120) NOT NULL,
  `product_weight` decimal(10,3) NOT NULL default '0.000',
  `product_price` decimal(10,2) NOT NULL default '0.00',
  `product_brief` varchar(255) NOT NULL,
  `product_desc` text NOT NULL,
  `product_thumb` varchar(255) NOT NULL,
  `product_img` varchar(255) NOT NULL,
  `original_img` varchar(255) NOT NULL,
  `is_on_sale` tinyint(1) NOT NULL default '1',
  `add_time` int(10) NOT NULL default '0',
  `is_delete` tinyint(1) NOT NULL default '0',
  `last_update` int(10) NOT NULL default '0',
  `baseProduct_items` varchar(255) NOT NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_products
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_shop`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_shop`;
CREATE TABLE `shuijian_shop` (
  `shop_id` tinyint(3) NOT NULL default '0',
  `shop_city` tinyint(3) NOT NULL,
  `shop_name` varchar(120) NOT NULL,
  `low_price` decimal(10,2) NOT NULL default '0.00',
  `ship_cost` decimal(10,2) NOT NULL default '0.00',
  `shop_address` varchar(120) NOT NULL,
  `shop_type` tinyint(3) NOT NULL default '1',
  `shop_businessType` tinyint(3) NOT NULL default '1',
  `shop_deliverType` tinyint(3) NOT NULL default '2',
  `shop_payType` tinyint(3) NOT NULL default '1',
  `shop_isopen` tinyint(3) NOT NULL default '0',
  `longitude` float default NULL,
  `latitude` float default NULL,
  PRIMARY KEY  (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_shop
-- ----------------------------
INSERT INTO shuijian_shop VALUES ('0', '1', 'test', '1.00', '1.00', 'test222', '3', '1', '3', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for `shuijian_shopshiptime`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_shopshiptime`;
CREATE TABLE `shuijian_shopshiptime` (
  `shipTime_id` tinyint(3) NOT NULL auto_increment,
  `shopid` tinyint(3) NOT NULL,
  `shipT_weekDays` varchar(120) NOT NULL,
  `shipT_todayArrive` tinyint(3) NOT NULL,
  `todayArriveTime` varchar(120) NOT NULL,
  `sendAfterDays` varchar(20) NOT NULL,
  `sendTimeBegin` varchar(20) NOT NULL,
  `sendTimeEnd` varchar(20) NOT NULL,
  `chooseCount` tinyint(3) NOT NULL default '3',
  PRIMARY KEY  (`shipTime_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_shopshiptime
-- ----------------------------
INSERT INTO shuijian_shopshiptime VALUES ('1', '0', '1,2,3,4,5,6,7', '1', '04:08:00', '1', '01:00', '19:00', '3');

-- ----------------------------
-- Table structure for `shuijian_site`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_site`;
CREATE TABLE `shuijian_site` (
  `site_id` tinyint(3) NOT NULL auto_increment,
  `site_city` tinyint(3) NOT NULL,
  `site_shop` tinyint(3) NOT NULL,
  `site_name` varchar(120) NOT NULL,
  `site_address` varchar(120) NOT NULL,
  `site_contact` varchar(120) NOT NULL,
  `site_mobile` varchar(20) NOT NULL,
  `site_startTime` int(11) NOT NULL,
  `site_endTime` int(11) NOT NULL,
  `shop_isopen` tinyint(3) NOT NULL default '1',
  `longitude` float default NULL,
  `latitude` float default NULL,
  PRIMARY KEY  (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_site
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_users`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_users`;
CREATE TABLE `shuijian_users` (
  `user_id` mediumint(8) NOT NULL auto_increment,
  `email` varchar(60) NOT NULL,
  `user_name` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `sex` tinyint(1) NOT NULL default '0',
  `user_money` decimal(10,2) NOT NULL default '0.00',
  `frozen_money` decimal(10,2) NOT NULL default '0.00',
  `address_id` mediumint(8) NOT NULL default '0',
  `reg_time` int(10) NOT NULL default '0',
  `last_login` int(11) NOT NULL,
  `last_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_ip` varchar(15) NOT NULL,
  `mobile_phone` varchar(20) NOT NULL,
  `from` varchar(20) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_users
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_user_address`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_user_address`;
CREATE TABLE `shuijian_user_address` (
  `address_id` mediumint(8) NOT NULL auto_increment,
  `user_id` mediumint(8) NOT NULL default '0',
  `consignee` varchar(60) NOT NULL,
  `country` smallint(5) NOT NULL default '0',
  `countryName` varchar(60) NOT NULL,
  `provinceId` smallint(5) NOT NULL default '0',
  `provinceName` varchar(60) NOT NULL,
  `cityId` smallint(5) NOT NULL default '0',
  `cityName` varchar(60) NOT NULL,
  `districtId` smallint(5) NOT NULL default '0',
  `districtName` varchar(60) NOT NULL,
  `isDefault` tinyint(2) NOT NULL default '0',
  `isDeleted` tinyint(2) NOT NULL default '0',
  `address` varchar(120) NOT NULL,
  `mobile` varchar(60) NOT NULL,
  `create_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `update_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_user_address
-- ----------------------------

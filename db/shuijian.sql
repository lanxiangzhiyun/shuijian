/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : shuijian

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-12-26 18:25:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `shuijian_admin`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_admin`;
CREATE TABLE `shuijian_admin` (
  `admin_id` tinyint(4) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_admin
-- ----------------------------
INSERT INTO `shuijian_admin` VALUES ('1', 'admin', 'd41d8cd98f00b204e9800998ecf8427e', 'f@126.com', 'admin', '123456', '127.0.0.1', '112222233', '1450879362', '1', '2,3');

-- ----------------------------
-- Table structure for `shuijian_article`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_article`;
CREATE TABLE `shuijian_article` (
  `article_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(30) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `add_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_article
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_authmenu`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_authmenu`;
CREATE TABLE `shuijian_authmenu` (
  `auth_id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `menu_id` tinyint(3) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_authmenu
-- ----------------------------
INSERT INTO `shuijian_authmenu` VALUES ('1', '2', '1', '1');

-- ----------------------------
-- Table structure for `shuijian_cart`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_cart`;
CREATE TABLE `shuijian_cart` (
  `rec_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `session_id` char(32) NOT NULL,
  `goods_id` mediumint(8) NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL,
  `goods_name` varchar(120) NOT NULL,
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` smallint(5) NOT NULL DEFAULT '0',
  `goods_attr` text NOT NULL,
  PRIMARY KEY (`rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_city`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_city`;
CREATE TABLE `shuijian_city` (
  `city_id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `ctity_code` varchar(20) NOT NULL,
  `city_name` varchar(120) NOT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_city
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_goods`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_goods`;
CREATE TABLE `shuijian_goods` (
  `goods_id` mediumint(8) NOT NULL,
  `goods_sn` varchar(60) NOT NULL,
  `goods_name` varchar(120) NOT NULL,
  `click_count` int(10) NOT NULL DEFAULT '0',
  `goods_number` smallint(5) NOT NULL DEFAULT '0',
  `goods_weight` decimal(10,3) NOT NULL DEFAULT '0.000',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `promote_start_date` int(11) NOT NULL DEFAULT '0',
  `promote_end_date` int(11) NOT NULL DEFAULT '0',
  `warn_number` tinyint(3) NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL,
  `goods_brief` varchar(255) NOT NULL,
  `goods_desc` text NOT NULL,
  `goods_thumb` varchar(255) NOT NULL,
  `goods_img` varchar(255) NOT NULL,
  `original_img` varchar(255) NOT NULL,
  `is_on_sale` tinyint(1) NOT NULL DEFAULT '1',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `sort_order` smallint(4) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_promote` tinyint(1) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `goods_attrs` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_menu`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_menu`;
CREATE TABLE `shuijian_menu` (
  `menu_id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(120) NOT NULL,
  `menu_level` tinyint(3) NOT NULL DEFAULT '1',
  `menu_url` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `menu_pid` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shuijian_menu
-- ----------------------------
INSERT INTO `shuijian_menu` VALUES ('1', '系统设置', '1', '', '1', '0');
INSERT INTO `shuijian_menu` VALUES ('2', '管理员设置', '2', '/iadmin.php/Sys/admin_list', '1', '1');
INSERT INTO `shuijian_menu` VALUES ('3', '菜单管理', '2', '/iadmin.php/Menu/menu_list', '1', '1');
INSERT INTO `shuijian_menu` VALUES ('4', 'test', '2', 'test', '1', '1');

-- ----------------------------
-- Table structure for `shuijian_order_action`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_order_action`;
CREATE TABLE `shuijian_order_action` (
  `action_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `order_id` mediumint(8) NOT NULL DEFAULT '0',
  `action_user` varchar(30) NOT NULL,
  `order_status` tinyint(1) NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) NOT NULL,
  `action_note` varchar(255) NOT NULL,
  `log_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_order_action
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_order_info`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_order_info`;
CREATE TABLE `shuijian_order_info` (
  `order_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(20) NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `order_status` tinyint(1) NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL,
  `country` smallint(5) NOT NULL DEFAULT '0',
  `province` smallint(5) NOT NULL DEFAULT '0',
  `city` smallint(5) NOT NULL DEFAULT '0',
  `district` smallint(5) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL,
  `mobile` varchar(60) NOT NULL,
  `postscript` varchar(255) NOT NULL,
  `shipping_area` varchar(120) NOT NULL,
  `shipping_name` varchar(120) NOT NULL,
  `pay_id` tinyint(3) NOT NULL DEFAULT '0',
  `pay_name` varchar(120) NOT NULL,
  `inv_payee` varchar(120) NOT NULL,
  `goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coupon_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `money_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `surplus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `confirm_time` int(10) NOT NULL DEFAULT '0',
  `pay_time` int(10) NOT NULL DEFAULT '0',
  `shipping_time` int(10) NOT NULL DEFAULT '0',
  `to_buyer` varchar(255) NOT NULL,
  `pay_note` varchar(255) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_order_info
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_payment`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_payment`;
CREATE TABLE `shuijian_payment` (
  `pay_id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(20) NOT NULL,
  `pay_name` varchar(120) NOT NULL,
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) NOT NULL DEFAULT '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_payment
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_shop`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_shop`;
CREATE TABLE `shuijian_shop` (
  `shop_id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `shop_city` tinyint(3) NOT NULL,
  `shop_name` varchar(120) NOT NULL,
  `low_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shop_address` varchar(120) NOT NULL,
  `shop_type` tinyint(3) NOT NULL DEFAULT '1',
  `shop_businessType` tinyint(3) NOT NULL DEFAULT '1',
  `shop_deliverType` tinyint(3) NOT NULL DEFAULT '2',
  `shop_payType` tinyint(3) NOT NULL DEFAULT '1',
  `shop_isopen` tinyint(3) NOT NULL DEFAULT '0',
  `longitude` float DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_shop
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_users`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_users`;
CREATE TABLE `shuijian_users` (
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `user_name` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `address_id` mediumint(8) NOT NULL DEFAULT '0',
  `reg_time` int(10) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL,
  `last_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_ip` varchar(15) NOT NULL,
  `mobile_phone` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_users
-- ----------------------------

-- ----------------------------
-- Table structure for `shuijian_user_address`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_user_address`;
CREATE TABLE `shuijian_user_address` (
  `address_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL,
  `country` smallint(5) NOT NULL DEFAULT '0',
  `province` smallint(5) NOT NULL DEFAULT '0',
  `city` smallint(5) NOT NULL DEFAULT '0',
  `district` smallint(5) NOT NULL DEFAULT '0',
  `address` varchar(120) NOT NULL,
  `mobile` varchar(60) NOT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_user_address
-- ----------------------------

/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : shuijian

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-12-11 18:40:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `shuijian_admin`
-- ----------------------------
DROP TABLE IF EXISTS `shuijian_admin`;
CREATE TABLE `shuijian_admin` (
  ` admin_id` tinyint(4) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (` admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shuijian_admin
-- ----------------------------

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

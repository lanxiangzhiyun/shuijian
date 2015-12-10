/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : shuijian

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-12-10 18:33:44
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

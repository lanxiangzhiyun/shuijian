<?php
/** 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.2
 *
 * 编码者：Fongson
 * 编码时间：2011-08-15
 */
require_once("alipay.config.php");
require_once("alipay_notify.class.php");
require_once('../../../api/api.function.php');

// 计算得出通知验证结果
$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyReturn();

// 验证成功
if($verify_result) 
{
	// 数据库配置文件
	include_once('../../../config/shop.db.conf.php');
	// 数据库操作文件
	include_once('../../../class/db_mysql.php');
		
	$db = new DB(DB_HOST,DB_USER,DB_PWD,DB_SERVICE);

	// 获取支付宝的通知返回参数，例如 http://127.0.0.1/boqii/shop/include/alipay/kjlogin/return_url.php?is_success=T&notify_id=RqPnCoPT3K9%252Fvwbh3I7w5vbw4HKe1GZDnYE%252BZhZaPxkK8DJLFeH5O72ZeghQHi2WXWs6&real_name=%E6%96%B9%E6%96%87%E6%96%8C&token=201108155d41389882e9464284119bbfd0d34e3f&user_id=2088002533247163&sign=4a70ff438756289c094a972bdf0a2a05&sign_type=MD5
	// 成功标志
	//$is_success = $_GET['is_success'];
	// 签名方式
	//$sign_type = $_GET['sign_type'];
	// 签名
	//$sign = $_GET['sign'];
	// 通知校验ID
	//$notify_id = $_GET['notify_id'];
	// 支付宝用户号
	$user_id	= $_GET['user_id'];
	// 支付宝用户姓名或淘宝昵称（当买家通过etao并且使用淘宝账号登录时，本参数记录的是淘宝昵称）
	$user_real_name = $_GET['real_name'];
	// 用户支付宝登录账号（支付宝登录账号，email地址或者手机号）
	$user_name = $_GET['email'];
	if(empty($user_name))
	{
		$user_name = $_GET['user_id'];
	}
	// 授权令牌
	$token		= $_GET['token'];
	// 用户等级（NOMARL:普通会员;VIP:VIP会员;IMPERIAL_VIP:至尊VIP会员）
	//$user_grade = $_GET['user_grade'];
	// 用户等级类型（0:金账户未激活;1:金账户已激活）
	//$user_grade_type = $_GET['user_grade_type'];
	// 用户等级衰减时间
	//$gmt_decay = $_GET['gmt_decay'];
	// 目标商户跳转结果页面（etao用）
	$target_url = $_GET['target_url'];

	// 业务程序
	// 授权令牌放入SESSION中
	//session_cache_limiter('nocache');
	//session_start();
	//$_SESSION['alipay_token'] = $token;
	$cookie_time = (time() + 1800);
	setcookie("alipay_token",$token,$cookie_time,'/','.boqii.com',0);

	// 构造参数数组
	$froms_datas = array("froms_coms" 		=> "taobao",
					 	 "froms_uids" 		=> $user_id,
						 "froms_username"	=> $user_name,
					 	 "froms_realname" 	=> $user_real_name,
						 "froms_email"		=> "",
					 	 "froms_pass" 		=> "",
						 "city_id"			=> 0
					 	 );	 
	
	// 处理用户数据
	operate_user($froms_datas);
	
	// etao专用
	if($_GET['target_url'] != "") 
	{
		// 程序自动跳转到target_url参数指定的url去		
		//header("Location: " . $_GET['target_url'] ); exit;
		gotoUrl($target_url);
	}	
	else
	{
		//echo "gotourl" . $_REQUEST['goto_url'];
		gotoUrl($shop_dir . "/index.php");
		// header("Location: " . $shop_dir . "/index.php"); exit;
	}
}
else {
    // 验证失败
    echo "验证失败";
}

/**
 * 跳转到指定页面
 *
 * @param string $html_url 跳转页面地址
 *
 */
function gotoUrl($html_url)
{
	echo("<script language='javascript'>self.location.replace('$html_url');</script>");exit;
}

?>
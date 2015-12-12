<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

// 数据库配置文件
include_once('../../config/shop.db.conf.php');

// 数据处理
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token),time()+1800,'/','.boqii.com',0);

	$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $_SESSION['token']['access_token']);
	$uid_get = $c->get_uid();
	if(empty($uid_get) || empty($uid_get['uid']))
	{
		echo "用户数据不允许获取，连接失败，请重新登录！";
		// 返回用户登录页面
		gotoUrl($blog_dir . "/user/login.php");
	}
	$uid = $uid_get['uid'];
	$weibo_user = $c->show_user_by_id($uid);//根据ID获取用户等基本信息

	// 数据库操作文件
	include_once('../../class/db_mysql.php');
	include_once('../api.function.php');

	$db = new DB(DB_HOST,DB_USER,DB_PWD,DB_SERVICE);

	// 构造参数数组
	$froms_datas = array("froms_coms" 		=> "sina",
					 	 "froms_uids" 		=> $uid,
						 "froms_username"	=> $uid,
					 	 "froms_realname" 	=> empty($weibo_user['screen_name']) ? $uid : $weibo_user['screen_name'],
						 "froms_email"		=> "",
					 	 "froms_pass" 		=> "",
						 "city_id"			=> 0
					 	 );	 
	
	// 处理用户数据
	operate_user($froms_datas);
	// 返回首页
	gotoUrl($shop_dir."/?weibo");

}
else
{
	echo "连接失败，请重新登录！";
	// 返回用户登录页面
	gotoUrl($blog_dir . "/user/login.php");
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

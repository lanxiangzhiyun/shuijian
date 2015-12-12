<?php
/**
 * PHP SDK for QQ登录 OpenAPI
 *
 * @version 1.5
 * @author connect@qq.com
 * @copyright © 2011, Tencent Corporation. All rights reserved.
 */
error_reporting(0);
header("Content-Type: text/html; charset=utf-8");
require_once("../comm/utils.php");
require_once('../../../api/api.function.php');

 /*
 * @brief 获取用户信息.请求需经过URL编码，编码时请遵循 RFC 1738
 * 
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function get_user_info($appid, $appkey, $access_token, $access_token_secret, $openid)
{
	//获取用户信息的接口地址, 不要更改!!
    $url    = "http://openapi.qzone.qq.com/user/get_user_info";
    $info   = do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid);
    $arr = array();
    $arr = json_decode($info, true);

    return $arr;
}

//接口调用示例：
$arr = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);

if($_SESSION['openid']&&$arr['ret']==0&&$_SESSION["openid"]&&$arr['msg']==""){
	/*// 数据库配置文件
	include_once('../../../config/shop.db.conf.php');
	// 数据库操作文件
	include_once('../../../class/db_mysql.php');

	$db = new DB(DB_HOST,DB_USER,DB_PWD,DB_SERVICE);

	// 构造参数数组
	$froms_datas = array("froms_coms" 		=> "qq",
					 	 "froms_uids" 		=> db_input($_SESSION["openid"]),
						 "froms_username"	=> db_input($arr['nickname']),
					 	 "froms_realname" 	=> db_input($arr['nickname']),
						 "froms_email"		=> "",
					 	 "froms_pass" 		=> "",
						 "city_id"			=> 0
					 	 );

	// 处理用户数据
	operate_user($froms_datas);

	$cookie_time = (time() + 3600 * 48);
	setcookie("qq_openid",$_SESSION["openid"],$cookie_time,'/','.boqii.com',0);*/
	
	//echo('<script type="text/javascript">window.opener.location.href ="http://shop.boqii.com";window.self.close();</script>');
	echo("<script type='text/javascript'>if(window.opener.parent != window.opener) {window.opener.parent.location.reload();}else{window.opener.location.href ='$callback';} window.self.close();</script>");
}

function db_input($string){
	if (function_exists('mysql_escape_string')) {
		return mysql_escape_string($string);
	} else {
		return addslashes($string);
	}		
}
?>

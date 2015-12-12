<?php
$appkey		= "1"; //平台分配的接口调用KEY
$secret		= ""; //平台分配的接口调用密钥
$appurl		= "http://ecode.ematong.com/gcert/submsg";	//平台的接口调用网址
$callback   = "http://localhost/gcert/callback.php";		//接口返回结果的回调地址

function get_appkey(){
	return $appkey;
}

$server_host = "ecode.ematong.com";
$server_port = "80";
$server_url = "/gcert/submsg";

//date_default_timezone_set('PRC');
?>
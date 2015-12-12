<?php
/*
	*功能：读取回调返回结果
	*版本：1.0
*/

require_once("config.php"); 
require_once("service.php"); 

$method  = $_POST['method']; 
$format  = $_POST['format']; 
$appkey  = $_POST['appkey']; 
$timestamp  = $_POST['timestamp'];
$version  = $_POST['version'];
$message  = $_POST['message'];
$sign  = $_POST['sign'];
		
$service = new service();

$parameter = array(
	"method"        => $method, //方法
	"format"        => $format,         //数据格式
	"appkey"     		=> $appkey,      		//平台分配的key
	"timestamp"     => $timestamp,      //时间戳
	"version"       => $version ,  		//版本
	"message"       => $message				 //消息体内容
); 

$mysign = $service->md5sign($parameter,$secret);
$now = date("Y-m-d H:i:s"); 

if($sign != $mysign)
{
	//echo '签名失败';
	$service->log($sign.'='.$mysign);
	echo 'fail';
	return;
}
if((strtotime($now) - strtotime($timestamp))/60 > 5){
	//超时消息
	$service->log((strtotime($now) - strtotime($timestamp))/60);
	echo 'fail';
	return;
} 
//返回成功标志
echo 'true';

//存结果到文件
$obj = json_decode($message);  
$item = $obj->callback_message->message->items->item;
//请求流水号
$req_flowno = $obj->callback_message->message->req_flowno;

$fp = fopen("callback.txt","a");
fwrite($fp,$now." req_flowno=".$req_flowno.";");
foreach ($item as $v1) {
		foreach ($v1 as $key => $value) {
        fwrite($fp,$key."=".$value."; "); 
    }
}
fwrite($fp,"\t\n");
fclose($fp);

?>
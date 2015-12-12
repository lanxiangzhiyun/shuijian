<?php
/*
	*功能：电子凭证生成和分发
	*版本：1.0
*/

require_once("config.php"); 
require_once("service.php"); 

$arr = array(   
    'request_message' => array(   
        'message' => array(
         	'req_type' => '02',   
        	'title' => '商品简称',
        	'price' => '10.00',   
        	'mms_title' => '彩信标题', 
        	'send_type' => '02',  //00:彩信+短信（默认）；01：只发彩信；02：只发短信 
        	'valid_datetime' => '2011-07-10 12:10:00', //有效期
        	'valid_times' => 1,  //可验证次数 
        	'send_mobile' => '13512345678', //发送者手机号
        	'send_total' => 1,   
        	'rece_mobile' => '13512345678', //接收者手机号
        	'mms_content' => '凭此信息可至福田八卦三路兑换生日蛋糕一份！有效期至2011-07-10。过期无效。！', //彩信内容  
        	'sms_content' => '凭此信息可至福田八卦三路兑换生日蛋糕一份！有效期至2011-07-10。过期无效。！', //短信内容
        	'server_print_text' => '终端上打印的服务内容',  //终端小票打印的服务内容 
        	'callback_url' => 'http://localhost/phpdemo/callback.php',//你的外网的有效地址
        	'type' => '1', //0:要回调；1：不要回调
        )   
    )   
);  

$service = new service();
 
$message = json_encode($arr);
$now = date("Y-m-d H:i:s"); 
$flowno = $service->getFlowno(); 
if($flowno != null){
	$flowno = strval(intval($flowno) + 1);
}else{
	$flowno = "1";
}

$service->setFlowno($flowno);

$parameter = array(
	"method"        => "requestmessage", //请求方法
	"format"        => "json",         	 //数据格式
	"appkey"     		=> $appkey,      		//平台分配的key
	"timestamp"     => $now,      			//时间戳
	"version"       => "1.0",  					//版本
	"flowno"        => $flowno,        //流水号
	"message"       => $message				 //消息体内容
); 

$sign = $service->md5sign($parameter,$secret);

$parameter['sign'] = $sign;

$apiparams = $service->getParams($parameter);

$result = $service->getResult($appurl,$apiparams);

$result = strstr($result, '{');
//echo $result; 
echo '<p>';

$obj = json_decode($result);  
//print_r($obj);
echo '执行结果：<a href="index.php">返回</a><br>';

$arr = $obj->response_message->message;
foreach ($arr as $key => $value) {
    echo "$key = $value<br>\n";
}

?>
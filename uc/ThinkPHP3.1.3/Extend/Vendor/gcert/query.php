<?php
/*
	*功能：凭证使用查询
	*版本：1.0
*/

require_once("config.php"); 
require_once("service.php"); 

$arr = array(   
    'request_message' => array(   
        'message' => array(
         	'req_type' => '04',   
        	'fields' => 'trade_id,send_mobile,rece_mobile,send_type,order_status,posno,code,resend_times,createtime,valid_date,valid_times,cert_status',
        	'start_time' => '2011-06-01 00:00:00',   
        	'end_time' => '2011-06-30 23:59:59', 
        	'page_no' => '1',  
        	'page_size' => '10', 
        )   
    )   
);  

$service = new service();
 
$message = json_encode($arr);
$now = date("Y-m-d H:i:s"); 
$flowno = $service->getFlowno(); 
if($flowno != null){
	//$flowno = strval(intval($flowno) + 1);
	$flowno = $flowno + 1;
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
$total = $obj->response_message->message->total;

//print_r($obj);

echo '查询结果：<a href="index.php">返回</a><br>';
if($total == 0){
	echo '没有记录';
	return;
}

$item =  $obj->response_message->message->items->item;
//print_r($item);
echo '<table border=1>';
 //字段
foreach ($item[0] as $key => $value) {
echo "<th>$key</th>";
}
//值
foreach ($item as $v1) {
	echo '<tr>';
foreach ($v1 as $v2) {
	print "<td>$v2</td>";
}
 echo '</tr>';
}
echo '</table>';

?>
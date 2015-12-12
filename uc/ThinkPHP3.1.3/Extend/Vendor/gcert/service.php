<?php
/**
	*类名：service
	*功能：通用库
	*版本：1.0
*/

class service {

	function getParams($params) {
		$apiparams  = "";
		
		$sort_array = array();
		$arg        = "";
		$sort_array = $this->arg_sort($params);
		while (list ($key, $val) = each ($sort_array)) {
			$arg.="&".$key."=".urlencode($val);
		}
		$apiparams = substr($arg,1);
		
		return $apiparams;

	}

	function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;

	}

	function md5sign($parameter,$secret) {
		$mysign = "";
		
		$sort_array = array();
		$arg = "";
		$para = $this->para_filter($parameter);
		$sort_array = $this->arg_sort($para);
		while (list ($key, $val) = each ($sort_array)) {
			$arg.=$key.$val;
		}
		
		//首尾加上密钥
		$mysign = md5($secret.$arg.$secret);
		
		return strtoupper($mysign);

	}
	
	function para_filter($parameter) { //除去数组中的签名模式
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign")continue;
			else	$para[$key] = $parameter[$key];
		}
		return $para;
	}
	
	
	function getResult($appurl, $apiparams, $server_host, $server_port, $server_url, $timeout = "60") {
	   //使用全局的服务地址配置
	  //global $server_host,$server_port,$server_url;

	  $srv_host = $server_host;	//凭证平台服务主机
	  $srv_port = $server_port;	//凭证平台服务端口号
	  $url = $server_url;					//凭证平台服务地址
	  $fp = '';
	  $result = '';
	  $errno = 0;
	  $errstr = '';
	  //echo $server_host,$server_url;exit;
	  if ($srv_host == '' || $url == ''){
	   echo('host or dest url empty<br>');
	  }
	  $fp = fsockopen($srv_host,$srv_port,$errno,$errstr,$timeout);
	  if (!$fp){
	   die("ERROR: $errno - $errstr<br />\n");
	  }
	  
	  $out = "POST $url HTTP/1.1\r\n";
	  $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
	  $out .= "User-Agent: MSIE\r\n";
	  $out .= "Host: ".$srv_host."\r\n";
	  $out .= "Content-Length: ".strlen($apiparams)."\r\n";
	  $out .= "Connection: close\r\n\r\n";
	  $out .= $apiparams."\r\n\r\n";
	  //print_r($out);exit;
	  fputs($fp,$out);
	  while(!feof($fp)){
	   $result .= fgets($fp,1024);
	  }
	  fclose($fp);
	  
	  return $result ;
	} 
	
	function  getFlowno() {
		$contents  = '';
		$filename = "flowno.txt";
		$handle = fopen ($filename, "r");
		$contents = fread ($handle, filesize($filename));
		fclose ($handle);
		return $contents; 
	}
	
	function  setFlowno($flowno) {
		$filename = 'flowno.txt';		
		$fp = fopen($filename,"w");	
		flock($fp, LOCK_EX) ;
 		fwrite($fp,$flowno."\t\n");
		flock($fp, LOCK_UN); 
		fclose($fp);
	}
	
	function  log($result) {
		$filename = 'log.txt';		
		$fp = fopen($filename,"a");	
		flock($fp, LOCK_EX) ;
 		fwrite($fp,$result."\t\n");
		flock($fp, LOCK_UN); 
		fclose($fp);
	} 
}
?>
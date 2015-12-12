<?php
//类
class RSADataSigner {
	//支付宝pid
	public $pid = '';
	//支付宝app_id
	public $appId = '';
	//支付宝authType
	public $authType = 'AUTHACCOUNT';//默认值
	//支付宝date
	public $signDate = '';
	//private key
	public $_privateKey = '';

	public function __construct($privateKey){
		$this->$_privateKey = $privateKey;
	}
	//
	public function formatPrivateKey($privateKey){
		$len = mb_strlen($privateKey,'utf-8');
		$result = '-----BEGIN PRIVATE KEY-----\n';
		$index = 0;
		$count = 0;
		while($index<$len){
			$ch = $privateKey[$index];
			if($ch=='\r' || $ch=='\n'){
				++$index;
				continue;
			}
			$result .= $ch;
			if(++$count==79){
				$result.='\n';
				$count=0;
			}
			$index++;
		}
		$result .= '\n-----END PRIVATE KEY-----';
		return $result;
	}

	//
	public function signString($str){
		$formatKey = $this->formatPrivateKey($this->_privateKey);
		$messageLength = mb_strlen();
	}
	
}
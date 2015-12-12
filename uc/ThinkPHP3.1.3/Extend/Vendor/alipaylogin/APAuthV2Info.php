<?php
//֧����������
class APAuthV2Info {
	//֧����pid
	public $pid = '';
	//֧����app_id
	public $appId = '';
	//֧����authType
	public $authType = 'LOGIN';//Ĭ��ֵ
	//֧����date
	public $signDate = '';
	public function __construct() {
		$this->pid = C('ALIPAY_P_ID');
		$this->appId = C('ALIPAY_APP_ID');
    }
	//���ܺ���
	//type1Ϊandroid��2Ϊios
	public function description($targetId,$type=1){
		if(strlen($this->pid)!=16 || strlen($this->appId)!=16){
			return '';
		}
		$arr = array(
			'app_id'=>$this->appId,
			'pid'=>$this->pid,
			'apiname'=>'com.alipay.account.auth',
			'app_name'=>'mc',
			'biz_type'=>'openservice',
			'product_id'=>'WAP_FAST_LOGIN',
			'scope'=>'kuaijie',
			'target_id'=>$targetId,
			'auth_type'=>$this->authType,
			'sign_date'=>date('Y-m-d H:i:s'),
			'service'=>'mobile.securitypay.pay',
		);
		if($type==2){	//ios
			//$arr['auth_type'] = 'AUTHACCOUNT';
		}
		$result = '';
		foreach($arr as $key=>$val){
			$result .= $key.'="'.$val.'"&';
		}
		return mb_substr($result,0,-1,'utf-8');
	}
	
}
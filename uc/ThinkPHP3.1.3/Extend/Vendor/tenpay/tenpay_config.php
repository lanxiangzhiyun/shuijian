<?php
/**
 * ΢��֧�������ļ�
 */
class tenpay_config{
	public  $tenpay_config = array();

	public function __construct(){
		$tenpay_config['DEBUG_'] = false;
		//�Ƹ�ͨ�̻���
		$tenpay_config['PARTNER'] = "1219676801";
		//�Ƹ�ͨ��Կ
		$tenpay_config['PARTNER_KEY'] = "eca1cfbfefccafcb947ef552708b735e";
		//appid
		$tenpay_config['APP_ID']="wx6ce20275fb3ca36b";
		//appsecret
		$tenpay_config['APP_SECRET']= "07a1e931f0f28a65d0b55e20e095241b";
		//paysignkey(��appkey)
		$tenpay_config['APP_KEY']="F0cnttdvyarN2qUR7XqgHNCDndOADZsIbzpO6h8s3Sp21CDx6VRiPyuCPDE0warBo7t8y8kGdtEozXn5CybAkBHmdv9oUj0UeTzgAAXgDc17JHIdscSWdRYbkJSI32AA";

		$this->tenpay_config = $tenpay_config;
	}
}

?>
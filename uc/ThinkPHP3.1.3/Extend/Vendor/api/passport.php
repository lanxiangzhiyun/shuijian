<?php
	// 数据库配置文件
	include_once('config/shop.db.conf.php');
	// 支付宝快捷登录配置文件
	require_once("include/alipay/kjlogin/alipay.config.php");
	// 支付宝接口公用函数
	require_once("include/alipay/kjlogin/alipay_core.function.php");
	// 支付宝各接口构造类
	require_once("include/alipay/kjlogin/alipay_service.class.php");
	
	// 支付宝快捷登录网关接口
	$gateway 		= "https://mapi.alipay.com/gateway.do";
	// 参数编码字符集
	$input_charset 	= trim($aliapy_config['input_charset']);//empty($aliapy_config['input_charset']) ? "utf-8" : $aliapy_config['input_charset'];
	// 合作者身份ID
	$partner 		= trim($aliapy_config['partner']);//empty($aliapy_config['partner']) ? "" : $aliapy_config['partner'];
	$goto_url = empty($_SERVER['HTTP_REFERER']) ? $shop_dir . "/login.php" : $_SERVER['HTTP_REFERER'];
	// 页面跳转同步通知路径（支付宝处理完请求后，当前页面自动跳转至商户网站里指定页面的http路径）
	if($goto_url == $shop_dir . "/index.php?carlogin.html")
	{ 
		$return_url 	= trim($aliapy_config['car_return_url']);//empty($aliapy_config['return_url']) ? "" : $aliapy_config['return_url'];
	}
	else
	{
		$return_url 	= trim($aliapy_config['return_url']);
	}
	//$goto_url = empty($_SERVER['HTTP_REFERER']) ? $shop_dir . "/index.php" : $_SERVER['HTTP_REFERER'];
	
	// EncodeURI
	//$gateway .= "&seller_email=" . $alipay_config['seller_email'];
	// 接口名称
	$service		= "alipay.auth.authorize";
	// 目标服务地址
	$target_service	= "user.auth.quick.login";
	// 签名方式
	//$sign_type = empty($aliapy_config['sign_type']) ? 'MD5' : $aliapy_config['sign_type'];
	
	// 代签名字符串数组
	$sign_arr = array("_input_charset"	=>	$input_charset,
					"partner"			=>	$partner,
					"return_url"		=>	$return_url,
					"service"			=>	$service,
					"target_service"	=>	$target_service
					);

	// 构造提交表单HTML数据
	$alipayService = new AlipayService($aliapy_config);

	//logResult("URL参数：" . createLinkstring($sign_arr));
	
	// 构造快捷登录接口
	echo $alipay_form = $alipayService->alipay_auth_authorize($sign_arr);
	
	// 写日志
	//logResult($alipay_form);
	
	// 客户端IP与防钓鱼时间戳（可空）
	// 构造模拟远程HTTP的POST请求，获取支付宝的返回XML处理结果	
	//echo $alipayService->alipay_send_postinfo($sign_arr);
	
?>
<?php

$common_conf = require './config.php';

$home_conf = array(
	//'配置项' => '配置值',
	    'LANG_SWITCH_ON' => true,   // 开启语言包功能
		'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
		'DEFAULT_LANG' => 'zh-cn', // 默认语言
		'LANG_LIST'        => 'zh-cn', // 允许切换的语言列表 用逗号分隔
		'VAR_LANGUAGE'     => 'l', // 默认语言切换变量?l=zh-cn
	
		//主题
		'DEFAULT_THEME'  => 'default',
		'THEME_LIST' => 'default',
	    'TMPL_DETECT_THEME' => true, // 自动侦测模板主题
);

return array_merge($common_conf,$home_conf);
	
?>
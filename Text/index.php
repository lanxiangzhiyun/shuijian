<?php


	define('APP_NAME','admin');
	define('APP_PATH','./admin/');
 

	//调试模式显示错误位置
	define('APP_DEBUG',1);

	//物理路径
	define('REAL_PATH',str_replace('\\','/',dirname(__FILE__)).'/');
	//域名路径\
	//define('SITE_PATH','http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"].'/');//http://localhost/newgfong/index.php/  适合多层目录

	define('SITE_PATH','http://'.$_SERVER["HTTP_HOST"].'/');

	require('./Core/ThinkPHP.php');
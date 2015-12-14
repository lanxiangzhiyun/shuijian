<?php
if (in_array($_SERVER['HTTP_HOST'], array('local.shuijian.com'))) {
	//数据库
    $db_host = '127.0.0.1';
    $db_name = 'shuijian';
    $db_user = 'root';
    $db_pwd = '';
    // 网站域名
    $blog_dir = 'http://local.shuijian.com/';
}

$myconfig = array(
	'DB_TYPE'=> 'mysql',   	// 数据库类型
    'DB_HOST'=> $db_host, 	// 数据库服务器地址
    'DB_NAME'=>$db_name,  	// 数据库名称
    'DB_USER'=>$db_user, 	// 数据库用户名
    'DB_PWD'=>$db_pwd, 		// 数据库密码
    'DB_PORT'=>'3306', 		// 数据库端口
    'DB_PREFIX'=>'', 		// 数据表前缀
	);
?>

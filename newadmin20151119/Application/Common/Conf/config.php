<?php
return array(
'DB_TYPE'   => 'mysql', // 数据库类型
'DB_HOST'   => 'localhost', // 数据库连接地址
'DB_NAME'   => 'newadmin', // 数据库名
'DB_USER'   => 'root', // 数据库用户名
'DB_PWD'    => '', // 数据库密码
'DB_PORT'   => 3306, // 数据库端口
'DB_PREFIX' => 'mr_', // 数据库前缀 
'DB_CHARSET'=> 'utf8', // 数据库编码
'DB_DEBUG'  =>  TRUE, // 是否开启调试模式
'DB_LIKE_FIELDS'=>'news_title|news_content|news_flag|news_open',//自动模糊查询字段
		
   'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
   'MAIL_CHARSET' =>'utf-8',//设置邮件编码
   'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
		
'AUTH_CONFIG' => array(
'AUTH_ON' => true, //是否开启权限
'AUTH_TYPE' => 1, // 
'AUTH_GROUP' => 'mr_auth_group', //用户组
'AUTH_GROUP_ACCESS' => 'mr_auth_group_access', //用户组规则
'AUTH_RULE' => 'mr_auth_rule', //规则中间表
'AUTH_USER' => 'mr_admin'// 管理员表
		)
);
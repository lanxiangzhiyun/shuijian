<?php
if(in_array($_SERVER['HTTP_HOST'],array('wwwlocal.boqii.com','ilocal.boqii.com','vetlocal.boqii.com','bbslocal.boqii.com','newbbslocal.boqii.com','mlocal.boqii.com','clocal.boqii.com','ulocal.boqii.com','uplocal.boqii.com','newslocal.boqii.com','zhuantilocal.boqii.com'))) {
	//数据库
    $db_host = '172.16.76.252';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = 'VViVDwp7hW';
    //接口日志数据库
	$db_applog_host = '172.16.76.252';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_web_user';
    $db_applog_pwd = 'VViVDwp7hW';
    //redis
    $redis_host = '172.16.76.251';
	//接口日志redis
    $redis_applog_host = '172.16.76.251';
    $redis_port = '6379';
    $redis_prefix = 'local';
	//sphinx
	$sphinx_host = '172.16.76.251';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_local';
    $o2o_business = 'o2o_business_local';
    //网站域名
    $blog_dir = "http://wwwlocal.boqii.com";
    $shop_dir = "http://v1.shoptest.boqii.com";
    $shop_api_dir = $shop_dir."/api/api.php";
	$shop_new_api_dir = "http://v1.test.shopapitest.boqii.com";
    $bbs_dir  = "http://bbslocal.boqii.com";
    $i_dir = "http://ilocal.boqii.com";
    $bk_dir = "http://wwwlocal.boqii.com/baike/";
    $img_dir = 'http://imglocal.boqiicdn.com';
	$img_upload_dir = 'http://imglocal.boqii.com';
    $static_dir = 'http://alocal.boqiicdn.com';
	$html_dir = 'D:/phpwork/cache/html/site/';
    $vet_dir = 'http://v2.vetlocal.boqii.com';
    $c_dir = "http://clocal.boqii.com";
	$m_dir = 'http://mlocal.boqii.com';
	$u_dir = 'http://ulocal.boqii.com';
	$v_dir = 'http://vlocal.boqii.com';
	$news_dir="http://newslocal.boqii.com";
    $zhuanti_dir = "http://zhuantilocal.boqii.com";
	//标识
    $www_filename = "svnwww";
	$env_flag = 'local';
	//物理路径
	$upload_file = 'Data/Bbs/Upload/Pushs/';
	$static_dir_path = 'D:/phpwork/static/';
	$resource_path = 'D:/phpwork/www/resource/';
	$api_log_vet_dir = 'D:/phpwork/cache/apilog/www/';
    $api_log_shop_dir = 'D:/phpwork/cache/apilog/shop/';
	$php_log_dir = 'D:/phpwork/cache/phplog/';
	$php_vet_log_dir = 'D:/phpwork/cache/phplog/Vet/Log/';
}elseif(in_array($_SERVER['HTTP_HOST'],array('wwwlocal.boqii.com','ilocal.boqii.com','v1.vetlocal.boqii.com','bbslocal.boqii.com','newbbslocal.boqii.com','mlocal.boqii.com','clocal.boqii.com','ulocal.boqii.com','uplocal.boqii.com','newslocal.boqii.com','zhuantilocal.boqii.com'))) {
	//数据库
    $db_host = '172.16.76.252';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = 'VViVDwp7hW';
    //接口日志数据库
	$db_applog_host = '172.16.76.252';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_web_user';
    $db_applog_pwd = 'VViVDwp7hW';
    //redis
    $redis_host = '172.16.76.251';
	//接口日志redis
    $redis_applog_host = '172.16.76.251';
    $redis_port = '6379';
    $redis_prefix = 'local';
	//sphinx
	$sphinx_host = '172.16.76.251';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_local';
    $o2o_business = 'o2o_business_local';
    //网站域名
    $blog_dir = "http://wwwlocal.boqii.com";
    $shop_dir = "http://v1.shoptest.boqii.com";
    $shop_api_dir = $shop_dir."/api/api.php";
	$shop_new_api_dir = "http://v1.test.shopapitest.boqii.com";
    $bbs_dir  = "http://bbslocal.boqii.com";
    $i_dir = "http://ilocal.boqii.com";
    $bk_dir = "http://wwwlocal.boqii.com/baike/";
    $img_dir = 'http://imglocal.boqiicdn.com';
	$img_upload_dir = 'http://imglocal.boqii.com';
    $static_dir = 'http://alocal.boqiicdn.com';
	$html_dir = 'D:/phpwork/cache/html/site/';
    $vet_dir = 'http://v1.vetlocal.boqii.com';
    $c_dir = "http://clocal.boqii.com";
	$m_dir = 'http://mlocal.boqii.com';
	$u_dir = 'http://ulocal.boqii.com';
	$v_dir = 'http://vtest.boqii.com';
	$news_dir="http://newslocal.boqii.com";
    $zhuanti_dir = "http://zhuantilocal.boqii.com";
	//标识
    $www_filename = "svnwww";
	$env_flag = 'local';
	//物理路径
	$upload_file = 'Data/Bbs/Upload/Pushs/';
	$static_dir_path = 'D:/phpwork/static/';
	$resource_path = 'D:/phpwork/www/resource/';
	$api_log_vet_dir = 'D:/phpwork/cache/apilog/www/';
    $api_log_shop_dir = 'D:/phpwork/cache/apilog/shop/';
	$php_log_dir = 'D:/phpwork/cache/phplog/';
	$php_vet_log_dir = 'D:/phpwork/cache/phplog/Vet/Log/';
}elseif(in_array($_SERVER['HTTP_HOST'],array('wwwlocal.boqii.com','ilocal.boqii.com','v2.vetlocal.boqii.com','bbslocal.boqii.com','newbbslocal.boqii.com','mlocal.boqii.com','clocal.boqii.com','ulocal.boqii.com','uplocal.boqii.com','newslocal.boqii.com','zhuantilocal.boqii.com'))) {
	//数据库
    $db_host = '172.16.76.252';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = 'VViVDwp7hW';
    //接口日志数据库
	$db_applog_host = '172.16.76.252';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_web_user';
    $db_applog_pwd = 'VViVDwp7hW';
    //redis
    $redis_host = '172.16.76.251';
	//接口日志redis
    $redis_applog_host = '172.16.76.251';
    $redis_port = '6379';
    $redis_prefix = 'local';
	//sphinx
	$sphinx_host = '172.16.76.251';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_local';
    $o2o_business = 'o2o_business_local';
    //网站域名
    $blog_dir = "http://wwwlocal.boqii.com";
    $shop_dir = "http://v1.shoptest.boqii.com";
    $shop_api_dir = $shop_dir."/api/api.php";
	$shop_new_api_dir = "http://v1.test.shopapitest.boqii.com";
    $bbs_dir  = "http://bbslocal.boqii.com";
    $i_dir = "http://ilocal.boqii.com";
    $bk_dir = "http://wwwlocal.boqii.com/baike/";
    $img_dir = 'http://imglocal.boqiicdn.com';
	$img_upload_dir = 'http://imglocal.boqii.com';
    $static_dir = 'http://alocal.boqiicdn.com';
	$html_dir = 'D:/phpwork/cache/html/site/';
    $vet_dir = 'http://v2.vetlocal.boqii.com';
    $c_dir = "http://clocal.boqii.com";
	$m_dir = 'http://mlocal.boqii.com';
	$u_dir = 'http://ulocal.boqii.com';
	$v_dir = 'http://vlocal.boqii.com';
	$news_dir="http://newslocal.boqii.com";
    $zhuanti_dir = "http://zhuantilocal.boqii.com";
	//标识
    $www_filename = "svnwww";
	$env_flag = 'local';
	//物理路径
	$upload_file = 'Data/Bbs/Upload/Pushs/';
	$static_dir_path = 'D:/phpwork/static/';
	$resource_path = 'D:/phpwork/www/resource/';
	$api_log_vet_dir = 'D:/phpwork/cache/apilog/www/';
    $api_log_shop_dir = 'D:/phpwork/cache/apilog/shop/';
	$php_log_dir = 'D:/phpwork/cache/phplog/';
	$php_vet_log_dir = 'D:/phpwork/cache/phplog/Vet/Log/';
}elseif(in_array($_SERVER['HTTP_HOST'],array('wwwtest.boqii.com','itest.boqii.com','vettest.boqii.com','bbstest.boqii.com','mtest.boqii.com','ctest.boqii.com','utest.boqii.com','up.boqii.com','newstest.boqii.com','zhuantitest.boqii.com'))){
	//数据库
    $db_host = '172.16.76.252';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = 'VViVDwp7hW';
    //接口日志数据库
	$db_applog_host = '172.16.76.252';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_web_user';
    $db_applog_pwd = 'VViVDwp7hW';
    //redis
    $redis_host = '172.16.76.251';
	//接口日志redis
    $redis_applog_host = '172.16.76.251';
    $redis_port = '6379';
    $redis_prefix = 'test';
	//sphinx
	$sphinx_host = '172.16.76.251';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_test';
    $o2o_business = 'o2o_business_test';
    //网站域名
    $blog_dir = "http://wwwtest.boqii.com";
    $shop_dir = "http://v1.shoptest.boqii.com";
    $shop_api_dir = $shop_dir."/api/api.php";
	$shop_new_api_dir = "http://v1.test.shopapitest.boqii.com";
    $bbs_dir  = "http://bbstest.boqii.com";
    $i_dir = "http://itest.boqii.com";
    $bk_dir = "http://wwwtest.boqii.com/baike/";
    $img_dir = 'http://imgtest.boqiicdn.com';
	$img_upload_dir = 'http://imgtest.boqii.com';
    $c_dir = "http://ctest.boqii.com";
	$m_dir = 'http://mtest.boqii.com';
	$u_dir = 'http://utest.boqii.com';
	$v_dir = 'http://vtest.boqii.com';
    $static_dir = 'http://atest.boqiicdn.com';
    $vet_dir = 'http://v2.vettest.boqii.com';
    $news_dir="http://newstest.boqii.com";
    $zhuanti_dir = "http://zhuantitest.boqii.com";
	//标识
    $www_filename = "www";
	$env_flag = 'test';
	//物理路径
	$upload_file = '/webwww/bbs/Data/Bbs/Upload/Pushs/';
	$static_dir_path = '/webwww/static/www/';
	$resource_path = '/webwww/www/resource/';
	$api_log_vet_dir = '/webapplog/apilog/www/';
    $api_log_shop_dir = '/webapplog/apilog/shop/';
	$php_log_dir = 'D:/phpwork/cache/phplog/';
	$php_vet_log_dir = 'D:/phpwork/cache/phplog/Vet/Log/';
}elseif(in_array($_SERVER['HTTP_HOST'],array('wwwtest.boqii.com','itest.boqii.com','v1.vettest.boqii.com','bbstest.boqii.com','mtest.boqii.com','ctest.boqii.com','utest.boqii.com','up.boqii.com','newstest.boqii.com','zhuantitest.boqii.com'))){
	//数据库
    $db_host = '172.16.76.252';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = 'VViVDwp7hW';
	//接口日志数据库
	$db_applog_host = '172.16.76.252';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_web_user';
    $db_applog_pwd = 'VViVDwp7hW';
    //redis
    $redis_host = '172.16.76.251';
	//接口日志redis
    $redis_applog_host = '172.16.76.251';
    $redis_port = '6379';
    $redis_prefix = 'test';
	//sphinx
	$sphinx_host = '172.16.76.251';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_test';
    $o2o_business = 'o2o_business_test';
    //网站域名
    $blog_dir = "http://wwwtest.boqii.com";
    $shop_dir = "http://v1.shoptest.boqii.com";
    $shop_api_dir = $shop_dir."/api/api.php";
	$shop_new_api_dir = "http://v1.test.shopapitest.boqii.com";
    $bbs_dir  = "http://bbstest.boqii.com";
    $i_dir = "http://itest.boqii.com";
    $bk_dir = "http://wwwtest.boqii.com/baike/";
    $img_dir = 'http://imgtest.boqiicdn.com';
	$img_upload_dir = 'http://imgtest.boqii.com';
    $c_dir = "http://ctest.boqii.com";
	$m_dir = 'http://mtest.boqii.com';
	$u_dir = 'http://utest.boqii.com';
	$v_dir = 'http://vtest.boqii.com';
    $static_dir = 'http://atest.boqiicdn.com';
    $vet_dir = 'http://v2.vettest.boqii.com';
    $news_dir="http://newstest.boqii.com";
    $zhuanti_dir = "http://zhuantitest.boqii.com";
	//标识
    $www_filename = "www";
	$env_flag = 'test';
	//物理路径
	$upload_file = '/webwww/bbs/Data/Bbs/Upload/Pushs/';
	$static_dir_path = '/webwww/static/www/';
	$resource_path = '/webwww/www/resource/';
	$api_log_vet_dir = '/webapplog/apilog/www/';
    $api_log_shop_dir = '/webapplog/apilog/shop/';
	$php_log_dir = '/webapplog/phplog/www/';
	$php_vet_log_dir = '/webapplog/phplog/www/Vet/Log/';
}elseif(in_array($_SERVER['HTTP_HOST'],array('wwwtest.boqii.com','itest.boqii.com','v2.vettest.boqii.com','bbstest.boqii.com','mtest.boqii.com','ctest.boqii.com','utest.boqii.com','up.boqii.com','newstest.boqii.com','zhuantitest.boqii.com'))){
	//数据库
    $db_host = '172.16.76.252';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = 'VViVDwp7hW';
	//接口日志数据库
	$db_applog_host = '172.16.76.252';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_web_user';
    $db_applog_pwd = 'VViVDwp7hW';
    //redis
    $redis_host = '172.16.76.251';
	//接口日志redis
    $redis_applog_host = '172.16.76.251';
    $redis_port = '6379';
    $redis_prefix = 'test';
	//sphinx
	$sphinx_host = '172.16.76.251';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_test';
    $o2o_business = 'o2o_business_test';
    //网站域名
    $blog_dir = "http://wwwtest.boqii.com";
    $shop_dir = "http://v1.shoptest.boqii.com";
    $shop_api_dir = $shop_dir."/api/api.php";
	$shop_new_api_dir = "http://v1.test.shopapitest.boqii.com";
    $bbs_dir  = "http://bbstest.boqii.com";
    $i_dir = "http://itest.boqii.com";
    $bk_dir = "http://wwwtest.boqii.com/baike/";
    $img_dir = 'http://imgtest.boqiicdn.com';
	$img_upload_dir = 'http://imgtest.boqii.com';
    $c_dir = "http://ctest.boqii.com";
	$m_dir = 'http://mtest.boqii.com';
	$u_dir = 'http://utest.boqii.com';
	$v_dir = 'http://vtest.boqii.com';
    $static_dir = 'http://atest.boqiicdn.com';
    $vet_dir = 'http://v2.vettest.boqii.com';
    $news_dir="http://newstest.boqii.com";
    $zhuanti_dir = "http://zhuantitest.boqii.com";
	//标识
    $www_filename = "www";
	$env_flag = 'test';
	//物理路径
	$upload_file = '/webwww/bbs/Data/Bbs/Upload/Pushs/';
	$static_dir_path = '/webwww/static/www/';
	$resource_path = '/webwww/www/resource/';
	$api_log_vet_dir = '/webapplog/apilog/www/';
    $api_log_shop_dir = '/webapplog/apilog/shop/';
	$php_log_dir = '/webapplog/phplog/www/';
	$php_vet_log_dir = '/webapplog/phplog/www/Vet/Log/';
}elseif(in_array($_SERVER['HTTP_HOST'],array('www1.boqii.com','i1.boqii.com','vet1.boqii.com','bbs1.boqii.com','newbbs1.boqii.com','m1.boqii.com','c1.boqii.com','u1.boqii.com','news1.boqii.com','zhuanti1.boqii.com'))){
	//数据库
    $db_host = '192.168.22.10';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = '12DEAAD540A6A90294C6F42D31B3456';
	//接口日志数据库
	$db_applog_host = '192.168.22.10';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_web_user';
    $db_applog_pwd = '12DEAAD540A6A90294C6F42D31B3456';
    //redis
    $redis_host = '192.168.22.10';
	//接口日志redis
    $redis_applog_host = '192.168.22.10';
    $redis_port = '6379';
    $redis_prefix = 'release';
	//sphinx
	$sphinx_host = '192.168.22.10';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_release';
    $o2o_business = 'o2o_business_release';
    //网站域名
    $blog_dir = "http://www1.boqii.com";
    $shop_dir = "http://shop1.boqii.com";
    $shop_api_dir = $shop_dir."/api/api.php";
	$shop_new_api_dir = "http://shopapi1.boqii.com";
    $bbs_dir  = "http://bbs1.boqii.com";
    $i_dir = "http://i1.boqii.com";
    $bk_dir = "http://www1.boqii.com/baike/";
    $img_dir = 'http://img1.boqiicdn.com';
	$img_upload_dir = 'http://img1.boqii.com';
    $static_dir = 'http://a1.boqiicdn.com';
    $vet_dir = 'http://vet1.boqii.com';
    $c_dir = "http://c1.boqii.com";
	$m_dir = 'http://m1.boqii.com';
    $u_dir = 'http://u1.boqii.com';
	$v_dir = 'http://v1.boqii.com';
	$news_dir="http://news1.boqii.com";
    $zhuanti_dir = "http://zhuanti1.boqii.com";
	//标识
    $www_filename = "www1";
	$env_flag = 'release';
	//物理路径
	$upload_file = '/webwww1/bbs1/Data/Bbs/Upload/Pushs/';
	$static_dir_path = '/webwww1/static1/www/';
	$resource_path = '/webwww1/www1/resource/';
	$api_log_vet_dir = '/webapplog/apilog/www/';
    $api_log_shop_dir = '/webapplog/apilog/shop/';
	$php_log_dir = '/webapplog/phplog/www/';
	$php_vet_log_dir = '/webapplog/phplog/www/Vet/Log/';
} elseif (in_array($_SERVER['HTTP_HOST'], array('www.boqii.com','i.boqii.com','vet.boqii.com','bbs.boqii.com','m.boqii.com','c.boqii.com','u.boqii.com','news.boqii.com','zhuanti.boqii.com'))) {
	//数据库
    $db_host = '192.168.22.33';
    $db_name = 'hzkj_zh';
    $db_user = 'boqii_web_user';
    $db_pwd = 'a1UyVHFIHsld9COVViVDwp7hW6egJ3KdhM8YxgHXj4a';
	//接口日志数据库
	$db_applog_host = '192.168.22.7';
    $db_applog_name = 'applog';
    $db_applog_user = 'boqii_log_user';
    $db_applog_pwd = '1VViVDwp7hW';
    //redis
    $redis_host = '192.168.22.11';
	//接口日志redis
    $redis_applog_host = '192.168.22.7';
    $redis_port = '6379';
    $redis_prefix = 'online';
	//sphinx
    $sphinx_host = '192.168.22.11';
	$sphinx_port = '9312';
    //xs
    $o2o_coupon = 'o2o_coupon_online';
    $o2o_business = 'o2o_business_online';
    //网站域名
    $blog_dir = "http://www.boqii.com";
    $shop_dir = "http://shop.boqii.com";
    $shop_api_dir = "http://shopapiold.boqii.com/api/api.php";
	$shop_new_api_dir = "http://shopapi.boqii.com";
    $bbs_dir = "http://bbs.boqii.com";
    $vet_dir = "http://vet.boqii.com";
    $i_dir = "http://i.boqii.com";
    $bk_dir = "http://www.boqii.com/baike/";
    $img_dir = 'http://img.boqiicdn.com';
	$img_upload_dir = 'http://img.boqii.com';
    $c_dir = "http://c.boqii.com";
	$m_dir = 'http://m.boqii.com';
	$u_dir = 'http://u.boqii.com';
	$v_dir = 'http://v.boqii.com';
	$news_dir="http://news.boqii.com";
    $static_dir = 'http://a.boqiicdn.com';
    $zhuanti_dir = "http://zhuanti.boqii.com";
    $www_filename = "www";
	$env_flag = 'online';
    //附件地址
    $attach_url = 'http://bbs.boqii.com/attachments/';
    $ip_prefix = '192.168';
	$static_dir_path = '/webwww/static/www/';
	$resource_path = '/webwww/www/resource/';
	$api_log_vet_dir = '/webapplog/apilog/www/';
    $api_log_shop_dir = '/webapplog/apilog/shop/';
	$php_log_dir = '/webapplog/phplog/www/';
	$php_vet_log_dir = '/webapplog/phplog/www/Vet/Log/';
}elseif(in_array($_SERVER['HTTP_HOST'], array('apiloglocal.boqii.com','apilogtest.boqii.com','apilog.boqii.com'))){
	$db_host = 'db252.boqii.com';
	$db_name = 'hzkj_log';
	$db_user = 'hzkj_zh';
	$db_pwd = 'Vy55KqsHQDPdKnao9yXx8Nz2';
	$api_log_vet_dir = '/webapplog/apilog/www/';
    $api_log_shop_dir = '/webapplog/apilog/shop/';
	$php_log_dir = '/webapplog/phplog/www/';
	$php_vet_log_dir = '/webapplog/phplog/www/Vet/Log/';
}
$myconfig = array(
    'SHOW_PAGE_TRACE' => false,
    'LOG_RECORD' => true,
    'LOG_RECORD_LEVEL' => 'DEBUG,SQL,EMERG,ALERT,CRIT,ERR',
    'DB_TYPE'=> 'mysql',          // 数据库类型
    'DB_HOST'=> $db_host, // 数据库服务器地址
    'DB_NAME'=>$db_name,  // 数据库名称
    'DB_USER'=>$db_user, // 数据库用户名
    'DB_PWD'=>$db_pwd, // 数据库密码
	'DB_APPLOG_HOST'=> $db_applog_host, // 数据库服务器地址
    'DB_APPLOG_NAME'=>$db_applog_name,  // 数据库名称
    'DB_APPLOG_USER'=>$db_applog_user, // 数据库用户名
    'DB_APPLOG_PWD'=>$db_applog_pwd, // 数据库密码
    'DB_PORT'=>'3306', // 数据库端口
    'DB_PREFIX'=>'', // 数据表前缀
	//sphinx host
	'SPHINX_HOST' =>$sphinx_host,
	'SPHINX_PORT' =>$sphinx_port,
    //xs
    'XS_OBJECT' =>array($o2o_coupon,$o2o_business),
    //redis 缓存
    'REDIS_HOST' => $redis_host,
	'REDIS_APPLOG_HOST' => $redis_applog_host,
    'REDIS_PORT' => $redis_port,
    'REDIS_PREFIX' => $redis_prefix,
    'BLOG_DIR' => $blog_dir,
    'BBS_DIR' => $bbs_dir,
    'I_DIR' => $i_dir,
    'C_DIR' => $c_dir,
	'M_DIR' => $m_dir,
	'U_DIR' => $u_dir,
	'V_DIR' => $v_dir,
    'BK_DIR'=> $bk_dir,
    'SHOP_DIR' => $shop_dir,
    'SHOP_API_DIR' => $shop_api_dir,
	'SHOP_NEW_API_DIR' => $shop_new_api_dir,
    'IMG_DIR' => $img_dir,
	'IMG_UPLOAD_DIR' => $img_upload_dir,
    'STATIC_DIR'=>$static_dir,
	'STATIC_DIR_PATH'=>$static_dir_path,
    'ZHUANTI_DIR'=> $zhuanti_dir,
	'RESOURCE_PATH'=>$resource_path,
	'API_LOG_VET_DIR'=>$api_log_vet_dir,
	'API_LOG_SHOP_DIR'=>$api_log_shop_dir,
	'PHP_LOG_DIR'=>$php_log_dir,
	'PHP_VET_LOG_DIR'=>$php_vet_log_dir,
    'VET_DIR'=>$vet_dir,
	'NEWS_DIR'=>$news_dir,
    'URL_MODEL'=>2,
    'SESSION_EXPIRE'=>'43200',
    'WWW_FILENAME' => $www_filename,
	'URL_CASE_INSENSITIVE' => true, //忽略大小写
    'UPLOAD_FILE'=>$upload_file,
	'ENV_FLAG'=>$env_flag,
	'DEFAULT_FILTER' => 'trim',
	'ALIPAY_P_ID'=>'2088901895385808',
	'ALIPAY_APP_ID'=>'2015010800024152',
);
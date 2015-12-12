<?php
//服务器地址
if(in_array($_SERVER['HTTP_HOST'],array("ilocal.boqii.com", "wwwlocal.boqii.com"))) {
	$imgFilename = 'img';
	$wwwFilename = "svnwww";
	$wwwpushFilename = 'svnwww/data/indexview';
	$baikeFilename = 'svnwww/baike';
	$iFilename = 'svnuc';
}elseif(in_array($_SERVER['HTTP_HOST'],array('itest.boqii.com', "wwwtest.boqii.com"))){
	$imgFilename = 'img';
	$wwwFilename = "www";
	$wwwpushFilename = 'www/data/indexview';
	$baikeFilename = 'www/baike';
	$iFilename = 'uc';
} elseif(in_array($_SERVER['HTTP_HOST'],array("i1.boqii.com", "www1.boqii.com"))){
	$imgFilename = 'img1';
	$wwwFilename = "www1";
	$wwwpushFilename = 'www1/data/indexview';
	$baikeFilename = 'www1/baike';
	$iFilename = 'uc1';
} elseif(in_array($_SERVER['HTTP_HOST'],array("i.boqii.com", "www.boqii.com"))){
	$imgFilename = 'img';
	$wwwFilename = "www";
	$wwwpushFilename = 'www/data/indexview';
	$baikeFilename = 'www/baike';
	$iFilename = 'uc';
}
return array(
	//图片上传全局设置
	'IMAGE_UPLOAD' => array(
		'FILE_SIZE' => 2048, 
		'FILE_PATH' => $_SERVER['DOCUMENT_ROOT'] . "/", 
		'FILE_TYPE' => array(".gif",".png",".jpg",".jpeg",".bmp"),
		'IMG_FILENAME' => $imgFilename,
		'WWW_FILENAME' => $wwwFilename,
		'BAIKE_FILENAME' => $baikeFilename,
		'I_FILENAME' => $iFilename),
	//[百科]百科文章内容图片上传设置(个人中心后台，编辑器上传)
	'BAIKE_ARTICLE_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/BK/A', //百科文章目录
		'PATH_EXT' => 'TIME', //图片目录以时间扩展
		'WATERMARK' => 1, //加水印
		'COMPRESS' => 1, //压缩
		'IMAGE_SUFFIX' => '_y,_s',
		'UPLOAD_WIDTH_Y' => 620,
		'UPLOAD_WIDTH_S' => 120,
		'UPLOAD_HEIGHT_S' => 120
		),
	//[百科]百科文章封面图片上传设置(个人中心后台，异步上传)
	'BAIKE_ARTICLE_COVER_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/BK/A', //百科文章目录
		'PATH_EXT' => 'TIME', //图片目录以时间扩展
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 0 //不压缩
		),
	//[百科]百科帖子内容图片上传设置(百科前台，编辑器上传)
	'BAIKE_POST_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1,
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/BK/T', //百科帖子目录
		'PATH_EXT' => 'UID', //图片目录以用户UID扩展
		'WATERMARK' => 1, //加水印
		'COMPRESS' => 1, //压缩
		'IMAGE_SUFFIX' => '_y,_s',
		'UPLOAD_WIDTH_Y' => 580,
		'UPLOAD_WIDTH_S' => 120,
		'UPLOAD_HEIGHT_S' => 120),
	//[百科]百科分类封面图片上传设置(个人中心后台，异步上传)
	'BAIKE_CATEGORY_COVER_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/BK/C', //百科分类图片目录
		'PATH_EXT' => 'NO', //图片目录不需要扩展
		'WATERMARK' => 0, //加水印
		'COMPRESS' => 0 //压缩
		),
	//[百科]百科小组头像图片上传设置(个人中心后台，异步上传)
	'BAIKE_TEAM_COVER_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/BK/Team', //百科小组图片目录
		'PATH_EXT' => 'NO', //图片目录不需要扩展
		'WATERMARK' => 0, //加水印
		'COMPRESS' => 0 //压缩
		),
	//[百科]百科小组介绍图片上传设置(个人中心后台，编辑器)
	'BAIKE_TEAM_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/BK/Team', //百科小组图片目录
		'PATH_EXT' => 'NO', //图片目录不需要扩展
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 0 //不压缩
		),
	//[百科]百科专家头像图片上传设置(个人中心后台，异步上传)
	'BAIKE_EXPERT_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/BK/E', //百科专家头像目录
		'PATH_EXT' => 'NO', //图片目录不需要扩展
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 1,//压缩
		'INTERCEPT' => 1, //图片上传需要截取
		'IMAGE_SUFFIX' => '_b,_m,_s',
		'UPLOAD_WIDTH_B' => 80,
		'UPLOAD_HEIGHT_B' => 80,
		'UPLOAD_WIDTH_M' => 50,
		'UPLOAD_HEIGHT_M' => 50,
		'UPLOAD_WIDTH_S' => 30,
		'UPLOAD_HEIGHT_S' => 30
		),
	//[百科]百科/首页/个人中心推送图片上传设置(个人中心后台，异步上传)
	'BAIKE_PUSH_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/U/P',//推送图片目录
		'PATH_EXT' => 'NO', //图片目录不需要扩展
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 0 //不压缩
		),
	//[百科]百科广告图片上传设置(个人中心后台，异步上传)
	'BAIKE_ADS_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/U/ADS', //广告图片目录
		'PATH_EXT' => 'NO', //图片目录不需要扩展
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 0 //不压缩
		),

	//[波齐后台]首页推送图片上传设置(个人中心后台，异步上传)
	'WWW_PUSH_UPLOAD' => array(
		'DOMAIN' => 'BLOG_DIR',
		'PATH' => 1,
		'PATH_EXT' => 'YM',
		'FILENAME' =>$wwwpushFilename,
		'COMPRESS' => 0,//不压缩
		'WATERMARK' => 0 //不加水印
		),
	//[个人中心]个人中心日志图片上传设置
	'DIARY_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/U/D', //日志图片目录
		'PATH_EXT' => 'UID', //图片目录以用户UID扩展
		'WATERMARK' => 1, //加水印
		'COMPRESS' => 1, //压缩
		'IMAGE_SUFFIX' => '_y,_s',
		'UPLOAD_WIDTH_Y' => 750, 
		'UPLOAD_WIDTH_S' => 120,
		'UPLOAD_HEIGHT_S' => 120,
		'ALBUM_SYNC' => 1 //同步相册
		),
	//[个人中心]相册图片上传设置
	'ALBUM_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR',
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/U/A', //相册图片目录
		'PATH_EXT' => 'UID', //图片目录以用户UID扩展
		'WATERMARK' => 1, //加水印
		'COMPRESS' => 1, //压缩
		'IAMGE_COMPRESS' => 'MIN_EDGE', //以最小边压缩
		'IMAGE_SUFFIX' => '_y,_b,_m', 
		'UPLOAD_WIDTH_Y' => 750, 
		'UPLOAD_WIDTH_B' => 160, 
		'UPLOAD_HEIGHT_B' => 160, 
		'UPLOAD_WIDTH_M' => 78, 
		'UPLOAD_HEIGHT_M' => 78
		),
	//[个人中心]用户头像上传设置
	'USER_AVATAR_UPLOAD' => array(
		'DOMAIN' => 'BLOG_DIR', //用户头像使用主站地址
		'PATH' => 1,
		'FILENAME' =>$wwwFilename,
		'FILE_URL' => 'blog/upload', //用户头像目录
		'PATH_EXT' => 'UID', //图片目录以用户UID扩展
		'INTERCEPT' => 1, //图片上传需要截取
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 1, //压缩
		'IMAGE_SUFFIX' => '_b,_m,_s', 
		'UPLOAD_WIDTH_B' => 120,
		'UPLOAD_HEIGHT_B' => 120,
		'UPLOAD_WIDTH_M' => 50,
		'UPLOAD_HEIGHT_M' => 50,
		'UPLOAD_WIDTH_S' => 30,
		'UPLOAD_HEIGHT_S' => 30
		),
	//[个人中心]宠物头像图片上传设置
	'PET_AVATAR_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR', 
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/U/C', //宠物头像目录
		'PATH_EXT' => 'UID', //图片目录以用户UID扩展
		'INTERCEPT' => 1, //图片上传需要截取
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 1, //压缩
		'IMAGE_SUFFIX' => '_b,_m,_s',
		'UPLOAD_WIDTH_B' => 150,
		'UPLOAD_HEIGHT_B' => 150,
		'UPLOAD_WIDTH_M' => 120,
		'UPLOAD_HEIGHT_M' => 120,
		'UPLOAD_WIDTH_S' => 70, 
		'UPLOAD_HEIGHT_S' => 70
		),
	//[个人中心]微博图片上传设置
	'WEIBO_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR', 
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/U/W', //微博图片目录
		'PATH_EXT' => 'UID', //图片目录以用户UID扩展
		'IMAGE_SUFFIX' => '_y,_b,_s',
		'WATERMARK' => 1, //加水印
		'COMPRESS' => 1, //压缩
		'UPLOAD_WIDTH_B' => 500,
		'UPLOAD_WIDTH_S' => 120
	),
	//[个人中心]专题图片上传设置
	'SUBJECT_UPLOAD' => array(
		'DOMAIN' => 'IMG_DIR', 
		'PATH' => 1, 
		'FILENAME' =>$imgFilename,
		'FILE_URL' => 'Data/U/S', //微博图片目录
		'PATH_EXT' => 'YM', //图片目录以年月扩展
		'WATERMARK' => 0, //不加水印
		'COMPRESS' => 0 //不压缩
	),
	//水印设置
	'WATERMARK' => array(
		'WATERMARK_POSITION' => 9, 
		'WATERMARK_IMAGE_WIDTH' => 300, 
		'WATERMARK_IMAGE_HEIGHT' => 300, 
		'WATERMARK_IMAGE_TYPE' => '', 
		'WATERMARK_IMAGE_QUALITY' => 100),
	//上传错误设置
	'UPLOAD_ERROR' => array(
		'MSG_UPLOADFILENOSELECTERROR' => '请选择上传图片！',
		'MSG_MODULETYPRERROR' => '请设置上传模块类型！',
		'MSG_LOGINERROR' => '请登录后再上传图片！',
		'MSG_FILETYPEERROR' => '文件类型不符，不允许上传！', 
		'MSG_FILESIZEBIGERROR' => '请保持图片不超过2M！', 
		'MSG_UPLOADFALSEERROR' => '上传失败！', 
		'MSG_FILEPATHERROR' => '无法正确获取图像文件！',
		'MSG_INTERCEPTFAILERROR' => '截图失败！',
	),
);
?>
<?php
if (in_array($_SERVER['HTTP_HOST'], array("ilocal.boqii.com"))) {
} elseif (in_array($_SERVER['HTTP_HOST'], array("itest.boqii.com"))) {
} elseif (in_array($_SERVER['HTTP_HOST'], array("i1.boqii.com"))) {
} elseif (in_array($_SERVER['HTTP_HOST'], array("i.boqii.com"))) {
}
return array(
	'LOG_TYPE'=> 'File',
	'DEFAULT_ACTION' => 'indexSeo',
	'ALBUM_IMAGE_UPLOAD' => array(
		"uploadWidthY" => 750, //相册缩略图宽
		"uploadWidthB" => 160, //相册大缩略图宽
		"uploadHeightB" => 160, //相册大缩略图高
		"uploadWidthM" => 78, //相册中缩略图宽
		"uploadHeightM" => 78 //相册中缩略图高
	),
	'REDIS_KEY' => array(
		'userinfo'=>$redis_prefix.':public:userinfo:',
		'follow' => $redis_prefix.'uc:follow:',
		'fans' => $redis_prefix.'uc:fans:',
		'friend' => $redis_prefix.'uc:friend:',
		'black' => $redis_prefix.'uc:black:',
		'cityinfo'=>$redis_prefix.':public:cityinfo',
		'weibo'=>$redis_prefix.':uc:weibo',
		'userExtend'=>$redis_prefix.':user:extend'
	),
	'SPHINX_CONF'=>array(
		'1'=>'user'
	)
);
?>
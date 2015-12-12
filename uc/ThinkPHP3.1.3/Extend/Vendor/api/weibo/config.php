<?php
header('Content-Type: text/html; charset=UTF-8');

include_once('../../config/shop.db.conf.php');

define( "WB_AKEY" , '4030222662' );
define( "WB_SKEY" , '255ad0fbfe4f68e8cc47e1fcf07c5c96' );
define( "WB_CALLBACK_URL" , $shop_dir.'/api/weibo/callback.php' );
?>
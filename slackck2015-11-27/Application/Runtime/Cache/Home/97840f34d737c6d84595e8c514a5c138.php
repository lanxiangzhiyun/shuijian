<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<title>创建全国文明城市 建设和谐美丽龙岩</title>
<link rel="stylesheet" type="text/css" href="/newadmin/Public/civi/css/main.css" />
</head>

<body>

<div class="pic5"></div>
<div class="self_rank">
<span>当前分数：<?php echo ($num); ?>分 排名</span>
<span>第<strong><?php echo ($k); ?></strong>名</span>
</div>
<ul class="rank">
<li style=" background:none; "><span class="t1" style=" background:none; color:#333">排名</span><span class="t2">手机号码</span></li>
<?php if(is_array($user_order)): $i = 0; $__LIST__ = $user_order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><span class="t1"><?php echo ($i); ?></span><span class="t2"><?php echo (substr_replace($v["test_user_tel"],'*****',3,5)); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>

</ul>

<a class="change" href="123">重新答1题</a>


<div class="afoot"></div>





</body>
</html>
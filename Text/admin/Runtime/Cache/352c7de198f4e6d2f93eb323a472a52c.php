<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>联系方式 - 基业资本</title> 
<meta charset="utf-8" />
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="renderer" content="webkit">
<link rel="shortcut icon" type="image/x-icon" href="__IMAGES__/favicon.ico" />
<link rel="stylesheet" type="text/css" href="__CSS__/bootstrap.min.css?ver=<?php echo ($conf["site_cssver"]); ?>">
<link rel="stylesheet" type="text/css" href="__CSS__/common.css?ver=<?php echo ($conf["site_cssver"]); ?>">
<script type="text/javascript" src="__JS__/jquery-1.10.js?ver=<?php echo ($conf["site_cssver"]); ?>"></script>
<script type="text/javascript" src="__JS__/base.js?ver=<?php echo ($conf["site_cssver"]); ?>"></script>
<script src="__JS__/do.min.js" data-cfg-autoload="false"></script>
</head>
<body>
 
<div id="top">
  <div class="container">
 	
 		<?php if((MODULE_NAME) == "Index"): ?><div class="logo_container mt10" >
 		<a href="__APP__/Index"><h1><img style="position:relative;top:5px;" id="logo" src="__IMAGES__/jiye.png" alt="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司" title="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司"/></h1></a>
 		</div>
 		<?php else: ?>
 		<div class="logo_container mt15" >
 		<a href="__APP__/Index"><img id="logo" src="__IMAGES__/jiye.png" alt="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司" title="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司"/></a> 	
 		</div><?php endif; ?>
 	
 	<div class="nav_container">
 		<img id="banner_shade" src="__IMAGES__/banner_shade.png"/>
 		<ul>
 			<?php if((MODULE_NAME) == "Index"): ?><li class="active" rel="0"><a href="javascript:void(0);">首页</a></li>
 			<?php else: ?>
 			<li rel="0"><a href="__APP__/Index">首页</a></li><?php endif; ?>
<!--  			<?php if((MODULE_NAME) == "Treasure"): ?><li class="active" rel="1"><a href="__APP__/Treasure">财富管理</a></li>
 			<?php else: ?>
 			<li rel="1"><a href="__APP__/Treasure">财富管理</a></li><?php endif; ?>

 			<?php if(in_array((ACTION_NAME), explode(',',"news,notices,reports"))): ?><li class="active" id="jiye_medias" rel="2"><a href="javascript:void(0);">基业动态</a></li>
 			<?php else: ?>
 			<li  id="jiye_medias" rel="2"><a href="javascript:void(0);">基业动态</a></li><?php endif; ?>

 			<?php if((MODULE_NAME) == "Invest"): ?><li class="active" rel="3" ><a href="javascript:void(0);" >投资须知</a></li>
 			<?php else: ?>
 			<li rel="3" ><a href="__APP__/Invest" >投资须知</a></li><?php endif; ?> -->

 			<?php if((MODULE_NAME) == "Serve"): ?><li class="active" rel="1" ><a href="javascript:void(0);" >服务介绍</a></li>
 			<?php else: ?>
 			<li rel="1" ><a href="__APP__/Serve" >服务介绍</a></li><?php endif; ?>

<!--  			<?php if((MODULE_NAME) == "Safe"): ?><li class="active" rel="5"><a href="javascript:void(0);" >安全保障</a></li>
 			<?php else: ?>
 			<li rel="5"><a href="__APP__/Safe" >安全保障</a></li><?php endif; ?> -->

 			<?php if(in_array((ACTION_NAME), explode(',',"company,team,partner,connect"))): ?><li class="active" id="connect_us" rel="2"><a href="javascript:void(0);">联系我们</a></li>
 			<?php else: ?>
 			<li id="connect_us" rel="2"><a href="javascript:void(0);" rel="5">联系我们</a></li><?php endif; ?>
 		</ul>
 	</div>
  </div>
  <div class="second_menu second_menu1">
  		<div class="container">
	  		<ul class="ul-child" id="media_second">
	  			<i class="arrows arrows1"></i>

	  			<?php if((ACTION_NAME) == "notices"): ?><li class="active"><a href="javascript:void(0);">最新动态</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/Media/notices">最新动态</a></li><?php endif; ?>

	  			<?php if((ACTION_NAME) == "reports"): ?><li class="active"><a href="javascript:void(0);">媒体报道</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/Media/reports">媒体报道</a></li><?php endif; ?>


	  			<?php if((ACTION_NAME) == "news"): ?><li class="active"><a href="javascript:void(0);">行业新闻</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/Media/news">行业新闻</a></li><?php endif; ?>
	  		</ul>	  		
  		</div>
  </div>  
  <div class="second_menu second_menu2">
  		<div class="container">
	  		<ul class="ul-child" id="about_second" style="left: -199px;">
	  			<!-- <i class="arrows arrows2"></i> -->
	  			<i class="arrows arrows2" style="left:42px;"></i>

	  			<?php if((ACTION_NAME) == "company"): ?><li class="active"><a href="javascript:void(0);">公司简介</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/About/company">公司简介</a></li><?php endif; ?>

<!-- 	  			<?php if((ACTION_NAME) == "team"): ?><li class="active"><a href="javascript:void(0);">团队结构</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/About/team">团队结构</a></li><?php endif; ?>

	  			<?php if((ACTION_NAME) == "partner"): ?><li class="active"><a href="javascript:void(0);">合作伙伴</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/About/partner">合作伙伴</a></li><?php endif; ?> -->

	  			<?php if((ACTION_NAME) == "connect"): ?><li class="active"><a href="javascript:void(0);">联系方式</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/About/connect">联系方式</a></li><?php endif; ?>
 
	  		</ul>  		
  		</div>
  </div>
</div>

<script type="text/javascript">
 $(document).ready(function() {
 	var left = $(".nav_container ul li.active").attr('rel');
 	$("#banner_shade").css({'left':left*100+"px",'display':"block"});

 	$("#jiye_medias,.second_menu1").hover(function(){
 		$(".second_menu1").show();
 	},function(){
 		$(".second_menu1").hide();
 	})
 	$("#connect_us,.second_menu2").hover(function(){
 		$(".second_menu2").show();
 	},function(){
 		$(".second_menu2").hide();
 	}) 	
 });
</script>
<link rel="stylesheet" type="text/css" href="__CSS__/connect.css?ver=<?php echo ($conf["site_cssver"]); ?>">



 <div id="connect">
 	<div class="container">
 		<div class="col-xs-12">
 			<h3>媒体合作 </h3>
 			<p class="mt10">欢迎各界媒体机构或人士共同交流监督，如有报道、撰稿或相关咨询合作请联系： 兰小姐：Lanjie@Lty.vc</p>
 			<h3>市场合作 </h3>
 			<p class="mt10">欢迎品牌资源共享、友情链接、渠道共建或广告合作相关合作需求请联系：燕先生：YLL@Lty.vc</p>
<!--  			<h3>商务合作 </h3>
 			<p class="mt10">机构投资者/银行/担保公司/小额贷款/资产管理等金融机构渠道合作;相关商协会组织资源整合请联系：罗先生：King@Lty.vc</p> -->
 			<h3>公司地址 </h3>
 			<p class="mt10">总部：深圳市前海深港合作区前湾一路1号A栋201室</p>
			<p>惠州办事处：惠州市惠城区演达一路石湖苑1栋B座4楼</p> 			 			 			
 		</div>
 	</div>
 </div>



<script type="text/javascript">
 $(document).ready(function() {
 	$("#connect > .container").css('opacity', 1);
 });
</script>

	<div id="footer">
		<div class="container">
			<ul>
				<li><a href="__APP__/About/company">关于我们</a></li>
<!-- 				<li><a href="__APP__/Treasure">财富管理</a></li>
				<li><a href="__APP__/Media/notices">基业动态</a></li>
				<li><a href="__APP__/Invest">投资须知</a></li>
				<li><a href="__APP__/Safe">安全保障</a></li> -->
				<li><a href="__APP__/Serve">服务介绍</a></li>
				<li><a href="__APP__/About/connect">联系我们</a></li>
			</ul>
			<!-- <div class="kefu_phone mt40">客户服务中心热线：400-830-2015</div> -->
			<div class="kefu_phone mt20">客户服务中心热线：400-830-2015</div>
			<div class="beian mt5"> Copyright © 深圳市基业资本信息咨询有限公司  2014-2015  <a target="_blank" href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action">粤ICP备14057621号</a></div>
		</div>
	</div>
	</body>
</html>
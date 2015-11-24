<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>最新动态 - 详情页 -基业资本</title> 
<meta charset="utf-8" />
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="renderer" content="webkit">
<link rel="stylesheet" type="text/css" href="__CSS__/bootstrap.min.css?ver=<?php echo ($conf["site_cssver"]); ?>">
<link rel="stylesheet" type="text/css" href="__CSS__/common.css?ver=<?php echo ($conf["site_cssver"]); ?>">
<script type="text/javascript" src="__JS__/jquery-1.10.js?ver=<?php echo ($conf["site_cssver"]); ?>"></script>
<script type="text/javascript" src="__JS__/base.js?ver=<?php echo ($conf["site_cssver"]); ?>"></script>
<script src="__JS__/do.min.js" data-cfg-autoload="false"></script>
</head>
<body>
 
<div id="top">
  <div class="container">
 	<div class="logo_container mt15" >
 		<a href="__APP__/Index"><img id="logo" src="__IMAGES__/jiye.png"/></a> 
 	</div>
 	<div class="nav_container">
 		<img id="banner_shade" src="__IMAGES__/banner_shade.png"/>
 		<ul>
 			<?php if((MODULE_NAME) == "Index"): ?><li class="active" rel="0"><a href="javascript:void(0);">首页</a></li>
 			<?php else: ?>
 			<li rel="0"><a href="__APP__/Index">首页</a></li><?php endif; ?>
 			<?php if((MODULE_NAME) == "Treasure"): ?><li class="active" rel="1"><a href="__APP__/Treasure">财富管理</a></li>
 			<?php else: ?>
 			<li rel="1"><a href="__APP__/Treasure">财富管理</a></li><?php endif; ?>

 			<?php if(in_array((ACTION_NAME), explode(',',"news,notices,reports"))): ?><li class="active" id="jiye_medias" rel="2"><a href="javascript:void(0);">基业动态</a></li>
 			<?php else: ?>
 			<li  id="jiye_medias" rel="2"><a href="javascript:void(0);">基业动态</a></li><?php endif; ?>

 			<?php if((MODULE_NAME) == "Invest"): ?><li class="active" rel="3" ><a href="javascript:void(0);" >投资须知</a></li>
 			<?php else: ?>
 			<li rel="3" ><a href="__APP__/Invest" >投资须知</a></li><?php endif; ?>

 			<?php if((MODULE_NAME) == "Safe"): ?><li class="active" rel="4"><a href="javascript:void(0);" >安全保障</a></li>
 			<?php else: ?>
 			<li rel="4"><a href="__APP__/Safe" >安全保障</a></li><?php endif; ?>

 			<?php if(in_array((ACTION_NAME), explode(',',"company,team,partner,connect"))): ?><li class="active" id="connect_us" rel="5"><a href="javascript:void(0);">联系我们</a></li>
 			<?php else: ?>
 			<li id="connect_us" rel="5"><a href="javascript:void(0);" rel="5">联系我们</a></li><?php endif; ?>
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
	  		<ul class="ul-child" id="about_second">
	  			<i class="arrows arrows2"></i>

	  			<?php if((ACTION_NAME) == "company"): ?><li class="active"><a href="javascript:void(0);">公司简介</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/About/company">公司简介</a></li><?php endif; ?>

	  			<?php if((ACTION_NAME) == "team"): ?><li class="active"><a href="javascript:void(0);">团队结构</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/About/team">团队结构</a></li><?php endif; ?>

	  			<?php if((ACTION_NAME) == "partner"): ?><li class="active"><a href="javascript:void(0);">合作伙伴</a></li>
	  				<?php else: ?>
	  				<li><a href="__APP__/About/partner">合作伙伴</a></li><?php endif; ?>

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
<link rel="stylesheet" type="text/css" href="__CSS__/media.css?ver=<?php echo ($conf["site_cssver"]); ?>">
 <div id="notices_detail">
 	<div class="container" id="notice_detail">
 		<div class="col-xs-12">
	<!--  		<h3 class="new_title"><?php echo ($post["title"]); ?></h3>
	        <div class="notice_detail_content radius4">
	            <?php echo ($post["contents"]); ?>
	        </div> -->

			<h3 class="new_title">领投羊2015年端午假期期间业务受理及值班公告</h3>
	        <div class="notice_detail_content radius4">

<p>			</p><p><span style="color: rgb(51, 51, 51); font-family: 微软雅黑, sans-serif; font-size: 14px; line-height: 17px;">尊敬的领投羊用户：</span><br></p><p style=";margin-bottom:0;line-height:17px;background:white"><span style="font-size:12px;font-family:'微软雅黑','sans-serif';color:#333333;border:none windowtext 1px;padding:0">&nbsp; &nbsp; &nbsp;&nbsp;</span><span style="font-size: 14px; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">根据国务院办公厅的通知，2015年6月20日至6月22日为端午节公共假期，领投羊在假期期间的业务受理安排如下：</span></p><p style=";margin-bottom:0;text-align:justify;text-justify:inter-ideograph;text-indent:24px;line-height:150%;background:white"><span style="font-size: 14px; line-height: 150%; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">1.领投羊会安排客服人员进行值班，为客户解答相关问题，客服电话及在线值班时间为每天的09：00-18：00；</span></p><p style=";margin-bottom:0;text-align:justify;text-justify:inter-ideograph;text-indent:24px;line-height:150%;background:white"><span style="font-size: 14px; line-height: 150%; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">2.6月20日至6月22日这三天内的有提现需求的客户需注意：根据第三方资金托管机构汇潮支付有限公司的提现安排，假期期间超过50000元的提现操作，需到节后的第一个工作日即6月23日到账；假期期间不超过50000元的提现操作，在每天08：00-16：00时段提现的当天到账，其他时段提现次日到账；</span></p><p style=";margin-bottom:0;text-align:justify;text-justify:inter-ideograph;text-indent:24px;line-height:150%;background:white"><span style="font-size: 14px; line-height: 150%; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">3.&nbsp;6月20日至6月22日假期期间领投羊会安排轮岗人员审核借款项目，平台将不定时发布借款项目；</span></p><p style=";margin-bottom:0;text-align:justify;text-justify:inter-ideograph;text-indent:24px;line-height:150%;background:white"><span style="font-size: 14px; line-height: 150%; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">4.&nbsp;6月20日至6月22日假期期间充值、借款项目到期付息还本等业务均不受影响，一切照常。&nbsp;&nbsp; </span><span style="font-size:12px;line-height:150%;font-family:'微软雅黑','sans-serif';color:#333333;border:none windowtext 1px;padding:0">&nbsp; </span></p><p style=";margin-bottom:0;text-align:justify;text-justify:inter-ideograph;text-indent:24px;line-height:150%;background:white"><span style="font-size: 14px; line-height: 150%; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">感谢您一直以来对领投羊的支持，提前祝您端午节快乐！</span></p><p style=";margin-bottom:0;line-height:17px;background:white"><span style="font-size: 14px; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">&nbsp;</span></p><p style="margin-bottom: 0px; line-height: 17px;-color: white; text-align: right;-position: initial initial;-repeat: initial initial;"><span style="font-size:12px;font-family:'微软雅黑','sans-serif';color:#333333;border:none windowtext 1px;padding:0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 14px; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); padding: 0px;">&nbsp;领投羊团队</span></span></p><p style="margin-bottom: 0px; line-height: 17px;-color: white; text-align: right;-position: initial initial;-repeat: initial initial;"><span style="font-size: 14px; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2015年6月18日</span></p><p style="text-align: right;">&nbsp;</p><p style="margin-bottom: 0px; line-height: 17px;-color: white; text-align: right;-position: initial initial;-repeat: initial initial;"><span style="font-size: 12px; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); border: 1px none windowtext; padding: 0px;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<span style="font-size: 14px; font-family: 微软雅黑, sans-serif; color: rgb(51, 51, 51); padding: 0px;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></span></p><p>		</p>

	        </div>

 		</div>
 	</div>
 </div>
<script type="text/javascript">
 $(document).ready(function() {
 
 });
</script>

	<div id="footer">
		<div class="container">
			<ul>
				<li><a href="__APP__/About/company">关于我们</a></li>
				<li><a href="__APP__/Treasure">财富管理</a></li>
				<li><a href="__APP__/Media/notices">基业动态</a></li>
				<li><a href="__APP__/Invest">投资须知</a></li>
				<li><a href="__APP__/Safe">安全保障</a></li>
				<li><a href="__APP__/About/connect">联系我们</a></li>
			</ul>
			<div class="kefu_phone mt40">客户服务中心热线：400-830-2015</div>
			<div class="beian mt5"> Copyright © 深圳市基业资本信息咨询有限公司  2014-2015  粤ICP备14057621号</div>
		</div>
	</div>
	</body>
</html>
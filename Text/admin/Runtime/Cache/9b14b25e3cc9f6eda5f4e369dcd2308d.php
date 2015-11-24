<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>安全保障 - 基业资本</title> 
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
<link rel="stylesheet" type="text/css" href="__CSS__/safe.css?ver=<?php echo ($conf["site_cssver"]); ?>">
 
 <div id="safe_container">
 	<div class="container">
 		<div id="zijin">
 		<h3>资金安全</h3>
 		<p>账户托管：第三方支付机构为基业资本建立了专属账户，对所有资金进行托管，确保资金安全。</p>
 		<p>风险控制：基业资本精选顶级阳光私募与优质机构进行合作，为用户提供最优质的投资项目。</p>
 		<p>风险备用金：基业资本设立风险备用金，保障用户的利益不受损失。</p>
 		</div>
 		<div id="yinsi">
 			<h3>隐私安全</h3>
 			<p>基业资本凭借严格的用户信息操作规定，从流程上保障用户信息安全。基业资本与第三方机构合作，要求其遵守相关协议，保障用户隐私不受侵犯基业资本绝不将用户信息泄露，除非出现如下情况应国家法律法规的要求应政府机关、司法检察机关及相关部门的要求。</p>
 		</div>
 		<div id="leval">
 			<h3>阳光合法，法律护航</h3>
			<p>基业资本将把具有法律效力的纸质合同备案等法律证据，委托广东国欣律师事务所进行第三方资料存管。即使在极端情况下，平台不复存在，投资人仍能够顺利实现权利主张。</p>
 		</div>
 		<div id="bank_safe">
	 		<h3>银行级数据安全</h3>
			<p>基业资本平台软件系统均为自主研发，拥有高新知识产权，为平台用户提供银行级的数据安全与隐私信息保护。采用国际领先的系统加密及保护技术，并由专人24小时严密监控；三层防火墙隔离系统的访问层、应用层和数据层集群；有效的入侵防范及容灾备份，确保交易数据安全无虞。</p>
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
			<div class="beian mt5"> Copyright © 深圳市基业资本信息咨询有限公司  2014-2015  <a target="_blank" href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action">粤ICP备14057621号</a></div>
		</div>
	</div>
	</body>
</html>
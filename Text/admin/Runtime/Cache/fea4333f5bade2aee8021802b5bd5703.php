<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>公司简介 - 基业资本</title> 
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
<link rel="stylesheet" type="text/css" href="__CSS__/company.css?ver=<?php echo ($conf["site_cssver"]); ?>">

<div id="company">
 	<div class="container">
 		<div class="col-xs-12">
 			 <h3 class="mb5">深圳市基业资本信息咨询有限公司 </h3>
 			  <p class="mb30">成立于中国金融改革特区--深圳前海，实缴注册资本2000万元，专注于从事投资咨询、项目策划、资本运作等业务的专业顾问公司。公司凭借良好的信誉和资深专业的经验，已成功为多家国内外企业和个人提供了卓有成效的投资咨询服务。</p>
 		<!--	 <p class="mb30">成立于中国金融改革特区——深圳前海，实缴注册资本2000万元，专注于发展互联网金融领域的商业模式与产品创新。公司致力于给社会大众人群提供可信赖的互联网理财服务。通过高效的互联网信息技术手段以及专业的风险控制能力，促进投融资金的双赢流通，实现社会大众财富增值的共同愿望。</p>
  	 		 <h3 class="mb5">平台优势 </h3>
 	 		 <p class="mb30">基业资本由一群相信专业，尊重知识，精于执行的精英主义者组成，核心团队成员来自创新型互联网企业、大型金融机构、知名会计师事务所、NGO商协会组织以及信息技术安全组织。深度全面的金融产品研究结合互联网高效聚合、透明便捷的特点，使得通过基业资本审核并在线上平台交易的理财项目兼具了门槛更低，资金灵活性更高、收益更可观、操作更便捷等特点，真正满足广大用户的理财需求。我们将勇敢背负投资用户、融资客户以及合作伙伴的希冀——成为最值得信任的”互联网投资银行”，为您铺平钱路。</p>
 	 		 <h3 class="mb5">基业Style</h3>
 	 		 <p>基业资本的创立与发展，顺应了新一届政府领导经济转型与金融变革的历史潮流。互联网技术正在打破过去金融权利高度集中的固有格局，投资者将在互联网金融高歌猛进的发展道路上，拥有更多金融活动中的参与权、话语权，同时获得比线下理财更加透明、便捷，同时收益更加可观的财富增值服务。而同为达成财务发展、实现个人价值的融资用户，也能获得比传统渠道更低成本，更高效率的资金融通服务。让资本在阳光下健康稳定地成长，实现融投共赢，藏富于民的美好愿景。</p> -->
 		
 		</div>
 	</div>
</div>
 


<script type="text/javascript">
 $(document).ready(function() {
 	$("#company > .container").css('opacity', 1);
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
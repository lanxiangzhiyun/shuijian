<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司</title> 
<meta name="keywords" content="基业资本,深圳市基业资本,金融理财,投资理财" />
<meta name="description" content="深圳市基业资本信息咨询有限公司是一家综合金融服务公司。公司集投资咨询、资产管理、财富管理、风险管理为一体,专注于发展互联网金融领域的商业模式与产品创新。" />
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
<link rel="stylesheet" type="text/css" href="__CSS__/index.css?ver=<?php echo ($conf["site_cssver"]); ?>">
<div id="banner"><div class='container'></div></div>
<div id="achievement" class="container">
	<div class='col-xs-12 text-center'> <img src="__IMAGES__/cj.png" /> </div>
	<div id="ach_container" class="col-xs-12 mt30">
		<div id="user_num">	<div class="title">高净值服务用户达到</div><div id="user_count" class="text-center">0</div></div>
		<div id="pre_income"><div class="title">已为投资人额外赚取</div><div id="all_interest" class="text-center">0.00</div></div>
		<div id="all_money"><div class="title">优质项目成交额已达</div><div id="total_amount" class="text-center">0.00</div></div>
	</div>
</div>
<div id="intro">
<div class="container">
	<div class='col-xs-12 text-center'> <img src="__IMAGES__/company_intro.png" /> </div>
	<div class='col-xs-12'>
		<p class="mt40">基业资本是一家成立于中国金融改革特区-深圳前海自贸区的综合金融服务公司，公司集投资咨询、资产管理、财富管理、风险管理为一体，专注于发展互联网金融领域的商业模式与产品创新，致力于给社会大众人群提供可信赖的理财服务。通过匹配长期战略性、中期财务性、短期流动性三个阶段的投资，为客户提供全面稳健的资本市场解决方案。</p>
		<p>我们坚持以“开放、平等、协作、分享”的精神，使金融服务更加透明、便捷、高效，真正具有革命意义地促进交易主体、交易结构的变化，使金融回归民主化，通过高效的信息技术手段及专业的风险控制能力，达成投融资金的长远共赢，实现社会大众财富增值的共同愿望。</p>
	</div>
</div>
</div>
<div class="bgrey"></div>
<div id='media'>
	<div class='container'>
		<div class='col-xs-6'>
			<!--搜索基业最新动态的前四条-->
			<h3>Trends&nbsp;&nbsp;基业动态<a href="__APP__/Media/notices"><i class="more"></i></a></h3>
			<div class="medias"><span class="max"><a href="http://gd.sina.com.cn/hz/social/2015-07-03/155617273.html" target="_blank" rel="nofollow">深圳基业资本获广东省守合同重信用企业称号</a></span><span>2015/7/3</span></div>
			<div class="medias"><span class="max"><a href="http://mt.sohu.com/20150619/n415322625.shtml" target="_blank" rel="nofollow">基业资本创新互联网理财超市，便捷性优势突出</a></span><span>2015/6/19</span></div>
			<div class="medias"><span class="max"><a href="http://ln.sina.com.cn/fs/economy/2015-06-19/152018033.html" target="_blank" rel="nofollow">基业资本携手滴滴打车百万红包大派送</a></span><span>2015/6/19</span></div>
			<div class="medias"><span class="max"><a href="http://js.ifeng.com/business/landmark/detail_2015_06/11/3995831_0.shtml" target="_blank" rel="nofollow">金融脱媒如野马，保本型产品弯道截杀P2P</a></span><span>2015/6/11</span></div>
		</div>
		<div class='col-xs-6'>
			<h3>Partners&nbsp;&nbsp;合作伙伴<a href="__APP__/About/partner"><i class="more"></i></a></h3>
 			<div class="partners">
 			<img class="mr20" src="__IMAGES__/yuec.jpg"/>
 			<img class="mr20" src="__IMAGES__/hanxin.jpg"/>
 			<img src="__IMAGES__/pingan.jpg"/>
 			</div>
		</div>
	</div>
</div>
<div class="bgrey"></div>


<script type="text/javascript">
 $(document).ready(function() {
 	//引用countUp模块
 	Do("countUp",function(){ 
 		/*计数器-动画*/
        var count=function(num,id,prefix,suffix,digit){
            var options = {
            useEasing : true, 
            useGrouping : true, 
            separator : ',', 
            decimal : '.' ,
            prefix : prefix,
            suffix : suffix 
            }
            var demo = new countUp(id, 0, num, digit, 2, options);      
            demo.start();  
        }

        count($.formatNum('<?php echo ($data["all_interest"]); ?>'),"all_interest",'￥','',2);
        count($.formatNum('<?php echo ($data["total_amount"]); ?>'),"total_amount",'￥','',2); 
        count('<?php echo ($data["user_count"]); ?>',"user_count",'','位',0);	
	
 	});

 

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
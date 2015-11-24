<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>稳健计划_固定收益类产品_固定收益理财_固定收益理财产品-基业资本</title> 
<meta name="keywords" content="固定收益理财,固定收益类产品,固定收益类理财产品,固定收益理财产品,固定收益产品" />
<meta name="description" content="固定收益稳健计划以安全稳健，追求绝对收益为原则，涵盖多种固定收益类理财产品与项目的资产管理集合计划，轻松享受年化收益率6.8%至12.6%的高额收益。" />
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
 		<?php if((MODULE_NAME) == "Index"): ?><a href="__APP__/Index"><h1><img id="logo" src="__IMAGES__/jiye.png" alt="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司" title="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司"/></h1></a>
 		<?php else: ?>
 		<a href="__APP__/Index"><img id="logo" src="__IMAGES__/jiye.png" alt="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司" title="基业资本_金融理财_投资理财-深圳市基业资本信息咨询有限公司"/></a><?php endif; ?>
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
<link rel="stylesheet" type="text/css" href="__CSS__/treasure.css?ver=<?php echo ($conf["site_cssver"]); ?>">

<div id="treasure_taoli">
	<div class="container">
 		<h3 class="mb20"><a class="treasure_tag" href="__APP__/Treasure">财富管理</a>&nbsp;>&nbsp;固定收益&nbsp;&nbsp;稳健计划</h3>
 		<div id="gu_container" class="col-xs-12">
 			<h3 class="title">资产硬增值&nbsp;&nbsp;&nbsp;&nbsp;<small>横跨熊市牛市·无惧经济波动</small></h3>
 			<p class="mt10">以安全稳健，追求绝对收益为原则，涵盖多种固定收益理财产品与项目的资产管理集合计划，轻松享受年化6.8%至12.6%的高额收益。</p>
 			<div class="col-xs-12 mb30 nopadding" style="height:110px">
 				<div class="col-xs-3 nopadding"><img class="gu_adv_img" src="__IMAGES__/gu1.png" /><div class="gu_adv"><h3>普惠门槛</h3><p>1万元享100万元级产品</p></div></div>
 				<div class="col-xs-3 nopadding"><img class="gu_adv_img" src="__IMAGES__/gu2.png" /><div class="gu_adv"><h3>收益更高</h3><p>轻松超过活期利率50倍</p></div></div>
 				<div class="col-xs-3 nopadding"><img class="gu_adv_img" src="__IMAGES__/gu3.png" /><div class="gu_adv"><h3>资金灵活</h3><p>投资30天起即可申请退出</p></div></div>
 				<div class="col-xs-3 nopadding"><img class="gu_adv_img" style="position:relative;top:-15px;" src="__IMAGES__/gu4.png" /><div class="gu_adv"><h3>本息安全</h3><p>100%保本或有限风险自由选择</p></div></div>
 			</div>
 			<div class="col-xs-12 gu_trend nopadding mb40">
 				<div class="trend_img"><img src="__IMAGES__/zeng.png"/></div>
 				<div class="treend_dec"><p>稳健计划包含了5种投资产品类型，投资者可依偏好选择多种投资产品，基业资本将按照同期最佳收益、提高资金利用率等策略进行资产配置。</p></div>
 			</div>
 			<h3 class="title mt20">产业宝&nbsp;&nbsp;&nbsp;&nbsp;<small>坐享大品牌产业分红</small></h3>
 			<p class="mt10">产业宝是针对阳光行业产业链提供的上下游金融服务，投资者通过众筹投资促进产业链的订单周转，共同获得经营分红。</p>
 			<h4 class="subtitle mb15">交易模式</h4>
 			<div class="col-xs-12 jiaoyi nopadding mb40">
 				<div class="jiaoyi_img"><img src="__IMAGES__/jiaoyi.png"/></div>
 				<div class="jiaoyi_dec"> 
 					<h4>七星级风控保障</h4>
 					<p>· 先有订单后投资，经营增长可体现，利润有保障</p>
 					<p>· 永续性行业大牌产业链，AA级信用企业入驻，实力有保障</p>
 					<p>· 商品库存由核心企业共管仓库质押控制，变现有保障</p>
 					<p>· 共享ERP进销存数据，高频率订单跟进，稳定有保障</p>
 					<p>· 中下游企业商圈关联方承担连带担保，信用有保障</p>
 					<p>· 订单结款逾期，核心企业足额回购，周转有保障</p>
 					<p>· 第三方担保机构承担连带担保责任，安全有保障</p>
 				</div>
 			</div> 	
 			<h4 class="subtitle mb15">产品介绍</h4>
 			<p>快消品产业链: 项目为大众熟悉的百事、康师傅等多家知名品牌的旗下渠道订单。快消品产业链具有高强度的抗风险性，在国家“促内需、稳增长、惠民生”系列政策的作用下，2014年广东省全省批发零售业销售额98857.85亿元，增长16.4%，历史以来的经济周期性波动，均难以影响产业发展。</p>	
 			<div class="text-center pro_img mt20 mb20"><img src="__IMAGES__/kuai.png" /></div>	 
 			<p>黄金珠宝产业链：投资项目通过黄金实物加工和售卖周转运作，经线上线下销售分享利润。基于黄金所特有的强大国际流通性，保证了投资变现的便捷性。目前已对接多家黄金产业的亚洲品牌500强企业，核心企业已建立遍及国内20余个省市的业内领先的全方位综合服务网络。</p>
 			<div class="text-center pro_img mt20 mb20"><img src="__IMAGES__/zhubao.png" /></div>	
 			<h3 class="title">优选包&nbsp;&nbsp;&nbsp;&nbsp;<small>精选A级优质资产组合</small></h3>
 			<p class="mt10">优选包是基于现有稳健计划的项目类型，从中精选A级以上资产包组成的理财集合项目，通过设定的分散投资原则，控制固定投资期限内资产流动性，提高资金增值效率。</p>
 			<div class="col-xs-12 youxuan nopadding mb40 mt20">
 				<div class="youxuan_img"><img src="__IMAGES__/youxuanbao.png"/></div>
 				<div class="youxuan_dec"> 
 					<h4>四重安全垫·层层剥离风险</h4>
 					<p>· 优质资产分拆重组，资产评估值50%至60%</p>
 					<p>· 由合作资产管理机构到期全额回购</p>
 					<p>· 担保机构承担100%本息连带责任担保</p>
 					<p>· 基业资本提供风险储备金垫付服务，优先保护投资人权益</p>
 				</div>
 			</div>  
 			<h3 class="title">恒稳健&nbsp;&nbsp;&nbsp;&nbsp;<small>收益翻倍的同结构保本型理财产品</small></h3>
 			<p class="mt10">恒稳健是经中国证监会备案，由信托公司、基金公司或券商发行的优先级保本型理财项目，劣后级资金作为安全垫保障本息，通过省去中间发行渠道、销售渠道及交易费用，大幅提高投资收益率。</p> 		
 			<div class="col-xs-12 wenjian nopadding">
 				<div class="col-xs-6 nopdl">
 					<h4 class="subtitle mb20">恒稳健直接发行模式</h4>
 					<div class="text-center"><img src="__IMAGES__/benxi.png" /></div>
 					<div class="mt20"><p>传统模式下，产品发行需要经过多层中间销售渠道，且优先级份额一般由银行、保险公司或财务公司等机构投资者认购，极少在市面流通。恒稳健采用直接发行模式，多方监管多方保障，使投资者充分享有优先级份额特权。</p></div>
 				</div>

 				<div class="col-xs-6 nopdr tuoguan mb30">
 					<h4 class="subtitle mb20">托管与发行机构</h4>
 					<div class="text-center"><img src="__IMAGES__/tuoguan.png" /></div>
 					<h4 class="subtitle" style="font-size:16px;">100%保息</h4>
 					<div>
 						<p>·结构化优先级份额，投资者无论项目盈亏均获得固定收益</p>
 						<p>·第三方信托机构预警监管，强制平仓，规避系统性风险</p>
 						<p>·劣后资金先行偿付，厚实安全垫足额覆盖风险，保障绝对收益</p>
 						<p>·项目方提供足额金融资产质押，并由担保机构承担全额回购与本息垫付连带责任</p>
 						<p>·银行资金存管，券商交易监管，顶级信托风控监管，三方联合监管</p>
 						<p>·基业资本提供风险储备金垫付服务，优先保护投资人权益</p>
 					</div>
 				</div>
 			</div>		
 			<h3 class="title">月利通&nbsp;&nbsp;&nbsp;&nbsp;<small>抵押+担保双保险债券投资</small></h3>
 			<p class="mt10">月利通项目是通过多重控审核通过的，信用良好的小微企业及个人债券债权项目。秉承小额分散原理点对点出借获得利息，并由合作担保机构为每一笔项目承担100%的连带担保责任进行全额垫付。</p>		

 			<div class="col-xs-12 yueli nopadding mb40 mt20">
 				<div class="yueli_img"><img src="__IMAGES__/yueli.png"/></div>
 				<div class="yueli_dec"> 
 					<h4>资金保护 -  100%本息担保</h4>
 					<p>·合作担保机构为项目承担100%的连带担保责任</p>
 					<p>·基业资本风险储备金优先保护投资人权益</p>
 					<h4>严格风控 - 房产、车产抵押</h4>
 					<p>·专业评估：独立的第三方评估公司，权威估值</p>
 					<p>·低抵押率：住房、车辆不超过70%，商铺不超过50%</p> 
 					<p>·委托处理：抵押物已办理委托处理书，可快速变现</p> 
 				</div>
 			</div>  

 			<h3 class="title">淘转利&nbsp;&nbsp;&nbsp;&nbsp;<small>淘出额外收益</small></h3>
 			<p class="mt10">淘转利是基业资本的投资者将其投资在固定收益·稳健计划中的项目所持有的份额，部分或全额进行转让的自由交易市场，项目有着不同的折价或溢价，即投即计息，无需等待募集期满。系统自动抓取同期折价幅度最大项目并投资受让资产，获得额外差价收益。</p>
 			<div class="col-xs-12 text-center mb45"><img src="__IMAGES__/zhuanli.png" /></div>

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
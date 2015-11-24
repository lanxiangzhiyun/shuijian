<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>套利宝_分级基金_分级基金套利_基金理财_基金投资理财-基业资本</title> 
<meta name="keywords" content="套利宝,分级基金,分级基金套利,基金理财,基金投资,基金投资理财,理财基金" />
<meta name="description" content="套利宝投向分级基金优先级及中间级份额,通过劣后级资金+资产质押+担保机构+风险储备金多重保障保护投资者资金安全,是一款低风险甚至无风险撬动高收益的套利产品。" />
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
 		<h3 class="mb20"><a class="treasure_tag" href="__APP__/Treasure">财富管理</a>&nbsp;>&nbsp;套利宝&nbsp;&nbsp;浮动收益进取计划</h3>
 		<div id="taoli_container" class="col-xs-12">
 		<h3>产品说明 </h3>
 		<table class="table mt10">
 			<tr><td>项目投向</td><td>选择套利宝A/B：认购阳光私募基金全优先级份额  |  选择套利宝C/D：认购阳光私募基金全中间级份额</td></tr>
 			<tr><td>认购金额</td><td>1万元起超低认购门槛，以1万元每单位递增</td></tr>
 			<tr><td>项目期限</td><td>6个月/12个月</td></tr>
 			<tr><td>起购金额</td><td>10000元起购，以万元正倍数递增，产品净值初始1元/份</td></tr>
 			<tr><td>募集规模</td><td>100万元起</td></tr>
 			<tr><td>收益类型</td><td>浮动收益</td></tr>
 			<tr><td>计息日</td><td>计划成立后下1个工作日</td></tr>
 			<tr><td>警戒线</td><td>净值达到0.9触发警戒线，操盘方只能平仓不能建仓，并需及时补充劣后资金，保护投资人本息安全</td></tr>
 			<tr><td>平仓线</td><td>净值达到0.8触发平仓线，由信托公司强制平仓,并由劣后资金偿付亏损</td></tr>
 			<tr><td>结构型</td><td>优先级：中间级：劣后级=5：2：3</td></tr>
 			<tr><td>本息保障</td><td>套利宝A：保障100%本金及5%收益  |  套利宝B：保障100%本金  |  套利宝C：保障95%本金  |  套利宝D：保障90%本金 </td></tr>
 			<tr><td>退出赎回</td><td>持有31天起可随时转让退出</td></tr>
 		</table>
 			<div class="col-xs-6 nopadding mb25">
 				<h3>历史平均收益 </h3>
 				<img class="mt10" src="__IMAGES__/zhe.png" />
 			</div>
 			<div class="col-xs-6 nopdr mingci mb25">
 				<h3>分级基金名词解释 </h3>
 				<p class=" mt15 bigger">基金的优先级份额</p>
 				<p>保本型理财产品，极少流入市面，一般由银行、保险公司或财务公司等机构投资者认购，<strong>认购套利宝A或套利宝B即可持有。</strong></p>
 				<p class="bigger">基金的中间级份额</p>
 				<p>小额风险撬动高额分红的平衡性产品，较少在市面公开发行，一般由第三方理财公司、投资管理公司等机构投资者认购，<strong>认购套利宝C或套利宝D即可持有。</strong></p>
 				<p class="bigger">基金的劣后级份额</p>
 				<p>发生亏损风险时，需优先赔付给中间级与优先级份额投资者，产生收益时获得额外分红，市面发行较多的常见级别，<strong>可通过市面公开发行渠道认购。</strong></p>
 			</div>
 			<h3>四重监管措施 </h3>
 			<div class="col-xs-12 nopadding taoli_safe">
 				<div class="col-xs-6 mt20 nopdl">
 					<h4 class="safe_title">中国证监会</h4>
 					<p>中国证监会备案，资金专户托管，投资者可自己查询产品净值</p>
 				</div>
 				<div class="col-xs-6 mt20 nopdr">
 					<h4 class="safe_title">银行资金托管</h4>
 					<p>由商业银行开设托管专户托管，管理人不得提现挪用</p>
 				</div> 	
 				<div class="col-xs-6 mb40 nopdl">
 					<h4 class="safe_title">信托风控监管</h4>
 					<p>信托公司强制执行预警线与平仓线操作止损</p>
 				</div> 	
 				<div class="col-xs-6 mb40 nopdr">
 					<h4 class="safe_title">劵商监管</h4>
 					<p>券商交易限制，不得投资高风险证券</p>
 				</div> 	 	 				 							
 			</div>	
 			<h3>五大安全保障机制 </h3>
 			<div class="col-xs-12 nopadding">

	    		<div class="taoli_safe_contianer mt20" >
	    			<div class="taoli_safe_child mt10">
	    				<img src="__IMAGES__/safe1.png"/>
	    				<div class="taoli_safe_dec mt10">
	    					<h4>第一重 :强制平仓机制</h4>
	    					<p>强制平仓机制，保证了整支基金亏损不会扩大，一旦触及警戒线，操盘手需补充劣后资金来保证套利宝投资者的资金安全。</p>
	    				</div>
	    			</div>
	    			<div class="taoli_safe_child mt10">
	    				<img src="__IMAGES__/safe2.png"/>
	    				<div class="taoli_safe_dec mt10">
	    					<h4>第二重: 劣后资金偿付风险</h4>
	    					<p>如果基金产生亏损，套利宝投资者的本金与收益由劣后资金偿付，劣后资金额度与风险额度比例为3:2，绰绰有余覆盖风险。</p>
	    				</div>
	    			</div>
	    			<div class="taoli_safe_child mt10">
	    				<img src="__IMAGES__/safe3.png"/>
	    				<div class="taoli_safe_dec mt10">
	    					<h4>第三重 :担保机构100%本息担保</h4>
	    					<p>投资人本金与收益由第三方担保机构承担100%连带责任，为投资保障全程护航。</p>
	    				</div>
	    			</div>	    
	    			<div class="taoli_safe_child  mt10">
	    				<img src="__IMAGES__/safe4.png"/>
	    				<div class="taoli_safe_dec mt10">
	    					<h4>第四重: 足额资产质押回购</h4>
	    					<p>合作资产管理机构提供足额金融资产作为反担保质押物，并承担100%回购责任，加固产品结构化安全性。</p>
	    				</div>
	    			</div>
	    			<div class="taoli_safe_child mt10">
	    				<img src="__IMAGES__/safe5.png"/>
	    				<div class="taoli_safe_dec mt10">
	    					<h4>第五重: 风险保证金垫付</h4>
	    					<p>套利宝投资人享有基业资本风险储备金提前垫付服务，无需操心权益受损，到底还是有保障。</p>
	    				</div>
	    			</div>	    				    						
	    		</div>


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
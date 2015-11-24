<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>投资须知 - 基业资本</title> 
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
 			<li rel="1" ><a href="__APP__/Invest" >服务介绍</a></li><?php endif; ?>

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
<link rel="stylesheet" type="text/css" href="__CSS__/invest.css?ver=<?php echo ($conf["site_cssver"]); ?>">
<div class="container" id="invest_know"> 
<!-- <form class="form-inline">
  <div class="form-group mr20">
    <p class="form-control-static ">基金查询</p>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" id="jijin" placeholder="输入基金代码、简称或简拼">
  </div>
  <a href="javascript:void(0);" class="btn" id="search">查询</a>
</form> -->
<table class="table mt20">
	<tr>
		<th style="border-radius:0;">序号</th>
		<th>基金简称</th>
		<th>基金经理</th>
		<th>基金类型</th>
		<th>单位净值/日期</th>
		<th>成立日期</th>
		<th>今年以来收益率</th>
		<th>成立以来收益率</th>
		<th style="border-radius:0;">&nbsp;</th>
	</tr>
	<tr>
		<td>1</td>
		<td>瀚信成长1期</td>
		<td>蒋国云</td>
		<td>股票型</td>
		<td>2.0579*(06-19)</td>
		<td>2010-02-11</td>
		<td>97.14%</td>
		<td>105.79%</td>
		<td><a href="http://simu.howbuy.com/hanxin/P00377/" target="_blank">了解详情</a></td> 
	</tr>
	<tr>
		<td>2</td>
		<td>瀚信成长2期</td>
		<td>蒋国云</td>
		<td>股票型</td>
		<td>2.0501*(06-26)</td>
		<td>2010-02-12</td>
		<td>87.46%</td>
		<td>105.01%</td>
		<td><a href="http://simu.howbuy.com/hanxin/P00378/" target="_blank">了解详情</a></td> 
	</tr>
	<tr>
		<td>3</td>
		<td>瀚信成长3期</td>
		<td>蒋国云</td>
		<td>股票型</td>
		<td>1.9273*(06-26)</td>
		<td>2010-04-09</td>
		<td>88.58%</td>
		<td>92.73%</td>
		<td><a href="http://simu.howbuy.com/hanxin/P00424/" target="_blank">了解详情</a></td> 
	</tr>
	<tr>
		<td>4</td>
		<td>瀚信成长4期</td>
		<td>蒋国云</td>
		<td>股票型</td>
		<td>2.2965*(06-12)</td>
		<td>2010-04-29</td>
		<td>108.96%</td>
		<td>129.65%</td>
		<td><a href="http://simu.howbuy.com/hanxin/P00451/" target="_blank">了解详情</a></td> 
	</tr>		
	<tr>
		<td>5</td>
		<td>瀚信成长5期</td>
		<td>蒋国云</td>
		<td>股票型</td>
		<td>1.7363*(06-26)</td>
		<td>2010-03-29</td>
		<td>104.49%</td>
		<td>73.63%</td>
		<td><a href="http://simu.howbuy.com/hanxin/P00420/" target="_blank">了解详情</a></td> 
	</tr>		
	<tr>
		<td>6</td>
		<td>瀚信成长4期</td>
		<td>蒋国云</td>
		<td>股票型</td>
		<td>1.6505*(06-19)</td>
		<td>2010-66-09</td>
		<td>92.95%</td>
		<td>65.05%</td>
		<td><a href="http://simu.howbuy.com/hanxin/P00483/" target="_blank">了解详情</a></td> 
	</tr>		
	<tr>
		<td>7</td>
		<td>瀚信经典1期</td>
		<td>陈鹏 </td>
		<td>股票型</td>
		<td>2.0104*(06-26)</td>
		<td>2010-06-24</td>
		<td>74.09%</td>
		<td>101.04%</td>
		<td><a href="http://simu.howbuy.com/hanxin/P00509/" target="_blank">了解详情</a></td> 
	</tr>		
	<tr>
		<td>8</td>
		<td>粤财新价值1</td>
		<td>罗伟广 </td>
		<td>股票型</td>
		<td>2.4604*(06-19)</td>
		<td>2007-11-15</td>
		<td>80.06%</td>
		<td>146.04%</td>
		<td><a href="http://simu.howbuy.com/xinjiazhi/P00163/" target="_blank">了解详情</a></td> 
	</tr>	
	<tr>
		<td>9</td>
		<td>粤财新价值2</td>
		<td>罗伟广 </td>
		<td>股票型</td>
		<td>2.5738*(06-19)</td>
		<td>2008-02-19</td>
		<td>91.99%</td>
		<td>157.38%</td>
		<td><a href="http://simu.howbuy.com/xinjiazhi/P00164/" target="_blank">了解详情</a></td> 
	</tr>			
	<tr>
		<td>10</td>
		<td>新价值4期</td>
		<td>罗伟广 </td>
		<td>股票型</td>
		<td>1.7131* (06-30)</td>
		<td>2009-06-26</td>
		<td>90.66%</td>
		<td>71.31%</td>
		<td><a href="http://simu.howbuy.com/xinjiazhi/P00226/" target="_blank">了解详情</a></td> 
	</tr>			
</table>

 <div class="text-center touzi_liu">
 <img  src="__IMAGES__/invest_know.png"/>
 </div>

</div>

<script type="text/javascript">
//预加载动画效果
 // function run_waitMe(id,text,effect,bg,color,sizeW,sizeH){
 //    		 var effect = effect || 'bounce';
 //    		 var bg = bg || 'rgba(255,255,255,0.7)';
 //    		 var color = color || '#000';
 //    		 var text = text || '';
 //    		 var sizeW = sizeW || '';
 //    		 var sizeH = sizeH || ''; 	
 // 	//引用waitMe模块
 //    Do('waitMe_css','waitMe_js', function(){

	// 		  $(id).waitMe({
			 
	// 		      //none, rotateplane, stretch, orbit, roundBounce, win8, 
	// 		      //win8_linear, ios, facebook, rotation, timer, pulse, 
	// 		      //progressBar, bouncePulse or img
	// 		      effect: effect,
			 
	// 		      //place text under the effect (string).
	// 		      text: text,
			 
	// 		      //background for container (string).
	// 		      bg: bg,
			 
	// 		      //color for background animation and text (string).
	// 		      color: color,
			 
	// 		      //change width for elem animation (string).
	// 		      sizeW: sizeW,
			 
	// 		      //change height for elem animation (string).
	// 		      sizeH: sizeH,
			 
	// 		      // url to image
	// 		      source: ''
			 
	// 		      });
					
 //    });		 	
 // }
//停止加载动画，调用即可
//$("#invest_know").waitMe("hide");  

 $(document).ready(function() {

 // 	$("#search").click(function() {
 // 		//run_waitMe("#invest_know","加载中，请稍后...","stretch","","#3e3934"); //预加载动画
 // 		//ajax异步申请数据
	// });

 

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
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>团队架构 - 基业资本</title> 
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
<link rel="stylesheet" type="text/css" href="__CSS__/team.css?ver=<?php echo ($conf["site_cssver"]); ?>">


<div id="team">
	<div class="container">
		<div class="col-xs-12">
			<h3>专家顾问 </h3>
 			<h5 class="mt10">曹仓</h5>
 			<p>15年营销学和管理学领域钻研经验，现任珠三角经济发展战略研究所所长，惠州学院经济管理系主任兼任惠州市文化与经济促进会常务副会长、人力资源协会名誉副会长、惠州市价格协会理事、惠州市审计学会理事、香港旭日集团特聘高级讲师，曾发表《企业核心能力的营销管理》、《营销管理价值论》等论文；拥有丰富的经济学、金融学、市场营销学与管理学等多领域的实践成果与学术建树。</p>
 			<h5 class="mt30">胡瑞卿</h5>
 			<p>经济学博士，惠州市政协委员、社科专家库首批专家、“十三五”规划前期重大研究课题组专家、惠州市特约审计员。著有《当代市场调研理论与实务》、《统计学原理》、《统计学》等教材，在众多国家级核心刊物发表过许多学术研究论文，具备丰富的项目指导经验。多年来主持或承担了有关部门的多项科研课题，对区域经济发展的建言献策作出了众多积极贡献，在经济学、应用统计学领域拥有20年的丰富科研和实践经验。</p> 
 			<h3 class="mt30">管理团队 </h3>			
 			<h5 class="mt30">林之瀚&nbsp;&nbsp;&nbsp;&nbsp;领投羊联合创始人，基业资本董事 / COO</h5>
 			<p>曾任职深圳IT公司副总经理兼联合创始人。十年商业管理运作经验，精通互联网产品营运与创新金融业务发展。</p>
 			<p>我从2005年开始涉足电商、2010年运作虚拟社区媒体、2012年操盘O2O项目，见证了中国互联网发展演变的黄金年代。每个新生事物从诞生到壮大，必然伴随着不为人所理解的阵痛与迷茫，今天互联网金融的大放异彩，是无数领域精英前仆后继共同推动的时代浪潮。如果把它单纯看作赚钱的渠道，那就未免太小看人类的智慧了。互联网金融将从国民经济结构的重组、财富价值的观念、金融权利的普惠等多个方面，入木三分却细无声息地改变我们的生活。领投羊是未来共享经济模式的构建与践行者，我们不准备把它打造成某些人或组织的私有资产，它将是一个属于社会大众并践行财富平等价值观的服务平台，跨地域跨文化创造个人财富和社会经济的共同发展，十分有幸与领投羊的所有用户一起，站在历史正确的一边。</p> 

 			<h5 class="mt30">张家俊&nbsp;&nbsp;&nbsp;&nbsp;产品及技术合伙人，CTO</h5>
 			<p>国内前沿信息安全技术研究组织成员，七年IT架构与产品研究实践，擅长高效能IT系统架构、数据挖掘及分析、信息安全防护系统构建。</p>
 			<p>国际金融界被称为巨人的前任美联储主席保罗·沃尔克在2010年曾经说过，银行唯一有用的创新就是ATM自动提款机。这句话在互联网时代正式被划上句号，而紧接在后的篇章，是一串串由01字符谱写的技术改革。打造一个稳定高效的信息应用系统，是一项庞杂而浩大的工程，而打造一个稳定高效的金融应用系统，则是在推进一段不断演变的进化史。不论是打破信息不对称的投融资商机互通、为用户进行多重加密的隐私信息保护、构建账户资金安全的隔离防护，抑或是是更优的分散投资策略模型，在今天都能引入更智能高效的计算机技术解决方案；而通过大数据挖掘，更能够得到融资方信用情况客观真实的反映，为风险控制提供更多线索证据。在未来，我们还将再进一步深入挖掘，让充满后现代主义色彩的信息技术与传统金融无缝连接。</p>  

 			<h5 class="mt30">刘进辉&nbsp;&nbsp;&nbsp;&nbsp;领投羊联合创始人，风控总监/信贷政策研究负责人</h5>
 			<p>曾于国内最大P2P企业任资深风控管理，每年经手贷款项目金额数亿，负责尽职调查与信贷政策研究，构建反欺诈体系，数据征信分析等风控体制。</p>
 			<p>做培训的时候，我经常开一个玩笑：一个人从25楼往下跳是否有风险？答案是没风险，因为必死无疑。风险是一种不确定性，也是我们生命中对未来憧憬的美妙所在。对于金融企业而言，风险是唯一的利润来源，而对于风险的控制能力，则是金融企业的核心竞争力。在调查审核过数千个项目后，我深刻明白，风险既不是敌人也不是朋友，只有通过风险的有效转移，刨根挖底的丰富实践，辅以对各行业透彻专业判断和经济发展结构调整的全面理解，才能乐观积极地与风险共舞。我对领投羊风控人员的素质要求是实战与理论缺一不可，不盲目扩充，也不恣意投机。建立整套更科学严谨的风控体系，是为了将安全性和收益控制在最稳定合理的范围之内，只有这样，投资人的利益才能够最大化实现，企业发展才能源远流长，基业长青。</p>  	

 			<h5 class="mt30">谢杭&nbsp;&nbsp;&nbsp;&nbsp;领投羊联合创始人，财务总监/首席分析师</h5>
 			<p>注册会计师，出身于国内最具规模的会计师事务所（证券资格）。具有丰富的资金管理、内控审计、IPO上市尽调、投资并购尽调等专业操盘经验。</p>
 			<p>目前国内的互联网金融在经历了野蛮生长之后，淘汰了一批批严重依赖个人经验风控、过度放纵企业道德风险、资金管理流程缺乏科学约束的企业平台。领投羊自创立之日起便严格遵循标准化、正规化的资金运作模式，倡导阳光合法，透明安全的金融理念。为此我们引入了拥有中央人民支付牌照的第三方资金托管机构隔离用户资金，只有用户本人能够支配账户资金，规避了平台的道德风险。公司的财务分析、预算、税务、内控设计、资金管理均根据国家相关政策法规建立制度制度，拥有一套稳健的财务管理体系，才能更好控制企业的经营风险，障平台与投资用户的共同利益。</p>   		
 			

 			<h5 class="mt30">陈敏丽&nbsp;&nbsp;&nbsp;&nbsp;CRM负责人，理财投资顾问</h5>
 			<p>曾获中国银河证券全国风云人物称号，拥有证券从业资格、基金从业资格、证券投资顾问资格；15年财富管理投顾经验，带领团队管理资产逾2亿元。</p>
 			<p class="mb30">经历过大熊大牛的股市跌宕，见证过瞬息万变的期货波动，15年的资产管理与投资理财让我对理财的理解更加深刻透彻。在资本抵抗贬值寻求升值的过程中，投资人能够对自己内心的贪婪与恐惧一览无余，同时也触发对金融与商业世界运作规律有更本质的思考，最终形成的是我们对待财富和生活的态度。互联网理财自余额宝横空出世后，P2P、众筹等新生力量也如雨后春笋此起彼落，从来没有一个时代能让金融与投资行为如此普及，这是属于我们这一代人的红利。随着国家存款保险制的出台实施，越来越多聪明的投资者具备了更专业的风险意识，养成长期健康的理财投资习惯。与投资者共同成长，为投资者制定合理安全的资产配置方案，并看着越来越多领投羊的投资用户实现自己的财富成长目标，对我来说是一件极具乐趣与成就感的事。</p>   		 				 				

		</div>
	</div>
</div> 


<script type="text/javascript">
 $(document).ready(function() {
 	$("#team > .container").css('opacity', 1);
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
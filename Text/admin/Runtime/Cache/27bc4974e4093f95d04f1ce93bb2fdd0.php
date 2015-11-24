<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>媒体报道 - 详情页 - 基业资本</title> 
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

 <div id="report_detail">
 	<div class="container" id="news_detail">
 		<div class="col-xs-12">

 		<!--数据格式-->
 		<!--
        <h3 class="new_title"><?php echo ($data["title"]); ?></h3>
        <p class="subtitle"><?php echo (date('Y-m-d',$data["ctime"])); ?> | 来自<a target="_blank" href="<?php echo ($media_data["domain"]); ?>"><?php echo ($media_data["tname"]); ?></a></p>
        <p class="subtitle">
            <?php if($media_data['outsite_url']){ ?>
                原文地址：<a href="<?php echo ($media_data["outsite_url"]); ?>" target="_blank"><?php echo ($media_data["outsite_url"]); ?></a> 
            <?php }else{ ?>
                原文地址：<a href="<?php echo ($media_data["url"]); ?>/media/<?php echo ($media_data["id"]); ?>"><?php echo ($media_data["url"]); ?>/media/<?php echo ($media_data["id"]); ?></a> 
            <?php } ?>
        </p>
        <div id="news_content"><?php echo ($data["contents"]); ?></div>
		-->

		<!--格式输出demo-->
        <h3 class="new_title">领投羊林之瀚：一封P2P公司的内部邮件</h3>
        <p class="subtitle">2014-11-06 | 来自<a target="_blank" href="####">凤凰网</a></p>
        <p class="subtitle">
                原文地址：<a href="<?php echo ($media_data["outsite_url"]); ?>" target="_blank">http://sn.ifeng.com/caijing/newswire/detail_2014_11/06/3117126_0.shtml</a> 
        </p>		
        <div id="news_content">
<p><br></p><p>　　周鸿祎说互联网是颠覆一切的力量，我觉得用“颠覆”这个词只不过是为了增添煽动性，互联网的力量，在各个行业里是一种渗透和优化，所谓互联网金融要颠覆传统金融也是行内自己给自己打鸡血的一种标语。</p><p>　　2004年丁磊捣鼓了一个跟微信很相近的东西(没记错应该是一款叫泡泡的软件)，他在北京开了个发布会，说要成为中国虚拟运营商，结果两天后有关部门找他谈话，把这东西给关了。</p><p>　　前段日子，广电总局发了张函，要求所有互联网电视终端只能与牌照方合作，不得将公共互联网上的内容直接提供给用户，那些电视盒子里的视频客户端统统下架……今天，每次听到同行说要颠覆银行的豪情状语，我都捏一把冷汗，这次咱IT狗们，踩着是最有钱的主儿们的尾巴。</p><p>　　一个新模式的价值不在于颠覆谁谁谁，而是有没有为用户创造新的价值。距丁磊那场发布会有10年了，今天微信是挺着腰板屹立不倒的，只要一件新事物代表的是多数人利益，历史会给它应有的位置。历史也会有波动，在什么时候做与具体做什么事，其实一样重要，但我们不是预言家无法预测波动，所以我们能做的只有两件事：“1.站在历史正确的一边;2.撑到那一天。”</p><p>　　站队从来不是一件困难的事，难度都集中我们的脚踝上——你在能站多久?我非常赞同江南愤青说的一句话：“行业没有未来，但是企业有。”你细看一下现在“互联网金融”的商业模式，其实模式一点都不值钱，谁都可以做，有钱的互联网企业可以做，有人的传统金融企业也可以做。面对金融这个几千年没变过的行业，互联网除了在销售渠道上的改良，并没有带来太多想象空间：大数据征信是“理论大于实践”，金融普惠化是“噱头大于本质”。</p><p>　　说这些不是泼冷水，而是告诉各位，不要存一丝侥幸心理，必须做好打硬仗的准备与觉悟。即使现在看来行业是风口上的猪，站对位置就能起飞，但到退潮的时候，P2P网贷也会跟其他行业一样，赚钱的留下，亏损的淘汰，留下的越来越牛逼，离场的不见功与名。</p><p>　　抱着一本人人称道的《行业模式指南》来盲目创业是没有希望的。团购网站模式都一个样，你觉得美团和拉手两家团购有什么不同?美团占了近60%的市场，而拉手占有率是前者的五分之一不到;同样是网上商城，京东凭什么打赢背后有腾讯撑腰的易讯?因为美团的CEO王兴为了挖一个人愿意花5个月软磨硬泡，因为京东小到一张包装胶纸都是经过计算以降低成本的。</p><p>　　不论哪个行业的企业，只有发展出核心价值才有希望，企业的核心价值来自什么?本质上还是团队和资源的差异化。你会看到一些公司明明人浮于事却很赚钱，这就是资源的作用，资源可以使得一家公司盈利，但无法造就一家值得尊敬的企业，况且在今天的市场化进程里，你已经找不到一家公司是核心团队素质低下又取得突出成就的了。鉴于我们的野心是名利双收，领投羊必须得花多点心思关注怎样打造一支牛逼的团队。</p><p>　　大部分团队都是有层级的，不同的层级之间是相互流动的，这些组织的层级几乎就是社会层级的一个小缩影。以下是我道听途说+实践观察的总结，好让各位大致知道每个层级要付出什么代价才能换取一个位置。</p><p>　　第一个是动作执行层，这是每个组织人数最多又不可或缺的层级，一般来说，在这层级混的好要求你有一块特别突出的长板，最基本的要求是按品质完成任务。这阶段最重要的是才气和自我要求，例如你在麦当劳擦桌子和在沙县擦桌子，那完成品质要求明显是不一样的，实际工作中，小到一张Excel表，一份报告的标点符号都能体现一个人的自我要求，对自己没要求的人，我们也不要去抱期望。</p><p>　　稍微累一点的叫任务执行层，几乎所有中层人员都得顺利踏过这个阶段，与动作执行层最大不同在于，有些任务你没办法单独完成，但你还是玩的转——这就是差别。如果说动作执行层是有一块长板就能混好的地方，那任务执行层就得擅用别人的长板：将任务拆分成动作，分配到位，次序逻辑清晰，还得控制人心。自己有长板不说，还不能有特别短的短板，战略理解要精准到位，业务操作要驾轻就熟，关联知识要全面涉猎……</p><p>　　你知道擦桌子是为了给客户提供一个干净卫生的印象，这时候你就会注意地板要拖干净，窗户要擦亮……这些事都得你决定都得你负责都得你安排，可悲的是，很多人一辈子没想明白什么叫“给客户提供一个干净卫生的印象”，不忿地在擦桌子中度过余生。</p><p>　　混到总监混到副总的，我们可以管他叫战略管理层，那真是一份无限心塞的活。因为资源永远是不够用的，日子难过的时候，连擦桌子的肥皂水都要想办法才能搞到手。混到这段上，拼才气和智商远远不够，情商和心力也是项基础配置。资源永远有人在抢，战略永远在手艺化，明知没有完美可言又必须追求完美。面对的不再是“给客户留下卫生干净的印象”这种直述型的任务，而是“怎样让客户留下好印象”这类综合复杂的开放性问题，内部已有的长板能用都用上了，还得去把公司外部的资源搞到手，是不是心累?</p><p>　　写上面这些，是为了让有野心的人看清楚状况，让没野心的人知道自己想做什么。每个人都有选择待在哪里的权利，一旦你选择呆在某个层级，就得面对相应的问题，毕竟解决不同问题得到的回报也是有很大差距的。</p><p>　　嗯，我们都会遇到各种各样的问题，我们要往二楼去，偏偏前方只有一堵墙。</p><p>　　有人会抱怨，但他们骂不倒墙垣，他们是上不去的人;</p><p>　　有人会畏难，指望其他人想办法，他们是上不去的人;</p><p>　　有人会勇字当头爬了再说，但他们不是蜘蛛侠，他们最终累死也上不去……</p><p>　　真正要上去的人，不过是找到一把扶梯，然后踏踏实实一步一步往上走——做成一件事，从来就没有坊间流传的那么玄乎。</p><p>　　在知乎上看过一个问题：“人们为什么崇拜毛泽东?”</p><p>　　点赞数最多的答案是这样的：“当他来到这个项目组的时候，这个项目已经持续开发了300多年了，有多达4亿多行代码，架构混乱垃圾代码遍地，大部分时间都无法编译，即使偶尔能编译也基本不能运行，整天到处崩溃，bug多到改不完甚至连bug管理系统都满是bug。项目管理者根本没有任何管理能力，他们有些是从爷爷的爷爷的爷爷那里继承的管理职位，有的是靠身体强壮强行霸占了一部分模块的所有权。底层工程师没有经过任何培训，别说写代码，很多连算术都不会，拿着微薄的薪水连本培训教材都买不起。有些在其它公司上过几天培训班的所谓大师级工程师，整天抱着本设计模式到处指指点点，实际上他们连hello 
world都不会写。</p><p>　　一群年轻的工程师下定决心要拯救这项目，而毛泽东被选为首席工程师。“</p><p>　　我们不会成为毛泽东，但我们也不会遇到比上述更高难度的项目。对于各位正在处理以及即将面临的困难，都不过是一家创业企业成长过程中的小插曲，同时也是对个人成长的一次次考试，着实不必看得太严峻。犯错不可怕，可怕的是不敢犯错和重复犯错。我坚信在这个过程中团队成长了，企业做什么业务都比蓝翔强。</p><p>　　So，搞清楚你要上几楼，然后跑遍整条村为你自己找到那条扶梯——路费公司给。</p><p><br></p>				
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
<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<title>创建全国文明城市 建设和谐美丽龙岩</title>
<link rel="stylesheet" type="text/css" href="/newadmin/Public/civi/css/main.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<script src="/newadmin/Public/assets/js/jquery.min.js"></script>
<script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="/newadmin/Public/assets/js/jquery.form.js"></script>
<script src="/newadmin/Public/layer/layer.js"></script>
<script>
$(document).ready(function(){
        $('ul.timu li').click(function() {
            var thisLi = $(this);
            if (thisLi.hasClass('selected')) {
				$('li.selected input').removeAttr("checked","checked");
                thisLi.removeClass('selected');
            } else {
                thisLi.parents('.timu').children('li').removeClass('selected');
                thisLi.addClass('selected');
            }
			$('li.selected input').attr("checked","checked");

        }); 		
		
});

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>

<div class="pic1"><img src="/newadmin/Public/civi/img/pic1.png"/></div>
<div class="pic5"></div>
<div style=" display:block; width:100%; height:30px;"></div>
<div class="tip" id="t1"></div>


<div class="con" id="p1">
<b>第 1 题</b>
<p>“全国文明城市”称号是由中央哪个部门授予的？</p>
<ul class="timu">
<li onClick="return stateyes(1,'a');" ><input type="radio" name="s1" readonly  id="s1"  value="a"/>中央文明委</li>
<li onClick="return stateyes(1,'b');" ><input type="radio" name="s1" readonly  id="s1"  value="b"/>中央宣传委</li>
<li onClick="return stateyes(1,'c');" ><input type="radio" name="s1" readonly id="s1"  value="c"/>中央组织部</li>
</ul>
</div>





<div class="con" id="p2">
<b>第 2 题</b>
<p>中共中央于哪一年颁布了《公民道德建设实施纲要》？</p>
<ul class="timu">
<li onClick="return stateyes(2,'a');" ><input type="radio" name="s2" id="s2"  value="a"/>2000年</li>
<li onClick="return stateyes(2,'b');" ><input type="radio" name="s2" id="s2"  value="b"/>2001年</li>
<li onClick="return stateyes(2,'c');" ><input type="radio" name="s2" id="s2"  value="c"/>2002年</li>
</ul>
</div>

<div class="con" id="p3">
<b>第 3 题</b>
<p>我市____2009年被评为“全国见义勇为模范”</p>
<ul class="timu">
<li onClick="return stateyes(3,'a');" ><input type="radio" name="s3" id="s3" value="a"/>王树先</li>
<li onClick="return stateyes(3,'b');" ><input type="radio" name="s3" id="s3" value="b"/>沈冬红</li>
<li onClick="return stateyes(3,'c');" ><input type="radio" name="s3" id="s3" value="c"/>华锦先</li>
</ul>
</div>

<div class="con" id="p4">
<b>第 4 题</b>
<p>提倡文明上网，不传播色情_____信息。</p>
<ul class="timu">
<li onClick="return stateyes(4,'a');" ><input type="radio" name="s4" id="s4" value="a"/>正能量</li>
<li onClick="return stateyes(4,'b');" ><input type="radio" name="s4" id="s4" value="b"/>新闻</li>
<li onClick="return stateyes(4,'c');" ><input type="radio" name="s4" id="s4" value="c"/>暴力</li>
</ul>
</div>

<div class="con" id="p5">
<b>第 5 题</b>
<p>上网要使用文明用语，不使用_____。</p>
<ul class="timu">
<li onClick="return stateyes(5,'a');" ><input type="radio" name="s5" id="s5" value="a"/>表情 </li>
<li onClick="return stateyes(5,'b');" ><input type="radio" name="s5" id="s5" value="b"/>符号</li>
<li onClick="return stateyes(5,'c');" ><input type="radio" name="s5" id="s5" value="c"/>脏话</li>
</ul>
</div>

<div class="con" id="p6">
<b>第 6 题</b>
<p>文明餐桌6字宣传语是_____。</p>
<ul class="timu">
<li onClick="return stateyes(6,'a');" ><input type="radio" name="s6" id="s6" value="a"/>不剩饭不剩菜</li>
<li onClick="return stateyes(6,'b');" ><input type="radio" name="s6" id="s6" value="b"/>要生育先计划</li>
<li onClick="return stateyes(6,'c');" ><input type="radio" name="s6" id="s6" value="c"/>手握天下万千</li>
</ul>
</div>

<div class="con" id="p7">
<b>第 7 题</b>
<p>在全国文明城市测评中，要求对文明单位实行</p>
<ul class="timu">
<li onClick="return stateyes(7,'a');" ><input type="radio" name="s7" id="s7" value="a"/>综合管理</li>
<li onClick="return stateyes(7,'b');" ><input type="radio" name="s7" id="s7" value="b"/>动态管理</li>
<li onClick="return stateyes(7,'c');" ><input type="radio" name="s7" id="s7" value="c"/>常态管理</li>
</ul>
</div>

<div class="con" id="p8">
<b>第 8 题</b>
<p>龙岩市创建全国文明城市工作每届持续周期是？</p>
<ul class="timu">
<li onClick="return stateyes(8,'a');" ><input type="radio" name="s8" id="s8" value="a"/>3年</li>
<li onClick="return stateyes(8,'b');" ><input type="radio" name="s8" id="s8" value="b"/>5年</li>
<li onClick="return stateyes(8,'c');" ><input type="radio" name="s8" id="s8" value="c"/>1年</li>
</ul>
</div>

<div class="con" id="p9">
<b>第 9 题</b>
<p>_____称号是反应城市整体文明水平的综合性荣誉称号。</p>
<ul class="timu">
<li onClick="return stateyes(9,'a');" ><input type="radio" name="s9" id="s9" value="a"/>全国森林城市</li>
<li onClick="return stateyes(9,'b');" ><input type="radio" name="s9" id="s9" value="b"/>全国文明城市</li>
<li onClick="return stateyes(9,'c');" ><input type="radio" name="s9" id="s9" value="c"/>全国卫生城市</li>
</ul>
</div>

<div class="con" id="p10">
<b>第 10 题</b>
<p>中央文明办确认的社区志愿服务主题活动名称是？</p>
<ul class="timu">
<li onClick="return stateyes(10,'a');" ><input type="radio" name="s10" id="s10" value="a"/>办事公道</li>
<li onClick="return stateyes(10,'b');" ><input type="radio" name="s10" id="s10" value="b"/>奉献社会</li>
<li onClick="return stateyes(10,'c');" ><input type="radio" name="s10" id="s10" value="c"/>邻里守望</li>
</ul>
</div>


<div class="afoot"></div>


<script>
var start;  
var end;  
var state;
var duration;
start = new Date();
//修改模态框状态
$(document).ready(function(){
	$("#t1").hide();
	$("#p2").hide();
	$("#p3").hide();
	$("#p4").hide();
	$("#p5").hide();
	$("#p6").hide();
	$("#p7").hide();
	$("#p8").hide();
	$("#p9").hide();
	$("#p10").hide();
});


function stateyes(id,val){

		  $.post('<?php echo U("test");?>',
		  {x:id,h:val},
	function(data){
	var rr=Number(data.listid)+1;
	
		if(data.status==1){
				
			$("#t1").show(100);
			$("#t1").html('恭喜，回答正确').delay(1000).fadeOut();
			$("#p"+data.listid).hide();
			$("#p"+rr).show(200);
		if(data.listid==10){
				end = new Date();
                duration = end.getTime() - start.getTime();
                duration =duration/1000;
					window.location.href="/newadmin/index.php/Home/Index/rank/latime/"+duration+"";
		}
		
		}else if(data.checknum>=2){
				
			$("#t1").show(100);
			$("#t1").html('错误，此题答题结束').delay(1000).fadeOut();
			$("#p"+data.listid).hide();
			$("#p"+rr).show(200);

		if(data.listid==10){
				end = new Date();
                duration = end.getTime() - start.getTime();
                duration =duration/1000;
				window.location.href="/newadmin/index.php/Home/Index/rank/latime/"+duration+"";
		}
		
		}else{
			$("#t1").show(100);
			$("#t1").html('回答错误，还剩一次机会').delay(500).fadeIn();
		}
	});
	return false;
}



$(window).bind('beforeunload',function(){
end = new Date();
 duration = end.getTime() - start.getTime();
 duration =duration/1000;
$.post("<?php echo U('endnum');?>",
    {
      aid:<?php echo ($_SESSION['aid']); ?>,
    },
    function(data){
		if(data.status==1){
			alert('此次答题结束');
		}
		
    });
	
});




</script>

</body>
</html>
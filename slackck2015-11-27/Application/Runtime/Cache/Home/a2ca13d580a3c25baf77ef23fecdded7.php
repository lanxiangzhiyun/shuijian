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

   
<style>
html,body { height:100%}
.regi { width:86%; margin:0 5%; height:auto; border:1px solid #CCC; background:#fff; border-radius:3px;}
.regi span { display:block; width:100%;padding:10px 3%; line-height:30px; background:#999; color:#fff; text-align: center}
.regi input.txt { width:90%; margin:15px 5%; border-radius:5px; border:1px solid #ccc;  line-height:40px;}
.regi input.btn { width:90%; margin:0px 5% 10px 5%; border-radius:5px; background:#ce4e54; color:#fff; border:none; text-align:center; line-height:40px;}

</style>
<body>

<div class="pic1"><img src="/newadmin/Public/civi/img/pic1.png"/></div>
<div class="pic2"><img src="/newadmin/Public/civi/img/pic2.png"/></div>
<div class="pic3"><img src="/newadmin/Public/civi/img/pic3.png"/></div>


<a class="btnnn s1"  data-toggle="modal" data-target="#myModal"  style="cursor:pointer" >开始答题</a>
<a class="btnnn s2" href="intro.html">查看规则</a>

<span class="foot">龙岩文明办 · 国网龙岩供电公司 </span>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               填写手机号码
            </h4>
         </div>
<form class="regi" name="addtel" id="addtel" method="post" action="/newadmin/index.php/Home/Civi/addtel">
         <div class="modal-body">

<span>请填写手机号码，我们在您中奖后联系</span>
<input class="txt" type="text" name="tel" id="tel"/>

			
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">关闭
            </button>
            <button type="submit" class="btn btn-primary">
               保存信息
            </button>
         </div>
</form>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->

<script>

$(function(){
	$('#addtel').ajaxForm({
		beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
		success: complete, // 这是提交后的方法
		dataType: 'json'
	});
	
	function checkForm(){
		if( '' == $.trim($('#tel').val())){
			layer.alert('电话号码不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#tel').focus(); 
			});
			return false;
		}
		
		
		if (!$("#tel").val().match(/^(((13[0-9]{1})|159|153)+\d{8})$/)) { 
			layer.alert('电话号码格式不正确', {icon: 6}, function(index){
 			layer.close(index);
			$('#tel').focus(); 
			});
			return false;
		} 

 }
	function complete(data){
		if(data.status==1){
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
		}else{
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
			return false;	
		}
	}
});


</script>


</body>
</html>
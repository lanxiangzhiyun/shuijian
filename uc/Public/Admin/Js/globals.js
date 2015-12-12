var action={'1':'deleteAlbum','2':'deletePhoto','3':'deleteDiray','4':'deleteDirayComment','5':'deleteWeibo','6':'deleteWeiboReply','7':'deletePet','8':'deletePhotoComment','9':'pushHandle','10':'pushCancel','11':'deleteTag','12':'deleteSensitive','13':'deleteAd','14':'deleteAdmin','15':'delNoticePush','16':'delBaikePush','17':'deleteFeedback','18':'deleteArticle','19':'deleteArticle','20':'deleteThread','21':'deleteThreadComment','22':'deleteArticleComment','23':'ajaxDelList','24':'ajaxDelList','25':'deletePet','26':'publishPet','27':'ajaxDelSubject','28':'ajaxDelComment','29':'ajaxDelCity','30':'ajaxDelType','31':'ajaxDelJob','32':'petDel', '33':'petpicDel','34':'lockedTag','35':'newsTagDel','36':'ajaxDelKeyword','37':'batchTagOperation','38':'ajaxDelNewSubject'};
var controller = {'1':'Album','2':'Diary','3':'Weibo','4':'Pet','5':'Push','6':'Tag','7':'Sensitive','8':'Ad','9':'Admin','10':'Feedback','11':'Article','12':'Thread','13':'Subject','14':'Help','15':'AllPet','16':'SubTem','17':'Job','18':'Search'};

$(document).ready(function(){
	$("[id^='clearText_']").focus(function(){
		if($(this).val()==$(this).attr('val')){
			$(this).val('');	
		}
	});
	$(".calendarClear").focus(function(){
		$(".calendar").val('');
	});

	$('.login_btn').click(function(){
		var username = $.trim($('#username').val());
		var password = $.trim($('#password').val());
		
		if(isSpecialChar(username)==true && isSpecialChar(password)==true){
			$.ajax({
				type:'post',
				url: '/iadmin.php/Index/loginCheck',
				data:{username:username,password:password},
				success:function(msg){
					if(msg==1){
						location.href='/iadmin.php/Index/index';
					}else{
						$('.error').css('display','inline');
						return false;
					}
				}
			});
		}else{
			alert('用户名或者密码含有特殊字符！请从新输入！');
			return false;
		}
		return false;
	});
	$('#login_form').keydown(function(e){
		if(e.keyCode==13){
			$('.login_btn').click();
		}
	});
	//页面跳转
	$(".goto_btn").click(function(){
		var url = $("#pageJump").attr('url');
		var page = $.trim($("#pageJump").val());
		if(isNumber(page)==true){
			location.href=url+page;
		}else{
			alert('请输入数字！');
			return false;
		}
	});
	//全选
	$(".select_all").change(function(){
		if($(this).attr('checked')){

			$("[id^='blogID_']").each(function(){
				$(this).attr('checked',true);
			});
		}else{
			$("[id^='blogID_']").each(function(){
				$(this).attr('checked',false);
			});
		}
	});
	//背景变色
	$('.tb tbody tr').hover(
		function(){
			$(this).css('background','#F5FBFE')
		},function(){
			$(this).css('background','#FFFFFF')
		}
	);
	//按钮删除删除\推荐
	$(".del_btn, .recommend_btn").click(function(){
		$("#isdelete").css('display','block');
		$(".popup_mask").css('display','block').height($(document).height());
	});
	//按钮发布
	$(".publish_btn").click(function(){
		$("#ispublish").css('display','block');
		$(".popup_mask").css('display','block').height($(document).height());
	});
	//按钮锁定
	$(".locked_btn").click(function(){
		$("#islocked").css('display','block');
		$(".popup_mask").css('display','block').height($(document).height());
	});
	//取消删除\推荐
	$(".cancel_btn").click(function(){
		$(".popup_layer").css('display','none');
		$(".popup_mask").css('display','none');
	});
	//密码重置
	$("#isreset_btn").click(function(){
		$("#isreset").css('display','block');
		$(".popup_mask").css('display','block').height($(document).height());
	});
	//返回
	//$("#back_btn").click(function()}{
		//alert('123');//history.back();
	//});
});

//验证是否存在除了(数字 字母 汉子 下划线)以外的字符
function isSpecialChar(val){
	var regu = "^[0-9a-zA-Z\u4e00-\u9fa5_]+$";
	var re = new RegExp(regu);
	if(re.test(val)){
		return true;
	}else{
		return false;
	}
}

//验证是否为数字
function isNumber(val){
	var regu = "^[0-9]+$";
	var re = new RegExp(regu);
	if(re.test(val)){
		return true;
	}else{
		return false;
	}
}
//触发表单提交事件
function submitFun(){
	 $('form').submit();
}
//触发表单提交事件
function submitFunid(formid){
    arrFormId = formid.split(',');
    $.each(arrFormId,function(key,val) {
        $('#'+val).submit();
    })
}
//链接删除
function urlDelete(type,url){
	$("#isdelete").css('display','block');
	$(".popup_mask").css('display','block').height($(document).height());
	$("#isdelete .submit_btn").attr("href","javascript:return isDelete("+type+",'"+url+"');");
}
//链接发布
function urlPublish(type,url){
	$("#ispublish").css('display','block');
	$(".popup_mask").css('display','block').height($(document).height());
	$("#ispublish .submit_btn").attr("onClick","return isDelete("+type+",'"+url+"');");
}
//删除连接，针对栏目推荐
function newUurlDelete(type,url){
	$("#isdelete").css('display','block');
	$(".popup_mask").css('display','block').height($(document).height());
	$("#isdelete .submit_btn").attr("onClick","javascript:return isDelete("+type+",'"+url+"');");
}


//确定执行删除/推荐 type类型是判断是checkbox删除还是链接删除 isSetNotice 判断是否发送站内信 1 是发送 2是不发送
function isDelete(type,url){
		
		if($("#isdelete #setNotice").attr('checked')=='checked'){		
			var isSetNotice = 1;
		}else{
			var isSetNotice = 2;
		}
		
		if(type==2){
			location.href=url+'&isNotice='+isSetNotice;
		}else{
			var actions = $(".del_btn").attr('actions');
			var controllers = $(".del_btn").attr('controllers');

			if(typeof actions=='undefined'){
				actions = $(".recommend_btn").attr('actions');
				controllers = $(".recommend_btn").attr('controllers');
			}
			
			var ids='';
			var i=0;
			$("[id^='blogID_']").each(function(){
				if($(this).attr('checked')=='checked'){
					ids +=$(this).val()+',';
					i++;
				}
			});
			if(i!=0){
				$.ajax({
					type:'get',
					url :'/iadmin.php/'+controller[controllers]+'/'+action[actions],
					data:action[actions]+'='+ids+'&act='+action[actions]+'&isNotice='+isSetNotice,
					success:function(d){
						if(d==1){
							window.location.reload();
						}else if(d.status == "error"){
							$('#isdelete,.popup_mask').hide();
							alert(d.msg);
						}
					}
				});
			}else{
				alert('复选框不能为空！');
				return false;
			}
		}
}

//发布
function ispublish(type,url){
	var actions = $(".publish_btn").attr('actions');
	var controllers = $(".publish_btn").attr('controllers');
	var ids='';
	var i=0;
	$("[id^='blogID_']").each(function(){
		if($(this).attr('checked')=='checked'){
			ids +=$(this).val()+',';
			i++;
		}
	});
	if(i!=0){
		$.ajax({
			type:'get',
			url :'/iadmin.php/'+controller[controllers]+'/'+action[actions],
			data:action[actions]+'='+ids+'&act='+action[actions],
			success:function(msg){
				if(msg==1){
					window.location.reload();
				}
			}
		});
	}else{
		alert('复选框不能为空！');
		return false;
	}
}

//锁定
function islocked(type,url){
	var actions = $(".locked_btn").attr('actions');
	var controllers = $(".locked_btn").attr('controllers');
	var ids='';
	var i=0;
	$("[id^='blogID_']").each(function(){
		if($(this).attr('checked')=='checked'){
			ids +=$(this).val()+',';
			i++;
		}
	});
	if(i!=0){
		$.ajax({
			type:'get',
			url :'/iadmin.php/'+controller[controllers]+'/'+action[actions],
			data:action[actions]+'='+ids+'&act='+action[actions],
			success:function(msg){
				if(msg==1){
					window.location.reload();
				}
			}
		});
	}else{
		alert('复选框不能为空！');
		return false;
	}
}

//批量处理 -- 标签列表
function isbatch(type,url){
	var actions = $(".batch_btn").attr('actions');
	var controllers = $(".batch_btn").attr('controllers');
	var ids='';
	var i=0;
	$("[id^='blogID_']").each(function(){
		if($(this).attr('checked')=='checked'){
			ids +=$(this).val()+',';
			i++;
		}
	});
	window.location.href = '/iadmin.php/'+controller[controllers]+'/'+action[actions]+'?tagIds='+ids;
}

//编辑相册
function editAlbum(id){
	
	$.ajax({
		type:'post',
		url:'/iadmin.php/Album/ajaxAlbum',
		data:{albumid:id},
		dataType:'json',
		success:function(msg){
			$("#albumid").val(msg[0].id);
			$("#title").val(msg[0].title);
			$("#content").val(msg[0].content);
			$("#editAlbum").css('display','block');
			$(".popup_mask").css('display','block').height($(document).height());
		}
	});
}
//获取敏感词
function editSensitive(id){
	$.ajax({
		type:'post',
		url:'/iadmin.php/Sensitive/ajaxKeyword',
		data:{id:id},
		dataType:'json',
		success:function(msg){
			$("#sensitiveid").val(msg[0].id);
			$("#keyword").val(msg[0].keyword);
			$("#editSensitive").css('display','block');
			$(".popup_mask").css('display','block').height($(document).height());
		}
	});
}
//提交敏感词编辑
function postSensitive(){
	$.ajax({
		type:'post',
		url:'/iadmin.php/Sensitive/updateSensitive',
		data:{id:$("#sensitiveid").val(),keyword:$("#keyword").val()},
		success:function(msg){
			if(msg==1){
					window.location.reload();
			}
		}
	});
}
//添加敏感词
function addSensitive(){
	$.ajax({
		type:'post',
		url:'/iadmin.php/Sensitive/createSensitive',
		data:{keyword:$("#keywords").val()},
		success:function(msg){
			if(msg==1){
					window.location.reload();
			}
		}
	});
	
}
//修改反馈状态
function editFeedbackStatus(id){
	$.ajax({
		type:'post',
		url:'/iadmin.php/Feedback/ajaxStatus',
		data:{id:id},
		dataType:'json',
		success:function(msg){
			$("#statusid").val(msg.id);
			$("#status").val(msg.status);
			$("#editFeedbackStatus").css('display','block');
			$(".popup_mask").css('display','block').height($(document).height());
		}
	});
}
//提交反馈状态
function postStatus(){
	$.ajax({
		type:'post',
		url:'/iadmin.php/Feedback/updateStatus',
		data:{id:$("#statusid").val(),status:$("#status").val()},
		success:function(msg){
			if(msg==1){
				window.location.reload();
			}
		}
	});
}
//批量修改
function editBatchFeedbackStatus(){
	$("#editBatchFeedbackStatus").css('display','block');
	$(".popup_mask").css('display','block').height($(document).height());
}
//批量提交状态
function postBatchStatus(){
	var ids='';
	var i=0;
	$("[id^='blogID_']").each(function(){
		if($(this).attr('checked')=='checked'){
			ids +=$(this).val()+',';
			i++;
		}
	});
	if(i!=0){
		$.ajax({
			type:'get',
			url :'/iadmin.php/Feedback/updateBatchStatus',
			data:{idstr:ids,status:$("#statusbatch").val()},
			success:function(msg){
				if(msg==1){
					window.location.reload();
				}
			}
		});
	}else{
		alert('请勾选要批量操作的选项！');
		return false;
	}
}
//提交编辑的
function ajaxEdit(){
	if($("#editAlbum #setNotice").attr('checked')=='checked'){		
		var isSetNotice = 1;
	}else{
		var isSetNotice = 2;
	}
	$.ajax({
		type:'post',
		url:'/iadmin.php/Album/submitAjax',
		data:{albumid:$("#albumid").val(),title:$("#title").val(),content:$("#content").val(),isSetNotice:isSetNotice},
		success:function(msg){
			if(msg==1){
					window.location.reload();
			}
		}
	});
}


//密码重置
function resetPassword(){
	var ids='';
	var i=0;
	$("[id^='blogID_']").each(function(){
		if($(this).attr('checked')=='checked'){
			ids +=$(this).val()+',';
			i++;
		}
	});
	if(i!=0){
		$.ajax({	
			type:'post',
			url :'/iadmin.php/Admin/resetPassword',
			data:{id:ids},
			success:function(msg){
				if(msg==1){
					window.location.reload();
				}
			}
		});
	}else{
		alert('选择框不能为空！');
		return false;
	}
}

function backfun(url){
	location.href=url;
}
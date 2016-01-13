$(function() {
/*
|============================================================================================================
|ROUTES_USER : DeliverController.
|============================================================================================================
*/
// 添加配送员
$('#deliverMenAdd').click(function(){
//	var shopSelect = $('#deliverMenHideShops').html();
//	var roleId = $('#hiddenRoleId').val();
	var bodyHtml = '';
	bodyHtml += '<form class="form-horizontal" fole="form" id="shopFormAdd">';
	bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-2 control-label">名称</label>';
	bodyHtml += '		<div class="col-sm-10">';
	bodyHtml += '			<input type="text" class="form-control" name="title" id="title">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-2 control-label">API代号</label>';
	bodyHtml += '		<div class="col-sm-10">';
	bodyHtml += '			<input type="text" class="form-control" name="code" id="code">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-2 control-label">联系方式</label>';
	bodyHtml += '		<div class="col-sm-10">';
	bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="mobile" id="mobile">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('添加物流公司',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var titleInput = $('#title');
		var codeInput = $('#code');
		var mobileInput = $('#mobile');
		if(DoIllegalValidate2(new Array(titleInput,codeInput,mobileInput),'required')) return;
		if(DoIllegalValidate2(new Array(mobileInput),'mobile')) return;
		
		var ajaxResult = DoAjaxPost('/deliver/add',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			$('#modal_main').modal('hide');
			window.location.href = window.location.href;
		}
	});
});


// 编辑配送员
$('.deliverMenEdit').click(function(){
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var title = trObj.find('.title').text();
	var code = trObj.find('.code').text();
	var mobile = trObj.find('.mobile').text();
	var bodyHtml = '';
	bodyHtml += '<form class="form-horizontal" fole="form" id="shopFormAdd">';
	bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<input type="hidden" name="id" value="'+id+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-2 control-label">名称</label>';
	bodyHtml += '		<div class="col-sm-10">';
	bodyHtml += '			<input type="text" class="form-control" name="title" id="title" value="'+title+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-2 control-label">业务码</label>';
	bodyHtml += '		<div class="col-sm-10">';
	bodyHtml += '			<input type="text" class="form-control" name="code" id="code" value="'+code+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-2 control-label">手机号</label>';
	bodyHtml += '		<div class="col-sm-10">';
	bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="mobile" id="mobile" value="'+mobile+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('编辑配送员',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var titleInput = $('#title');
		var codeInput = $('#code');
		var mobileInput = $('#mobile');
		if(DoIllegalValidate2(new Array(titleInput,codeInput,mobileInput),'required')) return;
		if(DoIllegalValidate2(new Array(mobileInput),'mobile')) return;
		var ajaxResult = DoAjaxPost('/deliver/edit',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			$('#modal_main').modal('hide');
			window.location.href = window.location.href;
		}
	});
});

//删除
$('.deliverMenDelete').click(function(){
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var ajaxResult = DoAjaxPost('/deliver/delete',{'id':id});
	if(ajaxResult['title'] == 'success'){
		trObj.remove();
	}
});


});
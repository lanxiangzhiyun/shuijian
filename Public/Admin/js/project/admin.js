/**
 * Created by fanghui on 2015/12/17 0017.
 */
$(document).on('click','.adminEdit',function(){
  var trObj = $(this).parent().parent();
  var id = trObj.attr('trid');
  var admin_username = trObj.find('.admin_username').text();
  var admin_email = trObj.find('.admin_email').text();
  var admin_realname = trObj.find('.admin_realname').text();
  var admin_tel = trObj.find('.admin_tel').text();
  var bodyHtml = '';
  bodyHtml += '<form class="form-horizontal" fole="form" id="shopFormAdd">';
  bodyHtml += '	<input type="hidden" name="data[id]" value="'+id+'" />';
  bodyHtml += '	<div class="form-group">';
  bodyHtml += '		<label class="col-sm-2 control-label">姓名</label>';
  bodyHtml += '		<div class="col-sm-10">';
  bodyHtml += '			<input type="text" class="form-control" name="data[admin_username]" id="admin_username" value="'+admin_username+'">';
  bodyHtml += '		</div>';
  bodyHtml += '	</div>';
  bodyHtml += '	<div class="form-group">';
  bodyHtml += '		<label class="col-sm-2 control-label">邮件</label>';
  bodyHtml += '		<div class="col-sm-10">';
  bodyHtml += '			<input type="text" class="form-control" name="data[admin_email]" id="admin_email" value="'+admin_email+'">';
  bodyHtml += '		</div>';
  bodyHtml += '	</div>';
  bodyHtml += '	<div class="form-group">';
  bodyHtml += '		<label class="col-sm-2 control-label">真实姓名</label>';
  bodyHtml += '		<div class="col-sm-10">';
  bodyHtml += '			<input type="text" class="form-control" name="data[admin_realname]" id="admin_realname" value="'+admin_realname+'">';
  bodyHtml += '		</div>';
  bodyHtml += '	</div>';
  bodyHtml += '	<div class="form-group">';
  bodyHtml += '		<label class="col-sm-2 control-label">手机号</label>';
  bodyHtml += '		<div class="col-sm-10">';
  bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[admin_tel]" id="admin_tel" value="'+admin_tel+'">';
  bodyHtml += '		</div>';
  bodyHtml += '	</div>';
  bodyHtml += '</form>';
  InitShowModal('编辑管理员',bodyHtml);
  $(document).off('click','#modal_main .modal-footer #submit');
  $(document).on('click','#modal_main .modal-footer #submit',function(){
    //var nameInput = $('#username');
    //var mobileInput = $('#mobile');
    //if(DoIllegalValidate2(new Array(nameInput,mobileInput),'required')) return;
    //if(DoIllegalValidate2(new Array(mobileInput),'mobile')) return;
    var ajaxResult = DoAjaxPost('/iadmin.php/Sys/admin_edit',$('#modal_main form').serialize());
    if(ajaxResult['title'] == 'success'){
      $('#modal_main').modal('hide');
      window.location.href = window.location.href;
    }
  });
});

//删除
$(document).on('click','.adminDelete',function(){
  var trObj = $(this).parent().parent();
  var id = trObj.attr('trid');
  var ajaxResult = DoAjaxPost('/deliver/delete',{'id':id});
  if(ajaxResult['title'] == 'success'){
    trObj.remove();
  }
});

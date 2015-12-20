/**
 * Created by fanghui on 2015/12/17 0017.
 */
$(function() {
//添加一级菜单
  $('#menu_add1').click(function () {
    InitShowModal();
    $('#modal_main .modal-title').html('添加一级菜单');
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form">';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<div class="col-md-12">';
    bodyHtml += '			<label for="menu1_title" class="control-label">菜单标题</label>';
    bodyHtml += '			<input type="text" class="form-control" id="menu1_title">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    $('#modal_main .modal-body').html(bodyHtml);
    $(document).off('click', '#modal_main .modal-footer #submit');
    $(document).on('click', '#modal_main .modal-footer #submit', function () {
      $('input').parent().parent().removeClass('has-error');
      var titleInput = $('#menu1_title');
      if (DoIllegalValidate2(new Array(titleInput), 'required')) return;
      var title = titleInput.val();
      DoAjaxPost('/iadmin.php/Menu/add1',{'data':{'menu_name': title}}, '/iadmin.php/Menu/menu_list');
    });
  });
//添加二级菜单
  $('.menu_add2').click(function () {
    var thisMenuAdd2 = $(this);
    InitShowModal();
    $('#modal_main .modal-title').html('添加二级菜单');
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form">';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<div class="col-md-12">';
    bodyHtml += '			<label for="menu2_title" class="control-label">菜单标题</label>';
    bodyHtml += '			<input type="text" class="form-control" id="menu2_title">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<div class="col-md-12">';
    bodyHtml += '			<label for="menu2_link" class="control-label">链接URI</label>';
    bodyHtml += '			<input type="text" class="form-control" id="menu2_link" placehoder="eg:menu">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    $('#modal_main .modal-body').html(bodyHtml);
    $(document).off('click', '#modal_main .modal-footer #submit');
    $(document).on('click', '#modal_main .modal-footer #submit', function () {
      var titleInput = $('#menu2_title');
      var linkInput = $('#menu2_link');
      if (DoIllegalValidate2(new Array(titleInput, linkInput), 'required')) return;
      var title = titleInput.val();
      var link = linkInput.val();
      var parentId = $('.tabs-vertical li[class=active]').attr('pid');
      $returnData = DoAjaxPost('/iadmin.php/Menu/add2', {'data':{'menu_name': title, 'menu_url': link, 'menu_pid': parentId}});
      if ($returnData) {
        thisMenuAdd2.before('<button class="btn btn-gray disabled">' + title + '</button><br/>');
        $('#modal_main').modal('hide');
      }
    });
  });

// 添加管理员
  $(document).on('click', '#adminAdd', function () {
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="adminFormAdd">';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">姓名</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[admin_username]" id="admin_username" value="">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">邮件</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[admin_email]" id="admin_email" value="">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">真实姓名</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[admin_realname]" id="admin_realname" value="">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">手机号</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[admin_tel]" id="admin_tel" value="">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('添加管理员', bodyHtml);
    $(document).off('click', '#modal_main .modal-footer #submit');
    $(document).on('click', '#modal_main .modal-footer #submit', function () {
      var ajaxResult = DoAjaxPost('/iadmin.php/Sys/admin_edit', $('#modal_main form').serialize());
      if (ajaxResult['title'] == 'success') {
        $('#modal_main').modal('hide');
        window.location.href = window.location.href;
      }
    });
  });

  $(document).on('click', '.adminEdit', function () {
    var trObj = $(this).parent().parent();
    var id = trObj.attr('trid');
    var admin_username = trObj.find('.admin_username').text();
    var admin_email = trObj.find('.admin_email').text();
    var admin_realname = trObj.find('.admin_realname').text();
    var admin_tel = trObj.find('.admin_tel').text();
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="adminFormAdd">';
    bodyHtml += '	<input type="hidden" name="data[id]" value="' + id + '" />';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">姓名</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[admin_username]" id="admin_username" value="' + admin_username + '">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">邮件</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[admin_email]" id="admin_email" value="' + admin_email + '">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">真实姓名</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[admin_realname]" id="admin_realname" value="' + admin_realname + '">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">手机号</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[admin_tel]" id="admin_tel" value="' + admin_tel + '">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('编辑管理员', bodyHtml);
    $(document).off('click', '#modal_main .modal-footer #submit');
    $(document).on('click', '#modal_main .modal-footer #submit', function () {
      //var nameInput = $('#username');
      //var mobileInput = $('#mobile');
      //if(DoIllegalValidate2(new Array(nameInput,mobileInput),'required')) return;
      //if(DoIllegalValidate2(new Array(mobileInput),'mobile')) return;
      var ajaxResult = DoAjaxPost('/iadmin.php/Sys/admin_edit', $('#modal_main form').serialize());
      if (ajaxResult['title'] == 'success') {
        $('#modal_main').modal('hide');
        window.location.href = window.location.href;
      }
    });
  });

//删除
  $(document).on('click', '.adminDelete', function () {
    var trObj = $(this).parent().parent();
    var id = trObj.attr('trid');
    var ajaxResult = DoAjaxPost('/iadmin.php/Sys/ajaxDelList', {'ajaxDelList': id});
    if (ajaxResult['title'] == 'success') {
      trObj.remove();
    }
  });
})

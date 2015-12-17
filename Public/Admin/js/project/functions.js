/**
 * Created by fanghui on 2015/12/17 0017.
 */
/*

 | 初始化Modal弹出框
 */
function InitShowModal(title,data){
  $('#modal_main .modal-title').html('');
  $('#modal_main .modal-body').html('');
  $('#modal_main').modal('show', {backdrop: 'fade'});
  $('#modal_main .modal-body').html(data);
  $('#modal_main .modal-title').html(title);
}


/*

 | Ajax Post请求，获取并返回数据，或者刷新(redirectUrl)
 */
function DoAjaxPost(url,jsonPara,redirectUrl){
  var _token = $('#modal_main #csrf_token').val();
  jsonPara._token = _token;
  var responseData = $.ajax({
    url : url,
    type : 'POST',
    data : jsonPara,
    async : false
  });
  if(redirectUrl){
    window.location.href = redirectUrl;
  }else{
    return $.parseJSON(responseData.responseText);
  }
}

/*
 | Ajax GET请求，获取并返回数据，或者刷新(redirectUrl)
 */
function DoAjaxGet(url,redirectUrl){
  var _token = $('#modal_main #csrf_token').val();
  jsonPara ={ '_token' : _token };
  var responseData = $.ajax({
    url : url,
    type : 'GET',
    data : jsonPara,
    async : false
  });
  if(redirectUrl){
    window.location.href = redirectUrl;
  }else{
    return $.parseJSON(responseData.responseText);
  }
}

/*

 | Ajax Post请求，获取并返回数据，或者刷新(redirectUrl)
 | 直接返回数据不做json处理
 */
function DoAjaxPostReturnHtml(url,jsonPara,redirectUrl){
  var _token = $('#modal_main #csrf_token').val();
  jsonPara._token = _token;
  var responseData = $.ajax({
    url : url,
    type : 'POST',
    data : jsonPara,
    async : false
  });
  if(redirectUrl){
    window.location.href = redirectUrl;
  }else{
    return responseData.responseText;
  }
}

/*

 | 根据ID删除，并且remove table.tr
 */
function DoDeleteTr(url,trObj,alertTxt,id){
  alertTxt += '\n\n你真的要删吗？';
  if(!confirm(alertTxt)) return;
  var result = DoAjaxPost(url,{'id':id});
  if(result['title']=='success'){
    trObj.remove();
  }else{
    alert(result['data']);
  }
}


/*
 | 下拉筛选， JS端做url判断、替换
 */
function DoChangeSelect(obj, key, reg, isFirst){
  var value = obj.val();
  var url = window.location.href;
  url = url.replace(/&*page=.*/,'');
  if(isFirst){
    if(url.indexOf('?')>0){
      url = url.replace(reg,'?'+key+'='+value);
    }else{
      url = url+'?'+key+'='+value;
    }
  }else if(url.indexOf(key+'=')>0){
    url = url.replace(reg,key+'='+value);
  }else{
    if(url.indexOf('?')>0){
      url += '&'+key+'='+value;
    }else{
      url += '?'+key+'='+value;
    }
  }
  window.location.href = url;
}

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

/*

 | 仿jQueryValidate客户端校验
 | 非法返回true，合法返回false
 | DoIllegalValidate()为历史遗留，统一用DoIllegalValidate2()

 */
function DoIllegalValidate(value,rule){
  var value = value.replace(/(^\s*)|(\s*$)/g, "");
  var illegal = false;
  var regMobile = /^1\d{10}$/; //手机号码 mobile
  var regEmail = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/; //邮箱 email
  var regPlus = /^\d+(?=\.{0,1}\d+$|$)/; //正数+ positivenum
  switch(rule){
  case 'required':
    if(value.length<=0){ illegal = true; } break;
  case 'mobile':
    if(!regMobile.test(value)){ illegal = true; } break;
  case 'email':
    if(!regEmail.test(value)){ illegal = true; } break;
  case 'positivenum':
    if(!regPlus.test(value)){ illegal = true; } break;
  default:
    illegal = false;
  }
  return illegal;
}
function DoIllegalValidate2(values,rule,options){
  $('.errorLabel').remove();
  $('.has-error').removeClass('has-error');
  var outterIllegal = false;
  var regMobile = /^1\d{10}$/; //手机号码 mobile
  var regEmail = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/; //邮箱 email
  var regPlus = /^\d+(?=\.{0,1}\d+$|$)/; //正数+ positivenum
  for(var i in values){
    var illegal = false;
    var inputObj = values[i];
    if(inputObj.attr('type')=='checkbox'){
      var checked = CheckboxToArray(inputObj);
      if(checked.length<=0){
        inputObj.first().parent().parent().parent().addClass('has-error');
        return true;
      }
    }else if(inputObj.attr('type')=='radio'){
      var inputVal = inputObj.val();
      inputVal = inputVal.replace(/(^\s*)|(\s*$)/g, "");
      if(inputVal.length <= 0){
        inputObj.parent().parent().parent().addClass('has-error');
        inputObj.after('<label class="control-label errorLabel">此项不能为空 ！</label>');
        return true;
      }
    }else{
      var inputVal = inputObj.val();
      inputVal = inputVal.replace(/(^\s*)|(\s*$)/g, "");
      switch(rule){
      case 'required':
        if(inputVal.length <= 0){
          illegal = true;
          inputObj.parent().parent().addClass('has-error');
          inputObj.after('<label class="control-label errorLabel">此项不能为空 ！</label>');
        }
        break;
      case 'mobile':
        if(!regMobile.test(inputVal)){
          illegal = true;
          inputObj.parent().parent().addClass('has-error');
          inputObj.after('<label class="control-label errorLabel">手机格式有误 ！</label>');
        }
        break;
      case 'email':
        if(!regEmail.test(inputVal)){
          illegal = true;
          inputObj.parent().parent().addClass('has-error');
          inputObj.after('<label class="control-label errorLabel">邮箱格式有误 ！</label>');
        }
        break;
      case 'positivenum':
        if(!regPlus.test(inputVal)){
          illegal = true;
          inputObj.parent().parent().addClass('has-error');
          inputObj.after('<label class="control-label errorLabel">请填写一个正数 ！</label>');
        }
        break;
      case 'unique':
        var url = options[i];
        var jsonPara = {'value':inputVal};
        var result = DoAjaxPost(url,jsonPara);
        if(result=='false'){
          illegal = true;
          inputObj.parent().parent().addClass('has-error');
          inputObj.after('<label class="control-label errorLabel">“'+inputVal+'”已被占用，请换其它 ！</label>');
        }
        break;
      default:
        illegal = false;
      }
      if(illegal){
        return true;
      }
    }
  }
  return outterIllegal;
}

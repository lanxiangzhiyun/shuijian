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
        if(result=='true'){
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

/*

 | 日期控件，必须引用本地的jquery-ui.min.js文件，i don't know why!
 | eg：全部订单

 */
function DatePickerInit()
{
  $.datepicker.regional['zh-CN'] = {
    clearText: '清除',
    clearStatus: '清除已选日期',
    closeText: '关闭',
    closeStatus: '不改变当前选择',
    prevText: '<上月',
    prevStatus: '显示上月',
    prevBigText: '<<',
    prevBigStatus: '显示上一年',
    nextText: '下月>',
    nextStatus: '显示下月',
    nextBigText: '>>',
    nextBigStatus: '显示下一年',
    currentText: '今天',
    currentStatus: '显示本月',
    monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
    monthNamesShort: ['一','二','三','四','五','六', '七','八','九','十','十一','十二'],
    monthStatus: '选择月份',
    yearStatus: '选择年份',
    weekHeader: '周',
    weekStatus: '年内周次',
    dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
    dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
    dayNamesMin: ['日','一','二','三','四','五','六'],
    dayStatus: '设置 DD 为一周起始',
    dateStatus: '选择 m月 d日, DD',
    dateFormat: 'yy-mm-dd',
    firstDay: 1,
    initStatus: '请选择日期',
    isRTL: false};
  $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
  $( "#datepicker" ).datepicker({
    altField: "#datepicker",
    altFormat: "yy-mm-dd"
  });
  $('#datepicker_second').datepicker({
    altField: "#datepicker_second",
    altFormat: "yy-mm-dd"
  });
  $('#datepicker_third').datepicker({
    altField: "#datepicker_third",
    altFormat: "yy-mm-dd"
  });$('#datepicker_third').datepicker({
  altField: "#datepicker_third",
  altFormat: "yy-mm-dd"
});
  $('#datepicker_forth').datepicker({
    altField: "#datepicker_forth",
    altFormat: "yy-mm-dd"
  });
}



/*

 | 日期格式转换，对应PHP的DateFormat()
 | eg：BeiingAPI返回的date分平台，10/13位Long型

 */
Date.prototype.Format = function(format)
{
  var o = {
    "M+" : this.getMonth()+1,                 //月份
    "d+" : this.getDate(),                    //日
    "h+" : this.getHours(),                   //小时
    "m+" : this.getMinutes(),                 //分
    "s+" : this.getSeconds(),                 //秒
    "q+" : Math.floor((this.getMonth()+3)/3), //季度
    "S"  : this.getMilliseconds()             //毫秒
  };
  if( /(y+)/.test(format) ){
    format=format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
  }
  for(var k in o){
    if( new RegExp("("+ k +")").test(format) ){
      format = format.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    }
  }
  return format;
}
function DateFormat(time,format){
  time = time.length == 10 ? time*1000 : time;
  return new Date(time).Format(format);
}



/*

 | 获取checkbox的值, 转换成数组或者字符串
 | eg：DoIllegalValidate2()对checkbox的验证，Edit优惠券基础模版的client

 */
function CheckboxToArray(inputObj){
  var clients = [];
  inputObj.each(function(){
    if($(this).prop('checked')){
      clients.push($(this).val());
    }
  });
  return clients;
}
function CheckboxToString(inputObj)
{
  var clients = CheckboxToArray(inputObj);
  return clients.join();
}



/*

 | 优惠券试用客户端，对应PHP的CouponClient()
 | eg：优惠券管理->基础模版

 */
function CouponClient(type)
{
  var clientType = {'0':'Other','1':'PC','2':'Wechat','3':'Android','5':'iOS'};
  if( type== 'ALL'){
    return '全部';
  }else{
    if(type.indexOf(',')<0){
      return clientType[type];
    }else{
      types = type.split(',');
      var client = '';
      for (var i in types){
        client += clientType[types[i]]+',';
      }
      return client.substring(0,client.length-1);
    }
  }
}

/*

 | 时
 | eg：自提点

 */
function selectHourTime(timeData) {
  var str = '';
  if(timeData) {
    for(var i=0;i<24;i++) {
      if(i < 10) {
        if(timeData == '0'+i) {
          str += '<option value="0'+i+'" selected>0'+i+'</option>';
        } else {
          str += '<option value="0'+i+'">0'+i+'</option>';
        }
      } else {
        if(timeData == i) {
          str += '<option value="'+i+'" selected>'+i+'</option>';
        } else {
          str += '<option value="'+i+'">'+i+'</option>';
        }
      }
    }
  } else {
    for(var i=0;i<24;i++) {
      if(i < 10) {
        str += '<option value="0'+i+'">0'+i+'</option>';
      } else {
        str += '<option value="'+i+'">'+i+'</option>';
      }
    }
  }
  return str;
}
/*

 | 分
 | eg：自提点

 */
function selectMinuteTime(timeData) {
  var str = '';
  if(timeData) {
    for(var i=0;i<60;i++) {
      if(i < 10) {
        if(timeData == '0'+i) {
          str += '<option value="0'+i+'" selected>0'+i+'</option>';
        } else {
          str += '<option value="0'+i+'">0'+i+'</option>';
        }
      } else {
        if(timeData == i) {
          str += '<option value="'+i+'" selected>'+i+'</option>';
        } else {
          str += '<option value="'+i+'">'+i+'</option>';
        }
      }
    }
  } else {
    for(var i=0;i<60;i++) {
      if(i < 10) {
        str += '<option value="0'+i+'">0'+i+'</option>';
      } else {
        str += '<option value="'+i+'">'+i+'</option>';
      }
    }
  }
  return str;
}
/*

 | 秒
 | eg：店铺派送时间

 */
function selectSecondTime(timeData) {
  var str = '';
  if(timeData) {
    for(var i=0;i<60;i++) {
      if(i < 10) {
        if(timeData == '0'+i) {
          str += '<option value="0'+i+'" selected>0'+i+'</option>';
        } else {
          str += '<option value="0'+i+'">0'+i+'</option>';
        }
      } else {
        if(timeData == i) {
          str += '<option value="'+i+'" selected>'+i+'</option>';
        } else {
          str += '<option value="'+i+'">'+i+'</option>';
        }
      }
    }
  } else {
    for(var i=0;i<60;i++) {
      if(i < 10) {
        str += '<option value="0'+i+'">0'+i+'</option>';
      } else {
        str += '<option value="'+i+'">'+i+'</option>';
      }
    }
  }
  return str;
}


/*

 | 验证时间大小
 | eg：自提点

 */
function checkTime(startTime,endTime) {
  starttime = startTime.split(":");
  endtime = endTime.split(":");
  if(starttime[0] > endtime[0]) {
    return true;
  } else if (starttime[0] == endtime[0]) {
    if(starttime[1] >= endtime[1]) {
      return true;
    }
  }
  return false;
}

/*

 | 比较日期大小
 | eg：公告

 */
function checkEndTime(startTime,endTime){
  var start=new Date(startTime.replace("-", "/").replace("-", "/"));
  var end=new Date(endTime.replace("-", "/").replace("-", "/"));
  if(end<start){
    return true;
  }
  return false;
}

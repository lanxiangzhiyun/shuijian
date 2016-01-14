/**
 * Created by fanghui on 2016/1/6.
 */
$(function() {
//选择城市
  $("#shopCitySelect").change(function(){
    DoChangeSelect($(this), 'shop_city', /\?.*/, true);
  });
//删除店铺
  $(document).off('click','.shopDelete');
  $(document).on('click','.shopDelete',function(){
    var trObj = $(this).parent().parent();
    var name = trObj.find('.shopName').text();
    var alertTxt = '店铺：'+name+'\n\n店铺相关的信息也将删除';
    var id = trObj.attr('trid');
    DoDeleteTr('/iadmin.php/Shop/ajaxDelList',trObj,alertTxt,id);
  });
//添加店铺
  $('#shopAdd').click(function() {
    var shopCitySelect = $("#shopCitySelect").html();
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="shopFormAdd">';
    bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">所在城市</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<select class="form-control" name="data[shop_city]" id="shopCitySelectModal">';
    bodyHtml += 				shopCitySelect
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">店铺名称</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[shop_name]" id="shopName" value="" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">起送价</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[low_price]" id="lowprice" value="" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">运费</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[ship_cost]" id="shipCost" value="" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">地址</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[shop_address]" id="address" value="" max="200">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">类型</label>';
    bodyHtml += '		<div class="col-sm-10 shopType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_type]" value="1" checked><span>校园</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_type]" value="2"><span>社区</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_type]" value="3"><span>办公室</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">运营方式</label>';
    bodyHtml += '		<div class="col-sm-10 shopBusinessType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_businessType]" value="1" checked><span>门店模式</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_businessType]" value="2"><span>快递模式</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">送货方式</label>';
    bodyHtml += '		<div class="col-sm-10 shopDeliverType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_deliverType]" value="1"><span>自提</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_deliverType]" value="2" checked><span>送货上门</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_deliverType]" value="3"><span>两种方式都行</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">支付方式</label>';
    bodyHtml += '		<div class="col-sm-10 shopPayType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_payType]" value="1" checked><span>在线支付</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_payType]" value="2"><span>货到付款</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_payType]" value="3"><span>两种方式都行</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">开通状态</label>';
    bodyHtml += '		<div class="col-sm-10 shopIsopen">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="0" checked><span>未营业</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="3"><span>放假中</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="1"><span>半开通</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="2"><span>全开通</span>(<font color="red">选择全开通必须要有自提点</font>)';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">经度</label>';
    bodyHtml += '		<div class="col-sm-5">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="text" id="longitude" name="data[longitude]" placeholder="经度，如：127.14321" max="64" value="">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">维度</label>';
    bodyHtml += '		<div class="col-sm-5">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="text" id="latitude" name="data[latitude]" placeholder="纬度，如：37.6454" max="64" value="">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('添加店铺',bodyHtml);
    $("#shopCitySelectModal").find('option:eq(0)').html('请选择');
    $("#shopCitySelectModal option").prop('selected',false);
    $(document).off('click','#modal_main .modal-footer #submit');
    $(document).on('click','#modal_main .modal-footer #submit',function(){
      $('#shopFormAdd').find('.form-group').removeClass('has-error');
      var cityInput = $('#shopCitySelectModal');
      var shopNameInput = $('#shopName');
      var lowpriceInput = $('#lowprice');
      var shipCostInput = $('#shipCost');
      var addressInput = $('#address');
      var type = $("input[name='data[shop_type]']:checked");
      var businessType = $("input[name='data[shop_businessType]']:checked");
      var deliverType = $("input[name='data[shop_deliverType]']:checked");
      var payType = $("input[name='data[shop_payType]']:checked");
      var isopen = $("input[name='data[shop_isopen]']:checked");
      var longitudeInput = $('#longitude');
      var latitudeInput = $('#latitude');
      if(DoIllegalValidate2(new Array(cityInput,shopNameInput,lowpriceInput,shipCostInput,addressInput,longitudeInput,latitudeInput,type,businessType,deliverType,payType,isopen),'required')) return;
      if(DoIllegalValidate2(new Array(shopNameInput),'unique', new Array('/iadmin.php/Shop/name_unique_check'))) return;
      var ajaxResult = DoAjaxPost('/iadmin.php/Shop/shop_edit',$('#modal_main form').serialize());
      if(ajaxResult['title'] == 'success'){
        $('#modal_main').modal('hide');
        window.location.href = window.location.href;
      }
    });
  });

//修改店铺
  $(document).off('click','.shopEdit');
  $(document).on('click','.shopEdit',function(){
    var trObj = $(this).parent().parent();
    var shopId = trObj.attr('trid');
    var longitude = trObj.attr('lng');
    var latitude = trObj.attr('lat');
    var address = trObj.attr('address');
    var shopName = trObj.find('.shopName').text();
    var shopCity = trObj.find('.shopCity').text();
    var lowprice = trObj.find('.shopLowprice').text();
    var shopShipCost = trObj.find('.shopShipCost').text();
    var shopTypeTd = trObj.find('.shopTypeTd').attr('typeval');
    var shopBusinessTypeTd = trObj.find('.shopDeliverTypeTd').attr('typeval');
    var shopDeliverTypeTd = trObj.find('.shopDeliverTypeTd').attr('typeval');
    var shopPayTypeTd = trObj.find('.shopPayTypeTd').attr('typeval');
    var shopIsopenTd = trObj.find('.shopIsopenTd').attr('typeval');
//	alert(shopDeliverTypeTd);return;
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="shopFormAdd">';
    bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
    bodyHtml += '	<input type="hidden" name="data[id]" id="shopId" value="'+shopId+'" />';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">所在城市</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="shop_city" value="'+shopCity+'" disabled>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">店铺名称</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[shop_name]" id="shopName" value="'+shopName+'" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">起送价</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[low_price]" id="lowprice" value="'+lowprice+'" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">运费</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[ship_cost]" id="shipCost" value="'+shopShipCost+'" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">地址</label>';
    bodyHtml += '		<div class="col-sm-10">';
    bodyHtml += '			<input type="text" class="form-control" name="data[shop_address]" id="address" value="'+address+'" max="200">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">类型</label>';
    bodyHtml += '		<div class="col-sm-10 shopType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_type]" value="1"><span>校园</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_type]" value="2"><span>社区</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_type]" value="3"><span>办公室</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">运营方式</label>';
    bodyHtml += '		<div class="col-sm-10 shopBusinessType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_businessType]" value="1"><span>门店模式</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_businessType]" value="2"><span>快递模式</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">送货方式</label>';
    bodyHtml += '		<div class="col-sm-10 shopDeliverType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_deliverType]" value="1"><span>自提</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_deliverType]" value="2"><span>送货上门</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_deliverType]" value="3"><span>两种方式都行</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">支付方式</label>';
    bodyHtml += '		<div class="col-sm-10 shopPayType">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_payType]" value="1" checked><span>在线支付</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_payType]" value="2"><span>货到付款</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_payType]" value="3"><span>两种方式都行</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">开通状态</label>';
    bodyHtml += '		<div class="col-sm-10 shopIsopen">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="0" checked><span>未营业</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="3"><span>放假中</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="1"><span>半开通</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="2"><span>全开通</span>(<font color="red">选择全开通必须要有自提点</font>)';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">经度</label>';
    bodyHtml += '		<div class="col-sm-5">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="text" id="longitude" name="data[longitude]" placeholder="经度，如：127.14321" max="64" value="'+longitude+'">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-2 control-label">维度</label>';
    bodyHtml += '		<div class="col-sm-5">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="text" id="latitude" name="data[latitude]" placeholder="纬度，如：37.6454" max="64" value="'+latitude+'">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('修改店铺',bodyHtml);
    $(".shopType").find('input:radio[value='+shopTypeTd+']').prop('checked',true);
    $(".shopBusinessType").find('input:radio[value='+shopBusinessTypeTd+']').prop('checked',true);
    $(".shopDeliverType").find('input:radio[value='+shopDeliverTypeTd+']').prop('checked',true);
    $(".shopPayType").find('input:radio[value='+shopPayTypeTd+']').prop('checked',true);
    $(".shopIsopen").find('input:radio[value='+shopIsopenTd+']').prop('checked',true);
    $(document).off('click','#modal_main .modal-footer #submit');
    $(document).on('click','#modal_main .modal-footer #submit',function(){
      $('#shopFormAdd').find('.form-group').removeClass('has-error');
      var shopIdInput = $('#shopId');
      var shopNameInput = $('#shopName');
      var lowpriceInput = $('#lowprice');
      var shipCostInput = $('#shipCost');
      var addressInput = $('#address');
      var type = $("input[name='data[shop_type]']:checked");
      var businessType = $("input[name='data[shop_businessType]']:checked");
      var deliverType = $("input[name='data[shop_deliverType]']:checked");
      var payType = $("input[name='data[shop_payType]']:checked");
      var isopen = $("input[name='data[shop_isopen]']:checked");
      var longitudeInput = $('#longitude');
      var latitudeInput = $('#latitude');
      if(DoIllegalValidate2(new Array(shopIdInput,shopNameInput,lowpriceInput,shipCostInput,addressInput,longitudeInput,latitudeInput,type,businessType,deliverType,payType,isopen),'required')) return;
      if(DoIllegalValidate2(new Array(shopNameInput),'unique', new Array('/iadmin.php/Shop/name_unique_check'))) return;
      var ajaxResult = DoAjaxPost('/iadmin.php/Shop/shop_edit',$('#modal_main form').serialize());
      if(ajaxResult['title'] == 'success'){
        $('#modal_main').modal('hide');
        window.location.href = window.location.href;
      }
    });
  });

//添加派送时间
  $(document).off('click',".shopShipTimeAdd");
  $(document).on('click',".shopShipTimeAdd",function() {
    var trObject = $(this).parent().parent();
    var shopId = trObject.attr('trid');
    var roleId = trObject.attr('roleId');
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="shopShipTimeFormAdd">';
    bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
    bodyHtml += '	<input type="hidden" name="shopId" id="shopId" value="'+shopId+'" />';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">送货时间</label>';
    bodyHtml += '		<div class="col-sm-9 weekDays">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="1" checked> 星期一';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="2" checked> 星期二';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="3" checked> 星期三';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="4" checked> 星期四';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="5" checked> 星期五';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="6" checked> 星期六';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="7" checked> 星期日';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">是否当日达</label>';
    bodyHtml += '		<div class="col-sm-9 todayArrive">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="todayArrive" class="shipTimeTodayArrive" value="1">是';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="todayArrive" class="shipTimeTodayArrive" value="0" checked>否';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group" id="todayArriveTime">';
    bodyHtml += '		<label class="col-sm-3 control-label">当日达最晚下单时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-2">';
    bodyHtml += '			<select class="form-control" name="startHour" id="startHour">';
    bodyHtml += 				selectHourTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-2">';
    bodyHtml += '			<select class="form-control" id="startMinute" name="startMinute">';
    bodyHtml += 				selectMinuteTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">秒</label>';
    bodyHtml += '		<div class="col-sm-2">';
    bodyHtml += '			<select class="form-control" id="startSecond" name="startSecond">';
    bodyHtml += 				selectSecondTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">几日后到达</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="number" name="sendAfterDays" id="sendAfterDays" min="1" max="32" placeholder="请输入整数" value="">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">送达开始时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="sendTimeBeginHour" id="sendTimeBeginHour">';
    bodyHtml += 				selectHourTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="sendTimeBeginMinute" name="sendTimeBeginMinute">';
    bodyHtml += 				selectMinuteTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">送达结束时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="sendTimeEndHour" id="sendTimeEndHour">';
    bodyHtml += 				selectHourTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="sendTimeEndMinute" name="sendTimeEndMinute">';
    bodyHtml += 				selectMinuteTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">提货日期(数量)</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="number" name="chooseCount" id="chooseCount" min="1" max="32" placeholder="" value="3">';
    bodyHtml += '			<span style="color: gray;">默认未来3天</span>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('添加派送时间',bodyHtml);

    //角色是客服，只能看
    if(roleId == 2) {
      $('#modal_main .modal-footer').empty();
    }
    $('#todayArriveTime').hide();
    $('.shipTimeTodayArrive').off('click');
    $('.shipTimeTodayArrive').on('click',function(){
      var todayArriveValue = $(this).val();
      if(todayArriveValue == 1) {
        $('#modal_main .modal-body #todayArriveTime').show();
      } else {
        $('#modal_main .modal-body #todayArriveTime').hide();
      }
    });
    $(document).off('click','#modal_main .modal-footer #submit');
    $(document).on('click','#modal_main .modal-footer #submit',function(){
      $('#shopShipTimeFormAdd').find('.form-group').removeClass('has-error');
      var shopIdInput = $('#shopId');
      var weekDaysInput = $("input[name='weekDays[]']:checked");
      if(weekDaysInput.length <= 0) {
        alert('请选择送货时间');return;
      }
      var todayArriveInput = $("input[name='todayArrive']:checked");
      var startHourInput = $('#startHour');
      var startMinuteInput = $('#startMinute');
      var startSecondInput = $('#startSecond');
      var sendAfterDaysInput = $('#sendAfterDays');
      var sendTimeBeginHourInput = $('#sendTimeBeginHour');
      var sendTimeBeginMinuteInput = $('#sendTimeBeginMinute');
      var sendTimeEndHourInput = $('#sendTimeEndHour');
      var sendTimeEndMinuteInput = $('#sendTimeEndMinute');
      var chooseCountInput = $('#chooseCount');
      var startTime = sendTimeBeginHourInput.val()+':'+sendTimeBeginMinuteInput.val();
      var endTime = sendTimeEndHourInput.val()+':'+sendTimeEndMinuteInput.val();
      if(checkTime(startTime,endTime)) {
        alert('送达结束时间不能小于送达开始时间');return;
      }
      if(DoIllegalValidate2(new Array(shopIdInput,todayArriveInput,startHourInput,startMinuteInput,sendAfterDaysInput,sendTimeBeginHourInput,sendTimeBeginMinuteInput,sendTimeEndHourInput,sendTimeEndMinuteInput,chooseCountInput),'required')) return;
      var ajaxResult = DoAjaxPost('/iadmin.php/Shop/ship_time_add',$('#modal_main form').serialize());
      if(ajaxResult['title'] == 'success'){
        var data = ajaxResult['data'];
        trObject.find('.shipTimeId').attr('shipTimeId',data['id']);
        trObject.find('.weekDays').attr('weekDays',data['week_days']);
        trObject.find('.todayArrive').attr('todayArrive',data['today_arrive']);
        trObject.find('.todayArriveTime').attr('todayArriveTime',data['today_arrive_time']);
        trObject.find('.sendAfterDays').attr('sendAfterDays',data['send_after_days']);
        trObject.find('.sendTimeBegin').attr('sendTimeBegin',data['send_time_begin']);
        trObject.find('.sendTimeEnd').attr('sendTimeEnd',data['send_time_end']);
        trObject.find('.chooseCount').attr('chooseCount',data['choose_count']);
        trObject.find('.chooseCount').attr('chooseCount',data['choose_count']);
        trObject.find('.shopShipTimeAdd').addClass('shopShipTimeEdit').removeClass('shopShipTimeAdd');
        $('#modal_main').modal('hide');
      }
    });
  });
//修改派送时间
  $(document).off('click',".shopShipTimeEdit");
  $(document).on('click',".shopShipTimeEdit",function() {
    var trObject = $(this).parent().parent();
    var shopId = trObject.attr('trid');
    var roleId = trObject.attr('roleId');
    var shipTimeId = trObject.find('.shipTimeId').attr('shipTimeId');
    var weekDays = trObject.find('.weekDays').attr('weekDays');
    var weekDaysArray = weekDays.split(",");
    var todayArrive = trObject.find('.todayArrive').attr('todayArrive');
    var todayArriveTime = trObject.find('.todayArriveTime').attr('todayArriveTime');
    var todayTimeData = todayArriveTime.split(":");
    var sendAfterDays = trObject.find('.sendAfterDays').attr('sendAfterDays');
    var sendTimeBegin = trObject.find('.sendTimeBegin').attr('sendTimeBegin');
    var timeBegin = sendTimeBegin.split(":");
    var sendTimeEnd = trObject.find('.sendTimeEnd').attr('sendTimeEnd');
    var timeEnd = sendTimeEnd.split(":");
    var chooseCount = trObject.find('.chooseCount').attr('chooseCount');
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="shopShipTimeFormAdd">';
    bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
    bodyHtml += '	<input type="hidden" name="shopId" id="shopId" value="'+shopId+'" />';
    bodyHtml += '	<input type="hidden" name="shipTimeId" id="shipTimeId" value="'+shipTimeId+'" />';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">送货时间</label>';
    bodyHtml += '		<div class="col-sm-9 weekDays">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="1"> 星期一';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="2"> 星期二';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="3"> 星期三';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="4"> 星期四';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="5"> 星期五';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="6"> 星期六';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="checkbox" name="weekDays[]" value="7"> 星期日';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">是否当日达</label>';
    bodyHtml += '		<div class="col-sm-9 todayArrive">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="todayArrive" class="shipTimeTodayArrive" value="1">是';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="todayArrive" class="shipTimeTodayArrive" value="0" checked>否';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group" id="todayArriveTime">';
    bodyHtml += '		<label class="col-sm-3 control-label">当日达最晚下单时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-2">';
    bodyHtml += '			<select class="form-control" name="startHour" id="startHour">';
    bodyHtml += 				selectHourTime(todayTimeData[0]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-2">';
    bodyHtml += '			<select class="form-control" id="startMinute" name="startMinute">';
    bodyHtml += 				selectMinuteTime(todayTimeData[1]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">秒</label>';
    bodyHtml += '		<div class="col-sm-2">';
    bodyHtml += '			<select class="form-control" id="startSecond" name="startSecond">';
    bodyHtml += 				selectSecondTime(todayTimeData[2]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">几日后到达</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="number" name="sendAfterDays" id="sendAfterDays" min="1" max="32" placeholder="请输入整数" value="'+sendAfterDays+'">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">送达开始时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="sendTimeBeginHour" id="sendTimeBeginHour">';
    bodyHtml += 				selectHourTime(timeBegin[0]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="sendTimeBeginMinute" name="sendTimeBeginMinute">';
    bodyHtml += 				selectMinuteTime(timeBegin[1]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">送达结束时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="sendTimeEndHour" id="sendTimeEndHour">';
    bodyHtml += 				selectHourTime(timeEnd[0]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="sendTimeEndMinute" name="sendTimeEndMinute">';
    bodyHtml += 				selectMinuteTime(timeEnd[1]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">提货日期(数量)</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input class="form-control onlyDigitData" type="number" name="chooseCount" id="chooseCount" min="1" max="32" placeholder="" value="'+chooseCount+'">';
    bodyHtml += '			<span style="color: gray;">默认未来3天</span>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('修改派送时间',bodyHtml);
    $.each(weekDaysArray,function(i,item) {
      if(item) {
        $(".weekDays").find('input:checkbox[value='+item+']').prop('checked',true);
      }
    });
    $(".todayArrive").find('input:radio[value='+todayArrive+']').prop('checked',true);

    if(roleId == 5) {
      $('#modal_main .modal-footer').empty();
    }

    var todayArriveValue = $('.shipTimeTodayArrive:checked').val();
    if(todayArriveValue == 1) {
      $('#modal_main .modal-body #todayArriveTime').show();
    } else {
      $('#modal_main .modal-body #todayArriveTime').hide();
    }
    $('.shipTimeTodayArrive').off('click');
    $('.shipTimeTodayArrive').on('click',function(){
      var todayArriveValue = $(this).val();
      if(todayArriveValue == 1) {
        $('#modal_main .modal-body #todayArriveTime').show();
      } else {
        $('#modal_main .modal-body #todayArriveTime').hide();
      }
    });
    $(document).off('click','#modal_main .modal-footer #submit');
    $(document).on('click','#modal_main .modal-footer #submit',function(){
      $('#shopShipTimeFormAdd').find('.form-group').removeClass('has-error');
      var shopIdInput = $('#shopId');
      var shipTimeIdInput = $('#shipTimeId');
      var weekDaysInput = $("input[name='weekDays[]']:checked");
      if(weekDaysInput.length <= 0) {
        alert('请选择送货时间');return;
      }
      var weekDaysValue = '';
      $.each(weekDaysInput,function(i,item) {
        weekDaysValue += $(item).val()+',';
      });
      var todayArriveInput = $("input[name='todayArrive']:checked");
      var startHourInput = $('#startHour');
      var startMinuteInput = $('#startMinute');
      var startSecondInput = $('#startSecond');
      var todayArriveTime = startHourInput.val()+':'+startMinuteInput.val()+':'+startSecondInput.val();
      var sendAfterDaysInput = $('#sendAfterDays');
      var sendTimeBeginHourInput = $('#sendTimeBeginHour');
      var sendTimeBeginMinuteInput = $('#sendTimeBeginMinute');
      var sendTimeEndHourInput = $('#sendTimeEndHour');
      var sendTimeEndMinuteInput = $('#sendTimeEndMinute');
      var chooseCountInput = $('#chooseCount');
      var startTime = sendTimeBeginHourInput.val()+':'+sendTimeBeginMinuteInput.val();
      var endTime = sendTimeEndHourInput.val()+':'+sendTimeEndMinuteInput.val();
      if(checkTime(startTime,endTime)) {
        alert('送达结束时间不能小于送达开始时间');return;
      }
      if(DoIllegalValidate2(new Array(shopIdInput,shipTimeIdInput,todayArriveInput,startHourInput,startMinuteInput,sendAfterDaysInput,sendTimeBeginHourInput,sendTimeBeginMinuteInput,sendTimeEndHourInput,sendTimeEndMinuteInput,chooseCountInput),'required')) return;
      var ajaxResult = DoAjaxPost('/iadmin.php/Shop/ship_time_edit',$('#modal_main form').serialize());
      if(ajaxResult['title'] == 'success'){
        trObject.find('.weekDays').attr('weekDays',weekDaysValue);
        trObject.find('.todayArrive').attr('todayArrive',todayArriveInput.val());
        trObject.find('.todayArriveTime').attr('todayArriveTime',todayArriveTime);
        trObject.find('.sendAfterDays').attr('sendAfterDays',sendAfterDaysInput.val());
        trObject.find('.sendTimeBegin').attr('sendTimeBegin',startTime);
        trObject.find('.sendTimeEnd').attr('sendTimeEnd',endTime);
        trObject.find('.chooseCount').attr('chooseCount',chooseCountInput.val());
        $('#modal_main').modal('hide');
      }
    });
  });


  /*
   |============================================================================================================
   |ROUTES_USER : SiteController.
   |============================================================================================================
   */
//选择店铺
  $('#serviceSiteShopSelect').change(function(){
    DoChangeSelect($(this), 'shop_id', /\?.*/, true);
  });

//添加自提点
  $('#serviceSiteAdd').click(function() {
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="serviceSiteFormAdd">';
    bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">所在城市</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<select class="form-control" name="data[site_city]" id="cacheCitySelect">';
    bodyHtml += '				<option value="">请选择...</option>';
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">所在店铺</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<select class="form-control" name="data[site_shop]" id="cacheShopSelect">';
    bodyHtml += '				<option value="">请选择...</option>';
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">自提点名称</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control" name="data[site_name]" id="siteName" value="" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">地址</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control" name="data[site_address]" id="address" value="" max="255">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">联系人</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control" name="data[site_contact]" id="contact" value="" max="255">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">电话</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[site_mobile]" id="contact_mobile" value="" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">开始营业时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="data[startHour]" id="startHour">';
    bodyHtml += 				selectHourTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="startMinute" name="data[startMinute]">';
    bodyHtml += 				selectMinuteTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">结束营业时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="data[endHour]" id="endHour">';
    bodyHtml += 				selectHourTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="endMinute" name="data[endMinute]">';
    bodyHtml += 				selectMinuteTime();
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">是否营业</label>';
    bodyHtml += '		<div class="col-sm-9 siteStatus">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="1" checked><span>营业中√</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="0"><span>停用×</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">经度</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[longitude]" id="longitude" value="" max="10">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">维度</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[latitude]" id="latitude" value="" max="10">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('添加自提点',bodyHtml);
    //获取城市，城市只在添加时用到，所以放在这请求
    var $citys = DoAjaxPost('/iadmin.php/Cache/all_city',{});
    var str = '';
    $.each($citys,function(i,item){
      str += '<option value="'+item.city_id+'">'+item.city_name+'</option>';
    });
    $("#cacheCitySelect").append(str);
    $(document).off('click','#modal_main .modal-footer #submit');
    $(document).on('click','#modal_main .modal-footer #submit',function(){
      $('#serviceSiteFormAdd').find('.form-group').removeClass('has-error');
      var shopInput = $('#cacheShopSelect');
      var siteNameInput = $('#siteName');
      var address = $('#address');
      var contact = $('#contact');
      var contact_mobile = $('#contact_mobile');
      var startHour = $('#startHour');
      var startMinute = $('#startMinute');
      var endHour = $('#endHour');
      var endMinute = $('#endMinute');
      var status = $("input[name='data[shop_isopen]']:checked");
      var longitudeInput = $('#longitude');
      var latitudeInput = $('#latitude');
      var startTime = startHour.val()+':'+startMinute.val();
      var endTime = endHour.val()+':'+endMinute.val();
      if(checkTime(startTime,endTime)) {
        alert('结束时间不能小于开始时间');return;
      }
      if(DoIllegalValidate2(new Array(shopInput,siteNameInput,address,contact,contact_mobile,status),'required')) return;
      if(DoIllegalValidate2(new Array(siteNameInput),'unique', new Array('/iadmin.php/Site/name_unique_check'))) return;
      var ajaxResult = DoAjaxPost('/iadmin.php/Site/site_edit',$('#modal_main form').serialize());
      if(ajaxResult['title'] == 'success'){
        //var data = ajaxResult['data'];
        //$('#serviceSiteTable tbody').prepend($('#serviceSiteTable tbody tr').first().clone());
        //var currTr = $('#serviceSiteTable tbody tr').first();
        //currTr.attr('trid',data['id']);
        //currTr.attr('lng',data['longitude']);
        //currTr.attr('lat',data['latitude']);
        //currTr.find('.sName').attr('title',data['name']);
        //currTr.find('.sName').html(data['name']);
        //currTr.find('.sShop').html(shopInput.find('option:selected').text());
        //currTr.find('.sAddress').html(data['address']);
        //currTr.find('.sAddress').attr('address',data['address']);
        //currTr.find('.sContact').html(data['contact']);
        //currTr.find('.sMobile').html(data['contact_mobile']);
        //currTr.find('.sTime').html(data['site_startTime']+'<code>至</code>'+data['site_endTime']);
        //currTr.find('.sTime').attr('begin',data['site_startTime']);
        //currTr.find('.sTime').attr('end',data['site_endTime']);
        //currTr.find('.sStatus').attr('status',data['status']);
        //currTr.find('.sStatus').html($('.siteStatus').find('input:radio[value='+data['status']+']').next().text());
        $('#modal_main').modal('hide');
        window.location.href = window.location.href;
      }
    });
  });
//修改自提点
  $(document).off('click',".serviceSiteEidt");
  $(document).on('click',".serviceSiteEidt",function() {
    var trObject = $(this).parent().parent();
    var serviceSiteId = trObject.attr('trid');
    var longitude = trObject.attr('lng');
    var latitude = trObject.attr('lat');
    var sName = trObject.find('.sName').attr('title');
    var sShop = trObject.find('.sShop').text();
    var sAddress = trObject.find('.sAddress').attr('address');
    var sContact = trObject.find('.sContact').text();
    var sMobile = trObject.find('.sMobile').text();
    var startTime = trObject.find('.sTime').attr('begin');
    var siteBegin = startTime.split(":");
    var endTime = trObject.find('.sTime').attr('end');
    var siteEnd = endTime.split(":");
    var sStatus = trObject.find('.sStatus').attr('status');
    var bodyHtml = '';
    bodyHtml += '<form class="form-horizontal" fole="form" id="serviceSiteFormAdd">';
    bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
    bodyHtml += '	<input type="hidden" name="serviceSiteId" id="serviceSiteId" value="'+serviceSiteId+'" />';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">所在店铺</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" name="data[site_shop]" class="form-control" value="'+sShop+'" disabled>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">自提点名称</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control" name="data[site_name]" id="siteName" value="'+sName+'" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">地址</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control" name="data[site_address]" id="address" value="'+sAddress+'" max="255">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">联系人</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control" name="data[site_contact]" id="contact" value="'+sContact+'" max="255">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">电话</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[site_mobile]" id="contact_mobile" value="'+sMobile+'" max="32">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">开始营业时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="data[startHour]" id="startHour">';
    bodyHtml += 				selectHourTime(siteBegin[0]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="startMinute" name="data[startMinute]">';
    bodyHtml += 				selectMinuteTime(siteBegin[1]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">结束营业时间</label>';
    bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" name="data[endHour]" id="endHour">';
    bodyHtml += 				selectHourTime(siteEnd[0]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
    bodyHtml += '		<div class="col-sm-3">';
    bodyHtml += '			<select class="form-control" id="endMinute" name="data[endMinute]">';
    bodyHtml += 				selectMinuteTime(siteEnd[1]);
    bodyHtml += '			</select>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">是否营业</label>';
    bodyHtml += '		<div class="col-sm-9 siteStatus">';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="1" checked><span>营业中√</span>';
    bodyHtml += '			</label>';
    bodyHtml += '			<label class="radio-inline">';
    bodyHtml += '				<input type="radio" name="data[shop_isopen]" value="0"><span>停用×</span>';
    bodyHtml += '			</label>';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">经度</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[longitude]" id="longitude" value="'+longitude+'" max="10">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '	<div class="form-group">';
    bodyHtml += '		<label class="col-sm-3 control-label">维度</label>';
    bodyHtml += '		<div class="col-sm-9">';
    bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="data[latitude]" id="latitude" value="'+latitude+'" max="10">';
    bodyHtml += '		</div>';
    bodyHtml += '	</div>';
    bodyHtml += '</form>';
    InitShowModal('修改自提点',bodyHtml);
    $(".siteStatus").find('input:radio[value='+sStatus+']').prop('checked',true);
    $(document).off('click','#modal_main .modal-footer #submit');
    $(document).on('click','#modal_main .modal-footer #submit',function(){
      $('#serviceSiteFormAdd').find('.form-group').removeClass('has-error');
      var serviceSiteIdInput = $('#serviceSiteId');
      var siteNameInput = $('#siteName');
      var address = $('#address');
      var contact = $('#contact');
      var contact_mobile = $('#contact_mobile');
      var startHour = $('#startHour');
      var startMinute = $('#startMinute');
      var endHour = $('#endHour');
      var endMinute = $('#endMinute');
      var status = $("input[name='data[shop_isopen]']:checked");
      var longitudeInput = $('#longitude');
      var latitudeInput = $('#latitude');
      var startTime = startHour.val()+':'+startMinute.val();
      var endTime = endHour.val()+':'+endMinute.val();
      if(checkTime(startTime,endTime)) {
        alert('结束时间不能小于开始时间');return;
      }
      if(DoIllegalValidate2(new Array(serviceSiteIdInput,siteNameInput,address,contact,contact_mobile,status),'required')) return;
      if(DoIllegalValidate2(new Array(siteNameInput),'unique', new Array('/iadmin.php/Site/name_unique_check/'+serviceSiteIdInput.val()))) return;
      var ajaxResult = DoAjaxPost('/iadmin.php/Site/site_edit',$('#modal_main form').serialize());
      if(ajaxResult['title'] == 'success'){
        trObject.attr('lng',longitudeInput.val());
        trObject.attr('lat',latitudeInput.val());
        trObject.find('.sName').attr('title',siteNameInput.val());
        trObject.find('.sName').html(siteNameInput.val());
        trObject.find('.sAddress').attr('address',address.val());
        trObject.find('.sAddress').html(address.val());
        trObject.find('.sContact').html(contact.val());
        trObject.find('.sMobile').html(contact_mobile.val());
        trObject.find('.sTime').html(startTime+'<code>至</code>'+endTime);
        trObject.find('.sTime').attr('begin',startTime);
        trObject.find('.sTime').attr('end',endTime);
        trObject.find('.sStatus').attr('status',status.val());
        trObject.find('.sStatus').html($('.siteStatus').find('input:radio[value='+status.val()+']').next().text());
        $('#modal_main').modal('hide');
      }
    });

  });

//删除自提点
  $(document).off('click','.serviceSiteDelete');
  $(document).on('click','.serviceSiteDelete',function(){
    var trObj = $(this).parent().parent();
    var name = trObj.find('td').eq(0).text();
    var alertTxt = '自提点：'+name;
    var id = trObj.attr('trid');
    DoDeleteTr('/site/delete-site',trObj,alertTxt,id)
  });


  $('#shopDailySubmit').click(function() {
    var goodsData = {};
    var i = 0;
    $('#shopDailyTbody tr').each(function(){
      var trObj = $(this);
      var itemId = trObj.attr('itemId');
      var cost = trObj.find('.cost').html();
      var requires = trObj.find('.requires').html();
      var incomes = trObj.find('.incomes').val();
      if(incomes.length>0){
        var incomeLost = trObj.find('.incomeLost').val();
        var reserveLost = trObj.find('.reserveLost').val();
        goodsData[itemId] = {"cost":cost,"requires":requires,"incomes":incomes,"incomeLost":incomeLost,"reserveLost":reserveLost};
        i ++;
      }
    });
    if(i>0){
      var orderDate = $('#datepicker').val();
      if(DoIllegalValidate(orderDate,'required')){
        alert('请选择日期'); return;
      }
      DoAjaxPost('/shop/daily',{'goodsData':goodsData,'orderDate':orderDate},'/shop/daily-list'); //'/shop/journal-list'
    }
  });


  $('#shopWeeklySubmit').click(function() {
    var goodsData = {};
    var i = 0;
    $('#shopWeeklyTbody tr').each(function(){
      var trObj = $(this);
      var itemId = trObj.attr('itemId');
      var cost = trObj.find('.cost').html();
      var reserves = trObj.find('.reserves').val();
      if(reserves.length>0){
        goodsData[itemId] = {"cost":cost,"reserves":reserves};
        i ++;
      }
    });
    if(i>0){
      var orderDate = $('#datepicker').val();
      if(DoIllegalValidate(orderDate,'required')){
        alert('请选择日期'); return;
      }
      DoAjaxPost('/shop/weekly',{'goodsData':goodsData,'orderDate':orderDate},'/shop/weekly-list'); //'/shop/journal-list'
    }
  });

  $('#shopDailyRow #datepicker').change(function(){
    var date = $(this).val();
//	alert(date);
    window.location.href = "/shop/daily?date="+date;
  });

  $('#shopWeeklyRow #datepicker').change(function(){
    var date = $(this).val();
//	alert(date);
    window.location.href = "/shop/weekly?date="+date;
  });
});

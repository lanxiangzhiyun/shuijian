$(function(){
/*
 |=========================================================================================
 |					B E G I N ...
 */

 /*

 | XXXXXXXXXXXXXXXXXXXXXXXXXXXX
 | eg：XXXXXXXXXXXXXXXXXXXXXXXXXXXX

 */

 /*
 |=========================================================================================
 */


/*

 | 初始化日期控件
 | eg：

 */
if( $( "#datepicker" ).length>0 || $("#datepicker_third").length>0
		|| $("#datepicker_third").length>0 || $("#datepicker_forth").length>0 ){
	DatePickerInit();
}





/*

 | input前后+-button
 | eg：

 */
$(document).on('click','.btn_decrement',function(){
	var inputObj = $(this).next();
	var quantity = inputObj.val();
	quantity = parseInt(quantity);
	if(quantity<=1) return;
	inputObj.val(quantity-1);
});
$(document).on('click','.btn_increment',function(){
	var inputObj = $(this).prev();
	var quantity = inputObj.val();
	quantity = parseInt(quantity);
	inputObj.val(quantity+1);
});



/*

 | 选中th中的checkbox，tr中的checkbox全选
 | eg：

 */
$(document).on('click','#checkboxAll',function(){
	if($(this).prop('checked')){
		$("td .checkHandle").prop('checked',true);
	}else{
		$("td .checkHandle").prop('checked',false);
	}
});



/*

 | 限制input框只能输入数字
 | eg：优惠券-基础模版，Add时填写面值

 */
$(document).on('keyup','.onlyDigitData',function(){
	var value = $(this).val();
	if(value=='.'){
		$(this).val('');
	}else{
		var reg = /^[\+\-]?\d*?\.?\d*?$/;
		if(reg.test(value)){
			$(this).val(value);
		}else{
			$(this).val('');
		}
	}
});



/*
|'Cache_All_Citys' => url('cache/clear-all-citys'),
|'Cache_Districts_By_CityID' => url('cache/clear-districts-by-city-id'),
|'Cache_All_Shops' => url('cache/clear-all-shops'),
|'Cache_Shops_By_CityID' => url('cache/clear-shops-by-city-id'),
|'Cache_Areas_By_ShopID' => url('cache/clear-areas-by-shop-id'),
|'Cache_Sites_By_ShopID' => url('cache/clear-sites-by-shop-id'),
*/

$(document).on('change','#cacheCitySelect',function(){
	$("#cacheShopSelect option").not("#cacheShopSelect option:eq(0)").remove();
	var cityId = $(this).val();
	if(cityId) {
		var data = DoAjaxPost('/iadmin.php/Cache/shops_by_city_id/cityid/'+cityId,{});
		var bodyStr = '';
		$.each(data,function(i,item) {
			bodyStr += '<option value='+item['shop_id']+'>'+item['shop_name']+'</option>';
		});
		$("#cacheShopSelect").append(bodyStr);
	}
});


$(document).on('change','#cacheShopSelect',function(){
	$("#cacheServiceSiteSelect option").not("#cacheServiceSiteSelect option:eq(0)").remove();
	var shopId = $(this).val();
	if(shopId) {
		var data = DoAjaxPost('/cache/sites-by-shop-id/'+shopId,{});
		var bodyStr = '';
		$.each(data,function(i,item) {
			bodyStr += '<option value='+item['id']+'>'+item['name']+'</option>';
		});
		$("#cacheServiceSiteSelect").append(bodyStr);
	}
});






/*
|=========================================================================================
*/

/*
| XXXXXXXXXXXXXXXXXXXXXXXXXXXX
| eg：XXXXXXXXXXXXXXXXXXXXXXXXXXXX

*/

/*
|					E N D ...
|=========================================================================================
*/
});

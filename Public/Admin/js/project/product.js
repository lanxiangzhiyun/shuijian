$(function() {

/*
|============================================================================================================
|ROUTES_Product : ProductController.
|============================================================================================================
*/
// 添加产品分类
$('#productCategoryAdd').click(function(){
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="title" class="control-label">名称</label>';
	bodyHtml += '			<input type="text" class="form-control" id="title" name="data[cat_name]" value=""/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="orderby" class="control-label">排序值</label>';
	bodyHtml += '			<input type="text" class="form-control" id="orderby" name="data[sort_order]" value=""/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('添加新品类',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var titleInput = $("#title");
		var orderbyInput = $("#orderby");
		if(DoIllegalValidate2(new Array(titleInput,orderbyInput),'required')) return;
		if(DoIllegalValidate2(new Array(titleInput),'unique', new Array('/iadmin.php/Product/category_title_check'))) return;
		var ajaxResult = DoAjaxPost('/iadmin.php/Product/category_edit',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			$('#modal_main').modal('hide');
			window.location.href = window.location.href;
		}
	});
});
$(document).on('click','.categoryDelete',function(){
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var name = trObj.find('.cTitle').text();
	DoDeleteTr('/iadmin.php/Product/category_delete',trObj,name,id)
});
$(document).off('click','.categoryUpdate');
$(document).on('click','.categoryUpdate',function() {
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var title = trObj.find('.cTitle').text();
	var orderby = trObj.find('.cOrderby').html();
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="title" class="control-label">名称</label>';
	bodyHtml += '			<input class="form-control" id="title" name="title" value="'+title+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label class="control-label">排序值</label>';
	bodyHtml += '			<input class="form-control" id="orderby" name="orderby" value="'+orderby+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<input type="hidden" id="categoryId" name="categoryId" value="'+id+'"/>';
	bodyHtml += '</form>';
	InitShowModal('编辑产品分类',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var titleInput = $("#title");
		var orderbyInput = $("#orderby");
		if(DoIllegalValidate2(new Array(titleInput,orderbyInput),'required')) return;
		if(DoIllegalValidate2(new Array(titleInput),'unique', new Array('/iadmin.php/Product/category_title_check'))) return;
		var ajaxResult = DoAjaxPost('/iadmin.php/Product/category_edit',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			trObj.find(".cTitle").html(titleInput.val());
			trObj.find(".cOrderby").html(orderbyInput.val());
		}else{
			alert('update failure');
		}
		$('#modal_main').modal('hide');
	});
});


//添加品类
$('#productItemsCategoryAdd').click(function(){
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="title" class="control-label">名称</label>';
	bodyHtml += '			<input type="text" class="form-control" id="title" name="data[type_name]" value=""/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="code" class="control-label">代码</label>';
	bodyHtml += '			<input type="text" class="form-control" id="code" name="data[type_code]" value=""/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('添加新品类',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var titleInput = $("#title");
		var codeInput = $("#code");
		if(DoIllegalValidate2(new Array(titleInput,codeInput),'required')) return;
		if(DoIllegalValidate2(new Array(titleInput,codeInput),'unique', new Array('/iadmin.php/Product/title_unique_check','/iadmin.php/Product/code_unique_check'))) return;
		var ajaxResult = DoAjaxPost('/iadmin.php/Product/type_edit',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			$('#modal_main').modal('hide');
			window.location.href = window.location.href;
		}
	});
});
//基础产品类型筛选
$("#productItemsTypeSelect").change(function() {
	DoChangeSelect($(this), 't', /t=.*/);
});
//基础产品上下架筛选
$("#productItemsOnsaleSelect").change(function() {
	DoChangeSelect($(this), 'o', /o=.*/);
});

//添加基础产品
$("#productItemsAdd").click(function() {
	var productItemsCategorySelect = $("#productItemsCategory").html();
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="title" class="control-label">名称</label>';
	bodyHtml += '			<input type="text" class="form-control" max="200" id="title" name="title" value=""/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label class="control-label">类型</label>';
	bodyHtml += '			<select class="form-control" name="type" id="itemsTypeSelect">';
	bodyHtml += '				<option value="">请选择类型</option>';
	bodyHtml += '				<option value="1">进口</option>';
	bodyHtml += '				<option value="2">国产</option>';
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label class="control-label">类型</label>';
	bodyHtml += '			<select class="form-control" name="category" id="productItemsCategorySelect">';
	bodyHtml += 				productItemsCategorySelect;
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="barcode" class="control-label">编码</label>';
	bodyHtml += '			<input type="text" class="form-control" max="200" id="barcode" name="barcode" value=""/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('添加基础产品',bodyHtml);
	$("#itemsTypeSelect").change(function() {
		$("#productItemsCategorySelect option").prop('selected',false);
		$("#barcode").val('');
	});
	$("#productItemsCategorySelect").change(function() {
		$("#barcode").val('');
		var type = $("#itemsTypeSelect");
		var category = $(this);
		if(DoIllegalValidate2(new Array(type,category),'required')) return;
		var ajaxResult = DoAjaxPost('/product/base-barcode',{'type':type.val(),'category':category.val()});
		if(ajaxResult['title'] == 'success') {
			$("#barcode").val(ajaxResult['data']);
		}
	});
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var titleInput = $("#title");
		var typeInput = $("#itemsTypeSelect");
		var categoryInput = $("#productItemsCategorySelect");
		var barcodeInput = $("#barcode");
		if(DoIllegalValidate2(new Array(titleInput,typeInput,categoryInput,barcodeInput),'required')) return;
		if(DoIllegalValidate2(new Array(titleInput,barcodeInput),'unique', new Array('/product/item-name-unique-check','/product/item-barcode-unique-check'))) return;
		var ajaxResult = DoAjaxPost('/product/add-base',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			var data = ajaxResult['data'];
			$('#productItemsTable tbody').prepend($('#productItemsTable tbody tr').first().clone());
			var currTr = $('#productItemsTable tbody tr').first();
			currTr.attr('trid',data['id']);
			currTr.find('.title').html(data['title']);
			currTr.find('.IType').html(typeInput.find('option:selected').html());
			currTr.find('.ICode').html(data['category']);
			currTr.find('.CTitle').html(categoryInput.find('option:selected').html());
			currTr.find('.IBarcode').html(data['barcode']);
			currTr.find('.productItemsOnsale').prop('checked',true);
			currTr.find('.productItemsHandle').empty();
			currTr.find('.productItemsHandle').append('<a href="javascript:;" class="btn btn-gray btn-sm btn-icon icon-left productItemsEdit">编辑</a>');
			$('#modal_main').modal('hide');
		}
	});
});
//编辑基础产品
$(document).off('click','.productItemsEdit');
$(document).on('click','.productItemsEdit',function() {
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var title = trObj.find('.title').text();
	var type = trObj.find('.IType').html();
	var category = trObj.find('.CTitle').html();
	var barcode = trObj.find('.IBarcode').html();
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="title" class="control-label">名称</label>';
	bodyHtml += '			<input type="text" class="form-control" max="200" id="title" name="title" value="'+title+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label class="control-label">类型</label>';
	bodyHtml += '			<select class="form-control" disabled>';
	bodyHtml += '				<option value="">'+type+'</option>';
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label class="control-label">类型</label>';
	bodyHtml += '			<select class="form-control" disabled>';
	bodyHtml += '				<option value="">'+category+'</option>';
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="barcode" class="control-label">编码</label>';
	bodyHtml += '			<input type="text" class="form-control" max="200" disabled value="'+barcode+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<input type="hidden" id="itemsId" name="itemsId" value="'+id+'"/>';
	bodyHtml += '</form>';
	InitShowModal('编辑基础产品',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var titleInput = $("#title");
		var itemsIdInput = $("#itemsId");
		if(DoIllegalValidate2(new Array(titleInput,itemsIdInput),'required')) return;
		if(DoIllegalValidate2(new Array(titleInput),'unique', new Array('/product/item-name-unique-check/'+itemsIdInput.val()))) return;
		var ajaxResult = DoAjaxPost('/product/edit-base',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			trObj.find(".title").html(titleInput.val());
			$('#modal_main').modal('hide');
		}
	});
});
//上下架基础产品
$(document).off('click','.productItemsOnsale');
$(document).on('click','.productItemsOnsale',function(data){
	var trobj	 	=	$(this).parent().parent();
	var itemsId 	=	trobj.attr('trid');
	if(DoIllegalValidate(itemsId,'required')){
		alert('没有获取基础产品ID');return;
	}
	var isChecked	=	$(this).prop('checked');
	var onsale 		=	isChecked ? 1 : 0;
	var ajaxResult = DoAjaxPost('/product/onsale-base',{itemsId:itemsId,onsale:onsale});
	if(ajaxResult.title=='success') {
		trobj.find('.productItemsHandle').empty();
		if(onsale == 1) {
			trobj.find('.productItemsHandle').append('<a href="javascript:;" class="btn btn-gray btn-sm btn-icon icon-left productItemsEdit">编辑</a>');
		} else {
			trobj.find('.productItemsHandle').append('<a href="javascript:;" class="btn btn-gray btn-sm btn-icon icon-left productItemsEdit">编辑</a>');
			trobj.find('.productItemsHandle').append('<a href="javascript:;" class="btn btn-gray btn-sm btn-icon icon-left productItemsDelete">删除</a>');
		}
	} else {
		if(onsale == 1) {
			trobj.find('.productItemsOnsale').prop('checked',false);
		} else {
			trobj.find('.productItemsOnsale').prop('checked',true);
		}
		alert(ajaxResult.data);
	}
});
//删除基础产品
$(document).on('click','.productItemsDelete',function(data){
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var name = trObj.find('.title').text();
	DoDeleteTr('/product/delete-base',trObj,name,id)
});


//销售产品分类筛选
$("#productCategorySelect").change(function() {
	DoChangeSelect($(this), 'type', /type=.*/);
});
//销售产品上下架筛选
$("#productSaleOnsaleSelect").change(function() {
	DoChangeSelect($(this), 'onsale', /onsale=.*/);
});
//上下架销售产品
$(document).off('click','.productSaleOnsale');
$(document).on('click','.productSaleOnsale',function(data){
	var trobj	 	=	$(this).parent().parent();
	var productId 	=	trobj.attr('trid');
	if(DoIllegalValidate(productId,'required')){
		alert('没有获取销售产品ID');return;
	}
	var isChecked	=	$(this).prop('checked');
	var onsale 		=	isChecked ? 1 : 0;
	var ajaxResult = DoAjaxPost('/product/onsale-sale',{productId:productId,onsale:onsale});
	if(ajaxResult.title=='success') {
		trobj.find('.productSaleHandle').empty();
		if(onsale == 1) {
			trobj.find('.productSaleHandle').append('<a href="javascript:;" class="btn btn-turquoise btn-sm btn-icon icon-left productSaleDetail">详情</a>');
			trobj.find('.productSaleHandle').append('<a href="javascript:;" class="btn btn-turquoise btn-sm btn-icon icon-left productSalePicture">图片</a>');
			trobj.find('.productSaleHandle').append('<a href="/product/goods/'+productId+'" class="btn btn-turquoise btn-sm btn-icon icon-left">上架店铺</a>');
		} else {
			trobj.find('.productSaleHandle').append('<a href="javascript:;" class="btn btn-turquoise btn-sm btn-icon icon-left productSaleDetail">详情</a>');
			trobj.find('.productSaleHandle').append('<a href="javascript:;" class="btn btn-turquoise btn-sm btn-icon icon-left productSalePicture">图片</a>');
			trobj.find('.productSaleHandle').append('<a href="javascript:;" class="btn btn-turquoise btn-sm btn-icon icon-left productSaleDelete">删除</a>');
		}
	} else {
		if(onsale == 1) {
			trobj.find('.productSaleOnsale').prop('checked',false);
		} else {
			trobj.find('.productSaleOnsale').prop('checked',true);
		}
		alert(ajaxResult.data);
	}
});

//通过品类筛选基础产品
$('#productItemCategorySelect').change(function(){
	var code = $(this).val();
	$('#allProductItems tr').addClass('hide');
	$('.productTR_'+code).removeClass('hide');
});
$('#productAddOnsaleItemList .iswitch-secondary').click(function(){
	var check = $(this).prop("checked");
	var trObj = $(this).parent().parent();
	var itemId = trObj.attr('itemid');
	if(check){
		$('#product_items_waiting_add').prepend(trObj.clone());
	}else{
		$('#product_items_waiting_add').find('tr[itemid='+itemId+']').remove();
	}
});
$(document).on('click','#product_items_waiting_add .iswitch-secondary',function(){
	var check = $(this).prop("checked");
	var trObj = $(this).parent().parent();
	var itemId = trObj.attr('itemid');
	if(!check){
		$('#product_items_waiting_add').find('tr[itemid='+itemId+']').remove();
		$('#productAddOnsaleItemList').find('tr[itemid='+itemId+'] td .iswitch-secondary').prop('checked',false);
	}
});
$('#productAddSumit').click(function(){
	var cateSelect = $('#productAddCategorySelect');
	var titleInput = $('#title');
	var shopPriceInput = $('#shopPrice');
	var marketPriceInput = $('#marketPrice');
	var weightInput = $('#weight');
	var pictureInput = $('#picture');
	var thumbInput = $('#thumb');
	var introInput = $('#intro');
	var sizeInput = $('#size');
	var creditPriceInput = $('#credit_price');
	if(DoIllegalValidate2(new Array(cateSelect,titleInput,shopPriceInput,marketPriceInput,weightInput,pictureInput,thumbInput,introInput),'required')) return;
	var itemCount = 0 ;
	var items = [];
	$('#product_items_waiting_add tr').each(function(){
		if($(this).attr('itemid')){
			var itemId = $(this).attr('itemid');
			var itemQuantity = $(this).find('.onlyDigitData').val();
			itemCount ++;
			items.push(itemId+":"+itemQuantity);
		}
	});
	if(itemCount==0){
		alert('请选择基础产品');
	}else{
		$('#hiddenItems').val(items);
		$('#productInfoForm').submit();
	}
});


//编辑销售产品详情信息
$(document).on('click','.productSaleDetail',function(){
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var title = trObj.find('.title').text();
	var categoryId = trObj.find('.category').attr('category');
	var weight = trObj.find('.weight').html();
	var size = trObj.find('.size').html();
	var intro = trObj.find('.intro').attr('title');
	var detail = trObj.find('.detail').html();
	var market_price = trObj.find('.market_price').html();
	var shop_price = trObj.find('.shop_price').html();
	var credit_price = trObj.find('.credit_price').html();
	var productCategorySelect = $("#productCategorySelect").html();
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml +=	'	<input type="hidden" id="productId" name="id" value="'+id+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="title" class="control-label">名称</label>';
	bodyHtml += '			<input type="text" class="form-control" id="title" name="title" value="'+title+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label class="control-label">分类</label>';
	bodyHtml += '			<select class="form-control" name="category" id="productCategorySelectModal">';
	bodyHtml += 				productCategorySelect;
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="weight" class="control-label">重量(kg)</label>';
	bodyHtml += '			<input type="text" class="form-control" id="weight" name="weight" value="'+weight+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="size" class="control-label">规格(size)</label>';
	bodyHtml += '			<input type="text" class="form-control" id="size" name="size" value="'+size+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="intro" class="control-label">简介</label>';
	bodyHtml += '			<input type="text" class="form-control" id="intro" name="intro" value="'+intro+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-4">';
	bodyHtml += '			<label class="control-label">市场价</label>';
	bodyHtml += '			<input type="text" class="form-control onlyDigitData" id="market_price" name="market_price" value="'+market_price+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '		<div class="col-md-4">';
	bodyHtml += '			<label class="control-label">销售价</label>';
	bodyHtml += '			<input type="text" class="form-control onlyDigitData" id="shop_price" name="shop_price" value="'+shop_price+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '		<div class="col-md-4">';
	bodyHtml += '			<label class="control-label">积分价</label>';
	bodyHtml += '			<input type="text" class="form-control onlyDigitData" id="credit_price" name="credit_price" value="'+credit_price+'"/>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="shop_price" class="control-label">商品详情</label>';
	bodyHtml += '			<textarea class="form-control ckeditor" rows="10" name="detail" id="detail">'+detail+'</textarea>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('修改商品信息',bodyHtml);
	CKEDITOR.replace( 'detail',{
		filebrowserImageUploadUrl : '/product/image?_token='+$('#csrf_token').val(),
  	});
	$("#productCategorySelectModal option").prop('selected',false);
	$("#productCategorySelectModal option[value="+categoryId+"]").prop('selected',true);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		if(!$("#productId").val()) {
			alert('没有获取到销售产品id');return;
		}
		var titleInput = $("#title");
		var categoryInput = $('#productCategorySelectModal');
		var weightInput = $("#weight");
		var sizeInput = $("#size");
		var creditPriceInput = $("#credit_price");
		var introInput = $("#intro");
		var marketInput = $('#market_price');
		var shopInput = $('#shop_price');
		var productIdInput = $('#productId');
		if(DoIllegalValidate2(new Array(titleInput,weightInput,introInput,marketInput,shopInput),'required')) return;
		if(DoIllegalValidate2(new Array(titleInput),'unique', new Array('/product/product-title-unique-check/'+productIdInput.val()))) return;
		$("#detail").val(CKEDITOR.instances.detail.getData());
		var ajaxResult = DoAjaxPost('/product/sale-detail',$('#modal_main form').serialize());
		if(ajaxResult.title=='success'){
			trObj.find(".title").html(titleInput.val());
			trObj.find(".category").attr('category',categoryInput.val());
			trObj.find(".category").html($('#productCategorySelectModal option:selected').text());
			trObj.find(".weight").html(weightInput.val());
			trObj.find(".size").html(sizeInput.val());
			trObj.find(".credit_price").html(creditPriceInput.val());
			trObj.find(".intro").html(introInput.val());
			trObj.find('.intro').attr('title',introInput.val());
			trObj.find(".market_price").html(marketInput.val());
			trObj.find(".shop_price").html(shopInput.val());
			trObj.find(".detail").html( $("#detail").val());
		}
		$('#modal_main').modal('hide');
	});
});

//修改图片信息
$(document).off('click','.productSalePicture');
$(document).on('click','.productSalePicture',function(){
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var picture = trObj.find('.picture').html();
	var thumb = trObj.find('.thumb').html();
	var bodyHtml = '';
	bodyHtml +=	'<form id="productSaleEditImageForm" action="/product/edit-image" enctype="multipart/form-data" method="post">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml +=	'	<input type="hidden" id="productId" name="id" value="'+id+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="picture" class="control-label">缩略图</label>';
	bodyHtml += '			<input type="file" class="form-control" id="picture" name="picture">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<br>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="picture" class="control-label">原缩略图</label>';
	bodyHtml += '			<img src="'+picture+'" height="100px;">';
	bodyHtml += '			<input type="hidden" id="oldPicture" name="oldPicture" value="'+picture+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<br>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="thumb" class="control-label">背景图</label>';
	bodyHtml += '			<input type="file" class="form-control" id="thumb" name="thumb">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<br>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<div class="col-md-12">';
	bodyHtml += '			<label for="picture" class="control-label">背景图</label>';
	bodyHtml += '			<img src="'+thumb+'" height="100px;">';
	bodyHtml += '			<input type="hidden" id="oldThumb" name="oldThumb" value="'+thumb+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div style="clear:both"></div>';
	bodyHtml +=	'</form>';
	InitShowModal('修改商品图片',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		$("#productSaleEditImageForm").submit();
	});
});

// 软删除
$(document).off('click','.productSaleDelete');
$(document).on('click','.productSaleDelete',function(){
	var trObj = $(this).parent().parent();
	var name = trObj.find('.title').text();
	var alertTxt = '销售产品：'+name;
	var id = trObj.attr('trid');
	DoDeleteTr('/product/delete-sale',trObj,alertTxt,id)
});
// 上架店铺
$("#productGoodsAddShop").click(function() {
	var productId = $('#productId').val();
	var productTitle = $('#productTitle').val();
	//不做json处理的ajax
	var ajaxData = DoAjaxPost('/product/all-shop',{productId:productId});
	if(!ajaxData.shop) {
		alert('所有店铺都已被添加');return;
	}
	if(!ajaxData.product) {
		alert('没有获取产品信息');return;
	}
	var shop = '<option value="" cityName="">请选择</option>';
	$.each(ajaxData.shop,function(i,item) {
		shop += '<option value="'+item.id+'" cityName="'+item.city.name+'">'+item.name+'</option>';
	});
	var product = ajaxData.product;
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml +=	'	<input type="hidden" id="productId" name="productId" value="'+productId+'" />';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label class="control-label" for="shopId">选择店铺</label>';
	bodyHtml +=	'			<select class="form-control" id="shopId" name="shopId">';
	bodyHtml +=					shop;
	bodyHtml +=	'			</select>';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label class="control-label" for="onsale">是否上架</label>';
	bodyHtml +=	'			<input type="checkbox" id="onsale" name="onsale" checked="" class="form-control iswitch iswitch-secondary">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label for="latitude" class="control-label">参考售价</label>';
	bodyHtml +=	'			<input type="text" class="form-control onlyDigitData" id="shop_price" name="shop_price" placeholder=""  value="'+product.shop_price+'">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label for="latitude" class="control-label">市场价</label>';
	bodyHtml +=	'			<input type="text" class="form-control onlyDigitData" id="market_price" name="market_price" placeholder=""  value="'+product.market_price+'">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label for="latitude" class="control-label">积分价</label>';
	bodyHtml +=	'			<input type="text" class="form-control onlyDigitData" id="credit_price" name="credit_price" placeholder=""  value="'+product.credit_price+'">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'</form>';
	InitShowModal('添加上架店铺',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var shopInput = $("#shopId");
		var shopPriceInput = $("#shop_price");
		var marketPriceInput = $("#market_price");
		var creditPriceInput = $("#credit_price");
		if(DoIllegalValidate2(new Array(shopInput,shopPriceInput,marketPriceInput),'required')) return;
		var ajaxResult = DoAjaxPost('/product/add-goods',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			var data = ajaxResult['data'];
			$('#productGoodsTable tbody').prepend($('#productGoodsTable tbody tr').first().clone());
			var currTr = $('#productGoodsTable tbody tr').first();
			if(currTr.length <= 0) {
				$('#modal_main').modal('hide');
				window.location.href=window.location.href;
			} else {
				currTr.attr('trid',data['id']);
				currTr.find('.gShop').html('<a href="javascript:;" class="produtGoodsEdit"> '+shopInput.find('option:selected').html()+'&nbsp;&nbsp;<i class="fa fa-edit icon-biger"></i> </a>');
				currTr.find('.gCity').html(shopInput.find('option:selected').attr('cityName'));
				currTr.find('.gShopPrice').html(data['shop_price']);
				currTr.find('.gMarketPrice').html(data['market_price']);
				currTr.find('.gCreditPrice').html(data['credit_price']);
				currTr.find('.gOnsale').html('<input type="checkbox" checked="'+$("#onsale").prop('checked')+'" class="iswitch iswitch-secondary onOffSwitch">');
				currTr.find('.gSoldout').html('<input type="checkbox" class="iswitch iswitch-secondary onOffSoldout">');
				currTr.find('.produtGoodsStockAppendNumber').html('');
				currTr.find('.gGoodsStock').html('<a href="javascript:;" class="produtGoodsStockAdd" title="添加库存详情">添加</a>');
				$('#modal_main').modal('hide');
			}
		}
	});
});

//修改商品价格
$(".produtGoodsEdit").off('click');
$(".produtGoodsEdit").on('click',function() {
	var trObj = $(this).parent().parent();
	var goodId = trObj.attr('trid');
	var shop_price = trObj.find('.gShopPrice').html();
	var market_price = trObj.find('.gMarketPrice').html();
	var credit_price = trObj.find('.gCreditPrice').html();
	var productTitle = $("#productTitle").val();
	var bodyHtml = '';
	bodyHtml +=	'<form class="form-horizontal" fole="form">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml +=	'	<input type="hidden" id="goodId" name="goodId" value="'+goodId+'" />';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label for="latitude" class="control-label">产品名</label>';
	bodyHtml +=	'			<input type="text" class="form-control" disabled placeholder=""  value="'+productTitle+'">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label for="latitude" class="control-label">参考售价</label>';
	bodyHtml +=	'			<input type="text" class="form-control onlyDigitData" id="shop_price" name="shop_price" placeholder=""  value="'+shop_price+'">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label for="latitude" class="control-label">市场价</label>';
	bodyHtml +=	'			<input type="text" class="form-control onlyDigitData" id="market_price" name="market_price" placeholder=""  value="'+market_price+'">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'	<div class="form-group">';
	bodyHtml +=	'		<div class="col-md-12">';
	bodyHtml +=	'			<label for="latitude" class="control-label">积分价</label>';
	bodyHtml +=	'			<input type="text" class="form-control onlyDigitData" id="credit_price" name="credit_price" placeholder=""  value="'+credit_price+'">';
	bodyHtml +=	'		</div>';
	bodyHtml +=	'	</div>';
	bodyHtml +=	'</form>';
	InitShowModal('修改商品价格',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		if(DoIllegalValidate($("#goodId").val(),'required')){
			alert('没有获取商品ID');return;
		}
		var shopPriceInput = $("#shop_price");
		var marketPriceInput = $("#market_price");
		var creditPriceInput = $("#credit_price");
		if(DoIllegalValidate2(new Array(shopPriceInput,marketPriceInput),'required')) return;
		var ajaxResult = DoAjaxPost('/product/edit-goods',$('#modal_main form').serialize());
		if(ajaxResult.title=='success'){
			trObj.find(".gShopPrice").html(shopPriceInput.val());
			trObj.find(".gMarketPrice").html(marketPriceInput.val());
			trObj.find(".gCreditPrice").html(creditPriceInput.val());
		}
		$('#modal_main').modal('hide');
	});
});

//products/gooods删除
$(document).on('click','.produtGoodsDelete',function(){
	var trObj = $(this).parent().parent();
	var id = trObj.attr('trid');
	var gShop = trObj.find('.gShop').html();
	alertTxt = gShop;
	DoDeleteTr('/product/delete-goods',trObj,alertTxt,id);
});

//商品上下架
$(document).off('click','.onOffSwitch');
$(document).on('click','.onOffSwitch',function(data){
	var trobj	 	=	$(this).parent().parent();
	var goodId 	=	trobj.attr('trid');
	if(DoIllegalValidate(goodId,'required')){
		alert('没有获取商品ID');return;
	}
	var isChecked	=	$(this).prop('checked');
	var onsale 		=	isChecked ? 1 : 0;
	var ajaxResult = DoAjaxGet('/product/on-off-switch',{goodId:goodId,onsale:onsale});
	if(ajaxResult.title=='failure') {
		alert(ajaxResult.data);
	}
});
//商品是否售罄
$(document).off('click','.onOffSoldout');
$(document).on('click','.onOffSoldout',function(data){
	var trobj	 	=	$(this).parent().parent();
	var goodId 	=	trobj.attr('trid');
	if(DoIllegalValidate(goodId,'required')){
		alert('没有获取商品ID');return;
	}
	var isChecked	=	$(this).prop('checked');
	var soldout 		=	isChecked ? 1 : 0;
	var ajaxResult = DoAjaxPost('/product/on-off-soldout',{goodId:goodId,soldout:soldout});
	if(ajaxResult.title=='failure') {
		alert('修改失败');
	}
});
//添加库存，商品管理也在使用
$(document).off('click','.produtGoodsStockAdd');
$(document).on('click','.produtGoodsStockAdd',function(data){
	var trobj	 	=	$(this).parent().parent();
	var goodId = trobj.attr('trid');
	if(DoIllegalValidate(goodId,'required')){
		alert('没有获取商品ID');return;
	}
	if(DoIllegalValidate(goodId,'positivenum')){
		alert('商品ID必须为数字');return;
	}
	var bodyHtml = '';
	bodyHtml += '<form role="form" class="form-horizontal">';
	bodyHtml +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">总库存</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="number" class="form-control onlyDigitData" name="stockSettingSum" min="0" placeholder=""  value="">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group-separator"></div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">发送警告短信库存</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="number" class="form-control onlyDigitData" name="stockSave" min="0" placeholder=""  value="">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group-separator"></div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">是否启用库存</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="goodsStockStatus" value="1" checked>是';
	bodyHtml += '			</label>';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="goodsStockStatus" value="0">否';
	bodyHtml += '			</label>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group-separator"></div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">是否发送警报</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="isSendMessage" value="1" checked>是';
	bodyHtml += '			</label>';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="isSendMessage" value="0">否';
	bodyHtml += '			</label>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group-separator"></div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">警报人姓名</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control" name="alarmUserName" placeholder="请输入报警人姓名"  value="">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group-separator"></div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">警报人电话</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control onlyDigitData" name="alarmMobile" placeholder="请输入报警电话"  value="">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group-separator"></div>';
	bodyHtml += '	<div class="form-group hide">';
	bodyHtml += '		<label class="col-sm-3 control-label">使用状态</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="alarmStatus" value="1" checked>正常';
	bodyHtml += '			</label>';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="alarmStatus" value="0">停用';
	bodyHtml += '			</label>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<input type="hidden" value="'+goodId+'" name="goodId">';
	bodyHtml += '</form>';
	InitShowModal('添加库存信息',bodyHtml);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var stockSettingSum = $('#modal_main .modal-body').find("input[name='stockSettingSum']");
		var stockSave = $('#modal_main .modal-body').find("input[name='stockSave']");
		var goodsStockStatus = $('#modal_main .modal-body').find("input[name='goodsStockStatus']");
		var isSendMessage = $('#modal_main .modal-body').find("input[name='isSendMessage']");
		var alarmUserName = $('#modal_main .modal-body').find("input[name='alarmUserName']");
		var alarmMobile = $('#modal_main .modal-body').find("input[name='alarmMobile']");
		var alarmStatus = $('#modal_main .modal-body').find("input[name='alarmStatus']");
		var goodId = $('#modal_main .modal-body').find("input[name='goodId']").val();
		if(DoIllegalValidate2(new Array(stockSettingSum,stockSave,goodsStockStatus,isSendMessage,alarmUserName,alarmMobile,alarmStatus),'required')) return;
		if(DoIllegalValidate2(new Array(alarmMobile),'mobile')) return;
		if(Number(stockSave.val()) > Number(stockSettingSum.val())) {
			alert('发送警告短信库存不能大于总库存');return;
		}
		var ajaxResult = DoAjaxPost('/product/goods-stock-add',$('#modal_main form').serialize());
		if('success' == ajaxResult.title){
			window.location.href=window.location.href;
		}
	});
});
//设置库存信息，商品管理也在使用
$(document).off('click','.produtGoodsStockEdit');
$(document).on('click','.produtGoodsStockEdit',function(data){
	var trobj	 	=	$(this).parent().parent();
	var goodsStockId = trobj.find('.gsid').text();
	var stockSettingSum = trobj.find('.gssum').html();
	var stockSave = trobj.find('.gssave').html();
	var goodsStockStatus = trobj.find('.gsstatus').html();
	var isSendMessage = trobj.find('.gsmessage').html();
	var alarmUserName = trobj.find('.saname').html();
	var alarmMobile = trobj.find('.samobile').html();
	var alarmStatus = trobj.find('.sastatus').html();
	var stockAlarmId = trobj.find('.said').text();
	if(DoIllegalValidate(stockAlarmId,'required')){
		alert('没有获取报警信息');return;
	}
	if(DoIllegalValidate(stockAlarmId,'positivenum')){
		alert('报警ID必须为数字');return;
	}
	if(DoIllegalValidate(goodsStockId,'required')){
		alert('没有获取库存ID');return;
	}
	if(DoIllegalValidate(goodsStockId,'positivenum')){
		alert('库存ID必须为数字');return;
	}
	var bodyStr = '';
	bodyStr += '<form role="form" class="form-horizontal" id="goodsListStockFormAdd">';
	bodyStr +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">总库存</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<input type="text" class="form-control" readonly name="stockSettingSum" min="0" placeholder=""  value="'+stockSettingSum+'">';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">发送警告短信库存</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<input type="number" class="form-control onlyDigitData" name="stockSave" min="0" placeholder=""  value="'+stockSave+'">';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">是否启用库存</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<label class="radio-inline">';
	bodyStr += '				<input type="radio" name="goodsStockStatus" value="1" '+((goodsStockStatus == 1) ? 'checked' : '')+'>是';
	bodyStr += '			</label>';
	bodyStr += '			<label class="radio-inline">';
	bodyStr += '				<input type="radio" name="goodsStockStatus" value="0" '+((goodsStockStatus != 1) ? 'checked' : '')+'>否';
	bodyStr += '			</label>';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">是否发送警告短信</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<label class="radio-inline">';
	bodyStr += '				<input type="radio" name="isSendMessage" value="1" '+((isSendMessage == 1) ? 'checked' : '')+'>是';
	bodyStr += '			</label>';
	bodyStr += '			<label class="radio-inline">';
	bodyStr += '				<input type="radio" name="isSendMessage" value="0" '+((isSendMessage != 1) ? 'checked' : '')+'>否';
	bodyStr += '			</label>';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">报警人姓名</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<input type="text" class="form-control" name="alarmUserName" placeholder="请输入报警人姓名"  value="'+alarmUserName+'">';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">报警人电话</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<input type="text" class="form-control onlyDigitData" name="alarmMobile" placeholder="请输入报警电话"  value="'+alarmMobile+'">';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">使用状态</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<label class="radio-inline">';
	bodyStr += '				<input type="radio" name="alarmStatus" value="1" '+((alarmStatus == 1) ? 'checked' : '')+'>正常';
	bodyStr += '			</label>';
	bodyStr += '			<label class="radio-inline">';
	bodyStr += '				<input type="radio" name="alarmStatus" value="0" '+((alarmStatus != 1) ? 'checked' : '')+'>停用';
	bodyStr += '			</label>';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<input type="hidden" value="'+goodsStockId+'" name="goodsStockId" />';
	bodyStr += '	<input type="hidden" value="'+stockAlarmId+'" name="stockAlarmId" />';
	bodyStr += '</form>';
	InitShowModal('修改库存信息',bodyStr);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var stockSettingSum = $('#modal_main .modal-body').find("input[name='stockSettingSum']");
		var stockSave = $('#modal_main .modal-body').find("input[name='stockSave']");
		var goodsStockStatus = $('#modal_main .modal-body').find("input[name='goodsStockStatus']");
		var isSendMessage = $('#modal_main .modal-body').find("input[name='isSendMessage']");
		var alarmUserName = $('#modal_main .modal-body').find("input[name='alarmUserName']");
		var alarmMobile = $('#modal_main .modal-body').find("input[name='alarmMobile']");
		var alarmStatus = $('#modal_main .modal-body').find("input[name='alarmStatus']");
		if(DoIllegalValidate2(new Array(stockSettingSum,stockSave,goodsStockStatus,isSendMessage,alarmUserName,alarmMobile,alarmStatus),'required')) return;
		if(DoIllegalValidate2(new Array(alarmMobile),'mobile')) return;
		if(Number(stockSave.val()) > Number(stockSettingSum.val())) {
			alert('发送警告短信库存不能大于总库存');return;
		}
		var data = DoAjaxPost('/product/goods-stock-edit',$('#modal_main form').serialize());
		if('success' == data.title){
			window.location.href=window.location.href;
		}
	});
});
//增加库存量，商品管理也在使用
$(document).off('click','.produtGoodsStockAppend');
$(document).on('click','.produtGoodsStockAppend',function(data){
	var trobj	 	=	$(this).parent().parent();
	var goodsStockId = $.trim(trobj.find('.gsid').text());
	var stockRemain = trobj.find('.produtGoodsStockAppendNumber').html();
	if(DoIllegalValidate(goodsStockId,'required')){
		alert('没有获取库存ID');return;
	}
	if(DoIllegalValidate(goodsStockId,'positivenum')){
		alert('库存ID必须为数字');return;
	}
	var bodyStr = '';
	bodyStr += '<form role="form" class="form-horizontal">';
	bodyStr +=	'	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">剩余库存数</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<input type="text" readonly name="stockRemain" value="'+stockRemain+'" class="form-control">';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<div class="form-group-separator"></div>';
	bodyStr += '	<div class="form-group">';
	bodyStr += '		<label class="col-sm-3 control-label">增减库存数</label>';
	bodyStr += '		<div class="col-sm-9">';
	bodyStr += '			<input type="number" name="increaseCount" value="" class="form-control">';
	bodyStr += '		</div>';
	bodyStr += '	</div>';
	bodyStr += '	<input type="hidden" value="'+ goodsStockId +'" name="goodsStockId" />';
	bodyStr += '</form>';
	InitShowModal('修改库存数量',bodyStr);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		var stockRemain = $('#modal_main .modal-body').find("input[name='stockRemain']");
		var increaseCount = $('#modal_main .modal-body').find("input[name='increaseCount']");
		if((Number(stockRemain.val()) + Number(increaseCount.val())) < 0) {
			alert('剩余库存不能小于0');return;
		}
		if(DoIllegalValidate2(new Array(stockRemain,increaseCount),'required')) return;
		var ajaxResult = DoAjaxPost('/product/edit-stock',$('#modal_main form').serialize());
		if('success' == ajaxResult['title']){
			window.location.href=window.location.href;
		}
	});
});





/*
|============================================================================================================
|ROUTES_USER : GoodsController.
|============================================================================================================
*/
//是否售罄
$(".goodsListSoldoutChecked").click(function() {
	if(!confirm('确定要修改此项？')){
		$(this).attr('checked',false); return;
	}
	var trObject = $(this).parent().parent();
	var goods_id = trObject.attr('trid');
	var isChecked =	$(this).prop('checked');
	var soldout  =	isChecked ? 1 : 0;
	var data = DoAjaxPost('/goods/edit-soldout',{goods_id:goods_id,soldout:soldout});
	if('success' != data.title) {
		alert('修改失败');return;
	}
});

//下架商品
$('.goodsListOffsale').click(function() {
	if(!confirm('你确定真的下架改商品？')) return;
	var trObject = $(this).parent().parent();
	var goods_id = trObject.attr('trid');
	if(DoIllegalValidate(goods_id,'required')){
		alert('没有获取商品ID');return;
	}
	if(DoIllegalValidate(goods_id,'positivenum')){
		alert('商品ID必须为数字');return;
	}
	var data = DoAjaxPost('/goods/edit-onsale',{goods_id:goods_id,onsale:0});
	if('success' == data.title) {
		window.location.href = window.location.href;
	} else {
		alert(data.data);return;
	}
});
// 删除商品
$('.goodsListDelete').click(function() {
	if(!confirm('确定要删除？')) return;
	var trObject = $(this).parent().parent();
	var goods_id = trObject.attr('trid');
	var data = DoAjaxPost('/goods/delete',{goods_id:goods_id});
	if('success' == data.title) {
		trObject.remove();
	} else {
		alert(data.data);return;
	}
});

//上架商品
$('.goodsListOnsale').click(function() {
	var trObject = $(this).parent().parent();
	var goods_id = trObject.attr('trid');
	var data = DoAjaxPost('/goods/edit-onsale',{goods_id:goods_id,onsale:1});
	if('success' == data.title) {
		trObject.remove();
	} else {
		alert(data.data);return;
	}
});

//================添加库存,修改库存信息,增减库存数量 和控制器productController用的是同一个====================

//=================================商品排序===================================================
$( "#sortable1" ).sortable({
    connectWith: ".connectedSortable"
}).disableSelection();
$('#goodsDescShopSelect').change(function() {
	DoChangeSelect($(this), 's', /s=[\d]*/);
});
//=================================添加新产品===================================================
//添加新商品
$("#goodsAddGoodsAdd").click(function() {
	var pIDs = [];
	$("#goodsAddTable").find(".product_checkbox").each(function(){
		var type = $(this).prop('checked');
		if(type){
			var id = $(this).attr('pid');
			pIDs.push(id);
		}
	});
	if(pIDs.length < 1) {
		alert('请选择产品');return;
	}
	var data = DoAjaxPost('/goods/add-many',{pids:pIDs});
	if('success' == data.title) {
		window.location.href = window.location.href;
	}
});
//=================================商品活动管理===================================================
//活动上下架
$('.goodsActivityOnactiveOpen').click(function() {
	var trObject = $(this).parent().parent();
	var policyGoodsId = trObject.attr('trid');
	if(DoIllegalValidate(policyGoodsId,'required')){
		alert('没有获取活动ID');return;
	}
	if(DoIllegalValidate(policyGoodsId,'positivenum')){
		alert('活动ID必须为数字');return;
	}
	var data = DoAjaxPost('/activity/edit-activity-status',{policyGoodsId:policyGoodsId,onactive:1});
	if('success' == data.title) {
		$(this).next('.goodsActivityOnactiveClose').removeClass('btn-success');
		$(this).addClass('btn-success');
	}
});
$('.goodsActivityOnactiveClose').click(function() {
	var trObject = $(this).parent().parent();
	var policyGoodsId = trObject.attr('trid');
	if(DoIllegalValidate(policyGoodsId,'required')){
		alert('没有获取活动ID');return;
	}
	if(DoIllegalValidate(policyGoodsId,'positivenum')){
		alert('活动ID必须为数字');return;
	}
	var data = DoAjaxPost('/activity/edit-activity-status',{policyGoodsId:policyGoodsId,onactive:1});
	if('success' == data.title) {
		$(this).prev('.goodsActivityOnactiveOpen').removeClass('btn-success');
		$(this).addClass('btn-success');
	}
});
//删除活动
$('.goodsActivityDelete').click(function() {
	var trObj = $(this).parent().parent();
	var name = trObj.find('.mName').text();
	var alertTxt = '活动名称：'+name;
	var id = trObj.attr('trid');
	DoDeleteTr('/activity/delete-activity',trObj,alertTxt,id)
});
//添加活动
$("#goodsActivityAdd").click(function() {
	var activityManagePolicySelect = $('#activityManageRuleSelect').html();
	var bodyHtml = '';
	bodyHtml += '<form class="form-horizontal" fole="form" id="activityManageFormAdd">';
	bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">选择活动规则</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<select class="form-control" name="policyId" id="activityManagePolicySelect">';
	bodyHtml +=					activityManagePolicySelect;
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">活动名称</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control" name="goodsPoliciesName" id="goodsPoliciesName" placeholder="请输入活动名称" max="50" value="">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">活动描述</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<textarea class="form-control" rows="3" max="100" name="goodsPoliciesDescription" id="goodsPoliciesDescription" placeholder="可不填，100字以内"></textarea>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">选择商品</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<select class="form-control" name="goodsId" id="selectGoodsPolicygoods">';
	bodyHtml += '				<option value="">请选择商品</option>';
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label"><img id="activityManageGoodsImage" src="" height="50px" width="50px" /></label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<label class="col-sm-5 pull-left activityManageGoodsName">产品原名称</label>';
	bodyHtml += '			<label class="col-sm-4 activityManageGoodsPrice">原售价--￥</label>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group" id="activityManageGoodCutPrice">';
	bodyHtml += '		<label class="col-sm-3 control-label">优惠价格</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control onlyDigitData" id="cutPrice" name="cutPrice" max="10" placeholder="请输入优惠价格" value="">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group" id="activityManageGoodCutPrice">';
	bodyHtml += '		<label class="col-sm-3 control-label">有效日期 ： Begin</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<input type="text" class="form-control" name="datepicker" id="datepicker" placeholder="" value="">';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" name="startHouse" id="startHouse">';
	bodyHtml += 				selectHourTime();;
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" id="startMinute" name="startMinute">';
	bodyHtml += 				selectMinuteTime();
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group" id="activityManageGoodCutPrice">';
	bodyHtml += '		<label class="col-sm-3 control-label">有效日期 ： End</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<input type="text" class="form-control" name="datepicker_second" id="datepicker_second" placeholder="" value="">';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" name="endHouse" id="endHouse">';
	bodyHtml += 				selectHourTime();;
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" id="endMinute" name="endMinute">';
	bodyHtml += 				selectMinuteTime();
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">当日执行开始时间</label>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" name="startTimeslotHour" id="startTimeslotHour">';
	bodyHtml += 				selectHourTime();
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" id="startTimeslotMinute" name="startTimeslotMinute">';
	bodyHtml += 				selectMinuteTime();
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">当日执行结束时间</label>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" name="endTimeslotHour" id="endTimeslotHour">';
	bodyHtml += 				selectHourTime();
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" id="endTimeslotMinute" name="endTimeslotMinute">';
	bodyHtml += 				selectMinuteTime();
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">是否上架</label>';
	bodyHtml += '		<div class="col-sm-9 siteStatus">';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="onactive" value="1" checked>是';
	bodyHtml += '			</label>';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="onactive" value="0">否';
	bodyHtml += '			</label>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('添加新活动',bodyHtml);
	DatePickerInit();
	//获取城市，城市只在添加时用到，所以放在这请求
	var $citys = DoAjaxPost('/site/all-city',{});
	var str = '';
	$.each($citys,function(i,item){
		str += '<option value="'+item.id+'">'+item.name+'</option>';
	});
	$("#selectCityPolicygoods").append(str);
	$('#activityManagePolicySelect').change(function() {
		$("#goodsPoliciesName").val($(this).find('option:selected').html());
		var type = $(this).find('option:selected').attr('type');
		//规则是买一送一和第二份半价优惠价隐藏
		if(type == 3 || type == 4) {
			$("#cutPrice").val('');
			$("#activityManageGoodCutPrice").hide();
		} else {
			$("#activityManageGoodCutPrice").show();
		}
	});

	var shopId = $('#goodsActivityShopId').val();
	if(!shopId) {
		alert('没有获取到店铺id');
		window.location.href = window.location.href;
		return;
	}
	var data = DoAjaxPost('/activity/goods-by-shop',{shopId:shopId});
	var str = '';
	$.each(data,function(i,item) {
		if(item['product']) {
			str += '<option value="'+item['id']+'">'+item['product']['title']+'</option>';
		}
	});
	$('#selectGoodsPolicygoods').append(str);

	//根据商品获取商品信息
	$('#selectGoodsPolicygoods').change(function() {
		var goodsId = $(this).val();
		$('#modal_main .modal-body').find("#cutPrice").val('');
		var data = DoAjaxPost('/activity/product-information',{'goodsId':goodsId});
		$('#activityManageGoodsImage').attr('src',data[0]['product']['picture']);
		$('.activityManageGoodsName').html(data[0]['product']['title']);
		$('.activityManageGoodsPrice').html('原售价'+data[0].shop_price+'￥');
	});

	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		$('#activityManageFormAdd').find('.form-group').removeClass('has-error');
		var policyIdInput = $("#activityManagePolicySelect");
		var nameInput = $("#goodsPoliciesName");
		var goodsIdInput = $("#selectGoodsPolicygoods");
		var cutPriceInput = $("#cutPrice");
		var startTimeInput = $("#datepicker");
		var startHouseInput = $("#startHouse");
		var startMinuteInput = $("#startMinute");
		var endTimeInput = $("#datepicker_second");
		var endHouseInput = $("#endHouse");
		var endMinuteInput = $("#endMinute");
		var startTimeslotHour = $('#startTimeslotHour');
		var startTimeslotMinute = $('#startTimeslotMinute');
		var endTimeslotHour = $('#endTimeslotHour');
		var endTimeslotMinute = $('#endTimeslotMinute');
		var onactiveInput = $('#modal_main .modal-body').find("input[name='onactive']:checked");
		if(DoIllegalValidate2(new Array(policyIdInput,nameInput,goodsIdInput),'required')) return;
		//固定价格和首单优惠必须要填cutPrice
		var type = policyIdInput.find('option:selected').attr('type');
		if(type == 1 || type == 2) {
			if(DoIllegalValidate2(new Array(cutPriceInput),'required')) return;
		}
		if(DoIllegalValidate2(new Array(startTimeInput,startHouseInput,startMinuteInput,endTimeInput,endHouseInput,endMinuteInput),'required')) return;
		if(DoIllegalValidate2(new Array(startTimeslotHour,startTimeslotMinute,endTimeslotHour,endTimeslotMinute,onactiveInput),'required')) return;
		var startTimeData = startTimeInput.val()+' '+startHouseInput.val()+':'+startMinuteInput.val();
		var endTimeData = endTimeInput.val()+' '+endHouseInput.val()+':'+endMinuteInput.val();
		if(checkEndTime(startTimeData,endTimeData)) {
			alert('有效结束日期不能小于有效开始日期');return;
		}
		var startTimeslot = startTimeslotHour.val()+':'+startTimeslotMinute.val();
		var endTimeslot = endTimeslotHour.val()+':'+endTimeslotMinute.val();
		if(checkTime(startTimeslot,endTimeslot)) {
			alert('当日执行结束时间不能小于当日执行开始时间');return;
		}
		var ajaxResult = DoAjaxPost('/activity/add-manage',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			window.location.href = window.location.href;
			$('#modal_main').modal('hide');
		}
	});
});
//编辑活动
$(".goodsActivityEdit").click(function() {
	var trObj = $(this).parent().parent();
	var policyGoodsId = trObj.attr('trid');
	var description = trObj.attr('pgDescription');
	var goodsPoliciesName = trObj.find('.pgName').attr('gpName');
	var cutPrice = trObj.find('.pgName').attr('pgCutPrice');
	var ruleName = trObj.find('.pgPolicyTitle').text();
	var productTitle = trObj.find('.productTitle').attr('pgProductTitle');
	var shopPrice = trObj.find('.productTitle').attr('pgShopPrice');
	var imageUrl = trObj.find('.productTitle').attr('pgImageUrl');
	var startTime = trObj.find('.pgTime').attr('startTime');
	var endTime = trObj.find('.pgTime').attr('endTime');
	var startTimeslot = trObj.find('.pgTimeslog').attr('startTimeslot');
	var endTimeslot = trObj.find('.pgTimeslog').attr('endTimeslot');
	var onactive = trObj.find('.pgOnactive').attr('onactive');
	var startTimeData = new Date(startTime.replace("-", "/").replace("-", "/"));
	var endTimeData = new Date(endTime.replace("-", "/").replace("-", "/"));
	var month=new Array(01,02,03,04,05,06,07,08,09,10,11,12);
	var startTimeslotData = startTimeslot.split(":");
	var endTimeslotData = endTimeslot.split(":");
	var bodyHtml = '';
	bodyHtml += '<form class="form-horizontal" fole="form" id="activityManageFormAdd">';
	bodyHtml += '	<input type="hidden" name="_token" value="'+$('#csrf_token').val()+'" />';
	bodyHtml += '	<input type="hidden" name="policyGoodsId" id="policyGoodsId" value="'+policyGoodsId+'" />';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">选择活动规则</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control" disabled value="'+ruleName+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">活动名称</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control" name="goodsPoliciesName" id="goodsPoliciesName" placeholder="请输入活动名称" max="50" value="'+goodsPoliciesName+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">活动描述</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<textarea class="form-control" rows="3" max="100" disabled >'+description+'</textarea>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">选择商品</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control" disabled value="'+productTitle+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label"><img id="activityManageGoodsImage" src="'+imageUrl+'" height="50px" width="50px" /></label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<label class="col-sm-5 pull-left activityManageGoodsName">'+productTitle+'</label>';
	bodyHtml += '			<label class="col-sm-4 activityManageGoodsPrice">原售价'+shopPrice+'￥</label>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group" id="activityManageGoodCutPrice">';
	bodyHtml += '		<label class="col-sm-3 control-label">优惠价格</label>';
	bodyHtml += '		<div class="col-sm-9">';
	bodyHtml += '			<input type="text" class="form-control" disabled value="'+cutPrice+'">';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group" id="activityManageGoodCutPrice">';
	bodyHtml += '		<label class="col-sm-3 control-label">有效日期 ： Begin</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<input type="text" class="form-control" name="datepicker" id="datepicker" placeholder="" value="'+startTimeData.getFullYear()+'-'+month[startTimeData.getMonth()]+'-'+(startTimeData.getDate()< 10 ? "0" + startTimeData.getDate() : startTimeData.getDate())+'">';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" name="startHouse" id="startHouse">';
	bodyHtml += 				selectHourTime(startTimeData.getHours());;
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" id="startMinute" name="startMinute">';
	bodyHtml += 				selectMinuteTime(startTimeData.getMinutes());
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group" id="activityManageGoodCutPrice">';
	bodyHtml += '		<label class="col-sm-3 control-label">有效日期 ： End</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<input type="text" class="form-control" name="datepicker_second" id="datepicker_second" placeholder="" value="'+endTimeData.getFullYear()+'-'+month[endTimeData.getMonth()]+'-'+(endTimeData.getDate()< 10 ? "0" + endTimeData.getDate() : endTimeData.getDate())+'">';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" name="endHouse" id="endHouse">';
	bodyHtml += 				selectHourTime(endTimeData.getHours());;
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-2">';
	bodyHtml += '			<select class="form-control" id="endMinute" name="endMinute">';
	bodyHtml +=						selectMinuteTime(endTimeData.getMinutes());
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">当日执行开始时间</label>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" name="startTimeslotHour" id="startTimeslotHour">';
	bodyHtml += 				selectHourTime(startTimeslotData[0]);
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" id="startTimeslotMinute" name="startTimeslotMinute">';
	bodyHtml += 				selectMinuteTime(startTimeslotData[1]);
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">当日执行结束时间</label>';
	bodyHtml += '		<label class="col-sm-1 control-label">时</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" name="endTimeslotHour" id="endTimeslotHour">';
	bodyHtml += 				selectHourTime(endTimeslotData[0]);
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '		<label class="col-sm-1 control-label">分</label>';
	bodyHtml += '		<div class="col-sm-3">';
	bodyHtml += '			<select class="form-control" id="endTimeslotMinute" name="endTimeslotMinute">';
	bodyHtml += 				selectMinuteTime(endTimeslotData[1]);
	bodyHtml += '			</select>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '	<div class="form-group">';
	bodyHtml += '		<label class="col-sm-3 control-label">是否上架</label>';
	bodyHtml += '		<div class="col-sm-9 siteStatus">';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="onactive" value="1" checked>是';
	bodyHtml += '			</label>';
	bodyHtml += '			<label class="radio-inline">';
	bodyHtml += '				<input type="radio" name="onactive" value="0">否';
	bodyHtml += '			</label>';
	bodyHtml += '		</div>';
	bodyHtml += '	</div>';
	bodyHtml += '</form>';
	InitShowModal('编辑新活动',bodyHtml);
	DatePickerInit();
	$('.siteStatus').find('input:radio[value='+onactive+']').prop('checked',true);
	$(document).off('click','#modal_main .modal-footer #submit');
	$(document).on('click','#modal_main .modal-footer #submit',function(){
		$('#activityManageFormAdd').find('.form-group').removeClass('has-error');
		var policyGoodsIdInput = $("#policyGoodsId");
		var nameInput = $('#goodsPoliciesName');
		var startTimeInput = $("#datepicker");
		var startHouseInput = $("#startHouse");
		var startMinuteInput = $("#startMinute");
		var endTimeInput = $("#datepicker_second");
		var endHouseInput = $("#endHouse");
		var endMinuteInput = $("#endMinute");
		var startTimeslotHour = $('#startTimeslotHour');
		var startTimeslotMinute = $('#startTimeslotMinute');
		var endTimeslotHour = $('#endTimeslotHour');
		var endTimeslotMinute = $('#endTimeslotMinute');
		var onactiveInput = $("input[name='onactive']:checked");
		if(DoIllegalValidate2(new Array(policyGoodsIdInput,nameInput),'required')) return;
		if(DoIllegalValidate2(new Array(startTimeInput,startHouseInput,startMinuteInput,endTimeInput,endHouseInput,endMinuteInput),'required')) return;
		if(DoIllegalValidate2(new Array(startTimeslotHour,startTimeslotMinute,endTimeslotHour,endTimeslotMinute,onactiveInput),'required')) return;
		var startTimeData = startTimeInput.val()+' '+startHouseInput.val()+':'+startMinuteInput.val();
		var endTimeData = endTimeInput.val()+' '+endHouseInput.val()+':'+endMinuteInput.val();
		if(checkEndTime(startTimeData,endTimeData)) {
			alert('有效结束日期不能小于有效开始日期');return;
		}
		var startTimeslot = startTimeslotHour.val()+':'+startTimeslotMinute.val();
		var endTimeslot = endTimeslotHour.val()+':'+endTimeslotMinute.val();
		if(checkTime(startTimeslot,endTimeslot)) {
			alert('当日执行结束时间不能小于当日执行开始时间');return;
		}
		var ajaxResult = DoAjaxPost('/activity/edit-manage',$('#modal_main form').serialize());
		if(ajaxResult['title'] == 'success'){
			window.location.href = window.location.href;
			$('#modal_main').modal('hide');
		}
	});
});



});

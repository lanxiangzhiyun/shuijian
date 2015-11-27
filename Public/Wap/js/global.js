var Global = {
	HeaderNav : function(event){
		event.stopPropagation();
		var btn = $('.NavBar'),
			list = $('.NavList'),
			show = function(){
				btn.addClass('navbar_show');
				list.show();
			},
			hide = function(){
				btn.removeClass('navbar_show');
				list.hide();
			};	
		if(btn.hasClass('navbar_show')){
			hide();
		}else{
			show();
		}		
	}
};
$(function(){
	//头部用户下拉
	$('.NavBar').on('click',Global.HeaderNav);
	$(document).on('click',function(event){
		event.stopPropagation();
		$('.NavBar').removeClass('navbar_show');
		$('.NavList').hide();
	})
	//数字
	Base.Form.isNumber($('.isNumber'));
	//添加购物车
	$('.ShoppingCarAdd').click(Common.ShoppingAdd);

	//验证码
	$('.VerifyChange').click(function(){

		Common.Verify($(this));

	});
});
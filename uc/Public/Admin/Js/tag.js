$(function(){
	var tagArr=[];
	var tagId=$('#tag_id').val();
	if($('#tagids').val()!=''){
		tagArr=$('#tagids').val().split(',');
	}
	tag();
	function tag(){
		//搜索标签
		var tagObj=$('.add_tag_s');
		$('.addNewTag').bind('click keyup',function(){
			var _t = $(this);
			var key = $.trim(_t.val());
			if(key=='') return;
			searchAjax(key, _t);
			return false;
		});
		$(document).on('click',function(){
			tagObj.hide();
		});
		//下拉框添加标签
		$('.add_tag_s_list').live('click',function(){
			var _t = $(this);
			var tag_list = $('.add_tag_t');
			var length = tagArr.length, flag=true;
			if (_t.hasClass('batch') == false) {
				// 合并标签 隐藏输入框
				if($('.addNewTag').attr('act')=='merge'){
					$('.addNewTag').hide();
				}
				if(length>0){
					if($('.addNewTag').attr('act')=='merge'){
						if(length>=1){
							alert('已添加过合并标签！');
			
							return false;
						}
						
					}else{
						for(var i=0;i<length;i++){
							if(tagArr[i]==_t.attr('pid')){
								flag=false;
								alert('已经添加过了，请重新选择！');
								break;
							}
						}
					}
				}
				if(flag){
					_t.parent().prev().val(_t.text());
					tag_list.append('<div class="add_tag_list" pid="'+_t.attr('pid')+'">'+_t.text()+'<em></em></div>');
					tagArr.push(_t.attr('pid'));
					$('#tagids').val(tagArr.join(','));
					tagObj.html('').hide();
				}
			}
			
		});
		//移除标签
		$('.add_tag_list').live('click',function(){
			var _t = $(this), url = _t.parent().attr('url');
			// 合并标签 隐藏输入框
			if (_t.hasClass('batch') == false) {
				if($('.addNewTag').attr('act')=='merge'){
					$('.addNewTag').show();
				}
			}
			
			_t.remove();
			for(var i=0;i<tagArr.length;i++){
				if(tagArr[i]==_t.attr('pid')){
					tagArr.splice(i,1);
					break;
				}
			}
			$('#tagids').val(tagArr.join(','));
			
		});
		var ajaxData = {};
		function searchAjax(key, obj){  //搜索标签
			if(ajaxData[key]==undefined){
				$.get('/iadmin.php/Tag/ajaxSearchTagByName',{tagId:tagId,keyword:key,act:obj.attr('act')},function(d){
					loadData(d);
					ajaxData[key]=d;
				},'json');
			}else{
				loadData(ajaxData[key]);
			}	
		}
		function loadData(d){
			if(d.data){
				var pHtml = '';
				$.each(d.data,function(i,j){
					pHtml += '<div class="add_tag_s_list" pid="'+j.id+'">'+j.name+'</div>';
				});
				tagObj.html(pHtml).show();
			}else{
				tagObj.html('').hide();
			}
		}
	}
})

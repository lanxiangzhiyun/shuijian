var upload = function(options){
	var btn = $("."+options.btn+" span"),action=options.action,/*files,*/showimg,pic_path=options.pic_path,showimg=options.showimg,img_domain=options.img_domain;
	/*if(options.name){
		var files = $('#'+options.name);
	}*/

	$('.'+options.btn+' input[type="file"]').live('change',function(){
		
		var _self = $(this);
		var _imgsrc = '';

		_self.wrap('<form class="myupload" action="'+action+'" method="post" enctype="multipart/form-data"></form>');
		
		$('.myupload').append('<input type="hidden" name="type" class="type" value="'+options.type+'">');
		$('.myupload').ajaxSubmit({
			
			dataType:  'json',
			success: function(data) {
				_self.unwrap();

				if(data.status == 'error'){
					alert(data.tip);
					return false;
				}
				
				$('.type').remove();
				/*if(files){
					files.html("<b>"+data.name+"("+data.size+"k)</b> <span class='delimg' rel='"+data.imgpath+"'>删除</span>");
				}*/
				if(showimg==true && data.imgpath!='undefined'){
					var img = img_domain+'/'+data.imgpath;
					_self.parent().next('.show_img').attr('src',img);
					_self.parent().next('.show_img').show();
				}
				_self.parent().children('.pic_path').val(data.imgpath);
                _imgsrc = data.domain + data.imgpath;
                _self.parent().siblings('.show_img').attr('src',_imgsrc);
				btn.html('添加附件');
			},
			error:function(xhr){
				btn.html('上传失败');
				/*if(files){
					files.html(xhr.responseText);
				}*/
				_self.unwrap();
				$('.type').remove();
				//files.html(xhr.responseText);
			}
		});
	});
}



BQ.add('BQMinUP', function(W,CLASS_NAME){
	
	var defaultConfig = {
		but: '#bqminup', //触发按钮
		action:'/svnuc/Public/DemoForPHP/upload.php'
	};

    function ClassObj(eventType, config) {
        var self = this;
        if (!(self instanceof ClassObj)) {
            return new ClassObj(eventType, W.merge(defaultConfig, config));
        }
        var config = self.config = config;
		eventType = eventType ||'mup';

		var but = $(config.but);
		function byteLen(str){
			str = str || '';
			str.trim();
			var num = 0, i = 0, len = str.length, unicode;
			for(; i < len; i++){
				unicode = str.charCodeAt(i);
				num += unicode > 127 ? 2 : 1;
			}
			return num;
		}
		function _byrception (str,len) {
			var strl;
			len = len || 9;
			if(byteLen(str)>len){
				strl = str.substring(0,len)+'...';
			}else{
				strl = str;
			}
			return strl;
		}

		function rDom (qqb,bqb) {
			var mupTion = [];
			mupTion.push('<div class="img_panel muption none"><i class="arr"></i><a title="关闭" href="javascript:void(0);" class="close">关闭</a><p class="info">传张图让内容更精彩，支持png、jpg、gif格式</p>');
			mupTion.push('<div class="hide_file"><a href="javascript:void(0);" id="file_mask"></a></div>');
			mupTion.push('<div class="loading"> [<a href="javascript:void(0);">取消</a>]</div><div class="loading"></div></div>');
			mupTion.push('<div class="thumb_img_panel none" id="thumb_img_panel"><i class="arr"></i><p class="content"></p></div>');
			return mupTion.join('');
		}
		if(!$('.muption').is('div')){
			$('body').append(rDom());
		}
		var flieMask = $('#file_mask'),bqminup=$('#bqminup1'),bip;
		BQ.BQAjaxUP('AjaxUP',{but:flieMask,action:config.action,data:{'type':'weibo'},onSubmit:function(file,ext){
				if(ext && /^(jpg|jpeg|png|gif)$/.test(ext)){
						bqminup.addClass('img_mit').html('上传中……<a href="javascript:void(0);" class="bquping">[取消]</a>');
						bip = 1;
						bqminup.click(function(){
							var _t = $(this);
							_t.show().removeClass('img_mit').html('图片').show();
							$('#imgpad').val('');
							bip = 0;
						});
				}else{
					alert('不支持非图片格式！');
					return false;
				}
				},onComplete:function(file,response){
					response = eval("("+response+")");
					if(bip){
						if(response.status == 'ok'){
							if($('#bqcom1').val() == '' || $('#bqcom1').val() == '留个足迹，问候一下吧'){
								$('#bqcom1').val('#图片#').focus();
							}
							var thumbImg = $('#thumb_img_panel');
							bqminup.hide();
							$('#bqminimg').show().find('span').html(_byrception(file,6)).css({'display':'inline-block','height':'20px','overflow':'hidden','line-height': '25px'});
							$('#bqminimg').hover(function(e){
								e.preventDefault();
								var _t = $(this),_oft = _t.offset();
								thumbImg.css({'top':_oft.top+_t.height()+8,'left':_oft.left, 'zIndex': '99999'}).show();
								thumbImg.find('.content').html('<img src="'+response.domain+response.imgpath+'" width="120" height="120"/>');
							},function(){
								thumbImg.hide();
							}).click(function(){
								var _t = $(this);
								_t.hide().prev().removeClass('img_mit').html('图片').show();
								$('#imgpad').val('');
							});
							$('#imgpad').val('Data'+response.imgpath.split('Data')[1]);
						}else if(response.status == 'noselect'){
							alert('服务器错误、上传图片为空值。');
						}else if(response.status == 'login'){
							alert('请登录后操作！');
						}else{
							//alert('服务器错误。'+response.tip);
							alert('上传失败，请保持图片不超过2M');
							var _t = $(this);
							$('#bqminimg').hide().prev().removeClass('img_mit').html('图片').show();
						}
					}
				}});

		var mupDom = $('.muption');
		mupDom.find('.close').click(function(e){
			e.preventDefault();
			mupDom.hide();
		});
		function EveOn(bqebut) {
			

			bqebut.click(function(e){
				e.preventDefault();
				var _t = $(this),_oft = _t.offset();
				mupDom.css({'top':_oft.top+_t.height()+8,'left':_oft.left, 'zIndex': '99999'}).show();
				
				


				//点击关闭层
				$(document).unbind('click').click(function(e){
					var _e = $(e.target);
					if(!_e.parents('.muption')[0] && (_e[0] != but[0])){
						mupDom.hide();
					}
				});

			});
			
		
			
		}

		if(eventType == 'mup'){
			EveOn(but);
		}

	};

	 W.augment(ClassObj, {
			getVal:function(str){return this.GetVal(str);},
			getEmofoing:function(){return this.getEmo();}
	 })
	return ClassObj;

})

String.prototype.trim   =  function()
{
	  return this.replace(/(^\s*)|(\s*$)/g, "");
}

BQ.add('Public', function(W,CLASS_NAME){
	
	//var msgsuc = function(c,t,w,h,s,ms){_Msg(c,t,'ok',s,w,h,ms)}
	var msgsucs = function(c,t,w,h,s,ms){_Msg(c,t,'ok',s,w,h,ms)};
	(function(){
			
		$(document).ready(function(){

		$.post(idir+'/Public/ajaxGetUserCnts/callback/?',function(d){
			var topDoc = [];
			if(d.status == "ok"){
				if(d.fcnt != "0"){
					topDoc.push('<p>'+d.fcnt+'位新粉丝，<a href="'+idir+'/fans/u/'+uid+'">查看粉丝</a></p>');
				}
				if(d.ccnt != "0"){
					topDoc.push('<p>'+d.ccnt+'条新评论，<a href="'+idir+'/comment">查看评论</a></p>');
				}
				if(d.mcnt != "0"){
					topDoc.push('<p>'+d.mcnt+'条新消息，<a href="'+idir+'/UcMsg/myInbox">查看消息</a></p>');
				}
				if(d.ncnt != "0"){
					topDoc.push('<p>'+d.ncnt+'条新通知，<a href="'+idir+'/UcMsg/myNotice">查看通知</a></p>');
				}
				$('#topMenu').html(topDoc.join(''));
				$('#userInfo .num').show();
			}else if(d.status == 'no'){
				topDoc.push('<span style="color:#A8A8A8">没有新消息</span>');
				$('#userInfo .num').hide();
				$('#topMenu').html(topDoc.join(''));
			}else{
				
			}
		},'jsonp');


			BQ.widget.SeamlesScroll('#friends1', {
						speed: 500,
						scroll: 1,
						visible: 1,
						circular: false,
						disableBtnCls: ['prev_btn_disable', 'next_btn_disable'],
						panels: '.carousel_cont ul',
						btnPrev: 'a.prev_btn',
						btnNext: 'a.next_btn'
			});

			BQ.widget.SeamlesScroll('#friends2', {
						speed: 500,
						scroll: 1,
						visible: 1,
						circular: false,
						disableBtnCls: ['prev_btn_disable', 'next_btn_disable'],
						panels: '.carousel_cont ul',
						btnPrev: 'a.prev_btn',
						btnNext: 'a.next_btn'
			});

			var psear = [],psearname = [],chked;
			$('#t option').each(function(i,d){
				var _t = $(this);
				//psear.push({'val':_t.attr('value'),'text':_t.text(),'checked':_t.attr('checked')});
				chked = _t.attr('checked') == 'checked' ? i : 0;
				psearname.push(_t.text());
				psear.push('<li val="'+_t.attr('value')+'">'+_t.text()+'</li>');
			});
			var psearsel = '<em class="Select-title">'+psearname[chked]+'</em><ul style="z-index: 99999; display: none;" class="down_menu locat_select Select-List">'+psear.join('')+'</ul>'
			
			if(!$('.Select-title').is('em')){
				$('#t').after(psearsel);
			}
			$('.Select-title').click(function(){
				$('.down_menu').toggle();
				setSelectCls($('#t').val());
			});
			$(document).click(function(e){
				if ($(e.target).parents('.down_menu').length == 0 && !$(e.target).hasClass('down_menu') && !$(e.target).hasClass('Select-title')) {
					$('.down_menu').hide();
				}
			});
			$('.down_menu li').hover(function(){
				$(this).addClass('over');
			},function(){
				$(this).removeClass('over');
			}).removeClass('over').click(function(){
				setSelectVal($(this).index());
			});

			function setSelectVal(i){
				var dowli = $('.down_menu li').eq(i),dval = dowli.attr('val');
				$('.Select-title').html(dowli.html());
				(function(){setTimeout(function(){ $('#t').val(dval);},1)})();
				$('.down_menu').hide();
			}
			function setSelectCls(i){
				var dowli = $('.down_menu li').removeClass('over').eq(i-1).addClass('over');
			}
		//}
		
		
		if($('#bqpublicmsg').is('a')){
			//发送
			var _msgs = BQ.widget.LayerBox('struc',{struc:'#receivedmsg',drag:true,dragTags:'.hd',zIndex:'99',close:'.closes'});
			var bqmsgtextarea = BQ.BQSubInfo('',{con:$('#msgtextarea'),text:$('#receivedmsgnum'),max:150});//输入长度
			$('#sbBtn').unbind().click(function(e){
				e.preventDefault();
				if(!bqmsgtextarea.getSubV()){
					msgsucs('发内容已超出最大限制字数。','提示',250,135);
					return;
				}
				if($('#msgtextarea').val() == ''){
					$('#msgerror').show();
					return;
				}else{
				
					$.post('/UcMsg/publishMsg',{'receverid':$('#bqpublicmsg').attr('uid'),'content':$('#msgtextarea').val()},function(d){
						if(d.status == 'ok'){
							msgsucs('消息已送达。','提示',250,135);
						}else if (d.status == 'forbidden') {
							BQ.Public.msgalno('您可能涉及违规内容发布，暂时无法进行该操作，如有问题，请联系论坛管理员。','提示',250,135);
                        }else if(d.status == 'black'){
							msgsucs('请先解除对方黑名单。','提示',250,135);
						}else if(d.status == 'TBlack'){
							msgsucs('根据对方设置，你不能进行该操作。','提示',250,135);
						}else if(d.status == 'none'){
							msgsucs('该用户不存在，你不能进行该操作','提示',250,135);
						}else if(d.status == 'empty'){
							msgsucs('发件人和内容不能为空','提示',250,135);
						}else if(d.status == 'login'){
							msgsucs('未登录不能发消息','提示',250,135);
						}
					},'json');
				}
				_msgs.close();
			});
		
			$('#bqpublicmsg').click(function(e){
				e.preventDefault();
				$('#msgtextarea').val('').focus();
				_msgs.alert();
			});
			
			$('#cancel_close').click(function(e){
                e.preventDefault();
				_msgs.close();
			});
		}

				_focusab($('#keyword'),'关键字');
				$('#button').click(function(){
					var kword = $('#keyword');
					if(kword.val().trim()=='' || kword.val().trim() == '关键字') {
						kword.focus();
						return;
					}
					if($('#t').val() == "1"){
						$('#search').attr('target','_self');
					}else{
						$('#search').attr('target','_blank');
					}
					$('#search').submit();
				});

				$('#userInfo .moves').hover(function(){
					if($(this).find('.top_menu').html() != ''){
						$(this).find('.top_menu').show();
					}
				},function(){
					$(this).find('.top_menu').hide();
				});
			
			var toppp = $('#gotop').show(),gotop = $('.gotop').hide();
			toppp.css('left',document.documentElement.clientWidth-(toppp.width()-12));
			if ($.browser.msie && $.browser.version =='6.0'){
				toppp.css('position','absolute');
				$(window).bind('scroll resize',function(){
					var top = $(document).scrollTop()+document.documentElement.clientHeight-203;
					toppp.css({'top':top,height:50});
					if($(document).scrollTop()>=document.documentElement.clientHeight-(300+58)){
						gotop.fadeIn();
					}else{
						gotop.fadeOut();
					}
				});
			}else{
				toppp.css({'position':'fixed','top':document.documentElement.clientHeight-(158+45)});
				$(window).bind('scroll resize',function(){
					if($(document).scrollTop()>=document.documentElement.clientHeight-(300)){
						gotop.fadeIn();
					}else{
						gotop.fadeOut();
					}
				});
			}
			$('.gotop').click(function(e){
				e.preventDefault();
				$('html,body').animate({scrollTop: 0}, 200, "swing");
			});


			//图片自适应
			
			if ($.browser.msie && ($.browser.version =='6.0' || $.browser.version =='7.0')){
				$('.user_head img').css({'width':'120px','height':'120px'}).show();
			}else{
				if ( $.browser.msie){
					setTimeout(function(){_imgAgp($('.user_head img'),120,120,'#FFF');},200);
				}else{
					_imgAgp($('.user_head img'),120,120,'#FFF');
				}
			}
			if ( $.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0' || $.browser.version == '8.0')){
				setTimeout(function(){
					$('#friends1 img').each(function(){_imgAgp($(this),50,50,'#FFF');});
				},400);
			}else{
				$('#friends1 img').each(function(){_imgAgp($(this),50,50,'#FFF');});
			}

			if ( $.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0' || $.browser.version == '8.0')){
				setTimeout(function(){
					$('#friends2 img').each(function(){_imgAgp($(this),50,50,'#FFF');});
				},600);
			}else{
				$('#friends2 img').each(function(){_imgAgp($(this),50,50,'#FFF');});
			}

		});

		if($('.add_btn,.cancel_add_btn').is('a')){
			$('.add_btn,.cancel_add_btn').click(function(e){
					e.preventDefault();
					var _t = $(this);
					$.getJSON($(this).attr('thref'),function(d){
						if (d.status == 'login') {
							//BQ.Public.msgsuc('请登录后进行操作。','提示',250,135);
							BQLogin(curl);

						}else if(d.status=='black'){
                            BQ.Public.msgask('请先解除对方黑名单。','提示',250,135);
                        }else if(d.status=='tBlack'){
                            BQ.Public.msgask('根据对方设置,您不能进行该操作。','提示',250,135);
						}else {
							if(!_t.hasClass('cancel_add_btn')){
								_t.attr('class','cancel_add_btn').attr('thref',d.url);
								if($('#bqpublicmsg').is('a')){
									$('#bqpublicmsg').show();
								}else{
									(function(){setTimeout(function(){location.reload(true); },500)})();
								}
							}else{
								_t.attr('class','add_btn').attr('thref',d.url);
								$('#bqpublicmsg').hide();
							}
						}
					})
			});
		}

	})();
	
	function _Msg (c,t,mg,s,w,h,det,ca,ms) {
				w= w || 250;
				h= h || 125;
				ms = ms || 3000;
				mg = mg || 'ok';
				s = s || 'alert';
				t = t || '提示';
				det = det || function(){};
				ca = ca || function(){};

				mgcls = (mg == 'ok' ? "icon_succ" : (mg == 'cf' ? "icon_ask":"icon_warning"));
				var popt = '<div class="popup_layer" style="width:'+w+'px;height:'+h+'px;" id="popup_popt"><div class="bg" style="width:'+w+'px;height:'+h+'px;"><div class="content" style="width:'+w+'px;height:'+h+'px;"> <a title="关闭" href="javascript:void(0);" class="close">关闭</a><div class="hd">{title}</div><div style="height:80px;" class="bd"> <div class="warning_msg_tips_succ"><i class="'+ mgcls +'"></i>{content}</div></div></div></div></div>';
				var pconfirm = '<div class="popup_layer" style="width:'+w+'px;height:'+h+'px;" id="popup_pconfirm"><div class="bg" style="width:'+w+'px;height:'+h+'px;"><div class="content" style="width:'+w+'px;height:'+h+'px;"> <a title="关闭" href="javascript:void(0);" class="close">关闭</a><div class="hd">{title}</div><div style="height:100px;" class="bd"> <div class="warning_msg_tips"><i class="'+ mgcls +'"></i>{content}</div><p class="btn"><a href="javascript:void(0);" class="submit_btn popsubmit_btn">确定</a><a href="javascript:void(0);" class="cancel_btn popcancel_btn">取消</a></p></div></div></div></div>';
				var palet = '<div style="height: 98px; width: 239px;" class="popup_layer"><div class="bg" style="height: 98px; width: 239px;"><div class="mini_content tips_height" style="width: 162px; height: 20px;padding: 38px;"><p class="info"><i class="'+mgcls+'"></i>{content}</p></div></div></div>';

				var tmp = (s == 'alert' ? popt : pconfirm);
				if(s == 'palet') tmp = palet;
				var _msg = BQ.widget.LayerBox('Temps',
					{
					drag:true,
					zIndex:999999,//设置起始层级
					//dragTags:'.bd',
					Temps:tmp,
					width:w,
					height:h,
					alStyle:{'border':'none','backgroundColor':''},
					callback:function(){
						
						if(s == 'cf'){
							//popsubmit_btn popcancel_btn
							$('.popsubmit_btn').click(function(){
								det();
								_msg.close();
							})
							
							$('.popcancel_btn').click(function(){
								ca();
								_msg.close();
							})
						}

					}});
					if(s == 'cf'){
						_msg.alert({"title":t,'content':c}); 
					}else{
						_msg.alert({"title":t,'content':c},ms); 
					}
			}

	function _setFocus(obj,cl)
	{
		var s = obj.value.length,e = obj.value.length;
		if(cl){
			s = 0;
			e = 0;
			
		}
		//console.log(s.e);
		 if(obj.setSelectionRange)
		 {
			obj.setSelectionRange(s,e); //将光标定位在textarea的结尾
			//obj.setSelectionRange(obj.value.length,obj.value.length); //将光标定位在textarea的结尾
			obj.focus();
		 }
		 else if (obj.createTextRange)
		 {
			 var tempText=obj.createTextRange();
			 tempText.collapse(cl);
			 tempText.select();
		}
	}
	
	function _bqFlip (i,c,d,maxw) {
	
			var img = null, canvas = null;
			maxw = maxw || 440;
			// console.log(i,c);
			   img = i[0];
			   canvas = c[0];
			
			//console.log($('image'));
			
			//setTimeout(function(){rotateImage(90);},500);
			 if(!canvas || !canvas.getContext){
				   canvas.parentNode.removeChild(canvas);
			} else {
				//img.style.position = 'absolute';
				//img.style.visibility = 'hidden';
			}
			 /*
		   $('resetImage').onclick = function(){ rotateImage(0); };
		   $('rotate90').onclick = function(){ rotateImage(90); };
		   $('rotate180').onclick = function(){ rotateImage(180); };
		   $('rotate270').onclick = function(){ rotateImage(270); };
			*/
			function rotateImage(degree)
			{
				

				if(document.getElementById('canvas')){

				img.style.position = 'absolute';
				img.style.visibility = 'hidden';

				   var cdoc = canvas.getContext('2d');
				   	var imge = new Image();
					imge.src = i.attr('src');
					img = imge;
					imge.onload = function(){

					   var cw =this.width, ch = this.height, cx = 0, cy = 0;
					   //   Calculate new canvas size and x/y coorditates for image
					   switch(degree){
							case 90:
								cw = img.height;
								ch = img.width;
								cy = img.height * (-1);
								break;
							case 180:
								cx = img.width * (-1);
								cy = img.height * (-1);
								break;
							case 270:
								cw = img.height;
								ch = img.width;
								cx = img.width * (-1);
								break;
					   }

						var x = 1;
						//  Rotate image
						if(cw > maxw){
							var w = cw;
							x = 1-(w-maxw)/w;//缩放比例计算
							cw = maxw;
							ch = ch * x;
						}
						//console.log(cw,ch,x);
						
						d.css({'width':cw,'height':ch});
						canvas.setAttribute('width', cw);
						canvas.setAttribute('height', ch);
						cdoc.rotate(degree * Math.PI / 180);
						//cdoc.drawImage(img, cx, cy);
						cdoc.scale(x, x);//比例缩放
						cdoc.drawImage(img, cx, cy);
					}
					
				} else {
					//  Use DXImageTransform.Microsoft.BasicImage filter for MSIE
					//alert(degree);
					switch(degree){
						case 0: img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation=0)'; break;
						case 90: img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation=1)'; break;
						case 180: img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation=2)'; break;
						case 270: img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation=3)'; break;
					}
					/*switch(degree){
						case 0: $(img).css({'filter':'progid:DXImageTransform.Microsoft.BasicImage(rotation=0)','-webkit-transform':'rotate('+degree+'deg)','-moz-transform':'rotate('+degree+'deg)','-o-transform':'rotate('+degree+'deg)','transform':'rotate('+degree+'deg)'}); break;
						case 90: $(img).css({'filter':'progid:DXImageTransform.Microsoft.BasicImage(rotation=1)','-webkit-transform':'rotate('+degree+'deg)','-moz-transform':'rotate('+degree+'deg)','-o-transform':'rotate('+degree+'deg)','transform':'rotate('+degree+'deg)'}); break;
						case 180: $(img).css({'filter':'progid:DXImageTransform.Microsoft.BasicImage(rotation=2)','-webkit-transform':'rotate('+degree+'deg)','-moz-transform':'rotate('+degree+'deg)','-o-transform':'rotate('+degree+'deg)','transform':'rotate('+degree+'deg)'}); break;
						case 270: $(img).attr('style','filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);-webkit-transform:rotate('+degree+'deg);-moz-transform:rotate('+degree+'deg);-o-transform:rotate('+degree+'deg);transform:rotate('+degree+'deg)'); break;
					}*/
					//$(img).attr('style','filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);-webkit-transform:rotate('+degree+'deg);-moz-transform:rotate('+degree+'deg);-o-transform:rotate('+degree+'deg);transform:rotate('+degree+'deg)'})
					/*-webkit-transform: rotate(270deg);
					-moz-transform: rotate(270deg);
					-o-transform: rotate(270deg);
					transform: rotate(270deg);*/
					//alert(img.style.filter);
				}
			}

			return rotateImage;

		}

		function autoSizePic(ele,size,cbk){
				var src = ele.src,img = new Image();
				img.onload = function(){
					/*this.width
					this.height*/
				}
				img.onerror = function(){
					//throw new Error('图片'+src+'加载失败');
				}
				img.src = src;
		}
		
	
	function  _focusab(o,v,tpye) {
			o.unbind().focusin(function(){
				if(this.value == v){
					this.value = "";
					/*
					if(!$.browser.msie || ($.browser.msie && $.browser.version != 6.0))
					{
					this.type = tpye;
					}
					o.addClass('fc44');*/
				}
			});

			o.focusout(function(){
				if(this.value == v || this.value == ''){
					this.value = v;
					/*if(!$.browser.msie || ($.browser.msie && $.browser.version != 6.0))
					{
						this.type = "text";
					}
					o.removeClass('fc44');*/
				}
			});
	}

	 //测试某个字符是属于哪一类. 
        function CharMode(iN){ 
            if (iN>=48 && iN <=57) //数字 
            return 1; 
            if (iN>=65 && iN <=90) //大写字母 
            return 2; 
            if (iN>=97 && iN <=122) //小写 
            return 4; 
            else 
            return 8; //特殊字符 
        }
        //bitTotal函数 
        //计算出当前密码当中一共有多少种模式 
        function bitTotal(num){ 
            modes=0; 
            for (i=0;i<4;i++){ 
            if (num & 1) modes++; 
            num>>>=1; 
            } 
            return modes; 
        }

        //checkStrong函数 
        //返回密码的强度级别 
        function checkStrong(sPW){ 
            if (sPW.length<=5) 
				return 0; //密码太短
				Modes=0;
				for (i=0;i<sPW.length;i++){
				//测试每一个字符的类别并统计一共有多少种模式.
				Modes|=CharMode(sPW.charCodeAt(i));
			}
			return bitTotal(Modes); 
        }
		
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

		function Cookies() {  
			this.get = function(key) {  
				var cookie = document.cookie;
				var cookieArray = cookie.split(';');  
				var val = "";  
				for (var i = 0; i < cookieArray.length; i++) {  
					if (cookieArray[i].Trim().substr(0, key.length) == key) {  
						val = cookieArray[i].Trim().substr(key.length + 1);  
						break;  
					}  
				}  
				return unescape(val);  
			};  
			this.getChild = function(key, childKey) {  
				var child = this.get(key);  
				var childs = child.split('&');  
				var val = "";  
		  
				for (var i = 0; i < childs.length; i++) {  
					if (childs[i].Trim().substr(0, childKey.length) == childKey) {  
						val = childs[i].Trim().substr(childKey.length + 1);  
						break;  
					}  
				}  
				return val;  
			};  
			this.set = function(key, value) {  
				var cookie = "";  
				if (!!key && !!value)  
					cookie += key + "=" + escape(value) + ";";  
				if (!!arguments[2])  
					cookie += "expires=" + arguments[2].toGMTString() + ";";  
				if (!!arguments[3])  
					cookie += "domain=" + arguments[3] + ";";  
				if (!!arguments[4])  
					cookie += "path=" + arguments[4] + ";";  
				document.cookie = cookie;  
			};  
			this.remove = function(key) {  
				var date = new Date();  
				date.setFullYear(date.getFullYear() - 1);  
				var cookie = " " + key + "=;expires=" + date + ";"  
				document.cookie = cookie;  
			};
		}

		function _imgAgp (obj,w,h,b) {
			var _pobj=obj.parent(),_pw = w || _pobj.innerWidth(),_ph = h || _pobj.innerHeight();
			_img = new Image();
			_img.src = obj.attr('src');
			//console.log(_pobj,_pw,_ph);
			b != undefined && _pobj.css({'width':w,'height':h,'background-color':b,'display': 'block','overflow':'hidden'});
			if ( $.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0' || $.browser.version == '8.0')){
				obj.css({'display':'block','overflow':'inline'});
				//setTimeout(function(){
					//alert(_img.width);
					setobj(_img);
				//},100);
			}else{
				_img.onload = function(){
					setobj(this);
				}
			}
			
			function setobj (t) {
				var _t = t,_w = t.width,_h = t.height,_rmw,_rsw,_rmh,_rsh,_lmw,_lmh,_css = {};
				//console.log(_h);
				if(_w > _pw){
					_rsw = _pw/_w;
					_css['width'] = _w = _w*_rsw;
					_css['height'] = _h = _h * _rsw;
					//console.log('_mw:',_w,_h);
				}
				if(_pw > _w){
					_lmw =  _pw-_w;
					_css['margin-left'] = _lmw/2;
					//console.log('_lmw:',_lmw);
				}
				if(_h > _ph){
					_rsh = _ph/_h;
					_css['width'] = _w = _w*_rsh;
					_css['height'] = _h = _h * _rsh;
					//console.log('_mw:',_w,_h);
				}
				if(_ph > _h){
					_lmh = _ph - _h;
					_css['margin-top'] = _lmh/2;
					//console.log('_lmh:',_lmh);
				}
				//console.log(_css['margin-top']);
				//console.log(obj,_css);
				obj.css(_css);
				if ( $.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0')){
					obj.hide();
					setTimeout(function(){
							obj.show();
					},500);
				}
				obj.show();
			}
		}

	var bqPublic = {
		//公共弹层部分
		msgsuc : function(c,t,w,h,s,ms){_Msg(c,t,'ok',s,w,h,ms)},
		msgask : function(c,t,w,h,s,ms){_Msg(c,t,'no',s,w,h,ms)},
		msgwar : function(c,t,w,h,s,ms){_Msg(c,t,'cf',s,w,h,ms)},
		msgcsuc : function(c,t,det,ca,w,h,s){_Msg(c,t,'ok','cf',w,h,det,ca)},
		msgcask : function(c,t,det,ca,w,h,s){_Msg(c,t,'no','cf',w,h,det,ca)},
		msgcwar : function(c,t,det,ca,w,h,s){_Msg(c,t,'cf','cf',w,h,det,ca)},
		msgalok : function(c,t,det,ca,w,h,s){_Msg(c,t,'ok','palet',239,98,det,ca)},
		msgalno : function(c,t,det,ca,w,h,s){_Msg(c,t,'no','palet',239,98,det,ca)},
		msgalcf : function(c,t,det,ca,w,h,s){_Msg(c,t,'cf','palet',239,98,det,ca)},
		setFocus : function(obj){_setFocus(obj)},
		bqFlip : function(i,c,d,w){return  _bqFlip(i,c,d,w);},
		focusab : function(o,v,tpye){_focusab(o,v,tpye);},
		pswdcheck:function(v){return checkStrong(v);},
		stlength:function(str){return byteLen(str);},
		stlengthx2:function(str){return byteLen(str)/2;},
		byrception:function(str,len){return _byrception(str,len);},
		cookie: new Cookies(),
		imgAgp: function(o,w,h,b){_imgAgp(o,w,h,b)}
	}
	
	/*
		公共弹层试用
		BQ.Public.msgsuc('text','title',width,height);
		BQ.Public.msgask('text','title',width,height);
		BQ.Public.msgwar('text','title',width,height);
		
		BQ.Public.msgcsuc('text','title',function(){//点击确定执行},function(){//点击取消执行},width,height);
		BQ.Public.msgcask('text','title','','',width,height);
		BQ.Public.msgcwar('text','title','','',width,height);
	*/

	return bqPublic;
})
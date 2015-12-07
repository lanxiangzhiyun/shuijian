BQ.add('BQExp', function(W,CLASS_NAME){
	
	var defaultConfig = {
		com: '#bqecom', //容器
		but: '#bqebut', //触发按钮
		burl:''
	};

    function ClassObj(eventType, config) {
        var self = this;
        if (!(self instanceof ClassObj)) {
            return new ClassObj(eventType, W.merge(defaultConfig, config));
        }
        var config = self.config = config;
		eventType = eventType ||'emo';
		var com = $(config.com),but = $(config.but);

		function rDom (qqb,bqb) {
			var emoTion = [];
			qqb = bqb || 105;
			bqb = bqb || 20;
			emoTion.push('<div class="emotion none">');
			emoTion.push('<div class="emotion_hd"><ul>');
			emoTion.push('<li class="active"><a href="#">常用表情</a></li>');
			emoTion.push('<li><a href="#">波奇表情</a></li>');
			emoTion.push('</ul><a class="close" href="#" title="关闭">关闭</a></div>');
			emoTion.push('<div class="emotion_bd"><div class="emocom qq_emotion">');
			emoTion.push('<ul class="qq_panel">');
			for(var i = 0; i < qqb ; i++){
				emoTion.push('<li><a href="#"></a></li>');
			}
			emoTion.push('</ul></div><div class="emocom bq_emotion none"><ul class="bq_panel">');
			for(var i = 0; i < bqb ; i++){
				emoTion.push('<li><a href="#"></a></li>');
			}
			emoTion.push('</ul></div><div class="emotion_preview" style="right:3px;"><img src="/Public/Images/emotion/qq/e100.gif"><span>微笑</span></div></div><i class="arr"></i><div class="loading">加载中……</div></div>');
			return emoTion.join('');
		}
		if(!$('.emotion').is('div')){
			$('body').append(rDom());
		}
		var emoDom = $('.emotion'),emoPrw = emoDom.find('.emotion_preview'),emoPrImg = emoPrw.find('img'),emoPrTxt = emoPrw.find('span');
		emoDom.find('.emotion_hd li').click(function(e){
			e.preventDefault();
			var _t = $(this);
			emoDom.find('.emotion_hd li').removeClass('active').eq(_t.index()).addClass('active');
			emoDom.find('.emotion_bd>div.emocom').hide().eq(_t.index()).show();
		})

		var emo = {'1':'愁','2':'晕','3':'怒','4':'骂','5':'心心眼','6':'大笑','7':'问号','8':'哭','9':'羞','10':'飞吻','11':'摇头','12':'发呆','13':'哈欠','14':'摸头','15':'微笑','16':'黑线','17':'偷笑','18':'差劲','19':'惊','20':'鬼脸'}
		var emo2 = {'100':'微笑','101':'撇嘴','102':'色','103':'发呆','104':'得意','105':'流泪','106':'害羞','107':'闭嘴','108':'睡','109':'大哭','110':'尴尬','111':'发怒','112':'调皮','113':'呲牙','114':'惊讶','115':'难过','116':'酷','117':'冷汗','118':'抓狂','119':'吐','120':'偷笑','121':'可爱','122':'白眼','123':'傲慢','124':'饥饿','125':'困','126':'惊恐','127':'流汗','128':'憨笑','129':'大兵','130':'奋斗','131':'咒骂','132':'疑问','133':'嘘','134':'晕','135':'折磨','136':'哀','137':'骷髅','138':'敲打','139':'再见','140':'擦汗','141':'抠鼻','142':'鼓掌','143':'糗大了','144':'坏笑','145':'左哼哼','146':'右哼哼','147':'哈欠','148':'鄙视','149':'委屈','150':'快哭了','151':'阴险','152':'亲亲','153':'吓','154':'可怜','155':'菜刀','156':'西瓜','157':'啤酒','158':'篮球','159':'乒乓','160':'咖啡','161':'饭','162':'猪头','163':'玫瑰','164':'凋谢','165':'示爱','166':'爱心','167':'心碎','168':'蛋糕','169':'闪电','170':'炸弹','171':'刀','172':'足球','173':'瓢虫','174':'便便','175':'月亮','176':'太阳','177':'礼物','178':'拥抱','179':'强','180':'弱','181':'握手','182':'胜利','183':'抱拳','184':'勾引','185':'拳头','186':'差劲','187':'爱你','188':'NO','189':'OK','190':'爱情','191':'飞吻','192':'跳跳','193':'发抖','194':'怄火','195':'转圈','196':'磕头','197':'回头','198':'跳绳','199':'挥手','200':'激动','201':'街舞','202':'献吻','203':'左太极','204':'右太极'};
		self.getEmo = function (i) {
				var et = parseInt(i) >= 100 ? 1 : 0;
				return {
					'src': (et?config.burl+'/Public/Images/emotion/qq/e'+i+'.gif':config.burl+'/Public/Images/emotion/boqii/bq_00'+i+'.gif'),
					'txt': (et?emo2[i]:emo[i])
				}
		}

		//取出值表情转换成路径对象
		self.GetVal = function (str) {
			//var emo='/^',emo2='/';
			str = str || com.val();
			$.each(emo,function(i,d){
				var rgExp = new RegExp('\/\\^'+d,'igm'),emo = self.getEmo(i);
				str = str.replace(rgExp,'<img src="'+emo.src+'" alt="'+emo.txt+'"/>');
			});
			$.each(emo2,function(i,d){
				var rgExp = new RegExp('\/'+d,'igm'),emo = self.getEmo(i);
				str = str.replace(rgExp,'<img src="'+emo.src+'" alt="'+emo.txt+'"/>');
			});
			return str;
		}
		function savePos(textBox){
			var start,end;
			if(typeof(textBox.selectionStart) == "number"){
				start = textBox.selectionStart;
				end = textBox.selectionEnd;
			}
			else if(document.selection){
					var range = document.selection.createRange();
					if(range.parentElement().id == textBox.id){
					var range_all = document.body.createTextRange();
					range_all.moveToElementText(textBox);
					for (start=0; range_all.compareEndPoints("StartToStart", range) < 0; start++)
					range_all.moveStart('character', 1);
					for (var i = 0; i <= start; i ++){
					if (textBox.value.charAt(i) == '\n')
					start++;
					}
					var range_all = document.body.createTextRange();
					range_all.moveToElementText(textBox);
					for (end = 0; range_all.compareEndPoints('StartToEnd', range) < 0; end ++)
					range_all.moveStart('character', 1);
					for (var i = 0; i <= end; i ++){
					if (textBox.value.charAt(i) == '\n')
					end ++;
					}
				}
			}
			return {'st':start,'ed':end};
		}
		
		function saveAdd(tBx,val,sPs){
			sPs = sPs || {'st':0,'ed':0};
			if(tBx.val() == '留个足迹，问候一下吧'){
				tBx.val('');
			}
			if(tBx.val() == '说点什么吧……'){
				tBx.val('');
			}
			var tv = tBx.val(),pre = tv.substr(0, sPs.st),post = tv.substr(sPs.ed);
			tBx.val(pre + val + post);
			tBx.focus();
		}
		
		
		function EveOn(bqecom,bqebut) {

			var sPs;
			bqecom.bind('keydown keyup mousedown mouseup focus',function(){
				sPs = savePos(this);
				//console.log(sPs.st,sPs.ed);
			});
			
			bqebut.click(function(e){
				e.preventDefault();
				var _t = $(this),_oft = _t.offset(),emoDomOft;
				emoDom.css({'top':_oft.top+_t.height()+8,'left':_oft.left, 'zIndex': '99999'}).show();
				emoDomOft = {'lf':_oft.left,'wt':emoDom.width()};

			
				emoDom.find('.emotion_bd ul.qq_panel li').unbind().click(function(e){
					e.preventDefault();
					//console.log(bqecom,100+$(this).index(),sPs);
					saveAdd(bqecom,'/'+self.getEmo(100+$(this).index()).txt,sPs);
					emoDom.hide();
				}).hover(function(){
					var _t = $(this),_oft = _t.offset();
					var gEmo = self.getEmo(100+$(this).index());
					//console.log(_oft.left-emoDomOft.lf , emoDomOft.wt/2);
					(_oft.left-emoDomOft.lf > emoDomOft.wt/2-20) ? emoPrw.attr('style','left:3px') : emoPrw.attr('style','right:3px');
					emoPrw.show();
					emoPrImg.attr('src',gEmo.src);
					emoPrTxt.html(gEmo.txt);
				},function(){
					emoPrw.hide();
				});
				emoDom.find('.emotion_bd ul.bq_panel li').unbind().click(function(e){
					e.preventDefault();
					//console.log(bqecom,$(this).index(),sPs);
					saveAdd(bqecom,'/^'+self.getEmo($(this).index()+1).txt,sPs);
					emoDom.hide();
				}).hover(function(){
					var _t = $(this),_oft = _t.offset();
					//emoDom.css({'top':_oft.top+_t.height()+8,'left':_oft.left, 'zIndex': '99999'}).show();
					var gEmo = self.getEmo($(this).index()+1);
					(_oft.left-emoDomOft.lf > emoDomOft.wt/2-20) ? emoPrw.attr('style','left:3px') : emoPrw.attr('style','right:3px');
					emoPrw.show();
					emoPrImg.attr('src',gEmo.src);
					emoPrTxt.html(gEmo.txt);
				},function(){
					emoPrw.hide();
				});
				emoDom.find('.close').unbind().click(function(e){
					e.preventDefault();
					emoDom.hide();
				});
				//点击关闭层
				$(document).unbind('click').click(function(e){
					var _e = $(e.target);
					if(!_e.parents('.emotion')[0] && (_e[0] != com[0] && (_e[0] != but[0]))){
						emoDom.hide();
					}
				});
			});
			
		
			
		}

		if(eventType == 'emo'){
			EveOn(com,but);
		}

	};

	 W.augment(ClassObj, {
			getVal:function(str){return this.GetVal(str);},
			getEmofoing:function(){return this.getEmo();}
	 })
	return ClassObj;

})



BQ.add('Avatar', function(W,CLASS_NAME){

	function loadAvatar(d,p,a,s,f,type,w,h){
		var _pP;
		function rDom () {
				var emoTion = [];
				emoTion.push('<div id="allBox"><div id="picBox"><div id="runSub">图片处理中……</div><div id="picViewOuter">图片载入中……</div>');
				//emoTion.push('<div id="sliderOuter"><div id="run"><a href="#" id="subCut">提交截图</a></div><div id="slider"><span id="sliderBlock">100%</span></div></div></div></div>');
				emoTion.push('<div id="sliderOuter"><div id="slider"><span id="sliderBlock">100%</span></div></div></div></div>');
				return emoTion.join('');
		}

		if(!$('#allBox').is('div')){
			$(d).append(rDom());
		}
		s = s || {'width':120,'height':120};
		f = f || function(){};
		//关于图片处理
		var _cutMinW=20; //切片最小宽度
		var _cutMinH=20; //切片最小高度
		//var _imgPath='Public/Images/temp/25.jpg'; //图片路径
		var _imgPath= p || 'Public/Images/temp/32.jpg'; //图片路径
		var action = a || 'Public/DemoForPHP/avatar.php';

		var _cutMinW = _cutMinW || 48;
		var _cutMinH = _cutMinH || 48;


		var getID = function(o) {
			return document.getElementById(o);
		}

		/*
		图片实际宽度为imgW
		图片实际高度为imgH
		图片当前宽度为nowW
		图片当前高度为nowH
		截图X坐标为px
		截图Y坐标为py
		截图宽度为pw
		截图高度为ph
		图片地址picurl
		*/
		var getCut = function(){
			var rom = {
				'imgw':Math.ceil(_imgW),
				'imgh':Math.ceil(_imgH),
				'noww':Math.ceil(_nowW),
				'nowh':Math.ceil(_nowH),
				'px':Math.ceil(_pP.offsetWidth+1-_imgO.offsetLeft),
				'py':Math.ceil(_pP.offsetHeight+1-_imgO.offsetTop),
				'pw':Math.ceil(_pO.offsetWidth-2),
				'ph':Math.ceil(_pO.offsetHeight-2),
				'picurl':_imgPath,
				'type':type
			};
			return rom;
		}

		/*$('#saveBtnPic').click(function(e){
			e.preventDefault();
			//console.log(getCut(),a);
			$.post(a,getCut(),function(d){
				console.log(d);
				
				
			},'json')
		})*/
		/*getID('subCut').onclick = function(){
			var rom = {
				'imgw':Math.ceil(_imgW),
				'imgh':Math.ceil(_imgH),
				'noww':Math.ceil(_nowW),
				'nowh':Math.ceil(_nowH),
				'px':Math.ceil(_pP.offsetWidth+1-_imgO.offsetLeft),
				'py':Math.ceil(_pP.offsetHeight+1-_imgO.offsetTop),
				'pw':Math.ceil(_pO.offsetWidth-2),
				'ph':Math.ceil(_pO.offsetHeight-2),
				'picurl':_imgPath
			};
			console.log(rom);
			return false;
		}*/

		getID("allBox").onselectstart = function() {
			return false
		};
		var _imgO,
		_imgW,
		_imgH,
		_nowW,
		_nowH,
		_nowL,
		_nowT,
		_imgMinW,
		_imgMinH;
		var _pP = null;
		var _pO = null;
		var __M = 0;

		getID('picViewOuter').innerHTML = '<table id="picMask" border="0" cellspacing="0" cellpadding="0"><tr><td id="pP">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td id="pO"><div id="pC"></div></td><td>&nbsp;</td></tr><tr><td height="225">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></table><img src="' + _imgPath + '?' + new Date().getTime() + '" border="0" id="sourceimg" />';

		//readycanvas();
		_picLoad();

		var _dragStr1 = '';
		var _dragStr2 = '';
		var _ds = {
			'tl': [1, 1, -1, -1],
			'tm': [0, 1, 0, -1],
			'tr': [0, 1, 1, -1],
			'ml': [1, 0, -1, 0],
			'mr': [0, 0, 1, 0],
			'bl': [1, 0, -1, 1],
			'bm': [0, 0, 0, 1],
			'br': [0, 0, 1, 1]
		};

		/*for (var k in _ds) {
			//_dragStr1 += '<table id="dm_' + k + '" class="dm" border="0" cellspacing="0" cellpadding="0"><tr><td></td></tr></table>';
			_dragStr1 += '<div id="dm_' + k + '" class="dm"></div>';
			_dragStr2 += 'getID(\'dm_' + k + '\').onmousedown=function(e){dragsChange(e,\'' + k + '\',this)}\n';
		}*/
		getID('pC').innerHTML = _dragStr1 + '<div id="pI"></div>';
		eval(_dragStr2);
		_ds['tO'] = [1, 1, 0, 0];
		getID('pO').onmousedown = function(e) {
			$.browser.msie && this.setCapture();
			dragsChange(e, 'tO',this);
		};
		function dragsChange(e, k,t) {
			var e = window.event || e;
			e.cancelBubble = true;
			$.browser.msie && t.setCapture();
			var _piccutX = e.clientX;
			var _piccutY = e.clientY;
			//console.log(_pO.offsetWidth,_pO.offsetHeight);
			_pP = getID('pP');
			_pO = getID('pO');
			var evalStr = 'var _pX=' + _pP.offsetWidth + '+' + _ds[k][0] + '*_posX-2;var _pY=' + _pP.offsetHeight + '+' + _ds[k][1] + '*_posY-2;var _pW=' + _pO.offsetWidth + '+' + _ds[k][2] + '*_posX;var _pH=' + _pO.offsetHeight + '+' + _ds[k][3] + '*_posY;var _oldW=_pX+_pW;var _oldH=_pY+_pH;';
			if (_ds[k][0] != 0) evalStr += '_pP.style.width=_pW<=_cutMinW?_oldW-_cutMinW:_pX+\'px\';';
			if (_ds[k][1] != 0) evalStr += '_pP.style.height=_pW<=_cutMinH?_oldH-_cutMinH:_pY+\'px\';';
			//if (_ds[k][2] != 0) evalStr += '_pO.style.width=getID(\'pC\').style.width=(_pW>=290?290:(_pW<=_cutMinW?_cutMinW:_pW))+\'px\';';
			//if (_ds[k][3] != 0) evalStr += '_pO.style.height=getID(\'pC\').style.height=(_pH>=215?215:(_pH<=_cutMinH?_cutMinH:_pH))+\'px\';';
			document.onmousemove = function(e) {
				var e = window.event || e;
				var _posX = e.clientX - _piccutX;
				var _posY = e.clientY - _piccutY;
				try {
					eval(evalStr);
				} catch(err) {}
				try {
					eval(chkSize('X', 'W', 'L', 'width', 'left', 0, 2, 300));
				} catch(err) {}
				try {
					eval(chkSize('Y', 'H', 'T', 'height', 'top', 1, 3, 225));
				} catch(err) {}
				document.onmouseup = function() {
					$.browser.msie && t.releaseCapture();
					f(getCut());
					document.onmousemove = null
				};
			}
		};
		function _picLoad() {



			_imgO = getID('sourceimg')/*,_cas = getID('canvas')*/;
			if (_imgO == null) {
				//alert('没有载入源图片，无法截图，请载入图片！');
				return;
			}
			_img = new Image();
			_img.src = _imgO.src;
			_img.onload = function(){
		
			//f(getCut());
			/*_nowW = _imgW = img1.offsetWidth;
			_nowH = _imgH = img1.offsetHeight;*/

			_nowW = _imgW = w||this.width;
			_nowH = _imgH = h||this.height;
		
			_nowL = (300 - _nowW) / 2;
			_nowT = (225 - _nowH) / 2;
			_imgO.style.left = _nowL + 'px';
			_imgO.style.top = _nowT + 'px';
			_pP = getID('pP');
			_pO = getID('pO');
			_pO.style.width=s.width+'px';
			_pO.style.height = s.height+'px';

			if (_pO.offsetWidth > 200 || _pO.offsetHeight > 200) {
				alert('切片初始尺寸太大了！宽、高都不能超过200px');
				_pO.style.width = getID('pC').style.width = s.width+'px';
				_pO.style.height = getID('pC').style.height = s.height+'px';
			}
			if (_nowW <= _pO.offsetWidth) {
				_pP.style.width = _nowL - 1 + 'px';
				_pO.style.width = getID('pC').style.width = _nowW + 'px';
			}
			if (_nowH <= _pO.offsetHeight) {
				_pP.style.height = _nowT - 1 + 'px';
				_pO.style.height = getID('pC').style.height = _nowH + 'px';
			}
			if (_nowW < _cutMinW || _nowH < _cutMinH) {
				_imgMinW = _nowW;
				_imgMinH = _nowH;
			} else {
				if (_cutMinW / _cutMinH > _nowW / _nowH) {
					_imgMinW = _cutMinW;
					_imgMinH = _nowH * _cutMinW / _nowW;
				} else {
					_imgMinW = _nowW * _cutMinH / _nowH;
					_imgMinH = _cutMinH;
				}
			}

			}
		}
		getID('sliderBlock').onmousedown = function(e) {
			var e = window.event || e;
			var _scrO = this;
			var _imgX = e.clientX - _scrO.offsetLeft;
			var _t = this;
			//console.log(123);
			$.browser.msie && _t.setCapture();

			document.onmousemove = function(e) {
				var e = window.event || e;
				var _posX = e.clientX - _imgX;
				_posX = _posX < 0 ? 0: _posX;
				_posX = _posX > 124 - 24 ? 124 - 24: _posX;

				_nowW = _imgW * (_posX / 50);
				_nowH = _imgH * (_posX / 50);
				
				/*if(_nowW <= s.width){return;}
				if(_nowH <= s.height){return;}*/
				//console.log(_posX);

				_scrO.style.left = _posX + "px";
				_scrO.innerHTML = _posX * 2 + '%';
				_imgMinW = s.width;
				_imgMinH = s.height;
				//console.log(_imgMinW,_imgMinH,s);
				if (_nowW <= _imgMinW || _nowH <= _imgMinH) {
					_nowW = _imgMinW;
					_nowH = _imgMinH;
				}
				_nowL = (300 - _nowW) / 2;
				_nowT = (225 - _nowH) / 2;
				_imgO.style.width = _nowW + 'px';
				_imgO.style.height = _nowH + 'px';
				_imgO.style.left = _nowL + 'px';
				_imgO.style.top = _nowT + 'px';

				_pP = getID('pP');
				_pO = getID('pO');
				if (_nowW <= _pO.offsetWidth) {
					_pP.style.width = _nowL - 1 + 'px';
					_pO.style.width = getID('pC').style.width = _nowW + 'px';
				}
				if (_nowH <= _pO.offsetHeight) {
					_pP.style.height = _nowT - 1 + 'px';
					_pO.style.height = getID('pC').style.height = _nowH + 'px';
				}
				if (_nowH > _pO.offsetHeight)
				{
					if(_pO.offsetHeight<s.height){
						getID('pC').style.height = _nowH + 'px';
						getID('pC').style.width = _nowW + 'px';
					}else{
						getID('pC').style.height = s.height+'px';
						getID('pC').style.width = s.width+'px';
					}
				}
				
				var _pX = _pP.offsetWidth - 2;
				var _pY = _pP.offsetHeight - 2;
				var _pW = _pO.offsetWidth;
				var _pH = _pO.offsetHeight;
				__M = 1;
				/*try {
					//eval(chkSize('X', 'W', 'L', 'width', 'left', 0, 2, 300));
				} catch(err) {};
				try {
					//eval(chkSize('Y', 'H', 'T', 'height', 'top', 1, 3, 225));
				} catch(err) {};*/
				__M = 0;
				document.onmouseup = function() {
					$.browser.msie && _t.releaseCapture();
					f(getCut());
					document.onmousemove = null;
				}
			}
		};
		function chkSize(X, W, L, w, l, i, j, n) {
			return 'if(_now' + W + '<=_cutMin' + W + '){_pP.style.' + w + '=_now' + L + '-1+\'px\';_pO.style.' + w + '=_now' + W + '+\'px\';}else if(_now' + W + '>=' + n + '){var _nN=((_p' + X + '+_p' + W + '/2)/' + n / 2 + ')*_now' + L + ';if(_nN>=0){_nN=0;}if(_nN<=' + n + '-_now' + W + '){_nN=' + n + '-_now' + W + ';}_imgO.style.' + l + '=_nN+\'px\';if(_p' + X + '<=4){_pP.style.' + w + '=\'4px\';}if(_p' + X + '+_p' + W + '+2+4>=' + n + '){if(_ds[k][' + i + ']==0){_pO.style.' + w + '=' + n + '-_p' + X + '-8+\'px\';}if(_ds[k][' + j + ']==0){_pP.style.' + w + '=' + n + '-_p' + W + '-4+\'px\';}}}else{if(_p' + X + '<=_now' + L + '){_pP.style.' + w + '=_now' + L + '-1+\'px\';if(_ds[k][' + i + ']!=0 && _ds[k][' + j + ']!=0){var _old' + W + '=_p' + X + '+_p' + W + ';_pO.style.' + w + '=_old' + W + '-_now' + L + '+1+\'px\';}}if(_p' + X + '+2+_p' + W + '>=_now' + W + '+_now' + L + '){if(_ds[k][' + i + ']==0){if(__M!=1){_pO.style.' + w + '=_now' + L + '+_now' + W + '-_p' + X + '-3+\'px\';}else{_pP.style.' + w + '=_now' + L + '+_now' + W + '-_p' + W + '+1+\'px\';}}if(_ds[k][' + j + ']==0){_pP.style.' + w + '=_now' + L + '+_now' + W + '-_p' + W + '+1+\'px\';}}};f(getCut());';
		};
		return getCut;
	}
	var Avatar = {
		//公共弹层部分
		load : function(d,p,a,s,f,type,w,h){return loadAvatar(d,p,a,s,f,type,w,h);}
	}
	return Avatar;
});


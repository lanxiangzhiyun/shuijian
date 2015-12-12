/**
* 旋转木马中的无缝滚动效果
* @author 李文琨
* @version 0.11.26.1
*/

/**
* 2010-11-07 李文琨: 修正在非无缝滚动时，滚动到最后或第一页再点击btnNext/btnPrev会出错;将配置信息传给实例
*/
/**
 * 实例中所具有的属性：
 * self.config
 * self.container
 * self.curr
 */
BQ.add('widget.SeamlesScroll', function (W, CLASS_NAME) {
	var EVENT_BEFORE_SWITCH = 'beforeSwitch', EVENT_SWITCH = 'switch',
		GUID = 0, oldVisibles = null;


	var defaultConfig = {
		panels: [],
		btnPrev: null,
		btnNext: null,
		disableBtnCls: 'disabled', // 最终转为['leftDisabled', 'rightDisabled']
		btnGo: null,
		auto: null, // 指定多少秒内容定期自动滚动。默认为空(null),是不滚动,如果设定的,单位为毫秒,如1秒为1000

		speed: 200, // 滑动的速度，设置成0将删除效果
		easing: null, // 缓冲效果名称,如：easing: "bounceout"

		vertical: false, //是否垂直滚动
		circular: true, // 是否循环滚动,如果为false,滚动到最后一个将停止滚动
		visible: 5, // 可见数量
		start: 0, // 开始点
		scroll: 1, //每次滚动数量
		running: false, // 是否处理滚动状态 可用于中途暂停自动滚动
		viewSize: [] // 自定义元素的高度和宽度
	};
    function ClassObj(container, config) {
        var self = this;
        // factory or constructor
        if (!(self instanceof ClassObj)) {
            return new ClassObj(container, W.merge(defaultConfig, config || {}));
        }
        self.config = config;
        self.container = $(container);

        // 解决子父层运用自定义事件窜扰问题
        self.guid = ++GUID + CLASS_NAME;

        // 'disabled' to ['disabled', 'disabled']
        var btnCls = config.disableBtnCls;
        if (W.isString(btnCls)) {
            btnCls = config.disableBtnCls = [btnCls, btnCls];
        }

        if (W.isString(config.panels)) {
            config.panels = $(config.panels, self.container);
        }

        var animCss = config.vertical ? "top" : "left", sizeCss = config.vertical ? "height" : "width",
			tLi = config.panels, tl = tLi.size(), v = config.visible;

		// 当滚动项不足显示个数时关闭无缝滚动
		if(config.visible > tl){
			config.circular = false;
		}

		if (config.btnPrev) {
			if (W.isString(config.btnPrev)) {
				config.btnPrev = $(config.btnPrev, self.container);
			}
			config.btnPrev.click(function () {
				if (!config.btnPrev.hasClass(btnCls[0])) self.go(self.curr - config.scroll);
				return false;
			});
			// 开始点为第一个 + 非无缝滚动时，不允许点上一页
            config.btnPrev[['removeClass', 'addClass'][~~(!config.start && !config.circular)]](btnCls[0]);
		}

		if (config.btnNext) {
			if (W.isString(config.btnNext)) {
				config.btnNext = $(config.btnNext, self.container);
			}
			config.btnNext.click(function () {
				var scur = self.curr + config.scroll;
				//if(scur < config.visible){
				if (!config.btnNext.hasClass(btnCls[1])) self.go(self.curr + config.scroll);
				//}
				return false;
			});
            // 当滚动项不够一次滚动时将btnNext设置为不可点
            config.btnNext[['removeClass', 'addClass'][~~(tl < config.scroll)]](btnCls[1]);
		}

		// 当滚动项不存在时退出程序
		if (!tl) return false;

		var ul = $(config.panels[0].parentNode), div = ul.parent();

		tLi.each(function (i, obj) {
			$(obj).attr('asins', i + 1);
		});

		if (config.circular) {
			/*ul.prepend( innerShiv(tLi.slice(tl - v).clone()) )
			  .append( innerShiv(tLi.slice(0, v).clone()) );*/
			ul.prepend( tLi.slice(tl - v).clone() )
			  .append( tLi.slice(0, v).clone() );
			config.start += v;
		}

		var lis = ul.children(), itemLength = lis.size();
		self.curr = config.start;
		div.css("visibility", "visible");
		lis.css({ "overflow": "hidden", "float": config.vertical ? "none" : "left" });
		ul.css({ margin: "0", padding: "0", position: "relative", "list-style-type": "none", "z-index": "1" });
		div.css({ overflow: "hidden", position: "relative", "z-index": "2", left: "0px" });
		var liSize = config.vertical ? getHeight(lis[0]) : getWidth(lis[0]);   // Full li size(incl margin)-Used for animation
		var ulSize = liSize * itemLength;                   // size of full ul(total length, not just for the visible items)
		var divSize = liSize * v;                           // size of entire div(total length for just the visible items)

		var liSi = config.viewSize[~ ~config.vertical] || lis[['width', 'height'][~ ~config.vertical]]();
		lis.css(sizeCss, liSi + 'px');
		ul.css(sizeCss, ulSize + "px").css(animCss, -(self.curr * liSize));

        if (config.btnGo) {
            if (W.isString(config.btnGo)) {
                config.btnGo = $(config.btnGo, self.container);
            }
            $.each(config.btnGo, function (i, val) {
                $(val).click(function () {
                    return self.go((config.circular ? config.visible : 0) + i * config.scroll);
                });
            });
        }

		if(config.auto){
			var timer = function(){
				self.later = W.later(function () {
					self.go(self.curr + config.scroll);
				}, config.auto + config.speed, true);
			},
			autoEvents = {
				'mouseenter': function(){
					self.later && self.later.cancel();
				},
				'mouseleave': timer
			};

			timer();

			div.bind(autoEvents);
			if(config.btnNext) config.btnNext.bind(autoEvents);
			if(config.btnPrev) config.btnPrev.bind(autoEvents);
		}

        function vis(cu) {
            return lis.slice(cu).slice(0, config.scroll);
        };

        function getIndex(visibles) {
            return ~ ~visibles.slice(0, 1).attr('asins');
        }

        function upCurr(to) {
            var cu;
            //console.log(to);
            if (config.circular) {            // If circular we are in first or last, then goto the other end
				
                if (to <= config.start - v - 1) {           // If first, then goto last
                    ul.css(animCss, -((itemLength - (v * 2)) * liSize) + "px");
                    // If "scroll" > 1, then the "to" might not be equal to the condition; it can be lesser depending on the number of elements.
                    cu = to == config.start - v - 1 ? itemLength - (v * 2) - 1 : itemLength - (v * 2) - config.scroll;
                } else if (to >= itemLength - v + 1) { // If last, then goto first
					//console.log('is last, goto first:'+to);
                    ul.css(animCss, -((v) * liSize) + "px");
                    // If "scroll" > 1, then the "to" might not be equal to the condition; it can be greater depending on the number of elements.
                    cu = to == itemLength - v + 1 ? v + 1 : v + config.scroll;
                } else cu = to;
            } else {                    // If non-circular and to points to first or last, we just return.
                if (to < 0 || to > itemLength) return;
                else cu = to;
            }                           // If neither overrides it, the curr will still be "to" and we can proceed.

            return cu
        }

        self.go = function (to) {
            if (!config.running) {
                oldVisibles = vis(self.curr); // 得到老的焦点处元素
                var ciCurr = self.curr = upCurr(to),
					visObj = vis(self.curr), // 得到新的焦点处元素
					eventObj = { container: self.container, currentIndex: getIndex(visObj), visibles: visObj, oldVisibles: oldVisibles };
                self.container.trigger(EVENT_BEFORE_SWITCH + self.guid, eventObj);
                config.running = true;

                if (!config.circular){
					if(ciCurr < 0) ciCurr = 0
					else if(ciCurr + config.visible > itemLength)
						ciCurr = config.visible > tl ? 0 : (itemLength - config.visible);
                }

                ul.animate(
                    animCss == "left" ? { left: -(ciCurr * liSize)} : { top: -(ciCurr * liSize) }, config.speed, config.easing,
                    function () {
                        self.container.trigger(EVENT_SWITCH + self.guid, eventObj);
                        config.running = false;
                    }
                );
                // Disable buttons when the carousel reaches the last/first, and enable when not
                if (!config.circular) {
                    var btnCls = config.disableBtnCls;
                    if(config.btnPrev){
						//config.btnPrev.removeClass(btnCls[0]);
						//self.curr 0 && config.btnPrev.addClass(btnCls[0]);
						config.btnPrev[['removeClass', 'addClass'][~~(self.curr <= 0)]](btnCls[0]);
					}
					if(config.btnNext){
						//config.btnNext.removeClass(btnCls[1]);
						//self.curr + config.scroll >= itemLength && config.btnNext.addClass(btnCls[1]);
						config.btnNext[['removeClass', 'addClass'][~~(self.curr + config.scroll >= itemLength)]](btnCls[1]);
					}
                }

            }
            return false;
        };

        function getCss(el, prop) {
            return parseInt($.css(el, prop)) || 0;
        };
        function getWidth(el) {
            return (config.viewSize[0] || el.offsetWidth) + getCss(el, 'marginLeft') + getCss(el, 'marginRight');
        };
        function getHeight(el) {
            var marg = 0, mTop = getCss(el, 'marginTop'), mBot = getCss(el, 'marginBottom');
            if (mBot > mTop) marg = mBot - mTop;
            return (config.viewSize[1] || el.offsetHeight) + mTop + marg;
        };
    }

    W.augment(ClassObj, {
        on: function (eventType, callback) {
            this.container.bind(eventType + this.guid, function (s, d) {
                s.stopPropagation();
                callback.call(s, d);
            });
        },
        next: function () {
            var self = this;
            self.go(self.curr + self.config.scroll);
        },
        prev: function () {
            var self = this;
            self.go(self.curr - self.config.scroll);
        }
    });


/* 解决 HTML5标签无法解析问题 */
	/*var d, r;
	if (!d) {
		d = document.createElement('div');
		r = document.createDocumentFragment();
		d.style.display = 'none';
	}
	
	function innerShiv(h, u) {
		var e = d.cloneNode(true);
		document.body.appendChild(e);
		e.innerHTML = h.replace(/^\s\s*|\s\s*$/, '');
		document.body.removeChild(e);
		
		if (u === false) return e.childNodes;
		
		var f = r.cloneNode(true), i = e.childNodes.length;
		while (i--) f.appendChild(e.firstChild);
		
		return f;
	}*/

    return ClassObj;

});

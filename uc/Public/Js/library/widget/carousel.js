BQ.add("widget.Carousel",function(d){function g(b,e){var a=this;if(!(a instanceof g))return new g(b,e);var k=e.disableBtnCls;$.each(["prev","next"],function(d,c){var f=a[c+"Btn"]=$(i+e[c+"BtnCls"],b);f.bind("click",function(b){b.preventDefault();if(!f.hasClass(k))a[c]()})});$(b).bind("init",function(){if(e.circular==3){var b=$(a.panels[0]),c=$(a.panels[a.length-1]),f=a.content,d=a.panels.slice(-e.visual).clone(!0),g=a.panels.slice(0,e.visual).clone(!0);b.before(d);a.panels=d.add(a.panels);c.after(g);
a.panels=a.panels.add(g);f.width(e.viewSize[0]*(a.panels.length/e.steps));f.css("")}});g.superclass.constructor.call(a,b,d.merge(l,e))}var i=".",l={triggerType:"click",visual:1,navCls:"wy-carousel-nav",contentCls:"wy-carousel-content",prevBtnCls:"wy-carousel-prev-btn",nextBtnCls:"wy-carousel-next-btn",disableBtnCls:"wy-carousel-disable-btn"};d.augment(d.widget.Switchable,{_switchView:function(b,e,a,g){function h(){i.call(c,b,e,function(){c._fireOnSwitch(a)},a,g)}var c=this,f=c.config,j=f.effect,i=
d.isFunction(j)?j:d.widget.Switchable.Effects[j];switch(f.circular){case 1:if(a+f.visual<=c.length||a===c.length-1)a===c.length-1&&(a=c.length-f.visual),h();break;case 2:a%=f.visual;h();break;case 3:h();break;default:h()}},prev:function(){var b=this.activeIndex;this.config.circular!==2?this.switchTo(b>0?b-1:this.length-1,"backward"):this.switchTo(b>0?b-1:this.length,"backward")},next:function(){var b=this.activeIndex;this.config.circular!==2?this.switchTo(b<this.length-1?b+1:0,"forward"):this.switchTo(b<
this.length?b+1:0,"forward")}});d.extend(g,d.widget.Switchable);d.namespace("widget").Carousel=g});

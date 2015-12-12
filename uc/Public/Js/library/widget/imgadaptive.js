/*
* ImgAdaptive 图片缩放
* @author 陈桥
* @version 1.0
*/
BQ.add('widget.ImgAdaptive', function (W) {
    var defaultConfig = {
        Type: 0,
        layer: null,
        sizeWidth: 0,//限定最大宽度
        sizeHeight: 0//限定最大高度
    };
    function ClassObj(target, config) {
        var self = this;
        if (!(self instanceof ClassObj)) {
            return new ClassObj(target, W.merge(defaultConfig, config));
        }
        var config = self.config = config;
        function Dislay() {
            var tar = $(target), Currimg, lodimg, style, iecss, width, height, mint;
            var load = function (tag) {
                if ($(tag).attr('src') != undefined) {
                    Currimg = { width: $(tag).width(), height: $(tag).height() };
                    return true;
                } else {
                    Currimg = { width: config.sizeWidth, height: config.sizeHeight };
                    return true;
                }
            };

            var style1 = function () {
                tar.each(function () {
                    if (load(this)) {
                        if (config.sizeWidth != 0 && config.sizeHeight == 0) {
                            if (Currimg.width > config.sizeWidth || Currimg.height > config.sizeHeight) {
                                $(this).css({ width: Currimg.width - (Currimg.width - config.sizeWidth), height: 'auto' });
                            }
                        }
                        if (config.sizeWidth == 0 && config.sizeHeight != 0) {
                            if (Currimg.width > config.sizeWidth || Currimg.height > config.sizeHeight) {
                                $(this).css({ width: 'auto', height: Currimg.height - (Currimg.height - config.sizeHeight) });
                            }
                        }
                        if (config.sizeWidth != 0 && config.sizeHeight != 0) {
                            if (Currimg.width > config.sizeWidth || Currimg.height > config.sizeHeight) {
                                $(this).css({ width: Currimg.width - (Currimg.width - config.sizeWidth), height: Currimg.height - (Currimg.height - config.sizeHeight) });
                            }
                        }
                    }
                });
            };
            this.Initialize = function () {
                style1();
            }
        }
        new Dislay().Initialize();
		
		/*function getSize () {
			
			
		}*/


    };
    return ClassObj;
});
BQ.add('BQSubInfo', function(W,CLASS_NAME){
	
	var defaultConfig = {
		con: '#bqcom1', 
		text: '#bqtexsub', 
		max: 140 //触发按钮
	};

    function ClassObj(eventType, config) {
        var self = this;
        if (!(self instanceof ClassObj)) {
            return new ClassObj(eventType, W.merge(defaultConfig, config));
        }
        var config = self.config = config;
		eventType = eventType ||'mup';

		var con = $(config.con),info =$(config.text);
		
		function byteLen(str){
			str = str || '';
			var num = 0, i = 0, len = str.length, unicode;
			for(; i < len; i++){
				unicode = str.charCodeAt(i);
				num += unicode > 127 ? 2 : 1;
			}
			return num;
		}
		info.text(0 + '/'+config.max);
		con.bind('keydown keyup mousedown mouseup focus',function(){
			keyCon(this.value);
		});
		var bokey = 0;
		function keyCon(v){
			var zs= parseInt(byteLen(v) / 2);
			//console.log(zs);
			if(config.max >= zs){
					info.text(zs+'/'+config.max);
					bokey = 1;
			}else{
					bokey = 0;
					info.html('<span class="erro">'+(zs)+'</span>'+'/'+config.max);
			}
		}
		this.bokey = function(){
			return bokey;
		}
		keyCon(con.val());
	};

	 W.augment(ClassObj, {
			getSubV:function(){return this.bokey();}
	 })
	return ClassObj;

})

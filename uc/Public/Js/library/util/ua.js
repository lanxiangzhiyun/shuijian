BQ.add("util.Ua",function(){var a=navigator.userAgent.toLowerCase(),b={ie:/msie/.test(a)&&!/opera/.test(a),firefox:/firefox/.test(a),opera:/opera/.test(a),safari:/webkit/.test(a)&&!/chrome/.test(a),chrome:/chrome/.test(a)},d="",c;for(c in b)if(b[c]){d=c=="safari"?"version":c;break}b.version=d&&RegExp("(?:"+d+")[\\/: ]([\\d.]+)").test(a)?parseInt(RegExp.$1,10):"0";b.os={isWin:/windows|win32/.test(a),isMac:/macintosh|mac os x/.test(a),isAir:/adobeair/.test(a),isLinux:/linux/.test(a)};b.isSecure=/^https/i.test(window.location.protocol);
b.domIsStrict=document.compatMode=="CSS1Compat";return b});

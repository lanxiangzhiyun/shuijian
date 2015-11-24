<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * Think 标准模式公共函数库
 * @category   Think
 * @package  Common
 * @author   liu21st <liu21st@gmail.com>
 */

/**
 * 错误输出
 * @param mixed $error 错误
 * @return void
 */
function halt($error) {
    $e = array();
    if (APP_DEBUG) {
        //调试模式下输出错误信息
        if (!is_array($error)) {
            $trace          = debug_backtrace();
            $e['message']   = $error;
            $e['file']      = $trace[0]['file'];
            $e['line']      = $trace[0]['line'];
            ob_start();
            debug_print_backtrace();
            $e['trace']     = ob_get_clean();
        } else {
            $e              = $error;
        }
    } else {
        //否则定向到错误页面
        $error_page         = C('ERROR_PAGE');
        if (!empty($error_page)) {
            redirect($error_page);
        } else {
            if (C('SHOW_ERROR_MSG'))
                $e['message'] = is_array($error) ? $error['message'] : $error;
            else
                $e['message'] = C('ERROR_MESSAGE');
        }
    }
    // 包含异常页面模板
    include C('TMPL_EXCEPTION_FILE');
    exit;
}

/**
 * 自定义异常处理
 * @param string $msg 异常消息
 * @param string $type 异常类型 默认为ThinkException
 * @param integer $code 异常代码 默认为0
 * @return void
 */
function throw_exception($msg, $type='ThinkException', $code=0) {
    if (class_exists($type, false))
        throw new $type($msg, $code);
    else
        halt($msg);        // 异常类型不存在则输出错误信息字串
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

/**
 * 404处理 
 * 调试模式会抛异常 
 * 部署模式下面传入url参数可以指定跳转页面，否则发送404信息
 * @param string $msg 提示信息
 * @param string $url 跳转URL地址
 * @return void
 */
function _404($msg='',$url='') {
    APP_DEBUG && throw_exception($msg);
    if($msg && C('LOG_EXCEPTION_RECORD')) Log::write($msg);
    if(empty($url) && C('URL_404_REDIRECT')) {
        $url    =   C('URL_404_REDIRECT');
    }
    if($url) {
        redirect($url);
    }else{
        send_http_status(404);
        exit;
    }
}

/**
 * 设置当前页面的布局
 * @param string|false $layout 布局名称 为false的时候表示关闭布局
 * @return void
 */
function layout($layout) {
    if(false !== $layout) {
        // 开启布局
        C('LAYOUT_ON',true);
        if(is_string($layout)) { // 设置新的布局模板
            C('LAYOUT_NAME',$layout);
        }
    }else{// 临时关闭布局
        C('LAYOUT_ON',false);
    }
}

/**
 * URL组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[分组/模块/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $redirect 是否跳转，如果设置为true则表示跳转到该URL地址
 * @param boolean $domain 是否显示域名
 * @return string
 */
function U($url='',$vars='',$suffix=true,$redirect=false,$domain=false) {
    // 解析URL
    $info   =  parse_url($url);
    $url    =  !empty($info['path'])?$info['path']:ACTION_NAME;
    if(isset($info['fragment'])) { // 解析锚点
        $anchor =   $info['fragment'];
        if(false !== strpos($anchor,'?')) { // 解析参数
            list($anchor,$info['query']) = explode('?',$anchor,2);
        }        
        if(false !== strpos($anchor,'@')) { // 解析域名
            list($anchor,$host)    =   explode('@',$anchor, 2);
        }
    }elseif(false !== strpos($url,'@')) { // 解析域名
        list($url,$host)    =   explode('@',$info['path'], 2);
    }
    // 解析子域名
    if(isset($host)) {
        $domain = $host.(strpos($host,'.')?'':strstr($_SERVER['HTTP_HOST'],'.'));
    }elseif($domain===true){
        $domain = $_SERVER['HTTP_HOST'];
        if(C('APP_SUB_DOMAIN_DEPLOY') ) { // 开启子域名部署
            $domain = $domain=='localhost'?'localhost':'www'.strstr($_SERVER['HTTP_HOST'],'.');
            // '子域名'=>array('项目[/分组]');
            foreach (C('APP_SUB_DOMAIN_RULES') as $key => $rule) {
                if(false === strpos($key,'*') && 0=== strpos($url,$rule[0])) {
                    $domain = $key.strstr($domain,'.'); // 生成对应子域名
                    $url    =  substr_replace($url,'',0,strlen($rule[0]));
                    break;
                }
            }
        }
    }

    // 解析参数
    if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
        parse_str($vars,$vars);
    }elseif(!is_array($vars)){
        $vars = array();
    }
    if(isset($info['query'])) { // 解析地址里面参数 合并到vars
        parse_str($info['query'],$params);
        $vars = array_merge($params,$vars);
    }
    
    // URL组装
    $depr = C('URL_PATHINFO_DEPR');
    if($url) {
        if(0=== strpos($url,'/')) {// 定义路由
            $route      =   true;
            $url        =   substr($url,1);
            if('/' != $depr) {
                $url    =   str_replace('/',$depr,$url);
            }
        }else{
            if('/' != $depr) { // 安全替换
                $url    =   str_replace('/',$depr,$url);
            }
            // 解析分组、模块和操作
            $url        =   trim($url,$depr);
            $path       =   explode($depr,$url);
            $var        =   array();
            $var[C('VAR_ACTION')]       =   !empty($path)?array_pop($path):ACTION_NAME;
            $var[C('VAR_MODULE')]       =   !empty($path)?array_pop($path):MODULE_NAME;
            if($maps = C('URL_ACTION_MAP')) {
                if(isset($maps[strtolower($var[C('VAR_MODULE')])])) {
                    $maps    =   $maps[strtolower($var[C('VAR_MODULE')])];
                    if($action = array_search(strtolower($var[C('VAR_ACTION')]),$maps)){
                        $var[C('VAR_ACTION')] = $action;
                    }
                }
            }
            if($maps = C('URL_MODULE_MAP')) {
                if($module = array_search(strtolower($var[C('VAR_MODULE')]),$maps)){
                    $var[C('VAR_MODULE')] = $module;
                }
            }            
            if(C('URL_CASE_INSENSITIVE')) {
                $var[C('VAR_MODULE')]   =   parse_name($var[C('VAR_MODULE')]);
            }
            if(!C('APP_SUB_DOMAIN_DEPLOY') && C('APP_GROUP_LIST')) {
                if(!empty($path)) {
                    $group                  =   array_pop($path);
                    $var[C('VAR_GROUP')]    =   $group;
                }else{
                    if(GROUP_NAME != C('DEFAULT_GROUP')) {
                        $var[C('VAR_GROUP')]=   GROUP_NAME;
                    }
                }
                if(C('URL_CASE_INSENSITIVE') && isset($var[C('VAR_GROUP')])) {
                    $var[C('VAR_GROUP')]    =  strtolower($var[C('VAR_GROUP')]);
                }
            }
        }
    }

    if(C('URL_MODEL') == 0) { // 普通模式URL转换
        $url        =   __APP__.'?'.http_build_query(array_reverse($var));
        if(!empty($vars)) {
            $vars   =   urldecode(http_build_query($vars));
            $url   .=   '&'.$vars;
        }
    }else{ // PATHINFO模式或者兼容URL模式
        if(isset($route)) {
            $url    =   __APP__.'/'.rtrim($url,$depr);
        }else{
            $url    =   __APP__.'/'.implode($depr,array_reverse($var));
        }
        if(!empty($vars)) { // 添加参数
            foreach ($vars as $var => $val){
                if('' !== trim($val))   $url .= $depr . $var . $depr . urlencode($val);
            }                
        }
        if($suffix) {
            $suffix   =  $suffix===true?C('URL_HTML_SUFFIX'):$suffix;
            if($pos = strpos($suffix, '|')){
                $suffix = substr($suffix, 0, $pos);
            }
            if($suffix && '/' != substr($url,-1)){
                $url  .=  '.'.ltrim($suffix,'.');
            }
        }
    }
    if(isset($anchor)){
        $url  .= '#'.$anchor;
    }
    if($domain) {
        $url   =  (is_ssl()?'https://':'http://').$domain.$url;
    }
    if($redirect) // 直接跳转URL
        redirect($url);
    else
        return $url;
}

/**
 * 渲染输出Widget
 * @param string $name Widget名称
 * @param array $data 传人的参数
 * @param boolean $return 是否返回内容 
 * @param string $path Widget所在路径
 * @return void
 */
function W($name, $data=array(), $return=false,$path='') {
    $class      =   $name . 'Widget';
    $path       =   empty($path) ? BASE_LIB_PATH : $path;
    require_cache($path . 'Widget/' . $class . '/' .$class . '.class.php');
    if (!class_exists($class))
        throw_exception(L('_CLASS_NOT_EXIST_') . ':' . $class);
    $widget     =   Think::instance($class);
    $content    =   $widget->render($data);
    if ($return)
        return $content;
    else
        echo $content;
}

/**
 * 过滤器方法 引用传值
 * @param string $name 过滤器名称
 * @param string $content 要过滤的内容
 * @return void
 */
function filter($name, &$content) {
    $class      =   $name . 'Filter';
    require_cache(BASE_LIB_PATH . 'Filter/' . $class . '.class.php');
    $filter     =   new $class();
    $content    =   $filter->run($content);
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl() {
    if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
        return true;
    }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
        return true;
    }
    return false;
}

/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time=0, $msg='') {
    //多行URL地址支持
    $url        = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}

/**
 * 缓存管理
 * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
 * @param mixed $value 缓存值
 * @param mixed $options 缓存参数
 * @return mixed
 */
function S($name,$value='',$options=null) {
    static $cache   =   '';
    if(is_array($options)){
        // 缓存操作的同时初始化
        $type       =   isset($options['type'])?$options['type']:'';
        $cache      =   Cache::getInstance($type,$options);
    }elseif(is_array($name)) { // 缓存初始化
        $type       =   isset($name['type'])?$name['type']:'';
        $cache      =   Cache::getInstance($type,$name);
        return $cache;
    }elseif(empty($cache)) { // 自动初始化
        $cache      =   Cache::getInstance();
    }
    if(''=== $value){ // 获取缓存
        return $cache->get($name);
    }elseif(is_null($value)) { // 删除缓存
        return $cache->rm($name);
    }else { // 缓存数据
        if(is_array($options)) {
            $expire     =   isset($options['expire'])?$options['expire']:NULL;
        }else{
            $expire     =   is_numeric($options)?$options:NULL;
        }
        return $cache->set($name, $value, $expire);
    }
}
// S方法的别名 已经废除 不再建议使用
function cache($name,$value='',$options=null){
    return S($name,$value,$options);
}

/**
 * 快速文件数据读取和保存 针对简单类型数据 字符串、数组
 * @param string $name 缓存名称
 * @param mixed $value 缓存值
 * @param string $path 缓存路径
 * @return mixed
 */
function F($name, $value='', $path=DATA_PATH) {
    static $_cache  = array();
    $filename       = $path . $name . '.php';
    if ('' !== $value) {
        if (is_null($value)) {
            // 删除缓存
            return false !== strpos($name,'*')?array_map("unlink", glob($filename)):unlink($filename);
        } else {
            // 缓存数据
            $dir            =   dirname($filename);
            // 目录不存在则创建
            if (!is_dir($dir))
                mkdir($dir,0755,true);
            $_cache[$name]  =   $value;
            return file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($value, true) . ";?>"));
        }
    }
    if (isset($_cache[$name]))
        return $_cache[$name];
    // 获取缓存数据
    if (is_file($filename)) {
        $value          =   include $filename;
        $_cache[$name]  =   $value;
    } else {
        $value          =   false;
    }
    return $value;
}

/**
 * 取得对象实例 支持调用类的静态方法
 * @param string $name 类名
 * @param string $method 方法名，如果为空则返回实例化对象
 * @param array $args 调用参数
 * @return object
 */
function get_instance_of($name, $method='', $args=array()) {
    static $_instance = array();
    $identify = empty($args) ? $name . $method : $name . $method . to_guid_string($args);
    if (!isset($_instance[$identify])) {
        if (class_exists($name)) {
            $o = new $name();
            if (method_exists($o, $method)) {
                if (!empty($args)) {
                    $_instance[$identify] = call_user_func_array(array(&$o, $method), $args);
                } else {
                    $_instance[$identify] = $o->$method();
                }
            }
            else
                $_instance[$identify] = $o;
        }
        else
            halt(L('_CLASS_NOT_EXIST_') . ':' . $name);
    }
    return $_instance[$identify];
}

/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
    if (is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root='think', $item='item', $attr='', $id='id', $encoding='utf-8') {
    if(is_array($attr)){
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr   = trim($attr);
    $attr   = empty($attr) ? '' : " {$attr}";
    $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml   .= "<{$root}{$attr}>";
    $xml   .= data_to_xml($data, $item, $id);
    $xml   .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item='item', $id='id') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if(is_numeric($key)){
            $id && $attr = " {$id}=\"{$key}\"";
            $key  = $item;
        }
        $xml    .=  "<{$key}{$attr}>";
        $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml    .=  "</{$key}>";
    }
    return $xml;
}

/**
 * session管理函数
 * @param string|array $name session名称 如果为数组则表示进行session设置
 * @param mixed $value session值
 * @return mixed
 */
function session($name,$value='') {
    $prefix   =  C('SESSION_PREFIX');
    if(is_array($name)) { // session初始化 在session_start 之前调用
        if(isset($name['prefix'])) C('SESSION_PREFIX',$name['prefix']);
        if(C('VAR_SESSION_ID') && isset($_REQUEST[C('VAR_SESSION_ID')])){
            session_id($_REQUEST[C('VAR_SESSION_ID')]);
        }elseif(isset($name['id'])) {
            session_id($name['id']);
        }
        ini_set('session.auto_start', 0);
        if(isset($name['name']))            session_name($name['name']);
        if(isset($name['path']))            session_save_path($name['path']);
        if(isset($name['domain']))          ini_set('session.cookie_domain', $name['domain']);
        if(isset($name['expire']))          ini_set('session.gc_maxlifetime', $name['expire']);
        if(isset($name['use_trans_sid']))   ini_set('session.use_trans_sid', $name['use_trans_sid']?1:0);
        if(isset($name['use_cookies']))     ini_set('session.use_cookies', $name['use_cookies']?1:0);
        if(isset($name['cache_limiter']))   session_cache_limiter($name['cache_limiter']);
        if(isset($name['cache_expire']))    session_cache_expire($name['cache_expire']);
        if(isset($name['type']))            C('SESSION_TYPE',$name['type']);
        if(C('SESSION_TYPE')) { // 读取session驱动
            $class      = 'Session'. ucwords(strtolower(C('SESSION_TYPE')));
            // 检查驱动类
            if(require_cache(EXTEND_PATH.'Driver/Session/'.$class.'.class.php')) {
                $hander = new $class();
                $hander->execute();
            }else {
                // 类没有定义
                throw_exception(L('_CLASS_NOT_EXIST_').': ' . $class);
            }
        }
        // 启动session
        if(C('SESSION_AUTO_START'))  session_start();
    }elseif('' === $value){ 
        if(0===strpos($name,'[')) { // session 操作
            if('[pause]'==$name){ // 暂停session
                session_write_close();
            }elseif('[start]'==$name){ // 启动session
                session_start();
            }elseif('[destroy]'==$name){ // 销毁session
                $_SESSION =  array();
                session_unset();
                session_destroy();
            }elseif('[regenerate]'==$name){ // 重新生成id
                session_regenerate_id();
            }
        }elseif(0===strpos($name,'?')){ // 检查session
            $name   =  substr($name,1);
			if(strpos($name,':')){
				list($k,$v) = explode(':',$name);
				if($prefix) {
					return isset($_SESSION[$prefix][$k][$v]);
				}else{
					return isset($_SESSION[$k][$v]);
				}
			}else{
				if($prefix) {
					return isset($_SESSION[$prefix][$name]);
				}else{
					return isset($_SESSION[$name]);
				}
			}
        }elseif(is_null($name)){ // 清空session
            if($prefix) {
                unset($_SESSION[$prefix]);
            }else{
                $_SESSION = array();
            }
        }elseif(strpos($name,':')){//获取二维session
			list($k,$v) = explode(':',$name);
			if($prefix) {
                return isset($_SESSION[$prefix][$k][$v])?$_SESSION[$prefix][$k][$v]:null;
            }else{
                return isset($_SESSION[$k][$v])?$_SESSION[$k][$v]:null;
            }
		}elseif($prefix){ // 获取session
            return isset($_SESSION[$prefix][$name])?$_SESSION[$prefix][$name]:null;
        }else{
            return isset($_SESSION[$name])?$_SESSION[$name]:null;
        }
    }elseif(is_null($value)){ // 删除session
        if($prefix){
            unset($_SESSION[$prefix][$name]);
        }else{
            unset($_SESSION[$name]);
        }
    }else{ // 设置session
		if(strpos($name,':')){
			list($k,$v) = explode(':',$name);
			if($prefix){
				if (!is_array($_SESSION[$prefix])) {
					$_SESSION[$prefix] = array();
				}
				$_SESSION[$prefix][$k][$v]   =  $value;
			}else{
				$_SESSION[$k][$v]  =  $value;
			}
		}else{
			if($prefix){
				if (!is_array($_SESSION[$prefix])) {
					$_SESSION[$prefix] = array();
				}
				$_SESSION[$prefix][$name]   =  $value;
			}else{
				$_SESSION[$name]  =  $value;
			}
		}
    }
}

/**
 * Cookie 设置、获取、删除
 * @param string $name cookie名称
 * @param mixed $value cookie值
 * @param mixed $options cookie参数
 * @return mixed
 */
function cookie($name, $value='', $option=null) {
    // 默认设置
    $config = array(
        'prefix'    =>  C('COOKIE_PREFIX'), // cookie 名称前缀
        'expire'    =>  C('COOKIE_EXPIRE'), // cookie 保存时间
        'path'      =>  C('COOKIE_PATH'), // cookie 保存路径
        'domain'    =>  C('COOKIE_DOMAIN'), // cookie 有效域名
    );
    // 参数设置(会覆盖黙认设置)
    if (!is_null($option)) {
        if (is_numeric($option))
            $option = array('expire' => $option);
        elseif (is_string($option))
            parse_str($option, $option);
        $config     = array_merge($config, array_change_key_case($option));
    }
    // 清除指定前缀的所有cookie
    if (is_null($name)) {
        if (empty($_COOKIE))
            return;
        // 要删除的cookie前缀，不指定则删除config设置的指定前缀
        $prefix = empty($value) ? $config['prefix'] : $value;
        if (!empty($prefix)) {// 如果前缀为空字符串将不作处理直接返回
            foreach ($_COOKIE as $key => $val) {
                if (0 === stripos($key, $prefix)) {
                    setcookie($key, '', time() - 3600, $config['path'], $config['domain']);
                    unset($_COOKIE[$key]);
                }
            }
        }
        return;
    }
    $name = $config['prefix'] . $name;
    if ('' === $value) {
        if(isset($_COOKIE[$name])){
            $value =    $_COOKIE[$name];
            if(0===strpos($value,'think:')){
                $value  =   substr($value,6);
                return array_map('urldecode',json_decode(MAGIC_QUOTES_GPC?stripslashes($value):$value,true));
            }else{
                return $value;
            }
        }else{
            return null;
        }
    } else {
        if (is_null($value)) {
            setcookie($name, '', time() - 3600, $config['path'], $config['domain']);
            unset($_COOKIE[$name]); // 删除指定cookie
        } else {
            // 设置cookie
            if(is_array($value)){
                $value  = 'think:'.json_encode(array_map('urlencode',$value));
            }
            $expire = !empty($config['expire']) ? time() + intval($config['expire']) : 0;
            setcookie($name, $value, $expire, $config['path'], $config['domain']);
            $_COOKIE[$name] = $value;
        }
    }
}

/**
 * 加载动态扩展文件
 * @return void
 */
function load_ext_file() {
    // 加载自定义外部文件
    if(C('LOAD_EXT_FILE')) {
        $files      =  explode(',',C('LOAD_EXT_FILE'));
        foreach ($files as $file){
            $file   = COMMON_PATH.$file.'.php';
            if(is_file($file)) include $file;
        }
    }
    // 加载自定义的动态配置文件
    if(C('LOAD_EXT_CONFIG')) {
        $configs    =  C('LOAD_EXT_CONFIG');
        if(is_string($configs)) $configs =  explode(',',$configs);
        foreach ($configs as $key=>$config){
            $file   = CONF_PATH.$config.'.php';
            if(is_file($file)) {
                is_numeric($key)?C(include $file):C($key,include $file);
            }
        }
    }
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
	$type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 获取客户端IP地址(http://ip.taobao.com/service/getIpInfo.php?ip=[ip地址字串] 具体位置)
1. 请求接口（GET）：
http://ip.taobao.com/service/getIpInfo.php?ip=[ip地址字串]

2. 响应信息：

（json格式的）国家 、省（自治区或直辖市）、市（县）、运营商

3. 返回数据格式：

{"code":0,"data":{"ip":"210.75.225.254","country":"\u4e2d\u56fd","area":"\u534e\u5317",
"region":"\u5317\u4eac\u5e02","city":"\u5317\u4eac\u5e02","county":"","isp":"\u7535\u4fe1",
"country_id":"86","area_id":"100000","region_id":"110000","city_id":"110000",
"county_id":"-1","isp_id":"100017"}}
其中code的值的含义为，0：成功，1：失败。
 * array(2) {
  ["code"] => int(0)
  ["data"] => array(13) {
    ["country"] => string(6) "中国"
    ["country_id"] => string(2) "CN"
    ["area"] => string(6) "华东"
    ["area_id"] => string(6) "300000"
    ["region"] => string(9) "江苏省"
    ["region_id"] => string(6) "320000"
    ["city"] => string(9) "苏州市"
    ["city_id"] => string(6) "320500"
    ["county"] => string(0) ""
    ["county_id"] => string(2) "-1"
    ["isp"] => string(6) "电信"
    ["isp_id"] => string(6) "100017"
    ["ip"] => string(13) "61.155.149.77"
  }
}

 */
function get_client_ip_str($ip) {
	$url = 'http://ip.taobao.com/service/getIpInfo.php?ip=';
	$str = curl_get($url.$ip);
    $msg = json_decode($str,true);
    if($msg['code']==0){
        return $msg['data']['country']
        .($msg['data']['region']?'-'.$msg['data']['region']:'')
        .($msg['data']['city']?'-'.$msg['data']['city']:'')
        .($msg['data']['isp']?'('.$msg['data']['isp'].')':'');
    }else{
        return 'IP来源获取失败';
    }
}

/*
 * 获取手机归属地
 */
function get_phone_str($phone) {
    if(empty($phone)) return '';
	$url = 'http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi?chgmobile=';
	$str = curl_get($url.$phone);
    $msg = xml_to_array($str);
    if($msg['retmsg']=='OK'){
        return t($msg['province']).'-'.t($msg['city']).'-'.t($msg['supplier']);
    }else{
        return '获取归属地失败';
    }
}

/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code) {
    static $_status = array(
        // Success 2xx
        200 => 'OK',
        // Redirection 3xx
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',  // 1.1
        // Client Error 4xx
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        // Server Error 5xx
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    );
    if(isset($_status[$code])) {
        header('HTTP/1.1 '.$code.' '.$_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:'.$code.' '.$_status[$code]);
    }
}

// 过滤表单中的表达式
function filter_exp(&$value){
    if (in_array(strtolower($value),array('exp','or'))){
        $value .= ' ';
    }
}

/**
 *
 * 生成唯一单号
 */
function get_uuid($uid){ 
    //return date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).rand(1000,9999);
    $str = substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 9).rand(100,999);
	if($uid) return date('ymd').$uid.substr($str,strlen($uid));
	return date('ymd').$str;
}

/*
 * 利息计算  本金  利率  天数
 * $flag 是否进行四舍五入
 */
 function getInterest($capital,$rate,$days,$flag=true){
     $interest = ($days/365)*($rate/100)*$capital;
     return $flag ? round($interest,2) : sprintf("%.2f",substr(sprintf("%.3f", $interest), 0, -2));
}

/*
 * 两日期相差的天数
 */
 function getBetweenDay($date1_stamp,$date2_stamp){
     return abs(strtotime(date('Y-m-d',$date1_stamp))-strtotime(date('Y-m-d',$date2_stamp)))/86400;
    //return ceil( abs($date1_stamp-$date2_stamp) / 86400);
}

/*
 * 两日期相差的月数
 * */
 function getBetweenMon($date1_stamp,$date2_stamp){
    list($date_1['y'],$date_1['m'])=explode("-",date('Y-m',$date1_stamp));
    list($date_2['y'],$date_2['m'])=explode("-",date('Y-m',$date2_stamp));
    if($date_1['y']>$date_2['y']){
        return abs($date_1['y']-$date_2['y'])*12 +$date_1['m']-$date_2['m'];
    }else{
        return abs($date_1['y']-$date_2['y'])*12 +$date_2['m']-$date_1['m'];
    }
 }

/**
 *
 * 中文千分位1,234.50
 */
function qian($number,$format=2){
    return number_format($number,$format,'.',',');
}

/*
 * 人民币大写
 */
 function cny($ns) { 
    static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"), 
        $cnyunits=array("圆","角","分"), 
        $grees=array("拾","佰","仟","万","拾","佰","仟","亿"); 
    list($ns1,$ns2)=explode(".",$ns,2); 
    $ns2=array_filter(array($ns2[1],$ns2[0])); 
    $ret=array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),"")); 
    $ret=implode("",array_reverse(_cny_map_unit($ret,$cnyunits))); 
    return str_replace(array_keys($cnums),$cnums,$ret); 
} 
function _cny_map_unit($list,$units) { 
    $ul=count($units); 
    $xs=array(); 
    foreach (array_reverse($list) as $x) { 
        $l=count($xs); 
        if ($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l-1)%$ul]); 
        else $n=is_numeric($xs[0][0])?$x:''; 
        array_unshift($xs,$n); 
    } 
    return $xs; 
} 

/**
 * 微博短链接
 */
function shorturl($long_url) {
    $apiKey='2799170889';//要修改这里的key再测试哦
    $apiUrl='http://api.t.sina.com.cn/short_url/shorten.json?source='.$apiKey.'&url_long='.$long_url;
    $response = curl_get($apiUrl);
    $json = json_decode($response);
    return $json[0]->url_short;
}
/**
 * DZ在线中文分词
 * @param $title string 进行分词的标题
 * @param $content string 进行分词的内容
 * @param $encode string API返回的数据编码
 * @return  array 得到的关键词数组
 */
function dz_segment($title = '', $content = '', $encode = 'utf-8'){
	if($title == ''){
		return false;
	}
	$title = rawurlencode(strip_tags($title));
	$content = strip_tags($content);
	if(strlen($content)>2400){ //在线分词服务有长度限制
		$content =  mb_substr($content, 0, 800, $encode);
	}
	$content = rawurlencode($content);
	$url = 'http://keyword.discuz.com/related_kw.html?title='.$title.'&content='.$content.'&ics='.$encode.'&ocs='.$encode;
	$xml_array=simplexml_load_file($url);                        //将XML中的数据,读取到数组对象中  
	$result = $xml_array->keyword->result;
	$data = array();
	foreach ($result->item as $key => $value) {
			array_push($data, (string)$value->kw);
	}
	if(count($data) > 0){
		return $data;
	}else{
		return false;
	}
}

/*
 * 发送自定义信息到手机
 */
function sendToModile($msg,$mobile){
    $sms_uname = 'coolman';
    $sms_key = 'd13b16d1e85193d87f72';
    
    $content = rawurlencode($msg);
    $target = 'http://utf8.sms.webchinese.cn/?Uid='.$sms_uname.'&Key='.$sms_key.'&smsMob='.$mobile.'&smsText='.$msg;
    return curl_get($target);//返回1成功
}

/*
 * 随机字符串
 */
 function get_rand($len){
     $ary = array('2','3','4','5','6','7','8','9','A','B','E','F','H','K','M','N','P','R','T','W','X','Y');
     $str = '';
     for($i=0;$i<$len;$i++){
         $str.=$ary[rand(0,count($ary)-1)];
     }
     return $str;
 }
 
  function getCrypt(){
        $ary0 = array('MJ','OF','WS','IE','PU','RS','YQ','XF','FN','WE','AZ','ZB','KL','QY','IK','GR','SU','UN','SK','VV','DZ','UH','LZ','JO','EB','OT','GN','BS','ZZ','LE','MX','NJ','YJ','KG','WU','YI','EI','VG','SW','ZQ','FT','QQ','UV','TK','BY','FX','JF','YA','FF','LK');
        $ary1 = array('HV','TZ','UT','UQ','TN','SB','GP','CA','PR','DV','QD','WX','BB','OV','NI','XW','ZK','EN','XJ','QN','PW','QC','IT','AR','RH','DH','UF','KP','OX','HH','WI','SC','ZV','HX','OK','KV','FR','SD','KA','HB','BJ','VP','MT','WJ','VZ','FV','GU','ZG','FB','AD');
        $ary2 = array('TH','BG','PJ','ZW','ON','AO','SN','DA','RM','GT','WZ','GB','QK','QL','UX','LP','MO','QR','UO','JY','SA','DU','QB','DG','KF','NW','RT','MR','CX','SR','PM','NY','TT','CC','TO','XN','YS','QP','WC','UI','RI','RE','GC','WQ','NK','EO','SQ','GY','NU','MK');
        $ary3 = array('MS','PV','EY','OQ','JP','CP','MQ','UL','QG','JK','BZ','BN','CI','PE','WY','UD','AP','LN','KJ','XK','EZ','BM','VK','US','VN','BC','OP','IJ','UU','QF','RG','ZC','IY','LG','SL','QZ','FK','RV','RK','RP','TC','XA','DS','AF','TY','IG','BE','CM','KY','QW');
        $ary4 = array('BV','UM','OZ','YH','CT','MY','MU','XC','SM','DO','ZR','RA','ZL','CF','HZ','ZS','JD','IU','ED','WD','AT','FU','AX','BD','II','PG','MZ','SV','KN','AI','YX','LT','UG','HQ','CH','LO','LH','VJ','HN','JN','ZD','NL','HT','JT','JB','WT','IC','CN','FP','QS');
        $ary5 = array('HP','SZ','LM','IH','JW','WR','RR','WM','JZ','RF','XL','OM','OU','XS','QM','KS','TI','OC','ZN','UW','FS','LI','JI','OO','SH','QE','YP','FZ','NE','VC','DM','OH','MH','DF','AH','DY','WV','GV','DK','SX','ID','HO','MF','TJ','IX','YG','QV','ET','NP','IL');
        $ary6 = array('PQ','IB','GM','EL','YZ','FD','GA','OE','PT','EF','XT','FW','EJ','DD','WN','FH','TX','MA','LR','GD','TV','ES','MN','NQ','FE','NA','FJ','QX','UP','ND','LY','TF','JS','OJ','KE','NS','OL','NV','ZO','CE','JG','VL','IS','EC','JR','IO','HE','YT','NC','NM');
        $ary7 = array('EQ','YO','KC','GH','TL','JH','BI','ZJ','FC','YL','AU','OR','LA','UY','GS','KU','TP','ML','IV','KD','VW','HS','HL','BW','YW','VA','RD','DL','XD','GZ','MG','DB','BX','EG','GF','AK','CQ','XZ','BR','AJ','KZ','XV','JC','EU','QA','KQ','GJ','WB','DP','BA');
        $ary8 = array('GW','ZT','TR','CW','JX','YY','QO','BH','CL','PB','RY','EA','PY','LC','AE','CJ','GL','JQ','QI','ZP','EX','TU','RO','LJ','XX','LF','YV','RJ','KO','YE','SF','HG','HY','DX','VT','NN','NH','KI','MW','YC','RB','SS','MD','VY','UJ','IN','DR','RZ','IZ','HA');
        $ary9 = array('FA','TW','KT','AV','AG','QT','NX','IW','IQ','OA','RC','RL','JA','CB','LW','YU','ZI','PZ','VH','NB','KK','LD','IM','JM','MV','ZM','BL','WA','JE','VO','OG','XB','ZA','BU','UZ','YN','PX','LX','FQ','VD','WK','AA','AL','ZF','DE','PD','SI','HI','GG','EE');
        return array($ary0,$ary1,$ary2,$ary3,$ary4,$ary5,$ary6,$ary7,$ary8,$ary9);
    }

     function encrypt($str){
        $ary = getCrypt();        
        $str = str_split($str);
        $ret = '';
        foreach($str as $k => $v){
            $code = $ary[$v][rand(0,49)];
            foreach(str_split($code) as $k1 => $v1){
                $ret.= rand(0,1)?strtolower($v1):$v1;
            }
        }
        return $ret;
    }
    
     function decrypt($str){
        $str_len = strlen($str);
        if(($str_len%2) != 0) return 0;
        $ary = getCrypt();        
        $str = str_split(strtoupper($str),2);
        $ret = '';
        foreach($str as $k => $v){
            foreach($ary as $k1 => $v1){
                if(in_array($v, $v1)){
                    $ret.=$k1;break;
                }
            }
        }
        //判断密文格式
        if(strlen($ret) != ($str_len/2)) return 0;
        return $ret;
    }

/**
 *
 * xml转json
 */
function xml_to_json($source) {
	if(is_file($source)){ //传的是文件，还是xml的string的判断
		$xml_array=simplexml_load_file($source);
	}else{
		$xml_array=simplexml_load_string($source);
	}
	$json = json_encode($xml_array); //php5，以及以上，如果是更早版本，请查看JSON.php
	return $json;
} 

/**
 *
 * xml转array
 */
function xml_to_array($source) {
	return json_decode(xml_to_json($source),true);
} 

/**
 *
 * json转xml
 */
 function json_to_xml($source,$charset='utf8') {
	if(empty($source)){
		return false;
	}
	//php5，以及以上，如果是更早版本，请查看JSON.php
	$array = json_decode($source,true);
	$xml ='';
	$xml .= array_to_xml($array);
	return $xml;
}

function array_to_xml($source) {
	$string='';
	foreach($source as $k=>$v){
		$string .='<'.$k.'>';
		//判断是否是数组，或者，对像
		if(is_array($v) || is_object($v)){
			//是数组或者对像就的递归调用
			$string .= array_to_xml($v);
		}else{
			//取得标签数据
			$string .=$v;
		}
		$string .='</'.$k.'>';
	}
	return $string;
}

/*按键值对数组进行排序*/
function array_sort($arr, $keys, $type = 'asc') {

    $keysvalue = $new_array = array();

    foreach ($arr as $k => $v) {

        $keysvalue[$k] = $v[$keys];
    }

    if ($type == 'asc') {

        asort($keysvalue);
    } else {

        arsort($keysvalue);
    }

    reset($keysvalue);

    foreach ($keysvalue as $k => $v) {

        $new_array[] = $arr[$k];
    }

    return $new_array;
}

/*修复array_merge不能含有非数组参数（null，false等）*/
function array_merge_adv(){
    $ary = func_get_args();
    $ret = null;
    foreach($ary as $k => $v){
        if(!is_array($v)) continue ;
        is_array($ret) ? $ret = array_merge($ret,$v) : $ret = $v;
    }
    return $ret;
}

//curl:get方法
function curl_get($durl){  
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, $durl);  
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	$r = curl_exec($ch);  
	curl_close($ch);  
	return $r;  
}

function curl_get_https($durl){  
	$ch = curl_init();  

	$cookie = 
	'FAVOR=0437|||||5840|0437|||0|0304||~3954CA16C3924F10C3A1C97C1931F6E66A625A65||||;CCBIBS1=JlQlQxUWWGfiPpJeQJjjZZmqCKyiMptagH8irZuqtHejIZOX6NCqwdP3aE6xapKafKZiSZa6oNoiip36YKNi6ZamWOgirZpWkV6HDSEmpf;_BOA_mf_txcode_=411105;JSESSIONID=JKjcJG0VzhpyhkjmGK2T9VLyjDtXxVNqs2mRWvFgfDDJPDhsxJng!67234858;null=471925258.2336.0000;';
	curl_setopt($ch, CURLOPT_COOKIE, $cookie); 
	curl_setopt($ch, CURLOPT_URL, $durl);  
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //浏览器代理
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

	//抓取的页面跳转
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  

	//https
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	$r = curl_exec($ch);  
	curl_close($ch);  
	return $r;  
}

//curl:get方法
function curl_get_by_cookie($durl,$cook){  
	$ch = curl_init();  
	//$cookie = "user_id=jZv0nY%2BUR%7C79e7c294d0396e6742ab3079bb18100f;lasttime=1404459247;PHPSESSID=1mkbm9dmi2rkv6scf8b0mf1pe5;bdshare_firstime=1404317326413;think_language=zh-cn";
	$cookie = $cook;
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);  //关键一句话
	curl_setopt($ch, CURLOPT_URL, $durl);  
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	$r = curl_exec($ch);  
	curl_close($ch); 

	return $r;  
}

//curl:post方法
function curl_post($durl,$curlPost){  
	$ch = curl_init();//初始化curl
	curl_setopt($ch,CURLOPT_URL,$durl);//抓取指定网页
	curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	$r = curl_exec($ch);//运行curl
	curl_close($ch);
	return $r;
}

function curl_post_https($durl,$curlPost){  
	$ch = curl_init();//初始化curl
	curl_setopt($ch,CURLOPT_URL,$durl);//抓取指定网页
	curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);

	//https
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	$r = curl_exec($ch);//运行curl
	curl_close($ch);
	return $r;
}

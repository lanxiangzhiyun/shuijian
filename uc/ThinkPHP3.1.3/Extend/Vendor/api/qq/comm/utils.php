<?php
/**
 * PHP SDK for QQ登录 OpenAPI
 *
 * @version 1.5
 * @author connect@qq.com
 * @copyright © 2011, Tencent Corporation. All rights reserved.
 */

/**
 * @brief 本文件包含了OAuth认证过程中会用到的公用方法 
 */

require_once("config.php");

/**
 * @brief QQ登录中对url做编解码的统一函数
 * 按照RFC 1738 对URL进行编码
 * 除了-_.~之外的所有非字母数字字符都将被替换成百分号(%)后跟两位十六进制数
 */
$QQhexchars = "0123456789ABCDEF";

/**
 *@brief 增加一个全局变量
 */
$global_arg;

function QQConnect_urlencode($str)
{
    global $QQhexchars;
    $urlencode = "";
    $len = strlen($str);

    for($x = 0 ; $len--; $x++)
    {
        if (($str[$x] < '0' && $str[$x] != '-' && $str[$x] != '.') ||
            ($str[$x] < 'A' && $str[$x] > '9') ||
            ($str[$x] > 'Z' && $str[$x] < 'a' && $str[$x] != '_') ||
            ($str[$x] > 'z' && $str[$x] != '~')) 
        {
            $urlencode .= '%';
            $urlencode .= $QQhexchars[(ord($str[$x]) >> 4)];
            $urlencode .= $QQhexchars[(ord($str[$x]) & 15)];
        }
        else
        {
            $urlencode .= $str[$x];
        }
    }

    return $urlencode;
}

function QQConnect_urldecode($str)
{
    global $QQhexchars;
    $urldecode = "";
    $len = strlen($str);

    for ($x = 0; $x < $len; $x++)
    {
        if ($str[$x] == '%' && ($len - $x) > 2
            && (strpos($QQhexchars, $str[$x+1]) !== false) && (strpos($QQhexchars, $str[$x+2]) !== false))
        {
            $tmp = $str[$x+1].$str[$x+2];
            $urldecode .= chr(hexdec($tmp));
            $x += 2;
        }
        else
        {
            $urldecode .= $str[$x];
        } 
    }

    return $urldecode;
}

/**
 * @brief 对参数进行字典升序排序
 *
 * @param $params 参数列表
 *
 * @return 排序后用&链接的key-value对（key1=value1&key2=value2...)
 */
function get_normalized_string($params)
{
    ksort($params);
    $normalized = array();
    foreach($params as $key => $val)
    {
        $normalized[] = $key."=".$val;
    }

    return implode("&", $normalized);
}

/**
 * @brief 使用HMAC-SHA1算法生成oauth_signature签名值 
 *
 * @param $key  密钥
 * @param $str  源串
 *
 * @return 签名值
 */

function get_signature($str, $key)
{
    $signature = "";
    if (function_exists('hash_hmac'))
    {
        $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
    }
    else
    {
        $blocksize	= 64;
        $hashfunc	= 'sha1';
        if (strlen($key) > $blocksize)
        {
            $key = pack('H*', $hashfunc($key));
        }
        $key	= str_pad($key,$blocksize,chr(0x00));
        $ipad	= str_repeat(chr(0x36),$blocksize);
        $opad	= str_repeat(chr(0x5c),$blocksize);
        $hmac 	= pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad).$str
                    )
                )
            )
        );
        $signature = base64_encode($hmac);
    }

    return $signature;
} 

/**
 * @brief 对字符串进行URL编码，遵循rfc1738 urlencode
 *
 * @param $params
 *
 * @return URL编码后的字符串
 */
function get_urlencode_string($params)
{
    ksort($params);
    $normalized = array();
    foreach($params as $key => $val)
    {
		/*if(is_int($val)){
			$normalized[] = $key."=".$val;
		}else{*/
			$normalized[] = $key."=".QQConnect_urlencode($val);
		//}
        
    }

    return implode("&", $normalized);
}

/**
 * @brief 检查openid是否合法
 *
 * @param $openid  与用户QQ号码一一对应
 * @param $timestamp　时间戳
 * @param $sig　　签名值
 *
 * @return true or false
 */
function is_valid_openid($openid, $timestamp, $sig)
{
    global $global_arg;
    $key = $_SESSION["appkey"];
    $str = $openid.$timestamp;
    $signature = get_signature($str, $key);
    $global_arg = $signature;
    return $sig == $signature; 
}

/**
 * @brief 所有Get请求都可以使用这个方法
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 * @return true or false
 */
function do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
    $sigstr = "GET"."&".QQConnect_urlencode("$url")."&";

    //必要参数, 不要随便更改!!
    $params = $_GET;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);

    //参数按照字母升序做序列化
    $normalized_str = get_normalized_string($params);
    $sigstr        .= QQConnect_urlencode($normalized_str);

    //签名,确保php版本支持hash_hmac函数
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $url      .= "?".$normalized_str."&"."oauth_signature=".QQConnect_urlencode($signature);

    //echo "$url\n";
    return file_get_contents($url);
}

/**
 * @brief 所有multi-part post 请求都可以使用这个方法
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_multi_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
    //构造签名串.源串:方法[GET|POST]&uri&参数按照字母升序排列
    $sigstr = "POST"."&"."$url"."&";

    //必要参数,不要随便更改!!
    $params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);


    //获取上传图片信息
    foreach ($_FILES as $filename => $filevalue)
    {
        if ($filevalue["error"] != UPLOAD_ERR_OK)
        {
            //echo "upload file error $filevalue['error']\n";
            //exit;
        } 
        $params[$filename] = file_get_contents($filevalue["tmp_name"]);
    }

    //对参数按照字母升序做序列化
    $sigstr .= get_normalized_string($params);

    //签名,需要确保php版本支持hash_hmac函数
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $params["oauth_signature"] = $signature; 

    //处理上传图片
    foreach ($_FILES as $filename => $filevalue)
    {
        $tmpfile = dirname($filevalue["tmp_name"])."/".$filevalue["name"];
        move_uploaded_file($filevalue["tmp_name"], $tmpfile);
        $params[$filename] = "@$tmpfile";
    }

    /*
    echo "len: ".strlen($sigstr)."\n";
    echo "sig: $sigstr\n";
    echo "key: $appkey&\n";
    */

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);
    //$httpinfo = curl_getinfo($ch);
    //print_r($httpinfo);

    curl_close($ch);
    //删除上传临时文件
    unlink($tmpfile);
    return $ret;

}


/**
 * @brief 所有post 请求都可以使用这个方法
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid, $post_data)
{
    //构造签名串.源串:方法[GET|POST]&uri&参数按照字母升序排列
    $sigstr = "POST"."&".QQConnect_urlencode($url)."&";

    //必要参数,不要随便更改!!
    //$params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = strval(time());
    $params["oauth_nonce"]            = strval(mt_rand());
    $params["oauth_consumer_key"]     = strval($appid);
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);
	
	foreach ($post_data as $param => $value) {
		$params[$param] = $value;
	}
	
    //对参数按照字母升序做序列化
    $sigstr .= QQConnect_urlencode(get_normalized_string($params));

    //签名,需要确保php版本支持hash_hmac函数
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key); 
    $params["oauth_signature"] = $signature;
	 
	var_dump($params);echo("<br />");

    $postdata = get_urlencode_string($params);

    echo "$sigstr******\n";echo("<br />");
    echo "$postdata\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;

}

?>
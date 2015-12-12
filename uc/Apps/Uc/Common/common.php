<?php
//项目函数库
/**
 +------------------------------------------------------------------------------
 * 用户中心专用函数
 +------------------------------------------------------------------------------
 * @category Uc
 * @package  Common
 * @author   Ray <ray@boqii.com>
 * @version
 +------------------------------------------------------------------------------
 */
/**
 * 登录页面检测是否登录
 * @return boolen
 */
function user_login_check() {
	// 取得用户ID和密码
	list($pwd, $uid) = empty($_COOKIE['boqii_auth']) ? array('', 0) : daddslashes(explode("\t", authcode($_COOKIE['boqii_auth'], 'DECODE')), 1);
	if(!$uid) {
		return false;
	}
	$userinfo	= D('Api')->getUserInfo($uid);
	if($pwd == $userinfo['password'])
	{
		//判断COOKIE中是否存在登录时间
		if(!empty($_COOKIE['boqii_logtime']) && $uid) {
			$time_now = time();
			//本次操作时间超过三十分钟以三十分钟计算
			if($time_now - $_COOKIE['boqii_logtime'] >= 1800) {
				$data['uid'] = $uid;
				$data['oltime'] = 30;
				$data['start_time'] = $_COOKIE['boqii_logtime'];
				$data['end_time'] = $_COOKIE['boqii_logtime'] + 1800;
				$data['type'] = 2;
				M("bbs_oltimes")->add($data);

				setcookie('boqii_logtime', $time_now,  0, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);//以当前时间为起始计算在线时间

			}
			//操作时间在一分钟到三十分钟内按实际计数
			elseif($time_now - $_COOKIE['boqii_logtime'] >= 60) {
				$data['uid'] = $uid;
				$data['oltime'] = ceil(($time_now - $_COOKIE['boqii_logtime']) / 60);
				$data['start_time'] = $_COOKIE['boqii_logtime'];
				$data['end_time'] = $time_now;
				$data['type'] = 2;
				M("bbs_oltimes")->add($data);

				setcookie('boqii_logtime', $time_now,  0, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);//以当前时间为起始计算在线时间
			}
		}
		return $userinfo;
	}
	return false;
}

//数据编解码
function authcode($string, $operation, $key = '')
{
	//$key = md5($key ? $key : md5($_SERVER['HTTP_USER_AGENT']));  //360浏览器HTTP_USER_AGENT在支付回调的时候会有问题，改为'www.boqii.com'
	$key = md5($key ? $key : md5('boqiiloginkeywwwboqiicom'));
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}
}

function daddslashes($string, $force = 0)
{
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

/**
 * utf8字符串截取
 *
 * @param $sourcestr string 源字符串（utf8）
 * @param $cutlen int 截取字符长度
 * @param $retainlen int 保留字符长度
 *
 * @return 截取字符串
 */
function substr_utf8($sourcestr, $cutlen, $retainlen) {
	$len = strlength_utf8($sourcestr);
	if($len <= $retainlen) {
		return $sourcestr;
	} else {
		return mysubstr_utf8($sourcestr, $cutlen, "..");
	}
}

/**
 * 计算utf8字符长度
 */
function strlength_utf8($sourcestr) {
	$i=0;
	$n=0;
	$str_length=strlen($sourcestr);//字符串的字节数
	while ($i<=$str_length-1)
	{
		$temp_str=substr($sourcestr,$i,1);
		$ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
		if ($ascnum>=224) {   //如果ASCII位高与224，
			//根据UTF-8编码规范，将3个连续的字符计为单个字符
			$i=$i+3;            //实际Byte计为3
			$n++;            //字串长度计1
		}
		elseif ($ascnum>=192) { //如果ASCII位高与192，
			//根据UTF-8编码规范，将2个连续的字符计为单个字符
			$i=$i+2;            //实际Byte计为2
			$n++;            //字串长度计1
		}
		elseif ($ascnum>=65 && $ascnum<=90) { //如果是大写字母，
			$i=$i+1;            //实际的Byte数仍计1个
			$n++;            //但考虑整体美观，大写字母计成一个高位字符
		}
		else {               //其他情况下，包括小写字母和半角标点符号，
		$i=$i+1;            //实际的Byte数计1个
		$n=$n+1;        //小写字母和半角标点等与半个高位字符宽...
		}
	}
	return $n;
}

//$sourcestr 是要处理的字符串
//$cutlength 为截取的长度(即字数)
function mysubstr_utf8($sourcestr,$cutlength, $suffix = "...")
{
	$returnstr='';
	$i=0;
	$n=0;
	$str_length=strlen($sourcestr);//字符串的字节数
	while (($n<$cutlength) and ($i<=$str_length))
	{
	  $temp_str=substr($sourcestr,$i,1);
	  $ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
	  if ($ascnum>=224)    //如果ASCII位高与224，
	  {
		 $returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
		 $i=$i+3;            //实际Byte计为3
		 $n++;            //字串长度计1
	  }
	  elseif ($ascnum>=192) //如果ASCII位高与192，
	  {
		 $returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
		 $i=$i+2;            //实际Byte计为2
		 $n++;            //字串长度计1
	  }
	  elseif ($ascnum>=65 && $ascnum<=90) //如果是大写字母，
	  {
		 $returnstr=$returnstr.substr($sourcestr,$i,1);
		 $i=$i+1;            //实际的Byte数仍计1个
		 $n++;            //但考虑整体美观，大写字母计成一个高位字符
	  }
	  else                //其他情况下，包括小写字母和半角标点符号，
	  {
		 $returnstr=$returnstr.substr($sourcestr,$i,1);
		 $i=$i+1;            //实际的Byte数计1个
		 $n=$n+1;        //小写字母和半角标点等与半个高位字符宽...
	  }
	}
	if ($str_length>strlen($returnstr))
	{
		$returnstr = $returnstr . $suffix;//超过长度时在尾处加上省略号
	}
	return $returnstr;
}

/**
* 中文算1个，英文算0.5个，全角字符算1个，半角字符算0.5个
*
* @param string $string: 字符串
* @param int $charset: 编码，默认为UTF-8
* @return float: 字符长度
*/
function strlen_weibo($string, $charset='utf-8'){
    $n = $count = 0;
    $length = strlen($string);
    if (strtolower($charset) == 'utf-8'){
        while ($n < $length){
            $currentByte = ord($string[$n]);
            if ($currentByte == 9 || $currentByte == 10 || (32 <= $currentByte && $currentByte <= 126)){
                $n++;
                $count++;
            } elseif (194 <= $currentByte && $currentByte <= 223){
                $n += 2;
                $count += 2;
            } elseif (224 <= $currentByte && $currentByte <= 239){
                $n += 3;
                $count += 2;
            } elseif (240 <= $currentByte && $currentByte <= 247){
                $n += 4;
                $count += 2;
            } elseif (248 <= $currentByte && $currentByte <= 251){
                $n += 5;
                $count += 2;
            } elseif ($currentByte == 252 || $currentByte == 253){
                $n += 6;
                $count += 2;
            } else{
                $n++;
                $count++;
            }
			if ($count >= $length){
                break;
            }
        }
        return ceil($count/2);
    } else {
        for ($i = 0; $i < $length; $i++){
            if (ord($string[$i]) > 127){
                $i++;
                $count++;
            }
            $count++;
        }
        return ceil($count/2);
    }
}

/**
 * 替换过滤词
 *
 * @param $str string 字符串
 *
 * @return string 替换过滤词后的字符串
 */
function replace_fiter_words($str) {
	//过滤词
	$filterWords = C("FILTER_WORDS");
	//替换词
	$replaceWord = C("FILTER_REPLACE_WORD");

	return str_replace($filterWords, $replaceWord, $str);
}


//格式化时间
function format_time($date)
{
	$limit = time() - $date;
	if($limit <= 0) {
		return '1秒钟前';
	}elseif($limit >0 && $limit < 60){
		return $limit . '秒钟前';
	}elseif($limit >= 60 && $limit < 3600){
		return floor($limit/60) . '分钟前';
	}elseif($limit >= 3600 && $limit < 86400){
		return floor($limit/3600) . '小时前';
	}elseif($limit >= 86400){
		return date('Y-m-d', $date);
	}
}

/**
 * 格式化时间
 * 动态用
 */
function format_dynamic_time($date) {
	$limit = time() - $date;
	//当天0时
	$day = strtotime(date("Y-m-d"));
	//当天
	if($date >= $day) {
		if($limit <= 0) {
			return '1秒钟前';
		} elseif($limit >0 && $limit < 60){
			return $limit . '秒钟前';
		} elseif($limit >= 60 && $limit < 3600) {
			return floor($limit/60) . '分钟前';
		} elseif($limit >= 3600 && $limit < 86400) {
			return date('H:i', $date);
		}
	} else {
		return date('Y-m-d H:i', $date);
	}
}

/**
 * 格式化时间
 * 访问用
 */
function format_visit_time($vtime) {
	$limit = strtotime(date("Y-m-d")) - strtotime(date("Y-m-d", $vtime));
	if($limit < 86400) {
		return date('H点i分', $vtime);
	} elseif($limit >= 86400) {
		return date('m月d日', $vtime);
	}
}

/**
 * 格式化时间
 * 评论用
 */
function format_comment_time($ctime) {
	$limit = strtotime(date("Y-m-d")) - strtotime(date("Y-m-d", $ctime));
	if($limit < 86400) {
		return date('今天 H:i', $ctime);
	} elseif($limit >= 86400) {
		return date('Y-m-d H:i', $ctime);
	}
}

/**
 * 链接url转化
 *
 */
function get_rewrite_url($control, $action, $param = "",$page = 1) {
	$url = "http://".$_SERVER['HTTP_HOST'];//$url = C("I_DIR");
	if($param) {
		$params = explode(",", $param);
	}

	switch($control) {
		case 'User':
			if($action == 'login') {
				$url = C('BLOG_DIR') . '/user/login';
			} elseif($action == 'register') {
				$url = C('BLOG_DIR') . '/user/register';
			}
			break;
		case "Index":
			if($action == "index") {
				if($params[0]) {
					if($page == 1) {
						if(isset($params[1]) && $params[1] > 1) {
							$url .= "/u/" . $params[0] . "/t/" . $params[1];
						} else {
							$url .= "/u/".$params[0];
						}
					} else {
						if(isset($params[1]) && $params[1] > 1) {
							$url .= "/u/" . $params[0] . "/t/" . $params[1] . "/p/".$page;
						} else {
							$url .= "/u/".$params[0] . "/p/".$page;
						}
					}
				}
			}
			break;
		case "Forum":
			$forums = array(
				"90"	=> "/forum/jinmao",
				"100"	=> "/forum/sumu",
				"93"	=> "/forum/guibin",
				"105"	=> "/forum/bixiong",
				"97"	=> "/forum/xishi",
				"91"	=> "/forum/hashiqi",
				"92"	=> "/forum/samo",
				"98"	=> "/forum/xuenairui",
				"96"	=> "/forum/hunxueer",
				"99"	=> "/forum/qitaquanyou",
				"102"	=> "/forum/xiaochong",
				"101"	=> "/forum/maoyou",
				"38"	=> "/forum/shanghai",
				"37"	=> "/forum/nanning",
				"39"	=> "/forum/nanjing",
				"58"	=> "/forum/guangzhou",
				"87"	=> "/forum/beijing",
				"86"	=> "/forum/hangzhou",
				"84"	=> "/forum/wuhan",
				"82"	=> "/forum/qitafenhui",
				"106"	=> "/forum/yiliao",
				"41"	=> "/forum/chongyou",
				"47"	=> "/forum/yangchong",
				"103"	=> "/forum/jiaoyi",
				"56"	=> "/forum/shishang",
				"74"	=> "/forum/lvyou",
				"108"	=> "/forum/zengsong",
				"109"	=> "/forum/jiuzhu",
				"68"	=> "/forum/huodong",
				"116"	=> "/forum/chongwuxiu",
				"24"	=> "/forum/xinshou",
				"12"	=> "/forum/zhanwu",
				"53"	=> "/forum/banzhu"
			);
			if($action == "forumDisplay") {
				if($params[0]){
					$url = C("BBS_DIR") . $forums[$params[0]]."/";
				}
			}
			break;
		case "UcWeibo":
			if($action == "weibo") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/weibo/u/".$params[0];
					} else {
						$url .= "/weibo/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "hotWeibo") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/hotweibo/u/".$params[0];
					} else {
						$url .= "/hotweibo/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "weiboComments") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/weibo/w/".$params[0];
					} else {
						$url .= "/weibo/w/".$params[0] . "/p/".$page;
					}
				}
			}
			break;

		case "UcDiary" :
			if($action == "diaryList") {
				$url = '/diary';
				if($params[0]) {
					$t = explode(':',$params[0]);
					if(substr($url,-1)!='/'){
						$url .= '/';
					}
					$url .= $t[0].'/'.$t[1];
					//$url .= "/diary/u/" . $params[0];
					/*if($page == 1) {
						if(isset($params[1])) {
							$url .= "/diary/u/" . $params[0] . "/t_" . $params[1] . "/";
						} else {
							$url .= "/diary/u/" . $params[0];
						}
					} else {
						if(isset($params[1]) && $params[1]) {
							$url .= "/diary/u/" . $params[0] . "/t_" . $params[1] . "/p/" . $page;
						} else {
							$url .= "/diary/u/" . $params[0] . "/p/" . $page;
						}
					}*/
				}
				if(isset($params[1])){
					$t = explode(':',$params[1]);
					if($t[0]=='t'){
						$url .= '/t_'.$t[1].'/';
					}else{
						if(substr($url,-1)!='/'){
							$url .= '/';
						}
						$url .= $t[0].'/'.$t[1];
					}
				}
				if(isset($params[2])){
					$t = explode(':',$params[2]);
					if(substr($url,-1)!='/'){
						$url .= '/';
					}
					$url .= $t[0].'/'.$t[1];
				}
				if(isset($params[3])){
					$t = explode(':',$params[3]);
					if(substr($url,-1)!='/'){
						$url .= '/';
					}
					$url .= $t[0].'/'.$t[1];
				}
				if(isset($params[4])){
					$t = explode(':',$params[4]);
					if(isset($t[1]) && $t[1]!=0){
						if(substr($url,-1)!='/'){
							$url .= '/';
						}
						$url .= $t[0].'/'.$t[1];
					}
				}

				/*if(isset($params[4])){
					$t = explode(':',$params[4]);
					if(substr($url,-1)!='/'){
						$url .= '/';
					}
					$url .= $t[0].'/'.$t[1];
				}*/
				if(isset($page) && $page>1){
					if(substr($url,-1)!='/'){
						$url .= '/';
					}
					$url .= 'p/'.$page;
				}
			} elseif($action == "diary") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/diary/i/" . $params[0] . ".html";
					} else {
						$url .= "/diary/i/" . $params[0] . "/p/" . $page . ".html";
					}
				}
			}
			break;

		case "UcAlbum" :
			if($action == "photo") {
				if($params[0]) {
					if($page == 1) {
						if(isset($params[1]) && $params[1]) {
							$url .= "/photo/u/" . $params[0] . "/t_" . $params[1] . "/";
						} else {
							$url .= "/photo/u/" . $params[0];
						}
					} else {
						if(isset($params[1]) && $params[1]) {
							$url .= "/photo/u/" . $params[0] . "/t_".$params[1] . "/p/".$page;
						} else {
							$url .= "/photo/u/" . $params[0] . "/p/".$page;
						}
					}
				}
			} elseif($action == "photoList") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/photo/a/" . $params[0];
					} else {
						$url .= "/photo/a/" . $params[0] . "/p/".$page;
					}
				}
			} elseif($action == "photoshow") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/photo/p/" . $params[0] . ".html";
					} else {
						$url .= "/photo/p/" . $params[0] . "/p/" . $page . ".html";
					}
				}
			}
			break;

		case "UcForum":
			if($action == "forum") {
				if($params[0]) {
					if($page == 1) {
						if($params[1]){
							$url .= "/forum/u/".$params[0].'/type/'.$params[1];
						}else{
							$url .= "/forum/u/".$params[0];	
						}
						
					} else {
						if($params[1]){
							$url .= "/forum/u/".$params[0] . "/type/".$params[1]. "/p/".$page;
						}else{
							$url .= "/forum/u/".$params[0] . "/p/".$page;
						}
						
					}
				}
			} elseif($action == "reForum") {
				if($params[0]) {
					if($page == 1) {
						if($params[1]){
							$url .= "/reforum/u/".$params[0].'/type/'.$params[1];
						}else{
							$url .= "/reforum/u/".$params[0];
						}
						
					} else {
						if($params[1]){
							$url .= "/reforum/u/".$params[0] . "/type/".$params[1]. "/p/".$page;
						}else{
							$url .= "/reforum/u/".$params[0] . "/p/".$page;
						}
						
					}
				}
			} elseif($action == "attentionGroup") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/groupforum/u/".$params[0];
					} else {
						$url .= "/groupforum/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "attentionThread") {
				if($params[0]) {
					if($page == 1) {
						if($params[1]){
							$url .= "/tforum/u/".$params[0].'/type/'.$params[1];
						}else{
							$url .= "/tforum/u/".$params[0];
						}
						
					} else {
						if($params[1]){
							$url .= "/tforum/u/".$params[0] . "/type/".$params[1]. "/p/".$page;
						}else{
							$url .= "/tforum/u/".$params[0] . "/p/".$page;
						}
						
					}
				}
			}
			break;
		case "UcBaike":
			// 提问
			if($action == "ask") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/pbaike/u/".$params[0];
					} else {
						$url .= "/pbaike/u/".$params[0] . "/p/".$page;
					}
				}
			} 
			// 回答
			elseif($action == "reply") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/rebaike/u/".$params[0];
					} else {
						$url .= "/rebaike/u/".$params[0] . "/p/".$page;
					}
				}
			} 
			// 收藏
			elseif($action == "collection") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/baike/u/".$params[0];
					} else {
						$url .= "/baike/u/".$params[0] . "/p/".$page;
					}
				}
			} 
			// 关注
			elseif($action == "attention") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/abaike/u/".$params[0];
					} else {
						$url .= "/abaike/u/".$params[0] . "/p/".$page;
					}
				}
			}
			break;
		case "Baike" : 
			$url = C('BLOG_DIR');
			// 词条
			if($action == "entry") {
				// 词条id
				if($params[0]) {
					$url .= '/entry/detail/' . $params[0] . '.html';
				}
			}
			// 问答
			elseif($action == "ask") {
				// 问答id
				if($params[0]) {
					$url .= '/post/' . $params[0] . '.html';
				}
			}

			break;
		case "UcSearch" :
			if($action == "search") {
				if($page == 1) {
					$url .= "/search";
				} else {
					$url .= "/search/p/".$page;
				}
			}
			break;
		case "UcRelation" :
			if($action == "follow") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/follow/u/".$params[0];
					} else {
						$url .= "/follow/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "fans") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/fans/u/".$params[0];
					} else {
						$url .= "/fans/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "friends") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/friends/u/".$params[0];
					} else {
						$url .= "/friends/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "invite") {
                if($params[0]) {
                    if($page == 1) {
                        $url .= "/invite/u/".$params[0];
                    } else {
                        $url .= "/invite/u/".$params[0] . "/p/".$page;
                    }
                }
            }
			break;
		case "UcComment" :
			if($action == 'commentList') {
				if($page == 1) {
					if($params[0]) {
						$url .= "/comment/t/".$params[0];
					} else {
						$url .= "/comment";
					}
				} else {
					if($params[0]) {
						$url .= '/comment/t/' . $params[0] . '/p/' . $page;
					} else {
						$url .= "/comment/p/" . $page;
					}
				}
			}
			break;
		case 'BkCategory' :
			$url = C('BLOG_DIR');
			if($action == 'category') {
				$url .= '/baike/' . $params[0] . '/';
			}else if ($action == 'discuss') {
				$url .= '/baike/discuss-0-'.$param[0].'-' . $param[1] .'-'.$param[2]. '/';
			}
			break;
		case 'BkTeam' :
			$url = C('BLOG_DIR');
			if($action == 'team') {
				$url .= '/group/' . $params[0] . '/';
			}
			break;
		case 'BkThread' :
			$url = C('BLOG_DIR');
			if($action == 'thread') {
				$url .= '/post/' . $params[0] . '.html';
			}
			break;
		case 'BkArticle' :
			$url = C('BLOG_DIR');
			if($action == 'article') {
				$url .= '/article/' . $params[0] . '.html';
			}
			break;
	}

	return $url;
}

/**
 * 页面头部的title,description,keywords
 *
 * @param $control string 控制器名
 * @param $action string 方法名
 * @param $userinfo array 当前页面资源对应用户信息
 * @param $attach array 附属信息
 *
 * @return array(title,description,keywords) 页面头部信息数组
 */
function html_header_info($control,$action,$userinfo,$attach=null){
	$result = array('keywords'=>'','description'=>'','title'=>'');
	$name = '';	//显示的用户名
	if(isset($userinfo['nickname'])){
		$name=$userinfo['nickname'];
	}else{
		$name='波奇'.$userinfo['uid'];
	}
	if($control=='UcDiary'){
		if($action=='diaryList'){
			$diaryType = $attach['diaryType'];
			if(!empty($diaryType)){
				$result['keywords']=$diaryType['name'].',养宠日记';
				$result['title']=$diaryType['name'].' - '.$name.'的养宠日记 – 波奇网宠物家园、分享宠物的快乐生活';
				$result['description']=$name.'的'.$diaryType['name'].'，记录养宠的经历，分享生活的点滴';
			}else{
				if(!empty($attach['page']) && $attach['page']>1){
					$result['keywords']=$name.'的养宠日记';
					$result['title']=$name.'的养宠日记 - 页'.$attach['page'].' – 波奇网宠物家园、分享宠物的快乐生活';
					$result['description']=$name.'的养宠日记,第'.$attach['page'].'页,记录养宠的经历，分享生活的点滴';
				}else{
					$result['keywords']=$name.'的养宠日记';
					$result['title']=$name.'的养宠日记、养宠心得 – 波奇网宠物家园、分享宠物的快乐生活';
					$result['description']=$name.'的养宠日记，记录养宠的经历，分享生活的点滴';
				}
			}
		}else if($action=='diary'){
			$result['title']=$attach['diaryTitle'];
			if(!empty($attach['petTypes']) && $attach['petTypes']!=''){
				$result['title'] .= '-'.$attach['petTypes'];
			}
			$result['title'] .= '-波奇网宠物家园、分享宠物的快乐生活';

			$result['description']=$name.'分享的';
			if(!empty($attach['petTypes']) && $attach['petTypes']!=''){
				$result['description'] .= $attach['petTypes'];
			}
			$result['description'] .= '养宠日记：'.$attach['diaryTitle'].'，波奇网宠物家园，您可以和宠友们分享自己养宠心得，养宠经历和乐趣。';
			$result['keywords']=$attach['diaryTitle'];
		}
	}
	return $result;
}

	/**
	 * 获得单张图片显示临界尺寸(最大的宽和高)
	 * 返回宽和高array(宽,高)
	 *
	 * @param $width int 宽度
	 * @param $height int 高度
	 * @param $maxwidth int 最大宽度
	 * @param $maxheight int 最大高度
	 * @param $t boolean
	 *
	 * @return string 宽高
	 */
	function getallsizebymin($width, $height, $maxwidth, $maxheight, $t=false) {
		if(!$maxwidth) {
			$maxwidth = $width;
		}
		if(!$maxheight) {
			$maxheight = $height;
		}
		$ratio = 1;
		$str = '';
		if(!empty($width) && !empty($height) ) {
			if($maxwidth && $maxheight) {
				//图片宽 >= 高
				if($width >= $height) {
					//按高度压缩比率
					if($height > $maxheight) {
						$heightratio = $maxheight/$height;
					} else {
						$heightratio = 1;
					}
				} else {
					//按宽度压缩比率
					if($width > $maxwidth) {
						$widthratio = $maxwidth / $width;
					} else {
						$widthratio = 1;
					}
				}

				//计算图片压缩比率
				if($widthratio > 0) {
					$ratio = $widthratio;
				} elseif($heightratio > 0) {
					$ratio = $heightratio;
				}

				//根据得出的比例,重新计算缩略图的宽和高
				$newwidth = $ratio*$width;
				$newheight = $ratio*$height;
				if(!$t) {
					return array($newwidth,$newheight);
				} else {
					$str=" width='$newwidth' height='$newheight' ";
					return $str;
				}
			}
		}
		return $str;
	}


	/**
	 * 获得单张图片显示临界尺寸(最大的宽和高)
	 * 返回宽和高array(宽,高)
	 *
	 * @param $width int 宽度
	 * @param $height int 高度
	 * @param $maxwidth int 最大宽度
	 * @param $maxheight int 最大高度
	 * @param $t boolean
	 *
	 * @return string 宽高
	 */
	function getallsize($width, $height, $maxwidth, $maxheight, $t=false) {
		if(!$maxwidth) {
			$maxwidth = $width;
		}
		if(!$maxheight) {
			$maxheight = $height;
		}
		$ratio = 1;
		$str = '';
		if(!empty($width)&&!empty($height)) {
			if(!empty($width)) {
				//计算图片压缩比率
				if($width>$maxwidth || $height>$maxheight) {
					if($width>$maxwidth) {
						if($width>$maxwidth) {
							$widthratio = $maxwidth/$width;
						}

						if($height>$maxheight) {
							$heightratio = $maxheight/$height;
						}
					}
				}
				if($widthratio > 0 && $heightratio > 0) {
					if($widthratio < $heightratio) {
						$ratio = $widthratio;
					} else {
						$ratio = $heightratio;
					}
				} elseif($widthratio > 0) {
					$ratio = $widthratio;
				} elseif($heightratio > 0) {
					$ratio = $heightratio;
				}

				//根据得出的比例,重新计算缩略图的宽和高
				$newwidth = $ratio*$width;
				$newheight = $ratio*$height;
				if(!$t) {
					return array($newwidth,$newheight);
				} else {
					$str=" width='$newwidth' height='$newheight' ";
					return $str;
				}
			}
		}
		return $str;
	}

	/**
	 * img标签处理
	 * @param string $img_string 要处理的字符串
	 * @return string $img_string 返回处理后的字符串
	 */
	function img_treat($img_string){

		$img_string = str_replace('[img]', '<img src="', $img_string);
		$img_string = str_replace('[/img]', '" border="0" alt="" /> ', $img_string);

		return $img_string;
	}

	/**
	 * img标签反处理
	 * @param string $img_string 要处理的字符串
	 * @return string $img_string 返回处理后的字符串
	 */
	function img_opposition_treat($img_string){

		$img_string = str_replace('<img src="', '[img]', $img_string);
		$img_string = str_replace('" border="0" alt="" />', '[/img]', $img_string);

		return $img_string;
	}

//将分钟转化成几小时几分
//前台社区个人资料页的在线时间显示精确至小时（以30分钟为限，超过30分钟进位至一小时）
function min2time ($min) {
	if($min == 0) {
		return '';
	}
	if ($min >= 60) {
		$hour = floor($min / 60);
		$time = $hour . '小时 ';
		$min = $min % 60;
		if ($min > 30) {
			$time = ($hour + 1) . '小时 ';
		} else {
			$min != 0 && $time .= $min . '分钟';
		}
	} else {
		if ($min > 30) {
			$time = '1小时';
		} else {
			$time = $min . '分钟';
		}
	}
	return $time;
}

	//获取图片大小
	function myGetImageSize($url){
		$handle = fopen($url, 'rb');
			// 获取文件数据流信息
			$meta = stream_get_meta_data($handle);
			//nginx 的信息保存在 headers 里，apache 则直接在 wrapper_data
			$dataInfo = isset($meta['wrapper_data']['headers']) ? $meta['wrapper_data']['headers'] : $meta['wrapper_data'];
			foreach ($dataInfo as $va) {
				if ( preg_match('/length/iU', $va)) {
					$ts = explode(':', $va);
					$result['size'] = trim(array_pop($ts));
					break;
				}
			}
		//if ($type == 'fread') fclose($handle);
		return $result;
	}

	 /**
	 *使用缩率图
	 *@param $path 要替换的图片路径
	 *@param $prefix 找到要替换的前缀
	 *@param $replace 需要替换的值
	 */
	 function getSmallPicPath($path=null,$prefix=null,$replace=null){
		if(empty($prefix)){
			$prefix="_y";
		}

		if(empty($replace)){
			$replace='_m';
		}
		$intLastPosition = strripos($path,$prefix);
		if($intLastPosition){//gif 格式 不存在 _y 的后缀。其他格式存在则替换成页面的大小比例缩略图
			$newpath= substr_replace($path,$replace,$intLastPosition,2);
		}else{
			$newpath = $path;
		}
		return $newpath;
	 }
	//GET请求地址
	function get_url($url,$time=10){
		$time = ($time<=30)?$time:30;
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);        //CURLOPT_URL  需要获取的URL地址
		// curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //CURLOPT_RETURNTRANSFER   将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。 
        curl_setopt($ch, CURLOPT_TIMEOUT, $time); 
		//curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
		$output = curl_exec($ch);
        curl_close($ch);
		return $output;
	}
	//POST请求地址
	function post_url($url,$post_data,$time=100){
		$time = ($time<=30)?$time:30;
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);        //CURLOPT_URL  需要获取的URL地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //CURLOPT_RETURNTRANSFER   将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_POST, 1);  //CURLOPT_POST  启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。 	
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);   //CURLOPT_POSTFIELDS  全部数据使用HTTP协议中的"POST"操作来发送。要发送文件，在文件名前面加上@前缀并使用完整路径。这个参数可以通过urlencoded后的字符串类似'para1=val1&para2=val2&...'或使用一个以字段名为键值，字段数据为值的数组。如果value是一个数组，Content-Type头将会被设置成multipart/form-data。 
        curl_setopt($ch, CURLOPT_TIMEOUT, $time); 
		$output = curl_exec($ch);
        curl_close($ch);
		return $output;
	}
	/**
	 * PHP 过滤HTML代码空格,回车换行符的函数
	 * echo deletehtml()
	 */
	function deletehtml($str){
		$str = trim($str);
		//$str=strip_tags($str,"");
		$str=preg_replace("{\t}","",$str);
		$str=preg_replace("{\r\n}","",$str);
		$str=preg_replace("{\r}","",$str);
		$str=preg_replace("{\n}","",$str);
		//$str=preg_replace("{ }","",$str);
		return $str;
	}

	/**
	 * 对个人主页发表微博，评论，回复进行安全验证
	 *
	 * @param string $token 发送的token
	 * @param int $flag 是否要删除session值 默认为0删除
	 *
	 * @return bool 成功或失败
	 */
	function checkSafeForSns($token,$flag = 0){
		
		// 判断请求地址 域名为boqii.com
		$refererurl = parse_url(getenv('HTTP_REFERER'))	;
		if(!strpos($refererurl['host'], 'boqii.com')){
			return false;
		}
		
		// 匹配session中的token
		if(empty($token) || !session($token)){
			return false;
		}
		if (!$flag) {
			unset($_SESSION[$token]);
		}
		
		return true;
	}
?>
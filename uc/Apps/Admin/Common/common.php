<?php

//暂无使用
function startLimit($page,$limit){
	$start = (intval($page)-1)*intval($limit);
	return $start;
}


//使用缩率图
 function getSmallPicPath($path=null,$prefix=null){
	if(empty($prefix)){
		$prefix="_y";
	}
	$intLastPosition = strripos($path,$prefix);
	if($intLastPosition){//gif 格式 不存在 _y 的后缀。其他格式存在则替换成页面的大小比例缩略图
        $newpath= substr_replace($path,"_m", $intLastPosition,2);
    }else{
		$newpath = $path;
    }
	return $newpath;
 }

//获取最近时间段时间戳
 function getTime($days){
	$times = time()-$days*24*3600;
	return $times;
 }

//取出数组中的空值(一维数组)
 function unsetNull($arr){
	foreach($arr as $key=>$val){
		if(empty($val)){
			unset($arr[$key]);
		}
	}
	return $arr;
 }


//编辑日志 匹配图片 删除的图片
	function preg_match_diary($content,$photos=null){
        //匹配出所有的图片
		preg_match_all("/<img.*>/U",$content,$matches);//带引号
		$new_arr=array_unique($matches[0]);//去除数组中重复的值
        //整理成一维数组
		foreach($new_arr as $key){ 
			$arr[]=$key; 
		}
        //提取每个图片中的属性
		$needs = array('src','title','pid');
        $ret = array();
		foreach($arr as $key=>$val){
			foreach($needs as $k=>$need) {
				preg_match('|<img\s+.*?'.$need.'\s*=\s*[\'"]([^\'"]+).*?>|i',$val,$arr);
				$ret[$key][$need] = $arr[1];
			}
		}
        //判断是否删除
		if(!empty($photos)){
			foreach($ret as $k=>$v){
				if($v['pid']){
					$photo_ids[]=$v['pid'];
				}	
			}
			foreach($photos as $key=>$val){
				if(!in_array($val['photo_id'],$photo_ids)){
					$srcs['old'][$key]=$val['photo_id'];
				}
			}
		}
		return $srcs['old'];
	}

/**
* 根据city_id获取省市名字，table_name默认为shop_city，返回如:上海 浦东
* @param $city_id
* @param string $table_name
* @return string
*/
function getProvinceCity($city_id, $table_name = 'shop_city')
{
$db = M();

// 获取数据
if (strlen($city_id) == 4) { // 省市2级

	$sql = " SELECT c1.city_name AS city, c2.city_name AS province FROM $table_name c1 LEFT JOIN $table_name c2 ";
	$sql .= " ON LEFT(c1.city_id, 2) = c2.city_id WHERE c1.city_id='$city_id' ";
	$data = $db->query($sql);
} elseif (strlen($city_id) == 6) { // 省市3级

	$sql = " SELECT c1.city_name AS area, c2.city_name AS city, c3.city_name AS province FROM $table_name c1 LEFT JOIN $table_name c2 ";
	$sql .= " ON LEFT(c1.city_id, 4) = c2.city_id LEFT JOIN $table_name c3 ON LEFT(c1.city_id, 2) = c3.city_id WHERE c1.city_id='$city_id' ";
	$data = $db->query($sql);
}
$city_data = $data[0]['province'] . ' ' . $data[0]['city'] . ' ' . $data[0]['area'];
return $city_data;
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


// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
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

//GET请求地址
function get_url($url,$time=10){
	$time = ($time<=30)?$time:30;
	$_COOKIE['boqii_auth'] = urlencode($_COOKIE['boqii_auth']);
	$cookie = $_COOKIE;
	$count = count($cookie);
	$i = 1;
	$str = '';
	foreach($cookie as $k=>$v){
		$str .= $k.'='.$v.($count!=$i?'; ':'');
		$i++;
	}
	session_write_close();
	$ch= curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_COOKIE, $str);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, $time);
	curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	$result = curl_exec($ch);
	session_start();
	return $result;
}
//	//GET请求地址
//	function get_url($url,$time=10){
//		$time = ($time<=30)?$time:30;
//		$ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);        //CURLOPT_URL  需要获取的URL地址
//		// curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //CURLOPT_RETURNTRANSFER   将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。 
//        curl_setopt($ch, CURLOPT_TIMEOUT, $time); 
//		//curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
//		$output = curl_exec($ch);
//        curl_close($ch);
//		return $output;
//	}
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
  * 提示，关闭层
  */
function alert($msg){
    echo '<html>';
    echo '<head>';
    echo '<title>error</title>';
    echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
    echo '</head>';
    echo '<body>';
    echo '<script type="text/javascript">alert("'.$msg.'");history.back();</script>';
    echo '</body>';
    echo '</html>';
    exit;
}

/**
  * 判断改变的字段
  * $param array 
  * 		$data1 array 修改前的数据 有键名和键值
  * 		$data2 array 修改后的数据 
  * return array
  */
function getChangeCloum($data1,$data2){
    foreach($data1 as $key=>$val){
		foreach($data2 as $k=>$v){
			if($v!=$val[$k]){
				$arr[$k]['column']=$k;
				$arr[$k]['beforeContent']=$val[$k];
				$arr[$k]['afterContent']=$v;
			}
		}
	}
	return $arr;
}

/**
 * 返回操作回复消息
 * @param   string  $str
 * @return  string
 */
function showmsg($str,$url=''){
    echo "<script>";
    if(!empty($str))
    {
        echo "alert('".$str."');";
    }
    if(!empty($url))
    {
        echo "location.href='".$url."';";
    }
    echo "</script>";
}

/**
 * 生成动态
 *
 * type=8 operatetype=1 百科关注分类.
 * type=8 operatetype=2 百科加入小组.
 * type=8 operatetype=3 百科发帖.
 * type=8 operatetype=4 百科帖子评论.
 * type=8 operatetype=5 百科文章评论.
 * type=5 operatetype=5 百科帖子评论回复.
 * type=5 operatetype=6 百科文章评论回复.
 */
function add_dynamic($param) {
	//动态类型和操作类型
	if(!isset($param['uid']) || !isset($param['type']) || !isset($param['operatetype'])) {
		return false;
	}
	$url = C("I_DIR") . "/index.php/Index/addDynamic";
	$url .= '?uid='.$param['uid'];
	$url .= '&ouid='.$param['ouid'];
	if(isset($param['ousername'])) {
		$url .= '&ousername='.$param['ousername'];
	}
	$url .= '&type='.$param['type'];
	$url .= '&operatetype='.$param['operatetype'];
	$url .= '&oid='.$param['oid'];
	if(isset($param['otitle'])) {
		$url .= '&otitle='.$param['otitle'];
	}
	if(isset($param['mid'])) {
		$url .= '&mid='.$param['mid'];
	}
	get_url($url);
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
				"121"	=> "/forum/suzhou",
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
						$url .= "/forum/u/".$params[0];
					} else {
						$url .= "/forum/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "reForum") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/reforum/u/".$params[0];
					} else {
						$url .= "/reforum/u/".$params[0] . "/p/".$page;
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
						$url .= "/tforum/u/".$params[0];
					} else {
						$url .= "/tforum/u/".$params[0] . "/p/".$page;
					}
				}
			}
			break;
		case "UcBaike":
			if($action == "post") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/pbaike/u/".$params[0];
					} else {
						$url .= "/pbaike/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "repost") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/rebaike/u/".$params[0];
					} else {
						$url .= "/rebaike/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "category") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/baike/u/".$params[0];
					} else {
						$url .= "/baike/u/".$params[0] . "/p/".$page;
					}
				}
			} elseif($action == "team") {
				if($params[0]) {
					if($page == 1) {
						$url .= "/tbaike/u/".$params[0];
					} else {
						$url .= "/tbaike/u/".$params[0] . "/p/".$page;
					}
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
					$url .= "/comment";
				} else {
					$url .= "/comment/p/" . $page;
				}
			}
			break;
		case 'BkCategory' :
			$url = C('BLOG_DIR');
			if($action == 'category') {
				$url .= '/baike/' . $params[0] . '/';
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
        case "BkPetCategory":
            $url = C('BLOG_DIR');
            if($action == "index") {
                $url .= "/pet-all/";
            }else if($action == 'detail'){
                $url .= '/pet-all/'.$params[0].'.html';
            }else if($action == 'search'){
                $url .= '/pet-all/search/';
            }
            break;
	}

	return $url;
}
?>
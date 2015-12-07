<?php
/**
 * 自定义函数库(调用时手动载入)
 */
/**
 * 内容中指定的标签词增加链接
 *
 * @param $content string 内容
 *
 * @return string 处理后的字符串
 */
function preg_baike_article($content){
	//获取指定标签（status=0：正常标签；type=11：百科文章标签；from_type=0：系统标签；display_status=0：文章内容允许加该标签及链接）
	$tag = M()->Table('uc_tag') -> where('status=0 and type=11 and from_type=0 and display_status=0') -> field('id,name') -> order('length(name) asc') -> select();
	// 去除重名标签
	$strTags = '';
	foreach($tag as $key =>$val) {
		if(strpos($strTags, '"'.$val['name'].'"') !== false) {
			unset($tag[$key]);
		} else {
			$strTags .= ',"'. $val['name'].'"'; 
		}
	}
	//提取文章已有的标签
	preg_match_all('/<a.*>(.*)<\/a>/U',$content,$arr);
	//去除数组中重复的值
	$new_arr = array_unique($arr[0]);
	if($new_arr){
		foreach($new_arr as $k=>$v){
			foreach($tag as $kk=>$vv){
				preg_match('/('.$vv['id'].')/',$v,$match1);
				preg_match('/('.$vv['name'].')/',$v,$match2);
				if($match1 && $match2){
					//匹配出a标签中的文字
					preg_match('/<a(.*)>(.*)<\/a>/',$v,$replace);
					$content = str_replace($new_arr[$k],$replace[2],$content);
				}
			}
		}
	}
	//先匹配出关键字  随机取出五个
	$rand_1 = array();
	$rand_2 = array();
	foreach($tag as $k_s=>$v_s){
		preg_match('/('.$v_s['name'].')/',$content,$match_arr);
		if($match_arr){
			$rand_1[$v_s['id']] = $match_arr[0];
		}
	}
	$con = count($rand_1);
	if($con>5){
		$rand_keys = array_rand($rand_1, 5);
		foreach($rand_keys as $k_keys=>$k_val){
			$rand_2[$k_val] = $rand_1[$k_val];
		}
	}else{
		$rand_2=$rand_1;
	}

	foreach($rand_2 as $k=>$v){
		//echo $v;
		$content = preg_replace('/'.$v.'/',"<a href='".C('BLOG_DIR')."/tag/".$k."/' target='_blank' style='text-decoration:none;border-bottom:1px dotted #FE730C;'>".$v."</a>",$content,1);
	}
	//删除嵌套其他标签中的a标签
	preg_match_all('/title=\"(.*)\"/U',$content,$arr_a);
	preg_match_all('/alt=\"(.*)\"/U',$content,$arr_a_1);
	$new_arr_a=array_merge_recursive($arr_a[0],$arr_a_1[0]);//去除数组中重复的值
	foreach($new_arr_a as $key=>$val){
		preg_match_all('/<a.*>(.*)<\/a>/U',$val,$arra);
		$arr1 = $arra[0];
		$arr2 = $arra[1];
		if($arra[0]){
			foreach($arr1 as $k=>$v){
				$content = str_replace($v,$arr2[$k], $content);
			}
		}
	}
	return $content;
}

//匹配图片描述
function preg_baike_article_pic($content){
//	// 标签
//	$tag = M('uc_tag') -> where('status=0 and type=11 and from_type=0 and display_status=0') -> field('id,name') -> order('id desc') -> select();
//	// 去除重名标签
//	$strTags = '';
//	foreach($tag as $key =>$val) {
//		if(strpos($strTags, '"'.$val['name'].'"') !== false) {
//			unset($tag[$key]);
//		} else {
//			$strTags .= ',"'. $val['name'].'"'; 
//		}
//	}

	//去掉原有匹配  从新全部匹配
	preg_match_all('/<span class=\"replace_title\">(.*)<\/span>/U',$content,$matches_a);//带引号
	$new_arr_a=array_unique($matches_a[0]);//去除数组中重复的值
	if($new_arr_a){
		foreach($new_arr_a as $preg){
			preg_match('/<img.*>/U',$preg,$arr_a);
			if($arr_a[0]){
				$content = str_replace($preg,$arr_a[0],$content);
			}else{
				$content = str_replace($preg,'',$content);
			}
		}
	}

	//匹配出所有的图片
	preg_match_all("/<img.*>/U",$content,$matches);//带引号
	$new_arr=array_unique($matches[0]);//去除数组中重复的值
	if($new_arr){
		//整理成一维数组
		foreach($new_arr as $key){
				$arr[]=$key;
		}
		//提取每个图片中的属性
		$needs = array('src','title','pid');
		$ret = array();
		foreach($arr as $kk=>$vv){
			$title='';
			foreach($needs as $k=>$need) {
				if($need == 'title'){
					preg_match('|<img\s+.*?'.$need.'\s*=\s*[\'"]([^\'"]+).*?>|i',$vv,$arr);
					if(!preg_match("/[jpg|png|jpeg|gif]/", $arr[1])){
						$title = $arr[1];
					}
				}
			}
			if($title){
				$titleArr = explode(' ',$title);

				$str = '';
				foreach($titleArr as $tk=>$tags){

//					foreach($tag as $t=>$tval){
//						if( trim($tags) == $tval['name'] ){
//							$str .=' <a href="'.C('BLOG_DIR').'/tag/'.$tval['id'].'/" target="_blank" style="text-decoration:none;border-bottom:1px dotted #FE730C;">'.$tags.'</a> ';
//							$titleArr[$tk]='';
//						}
//					}
					$tval = M()->Table('uc_tag')->where('status=0 and type=11 and from_type=0 and display_status=0 and name="'. trim($tags).'"')->field('id,name')->find();
					if($tval) {
						$str .=' <a href="'.C('BLOG_DIR').'/tag/'.$tval['id'].'/" target="_blank" style="text-decoration:none;border-bottom:1px dotted #FE730C;">'.$tags.'</a> ';
						$titleArr[$tk]='';
					}

					if($titleArr[$tk]){
						$str .= ' '.$tags.' ';
					}
				}

				//$str = $str.implode(' ',$titleArr);
				if($contents){
					$contents = str_replace($vv,'<span class="replace_title">'.$vv.'<br />'.$str.'</span>',$contents);
				}else{
					$contents = str_replace($vv,'<span class="replace_title">'.$vv.'<br />'.$str.'</span>',$content);
				}
			}else{
				if(empty($contents)){
					$contents = $content;
				}
			}
		}

	}else{
		$contents = $content;
	}
	return $contents;
}

/**
 * 图片后面增加alt属性
 * @param $content
 * @return mixed
 */
   function getImgTitleAddAlt ($content) {
       $pattern = "/<img .*?  src=[\"|\'].*?[\"|\'] .*?  \/?>/isx";
       $pattern2 = "/<img .*? title=[\"|\'](.*?)[\"|\'] .*?  \/?>/isx";
       $matchs =array();
       if ($content) {
           preg_match_all($pattern,$content,$matchs);
           if ($matchs[0]) {
               foreach ($matchs[0] as $key => $val) {
                   $matchsTitles =array();
                   preg_match_all($pattern2,$val,$matchsTitles);
                   if (!$matchsTitles[0]) {
                       unset($matchs[0][$key]);
                       continue;
                   }

                   $strReplace =preg_replace("/alt=\".*?\"/",'',$matchs[0][$key]);
                   $str= '';
                   $str = substr($strReplace,0,-2);
                   if ($str) {
                       $arrTitle = $matchsTitles[1];
                       $str .= "alt=\"$arrTitle[0]\" />";
                       //替换
                       $content =  str_replace($val,$str,$content);
                   }
               }
           }
        return  $content;
       }
   }

?>
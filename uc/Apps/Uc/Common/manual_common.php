<?php
//自定义函数库(调用时手动载入)
/**
 +------------------------------------------------------------------------------
 * 用户中心专用函数
 +------------------------------------------------------------------------------
 * @category Uc
 * @package  Common
 * @author   Vic <vic@boqii.com>
 * @version  
 +------------------------------------------------------------------------------
 */
 /**
*	获取宠物类别
*/
function get_pet_type($petid){
	global $db;

	// 获取种类
	$query = $db->query("SELECT pet_type_id, pet_type_name FROM uc_user_pet WHERE pet_type_id<100");

	$pet = '<option value="10" selected>请选择</option>';
	$pettype = 'pettypearray10 = new Array(";请选择");';
	$getpetid = 'if(petid == 10) return pettypearray10;';
	while($pets = $db->fetch_array($query)) {
		if($pets['pet_type_id'] == $petid)
			$pet .= '<option value="'.$pets['pet_type_id'].'" selected>'.$pets['pet_type_name'].'</option>';
		else
			$pet .= '<option value="'.$pets['pet_type_id'].'">'.$pets['pet_type_name'].'</option>';
		$pet_id = $pets['pet_type_id'];

		// 获取品种
		$query2 = $db->query("SELECT pet_type_id, pet_type_name FROM uc_user_pet WHERE pet_type_id LIKE '$pet_id%' AND pet_type_id>100");
		$pettype .= 'pettypearray' . $pet_id . ' = new Array(";请选择",';
		while($pettypes = $db->fetch_array($query2)) {
			$pettype .= '"'.$pettypes['pet_type_id'].';'.$pettypes['pet_type_name'].'",';
		}
		$pettype .= ');';
		$pettype = substr($pettype, 0, -3) . ');';
		$getpetid .= 'if(petid == '.$pet_id.') return pettypearray'.$pet_id.';';
	}

	// 添加到宠物类别信息数组
	$pettype_info['pet'] = $pet;
	$pettype_info['pettype'] = $pettype;
	$pettype_info['gettypeid'] = $getpetid;

	return $pettype_info;
}

/**
*	获取省市
*/
function get_city($provinceid, $tablename='boqii_city'){
	global $db;

	// 获取省
	$query = $db->query("SELECT city_id,city_name FROM $tablename WHERE city_id<100");

	$province = '<option value="10" selected>请选择</option>';
	$city = 'cityarray10 = new Array(";请选择");';
	$area = 'areaarray10 = new Array(";请选择");';
	$getcityid = 'if(cityid == 10) return cityarray10;';
	$getareaid = 'if(areaid == 10) return areaarray10;';
	while($provinces = $db->fetch_array($query)) {
		if($provinces['city_id'] == $provinceid)
			$province .= '<option value="'.$provinces['city_id'].'" selected>'.$provinces['city_name'].'</option>';
		else
			$province .= '<option value="'.$provinces['city_id'].'">'.$provinces['city_name'].'</option>';
		$province_id = $provinces['city_id'];

		// 获取市
		$query2 = $db->query("SELECT city_id, city_name FROM $tablename WHERE LEFT(city_id, 2)=$province_id AND city_id>100 AND city_id<10000");
		$city .= 'cityarray' . $province_id . ' = new Array(";请选择",';
		while($cities = $db->fetch_array($query2)) {
			$city .= '"'.$cities['city_id'].';'.$cities['city_name'].'",';

			$city_id = $cities['city_id'];

			// 获取地区
			$query3 = $db->query("SELECT city_id, city_name FROM $tablename WHERE LEFT(city_id, 4)=$city_id AND city_id>100000");
			$area .= 'areaarray' . $city_id . ' = new Array(";请选择",';
			while ($areas = $db->fetch_array($query3)){
				$area .= '"'.$areas['city_id'].';'.$areas['city_name'].'",';
			}

			$area .= ');';
			$area = substr($area, 0, -3) . ');';
			$getareaid .= 'if(areaid == '.$city_id.') return areaarray'.$city_id.';';
		}

		$city .= ');';
		$city = substr($city, 0, -3) . ');';
		$getcityid .= 'if(cityid == '.$province_id.') return cityarray'.$province_id.';';
	}

	// 释放结果集
	$db->free_result($query);
	$db->free_result($query2);
	$db->free_result($query3);

	// 添加到省市信息数组
	$city_info['province'] = $province;
	$city_info['city'] = $city;
	$city_info['getcityid'] = $getcityid;
	$city_info['area'] = $area;
	$city_info['getareaid'] = $getareaid;

	return $city_info;
}

// 根据city_id获取省市名字，table_name默认为shop_city，返回如:上海 浦东
function get_province_city($city_id, $table_name='shop_city'){
	global $db;

	// 获取数据
	if( strlen($city_id) == 4 ){				// 省市2级

		$sql  = " SELECT c1.city_name AS city, c2.city_name AS province FROM $table_name c1 LEFT JOIN $table_name c2 ";
		$sql .= " ON LEFT(c1.city_id, 2) = c2.city_id WHERE c1.city_id='$city_id' ";

		$data = $db->get_one($sql);
	} elseif ( strlen($city_id) == 6 ){			// 省市3级

		$sql  = " SELECT c1.city_name AS area, c2.city_name AS city, c3.city_name AS province FROM $table_name c1 LEFT JOIN $table_name c2 ";
		$sql .= " ON LEFT(c1.city_id, 4) = c2.city_id LEFT JOIN $table_name c3 ON LEFT(c1.city_id, 2) = c3.city_id WHERE c1.city_id='$city_id' ";

		$data = $db->get_one($sql);
	}

	$city_data = $data['province'] . ' ' . $data['city'] . ' ' . $data['area'];

	return $city_data;
}

//编辑或者新增日志匹配图片 返回新添加的图片 或者删除的图片
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
		//判断title是否存在 判断pid是否存在
		foreach($ret as $key=>$val){
			if(!empty($val['title']) && empty($val['pid'])){
				$srcs['new'][$key]['src']=$val['src'];
				$srcs['new'][$key]['title']=$val['title'];
			}
		}				
		//print_r(count($ret));
		return $srcs;
	}

	//获得日志中图片对应的pid
	function preg_match_diary_pid($content){
		//匹配出所有的图片
		preg_match_all("/<img.*>/U",$content,$matches);//带引号
		$new_arr=array_unique($matches[0]);//去除数组中重复的值 
		//整理成一维数组
		foreach($new_arr as $key){ 
			$arr[]=$key; 
		}
		//提取每个图片中的属性
		$needs = array('pid');
        $ret = array();
		foreach($arr as $key=>$val){
			foreach($needs as $k=>$need) {
				preg_match('|<img\s+.*?'.$need.'\s*=\s*[\'"]([^\'"]+).*?>|i',$val,$arr);
				$ret[$key][$need] = $arr[1];
			}
		}
		return $ret;
	}

	//编辑时候读取内容
	function edit_diray_preg($content,$photos){
		$arrs = explode('src=',$content);
		foreach($arrs as $key=>$val){
			if($key!=0){
				$arrs[$key]='src='.$val;
			}
		}
		$html='';
		foreach($arrs as $key=>$val){
				foreach($photos as $k=>$v){
					preg_match('|src\s*=\s*[\'"]([^\'"]+).*?>|i',$val,$rets);
					$isTrue = strripos($rets[1],$v['photo_path']);
					if($isTrue){	
						$html.=' pid="'.$v['photo_id'].'" ';
					}
				}
				$html.=$val;		
		}
		return $html;
	}
	//替换图片路径
	function imageUrlReplace($url=null,$type=null){
		$arr = explode('/',$url);
		if($type==1){
			$arr[2]='A';
		}else{
			$arr[2]='D';
		}
		$str = implode('/',$arr);
		return $str; 
	}
	//匹配用户名并从新组合多种格式
	function preg_match_nickname($nickname){
		//$str = '测xunsearch';
		$len = mb_strlen($nickname,'utf-8');
		for($i=0;$i<$len;$i++){
			$array[]=mb_substr($nickname,$i,1,'utf-8');
		}
		for($i=0;$i<$len;$i++){
			$str = '';
			for($j=$i;$j<$len;$j++){
				$str.= $array[$j];
				$arr[] = $str;
			}
		}
		$result = implode('|',$arr);
		return $result;
	}
?>
<?php
/**
 * Test Action类
 */
class TestAction extends BaseAction {


	private function preg_match_nickname($nickname){
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

	private function cityTest(){
		//$apiModel = D('Api');
		//$result = $apiModel->getCityInfo(4304);
		//print_r($result);
		//4304
	}

	//判断图片路径是否存在
	public function isSetUserPic(){
		$code = $this->_get('code');
		if($code == 'boqii_user_avatar'){
			$page = $this->_get('page');
			if(!is_numeric($page)){
				$page=1;
			}
			$userModel = M('boqii_users_extend');
			$count = $userModel->where(" avatar <> '' ")->count();
			$limit = 10000;
			$pcount = ceil($count/$limit);
			$result = $userModel->where(" avatar <> '' ")->field('uid,avatar')->page($page)->limit($limit)->select();
			foreach($result as $key=>$val){
				if(!file_exists(C('BLOG_DIR') . '/' .$val['avatar'])){
					$userModel->save(array('uid'=>$val['uid'],'avatar'=>'image/upload/none1.gif'));
				}
			}
			$page++;
			echo $page;
			if($page<=$pcount){
				echo "<script>location.href='".C('I_DIR')."/index.php/Test/isSetUserPic?code=boqii_user_avatar&page=".$page."';</script>";
			}
			exit;
		}
		exit;
	}

	//医院旧数据导入
	public function import() {
		/*$val='<p><a href="http://www.baidu.com"><img src="sss"/></a></p><p>好样的</p><p><a href="http://www.baidu.com"><img src="sss"/></a></p><p>好样的</p>';
		$pattern = '/<p>(.*)<\/p>/U';
		if(preg_match_all($pattern, $val, $matches)){
			print_r($matches[1]);
		}
		exit;*/

		import('@.ORG.Util.Excel');
		$hospitalList = M('hospital_info')->where('valid=1')->select();
		$data = array();
		foreach($hospitalList as $key=>$val){
			$sq = '';
			/*$city = M('shop_city')->where('id='.$val['city_id'])->find();
			if($city>10000){
				$province = M('shop_city')->where('id='.substr($val['city_id'],0,2))->find();
				$c = M('shop_city')->where('id='.substr($val['city_id'],0,4))->find();
			}*/
			if($val['city_id']>10000){
				$sqr = M('shop_city')->where('city_id='.$val['city_id'])->find();
				$sq = $sqr['city_name'];
			}
			//处理医生
			$doctorinfo = '';
			$pattern = '/<p.*>(.*)<\/p>/U';
			if(preg_match_all($pattern, $val['doctorinfo'], $matches)){
				foreach($matches[1] as $subkey=>$subval){
					if(strpos($subval,'img')===false){
						if(trim($subval)!=''){
							$doctorinfo .= $subval.',';
						}
					}

				}
			}
			//评论
			/*$commentList = M('hospital_comment')->where('hid='.$val['hid'])->select();
			$comment = '';
			foreach($commentList as $ckey=>$cval){
				$user = M('boqii_users')->where('uid='.$cval['uid'])->find();
				if($user){
					$comment .= $user['nickname'].','.date('Y-m-d H:i:s',$cval['cretime']).','.$cval['hoscore'].','.$cval['sercore'].','.$cval['docscore'].','.$cval['attscore'].','.$cval['content'].';';
				}
			}*/
			$data[] = array(
				$val['name'],
				$sq,
				$val['address'],
				$val['points'],
				intval($val['points']/(4*$val['comments'])),
				$val['comments'],
				$val['info'],
				$val['hid'],
			);
		}
		//print_r($data);exit;
		// create a simple 2-dimensional array
		/*$data = array(
			1 => array ('高仕才', 'vic'),
			array('Schwarz', 'Oliver'),
			array('Test', 'Peter')
		);*/
		// generate file (constructor parameters are optional)
		$xls = new Excel('UTF-8', false, '医院');
		$xls->addArray($data);
		$xls->generateXML('hospital');
	}
	//医院旧数据导入
	public function import_comment() {
		import('@.ORG.Util.Excel');
		$hospitalList = M('hospital_comment')->where('valid=1 and ifcheck=1')->select();
		$data = array();
		foreach($hospitalList as $key=>$val){
			$hospital = M('hospital_info')->where('hid='.$val['hid'])->find();
			$user = M('boqii_users')->where('uid='.$val['uid'])->field('nickname,username')->find();
			$data[] = array(
				$user['nickname'],
				$val['content'],
				date('Y-m-d H:i:s',$val['cretime']),
				$hospital['name'],
				$val['hoscore'],
				$val['serscore'],
				$val['docscore'],
				$val['attscore'],
				$val['uid'],
				$val['parentid'],
				$val['cid']
			);
		}
		// generate file (constructor parameters are optional)
		$xls = new Excel('UTF-8', false, '医院-评论');
		$xls->addArray($data);
		$xls->generateXML('hospital-comment');
	}
	//读取
	public function run_business(){
		$hospitalList = M('hospital_info')->where('valid=1')->select();
		$businessModel = M('o2o_business');
		foreach($hospitalList as $key=>$val){
			$code = M('o2o_business')->order('id desc')->find();
			if($code){
				$code = $code['code']+1;
			}else{
				$code = 10000;
			}
			$data = array(
				'name'=>$val['name'],
				'address'=>$val['address'],
				'city_id'=>substr($val['city_id'],0,4),
				'tel1'=>$val['phone'],
				'introduce'=>$val['info'],
				'comment_num'=>$val['comments'],
				'view_num'=>$val['views'],
				'code'=>$code,
				'total_score'=>intval($val['points']/(4*$val['comments'])),
				'create_time'=>time(),
				'update_time'=>time()
			);
			$id = $businessModel->add($data);
			//医师
			$pattern = '/<p.*>(.*)<\/p>/U';
			if(preg_match_all($pattern, $val['doctorinfo'], $matches)){	
				foreach($matches[1] as $subkey=>$subval){
					$img = '';
					$introduce = '';
					if($subval!='&nbsp;' && mb_strlen($subval,'utf-8')>=2 && mb_strlen($subval,'utf-8')<20){
						if(strpos($matches[1][$subkey-1],'img')!==false){
							$img = $matches[1][$subkey-1];
							preg_match_all("/(src)=[\"|\'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|bmp|png))[\"|\'| ]{0,}/isU", $img, $img_array);
							$img = $img_array[2][0];
						}
						if(strlen($matches[1][$subkey+1])>10 && strpos($matches[1][$subkey+1],'img')===false){
							$introduce = $matches[1][$subkey+1];
						}
						$data_doctor = array(
							'business_id'=>$id,
							'name'=>$subval,
							'introduce'=>strip_tags($introduce),
							'create_time'=>time()	
						);
						M('o2o_business_expert')->add($data_doctor);
					 }
				}
			}
		}
		exit;
	}

	//医师
	public function import_doctor() {
		import('@.ORG.Util.Excel');
		$hospitalList = M('hospital_info')->where('valid=1')->select();
		$data = array();
		foreach($hospitalList as $key=>$val){
			//处理医生
			$doctorinfo = '';
			$pattern = '/<p.*>(.*)<\/p>/U';
			if(preg_match_all($pattern, $val['doctorinfo'], $matches)){
				foreach($matches[1] as $subkey=>$subval){
					$img = '';
					$introduce = '';
					if($subval!='&nbsp;' && mb_strlen($subval,'utf-8')>=2 && mb_strlen($subval,'utf-8')<20){
						//print_r($subval);
						if(strpos($matches[1][$subkey-1],'img')!==false){
							$img = $matches[1][$subkey-1];
							preg_match_all("/(src)=[\"|\'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|bmp|png))[\"|\'| ]{0,}/isU", $img, $img_array);
							$img = $img_array[2][0];
							//print_r($img);
						}
						if(strlen($matches[1][$subkey+1])>10 && strpos($matches[1][$subkey+1],'img')===false){
							$introduce = $matches[1][$subkey+1];
						}
						$data[] = array(
							$val['name'],
							$subval,
							$introduce,
							$img,
							$val['hid']
						);
						//print_r($data);
					}

				}
			}

		}
		// generate file (constructor parameters are optional)
		$xls = new Excel('UTF-8', false, '医院-医师');
		$xls->addArray($data);
		$xls->generateXML('hospital-doctor');
	}
}
?>
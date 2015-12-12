<?php

/*
*敏感词管理
*/
class SensitiveAction extends ExtendAction{
	
	/*
	*列表页面
	*/
	public function index(){
		$BoqiiSensitiveWord = D('BoqiiSensitiveWord');
		$limit = 20;
		$where="word.status=0";
		
		$order = "word.id desc";

		$limit=20;
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		//$starttime = date('Y-m-d');
		$url='/iadmin.php/Sensitive/index?';

		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');
			if(!in_array($data['keyword'],$noAllow) && !empty($data['keyword'])){
				$where.=" and word.keyword like '%".$data['keyword']."%' ";
				$url.='data[keyword]='.urlencode($data['keyword']).'&';
				$this->assign('keyword',$data['keyword']);
			}
			if(trim($data['starttime'])){
				$where.=" and word.createtime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and word.createtime <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if(!in_array($data['username'],$noAllow) && !empty($data['username'])){
				$struids = D('UcAdmin')->getStrUidsByUsername($data['username']);
				if($struids) {
					$where.=" and word.uid IN (".$struids.')';
				}
				$url.='data[username]='.urlencode($data['username']).'&';
				$this->assign('username',$data['username']);
			}
		}

		$url.="page=";


		$wordCount = $BoqiiSensitiveWord->hasWordCount($where);
		$pcount = ceil($wordCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}

		$words = $BoqiiSensitiveWord->hasManyWord($page,$limit,$where,$order);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($words));

		$this->assign('url',$url.$page);
		$this->assign('words',$words);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->display('index');
	}

	/*
	*删除关键词（逻辑删除）
	*/
	public function deleteSensitive(){
		$ids = $this->_get('deleteSensitive');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$idArr = explode(',',$ids);
		$BoqiiSensitiveWord = D('BoqiiSensitiveWord');
		foreach($idArr as $key=>$val){
			if($val){
				$this->recordOperations(2,13,$val);
				$BoqiiSensitiveWord->where(array('id'=>$val))->save(array('status'=>-1));
			}
		}
		if(empty($act)){
			//$this->redirect('/iadmin.php/Sensitive/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}	

	/*
	* 导入关键词（xls）
	*/
	public function addSensitive(){

		$this->display('addSensitive');
	}

	/*
	* 导入关键词（xls）
	*/
	public function importSensitive(){
		// 文件上传成功
        if ($_FILES['file']['error'] == 0) {
        	// 判断是否是excel文件
      		$fileName = explode('.', $_FILES['file']['name']);
      		$fileType = strtolower($fileName[1]);
      		if (!in_array($fileType, array('xlsx','xls'))) {
      			alert('上传文件格式不符合！');
      		}
     		// 导入phpexcel类
        	Vendor('excel.PHPExcel');
        	$objPHPExcel = PHPExcel_IOFactory::load($_FILES["file"]["tmp_name"]);
		    //内容转换为数组 
		    $indata = $objPHPExcel->getSheet(0)->toArray();
		    $param['indata'] = !empty($indata) ? $indata : '';
		    // print_r($param['indata']);exit;
		 	D('BoqiiSensitiveWord')->addSensitiveList($param['indata']);
		 	$this->redirect('/iadmin.php/Sensitive/index');
        }
	}
	
	/*
	*ajax获取编辑内容
	*/
	public function ajaxKeyword(){
		$id = $this->_post('id');
		$BoqiiSensitiveWord = D('BoqiiSensitiveWord');
		$word = $BoqiiSensitiveWord->where(array('id'=>$id))->field('id,keyword')->select();
		echo json_encode($word);
	}

	/*
	*修改敏感词
	*/
	public function updateSensitive(){
		$data['id'] = $this->_post('id');
		$data['keyword'] = $this->_post('keyword');
		$BoqiiSensitiveWord = D('BoqiiSensitiveWord');
		$BoqiiSensitiveWord->save($data);
		echo 1;
		exit;
	}

	/*
	*创建新敏感词
	*/
	public function createSensitive(){
		$words = $this->_post('keyword');
		$arr = split ('[，,]', $words);
		$return = unsetNull($arr);
		$BoqiiSensitiveWord = D('BoqiiSensitiveWord');
		$boqiiUserId = session('boqiiUserId');
		foreach($return as $key=>$val){
			$data['keyword']=$val;
			$data['uid']=$boqiiUserId;
			$data['createtime']=time();
			$id = $BoqiiSensitiveWord->add($data);
			$this->recordOperations(1,13,$id);
		}
		echo 1;
		exit;

	}
}
?>
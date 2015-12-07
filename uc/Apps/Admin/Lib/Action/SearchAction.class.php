<?php
/**
 * 搜索Action控制器类
 *
 * @author: JasonJiang
 * @date: 2015-02-04
 */
class SearchAction extends ExtendAction {
	/**
	 * 关键词列表页
	 */
	public function keywordList () {
		$arrAssign = array();
		$searchModel = D('Search');
		
		$data = $this -> _get();

		// 分页参数
		$param['page']= $data['page'] ? $data['page'] : 1;
		$param['pageNum'] = 15;
		// 当前页url地址
		$url='/iadmin.php/Search/keywordList?';
		// 专题标题
		if($data['name'] && !in_array($data['name'],'请输入关键词名称')){
			$url.='name='.urlencode($data['name']).'&';
			$param['name'] = $data['name'];
			$this->assign('name',$data['name']);
		}
		
		// 专题类型
		if($data['type']){
			$url.='type='.$data['type'].'&';
			$param['type'] = $data['type'];
			$this->assign('type',$data['type']);
		}

		$orderUrl = $url;
		// 使用次数排序条件
		if($data['column']){
			$url.='column='.$data['column'].'&';
			$param['column'] = $data['column'];
			$orderUrl .= 'column='.$data['column'].'&';
			// 使用次数排序方式
			if($data['sort']){
				$url.='sort='.$data['sort'].'&';
				$param['sort'] = $data['sort'];
				if ($param['sort'] == 'desc') {
					$orderUrl .= 'sort=asc';
				}else{
					$orderUrl .= 'sort=desc';
				}
				$this->assign('orderUrl',$orderUrl);
			}
		}
		
		$url.="page=";
		// 获取字段
		$param['fields'] = 'id,name,create_time,adminid,type,num';

		// print_r($param);
		// echo '<br>';
		// 获取专题列表
		$keywordList = $searchModel -> getList($param);
		$this->assign('keywordList',$keywordList);
		// 分页数据
		if ($param['page'] > $searchModel->pagecount) {
			$param['page'] = $searchModel->pagecount;
		}
		$pageHtml = $this->page($url,$searchModel->pagecount, $param['pageNum'],$param['page'],$searchModel->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$param['page']);
		$this -> display('keywordList');
	}

	/**
	 * 新增专题/编辑专题页
	 */
	public function addKeyword () {
		
		// URL参数：关键词id
		$id = $this -> _get('id');

		// 编辑关键词
		if ($id){
			//获取专题
			$info = D('Search') -> getInfoByKeywordId($id);
			$this->assign('info',$info);
			$this->assign('page',$this->_get('page'));
			$this -> display('editKeyword');
		} 
		// 新增关键词
		else {
			
			$this -> display('addKeyword');
		}
	}
	
	/**
	 * 保存关键词
	 */
    public function saveKeyword () {

        $data = $this -> _post();
        $searchModel   = D('Search');
        // print_r($_FILES);exit;
        // 编辑和新增判断
        if ($data['id']) {
        	if (!empty($data['name'])) {
        		$param['name'] = trim($data['name']);
        		// 热门关键词是否存在
        		$keywordIsExists = $searchModel->getKeywordIsExists(array('name'=>$param['name'],'type'=>1));
	        	if ($keywordIsExists) {
	        		alert('该关键词已存在！');
	        	}
	        }else{
	        	alert('关键词名称不能为空！');
	        }
	        $keywordInfo = $searchModel->getInfoByKeywordIdTypeIsSelect($data['id'],'name');
	        // 判断改变的字段
			$arr = getChangeCloum($keywordInfo,$param);
	        $param['id'] = $data['id'];
        	$result = $searchModel -> editKeyword($param);
        	// 编辑关键词记录后台操作日志
        	if ($result['status'] == 'ok') {
        		foreach ($arr as $key=>$val) {
        			$this->recordOperations(3,35,$param['id'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
        		}
        	}
        }else{
        	 // 判断类型
        	$param['type'] = $data['type'];
        	$param['uid']  = session('boqiiUserId');
        	// 文件上传成功
	        if (!$data['name'] && $_FILES['file']['error'] == 0) {
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
			 
	        }else{
	        	
			    // 判断关键词
	        	if (!empty($data['name'])) {
		        	$param['name'] = trim($data['name']);
		        }else{
		        	alert('关键词名称不能为空！');
		        }
	        }
        	$result = $searchModel -> addKeyword($param);
        	// 添加关键词记录后台操作日志
        	if ($result['status'] == 'ok') {
        		foreach ($result['data'] as $val) {
        			$this->recordOperations(1,34,$val);
        		}
        	}
        }
        
       	if ($result['status'] == 'ok') {

           	showmsg($result['msg'],'/iadmin.php/Search/keywordList?page='.$data['page']);
       	}else {
           	alert($result['msg']);
       	}
    }

    /**
	 * ajax 删除专题
	 */
    public function ajaxDelKeyword () {
        $searchModel = D('Search');
        $ids = $this->_get('ajaxDelKeyword');
        $act = $this->_get('act');
       
       
        // 解析标签id串
		$idArr = explode(',',$ids);
        foreach($idArr as $key=>$val){
			if($val){
				$res = $searchModel->delKeyword($val);
				if ($res) {
					// 删除关键词记录后台操作日志
					$this->recordOperations(2,33,$val);
				}
			}
		}
		
		if(empty($act)){
			// $this->redirect('/iadmin.php/Tag/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
    }


    /**
     * 根据关键词名确认是否存在
     *
     * @param $kn string 关键词名称
     *          $kt int 关键词类型
     * 
     */
    public function ajaxCheckKeywordIsExists () {
        
      	$keywordName = $this->_get('kn');
       	$keywordType = $this->_get('kt');
       	// 热门关键词是否存在
		$keywordIsExists = D('Search')->getKeywordIsExists(array('name'=>$keywordName,'type'=>$keywordType));
		if ($keywordIsExists) {
			$data = array('status'=>'ok','msg'=>' *该关键词已存在！');
		}else{
			$data = array('status'=>'false');
		}
		$this->ajaxReturn($data,'JSON');
    }
}

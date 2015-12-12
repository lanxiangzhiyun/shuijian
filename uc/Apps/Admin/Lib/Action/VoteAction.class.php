<?php
/**
 * 投票Action类
 */
class VoteAction extends ExtendAction{
	/**
	 * 投票管理列表
	 */
	public function index(){
		$voteModel = D('Vote');
		
		//当前页
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		//每页显示条数
		$param['pageNum'] = 10;
		
		//url地址
		$url='/iadmin.php/Vote/index?';
		
		if($_GET['type']){
			$param['type'] = $_GET['type'];
			$url.='type='.$param['type'].'&';
		}
		
		$url.="page=";
		$list = $voteModel->getVoteList($param);
		$this->assign('list', $list);
		//print_r($list);
		if($param['page'] >= $voteModel->pagecount){
			$param['page'] = $voteModel->pagecount;
		}
		$pageHtml = $this->page($url,$voteModel->pagecount,$param['pageNum'],$param['page'],$voteModel->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		$this->assign('type',$param['type']);
		
		$this->display('index');
	}
	
	//显示编辑页面
	public function edit(){
		$id = $this->_get('id');
		if($id){
			$voteModel = D('Vote');
			$vote = $voteModel->getVoteDetail($id);
			$this->assign('vote',$vote);
		}
		$this->display('edit');
	}
	
	//处理编辑
	public function save(){
		header("Content-type: text/html; charset=utf-8"); 
		$voteModel = D('Vote');
		$id = $this->_post('id');
		if($id){
			$param['id'] = $id;
			$param['vote_num'] = $this->_post('vote_num');
			$voteModel->editVote($param);
		}
		echo "<script>alert('修改成功');location.href='/iadmin.php/Vote/index';</script>";
		exit;
	}
	
	//导入数据
	public function addVote(){
		$voteModel = D('Vote');
		$voteModel->addVoteInfo();
		echo 'OK';
	}

	/************************************** 品牌投票管理 Start ***************************************/
	/**
 	 * 品牌列表页面
	 */
	public function brandList(){
		$voteModel = D('Vote');
		
		//当前页
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		//每页显示条数
		$param['pageNum'] = 10;
		
		//url地址
		$url='/iadmin.php/Vote/brandList?';
		
		$url.="page=";
		$list = $voteModel->getBrandVoteList($param);
		$this->assign('list', $list);
		// print_r($list);
		if($param['page'] >= $voteModel->brandpagecount){
			$param['page'] = $voteModel->brandpagecount;
		}
		$pageHtml = $this->page($url,$voteModel->brandpagecount,$param['pageNum'],$param['page'],$voteModel->brandsubtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		
		$this->display('brandList');
	}

	/**
 	 * 品牌列表页面
	 */
	public function editBrand(){
		$sid = $this->_get('sid');
		if($sid){
			$voteModel = D('Vote');
			$brandInfo = $voteModel->getBrandVoteDetail($sid);
			$this->assign('brandInfo',$brandInfo);
		}
		$this->display('editBrand');
	}

	/**
 	 * 修改品牌信息
	 */
	public function saveBrand(){
		header("Content-type: text/html; charset=utf-8"); 
		// echo "<pre>";print_r($this->_post());exit;
		$voteModel = D('Vote');
		$sid = $this->_post('sid');
		if($sid){
			$param['sid'] = $sid;
			$param['joiners'] = $this->_post('joiners');
			$res = $voteModel->saveBrandVoteInfo($param);
			if ($res) {
				showmsg('修改成功！','/iadmin.php/Vote/brandList');
			}else{
				alert('修改失败！');
			}
		}else{
			alert('参数出错！');
		}
		
	}
	/************************************** 品牌投票管理 End ***************************************/

	/************************************** 养宠报名管理 Start ***************************************/
	/**
 	 * 养宠报名列表页面
	 */
	public function applyList(){
		$applyModel = D('Apply');
		
		// 当前页码
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		// 页显数量
		$param['pageNum'] = 10;
		
		// 当前页url地址
		$url='/iadmin.php/Vote/applyList?';
		// 活动类型
		$type = $this->_get('type');
		if ($type) {
			$param['type'] = $type;
			$url .= 'type='.$type.'&';
			$this->assign('type',$type);
		}
		
		$url .= "page=";
		$list = $applyModel->getApplyList($param);
		$this->assign('list', $list);
		// print_r($this->_get());
		if($param['page'] >= $applyModel->pagecount){
			$param['page'] = $applyModel->pagecount;
		}
		$pageHtml = $this->page($url,$applyModel->pagecount,$param['pageNum'],$param['page'],$applyModel->subtotal);
		// 类型
		$typeArr = array(1=>'Web端品牌养宠报名',2=>'App端品牌养宠报名',3=>'Web端狗狗竞赛报名',4=>'App端狗狗竞赛报名',5=>'H5志愿者报名',6=>'App端宠聚报名',7=>'手机号移动端报名',8=>'O2O送电影票报名',9=>'带我走点赞活动报名');
		$this->assign('typeArr',$typeArr);
		if ($applyModel->pagecount > 1) {
			$this->assign('pageHtml',$pageHtml);
		}
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		
		$this->display('applyList');
	}
	
	
	/************************************** 养宠报名管理 End ***************************************/
}
?>
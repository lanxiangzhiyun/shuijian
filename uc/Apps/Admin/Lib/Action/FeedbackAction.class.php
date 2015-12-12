<?php 
class FeedbackAction extends ExtendAction {
	//意见反馈列表
	public function feedbackList(){
		$feedback = D('BoqiiFeedback');
		
		$param['status'] = isset($_GET['status'])?$_GET['status']:99;
		//当前页
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		//每页显示条数
		$param['pageNum'] = 20;
		//url地址
		$url='/iadmin.php/Feedback/feedbackList?';
		
		if($_GET['type']){
			$param['type'] = $_GET['type'];
			$url.='type='.$param['type'].'&';
		}
		if(isset($_GET['status']) && $_GET['status'] != 99){
			$param['status'] = $_GET['status'];
			$url.='status='.$param['status'].'&';
		}
		if($_GET['starttime']){
			$param['starttime'] = $_GET['starttime'];
			$url.='starttime='.$param['starttime'].'&';
		}
		if($_GET['endtime']){
			$param['endtime'] = $_GET['endtime'];
			$url.='endtime='.$param['endtime'].'&';
		}
		if($_GET['keyword'] && $_GET['keyword'] != "输入反馈内容关键字"){
			$param['keyword'] = trim($_GET['keyword']);
			$url.='keyword='.urlencode($param['keyword']).'&';
		}
		if($_GET['slt_type'] && $_GET['user']){
			$param['slt_type'] = $_GET['slt_type'];
			$url.='slt_type='.$param['slt_type'].'&';
			$param['user'] = $_GET['user'];
			$url.='user='.$param['user'].'&';
		}
		if($_GET['act']){
			$param['act'] = $_GET['act'];
			$url.='act='.$param['act'].'&';
		}
		
		$url.="page=";
		
		$list = $feedback->getFeedbackList($param);
		$this->assign('list', $list);
		//print_r($list);
		if($param['page'] >= $feedback->pagecount){
			$param['page'] = $feedback->pagecount;
		}
		$pageHtml = $this->page($url,$feedback->pagecount,$param['pageNum'],$param['page'],$feedback->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		
		$this->assign('type',$param['type']);
		$this->assign('status',$param['status']);
		$this->assign('starttime',$param['starttime']);
		$this->assign('endtime',$param['endtime']);
		$this->assign('keyword',$param['keyword']);
		$this->assign('slt_type',$param['slt_type']);
		$this->assign('user',$param['user']);
		
		//导出excel
		if($param['act'] == "exportExcel"){
			import('@.ORG.Util.PhpExcel');
			$doc[] = array ('id','问题类型','图片','反馈信息','反馈时间','用户昵称','UID','联系方式','邮箱','状态','问题网址');
			foreach($list as $k=>$v){
				if($v['pic_path']) $pic_path = C('BLOG_DIR')."/".$v['pic_path'];
				$doc[] = array ($v['id'],$v['question_type'],$pic_path,$v['content'],date("Y-m-d H:i:s",$v['create_time']),$v['username'],$v['uid'],$v['tel'],$v['email'],$v['status'],$v['url']);
			}
			 
			$xls = new Excel_XML;
			$xls->addArray($doc);
			$xls->generateXML("feedback_".date("Y-m-d"));
			die;
		}
		
		$this->display('feedbackList');
	}
	
	//删除
	public function deleteFeedback(){
		$feedback = D('BoqiiFeedback');
		$ids = $this->_get('deleteFeedback');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$idArr = explode(',',$ids);
		foreach($idArr as $key=>$val){
			if($val){
				$this->recordOperations(2,17,$val);
				$feedback->delFeedback($val);
			}
		}
		if(empty($act)){
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}
	
	//批量修改状态
	public function updateBatchStatus(){
		$feedback = D('BoqiiFeedback');
		$idstr = $this->_get('idstr');
		$status = $this->_get('status');
		$idArr = explode(',',$idstr);
		foreach($idArr as $key=>$val){
			if($val){
				$param['id'] = $val;
				$param['status'] = $status;
				$feedback->editStatusByID($param);
			}
		}
		if(empty($idArr)){
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}
	
	//获取状态
	public function ajaxStatus(){
		$feedback = D('BoqiiFeedback');
		$id = $this->_post('id');
		$status = $feedback->getStatusByID($id);
		echo json_encode($status);
	}

	//修改状态
	public function updateStatus(){
		$feedback = D('BoqiiFeedback');
		$param['id'] = $this->_post('id');
		$param['status'] = $this->_post('status');
		$feedback->editStatusByID($param);
		echo 1;
		exit;
	}

}
?>
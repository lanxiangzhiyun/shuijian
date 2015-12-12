<?php 
class BoqiiFeedbackModel extends RelationModel {
	protected $tableName='boqii_feedback';
	
	//意见反馈列表
	public function getFeedbackList($param) {
		$type = $param['type'];
		$status = $param['status'];
		$starttime = $param['starttime'];
		$endtime = $param['endtime'];
		$keyword = $param['keyword'];
		$selectType = $param['slt_type'];
		$user = $param['user'];
		$act = $param['act'];
		$where = "status != -1";
		if(!empty($type)) {
			$where = $where ." and question_type = ".$type;
		}
		if(isset($status) && $status != 99) {
			$where = $where ." and status = ".$status;
		}
		if(!empty($starttime)) {
			$where = $where ." and create_time >= ".strtotime($starttime.' 00:00:00');
		}
		if(!empty($endtime)) {
			$where = $where ." and create_time <= ".strtotime($endtime.' 23:59:59');
		}
		if(!empty($keyword)) {
			$where = $where ." and content like '%".$keyword."%' ";
		}
		if(!empty($selectType)) {
			if($selectType == 1 && $user) {
				$where = $where ." and username like '%".$user."%' ";
			}else if($selectType == 2 && is_numeric($user)) {
				$where = $where ." and uid=".$user;
			}
		}

		if($act == "exportExcel"){
			$listarr =  M()->Table("boqii_feedback")->where($where)->order("create_time desc")->select();
		}else{
			$page = $param['page']?$param['page']:1;
			$pageNum = $param['pageNum']?$param['pageNum']:20;
			$pageStart = ($page-1)*$pageNum;
			//总记录数
			$this->total = M()->Table("boqii_feedback")->where($where)->count();
			$listarr =  M()->Table("boqii_feedback")->where($where)->order("create_time desc")->limit("$pageStart, $pageNum")->select();
			//echo M()->Table("boqii_feedback")->getLastSql();
			//当前页条数
			$this->subtotal = count($listarr);
			//总页数
			$this->pagecount = ceil(($this->total)/$pageNum);
		}
		$list = array();
		foreach($listarr as $lists){		
			switch($lists["question_type"]) {
				case 1:
					$lists["question_type"] = "个人中心";
					break;
				case 2:
					$lists["question_type"] = "波奇商城";
					break;
				case 3:
					$lists["question_type"] = "宠物社区";
					break;
				case 4:
					$lists["question_type"] = "波奇医院";
					break;
				case 5:
					$lists["question_type"] = "其他";
					break;
				case 6:
					$lists["question_type"] = "波奇百科";
					break;
				default:
					$lists["question_type"] = "其他";
			}
			
			switch($lists["status"]) {
				case 0:
					$lists["status"] = "待解决";
					break;
				case 1:
					$lists["status"] = "已解决";
					break;
				case 2:
					$lists["status"] = "暂缓";
					break;
				default:
					$lists["status"] = "待解决";
			}
				$list[] = $lists;
		}
		//print_r($list);
		return $list;
	}
	
	//删除
	public function delFeedback($id){
		$where = "id = ".$id;
		$data['status'] = -1;
		return M()->Table("boqii_feedback")->where($where)->save($data);
	}
	
	//查询当前反馈状态
	public function getStatusByID($id){
		$where = "id = ".$id;
		return M()->Table("boqii_feedback")->field('id,status')->where($where)->find();
	}
	
	//修改状态
	public function editStatusByID($param){
		$where = "id = ".$param['id'];
		$data['status'] = $param['status'];
		return M()->Table("boqii_feedback")->where($where)->save($data);
	}
	
}
?>
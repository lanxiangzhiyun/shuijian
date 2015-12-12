<?php
class SysMsgModel extends RelationModel {
	protected $tableName = "uc_notice";
	
	//站内信列表
	public function getMsgList($param) {
		$keyword = $param['keyword'];
		$starttime = $param['starttime'];
		$endtime = $param['endtime'];
		$slt_type = $param['slt_type'];
		
		$where = "`from` > 0 and `from` != 2";
		if(!empty($keyword)) {
			$where = $where ." and content like '%".$keyword."%' ";
		}
		if(!empty($starttime)) {
			$where = $where ." and dateline >= ".strtotime($starttime.' 00:00:00');
		}
		if(!empty($endtime)) {
			$where = $where ." and dateline <= ".strtotime($endtime.' 23:59:59');
		}
		if(!empty($slt_type)) {
			$where = $where ." and `from` = ".$slt_type." ";
		}
		
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['pageNum']?$param['pageNum']:20;
		$pageStart = ($page-1)*$pageNum;
		//总记录数
		$this->total = M()->Table("uc_notice")->where($where)->count();
		$listarr =  M()->Table("uc_notice")->where($where)->order("dateline desc")->limit("$pageStart, $pageNum")->select();
		//echo M()->Table("uc_notice a")->getLastSql();
		//当前页条数
		$this->subtotal = count($listarr);
		//总页数
		$this->pagecount = ceil(($this->total)/$pageNum);
		$list = array();
		foreach($listarr as $lists){		
			$lists["dateline"] = date('Y-m-d H:i',$lists["dateline"]);
			switch($lists["from"]) {
				case 1:
					$lists["from"] = "全站";
					break;
				case 2:
					$lists["from"] = "上海地区";
					break;
				case 3:
					$lists["from"] = "部分自定义";
					break;
				default:
					$lists["from"] = "所有";
			}
			$list[] = $lists;
		}
		//print_r($list);
		return $list;
	}
	
	//站内信详情
	public function getMsgDetailList($param) {
		$msgid = $param['msgid'];
		$keyword = $param['keyword'];
		$starttime = $param['starttime'];
		$endtime = $param['endtime'];
		$uid = $param['uid'];
		
		$where = "b.msgid = ".$msgid."";
		if(!empty($keyword)) {
			$where = $where ." and a.content like '%".$keyword."%' ";
		}
		if(!empty($starttime)) {
			$where = $where ." and a.dateline >= ".strtotime($starttime.' 00:00:00');
		}
		if(!empty($endtime)) {
			$where = $where ." and a.dateline <= ".strtotime($endtime.' 23:59:59');
		}
		if(!empty($uid)) {
			$where = $where ." and b.receverid = ".$uid."";
		}
		
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['pageNum']?$param['pageNum']:20;
		$pageStart = ($page-1)*$pageNum;
		//总记录数
		$this->total = M()->Table("uc_notice a")->join("uc_notice_detail b ON a.id = b.msgid")->where($where)->count();
		$listarr =  M()->Table("uc_notice a")->field("a.*,b.id,b.sendid,b.receverid,b.readstatus")->join("uc_notice_detail b ON a.id = b.msgid")->where($where)->order("dateline desc")->limit("$pageStart, $pageNum")->select();
		//echo M()->Table("uc_notice a")->getLastSql();
		//当前页条数
		$this->subtotal = count($listarr);
		//总页数
		$this->pagecount = ceil(($this->total)/$pageNum);
		$list = array();
		foreach($listarr as $lists){		
			$lists["dateline"] = date('Y-m-d H:i',$lists["dateline"]);
			$list[] = $lists;
		}
		//print_r($list);
		return $list;
	}
	
	//发送站内信
	public function addMsg($param) {
		$param['content']  = urldecode($param['content']);	
		if($param['user_type'] == 1){
			if($param['content']){
				$param['type'] = 0;
				$param['from'] = 1;
				$r = $this->addNotice($param);
			}
		}elseif($param['user_type'] == 2){
			$uidSH = $this->getUserInSH();
			if($param['content'] && $uidSH){
				$param['type'] = 1;
				$param['from'] = 2;
				$msgid = $this->addNotice($param);
				$r = $this->addNoticeDetail($uidSH,$msgid);
			}
		}elseif($param['user_type'] == 3){
			if($_FILES) {
				$uidArray = $this->getUidArray();
			}
			if($uidArray){
				$param['uids'] = implode(" ",$uidArray);
			}else{
				$param['uids'] = trim($param['uids']);
			}
			$status = $this->uidEmpty($param['uids']);
			if($param['content'] && $status){
				$param['type'] = 1;
				$param['from'] = 3;
				$msgid = $this->addNotice($param);
				$uidArr = explode(" ",$param['uids']);
				$r = $this->addNoticeDetail($uidArr,$msgid);
			}
		}
		return $r;
	}
	
	//查询uid是否存在
	public function uidExist($uid) {
		return M()->Table('boqii_users')->field('uid')->where("uid = ".$uid."")->find();
	}
	
	//站内信主表
	public function addNotice($param){
		$data['content'] = $param['content'];
		$data['type'] = $param['type'];
		$data['dateline'] = time();
		$data['from'] = $param['from'];
		$msgid = M()->Table('uc_notice')->add($data);
		return $msgid;
	}
	
	//判断输入或者导入的uid是否为空
	public function uidEmpty($uids) {
		$uidArr = explode(" ",$uids);
        $status = false;
		foreach($uidArr as $k=>$v) {
			if($v) {
				$exist = $this->uidExist($v);
				if($exist) {
					$status = true;
				}
			}
		}
		return $status;
	}
	
	//站内信扩展表
	public function addNoticeDetail($uidArr,$msgid){
		foreach($uidArr as $k=>$v) {
			if($v) {
				$exist = $this->uidExist($v);
				if($exist) {
					$data2['sendid'] = 1328680;
					$data2['receverid'] = $v;
					$data2['msgid'] = $msgid;
					$r = M()->Table('uc_notice_detail')->add($data2);
				}
			}
		}
		return $r;
	}
	//导入excel
	public function getUidArray() {
		$fileName = $_FILES['excel']['name'];
		$tmpName = $_FILES['excel']['tmp_name'];
		
		import('@.ORG.Util.Reader');
		$data = new Spreadsheet_Excel_Reader(); 
		$data->setOutputEncoding('gbk'); 
		
		//上传
		$uidArray = array();
		$filePath = 'Data/Excel/';
		mkdir($filePath,0777);
		$newFileName = 'excel_'.time();
		$extend = strrchr($fileName,'.');
		$newFullName = $newFileName.$extend;
		$uploadFile = $filePath.$newFullName;
		$r = move_uploaded_file($tmpName,$uploadFile);
		if($r){
			$files = $uploadFile;
			$data->read($files);
			for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
				if(trim($data->sheets[0]['cells'][$i][1])){
					$uid = trim($data->sheets[0]['cells'][$i][1]);
					$uidArray[] = $uid;
				}
			}
		}
		return $uidArray;
	}
	
	//上海地区的用户
	public function getUserInSH() {
		$cityID = '3101,3102,3103,3104,3105,3106,3107,3108,3109,3110,3111,3112,3113,3114,3115,3116,3117,3118,3119';
		$user = M()->Table('boqii_users a')->field('b.uid')->join('boqii_users_extend b ON a.uid = b.uid')->where("b.city_id in (".$cityID.")")->select();
		foreach($user as $uk=>$uv){
			$newArr[] = $uv['uid'];
		}
		return $newArr;
	}
	
}
?>
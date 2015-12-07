<?php
class SysMsgAction extends ExtendAction {
	//站内信列表
	public function msgList() {
		$msg = D('SysMsg');
		
		//当前页
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		//每页显示条数
		$param['pageNum'] = 20;
		//url地址
		$url='/iadmin.php/SysMsg/msgList?';
		$noAllow = C('NO_ALLOW');
		if($_GET['keyword'] && !in_array($_GET['keyword'],$noAllow)){
			$param['keyword'] = trim($_GET['keyword']);
			$url.='keyword='.urlencode($param['keyword']).'&';
		}
		if($_GET['starttime']){
			$param['starttime'] = $_GET['starttime'];
			$url.='starttime='.$param['starttime'].'&';
		}
		if($_GET['endtime']){
			$param['endtime'] = $_GET['endtime'];
			$url.='endtime='.$param['endtime'].'&';
		}
		if($_GET['slt_type']){
			$param['slt_type'] = $_GET['slt_type'];
			$url.='slt_type='.$param['slt_type'].'&';
		}
	
		$url.="page=";
		
		$list = $msg->getMsgList($param);
		$this->assign('list', $list);
		//print_r($list);
		if($param['page'] >= $msg->pagecount){
			$param['page'] = $msg->pagecount;
		}
		$pageHtml = $this->page($url,$msg->pagecount,$param['pageNum'],$param['page'],$msg->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		
		$this->assign('starttime',$param['starttime']);
		$this->assign('endtime',$param['endtime']);
		$this->assign('keyword',$param['keyword']);
		$this->assign('slt_type',$param['slt_type']);
		
		$this->display('msgList');
	}
	
	//站内信详情
	public function msgDetailList() {
		$msg = D('SysMsg');
		$param['msgid'] = $_GET['id'];
		//当前页
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		//每页显示条数
		$param['pageNum'] = 20;
		//url地址
		$url='/iadmin.php/SysMsg/msgDetailList?';
		$noAllow = C('NO_ALLOW');
		if($_GET['keyword'] && !in_array($_GET['keyword'],$noAllow)){
			$param['keyword'] = trim($_GET['keyword']);
			$url.='keyword='.urlencode($param['keyword']).'&';
		}
		if($_GET['starttime']){
			$param['starttime'] = $_GET['starttime'];
			$url.='starttime='.$param['starttime'].'&';
		}
		if($_GET['endtime']){
			$param['endtime'] = $_GET['endtime'];
			$url.='endtime='.$param['endtime'].'&';
		}
		if($_GET['uid'] && !in_array($_GET['uid'],$noAllow)){
			$param['uid'] = $_GET['uid'];
			$url.='uid='.$param['uid'].'&';
		}
		if($_GET['id']){
			$param['msgid'] = $_GET['id'];
			$url.='id='.$param['msgid'].'&';
		}
	
		$url.="page=";
		
		$list = $msg->getMsgDetailList($param);
		$this->assign('list', $list);
		//print_r($list);
		if($param['page'] >= $msg->pagecount){
			$param['page'] = $msg->pagecount;
		}
		$pageHtml = $this->page($url,$msg->pagecount,$param['pageNum'],$param['page'],$msg->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		
		$this->assign('starttime',$param['starttime']);
		$this->assign('endtime',$param['endtime']);
		$this->assign('keyword',$param['keyword']);
		$this->assign('uid',$param['uid']);
		$this->assign('id',$param['msgid']);
		
		$this->display('msgDetailList');
	}
	
	public function addMsg() {
		$msg = D('SysMsg');
		$this->display('addMsg');
	}
	
	//发送站内信
	public function sendMsg() {
		header("Content-type: text/html; charset=utf-8"); 
		//set_time_limit(0);
		$msg = D('SysMsg');
		$data = $_POST;
		$r = $msg->addMsg($data);
		if($r){
			echo "<script>alert('站内信发送成功');location.href='/iadmin.php/SysMsg/msgList';</script>";
		}else{
			echo "<script>alert('站内信发送失败');location.href='/iadmin.php/SysMsg/msgList';</script>";
		}
		exit;
	}
}
?>
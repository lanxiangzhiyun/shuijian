<?php
class UcMsgAction extends BaseAction {

	public function __construct() {
        parent::__construct();
    }
	
	//收件箱
	public function myInbox(){
		$msg = D('UcMsg');
		
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		if(!$param['uid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$allinbox = $msg->getInboxMsg($param);
		//print_r($allinbox);
		import("ORG.Page");
		$Page = new Page($msg->total, $param['page_num']);
		$this->assign('page', $Page->frontShow());
		$this->assign('p',intval($_GET['p']));
		$this->assign("obj", "me");
		$this->assign('allinbox',$allinbox);
		$this->display('myInbox');
	}
	
	//发件箱
	public function myOutbox(){
		$msg = D('UcMsg');
		
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		if(!$param['uid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$outinbox = $msg->getOutboxMsg($param);
		//print_r($outinbox);
		import("ORG.Page");
		$Page = new Page($msg->total, $param['page_num']);
		$this->assign('page', $Page->frontShow());
		$this->assign('p',intval($_GET['p']));
		$this->assign("obj", "me");
		$this->assign('outinbox',$outinbox);
		$this->display('myOutbox');
	}
	
	//站内信
	public function myNotice(){
		$msg = D('UcMsg');
		
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		if(!$param['uid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$notice = $msg->getNotice($param);
		//print_r($notice);
		$sss = $msg->getNoticeCount($param['uid']);
		import("ORG.Page");
		$Page = new Page($msg->total,$param['page_num']);
		$this->assign('page', $Page->frontShow());
		$this->assign('p',intval($_GET['p']));
		
		//同时更改已读状态
		$msg->updateNew($param['uid']);
		$this->assign("obj", "me");
		$this->assign('notice',$notice);
		$this->display('myNotice');
	}
	
	//搜索我的好友
	public function searchFriend(){
		$msg = D('UcRelation');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$arr = $msg->getFriendsForSendNews($param);
		//print_r($arr);die;
		$this->ajaxReturn($arr,'JSON');
	}
	
	//回复消息
	public function replyMsg(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		if(!$this->checkUserGroup()){
			$data['status'] = 'forbidden';
			$this->ajaxReturn($data,'JSON');
		}
		if($userinfo['uid']){
			$_POST['uid'] = $userinfo['uid'];
			//上一级消息id
			$_POST['pid'] = $_POST['pid'];
			$_POST['receverid'] = $_POST['receverid'];
			$_POST['content'] = trim(stripslashes($_POST['content']));
			if($_POST['content']){
				if(strlen_weibo($_POST['content']) > 150){
					$data['status'] = 'max';
				}else{
					$r = $msg->reciveMsg($_POST);
					if($r == 0){
						//回复者不能为空
						$data['status'] = 'idempty';
					}elseif($r == -1){
						//根据对方设置，你不能进行操作
						$data['status'] = 'black';
					}elseif($r == -2){
						//请先解除黑名单再进行操作
						$data['status'] = 'delblack';
					}else{
						$data['status'] = 'ok';
					}
				}
			}else{
				$data['status'] = 'empty';
			}
		}else{
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data,'JSON');
	}
	
	//更改收件箱已读状态
	public function updateStatus(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		if($userinfo['uid']){
			$id = $_GET['id'];
			$msg->readMsg($id);	
		}
	}
	
	//发消息
	public function publishMsg(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['receverid'] = $_REQUEST['receverid'];
		$param['content'] = trim($_REQUEST['content']);
		if(!$this->checkUserGroup()){
			$data['status'] = 'forbidden';
			$this->ajaxReturn($data,'JSON');
		}
		if($param['uid']){
			if($param['receverid'] and $param['content']){
				if(strlen_weibo($param['content']) > 150){
					$data['status'] = 'max';
				}else{
					$r = $msg->sendMsg($param);
					if($r == -1){
						//根据对方设置，你不能进行该操作
						$data['status'] = 'black';
					}elseif($r == -2){
						//请先解除黑名单再进行操作
						$data['status'] = 'delblack';
					}elseif($r == 0){
						//该用户不存在，你不能进行该操作
						$data['status'] = 'none';
					}else{
						$data['status'] = 'ok';
					}
				}
			}else{
				//发件人和内容不能为空
				$data['status'] = 'empty';
			}
		}else{
			$data['status'] = 'login';
		}
		
		$callback = isset($_GET['callback']) ? $_GET['callback'] : '';
		// JSONP 形式的回调函数来加载其他网域的 JSON 数据
		if (!empty($callback)) {
			echo $_GET['callback'].'('.json_encode($data).')';
		} else {
			$this->ajaxReturn($data,'JSON');
		}
	}
	
	//删除站内信(后期备用)
	public function noticeDel(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['msgid'] = $_GET['msgid'];
		if($param['msgid']){
			//判断登陆
			if($param['uid']){
				$r = $msg->delNotice($param);
				if($r){
					$data['status'] = 'ok';
				}else{
					$data['status'] = 'false';
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		
		$this->ajaxReturn($data,'JSON');
	}
	
	//发件箱单个删除
	public function msgDel(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['msgid'] = $_GET['msgid'];
		$param['type'] = 2;
		if($param['msgid']){
			//判断登陆
			if($param['uid']){
				$r = $msg->delMsg($param);
				if($r){
					$data['status'] = 'ok';
				}else{
					$data['status'] = 'false';
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		
		$this->ajaxReturn($data,'JSON');
	}
	
	//收件箱单个删除
	public function msgInDel(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['msgid'] = $_GET['msgid'];
		$param['checkblack'] = $_GET['checkblack'];
		$param['type'] = 1;
		if($param['msgid']){
			//判断登陆
			if($param['uid']){
				$r = $msg->delMsg($param);
				if($r){
					$data['status'] = 'ok';
				}else{
					$data['status'] = 'false';
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		
		$this->ajaxReturn($data,'JSON');
	}
	
	//发件箱批量删除
	public function msgsDel(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		//print_r($_POST);die;
		$msgids = implode(',',$_POST);
		$param['msgid'] = $msgids;
		$param['type'] = 2;
		
		if($param['uid']){
			$r = $msg->delMsgs($param);
			if($r){
				$data['status'] = 'ok';
			}else{
				$data['status'] = 'false';
			}
		}else{
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data,'JSON');
	}
	
	//收件箱批量删除
	public function msgsInDel(){
		$msg = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$msgids = implode(',',$_POST);
		$param['msgid'] = $msgids;
		$param['checkblack'] = $_GET['checkblack'];
		$param['type'] = 1;
		//echo $param['msgid'],$param['checkblack'];
		if($param['uid']){
			$r = $msg->delMsgs($param);
			if($r){
				$data['status'] = 'ok';
			}else{
				$data['status'] = 'false';
			}
		}else{
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data,'JSON');
	}
	
}
?>
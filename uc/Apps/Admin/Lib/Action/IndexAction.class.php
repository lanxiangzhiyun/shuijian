<?php
/*
*管理系统首页控制器
*/
class IndexAction extends ExtendAction{

	/*
	*主体页面
	*/
	public function index(){
		$username = session('boqiiUserName');
		$this->assign('username',$username);
		$this->display('index');
	}

	/*
	*左侧菜单栏目
	*/
	public function menu(){
		$this->display('menu');
	}

	/*
	*登陆页面
	*/
	public function login(){
		$this->display('login');
	}

	/*
	*用户名密码验证
	*/
	public function loginCheck(){
		$data['username'] = $this->_post('username');
		$ucAdmin = D('UcAdmin');
		// 通过用户名查看是否有该用户
		$uid = $ucAdmin->where(array('username'=>$data['username']))->getField('id');
		if(!$uid) echo 0;
		$password = trim($this->_post('password'));
		$data['password'] = md5($uid,md5($password));
		$data['status'] = 0;
		$Admin = $ucAdmin->where($data)->find();
		if($Admin){
			//修改登录时间
			$ucAdmin->where('id='.$Admin['id'])->save(array('logintime'=>time()));
			session('boqiiUserId',$Admin['id']);
			session('boqiiUserName',$Admin['username']);
			session('boqiiTrueName',$Admin['truename']);
			if(in_array($Admin['id'],array(1,35))){
				$arr = C('RBAC');
				$keys = array_keys($arr);
				session('boqiiOperation',implode(',',$keys));
			}else{
				session('boqiiOperation',$Admin['operation']);
			}

			//后台IP登陆限制
			$ip = get_client_ip();
			$session_key = session_id().$ip;
			$_SESSION[$session_key] = 1;

			echo 1;
		}else{
			echo 0;
		}
		exit;
	}

	/*
	*管理员退出
	*/
	public function loginOut(){
			session('boqiiUserId',null);
			session('boqiiUserName',null);
			$this->redirect('/iadmin.php/Index/login');
	}

	/*
	*修改密码页面
	*/
	public function modifyPasswordPage(){
		$this->display('modifyPasswordPage');
	}

	/*
	*获得外部传值 发送站内信
	*/
	public function getNotice(){
		$object_id=$this->_get('object_id');
		$to_uid=$this->_get('to_uid');
		$notice_type=$this->_get('notice_type');
		$type=$this->_get('type');
		$param = $this->_get('param');
		$this->setNotice($object_id,$to_uid,$notice_type,$type,$param);
	}
	/*
	*提交修改b4f49f54ef1f263a536205d2b6a448b1
	*/
	public function modifyPassword(){

		$ucAdmin = D('UcAdmin');
		$boqiiUserName = session('boqiiUserName');
		$boqiiUserId = session('boqiiUserId');
		$password = doubleMd5($boqiiUserId,md5($this->_post('password')));

		$newpassword = doubleMd5($boqiiUserId,md5($this->_post('newpassword')));

		$admin = $ucAdmin->where(array('username'=>$boqiiUserName,'password'=>$password))->find();
		if(empty($admin)){
			echo 3;
			exit;
		}
		$result = $ucAdmin->where(array('id'=>$boqiiUserId))->save(array('password'=>$newpassword));
		session('boqiiUserId',null);
		session('boqiiUserName',null);
		echo 1;
		exit;
	}

	public function mkdirtest(){
		error_reporting(E_ALL);
		$reuslt = mkdir('/webwww/baike1', 0777);
		print_r($result);exit;
	}
}

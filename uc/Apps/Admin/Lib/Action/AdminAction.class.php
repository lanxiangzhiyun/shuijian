<?php
/*
*用户管理
*/
class AdminAction extends ExtendAction{
		
	/*
	*用户列表
	*/
	public function index(){
		
		$limit = 20;
		$where ='status=0 and id not in (1,'.session('boqiiUserId').')';
		$ucAdmin=D('UcAdmin');
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$adminCount = $ucAdmin->hasAdminCount($where);
		$pcount = ceil($adminCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$admins = $ucAdmin->hasManyAdmin($page,$limit,$where);

		$url = "/iadmin.php/Admin/index?page=";
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($admins));
		$RBAC = C('RBAC');
		foreach($admins as $key=>$val){
			$operation = explode(',',$val['operation']);
			unset($str);
			foreach($operation as $k=>$v){
				$str.=','.$RBAC[$v];
			}
			$admins[$key]['operationname'] = substr($str,1);
		}
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('admins',$admins);
		$this->display('index');
	}
	
	/*
	*后台用户删除
	*/
	public function deleteAdmin(){
		$ids = $this->_get('deleteAdmin');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$idArr = explode(',',$ids);
		$ucAdmin=D('UcAdmin');
		foreach($idArr as $key=>$val){
			if($val){	
				$this->recordOperations(2,15,$val);
				$ucAdmin->where(array('id'=>$val))->save(array('status'=>-1));
			}
		}
		if(empty($act)){
			$this->redirect('/iadmin.php/Admin/index?page='.$page);
		}else{
			echo 1;
			exit;
		}
	}

	/*
	*用户添加页面
	*/
	public function addPage(){

		if($this->_get('id')){
			$ucAdmin=D('UcAdmin');
			$admin = $ucAdmin->where(array('id'=>$this->_get('id')))->select();
			$this->assign('admin',$admin);
		}
	
		$RBAC = C('RBAC');
		$this->assign('RBAC',$RBAC);
		$this->display('addPage');
	}

	/*
	*添加编辑后台用户
	*/
	public function editAdmin(){
		$ucAdmin=D('UcAdmin');
		$data = $this->_post('data');
		if($this->_post('operation')){
			$data['operation']=implode(',',$this->_post('operation'));
		}

		//判断前后两次代码是不是输入一致
		if($data['password']==$this->_post('againpassword')){
			//判断用户名是否为空
			if(empty($data['username'])){
				// echo "<script>alert('用户名不能为空');history.back();</script>";
				alert('用户名不能为空！');
				exit;
			}
			if(empty($data['truename'])){
				// echo "<script>alert('真实姓名不能为空');history.back();</script>";
				alert('真实姓名不能为空！');
				exit;
			}
			if($data['password']){
				$data['password']=md5($data['password']);
			}else{
				unset($data['password']);
			}
			if($data['id']){
				// $data['password'] = md5(sprintf('%s%s%s',$data['id'],$data['password'],'bq@%(*%#)pwd*^!~$$@#'));
				$data['password'] = doubleMd5($data['id'],$data['password']);
				$admin = $ucAdmin->where(array('id'=>$data['id']))->field('id,username,truename,operation')->select();
				foreach($admin as $key=>$val){
					foreach($data as $k=>$v){
						if($v!=$val[$k]){
							$arr[$k]['column']=$k;
							$arr[$k]['beforeContent']=$val[$k];
							$arr[$k]['afterContent']=$v;
						}
					}
				}
				foreach($arr as $key=>$val){
						$this->recordOperations(3,15,$data['id'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
				}
				$ucAdmin->save($data);
				$this->redirect('/iadmin.php/Admin/index');
			}else{
				if($data['password']){
					$data['createtime']	= time();
					$data['logintime']	= time();
					$id = $ucAdmin->add($data);
					// 再次加密用户密码
					// $password = md5(sprintf('%s%s%s',$id,$data['password'],'bq@%(*%#)pwd*^!~$$@#'));
					$password = doubleMd5($id,$data['password']);
					$ucAdmin->where(array('id'=>$id))->save(array('password'=>$password));
					$this->recordOperations(1,15,$id);
					$this->redirect('/iadmin.php/Admin/index');
				}else{
					// echo "<script>alert('请输入密码！');history.back();</script>";	
					alert('请输入密码！');
				}
			}
		}else{
			// echo "<script>alert('你输入的密码不一致或者为空，请重新输入！');history.back();</script>";	
			alert('你输入的密码不一致或者为空，请重新输入！');
		}
		exit;
	}
	
	/*
	*密码重置
	*/
	public function resetPassword(){
		$ucAdmin=D('UcAdmin');
		$ids = $this->_post('id');
		$arr = explode(',',$ids);
		
		foreach($arr as $key=>$val){
			if($val){
				$password = doubleMd5($val,md5('123456'));
				$ucAdmin->where(array('id'=>$val))->save(array('password'=>$password));
			}
		}
		echo 1;
		exit;
	}
}
?>
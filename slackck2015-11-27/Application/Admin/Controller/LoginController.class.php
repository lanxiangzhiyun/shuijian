<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller {
	//登入页面
    public function login(){
		$this->display();
    }

    //登陆验证
	public function runlogin(){
		if (!IS_AJAX){
			$this->error("提交方式错误！",0,0);
		}else{
			$admin_username=I('admin_username');
			$admin_pwd=md5(I('admin_pwd'));
			
			$admin=M('admin')->where(array('admin_username'=>$admin_username,'admin_pwd'=>$admin_pwd))->find();
				if (!$admin||$admin_pwd!==$admin['admin_pwd']){
					$this->error('用户名或者密码错误，重新输入',0,0);
				}else{
					//登录后更新数据库，登录IP，登录次数,登录时间
					$data=array(
							'admin_ip'=>get_client_ip(),
					);
					M('admin')->where(array('admin_username'=>$admin_username))->setInc('admin_hits',1);
					M('admin')->save($data);
					cookie('aid',$admin['admin_id'],86400); // 指定cookie保存时间1天
					cookie('admin_username',$admin['admin_username'],86400); // 指定cookie保存时间1天
					cookie('admin_realname',$admin['admin_realname'],86400); // 指定cookie保存时间1天
					$this->success('恭喜您，登陆成功',1,1);
				}
		}
	}
	
	//注册验证
	public function reg(){
		$admin=M('admin');
		$vername=$admin->where(array('admin_username'=>I('admin_username')))->find();
		if ($vername){
			$this->error('用户已存在，请重新输入',0,0);
		}else{		
			$info=array(
				'admin_email'=>I('admin_email'),
				'admin_username'=>I('admin_username'),
				'admin_pwd'=>md5(I('admin_pwd')),
				'admin_addtime'=>time(),
			);
			$admin->add($info);
			$this->success('恭喜您，注册成功,等待审核',U('login'),1);
		}
	
	}
    
	public function logout(){
		cookie('aid',null);
		$this->redirect('Login/login');
	}
	
	//测试邮件发送
	public function runemail(){
		$emailsys=M('sys')->where(array('sys_id'=>1))->find();
	    $config = array(
			'MAIL_FROM'=>$emailsys['email_name'],
			'MAIL_HOST'=>$emailsys['email_smtpname'],
			'MAIL_USERNAME'=>$emailsys['email_emname'],
			'MAIL_FROMNAME'=>$emailsys['email_rename'],
			'MAIL_PASSWORD'=>$emailsys['email_pwd'],
		);
    	C($config);
		$admin=M('admin')->where(array('admin_email'=>I('email')))->find();
		
		if(!$admin){
			$this->error('邮件不存在，请重新输入',0,0);
		}
		$oldnum=rand(10000,99999);//获取一串随机数
		$num=md5($oldnum);//对随机数进行加密后传递
		$emailpwd=M('admin')->where(array('admin_email'=>I('email')))->setField('admin_mdemail',$num);//更新数据库
		$content="尊敬的用户，您好：<br>您当前的操作为找回密码，请点击以下链接重新设置密码<br><a href=http://127.0.0.1/newadmin/index.php/Admin/Login/checkpwd/emailpwd/$num.html>http://127.0.0.1/newadmin/index.php/Admin/Login/checkpwd/emailpwd/$num.html</a>";
		if(SendMail($_POST['email'],'找回密码服务',$content))
			$this->success('邮件发送成功！，打开邮件重新设置密码',1,1);
		else
			$this->error('邮件发送失败',0,0);
		}
	
	//打开修改密码页面
	public function checkpwd(){
		$admin_mdemail=I('emailpwd');
		$this->assign('admin_mdemail',$admin_mdemail);
		$this->display();
	}

	//修改密码操作
	public function runcheckpwd(){
		$admin_mdemail=I('admin_mdemail');//获取加密过后的随机值
		$admin_pwd=I('admin_pwd','','md5');//获取新密码，并且加密
		$checkadmin=M('admin')->where(array('admin_mdemail'=>$admin_mdemail))->find();//验证用户是否存在
		if(!$checkadmin){
			$this->error('邮箱不存在，请重新输入',0,0);
		}else{
			$admin=M('admin')->where(array('admin_mdemail'=>$admin_mdemail))->setField('admin_pwd',$admin_pwd);
			$this->success('恭喜您，密码修改成功',U('login'),1);
		}

	}


}
<?php
/*
*管理系统首页控制器
*/
class IndexAction extends ExtendAction{

    /*
    *主体页面
    */
    public function index(){
        $username = session('shuijianUserName');
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
        $data['admin_username'] = $this->_post('username');
        $sjAdmin = M('shuijian_admin');
        // 通过用户名查看是否有该用户
        $uid = $sjAdmin->where(array('admin_username'=>$data['admin_username']))->getField('admin_id');
        //echo M()->getLastSql();

        if(!$uid) echo 0;
        $password = trim($this->_post('password'));
        $data['admin_pwd'] = md5($password);
        $data['admin_open'] = 1;
        $Admin = $sjAdmin->where($data)->find();
        //echo M()->getLastSql();
        if($Admin){
            //修改登录时间
            $sjAdmin->where('admin_id='.$Admin['admin_id'])->save(array('admin_lasttime'=>time()));
            session('sjUserId',$Admin['admin_id']);
            session('sjUserName',$Admin['admin_username']);
            session('sjTrueName',$Admin['admin_realname']);
//            if(in_array($Admin['admin_id'],array(1,35))){
//                $arr = C('RBAC');
//                $keys = array_keys($arr);
//                session('boqiiOperation',implode(',',$keys));
//            }else{
//                session('boqiiOperation',$Admin['operation']);
//            }

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
        session('sjUserId',null);
        session('sjUserName',null);
        $this->redirect('/iadmin.php/Index/login');
    }

    /*
    *修改密码页面
    */
    public function modifyPasswordPage(){
        $this->display('modifyPasswordPage');
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
}

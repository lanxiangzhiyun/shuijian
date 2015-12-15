<?php
/*
*管理系统首页控制器
*/
class SysAction extends ExtendAction{

    /*
    *主体页面
    */
    public function admin_list(){
        $username = session('shuijianUserName');
        $this->assign('username',$username);
        $this->display('admin_list');
    }
}

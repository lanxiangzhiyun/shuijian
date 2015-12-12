<?php
/**
 *  登陆
 * @author fanghui
 * @date 2015/12/01
 *
 */
class LoginAction extends Action {
    /**
     * 首页
     *
     */
    public function index(){
        $this->display('Login');
    }

    public function forget(){
        $this->display('Forget');
    }
}

<?php
/*
*授权控制器
*/
class AuthAction extends ExtendAction{

    /*
    *主体页面
    */
    public function auth_list($adminID){
        $arrAssign = array();
        $menuModel = D('Menu');
        $adminModel = D('Admin');
        $data = $this -> _get('data');
        $arrAssign['data'] = $data;
        // 字段
        $data['fields'] = '*';

        // 获取列表
        $arrList = $menuModel -> getList($data);
        $adarrList = $adminModel -> getActionList($adminID);
        $arrAssign['arrList'] = $arrList;
        $arrAssign['adarrList'] = $adarrList[0];

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        $this->display('auth_list');
    }

    public function auth_menus(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $adminModel = D('Admin');
        $result = $adminModel -> setActionList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');</script>";
        }
    }
}

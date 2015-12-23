<?php
/*
*菜单控制器
*/
class MenuAction extends ExtendAction{

    /*
    *主体页面
    */
    public function menu_list(){
        $arrAssign = array();
        $menuModel = D('Menu');
        $data = $this -> _get('data');
        $arrAssign['data'] = $data;
        // 字段
        $data['fields'] = '*';

        // 获取列表
        $arrList = $menuModel -> getList($data);
        $arrAssign['arrList'] = $arrList;

        $this->assign('menuLA_l1',array_filter($arrList,function($var){
            if($var['menu_level'] == 1)
                return $var;
        }));
        $this->assign('menuLA_l2',array_reverse(array_filter($arrList,function($var){
            if($var['menu_level'] == 2)
                return $var;
        })));

        $this->display('menu_list');
    }

    public function add1(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $menuModel = D('Menu');
        $result = $menuModel -> addList1($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');window.location.reload();</script>";
        }
    }

    public function add2(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $menuModel = D('Menu');
        $result = $menuModel -> addList2($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');window.location.reload();</script>";
        }
    }
}

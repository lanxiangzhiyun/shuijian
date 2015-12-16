<?php
/*
*管理系统首页控制器
*/
class SysAction extends ExtendAction{

    /*
    *主体页面
    */
    public function admin_list(){
        $arrAssign = array();
        $adminModel = D('Admin');
        $data = $this -> _get('data');
        $arrAssign['data'] = $data;
        //分页参数
        $data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
        $data['pageNum'] = 10;
        // 字段
        $data['fields'] = 'id,title,url,sort,type,create_time';

        // 当前url地址
        $url='/iadmin.php/Sys/admin_list?';
        // 查询条件
        $url.="page=";

        // 获取列表
        $arrList = $adminModel -> getList($data);
        $arrAssign['arrList'] = $arrList;
        // 获取分页信息
        $pageHtml = $this->page($url,$adminModel->pagecount, $data['pageNum'],$data['page'],$adminModel->subtotal);
        $this->assign('pageHtml',$pageHtml);

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        $this->display('admin_list');
    }
}

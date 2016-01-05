<?php
/*
*商户控制器
*/
class ShopAction extends ExtendAction{

    /*
    *主体页面
    */
    public function shop_list(){
        $arrAssign = array();
        $shopModel = D('Shop');
        $data = $this -> _get('data');
        $arrAssign['data'] = $data;
        //分页参数
        $data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
        $data['pageNum'] = 10;
        // 字段
        $data['fields'] = '*';

        // 当前url地址
        $url='/iadmin.php/Shop/shop_list?';
        // 查询条件
        $url.="page=";

        // 获取列表
        $arrList = $shopModel -> getList($data);
        $arrAssign['arrList'] = $arrList;

        // 获取分页信息
        $pageHtml = $this->page($url,$shopModel->pagecount, $data['pageNum'],$data['page'],$shopModel->subtotal);
        $this->assign('pageHtml',$pageHtml);

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        $this->display('shop_list');
    }

    public function admin_edit(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $adminModel = D('Admin');
        $result = $adminModel -> addList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /**
     * 删除
     */
    public function ajaxDelList () {
        $adminModel = D('Admin');
        // 待删除友链id字符串（英文逗号串接）
        $ids = $this->_post('ajaxDelList');

        // 分割友链id字符串
        $idArr = array_filter(explode(',',$ids));
        // 删除友链
        $result =$adminModel -> delList($idArr) ;
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

}

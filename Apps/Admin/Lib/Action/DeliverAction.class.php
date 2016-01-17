<?php
/*
*物流管理控制器
*/
class DeliverAction extends ExtendAction{

    /*
    *主体页面
    */
    public function deliver_list(){
        $arrAssign = array();
        $deliverModel = D('Deliver');
        $data = $this -> _get('data');
        $shopcity = $this -> _get('shop_city');
        $shopName = $this -> _get('shopName');
        $arrAssign['data'] = $data;
        //分页参数
        $data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
        $data['pageNum'] = 10;

        $data['shopcity'] = $shopcity;
        $data['shopName'] = $shopName;
        // 字段
        $data['fields'] = '*';

        // 当前url地址
        $url='/iadmin.php/Deliver/deliver_list?';
        // 查询条件
        $url.="page=";

        // 获取列表
        $arrList = $deliverModel -> getList($data);
        $arrAssign['arrList'] = $arrList;

        // 获取分页信息
        $pageHtml = $this->page($url,$deliverModel->pagecount, $data['pageNum'],$data['page'],$deliverModel->subtotal);
        $this->assign('pageHtml',$pageHtml);

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        //获取商铺列表
        $shopModel = D('Shop');
        $dat_c = array();
        $dat_c['fields'] = '*';
        $shopList = $shopModel -> getList($dat_c);
        $this -> assign('shopList',$shopList);

        $this->display('deliver_list');
    }

    public function deliver_edit(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $deliverModel = D('Deliver');
        $result = $deliverModel -> addList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success','data'=>$result));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /**
     * 删除
     */
    public function ajaxDelList () {
        $deliverModel = D('Deliver');
        // 待删除友链id字符串（英文逗号串接）
        $ids = $this->_post('id');

        // 分割友链id字符串
        $idArr = array_filter(explode(',',$ids));
        // 删除友链
        $result =$deliverModel -> delList($idArr) ;
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }


    public function name_unique_check(){

        $value = $this -> _post('value');
        $siteModel = D('Site');
        $result = $siteModel -> nameuniquecheck($value);
        $this->ajaxReturn($result);
    }
}

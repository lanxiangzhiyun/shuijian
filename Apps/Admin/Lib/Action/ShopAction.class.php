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

        //获取城市列表
        $cityModel = D('City');
        $dat_c = array();
        $dat_c['fields'] = '*';
        $cityList = $cityModel -> getList($dat_c);
        $this -> assign('cityList',$cityList);

        $this->display('shop_list');
    }

    public function shop_edit(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $shopModel = D('Shop');
        $result = $shopModel -> addList($data);
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
        $shopModel = D('Shop');
        // 待删除友链id字符串（英文逗号串接）
        $ids = $this->_post('id');

        // 分割友链id字符串
        $idArr = array_filter(explode(',',$ids));
        // 删除友链
        $result =$shopModel -> delList($idArr) ;
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }


    public function name_unique_check(){

        $value = $this -> _post('value');
        $shopModel = D('Shop');
        $result = $shopModel -> nameuniquecheck($value);
        $this->ajaxReturn($result);
    }

    public function ship_time_add(){
        $data['shopId'] = $this -> _post('shopId');
        $data['weekDays'] = implode(',',$this -> _post('weekDays'));
        $data['todayArrive'] = $this -> _post('todayArrive');
        $data['sendAfterDays'] = $this -> _post('sendAfterDays');
        $data['todayTimeBeginHour'] = $this -> _post('startHour');
        $data['todayTimeBeginMinute'] = $this -> _post('startMinute');
        $data['todayTimeBeginSecond'] = $this -> _post('startSecond');
        $data['sendTimeBeginHour'] = $this -> _post('sendTimeBeginHour');
        $data['sendTimeBeginMinute'] = $this -> _post('sendTimeBeginMinute');
        $data['sendTimeEndHour'] = $this -> _post('sendTimeEndHour');
        $data['sendTimeEndMinute'] = $this -> _post('sendTimeEndMinute');
        $data['chooseCount'] = $this -> _post('chooseCount');
        $ShopShipTimeModel = D('ShopShipTime');
        $result = $ShopShipTimeModel -> addList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    public function ship_time_edit(){
        $data['id'] = $this -> _post('shipTimeId');
        $data['shopId'] = $this -> _post('shopId');
        $data['weekDays'] = implode(',',$this -> _post('weekDays'));
        $data['todayArrive'] = $this -> _post('todayArrive');
        $data['sendAfterDays'] = $this -> _post('sendAfterDays');
        $data['todayTimeBeginHour'] = $this -> _post('startHour');
        $data['todayTimeBeginMinute'] = $this -> _post('startMinute');
        $data['todayTimeBeginSecond'] = $this -> _post('startSecond');
        $data['sendTimeBeginHour'] = $this -> _post('sendTimeBeginHour');
        $data['sendTimeBeginMinute'] = $this -> _post('sendTimeBeginMinute');
        $data['sendTimeEndHour'] = $this -> _post('sendTimeEndHour');
        $data['sendTimeEndMinute'] = $this -> _post('sendTimeEndMinute');
        $data['chooseCount'] = $this -> _post('chooseCount');
        $ShopShipTimeModel = D('ShopShipTime');
        $result = $ShopShipTimeModel -> addList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }


    public function  map($shopid){
        $this->display('shop_map');
    }
}

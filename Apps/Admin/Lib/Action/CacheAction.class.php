<?php
/*
*缓存控制器
*/
class CacheAction extends ExtendAction{

    public function all_city(){
        //获取城市列表
        $cityModel = D('City');
        $dat_c = array();
        $dat_c['fields'] = '*';
        $cityList = $cityModel -> getList($dat_c);
        $this -> ajaxReturn($cityList);
    }

    public function shops_by_city_id($cityid){
        //获取商铺列表
        $shopModel = D('Shop');
        $dat_c = array();
        $dat_c['fields'] = '*';
        $dat_c['shopcity'] = $cityid;
        $shopList = $shopModel -> getList($dat_c);
        $this -> ajaxReturn($shopList);
    }

}

<?php
/**
 * 商户管理
 *
 * @author: fanghui
 * @created:
 */
class ShopModel extends Model {

    protected $trueTableName = 'shuijian_shop';

    //商户列表
    public function getList($param) {
        //分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;

        $where = "1=1";

        if($param['shopcity']>-1){
            $where .= " and shop_city = ".$param['shopcity'];
        }

        if($param['shopName']){
            $where .= " and shop_name like '%".$param['shopName']."%'";
        }

        $arrList = $this->join(' shuijian_city ON shuijian_city.city_id = shuijian_shop.shop_city') ->join(' shuijian_shopShipTime c ON c.shopid = shuijian_shop.shop_id')-> where ($where) -> limit($pageNum) -> page ($page) ->field($param['fields']) ->order('shop_id DESC')-> select();
       //echo M()->_sql();
        $this ->total=  $this -> where ($where) ->field('shop_id') -> count();
        $this->subtotal = count($arrList);
        //总页数
        $this->pagecount = ceil(($this->total)/$pageNum);

        return $arrList;
    }

    public function nameuniquecheck($value) {
        //分页参数
        $where = "1=1";
        $param = array();
        if($value)
            $where .= " and shop_name='" . $value."'";
        $param['fields'] = "*";

        $arrList = $this -> where ($where) -> field($param['fields']) -> select();
        //echo M()->_sql();
        if($arrList)
            return "success";
        else
            return "false";
    }

    //添加
    public function addList($param) {
        $id = intval($param['id']);
        //编辑
        if ($id) {
            $result = $this -> save(array(
                'shop_id'=>$id,
                'shop_name' => $param['shop_name'],
                'low_price'=>$param['low_price'],
                'ship_cost'=>$param['ship_cost'],
                'shop_address'=>$param['shop_address'],
                'shop_type'=>$param['shop_type'],
                'shop_businessType'=>$param['shop_businessType'],
                'shop_deliverType'=>$param['shop_deliverType'],
                'shop_payType'=>$param['shop_payType'],
                'shop_isopen'=>$param['shop_isopen'],
                'longitude'=>$param['longitude'],
                'latitude'=>$param['latitude']
            ));
        } else {
            //新增
            $result = $this -> add(array(
                'shop_city' => $param['shop_city'],
                'shop_name' => $param['shop_name'],
                'low_price'=>$param['low_price'],
                'ship_cost'=>$param['ship_cost'],
                'shop_address'=>$param['shop_address'],
                'shop_type'=>$param['shop_type'],
                'shop_businessType'=>$param['shop_businessType'],
                'shop_deliverType'=>$param['shop_deliverType'],
                'shop_payType'=>$param['shop_payType'],
                'shop_isopen'=>$param['shop_isopen'],
                'longitude'=>$param['longitude'],
                'latitude'=>$param['latitude']
            ));
        }
        //echo M()->_sql();
        if ($result !== FALSE) {
            return array('status'=> 1);
        } else {
            return array('msg'=>'操作失败');
        }
    }

    //删除
    public function delList($id) {
        if (is_array($id)) {
            $where =array('shop_id' => array('in', $id));
        } else {
            $where = array('shop_id' => array('in', "$id"));
        }

        $result = $this -> where($where) -> delete();
//        echo M()->_sql();
        if ($result !== FALSE) {
            $data =  1;
        } else {
            $data = 0;
        }
        return $data;
    }
}

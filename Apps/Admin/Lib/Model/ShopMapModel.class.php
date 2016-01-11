<?php
/**
 * 商铺配送区域管理
 *
 * @author: fanghui
 * @created:
 */
class ShopMapModel extends Model {

    protected $trueTableName = 'shuijian_shopMap';

    //商户列表
    public function getList($param) {
        $where = "1=1";

        if($param['shopid']>-1){
            $where .= " and shopid = ".$param['shopid'];
        }

        $arrList = $this-> where ($where) ->field($param['fields']) ->order('shipTime_id DESC')-> select();
       //echo M()->_sql();

        return $arrList;
    }

    //添加
    public function addList($param) {
        $id = intval($param['id']);
        print_r($param['lngAndLat']);
        //编辑
        if ($id) {
            $result = $this -> save(array(
                'shipAreaId'=>$id,
                'shopId' => $param['shopId'],
                'labelInfo' => $param['labelInfo'],
                'lngAndLat'=> json_encode($param['lngAndLat']),
                'lngAndLatSize'=>json_encode($param['lngAndLatSize'])
            ));
        } else {
            //新增
            $result = $this -> add(array(
                'shopId' => $param['shopId'],
                'labelInfo' => $param['labelInfo'],
                'lngAndLat'=>$param['lngAndLat'],
                'lngAndLatSize'=>$param['lngAndLatSize']
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
            $where =array('shipTime_id' => array('in', $id));
        } else {
            $where = array('shipTime_id' => array('in', "$id"));
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

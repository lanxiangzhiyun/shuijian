<?php
/**
 * 商铺配送时间管理
 *
 * @author: fanghui
 * @created:
 */
class ShopShipTimeModel extends Model {

    protected $trueTableName = 'shuijian_shopShipTime';

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
        //编辑
        if ($id) {
            $result = $this -> save(array(
                'shipTime_id'=>$id,
                'shopid' => $param['shopId'],
                'shipT_weekDays' => $param['weekDays'],
                'shipT_todayArrive'=>$param['todayArrive'],
                'todayArriveTime'=>$param['todayTimeBeginHour'].':'.$param['todayTimeBeginMinute'].':'.$param['todayTimeBeginSecond'],
                'sendAfterDays'=>$param['sendAfterDays'],
                'sendTimeBegin'=>$param['sendTimeBeginHour'].':'.$param['sendTimeBeginMinute'],
                'sendTimeEnd'=>$param['sendTimeEndHour'].':'.$param['sendTimeEndMinute'],
                'chooseCount'=>$param['chooseCount']
            ));
        } else {
            //新增
            $result = $this -> add(array(
                'shopid' => $param['shopId'],
                'shipT_weekDays' => $param['weekDays'],
                'shipT_todayArrive'=>$param['todayArrive'],
                'todayArriveTime'=>$param['todayTimeBeginHour'].':'.$param['todayTimeBeginMinute'].':'.$param['todayTimeBeginMinute'],
                'sendAfterDays'=>$param['sendAfterDays'],
                'sendTimeBegin'=>$param['sendTimeBeginHour'].':'.$param['sendTimeBeginMinute'],
                'sendTimeEnd'=>$param['sendTimeEndHour'].':'.$param['sendTimeEndMinute'],
                'chooseCount'=>$param['chooseCount']
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

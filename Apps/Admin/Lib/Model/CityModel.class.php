<?php
/**
 *开通城市管理
 *
 * @author: fanghui
 * @created:
 */
class CityModel extends Model {

    protected $trueTableName = 'shuijian_city';

    //城市列表
    public function getList($param) {
        $where = "1=1";

        $arrList = $this -> where ($where) ->field($param['fields']) ->order('city_id DESC')-> select();
       //echo M()->_sql();

        return $arrList;
    }

    //添加
    public function addList($param) {
        $id = intval($param['id']);

        //编辑
        if ($id) {
            $result = $this -> save(array(
                'admin_id'=>$id,
                'admin_username' => $param['admin_username'],
                'admin_email' => $param['admin_email'],
                'admin_realname'=>$param['admin_realname'],
                'admin_tel'=>$param['admin_tel']
            ));
        } else {
            //新增
            $result = $this -> add(array(
                'admin_username' => $param['admin_username'],
                'admin_email' => $param['admin_email'],
                'admin_realname'=>$param['admin_realname'],
                'admin_tel'=>$param['admin_tel'],
                'admin_addtime'=>time()
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
            $where =array('admin_id' => array('in', $id));
        } else {
            $where = array('admin_id' => array('in', "$id"));
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

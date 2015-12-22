<?php
/**
 * 菜单管理
 *
 * @author: fanghui
 * @created:
 */
class MenuModel extends Model {

    protected $trueTableName = 'shuijian_menu';

    //菜单列表
    public function getList($param,$admin_actionList) {
        $where = "(1=1 and menu_level =1) or menu_id in ('".$admin_actionList."')";

        $arrList = $this -> where ($where) ->field($param['fields']) ->order('menu_id DESC')-> select();
       echo M()->_sql();
        return $arrList;
    }

    //添加
    public function addList1($param) {
        //新增
        $result = $this -> add(array(
            'menu_name' => $param['menu_name'],
            'menu_level' => 1,
            'menu_url'=>'',
            'enabled'=>1,
            'menu_pid'=>0
        ));
        //echo M()->_sql();
        if ($result !== FALSE) {
            return array('status'=> 1);
        } else {
            return array('msg'=>'操作失败');
        }
    }

    //添加
    public function addList2($param) {
        //新增
        $result = $this -> add(array(
            'menu_name' => $param['menu_name'],
            'menu_level' => 2,
            'menu_url'=>$param['menu_url'],
            'enabled'=>1,
            'menu_pid'=>$param['menu_pid']
        ));
        //echo M()->_sql();
        if ($result !== FALSE) {
            return array('status'=> 1);
        } else {
            return array('msg'=>'操作失败');
        }
    }
}

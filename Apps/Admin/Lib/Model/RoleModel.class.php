<?php
/**
 * 管理员角色管理
 *
 * @author: fanghui
 * @created:
 */
class RoleModel extends Model {

    protected $trueTableName = 'shuijian_role';

    //管理员角色列表
    public function getList($param) {

        $where = "1=1";

        $arrList = $this -> where ($where) ->field($param['fields']) ->order('role_id DESC')-> select();
       //echo M()->_sql();

        return $arrList;
    }
}

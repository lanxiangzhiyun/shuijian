<?php
/**
 * 管理员管理
 *
 * @author: fanghui
 * @created:
 */
class AdminModel extends Model {

    protected $trueTableName = 'shuijian_admin';

    //管理员列表
    public function getList($param) {
        //分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;

        $where = "1=1";

        $arrList = $this -> where ($where) -> limit($pageNum) -> page ($page) ->field($param['fields']) ->order('admin_id DESC')-> select();
       //echo M()->_sql();
        $this ->total=  $this -> where ($where) ->field('admin_id') -> count();
        $this->subtotal = count($arrList);
        //总页数
        $this->pagecount = ceil(($this->total)/$pageNum);

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

    //根据admin_id获取权限菜单列表
    public function getActionList($adminId){
        $where = "1=1 and admin_id=".$adminId;
        $arrList = $this->where($where)->order('admin_id DESC')-> select();
//        echo M()->_sql();
        return $arrList;
    }

    //设置权限
    public function setActionList($param){
        $result = $this -> save(array(
            'admin_id'=>$param['userId'],
            'admin_actionList' => $param['actionList']
        ));
        //echo M()->_sql();
        if ($result !== FALSE) {
            return array('status'=> 1);
        } else {
            return array('msg'=>'操作失败');
        }
    }
}

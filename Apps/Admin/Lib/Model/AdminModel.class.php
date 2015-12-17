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

    //添加友情链接
    public function addList($param) {
        $id = intval($param['id']);
        $type = intval($param['type']);
        if (!in_array($type,array(1,2,3,4,5,6,7))) {
            return  array('msg'=>'操作失败');
        }

        //编辑
        if ($id) {
            $result = $this -> save(array(
                'id'=>$id,
                'type' => $type,
                'title' => $param['title'],
                'sort'=>$param['sort'],
                'url'=>$param['url'],
                'update_time'=>time()
            ));
        } else {
            //新增
            $result = $this -> add(array(
                'type' => $type,
                'title' => $param['title'],
                'sort'=>$param['sort'],
                'url'=>$param['url'],
                'create_time'=>time()
            ));
        }
        if ($result !== FALSE) {
            return array('status'=> 1);
        } else {
            return array('msg'=>'操作失败');
        }
    }

    //删除友情链接
    public function delList($id) {
        if (is_array($id)) {
            $where =array('id' => array('in', $id));
        } else {
            $where = array('id' => array('in', "$id"));
        }

        $result = $this -> where($where) -> delete();
        if ($result !== FALSE) {
            $data =  1;
        } else {
            $data = 0;
        }
        return $data;
    }

}

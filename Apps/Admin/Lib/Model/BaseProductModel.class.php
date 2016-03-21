<?php
/**
 * 基础产品管理
 *
 * @author: fanghui
 * @created:
 */
class BaseProductModel extends Model {

    protected $trueTableName = 'shuijian_baseproduct';

    public function getList($param) {
        //分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;

        $where = "1=1";

        if($param['keyword']){
            $where .= " and bproduct_name like '%".$param['keyword']."%'";
        }

        $arrList = $this->join(' shuijian_goods_category cat ON cat.cat_id = shuijian_baseproduct.catid')->join(' shuijian_goods_type type ON type.type_id = shuijian_baseproduct.typeid')-> where ($where) -> limit($pageNum) -> page ($page) ->field($param['fields']) ->order('bproduct_id DESC')-> select();
       //echo M()->_sql();
        $this ->total=  $this -> where ($where) ->field('bproduct_id') -> count();
        $this->subtotal = count($arrList);
        //总页数
        $this->pagecount = ceil(($this->total)/$pageNum);

        return $arrList;
    }

    public function title_unique_check($value) {
        //分页参数
        $where = "1=1";
        $param = array();
        if($value)
            $where .= " and type_name='" . $value."'";
        $param['fields'] = "*";

        $arrList = $this -> where ($where) -> field($param['fields']) -> select();
        //echo M()->_sql();
        if($arrList)
            return "success";
        else
            return "false";
    }

    public function code_unique_check($value) {
        //分页参数
        $where = "1=1";
        $param = array();
        if($value)
            $where .= " and type_code='" . $value."'";
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
                'type_id'=>$id,
                'type_name' => $param['type_name'],
                'type_code'=>$param['type_code']
            ));
        } else {
            //新增
            $result = $this -> add(array(
                'type_name' => $param['type_name'],
                'type_code'=>$param['type_code']
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
            $where =array('type_id' => array('in', $id));
        } else {
            $where = array('type_id' => array('in', "$id"));
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

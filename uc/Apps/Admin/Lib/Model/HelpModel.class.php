<?php
/**
 * 友情链接模型
 *
 * @author: zlg
 * @created: 13-8-7
 */
class HelpModel extends Model {

    protected $trueTableName = 'boqii_link';

    //友情链接列表
    public function getList($param) {
        //分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;

        $where = "1=1";

        //友情链接名称
        if (!empty($param['title']) && $param['title'] !== '输入友情链接内容关键字') {
            $where .=  " and title like'%{$param['title']}%'";
        }

        //创建时间
        if (!empty($param['start_time'])) {
            $where .=  " and create_time >= ".strtotime($param['start_time']);
        }

        if (!empty($param['end_time'])) {
            $where .=  " and create_time <=".strtotime($param['end_time']);
        }

        //友情链接类型
        if (!empty($param['type'])) {
            $where .=  " and type =".$param['type'];
        }

        if (empty($param['fields'])) {
            $param['fields'] = 'id';
        }

        $arrList = $this -> where ($where) -> limit($pageNum) -> page ($page) ->field($param['fields']) ->order('id DESC')-> select();
//       echo M()->_sql();
        $this ->total=  $this -> where ($where) ->field('id') -> count();
        $this->subtotal = count($arrList);
        //总页数
        $this->pagecount = ceil(($this->total)/$pageNum);
        foreach ($arrList as $key => $val) {
            if (empty($val['create_time'])) {
                $arrList[$key]['create_time'] = '';
            } else {
                $arrList[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
            }

            $arrList[$key]['typeName'] = C("HELP_TYPE.{$val['type']}");

        }

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

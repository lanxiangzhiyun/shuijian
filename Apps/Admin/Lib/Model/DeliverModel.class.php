<?php
/**
 * 自提点管理
 *
 * @author: fanghui
 * @created:
 */
class SiteModel extends Model {

    protected $trueTableName = 'shuijian_site';

    //商户列表
    public function getList($param) {
        //分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;

        $where = "1=1";

        if($param['siteName']){
            $where .= " and site_name like '%".$param['siteName']."%'";
        }

        $arrList = $this->join(' shuijian_city ON shuijian_city.city_id = shuijian_site.site_city') ->join(' shuijian_shop c ON c.shop_id = shuijian_site.site_shop')-> where ($where) -> limit($pageNum) -> page ($page) ->field($param['fields']) ->order('site_id DESC')-> select();
       //echo M()->_sql();
        $this ->total=  $this -> where ($where) ->field('site_id') -> count();
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
            $where .= " and site_name='" . $value."'";
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
                'site_id'=>$id,
                'site_city' => $param['site_city'],
                'site_shop'=>$param['site_shop'],
                'site_name'=>$param['site_name'],
                'site_address'=>$param['site_address'],
                'site_contact'=>$param['site_contact'],
                'site_mobile'=>$param['site_mobile'],
                'site_startTime'=>$param['startHour'].":".$param['startMinute'],
                'site_endTime'=>$param['endHour'].":".$param['endMinute'],
                'shop_isopen'=>$param['shop_isopen'],
                'longitude'=>$param['longitude'],
                'latitude'=>$param['latitude']
            ));
        } else {
            //新增
            $result = $this -> add(array(
                'site_city' => $param['site_city'],
                'site_shop'=>$param['site_shop'],
                'site_name'=>$param['site_name'],
                'site_address'=>$param['site_address'],
                'site_contact'=>$param['site_contact'],
                'site_mobile'=>$param['site_mobile'],
                'site_startTime'=>$param['startHour'].":".$param['startMinute'],
                'site_endTime'=>$param['endHour'].":".$param['endMinute'],
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

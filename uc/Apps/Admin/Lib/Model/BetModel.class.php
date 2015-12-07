<?php
/**
 * 投注抽奖model
 *
 * @author: zlg
 * @created: 13-1-8
 */
class BetModel extends RelationModel {
    protected $trueTableName = 'boqii_prize_goods';
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 查询投注列表
     * @param array $where
     * @param $param
     * @return array
     */
    public function getSearchList( $where = array(),$param) {
        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 25; //$param['num'] 自定义 显示条数

        
        
        $totalPage =  M('boqii_prize_exchange')->where($where)->count();
        $this->pcount = ceil($totalPage/$page_num);
        if ($this->pcount < $page) {
            $page = $this->pcount;
        }
        // echo $where;
        if($param['action'] == 'exportExcel') {
            $arrResult = M('boqii_prize_exchange')->where($where)->order('applychange_time desc')->select();
        } else {
            $arrResult = M('boqii_prize_exchange')->where($where)->page($page)->limit($page_num)->order('applychange_time desc')->select();
        }

        //本页条数
        $this->count = count($arrResult);
        foreach ($arrResult as $key=>$val) {
                 $arrResult[$key]['cityName'] = getProvinceCity($val['city_id'], $table_name = 'shop_city');
                 $arrResult[$key]['applychange_time'] = $val['applychange_time'] ? date('Y-m-d H:i:s',$val['applychange_time']) : '';
                 $arrResult[$key]['typeName'] = $val['type']== 1 ? "<font color='red'>抽奖</font>" : "<font color='red'>兑奖</font>";
                 if ($val['change_status'] == 0) {
                     $arrResult[$key]['applyMsg'] = '未受理';
                 } elseif ($val['change_status'] == 1) {
                     $arrResult[$key]['applyMsg'] = '已受理';
                 } elseif ($val['change_status'] == 2) {
                     $arrResult[$key]['applyMsg'] = '未抽中';
                 } else {
                     // do nothing
                 }

        }

        return  !empty($arrResult) ? $arrResult : array();
    }

    /**
     * 投注受理
     * @param $strId
     * @return bool
     */
    public function updateChangeStatus($strId) {
        if (empty($strId)) {
           return false ;
        } else {
            M('boqii_prize_exchange')->where(array('id'=>array('in',$strId)))->save(array('change_status'=>1,'change_time'=>time()));
        }
    }

    /**
     * 添加奖品
     * @param array $fields
     * @return bool|mixed
     */
    public function addPrize($fields) {
        if (empty($fields)) {
            return false;
        }
        
        $status = $this->add($fields); // 写入用户数据到数据库
        // echo $this->getLastSql();exit;
        return $status;
    }

    /**
     * 所有奖品 
     * @param string $where
     * @param $param
     * @return mixed
     */
    public function getPrizeList($where,$param) {
        // 分页
        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 25; 

        //本页条数
        $this->count    = $this->where($where)->page($page)->limit($page_num)->count();
        $totalPage      = $this->where($where)->count();
        $this->pcount   = ceil($totalPage/$page_num);
        if ($this->pcount < $page) {
            $page = $this->pcount;
        }
        $arrPrize = $this->where($where)->page($page)->limit($page_num)->order('pid desc')->select();
        // print_r($where);
        // echo $this->getLastSql();
        return $arrPrize;
    }

    /**
     * 编辑奖品
     *
     * @param int $pid 数据id
     * @param array $data 数据
     *
     * return array
     */
    public function editPrize($pid,$data) {
        $status = $this->where('pid = '.$pid)->save($data);
       // echo $this->getLastSql();exit;
        return $status;
    }

   /**
     * 删除奖品
     *
     * @param int $pid 数据id
     *
     * return array
     */
    public function deletePrize($pid) {
       $status = $this->where('pid = '.$pid)->setField('status',-1);
        return $status;
    }

    // 上架或下架
    // public function editPrizeValid($id,$valid) {
    //    $status = M('boqii_prize')->where(array('pid'=>$id))->setField('valid',$valid);
    //     return $status;
    // }

    
    /**
     * 获取一个奖品信息--一维数组
     *
     * @param int $pid 数据id
     *
     * return array
     */
    public function getPrizeInfo($pid) {
        $arrPrizeInfo = $this->where('pid = '.$pid)->find();
        return $arrPrizeInfo;
    }

    /**
     * 获取一个奖品信息--er维数组
     *
     * @param int $pid 数据id
     * @param str $fields 相应的字段字符串
     *
     * return array
     */
    public function getPrizeInfoEw ($pid,$fields) {
        $arrPrizeInfoEw = $this->where('pid = '.$pid)->field($fields)->select();
        return $arrPrizeInfoEw;
    }

    /*********************************** 2015改版 **********************/

    /**
     * 获得当前抽奖啵币数量
     *
     * @param string $where 条件
     *
     * return value string 啵币值
     */
    public function getBobiNum ($where) {
        $bobiNum = M('boqii_prize_config')->where($where)->getField('value');
        // echo M()->getLastSql();
        return $bobiNum;
    }

    /**
     * 获得当前抽奖啵币数量
     *
     * @param string $where 条件
     *
     * return value string 啵币值
     */
    public function saveBobiNum ($num) {
        $res = M('boqii_prize_config')->where('`key` = "draw_coins"')->setField('value',$num);
        // echo M()->getLastSql();exit;
        return $res;
    }

    /**
     * 获得当前抽奖奖品总共概率
     
     * return int 总概率值
     */
    public function getPrizeRateSum () {
        $where = 'ptype = 1 and status = 0';
        $prizeList = $this->where($where)->field('pid,draw_rate')->select();
        // echo $this->getLastSql();exit;
        if(!prizeList) return array();
        $rateSum = 0;
        foreach ($prizeList as $k => $val) {
            $rateSum += $val['draw_rate'];
        }
        
        return $rateSum;
    }
}

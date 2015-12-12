<?php
/**
 * 搜索Model类
 *
 * @author: JasonJiang
 * @date: 2015-02-04
 */
class SearchModel extends Model {
	// 数据库
	protected $trueTableName = 'boqii_search_keyword';

    /** 
	 * 专题列表
	 *
	 * @param $param array 参数数组
	 *					page int 当前页码
	 *					pageNum int 页显数量
	 *					name string 专题标题
	 *					start_time string 创建时间开始时间
	 *					end_time string 创建时间结束时间
	 *					type int 专题类型
	 *					fields string 查询字段
     *                  order string 排序条件
	 *
	 * @return array 目标数组
	 */
    public function getList($param) {
        // 分页参数
        $page = $param['page'] ? $param['page'] : 1;
        $pageNum = $param['pageNum'] ? $param['pageNum'] : 10;

        $where = "status = 0";

        // 专题名称
        if (!empty($param['name']) && $param['name'] !== '请输入关键词名称') {
            $where .=  " and name like'%{$param['name']}%'";
        }

        // 专题类型
        if (!empty($param['type'])) {
            $where .=  " and type =".$param['type'];
        }
		// 查询字段
        if (empty($param['fields'])) {
            $param['fields'] = 'id';
        }

        // 排序条件
        if (empty($param['column'])) {
            $param['order'] = 'id desc';
        }else{
            $param['order'] = $param['column'].' '.$param['sort'];
        }
        // print_r($param);
        $this ->total=  $this -> where ($where) ->field('id') -> count();
        // 总页数
        $this->pagecount = ceil(($this->total)/$pageNum);
        if ($page > $this->pagecount) {
             $page = $this->pagecount;
        }
		// 专题列表
        $keywordList = $this->where($where)->limit($pageNum)->page($page)->field($param['fields'])->order($param['order'])->select();
        // echo M()->getLastSql();
        $this->subtotal = count($keywordList);
        // 整理数据
        foreach ($keywordList as $key => $val) {
			// 创建时间
			if(empty($val['create_time'])) {
				$keywordList[$key]['create_time'] = '';
			}else {
				$keywordList[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
			}
            if ($val['type'] == 1) {
                $keywordList[$key]['type'] = '热门关键词';
            }
			// 作者
			$keywordList[$key]['username'] = M()->Table('uc_admin')->where(array('id'=>$val['adminid']))->getField('username');

        }

        return $keywordList;
    }

    /**
	 * 添加关键词
	 *
	 * @param $param array 参数数组
     *              $type   int     关键词类型 1.热门
     *              $name   string  关键词名称
     *              $file   string  关键词文件
	 *
	 * @return array 处理结果
	 */
    public function addKeyword ($param) {
        // echo "<pre>"; print_r($param);exit;
        // 判断excel文件存在
		if ($param['indata']) {
            foreach ($param['indata'] as $k => $v) {
                // 查看关键词是否存在
                $keywordIsExists = $this->getKeywordIsExists(array('name'=>$v[0],'type'=>1));
                if ($v[0] && !$keywordIsExists) {
                    $list[$k]['name']           = trim($v[0]);
                    $list[$k]['type']           = $param['type'];
                    $list[$k]['create_time']    = time();
                    $list[$k]['adminid']        = $param['uid'];
                    $res[$k] = $this->add($list[$k]);
                }
            }
        }else{
            $nameStr = explode(' ', $param['name']);
            foreach ($nameStr as $k => $v) {
                // 查看关键词是否存在
                $keywordIsExists = $this->getKeywordIsExists(array('name'=>$v,'type'=>1));
                // echo $keywordIsExists;
                if ($v && !$keywordIsExists) {
                    $list[$k]['name']           = trim($v);
                    $list[$k]['type']           = $param['type'];
                    $list[$k]['create_time']    = time();
                    $list[$k]['adminid']        = $param['uid'];
                    $res[$k] = $this->add($list[$k]);
                }
            }
        }
       
        if ($res) {
            return array('status'=>'ok','msg'=>'关键词添加成功！','data'=>$res);
        }else{
            return array('status'=>'false','msg'=>'关键词添加失败！');
        }
    }

    /**
     * 根据关键词名确认是否存在
     *
     * @param $name string 关键词名称
     *          $type int 关键词类型
     * @return boolean
     */
    public function getKeywordIsExists ($param) {
        
        $res = $this -> where('status = 0 and name = "'.$param['name'].'" and type = '.$param['type'])->getField('id');
        if ($res) {
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 编辑关键词
     *
     * @param $param array 参数数组
     *              $id     int     关键词id
     *              $type   int     关键词类型 1.热门
     *              $name   string  关键词名称
     *              $file   string  关键词文件
     *
     * @return array 处理结果
     */
    public function editKeyword ($param) {
        // print_r($param);exit;
        $res = $this->where('id = '.$param['id'])->save(array('name'=>$param['name']));
        if ($res) {
            return array('status'=>'ok','msg'=>'修改成功！');
        }else{
            return array('status'=>'false','msg'=>'修改失败！');
        }
    }

    /**
     * 通过关键词id获得相关数据
     *
     * @param  $id     int  关键词id
     *          $field string  控制输出数据
     * @return array 关键词数据
     */
    public function getInfoByKeywordId ($id,$field) {
        $result = $this->field($field)->where('status = 0 and id = '.$id)->find();
      
        // 作者
        $result['username'] = M()->Table('uc_admin')->where(array('id'=>$result['adminid']))->getField('username');
        // 时间
        $result['create_time'] = $result['create_time']?date('Y-m-d H:i:s',$result['create_time']):'';
        return $result;
    }

     /**
     * 通过关键词id获得相关数据
     *
     * @param  $id     int  关键词id
     *          $field string  控制输出数据
     * @return array 关键词数据
     */
    public function getInfoByKeywordIdTypeIsSelect ($id,$field) {
       return $this->where('id='.$id)->field($field)->select();
    }

    /**
	 * 删除专题
	 *
	 * @param $id 关键词id
	 *
	 * @return int 处理结果
	 */
    public function delKeyword ($id) {
		
		// 删除专题
        $result = $this -> where('id = '.$id) -> save(array('status'=> -1));
        
        return $result;
    }

}

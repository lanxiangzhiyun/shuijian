<?php
/**
 * 专题Model类
 *
 * @author zlg
 * @created 2013-08-01
 * @modify by Fongson 2014-11-01 增加专题搜索库
 */
class SubjectModel extends Model {
	// 数据库
	protected $trueTableName = 'zt_tb';

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
	 *
	 * @return array 目标数组
	 */
    public function getList($param) {
        // 分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;

        $where = "status = 0";

        // 专题名称
        if (!empty($param['name']) && $param['name'] !== '输入专题内容关键字') {
            $where .=  " and name like'%{$param['name']}%'";
        }

        // 创建时间开始时间
        if (!empty($param['start_time'])) {
            $where .=  " and create_time >= ".strtotime($param['start_time']);
        }
		// 创建时间结束时间
        if (!empty($param['end_time'])) {
            $where .=  " and create_time <=".strtotime($param['end_time']);
        }

        // 专题类型
        if (!empty($param['type'])) {
            $where .=  " and type =".$param['type'];
        }

        // 宠物类别
        if (!empty($param['pettype'])) {
            $where .=  " and pettype =".$param['pettype'];
        }

		// 查询字段
       if (empty($param['fields'])) {
         $param['fields'] = 'id';
       }

		// 专题列表
       $arrList = $this -> where ($where) -> limit($pageNum) -> page ($page) ->field($param['fields']) ->order('id DESC')-> select();
       $this ->total=  $this -> where ($where) ->field('id') -> count();
        $this->subtotal = count($arrList);

        // 总页数
        $this->pagecount = ceil(($this->total)/$pageNum);
        foreach ($arrList as $key => $val) {
			// 创建时间
			if(empty($val['create_time'])) {
				$arrList[$key]['create_time'] = '';
			}else {
				$arrList[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
			}
			// 作者
			$arrList[$key]['username'] = M()->Table('uc_admin') -> where(array('id'=>$val['author'])) -> getField('username');
			// 专题类型
			$arrList[$key]['typeName'] = C("ZT_TYPE.{$val['type']}");
			// 专题宠物类别
			$arrList[$key]['pettypeName'] = $val['pettype'] == 0 ? '无' : C("PET_TYPE.{$val['pettype']}");

        }

        return $arrList;
    }

    /**
	 * 编辑专题
	 *
	 * @param $param array 参数数组
	 *						id int 专题id
	 *						type int 专题类型
	 *						name string 专题名称
	 *						img_path string 专题封面图
	 *						url string 专题url
	 *						content string 专题内容
	 *
	 * @return array 处理结果
	 */
    public function addSubject ($param) {
		// 专题id
        $id = intval($param['id']);
		// 专题类型
        $type = intval($param['type']);
        if (!in_array($type,array(5,6,7,8,9,10,11,12,13))) {
            return  array('msg'=>'操作失败');
        }
		// 专题宠物类别
        // $pettype = intval($param['pettype']);
        // if (!in_array($pettype,array(0,1,2,3,4))) {
        //     return  array('msg'=>'操作失败');
        // }
        //编辑
        if ($id) {
            $result = $this->where('id='.$id)-> save(array(
                'type' => $type,
                'pettype' => $param['pettype'],
                'name' => $param['name'],
                'img_path'=>$param['img_path'],
                'author'=>session('boqiiUserId'),
                'url'=>$param['url'],
                'content'=>$param['content']
            ));

			if ($result !== false) {
				// 更新搜索库：新增专题
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=1&param[pid]=". $id ."&param[type]=4";
				get_url($url);
			}
        } else {
         //新增
            $result = $this -> add(array(
                'type' => $type,
                // 'pettype' => $param['pettype'],
                'name' => $param['name'],
                'img_path'=>$param['img_path'],
                'author'=>session('boqiiUserId'),
                'url'=>$param['url'],
                'content'=>$param['content'],
                'create_time'=>time()
            ));
            
			if ($result !== false) {
				// 更新搜索库：新增专题
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=add&param[config_object]=1&param[pid]=". $result ."&param[type]=4";
				get_url($url);
			}

        }

        if ($result !== false) {
            return array('status'=> 1);
        } else {
            return array('msg'=>'操作失败');
        }
    }

    /**
	 * 删除专题
	 *
	 * @param $id array/int 专题id数组/专题id
	 *
	 * @return int 处理结果
	 */
    public function delSubject ($id) {
		// 如果为数组
        if (is_array($id)) {
            $where =array('id' => array('in', $id));
        } else {
            $where = array('id' => array('in', "$id"));
        }

		// 删除专题
        $result = $this -> where($where) -> save(array('status'=> -1));
        if ($result !== false) {
            $data = 1;

			if (is_array($id)) {
				foreach($id as $val) {
					// 更新搜索库：删除专题
					$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=". $val ."&param[type]=4";
					get_url($url);
				}
			} else {
				// 更新搜索库：删除专题
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=". $id ."&param[type]=4";
				get_url($url);
			}

        } else {
            $data = 0;
        }
        return $data;
    }

}

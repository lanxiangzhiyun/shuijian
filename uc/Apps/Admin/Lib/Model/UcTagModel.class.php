<?php
/**
 * UcTag Model类
 * Modifier JasonJiang date:2015/01/12
 */
class UcTagModel extends RelationModel{
	//标签表
	protected $trueTableName='uc_tag';

	/**
	 * 获取标签列表
	 *
	 * @param $page int 当前页码
	 * @param $limit int 页显数量
	 * @param $where string 查询条件
	 * @param $order string 排序条件
	 * 
	 * @return array 标签列表数组
	 *
	 */
	public function hasManyTags($page,$limit,$where,$order){
		//标签列表
		$tagList = $this->table('uc_tag tag')->field('tag.id,tag.name,tag.dateline,tag.usetimes,tag.resourtimes,tag.uid,tag.type,tag.status,tag.locked')->order($order)->where($where)->limit($limit)->page($page)->select();

		$adminModel = D('UcAdmin');
		foreach($tagList as $key=>$val) {
			// // 取得父级标签
			// $ptag = M()->Table('uc_tag_relation')->where('status = 0 and tag_id='.$val['id'])->getField('parent_tag_id',true);

			// if ($ptag) {
			// 	foreach ($ptag as $k => $v) {
			// 		$ptagArr[$key][] = $this->where('status >= 0 and id='.$v)->getField('name');
			// 	}
			// }

			// $tagList[$key]['ptagStr'] = trim(implode(',', $ptagArr[$key]),',');

			// 根据id获取管理员信息
			// $admin = $adminModel->getAdminInfoById($val['uid']);
			// $tagList[$key]['username'] = $admin['username'];
			// $tagList[$key]['truename'] = $admin['truename'];
			$tagList[$key]['username'] = $val['uid'];
			$tagList[$key]['truename'] = $val['uid'];
		}

		return $tagList;
	}

	/*
	*获取标签个数
	*/
	public function hasTagCount($where){
		$result = $this->table('uc_tag tag')->where($where)->count();
		return $result;
	}

	/*
	 * 通过标签id获得该标签的父级关系
	 * @param $id int 标签id
	 * 			$tree int 树结构
	 *			$name string 标签名
	 */
	// public function getParTagTree2($id,&$tree='',$name='', &){

	// 	$pTagList = M()->Table('uc_tag_relation')->field('parent_tag_id pid')->where('status = 0 and tag_id = '.$id)->select();

	// 	foreach ($pTagList as $key => $val) {
	// 		if ($val['pid']) {
	// 			//$tree = $name;
	// 			// 父标签是否仍有父标签
	// 			$tag = M()->Table('uc_tag_relation')->where('status = 0 and tag_id = '.$val['pid'])->count();//echo M()->getLastSql();echo '<br>';
	// 			if ($tag) {

	// 				$tree = $tree . $name .','.$this->where('status >= 0 and id = '.$val['pid'])->getField('name');
	// 				dump($tree);echo '<br>';
	// 				$this->getParTagTree($val['pid'],$tree, '', $otree);
	// 			}else{
					
	// 				$tagStr = $tree[$val['pid']] . ','.$this->where('status >= 0 and id = '.$val['pid'])->getField('name');
	// 				//$tree = $tree != '' ? $tree . ',' . $name : $name;
	// 				//$tagStr = $tree . ',' . $this->where('status >= 0 and id = '.$val['pid'])->getField('name');
	// 				$tagArr = array_reverse(explode(',', $tagStr));echo $tagStr .'<br>';
	// 				// echo $tagStr.'<br>';
	// 				foreach ($tagArr as $k => $v) {
	// 					echo str_repeat('&nbsp;', $k*4).'●&nbsp;&nbsp;'.$v.'<br>';
	// 				}					
	// 			}

	// 		}
	// 	}
	// }

	/**
	 * 获取标签
	 *
	 * @param $id int 标签id
	 * @param &$tree array 标签树
	 */
	public function getParTagTree($id,&$tree){
		// 是否有父标签
		$pTagList = M()->Table('uc_tag_relation')->field('parent_tag_id pid')->where('status = 0 and tag_id = '.$id)->select();

		foreach ($pTagList as $key => $val) {
			// 父标签是否仍有父标签
			$tags = M()->Table('uc_tag_relation')->where('status = 0 and tag_id = '.$val['pid'])->getField('parent_tag_id', true);
			if ($tags) {
				$this->getParTagTree($val['pid'], $tree[$id]);
			}else{
				$name = M()->Table('uc_tag')->where('id='.$val['pid'])->getField('name');
				$tree[$id][$val['pid']] = array('id'=>$val['pid'], 'name'=>$name);
			}

		}
	}

	// public function getParTagTree($id, $tree='',$name){
	// 	//
	// 	$this->getAllParTagIdList($id, true, $pids);
	// 	$pids .= ',' .$id;
	// 	$rootid = $this->where('status=0 AND type=11 AND name="根标签"')->getField('id');

	// 	$root = array();
	// 	$this->getParTagList($rootid, $pids, $root);

	// 	//$tree = $rootid;
	// 	foreach($root as $k => $v){
	// 		echo 'key:'.$k .'	val:'; print_r($v);echo '<br>';
	// 		foreach($v as $k1 => $v1) {
	// 			echo 'skey:'.$k1 .'	sval:'; print_r($v1);echo '<br>';
	// 			//echo 'key:'.$k .'	val:'; print_r($v);echo '<br>';
	// 			$tree[$k1] = $tree[$k1] ? $tree[$k1] : '根标签';
	// 			if($root[$k1]) {
	// 				$tree[$k1] = 
	// 			} elseif($k1 == $id) {
	// 				$tree[$k1] . = $tree[$k1] "," . $v1['name'];
	// 			}
	// 		}
			
	// 	}

	// }

	/**
	 * 获取指定标签的父标签
	 *
	 * @param $param array 参数数组
	 *			tag_id int 标签id
	 *
	 * @return array 父标签信息
	 */
	public function getParTagList($pid, $pids, &$root) {
		$ids = M()->Table('uc_tag_relation')->where('status = 0 and parent_tag_id = '.$pid . ' AND tag_id in ('.$pids.')')->getField('tag_id', true);
		
		foreach($ids as $k=>$v) {
			$name = M()->Table('uc_tag')->where('id='.$v)->getField('name');
			$root[$pid][$v] = array('id'=>$v, 'name'=>$name);
			$cnt = M()->Table('uc_tag_relation')->where('status = 0 and parent_tag_id = '.$v)->count();
			if($cnt) {
				$this->getParTagList($v, $pids, $root);
			} else {
				 
			}
		}
	}

	/**
	 * 获取指定标签的所有父标签id
	 *
	 * @param $tagid int 标签id
	 * @param $loop boolean 是否递归查询所有父系标签
	 * @param &$pids string 引用传参，父标签id
	 *
	 * @return mixed 父标签id
	 */
	private function getAllParTagIdList($tagid, $loop = true, &$pids = '') {
		// 父标签id
		$ptagIds = M()->Table('uc_tag_relation')->where('tag_id IN ('.$tagid.') AND status=0')->getField('parent_tag_id', true);

		if($ptagIds) {
			if($loop === false) {
				return $ptagIds;
			}
			$pids = $pids == '' ? implode(',', $ptagIds) : $pids . ',' . implode(',', $ptagIds);
			$this->getAllParTagIdList(implode(',', $ptagIds), true, $pids);
		}
		
		return $pids;
	}

	/*
	 * 通过标签id获得该标签的子级关系
	 * @param $id int 标签id
	 * 			$tree int 树结构
	 *			$name string 标签名
	 */
	public function getSecTagTree($id,$tree='',$name=''){
		// 找出该标签所有的子标签
		$sTagList = M()->Table('uc_tag_relation')->field('tag_id')->where('status = 0 and parent_tag_id = '.$id)->limit(10)->order('id desc')->select();
		$sTagListCount = M()->Table('uc_tag_relation')->field('tag_id')->where('status = 0 and parent_tag_id = '.$id)->count();
		
		// echo "<pre>";print_r($sTagList);
		// echo '<span>●&nbsp;&nbsp;'.$name.'</span><br>';
		
		foreach ($sTagList as $k => $val) {
			if ($val['tag_id']) {
				$sTagList[$k]['name'] = $this->where('status >= 0 and type = 11 and id = '.$val['tag_id'])->getField('name');
				$sTagList[$k]['msg'] = '<span tagId = "'.$val['tag_id'].'">'.str_repeat('&nbsp;', 4).'●&nbsp;&nbsp;<a href="/iadmin.php/Tag/editPage?id='.$val['tag_id'].'">'.$sTagList[$k]['name'].'</a></span><br>';
				$count = M()->Table('uc_tag_relation')->field('tag_id')->where('status = 0 and parent_tag_id = '.$val['tag_id'])->count();
				if ($count) {
					$sTagList[$k]['msg'] .= '<span style="cursor:pointer;" onclick="loadMore(this)" page="1" space="8" id="'.$val['tag_id'].'" tagId="'.$val['tag_id'].'">'.str_repeat('&nbsp;', 8).'<font title="显示子标签" color="#666">显示子标签...</font></span><br>';
				}
			}
		}
		if ($sTagListCount > 10) {
			$sTagList[$k]['msg'] .= '<span style="cursor:pointer;" page="2" class="'.$id.'" onclick="loadMore(this)" space="4"  tagId="'.$id.'">'.str_repeat('&nbsp;', 4).'<font title="加载更多" color="#666">加载更多...</font></span><br>';
		}
		
		return $sTagList;
	}


	/*
	 * 通过标签id获得该标签的子级关系
	 * @param $id int 标签id
	 * 			$num int 空格数
	 *			$page int 数据分页
	 */
	public function getSubTagList($id,$num,$page=1){
		// 找出该标签所有的字标签
		$subTagList = M()->Table('uc_tag_relation')->field('tag_id')->where('status = 0 and parent_tag_id = '.$id)->page($page)->limit(10)->order('id desc')->select();
		$subTagListCount = M()->Table('uc_tag_relation')->field('tag_id')->where('status = 0 and parent_tag_id = '.$id)->count();
		// echo $subTagListCount;exit;
		foreach ($subTagList as $k => $val) {
			if ($val['tag_id']) {
				$subTagList[$k]['name'] = $this->where('status >= 0 and id = '.$val['tag_id'])->getField('name');
				if ($k) {
					$subTagList[$k]['msg'] .= '<br>';
				}
				$subTagList[$k]['msg'] .= '<span tagId = "'.$val['tag_id'].'">'.str_repeat('&nbsp;', $num).'●&nbsp;&nbsp;<a href="/iadmin.php/Tag/editPage?id='.$val['tag_id'].'">'.$subTagList[$k]['name'].'</a></span>';
				$count = M()->Table('uc_tag_relation')->field('tag_id')->where('status = 0 and parent_tag_id = '.$val['tag_id'])->count();
				// 显示子标签
				if ($count) {
					$subTagList[$k]['submsg'] = '<br><span style="cursor:pointer;"  id="'.$val['tag_id'].'" onclick="loadMore(this)" space="'.($num+4).'" tagId="'.$val['tag_id'].'">'.str_repeat('&nbsp;', $num+4).'<font title="显示子标签" color="#666">显示子标签...</font></span>';
				}
				
			}
			
		}

		if (!empty($subTagList)) {
			$subTagList['num'] = count($subTagList);
			// 加载更多
			$p = (int)$page*10;
			$subTagListCount = (int)$subTagListCount;
			if ($page && ($subTagListCount-$p)>0) {
				$subTagList['loadMoreTagList'] = '<br><span style="cursor:pointer;" page="'.($page+1).'" onclick="loadMore(this)" space="'.$num.'" class="'.$id.'" tagId="'.$id.'">'.str_repeat('&nbsp;', $num).'<font  title="加载更多" color="#666">加载更多...</font></span>';
			}
			$subTagList['page'] 	= $page;
			$subTagList['status'] 	= 'ok';
			$subTagList['id'] 		= $id;
		}else{
			$subTagList['status'] = 'false';
			$subTagList['msg'] = '参数出错！';
		}
		return $subTagList;
	}
	
	/*
	 * 检查标签是否存在
	 * @param $tagName string 标签名
	 * return bool
	 */
	public function checkTagIsExists($tagName){
		$result = $this->where(array('status'=>array('egt',0),'name'=>$tagName,'display_status'=>0,'type'=>11))->count();
		return $result;
	}

	/*
	 * 通过标签id获得相应输入的字段数据
	 *  $id int 标签名
	 *	$field string 获得数据
	 * return array 数据
	 */
	public function getInfoByTagId($id,$field=''){
		return $this->field($field)->where('status>=0 and id='.$id)->find();
	}

	/*
	 * 获取标签的父子级标签
	 *
	 * @param $id int 标签id
	 *
	 * return array 父子级标签
	 */
	public function getRelationByTagId($id){
		if (!$id) {
			return false;
		}
		// 父标签id
		$ptag = M()->Table('uc_tag_relation')->where('status = 0 and tag_id = '.$id)->getField('parent_tag_id',true);
		$ptagStr = implode(',', $ptag);
		// 父标签信息
		$data['ptag'] = array();
		if($ptagStr) {
			$data['ptag'] = $this->field('id,name')->where(array('id'=>array('in',$ptagStr),'status'=>array('egt',0)))->select();
		}
		// 子标签id
		$stag = M()->Table('uc_tag_relation')->where('status = 0 and parent_tag_id = '.$id)->getField('tag_id',true);
		$stagStr = implode(',', $stag);
		// 子标签信息
		$data['stag'] = array();
		if($stagStr) {
			$data['stag'] = $this->field('id,name')->where(array('id'=>array('in',$stagStr),'status'=>array('egt',0)))->select();
		}

		return $data;
	}


	/**
	 * 根据标签名获取标签id
	 */
	public function getTagsByName($param) {
		$where = 'status >= 0';
		// 类型
		if($param['type']) {
			$where .= ' AND type='.$param['type'];
		}
		// 标签
		$where .= ' AND name like "%' . $param['tagname'] . '%"';

		// 标签id
		$tagids = $this->where($where)->getField('id', true);

		return $tagids;
	}

	/**
	 * 通过标签id获得相关联的真实文章数|问答数|词条数
	 * @param array
	 *			$type int 类型
	 *			$tag_id int 标签id
	 */
	public function getObjectNum($param){
		return M()->Table('boqii_tag_object')->where(array('status'=>0,'tag_id'=>$param['tag_id'],'object_type'=>$param['type']))->count();
	}

	/*
	 * 批量合并标签关系
	 * $tagId1 array 标签一：被合并的
	 * $tagId2 int 标签二
	 * 			
	 * return bool
	 */
	public function batchMergeRelation($tagId1,$tagId2){
		// echo $tagId2;
		// 删除父级关系转接
		M()->Table('uc_tag_relation')->where(array('status'=>0,'tag_id'=>array('in',$tagId1)))->save(array('status'=>-1));
		// 如有子级分类关系
		$subTagList = M()->Table("uc_tag_relation")->where(array('status'=>0,'parent_tag_id'=>array('in',$tagId1)))->getField('tag_id',true);
		if ($subTagList) {
			// 获得未分类标签id
			$pTagId = $this-> getBkParentTagId('未分类');
			foreach ($subTagList as $key => $val) {
				$isRepeat[$key] = M()->Table("uc_tag_relation")->where('status = 0 and tag_id='.$val)->count();
				if ($isRepeat[$key] == 1) {
					M()->Table("uc_tag_relation")->where('tag_id='.$val)->save(array('parent_tag_id'=>$pTagId));
				}else{
					M()->Table("uc_tag_relation")->where(array('status'=>0,'tag_id'=>$val,'parent_tag_id'=>array('in',$tagId1)))->save(array('status'=>-1));
				}
				// echo M()->getLastSql();echo '<br>';
			}
			// print_r($subTagList);exit;
		}
		
		// 文章，问答，词条关系转接
		M()->Table('boqii_tag_object')->where(array('tag_id'=>array('in',$tagId1)))->save(array('tag_id'=>$tagId2));

		// 标签用户关注关系 不用继承，直接干掉
		M()->Table('bk_recommend')->where(array('object_type'=>10,'object_id'=>array('in',$tagId1)))->delete();
		
		//查到重复的 文章或者词条或者问答与标签的关系 id
		$relationIdList = M()->query('select tid from (select id as tid,count(*) as ct from boqii_tag_object group by tag_id,object_id,object_type having ct>1 ) as b');
		// echo '<pre>';print_r($relationIdList);exit;
		if ($relationIdList) {
			foreach ($relationIdList as $key => $val) {
				M()->Table('boqii_tag_object')->where('id='.$val['tid'])->save(array('status'=>-1));
			}
		}
		// 相关字段更新：别名,文章，问答，词条数量,关注数
		$param['article_num'] 	= $this->getObjectNum(array('type'=>1,'tag_id'=>$tagId2));
		$param['thread_num'] 	= $this->getObjectNum(array('type'=>2,'tag_id'=>$tagId2));
		$param['entry_num'] 	= $this->getObjectNum(array('type'=>3,'tag_id'=>$tagId2));
		// $param['attention_num'] = M()->Table('bk_recommend')->where('object_type = 10 and object_id='.$tagId2)->count();

		//获的真正的使用次数
		$param['usetimes'] 		= M()->Table('boqii_tag_object')->where('status = 0 and tag_id = '.$tagId2)->count();
		//合并别名，去除重复
		$alias1		= $this->where(array('id'=>array('in',$tagId1)))->getField('alias',true);
		$alias1		= implode(' ', $alias1);
		
		$alias2		= $this->where('id='.$tagId2)->getField('alias');
		$alias 		= trim($alias1).' '.trim($alias2);
		$alias  	= array_unique(explode(' ', $alias));
		
		$param['alias']	= implode(' ', $alias);
		$this->where('id='.$tagId2)->save($param);
		// 逻辑删除合并标签一        					
		$res = $this->where(array('id'=>array('in',$tagId1)))->save(array('status'=>-1));
		// echo M()->getLastSql();echo $res;
		return $res;
	}

	/*
	 * 合并标签关系
	 * $tagId1 int 标签一：被合并的
	 * $tagId2 int 标签二
	 * 			
	 * return bool
	 */
	public function mergeRelation($tagId1,$tagId2){
		// echo $tagId2;
		// 删除父级关系转接
		M()->Table('uc_tag_relation')->where('status = 0 and tag_id='.$tagId1)->setField('status',-1);
		// 如有子级分类关系
		$subTagList = M()->Table("uc_tag_relation")->where('status = 0 and parent_tag_id='.$tagId1)->getField('tag_id',true);
		if ($subTagList) {
			// 获得未分类标签id
			$pTagId = $this-> getBkParentTagId('未分类');
			foreach ($subTagList as $key => $val) {
				$isRepeat[$key] = M()->Table("uc_tag_relation")->where('status = 0 and tag_id='.$val)->count();
				if ($isRepeat[$key] == 1) {
					M()->Table("uc_tag_relation")->where('tag_id='.$val)->setField('parent_tag_id',$pTagId);
				}else{
					M()->Table("uc_tag_relation")->where('status=0 and tag_id='.$val.' and parent_tag_id='.$tagId1)->setField('status',-1);
				}
				// echo M()->getLastSql();echo '<br>';
			}
			// print_r($subTagList);exit;
		}
		
		// 文章，问答，词条关系转接
		M()->Table('boqii_tag_object')->where('tag_id='.$tagId1)->setField('tag_id',$tagId2);

		// 标签用户关注关系 不用继承，直接干掉
		M()->Table('bk_recommend')->where('object_type = 10 and object_id='.$tagId1)->delete();
		
		//查到重复的 文章或者词条或者问答与标签的关系 id
		$relationIdList = M()->query('select tid from (select id as tid,count(*) as ct from boqii_tag_object group by tag_id,object_id,object_type having ct>1 ) as b');
		// echo '<pre>';print_r($relationIdList);exit;
		foreach ($relationIdList as $key => $val) {
			M()->Table('boqii_tag_object')->where('id='.$val['tid'])->setField('status',-1);
		}

		// 相关字段更新：别名,文章，问答，词条数量,关注数
		$param['article_num'] 	= $this->getObjectNum(array('type'=>1,'tag_id'=>$tagId2));
		$param['thread_num'] 	= $this->getObjectNum(array('type'=>2,'tag_id'=>$tagId2));
		$param['entry_num'] 	= $this->getObjectNum(array('type'=>3,'tag_id'=>$tagId2));
		// $param['attention_num'] = M()->Table('bk_recommend')->where('object_type = 10 and object_id='.$tagId2)->count();

		//获的真正的使用次数
		$param['usetimes'] 		= M()->Table('boqii_tag_object')->where('status = 0 and tag_id = '.$tagId2)->count();
		//合并别名，去除重复
		$alias1		= $this->where('id='.$tagId1)->getField('alias');
		$alias2		= $this->where('id='.$tagId2)->getField('alias');
		$alias 		= trim($alias1).' '.trim($alias2);
		$alias  	= array_unique(explode(' ', $alias));
		
		$param['alias']	= implode(' ', $alias);
		$this->where('id='.$tagId2)->save($param);
		// 逻辑删除合并标签一        					
		$res = $this->where('id='.$tagId1)->save(array('status'=>-1));
		// echo M()->getLastSql();echo $res;
		return $res;
	}

	
	/*
	 * 变更历史 记录 增删改合
	 * $type 		 string 操作类型(ADD创建;MOD修改;DEL删除;MER合并;MOV移除;APP增加)
	 * $tag_id 		 int 	标签id
	 * $column 		 string 修改的字段
	 * $changeBefore string 修改前的内容
	 * $changeAfter	 string 修改后的内容		
	 * $mergeId 	 int 	合并标签使用，合并标签
	 */
	public function addTagHistory($type,$tag_id,$column=null,$changeBefore=null,$changeAfter=null,$mergeId=null){
		//字段名称
		$column_name = array('name'=>'名称','logo'=>'图片','alias'=>'别名','memo'=>'描述','locked'=>'锁定');
		$tagName = $this->getInfoByTagId($tag_id,'name');
		switch ($type) {
			case 'ADD':
				$data['operate_desc'] = '标签';
				break;
			case 'DEL':
				$data['operate_desc'] = '标签';
				break;
			case 'MOD':
				$data['operate_desc'] = $column_name[$column];
			  	break;
			case 'MER':
				$mergeTagName = $this->getInfoByTagId($mergeId,'name');
			  	$data['operate_desc'] =  $mergeTagName['name'];
			  	break;
			case 'MOV':
			  	$data['operate_desc'] = $column;
			  	break;
		  	case 'APP':
			  	$data['operate_desc'] = $column;
			  	break;
			default:
				return '未进行任何操作！';
				break;
		}
		$data['uid']			= session('boqiiUserId');
		$data['tag_id']			= $tag_id;
		$data['user_type']		= 10;
		$data['username']		= session('boqiiUserName');
		$data['operate_act']	= $type;
		$data['operate_field']	= $type == 'MOD' ? $column : '';
		$data['value_before']	= $changeBefore ? $changeBefore : ($column == 'locked'? 0 : '' ); 
		$data['value_after']	= $changeAfter ? $changeAfter : ($column == 'locked' ? 0 : '' )	; 
		$data['id_after']		= $mergeId ? $mergeId : 0;
		$data['create_time']  	= time();
		M()->Table('uc_tag_history')->add($data);
		// echo M()->getLastSql();exit;
	}

	/*
	 * 通过标签id获得相应历史操作数据
	 *  $id int 标签名
	 *	$field string 获得数据
	 * return array 数据
	 */
	public function getTagHistory($id){
		$tagHistory = M()->Table('uc_tag_history')->where('tag_id='.$id)->order('create_time desc')->select();
		foreach ($tagHistory as $key => $val) {
			if ($val['operate_act'] == 'ADD') {
				$tagHistory[$key]['str'] = $val['username'].' 创建 '.$val['operate_desc'];
			}elseif ($val['operate_act'] == 'DEL') {
				$tagHistory[$key]['str'] = $val['username'].' 删除 '.$val['operate_desc'];
			}elseif ($val['operate_act'] == 'MOD') {
				if ($val['operate_field'] == 'logo') {
					$tagHistory[$key]['str'] = $val['username'].' 修改 '.$val['operate_desc'].': <br/> <img src="'.C(IMG_DIR).'/'.$val['value_before'].'" width="64" /><br/>  改为：<br/> <img src="'.C(IMG_DIR).'/'.$val['value_after'].'"  width="64" />';
				}else{
					$tagHistory[$key]['str'] = $val['username'].' 修改 '.$val['operate_desc'].' '.$val['value_before'].'   改为：'.$val['value_after'];
				}
			}elseif ($val['operate_act'] == 'MER') {
				$tagHistory[$key]['str'] = $val['username'].' 合并到标签：'.$val['operate_desc'];
			}elseif ($val['operate_act'] == 'MOV') {
				$tagName = explode('：', $val['operate_desc']);
				$tagStr  = str_replace($tagName[1], '<a href="/iadmin.php/Tag/index?data[keyword]='.$tagName[1].'" >'.$tagName[1].'</a>', $val['operate_desc']);
				$tagHistory[$key]['str'] = $val['username'].' 移除 '.$tagStr;
			}elseif ($val['operate_act'] == 'APP') {
				$tagName = explode('：', $val['operate_desc']);
				$tagStr  = str_replace($tagName[1], '<a href="/iadmin.php/Tag/index?data[keyword]='.$tagName[1].'" >'.$tagName[1].'</a>', $val['operate_desc']);
				$tagHistory[$key]['str'] = $val['username'].' 添加 '.$tagStr;
			}
			$tagHistory[$key]['create_time'] = $val['create_time']?(date('Y-m-d H:i:s',$val['create_time'])):'';

		}
		return $tagHistory;
	}

	/*
	 * 删除标签
	 * @id int 标签id 
	 * 		
	 * return bool
	 */
	public function delTag($id){
		$tag = $this->where(array('id'=>$id))->find();
		// 删除标签数据
		$res = $this->where(array('id'=>$id))->save(array('status'=>-1));
		if($tag['type']==11){
			// 删除相关百科关系标签
			M()->Table("boqii_tag_object")->where(array('tag_id'=>$id))->save(array('status'=>-1));
			// 删除用户关注标签关系
			M()->Table("bk_recommend")->where(array('object_id'=>$id,'object_type'=>10))->delete();
			
			// 删除父子级标签关系 todo
			M()->Table("uc_tag_relation")->where('status=0 and tag_id='.$id)->setField('status',-1);
			// M()->Table("uc_tag_relation")->where(array('tag_id'=>$id,'parent_tag_id'=>10,'_logic'=>'or'))->setField('status',-1);
			// 如有子级分类关系
			$subTagList = M()->Table("uc_tag_relation")->where('status = 0 and parent_tag_id='.$id)->getField('tag_id',true);
			
			if ($subTagList) {
				// 获得未分类标签id
				$pTagId = $this-> getBkParentTagId('未分类');
				foreach ($subTagList as $key => $val) {
					$isRepeat[$key] = M()->Table("uc_tag_relation")->where('status = 0 and tag_id='.$val)->count();
					
					if ($isRepeat[$key] == 1) {
						M()->Table("uc_tag_relation")->where('tag_id='.$val)->setField('parent_tag_id',$pTagId);
					}else{
						M()->Table("uc_tag_relation")->where('status=0 and tag_id='.$val.' and parent_tag_id='.$id)->setField('status',-1);
					}
					// echo M()->getLastSql();echo '<br>';
				}
				// print_r($subTagList);exit;
			}
			
		}else if ($tag['type']==15) {
			M('news_information_tag')->where(array('tag_id'=>$id))->setField('status',-1);
			$usetimes = M('news_information_tag')->where(array('tag_id'=>$id,'status'=>0))->count();
			$this->where(array('id'=>$id))->setField('usetimes',$usetimes);
		}
		return $res;
	}

	/**
     * 通过标签名获得该分类的id
     * $name string 标签名
	 */
	public function getBkParentTagId($name){
		return $this->where(array('name'=>$name,'status'=>array('egt',0),'type'=>11))->getField('id');
	}

	/**
     * 添加标签的父子级关系
     * @param 	$tagId 	int 标签id
     *			$pid 	int 添加父级或者子级标签id
     * 		   	$type 	int 1:添加父标签关系 2：添加子标签关系 
	 */
	public function addTagRelation($param){
		// print_r($param);exit;
		if ($param['type'] == 1) {
			$tagRelation = array('tag_id'=>$param['tagId'],'parent_tag_id'=>$param['pid'],'create_time'=>time(),'status'=>0);
			$column = '父标签';
		}else if ($param['type'] == 2) {
			$tagRelation = array('tag_id'=>$param['pid'],'parent_tag_id'=>$param['tagId'],'create_time'=>time(),'status'=>0);
			$column = '子标签';
		}
		$res = M()->Table('uc_tag_relation')->add($tagRelation);
		if ($res) {
			$tagName = $this->where('id = '.$param['pid'])->getField('name');
			// 添加标签变更历史记录
			$this -> addTagHistory('APP',$param['tagId'],$column.'：'.$tagName);
		}
		
		return $res;
	}

	/**
	 * 记录标签操作历史记录
	 *
	 * @param $param array 参数数组
	 *			tag_id int 标签id
	 *			operate_act string 操作类型(ADD创建;MOD修改;DEL删除;MER合并;MOV移除;APP增加)
	 *			operate_field string 操作字段
	 *			operate_desc string 操作描述
	 *			value_before string 操作前值
	 *			id_after int 操作后id值
	 *			value_after string 操作后值
	 *			
	 */
	private function addTagHistoryLog($param) {
		// 后台操作者
		$data['uid'] = session('boqiiUserId');
		$data['tag_id'] = $param['tag_id'];
		// user_type int 用户类型0：前台；10：后台
		$data['user_type'] = 10;
		// 后台操作者名
		$data['username'] = session('boqiiUserName');
		$data['operate_act'] = $param['operate_act'];
		$data['operate_field'] = $param['operate_field'];
		$data['operate_desc'] = $param['operate_desc'];
		$data['value_before'] = $param['value_before'];
		if($param['id_after']) {
			$data['id_after'] = $param['id_after'];
		}
		if($param['value_after']) {
			$data['value_after'] = $param['value_after'];
		}
		$data['create_time'] = time();
		M()->Table('uc_tag_history')->add($data);

	}
	/**
     * 添加标签的父子级关系
     * @param 	$tagId 	int 标签id
     *			$pid 	int 删除父级或者子级标签id
     * 		   	$type 	int 1:删除父标签关系 2：删除子标签关系 
	 */
	public function delTagRelation($param){
		// print_r($param);exit;
		if ($param['type'] == 1) {
			$where = array('tag_id'=>$param['tagId'],'parent_tag_id'=>$param['pid']);
			$column = '父标签';
		}else if ($param['type'] == 2) {
			$where = array('tag_id'=>$param['pid'],'parent_tag_id'=>$param['tagId']);
			$column = '子标签';
		}
		$res = M()->Table('uc_tag_relation')->where($where)->save(array('status'=>-1));
		if ($res) {
			$tagName = $this->where('id = '.$param['pid'])->getField('name');
			// 添加标签变更历史记录
			$this -> addTagHistory('MOV',$param['tagId'],$column.'：'.$tagName);
		}
		
		return $res;
	}

	/**
	 * 判断是否标签的子系
	 *
	 * @param $tag_id int 标签id
	 * @param $ptagid string 父标签id，多个用','串接
	 *
	 * @return boolean 处理结果
	 */
	private function isTagChild($tagid, $ptagid) {
		// 所有子标签
		$stagids = M()->Table('uc_tag_relation')->where('status=0 AND parent_tag_id IN ('.$ptagid.')')->getField('tag_id', true);
		if($stagids) {
			// 子标签
			if(in_array($tagid, $stagids)) {
				return true;
			} 
			// 不是子标签
			else {
				return $this->isTagChild($tagid, implode(',', $stagids));
			}
		}

		return false;
	}

	/**
	 * 更新标签与文章，词条，问答的关系数，使用次数
	 *
	 * @param array 标签数组
	 * 		$type 1.文章，2.问答。3。词条
	 *
	 */
	public function updateTagRelationNum($param,$type) {
		if (empty($param)) {
			return;
		}
		// 更新相关标签的使用次数 根据类型更新文章，问答，词条数量
		foreach ($param as $k => $v) {
			$usetimes = M()->Table('boqii_tag_object')->where('status=0 and tag_id='.$v)->count();
			$this->where('id='.$v)->setField('usetimes',$usetimes);
			
			$entryNum = M()->Table('boqii_tag_object')->where('status=0 and object_type='.$type.' and tag_id='.$v)->count();
			$this->where('id='.$v)->setField('entry_num',$entryNum);
		}
	}

	/**
     * 添加文章，词条标签的关系
     * @param 	$objId 	int 文章或者词条id
     *			$id 	int 添加文章或者词条id
     * 		   	$type 	int 1:添加文章标签 3：添加词条标签关系 
	 */
	// public function addTagObject($param){
	// 	$tagRelation = array('tag_id'=>$param['id'],'object_id'=>$param['objId'],'create_time'=>time(),'object_type'=>$param['type'],'status'=>0);
	// 	$res = M()->Table('boqii_tag_object')->add($tagRelation);
	// 	if ($res) {
	// 		// 更新标签使用数
	// 		$this->where('id='.$param['id'])->setInc('usetimes');
	// 		// 更新相关 文章或问答或词条数量
	// 		if ($param['type'] == 1) {
	// 			$column = 'article_num';
	// 		}else if ($param['type']) {
	// 			$column = 'entry_num';	
	// 		}
	// 		$this->where('id='.$param['id'])->setInc($column);
	// 	}
		
	// 	return $res;
	// }

	/**
     * 删除文章，词条标签的关系
     * @param 	$objId 	int 文章或者词条id
     *			$id 	int 删除文章或者词条id
     * 		   	$type 	int 1:删除文章标签关系 3：删除词条标签关系 
	 */
	// public function delTagObject($param){
	// 	$res = M()->Table('boqii_tag_object')->where(array('object_id'=>$param['objId'],'object_type'=>$param['type'],'tag_id'=>$param['id']))->delete();
	// 	if ($res) {
	// 		// 更新标签使用数
	// 		$this->where('id='.$param['id'])->setDec('usetimes');
	// 		// 更新相关 文章或问答或词条数量
	// 		if ($param['type'] == 1) {
	// 			$column = 'article_num';
	// 		}else if ($param['type']) {
	// 			$column = 'entry_num';
	// 		}
	// 		$this->where('id='.$param['id'])->setDec($column);
	// 	}
		
	// 	return $res;
	// }

	/**
     * 查询类似的标签名
     * @param   $tagId 		int 	标签id
     *			$tagName 	string 	标签名
	 */
	public function getLikeTag($param){
		// print_r(C("TAG_LIMIT"));exit;
		$tagStr = '';
		// 标签编辑页面 添加父子级标签 时使用
		if ($param['tagId']) {
			
			$parTagRelation = M()->Table('uc_tag_relation')->where('status = 0 and tag_id = '.$param['tagId'])->getField('parent_tag_id',true);
			$subTagRelation = M()->Table('uc_tag_relation')->where('status = 0 and parent_tag_id = '.$param['tagId'])->getField('tag_id',true);
			$tagRelation = array_merge_recursive($parTagRelation,$subTagRelation);
			$tagStr .= $param['tagId'].','.implode(',', $tagRelation);
			
		}
		// 文章，问答，词条编辑页面 添加标签 时使用
		// if ($param['objId']) {
		// 	$objectTagRelation = M()->Table('boqii_tag_object')->where('status = 0 and object_type = '.$param['type'].' and object_id = '.$param['objId'])->getField('tag_id',true);

		// 	$tagStr = implode(',', $objectTagRelation);
		// 	$where['id'] = array('not in',$tagStr);
		// }
		// 不显示根标签和未分类标签
		$tagLimit = C("TAG_LIMIT");
		foreach ($tagLimit as $val) {
			$tagids[] = $this->where('status >= 0 and name = "'.$val.'"')->getField('id');
		}
		$tagStr .= ','.implode(',', $tagids);
		$where['id'] = array('not in',$tagStr);
		// 生成条件
		$where['type'] = 11;
		$where['name'] = array('like','%'.$param['tagName'].'%');
		$where['status'] = array('egt',0); 

		$data = $this->where($where)->field('id,name')->order('usetimes desc')->limit(10)->select();
		
		// echo M()->getLastSql();
		// echo "<pre>";print_r($data);
		return $data;
	}



	/*
	 * 添加标签
	 * @param array 
	 * 			name string 标签名
	 * 			logo string 图片名
	 * 			alias string 别名 空格隔开多个别名
	 *			memo string 标签描述
	 * 			type int 标签类型
	 *		$ptag string 父级标签 多个
	 * return bool
	 */
	public function addTag($param,$ptag){
		$param['dateline']   = time();
		$param['updatetime'] = time();
// print_r($param);exit;
		$res = $this->add($param);
		// 添加标签变更历史记录
		$this -> addTagHistory('ADD',$res,$param['name']);
		//添加和父级标签的关系
		if ($res) {
			if ($ptag) {
				M()->Table('uc_tag_relation')->add(array('tag_id'=>$res,'parent_tag_id'=>$ptag,'create_time'=>time()));
				$tagName = $this->where('id='.$ptag)->getField('name');
				$this -> addTagHistory('APP',$res,'父标签：'.$tagName);
			}else{
				// 针对“百科标签”类，新增的标签都默认添加为“未分类标签”的子标签。
				if ($param['type'] == 11) {
					$ptagid = $this->getBkParentTagId(C('TAG_LIMIT.2'));
					M()->Table('uc_tag_relation')->add(array('tag_id'=>$res,'parent_tag_id'=>$ptagid,'create_time'=>time()));
					$this -> addTagHistory('APP',$res,'父标签：未分类');	
				}
				else{
					$ptagid = $this->getBkParentTagId(C('TAG_LIMIT.1'));
					M()->Table('uc_tag_relation')->add(array('tag_id'=>$res,'parent_tag_id'=>$ptagid,'create_time'=>time()));
					$this -> addTagHistory('APP',$res,'父标签：根标签');	
				}
			}	
		}
		return $res;
		//echo '<pre>';print_r($param);exit;
	}

	/*
	 * 更新标签
	 * @param array 
	 * 			name string 标签名
	 * 			logo string 图片名
	 * 			alias string 别名 空格隔开多个别名
	 *			memo string 标签描述
	 * 			locked int 标签是否锁定
	 *		$ptag string 父级标签 多个
	 *		$stag string 子级标签 多个
	 * return bool
	 */
	public function saveTag($param,$ptag,$stag){
		//设定其他数据库值
		$param['updatetime'] = time();
		// print_r($param);exit;
		$res = $this->save($param);
		// echo $this->getLastSql();
		//添加和父级标签的关系
		// if ($res && !empty($ptag)) {
		// 删除旧的标签关系表
		// 	M()->Table('uc_tag_relation')->where('tag_id='.$param['id']))->delete();
		// 	$tagArr = explode(',', $ptag);
		// 	foreach ($tagArr as $val) {
		// 		M()->Table('uc_tag_relation')->add(array('tag_id'=>$param['id'],'parent_tag_id'=>$val,'create_time'=>time()));
		// 	}
		// }

		//添加和子级级标签的关系
		// if ($res && !empty($ptag)) {
		// 删除旧的标签关系表
		//  M()->Table('uc_tag_relation')->where('parent_tag_id='.$param['id']))->delete();
		// 	$tagArr = explode(',', $stag);
		// 	foreach ($tagArr as $val) {
		// 		M()->Table('uc_tag_relation')->add(array('parent_tag_id'=>$param['id'],'tag_id'=>$val,'create_time'=>time()));
		// 	}
		// }
		return $res;
		//echo '<pre>';print_r($param);exit;
	}
	

	/************************************ 资讯标签 START ************************************/

	/**
	 * 获取标签列表
	 *
	 * @param $page int 当前页码
	 * @param $limit int 页显数量
	 * @param $where string 查询条件
	 * @param $order string 排序条件
	 * 
	 * @return array 标签列表数组
	 *
	 */
	public function getNewsTagList($page,$limit,$where,$order){
		//标签列表
		$tagList = $this->table('uc_tag tag')->field('tag.id,tag.name,tag.dateline,tag.usetimes,tag.resourtimes,tag.uid,tag.type,tag.status,tag.locked')->order($order)->where($where)->limit($limit)->page($page)->select();

		$adminModel = D('UcAdmin');
		foreach($tagList as $key=>$val) {
			// 根据id获取管理员信息
			$admin = $adminModel->getAdminInfoById($val['uid']);
			$tagList[$key]['username'] = $admin['username'];
			$tagList[$key]['truename'] = $admin['truename'];
			
		}

		return $tagList;
	}

	/**
     * 关联资讯标签和资讯文章
     * $param $id int 资讯文章id
     *			$tagId int 标签id
	 */
	public function relationTagAndNews($id,$tagId){
		M('news_information_tag')->add(array('tag_id'=>$tagId,'information_id'=>$id,'create_time'=>time()));
		$usetimes = M('news_information_tag')->where(array('tag_id'=>$tagId,'status'=>0))->count();
		$this->where(array('id'=>$tagId))->setField('usetimes',$usetimes);
	}

	/**
     * 解除关联资讯标签和资讯文章
     * $param $id int 资讯文章id
     *			$tagId int 标签id
	 */
	public function cancelTagAndNews($id,$tagId){
		M('news_information_tag')->where(array('tag_id'=>$tagId,'information_id'=>$id))->delete();
		$usetimes = M('news_information_tag')->where(array('tag_id'=>$tagId,'status'=>0))->count();
		$this->where(array('id'=>$tagId))->setField('usetimes',$usetimes);
	}

	/**
     * 通过资讯标签id查询相关联的资讯文章id
     * $param $id int 资讯文章id
     *	return array 文章id集合
	 */

	public function getNewsIdByTagId($tagId){
		return M('news_information_tag')->where(array('tag_id'=>$tagId,'status'=>0))->getField('information_id',true);
	}

	/**
     * 查看是否存在资讯标签和资讯文章是否关联
     * $param $id int 资讯文章id
     *			$tagId int 标签id
	 */
	public function tagAndNewsRelationIsExists($id,$tagId){
		return M('news_information_tag')->where(array('tag_id'=>$tagId,'information_id'=>$id,'status'=>0))->getField('id');
	}

	/**
     * 通过资讯标签名判断资讯标签是否存在
     * $param $tagName string 资讯标签名
     *	
	 */
	public function newsTagIsExists($tagName){
		 $id = $this->where(array('name'=>$tagName,'status'=>array('egt',0),'type'=>15))->getField('id');
		 return $id;
	}
}
?>
<?php
/**
 * BkThread Model类
 */
class BkThreadModel extends Model{
	
	protected $tableName='bk_thread';
	
	/**
	 * 获取问答列表
	 *
	 * @param $param array 参数数组
	 *
	 * @return array 查询结果数组
	 */
	public function getAskList($param) {
		// 查询条件
		$where = '1';
		// 问答标题
		if(!empty($param['title'])) {
			$where = $where ." AND a.title like '%".$param['title']."%' ";
		}
		// 问答标题
		if(!empty($param['tag_name']) && $param['tag_name']!='输入标签关键字') {
			$tag_id = M()->Table('uc_tag')->where('status=0 and type=11 and name="'.$param['tag_name'].'"')->getField('id');
			
			$threadArr = M()->Table('boqii_tag_object')->where('status=0 and object_type=2 and tag_id='.$tag_id)->getField('object_id',true);
			
			$where .= " and a.id in (".trim(implode(',', $threadArr),',').")";

		}
		// 发布时间开始时间
		if(!empty($param['starttime'])) {
			$where = $where ." AND a.create_time >= ".strtotime($param['starttime'].' 00:00:00');
		}
		// 发布时间结束时间
		if(!empty($param['endtime'])) {
			$where = $where ." AND a.create_time <= ".strtotime($param['endtime'].' 23:59:59');
		}
		// 用户昵称/id
		if(!empty($param['user'])) {
			if($param['select'] == 1) {
				$uids = M()->Table('boqii_users')->where('nickname like "%' . $param['user'] . '%"')->getField('uid', true);
				if($uids) {
					$where = $where ." AND a.uid IN (". implode(',', $uids) .")";
				} else {
					return array();
				}
			} else {
				$where = $where ." AND a.uid = ". intval($param['user'])."";
			}
		}
		// 是否已审核
		if($param['is_check'] != -1) {
			$where = $where ." AND a.is_check = ".$param['is_check'];
		}
		// 是否精华
		if($param['is_digest'] != -1) {
			$where = $where ." AND a.is_digest = ".$param['is_digest'];
		}
		// 是否置顶
		if($param['is_top'] != -1) {
			$where = $where ." AND a.status = ".$param['is_top'];
		} else {
			$where .= " AND a.status != -1";
		}
		// print_r($where);
		// 是否紧急
		if($param['is_urgent'] != -1) {
			$where = $where ." AND a.is_urgent = ".$param['is_urgent'];
		}
		// 三级分类
		if(!empty($param['thirdCatId'])) {
			$where = $where ." AND a.cat_id = ".$param['thirdCatId']."";
		}
		// 没有选择三级分类，选择了二级分类
		if(!empty($param['secondCatId']) && empty($param['thirdCatId'])){
			$thirdCatIdList = D('BkArticle')->getSubCatListByParentId($param['secondCatId']);
			foreach($thirdCatIdList as $v){
				$thirdCatIds[] = $v['id'];
			}
			$strThirdCatIds = implode(",",$thirdCatIds);
			$where = $where ." AND a.cat_id in (".$strThirdCatIds.")";
		}
		// 没有选择三级分类和二级分类，选择了一级分类
		if(!empty($param['firstCatId']) && empty($param['secondCatId']) && empty($param['thirdCatId'])){
			// 一级分类下的所有二级分类
			$secondCatIdList = D('BkArticle')->getSubCatListByParentId($param['firstCatId']);
			foreach($secondCatIdList as $v){
				$secondCatIds[] = $v['id'];
			}
			$strSecondCatIds = implode(",",$secondCatIds);
			// 一级分类下的所有三级分类
			$thirdCatIdList = D('BkArticle')->getSubCatListByParentIds($strSecondCatIds);
			foreach($thirdCatIdList as $v){
				$thirdCatIds[] = $v['id'];
			}
			$strThirdCatIds = implode(",",$thirdCatIds);

			$where = $where ." AND a.cat_id in (".$strThirdCatIds.")";
		}
		// 排序
		$order = $param['order'];
		switch($order) {
			case "1":
				$order = " a.create_time DESC";
				break;
			case "2":
				$order = " a.view_num DESC";
				break;
			case "3":
				$order = " a.comment_num DESC";
				break;
			default:
				$order = " a.create_time DESC";
				break;
		}
		// 分页参数
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['pageNum']?$param['pageNum']:20;
		$pageStart = ($page-1)*$pageNum;
		// 总记录数
		$this->total = M()->Table("bk_thread a")->join("bk_cat b ON a.cat_id = b.id")->where($where)->count();
		$listarr =  M()->Table("bk_thread a")->field("a.*,b.name")->join("bk_cat b ON a.cat_id = b.id")->where($where)->order($order)->limit("$pageStart, $pageNum")->select();

//		$this->total = M()->Table("bk_article a")->join("bk_category b ON a.cat_id = b.id")->where($where)->count();
//		$listarr =  M()->Table("bk_article a")->field("a.*,b.name")->join("bk_category b ON a.cat_id = b.id")->where($where)->order($order)->limit("$pageStart, $pageNum")->select();
// echo M()->getLastSql();
		//当前页条数
		$this->subtotal = count($listarr);
		//总页数
		$this->pagecount = ceil(($this->total)/$pageNum);
		$list = array();
		$apiModel = D('Api');
		foreach($listarr as $lists){		
			// 时间
			$lists["create_time"] = date('Y-m-d H:i',$lists["create_time"]);
			// 昵称
			$user = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $user['nickname'];
			// 浏览数
			$lists['view_num'] = $this->getRedisViewNum($lists['id'], $lists['view_num'], 'thread');

			$list[] = $lists;
		}
		//print_r($list);
		return $list;
	}

	/*
	*百科帖子和用户关联查询
	*/
	public function hasUserAndThread($page,$limit,$where, $order){
		switch($order) {
			case "1":
				$orderby = " thread.create_time DESC";
				break;
			case "2":
				$orderby = " thread.view_num DESC";
				break;
			case "3":
				$orderby = " thread.comment_num DESC";
				break;
			default:
				$orderby = " thread.create_time DESC";
				break;
		}

		$list = $this->table('bk_thread thread,boqii_users user,bk_category cat')->field('thread.id,thread.title,thread.create_time,thread.view_num,thread.comment_num,thread.uid,thread.is_check,user.nickname,cat.name')->where($where)->order($orderby)->limit($limit)->page($page)->select();

		return $list;
	}
	
	/**
	 * 获取redis中的浏览数
	 *
	 * @param $id int 对象id
	 * @param $dbViewNum 数据库浏览数（默认为-1，当redis中没有问答浏览数时需要查数据库获取）
	 * @param $type string 类型（默认为thread）
	 *
	 * @return int 浏览数
	 */
	public function getRedisViewNum($id, $dbViewNum = -1, $type = 'thread') {
		// 采用redis记录问答浏览数
		$cacheRedis = Cache::getInstance('Redis');
		$key = C('REDIS_KEY.' . $type . 'Views').$id;
		$redisViews = $cacheRedis->get($key);
		// redis不存在，查询数据库
		if(!$redisViews){
			if($dbViewNum != -1) {
				$viewNum = $dbViewNum;
			} else {
				// 获取问答浏览数
				$viewNum = M()->Table('bk_thread')->where('id ='.$id)->getField('view_num');
			}
			$cacheRedis->set($key,$viewNum);
		}else{
			$viewNum = $redisViews;
		}

		return $viewNum;
	}

	/*
	*获取百科帖子个数
	*/
	public function hasThreadCount($where){
		$result = $this->table('bk_thread thread,boqii_users user,bk_category cat')->where($where)->count();
		return $result;
	}

	/**
	 * 小组列表
	 */
	public function getTeamList() {
		$teamList = M()->Table('bk_team')->where('status>=0')->field('id,name')->select();
		return $teamList;
	}

	/**
	 * 所有子分类列表
	 */
	public function getAllSubCategoryList() {
		$catList = M()->Table('bk_category')->where('status=0 AND level=2')->field('id,name')->order('id')->select();
		return $catList;
	}

	/*
	*百科帖子内容 $photo_id图片ID $diary_id 百科帖子ID
	*/
	public function deleteThreadPhoto($photo_id,$diary_id){
		$thread = $this->where(array('id'=>$diary_id))->select();
		preg_match_all("/<img.*>/U", $thread[0]['content'],$matches);//带引号
		$new_arr=array_unique($matches[0]);//去除数组中重复的值 
		//整理成一个一维数组
		foreach($new_arr as $key){ 
			$arr[]=$key; 	
		}
		foreach($arr as $key=>$val){
			$intLastPosition = strripos($val,'pid="'.$photo_id.'"');
			if($intLastPosition){
				$k = $key;
			}
		}
		$content = str_replace($arr[$k],'',$thread[0]['content']);
		$this->where(array('id'=>$diary_id))->save(array('content'=>$content));
	}

	/**
	 * 帖子详细
	 *
	 * @param $id int 帖子ID
	 */
	public function getThreadDetail($id) {
		$thread = $this->where(array('id'=>$id))->field('id,cat_id,team_id,title,content,recommend_num,comment_num,view_num,create_time,update_time,uid,lastpost_uid,lastpost_nickname,lastpost_time,status,is_digest,is_urgent,expert_id,question_status,question_reply_time,is_check,variety,pet_age,pet_sex,lasttime_vaccine,lasttime_insecticide,lasttime_outsecticide')->find();
		
		$thread['create_time'] = date('Y-m-d H:i:s', $thread['create_time']);

//		//分类
//		$cat = M()->Table('bk_category')->where('id='.$thread['cat_id'])->field('name')->find();
//		$thread['name'] = $cat['name'];
		$cat = M()->Table('bk_cat')->where('id='.$thread['cat_id'])->field('name')->find();
		$thread['name'] = $cat['name'];
		//用户
		$user = M()->Table('boqii_users')->where('uid='.$thread['uid'])->field('uid,nickname')->find();
		$thread['nickname'] = $user['nickname'] ? $user['nickname'] : $user['uid'];
		// TODO标签
		$tagArr = M()->Table('boqii_tag_object a')->join('uc_tag b on a.tag_id = b.id')->where('a.object_type = 2 and a.status = 0 and a.object_id ='.$thread['id'].' and b.type = 11 and b.status = 0')->order('a.id ASC')->getField('name', true);

		$tagstr = implode(" ",$tagArr);
		$thread['tags'] = $tagstr;

		return $thread;
	}

	/**
	* 审核
	*/
	public function check($param){
		$id = $param['id'];
		// 是否已经通过审核
		$ischeck = $this->where(array('id'=>$id))->getField('is_check');
		if(!$ischeck) {
			// 审核通过
			$this->where(array('id'=>$id))->save(array('is_check'=>1));
			
			// 帖子
			$thread = $this->where(array('id'=>$id))->field('uid,cat_id,title,id,is_check')->find();
			// 审核
			if($thread['is_check'] == 1) {
				// 更新+3人气和+3啵币
				$this->updateMemberExtcredits(array('authorid'=>$thread['uid'], 'score'=>3));
				// 分类帖子数
				M() -> Table('bk_category') -> where('id=' . $thread['cat_id']) -> setInc('thread_num', 1); 

				// 百科发帖动态
				// type=8 operatetype=3 百科发帖
				$dynamic['uid'] = $thread['uid'];
				$dynamic['type'] = 8;
				$dynamic['operatetype'] = 3;
				$dynamic['oid'] = $thread['id']; //帖子编号
				//$dynamic['mid'] = $thread['team_id']; //小组编号
				$dynamic['mid'] = $thread['cat_id']; //分类ID
				add_dynamic($dynamic); 

				// 帖子新增记录
				$mparam['id'] = $thread['id'];
				$mparam['action'] = 'ADD';
				$mparam['uid'] = $thread['uid'];
				$this -> _logThreadmod($mparam); 
				// 更新搜索库
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=add&param[config_object]=1&param[pid]=" . $thread['id'] . "&param[type]=2";
				get_url($url);

				// 发送站内信
				$url = get_rewrite_url('BkThread', 'thread', $thread['id']);
				$notice = '您的帖子《' . urlencode('<a href="' . $url . '"  target="_blank">' . $thread['title'] . '</a>')  .'》被审核通过。';
				$url = C("I_DIR") . "/iadmin.php/Index/getNotice?to_uid=" . $thread['uid'] . "&notice_type=111&param[content]=" . ($notice) ;
				get_url($url);
			}
		}
	}

	/**
	 * 记录帖子操作
	 *
	 * @param $param array 参数数组
	 *
	 */
	private function _logThreadmod($param) {
		$tdata['thread_id'] = $param['id'];
		$tdata['action'] = $param['action'];
		$tdata['uid'] = $param['uid']; 
		// 移动操作帖子原分类id
		if ($param['action'] == 'MOV') {
			$tdata['ocat_id'] = $param['ocat_id'];
		} 
		$tdata['create_time'] = time();
		if ($param['expire_day']) {
			$tdata['expire_time'] = time() + $param['expire_day'] * 86400;
		} 
		if (isset($param['remark']) && !empty($param['remark'])) {
			$tdata['remark'] = $param['remark'];
		} 
		M() -> Table('bk_threadmod') -> add($tdata);
	} 

	/**
	 * 发帖人增加人气/啵币
	 *
	 * @param $param array 参数数组
	 *						authorid int 发帖人id
	 *						score int 积分值/啵币值
	 */
	public function updateMemberExtcredits($param) {
		//实例化ApiModel类
		$apiModel = D('Api');
		//给发帖用户增加人气
		M()->Table('boqii_users')->where('uid='.$param['authorid'])->setInc('extcredits1', intval($param['score']));
		//给发帖用户增加啵币
		M()->Table('boqii_users')->where('uid='.$param['authorid'])->setInc('extcredits2', intval($param['score']));

		//判断用户组是否需要更改
		//取得用户数据
		$member = M()->Table("boqii_users u")->join("boqii_users_extendbbs m ON u.uid=m.uid")->where("u.uid=".$param['authorid'])->field("u.extcredits1,u.uid,m.groupid,m.adminid")->find();
		//普通用户组
		if($member['adminid'] <= 0) {
			$group = M()->Table("bbs_usergroups")->where("creditslower>".$member['extcredits1']." AND creditshigher<=".$member['extcredits1'])->field("groupid")->order("creditslower")->limit(1)->find();
			if($group['groupid'] != $member['groupid']) {
				//更新用户组
				$this->execute("UPDATE boqii_users_extendbbs SET groupid=".$group['groupid']." WHERE uid=".$param['authorid']);
			}
		}
		//更新用户缓存信息
		$apiModel->updateUserInfo($param['authorid']);
	}

	/**
	 * 保存问答
	 */
	public function editThread($param) {
		//标签
		$tagArrs = D('BkArticle')->getTags($param['tags']);
		//修改前文章信息
		$info = $this->getThreadDetail($param['id']);
			//当标签更改后
			if(trim($param['tags']) && trim($param['tags']) != $info['tags']) {
				//标签id
				$tagIds = M()->Table("boqii_tag_object")->where('object_type = 2 and object_id ='.$param['id'])->getField('tag_id', true);
				//修改标签前删除标签
				M()->Table("boqii_tag_object")->where('object_type = 2 and object_id ='.$param['id'])->delete();

				foreach($tagArrs as $k1=>$v1) {
					if($v1){
						$data3['tag_id'] 		= $v1;
						$data3['object_id'] 	= $param['id'];
						$data3['object_type'] 	= 2;
						$data3['create_time'] 	= time();
						M()->Table("boqii_tag_object")->add($data3);
						//标签id
						$tagIds[] = $v1;
					}
				}
				//统计标签使用次数
				foreach($tagIds as $key=>$val) {
					$usetimes = M()->Table('boqii_tag_object')->where('object_type = 2 and tag_id='.$val.' AND status=0')->count();
					M()->Table('uc_tag')->where('id='.$val)->save(array('usetimes'=>$usetimes));	
				}
			}

			return true;
	}
}
?>
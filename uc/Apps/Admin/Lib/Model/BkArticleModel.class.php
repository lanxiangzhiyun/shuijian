<?php
/**
 * 百科文章Model类
 *
 * @created yumie
 * @modified by Fongson 2014-07-23 去除文章内容中指定的标签链接
 * @modified by Fongson 2014-09-28 百科改版
 */
class BkArticleModel extends RelationModel {
	// 数据库表名
	protected $tableName = "bk_article";
	
	/**
	 * 获取文章列表
	 *
	 * @param $param array 参数数组
	 *					keyword string 文章标题关键字（模糊匹配）
	 *					starttime string 查询开始时间
	 *					endtime string 查询结束时间
	 *					user string 文章作者（模糊匹配）
	 *					thirdCatId int 三级分类id
	 *					secondCatId int 二级分类id
	 *					firstCatId int 一级分类id
	 *					order string 排序字段（倒序）
	 *					page int 当前页码
	 *					pageNum int 页显数量
	 *
	 * @return array 查询结果数组
	 */
	public function getArticleList($param) {
		// 查询条件
		// print_r($param);
		// 文章标题模糊查询
		$keyword = $param['keyword'];
		// 发布时间开始时间
		$starttime = $param['starttime'];
		// 发布时间结束时间
		$endtime = $param['endtime'];
		// 发布人
		$user = $param['user'];
		// 排序
		$order = $param['order'];
		
		$where = "a.status != -1";
		// 文章标题
		if(!empty($keyword)) {
			$where = $where ." and a.title like '%".$keyword."%' ";
		}
		// 文章发布时间
		if(!empty($starttime)) {
			$where = $where ." and a.create_time >= ".strtotime($starttime.' 00:00:00');
		}
		if(!empty($endtime)) {
			$where = $where ." and a.create_time <= ".strtotime($endtime.' 23:59:59');
		}
		// 文章作者
		if(!empty($user)) {
			$where = $where ." and a.author like '%".$user."%' ";
		}
		// 三级分类
		if(!empty($param['thirdCatId'])) {
			$where = $where ." and a.cat_id = ".$param['thirdCatId']."";
		}
		// 没有选择三级分类，选择了二级分类
		if(!empty($param['secondCatId']) && empty($param['thirdCatId'])){
			$thirdCatIdList = $this->getSubCatListByParentId($param['secondCatId']);
			foreach($thirdCatIdList as $v){
				$thirdCatIds[] = $v['id'];
			}
			$strThirdCatIds = implode(",",$thirdCatIds);
			$where = $where ." and a.cat_id in (".$strThirdCatIds.")";
		}
		// 没有选择三级分类和二级分类，选择了一级分类
		if(!empty($param['firstCatId']) && empty($param['secondCatId']) && empty($param['thirdCatId'])){
			// 一级分类下的所有二级分类
			$secondCatIdList = $this->getSubCatListByParentId($param['firstCatId']);
			foreach($secondCatIdList as $v){
				$secondCatIds[] = $v['id'];
			}
			$strSecondCatIds = implode(",",$secondCatIds);
			// 一级分类下的所有三级分类
			$thirdCatIdList = $this->getSubCatListByParentIds($strSecondCatIds);
			foreach($thirdCatIdList as $v){
				$thirdCatIds[] = $v['id'];
			}
			$strThirdCatIds = implode(",",$thirdCatIds);

			$where = $where ." and a.cat_id in (".$strThirdCatIds.")";
		}
		// 文章标题
		if(!empty($param['tagname'])) {
			$tagId = M()->Table('uc_tag')->where(' status >= 0 and  type = 11 and name="'.$param['tagname'].'"')->getField('id');
			// echo M()->getLastSql();
			$articleIds = M()->Table('boqii_tag_object')->where('status=0 AND object_type=1 AND tag_id ='.$tagId)->getField('object_id', true);
			if($articleIds) {
				$where .= " AND a.id in (". implode(',' , $articleIds) .")";
			}
		}

		// 排序字段&方式
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
			case "4":
				$order = " a.recommend_num DESC";
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
		$this->total = M()->Table("bk_article a")->join("bk_cat b ON a.cat_id = b.id")->where($where)->count();
		// 文章
		$articleList =  M()->Table("bk_article a")->field("a.*,b.name")->join("bk_cat b ON a.cat_id = b.id")->where($where)->order($order)->limit("$pageStart, $pageNum")->select();
		// echo M()->getLastSql();
		// 当前页条数
		$this->subtotal = count($articleList);
		// 总页数
		$this->pagecount = ceil(($this->total)/$pageNum);

		foreach($articleList as $key=>$val){
			// 文章发布时间
			$articleList[$key]["create_time"] = date('Y-m-d H:i',$val["create_time"]);
			// 文章浏览数改为redis获取
			$articleList[$key]['view_num'] = $this->getRedisViewNum($val['id'], $val['view_num'], 'article');
		}

		return $articleList;
	}
	
	
	/**
	 * 获取redis中的浏览数
	 *
	 * @param $id int 对象id
	 * @param $dbViewNum 数据库查看数（默认为-1，当redis中没有文章浏览数时需要查数据库获取）
	 * @param $type string 类型（默认为article）
	 *
	 * @return int 浏览数
	 */
	public function getRedisViewNum($id, $dbViewNum = -1, $type = 'article') {
		// 采用redis记录文章浏览数
		$cacheRedis = Cache::getInstance('Redis');
		$key = C('REDIS_KEY.' . $type . 'Views').$id;
		$redisViews = $cacheRedis->get($key);
		// redis不存在，查询数据库
		if(!$redisViews){
			if($dbViewNum != -1) {
				$viewNum = $dbViewNum;
			} else {
				// 获取文章浏览数
				$viewNum = M()->Table('bk_article')->where('id ='.$id)->getField('view_num');
			}
			$cacheRedis->set($key,$viewNum);
		}else{
			$viewNum = $redisViews;
		}

		return $viewNum;
	}


	/**
	 * 删除文章操作
	 *
	 * @param $id int 文章id
	 */
	public function delAritcle($id) {
		$where = "id = ".$id;
		$data['status'] = -1;
		$r = M()->Table("bk_article")->where($where)->save($data);
		if($r){
			//更新搜索库
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=".$id."&param[type]=1";
			get_url($url);
			
			//更新搜索库
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=3&param[id]=".$id."";
			get_url($url);
		}
		//删除文章的时候删除该文章所属一级分类，二级分类文章数
		// 三级分类
		$info = M()->Table("bk_article")->field('cat_id')->where($where)->find();

		M()->Table('bk_cat')->where('id='.$info['cat_id'])->setDec('article_num'); 
		// 二级分类
		$parcat = $this->getParBySubCat($info['cat_id']);

		M()->Table('bk_cat')->where('id='.$parcat['parent_id'])->setDec('article_num');
		// 一级分类
		$firstcat = $this->getParBySubCat($parcat['id']);

		M()->Table('bk_cat')->where('id='.$firstcat['parent_id'])->setDec('article_num');
        //删除该文章对应的文章标签
        M('boqii_tag_object') -> where (array('object_id'=>$id,'object_type'=>1)) -> setField('status',-1);
		//标签id
		$tagIds = M()->Table("boqii_tag_object")->where('object_type = 1 and object_id ='.$id)->getField('tag_id', true);
		// 更新相关标签的使用次数和文章数
		D('UcTag') -> updateTagRelationNum($tagIds,1);

		return $r;
	}
	
	//查询文章详情
	public function getArticleDetail($id) {
		$where = "id = ".$id;
		$detail = M()->Table("bk_article")->where($where)->find();
		$tagArr = M()->Table('boqii_tag_object a')->field('a.tag_id,b.name')->join('uc_tag b on a.tag_id = b.id')->where('a.object_type = 1 and a.status = 0 and a.object_id ='.$detail['id'].' and b.type = 11 and b.status = 0')->order('a.id')->select();
		$tagarray = array();
		$tagStr = '';
		foreach($tagArr as $k=>$v){
			$tagarray[$k]['name'] = $v['name'];
			$tagarray[$k]['id'] = $v['tag_id'];
			$tagStr .= ','.$v['tag_id'];
		}
		$tagstr = implode(" ",$tagarray);
		$detail['tag'] = $tagarray;
		$detail['tagids'] = trim($tagStr,',');
		return $detail;
	}
	
	/**
	 * 添加文章操作
	 *
	 * @param $param array 参数数组
	 *
	 * @return mixed
	 */
	public function addArticle($param) {
		// 标题
		$data['title'] = $param['title'];
		// 文章分类
		$data['cat_id'] = $param['cat_id'];
		// 作者
		$data['authorid'] = $param['authorid'];
		if($param['authorid']){
			$name = $this->getNameByID($param['authorid']);
			if($name['name']){
				$data['author'] = $name['name'];
			}else{
				$data['author'] = $param['authorid'];
			}
		} 
		// 摘要
		$data['summary'] = $param['summary'];
		// 内容
		$data['content'] = $param['content'];
		// 关键词（页面meta用）
		$data['keywords'] = $param['tag'];
		// 文章封面图
		if($param['pic_path']){
			$data['pic_path'] = $param['pic_path'];
		}
		if(isset($param['is_cover_default'])) {
			$data['is_cover_default'] = $param['is_cover_default'];
		}
		//创建时间
		$data['create_time'] = time();
		$data['update_time'] = time();
		//标签
		$tagArrs = $this->getTags($param['tag']);

		import('Common.manual_common', APP_PATH, '.php');
		$data['content'] = preg_baike_article($data['content']);
		$data['content'] = preg_baike_article_pic($data['content']);
        $data['content'] = getImgTitleAddAlt ($data['content']);
        $data['content'] = $this -> addLinkForKeyword ($data['content']);
        // echo '<pre>';print_r($param);exit;
		$r = M()->Table("bk_article")->add($data);
		if($r){
			//更新搜索库
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=add&param[config_object]=1&param[pid]=".$r."&param[type]=1";
			get_url($url);
			
			//更新搜索库
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=add&param[config_object]=3&param[id]=".$r."";
			get_url($url);
			//更新二级分类文章数
			$this->addArticleNum($param['cat_id']);
			//更新一级分类文章数
			$parcat = $this->getParBySubCat($param['cat_id']);
			$this->addArticleNum($parcat['parent_id']);
			//更新专家文章数
			$this->addExpertArticleNum($param['authorid']);
			//标签
			if(trim($param['tagids'])) {
				$tagids = explode(',', $param['tagids']);
				foreach($tagids as $k1=>$v1) {
					if($v1){
						$data3['tag_id'] 		= $v1;
						$data3['object_id'] 	= $r;
						$data3['object_type'] 	= 1;
						$data3['create_time'] 	= time();
						M()->Table("boqii_tag_object")->add($data3);

						// 更新相关标签的使用次数
						$usetimes = M()->Table('boqii_tag_object')->where('status=0 and tag_id='.$v1)->count();
						M()->Table('uc_tag')->where('id='.$v1)->setField('usetimes',$usetimes);
						// 更新相关标签的文章数
						$article_num = M()->Table('boqii_tag_object')->where('status=0 and object_type=1 and tag_id='.$v1)->count();
						M()->Table('uc_tag')->where('id='.$v1)->setField('article_num',$article_num);
					}
				}
			}
			return $r;
		} else {
			return false;
		}
	}
	
	/**
	 * 编辑文章
	 */
	public function editArticle($param) {
		//文章id
		$where = 'id ='.$param['id'];
		//修改前文章信息
		$info = $this->getArticleDetail($param['id']);
		//修改后标题
		$data['title'] = $param['title'];
		// 修改后分类
		$data['cat_id'] = $param['cat_id'];
		// 修改后作者id
		$data['authorid'] = $param['authorid'];
		if($param['authorid']){ 
			$name = $this->getNameByID($param['authorid']);
			if($name['name']){
				$data['author'] = $name['name'];
			}else{
				$data['author'] = $param['authorid'];
			}
		}
		// 修改后摘要
		$data['summary'] = $param['summary'];
		// 修改后内容
		$data['content'] = $param['content'];
		// 修改后关键词（页面meta用）
		$data['keywords'] = $param['tag'];
		// 修改后文章封面图
		if($param['pic_path']) {
			$data['pic_path'] = $param['pic_path'];

		}
		if(isset($param['is_cover_default'])) {
			$data['is_cover_default'] = $param['is_cover_default'];
		}		
		//修改时间
		$data['update_time'] = time();
		
		import('Common.manual_common', APP_PATH, '.php');
		$data['content'] = preg_baike_article($data['content']);
		$data['content'] = preg_baike_article_pic($data['content']);
        $data['content'] = getImgTitleAddAlt ($data['content']);
        $data['content'] = $this -> addLinkForKeyword ($data['content']);
		$r = M()->Table("bk_article")->where($where)->save($data);
		if($r){
			//更新搜索库
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=1&param[pid]=".$param['id']."&param[type]=1";
			get_url($url);

			//更新搜索库
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=3&param[id]=".$param['id']."";
			get_url($url);
			//修改文章的时候如果修改了分类，将原来分类的文章数减1，修改过后的加1
			if($param['cat_id'] != $info['cat_id']) {
				//分类文章数减1
				$this->decArticleNum($info['cat_id']);
				//原大类文章数减1
				$parcat = $this->getParBySubCat($info['cat_id']);
				$this->decArticleNum($parcat['parent_id']);
				//更新分类文章数
				$this->addArticleNum($param['cat_id']);
				//大类文章数加1
				$parcatnew = $this->getParBySubCat($param['cat_id']);
				$this->addArticleNum($parcatnew['parent_id']);
			}
			//修改文章的时候如果修改了专家，将原来专家的文章数减1，修改过后的加1
			if($param['authorid'] != $info['authorid']) {
				//原专家文章数减1
				$this->decExpertArticleNum($info['authorid']);
				//更新专家文章数
				$this->addExpertArticleNum($param['authorid']);
			}
			//查询标签
			$tagList = M()->Table('boqii_tag_object')->where('status=0 and object_type=1 and object_id='.$param['id'])->order('id')->getField('tag_id',true);
			$tagStr = implode(',', $tagList);
			// 删除使用次数
			M()->Table('uc_tag')->where(array('id'=>array('in',$tagStr)))->setDec('usetimes');
			M()->Table('uc_tag')->where(array('id'=>array('in',$tagStr)))->setDec('article_num');
			M()->Table('boqii_tag_object')->where('status=0 and object_type=1 and object_id='.$param['id'])->delete();
			//当标签更改后
			$tagids = explode(',', $param['tagids']);
			if ($tagids) {
				foreach($tagids as $k1=>$v1) {
					$data3['tag_id'] 		= $v1;
					$data3['object_type'] 	= 1;
					$data3['object_id'] 	= $param['id'];
					$data3['create_time'] 	= time();
					M()->Table("boqii_tag_object")->add($data3);
					// 更新相关标签的使用次数
					$usetimes = M()->Table('boqii_tag_object')->where('status=0 and tag_id='.$v1)->count();
					M()->Table('uc_tag')->where('id='.$v1)->setField('usetimes',$usetimes);
					// 更新相关标签的文章次数
					$article_num = M()->Table('boqii_tag_object')->where('status=0 and object_type=1 and tag_id='.$v1)->count();
					M()->Table('uc_tag')->where('id='.$v1)->setField('article_num',$article_num);
					//统计标签使用次数
					// foreach($tagIds as $key=>$val) {
					// 	$usetimes = M()->Table('boqii_tag_object')->where('object_type = 1 AND tag_id='.$val.' AND status=0')->count();
					// 	M()->Table('uc_tag')->where('id='.$val)->save(array('usetimes'=>$usetimes));	
					// }
				}
			}
			
		} else {
			return false;
		}
	}
	
	/**
	 * 根据输入的文章标签返回数组
	 */
	public function getTags($str) {
		$tagArr = explode(" ",$str);
		foreach($tagArr as $k=>$v) {
			if($v) {
				$exist = $this->tagExist($v);
				if($exist) {
					$newTagArr[] = $exist['id'];
				} 
				//新增标签
				else {
					$data['name'] = $v;
					$data['dateline'] = time();
					$boqiiUserId = session('boqiiUserId');
					$data['uid'] = $boqiiUserId;
					$data['modify_uid'] = $boqiiUserId;
					$data['type'] = 11;
					$data['updatetime'] = time();
					$id = M()->Table('uc_tag')->add($data);
					$newTagArr[] = $id;
				}
			}
		}
		return $newTagArr;
	}
	
	//根据标签名查找是否存在
	public function tagExist($tag) {
		return M()->Table('uc_tag')->field('id')->where("name = '".$tag."' and type = 11 and status = 0")->find();
	}
	
	//文章下标签
	public function getArticleTag($id) {
		$where = 'object_type  = 1 and object_id ='.$id.' and status = 0';
		return M()->Table('boqii_tag_object')->where($where)->select();
	}
	
	//专家或者小编name
	public function getNameByID($uid) {
		$where = 'a.uid ='.$uid.' and a.status = 0';
		$list = M()->Table('boqii_users_extendbaike a')->field('b.nickname as name')->join('boqii_users b ON a.uid = b.uid')->where($where)->find();
		return $list;
	}
	
	//所有大类
	public function getParentCat() {
		$where = 'status = 0 and parent_id = 0';

		return M()->Table('bk_cat')->field('id,name')->where($where)->order('sort desc')->select();
	}
	
	/** 
	 * 根据小类获取上一级分类
	 *
	 * @param $id int 分类id
	 */
	public function getParBySubCat($id) {
		$where = 'status = 0 and id = '.$id.'';

		return M()->Table('bk_cat')->field('parent_id')->where($where)->order('sort desc')->find();

	}

	//根据大类获取二级分类
	public function getSubByParCat($id) {
		$where = 'status = 0 and parent_id = '.$id.'';

		return M()->Table('bk_cat')->field('id,name')->where($where)->order('sort')->select();
	}

	/**
	 * 获取所有一级分类
	 *
	 * @return array 一级分类列表数组
	 */
	public function getParentCatList() {
		// 查询条件
		$where = 'status = 0 and parent_id = 0';

		return M()->Table('bk_cat')->where($where)->order('id')->field('id,name')->select();
	}

	/** 
	 * 根据分类获取父分类信息
	 *
	 * @param $id int 分类id
	 *
	 * @return array 父分类信息数组
	 */
	public function getParentCatByCatId($id) {
		// 查询条件
		$where = 'status = 0 and id = '.$id.'';

		return M()->Table('bk_cat')->where($where)->field('parent_id')->find();
	}

	/**
	 * 根据父分类获取子分类信息
	 *
	 * @param $pid int 父分类id
	 *
	 * @return array 
	 */
	public function getSubCatListByParentId($pid) {
		// 查询条件
		$where = 'status = 0 and parent_id = '.$pid.'';

		return M()->Table('bk_cat')->where($where)->order('sort')->field('id,name')->select();
	}

	/**
	 * 根据父分类id字符串获取子分类信息
	 *
	 * @param $strPids string 父分类id字符串
	 *
	 * @return array 
	 */
	public function getSubCatListByParentIds($strPids) {
		// 查询条件
		$where = 'status = 0 and parent_id IN ( '.$strPids.')';

		return M()->Table('bk_cat')->where($where)->order('sort')->field('id,name')->select();
	}

	
	//所有小编
	public function getEditUser() {
		$where = 'a.status = 0 and a.level = 3';
		$listarr = M()->Table('boqii_users_extendbaike a')->field('a.uid,b.nickname as name')->join('boqii_users b ON a.uid = b.uid')->where($where)->order('a.article_num desc')->select();
		$list = array();
		foreach($listarr as $lists){		
			if(empty($lists['name'])){
				$lists['name'] = $lists['uid'];
			}
			$list[] = $lists;
		}
		return $list;
	}
	
	//所有专家
	public function getExpertUser() {
		$where = 'a.status = 0 and a.level = 5';
		$listarr = M()->Table('boqii_users_extendbaike a')->field('a.uid,b.nickname as name')->join('boqii_users b ON a.uid = b.uid')->where($where)->order('a.article_num desc')->select();
		$list = array();
		foreach($listarr as $lists){		
			if(empty($lists['name'])){
				$lists['name'] = $lists['uid'];
			}
			$list[] = $lists;
		}
		return $list;
	}
	
	//所有标签
	public function getAllTag() {
		$where = 'status = 0 and type = 11';
		return M()->Table('uc_tag')->field('id,name')->where($where)->select();
	}
	
	//根据uid查询角色
	public function getGroupByUid($uid) {
		$where ='uid ='.$uid;
		$group = M()->Table('boqii_users_extendbaike')->field('level')->where($where)->find();
		return $group;
	}
	
	//添加成功之后增加该分类下的文章数
	public function addArticleNum($cid) {
		$where = 'id ='.$cid;

		$r = M()->Table('bk_cat')->where($where)->setInc('article_num');
		if($r) {
            return true;
        } else {
            return false;
        }
	}
	
	//修改分类减少原分类下的文章数
	public function decArticleNum($cid) {
		$where = 'id ='.$cid;

		$r = M()->Table('bk_cat')->where($where)->setDec('article_num');
		if($r) {
            return true;
        } else {
            return false;
        }
	}
	
	//更新专家文章数
	public function addExpertArticleNum($uid) {
		$where = 'uid ='.$uid;
		$r = M()->Table('boqii_users_extendbaike')->where($where)->setInc('article_num');
		if($r) {
            return true;
        } else {
            return false;
        }
	}
	
	//修改之后更新专家文章数
	public function decExpertArticleNum($uid) {
		$where = 'uid ='.$uid;
		$r = M()->Table('boqii_users_extendbaike')->where($where)->setDec('article_num');
		if($r) {
            return true;
        } else {
            return false;
        }
	}
	
	//批处理更新分类文章数
	public function updateArticleNum() {
		$where = 'status = 0';
		$listArr = M()->Table('bk_article')->field('count(id) as num,cat_id')->where($where)->group('cat_id')->select();
		foreach($listArr as $k=>$v) {
			$data['article_num'] = $v['num'];

			M()->Table('bk_cat')->where('id = '.$v['cat_id'])->save($data);	
		}
	}
	
	//批量更新大类文章数
	public function updateParentArticleNum() {
		$where = 'status = 0 and parent_id = 0';

		$listArr = M()->Table('bk_cat')->field('id')->where($where)->select();
		foreach($listArr as $v) {
			$where2 = 'status = 0 and parent_id='.$v['id'];

			$subArr = M()->Table('bk_cat')->field('id,article_num')->where($where2)->select();
			$data['article_num'] = 0;
			foreach($subArr as $v1) {
				$data['article_num'] += $v1['article_num'];
			}

			M()->Table('bk_cat')->where('status = 0 and id='.$v['id'])->save($data);
		}
		return $subArr;
	}
	
	//批处理更新专家文章数
	public function updateExpertArticleNum() {
		$where = 'status = 0';
		$listArr = M()->Table('bk_article')->field('count(id) as num,authorid')->where($where)->group('authorid')->select();
		foreach($listArr as $k=>$v) {
			$data['article_num'] = $v['num'];
			M()->Table('boqii_users_extendbaike')->where('uid = '.$v['authorid'])->save($data);	
		}
	}

    //增加内容关键字详细介绍链接
    public function addLinkForKeyword ($content) {
        if(empty($content)) return false;
        //匹配所有的链接内容
        $pattern = "/<a .*? >(.*?)<\/a>/isx";
        $pattern2 = "/\(<a .*? >详情介绍<\/a>\)/isx";
        preg_match_all($pattern,$content,$match);
        if(empty($match[1])) return $content;
        $subject= $match[0];
        $subject1= $match[1];
        $arrUnique = array();
        foreach ($subject as $key2 => $val2) {
            if(in_array($val2,$arrUnique))  {
                unset($subject[$key2],$subject1[$key2]);
                continue;
            }
            $arrUnique[] = $val2;
        }
        $content = preg_replace($pattern2,'',$content);
        //内容是否宠物大全名字相同
        foreach ($subject1 as $key => $val) {
            $petId = M('bk_pet_detail') -> where(array('name'=>$val, 'status'=>0)) -> getField('id');
            if($petId) {
				$link = get_rewrite_url('BkPetCategory','detail',$petId);
				$addStr = "(<a href='".$link."' target=\"_blank\" >详情介绍</a>)";
				$content = str_replace($subject[$key],$subject[$key].$addStr,$content);
			}
        }
        return $content;
    }
}
?>
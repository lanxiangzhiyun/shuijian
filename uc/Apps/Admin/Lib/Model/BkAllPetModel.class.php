<?php
/**
 * 宠物Model类
 *
 * @author JasonJiang
 * date 2014-09-29 
 */
class BkAllPetModel extends RelationModel {
	//宠物品种表
	protected $tableName = "bk_pet_detail";
	
	/**
	 * 获取词条列表
	 *
	 *
	 * @param $param array 参数数组
	 */
	public function getEntryList($param) {
		// print_r($param);
		//分页
		$page 	 = !empty($param['page']) ? $param['page'] : 1;
		$pageNum = 10;
		//条件
		$where = '1=1';
		//词条关键字
		if ($param['name'] && $param['name']!='请输入词条关键字') {
			$where .= " and name like '%{$param['name']}%'";
		}
		//标签关键字
		if ($param['tag_name'] && $param['tag_name']!='请输入标签关键字') {
			$tag_id = M()->Table('uc_tag')->where('status=0 and type=11 and name="'.$param['tag_name'].'"	')->getField('id');
			
			$entryArr = M()->Table('boqii_tag_object')->where('status=0 and object_type=3 and tag_id='.$tag_id)->getField('object_id',true);
			
			$where .= " and id in (".trim(implode(',', $entryArr),',').")";
		}
		//开始时间
		if ($param['start_time']) {
			$where .= ' and create_time >='.strtotime($param['start_time']);
		}
		//结束时间
		if ($param['end_time']) {
			$where .= ' and create_time <='.(strtotime($param['end_time'])+3590*24);
		}
		//条件：一级分类
		if($param['first']){
		  	$where .= ' and first_cat_id = '.$param['first'];
		}
		//条件：二级分类
		if($param['second']){
		  	$where .= ' and sec_cat_id = '.$param['second'];
		}
		//条件：三级分类
		if($param['third']){
		  	$where .= ' and cat_id = '.$param['third'];
		}
		if($param['field']){
			$field = $param['field'];
		}else{
			$field = 'id';
		}
		// print_r($where);
	 	//数据总条数
		$this ->total = M()->Table('bk_pet_detail') -> where ($where) ->field('id') -> count();
		//总页数
    	$this->pagecount = ceil(($this->total)/$pageNum);
      	if( $page  >= $this->pagecount){
			$page = $this->pagecount;
		}
		//条件a：发布时间 1:升序(时间越近越靠前)
		//条件b：浏览数 1:升序(浏览人数越多越靠前)
		if($param['order'] == 1){
			$result = M() ->Table('bk_pet_detail') ->field($field) ->where($where) ->page($page) ->limit($pageNum) ->order('create_time desc') ->select();
		}elseif($param['order'] == 2){
			$result = M() ->Table('bk_pet_detail') ->field($field) ->where($where) ->page($page) ->limit($pageNum) ->order('view_num desc') ->select();
		}else{
			$result = M() ->Table('bk_pet_detail') ->field($field) ->where($where) ->page($page) ->limit($pageNum) ->order('id desc') ->select();
		}
		// echo M()->getLastSql();
		//当前条数	
		$this->subtotal = count($result);
		foreach ($result as $key => $val) {
    		if (empty($val['create_time'])) {
         		$result[$key]['create_time'] = '';
          	} else {
          	    $result[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
          	}
          	//通过分类id得到一级分类名
          	$result[$key]['first_name']  = $this-> getCatName($val['first_cat_id']);
          	//通过分类id得到二级分类名
          	$result[$key]['second_name'] = $this-> getCatName($val['sec_cat_id']);
          	//通过分类id得到三级分类名
          	$result[$key]['third_name']  = $this-> getCatName($val['cat_id']);
			// 浏览数（改为redis获取）
			$result[$key]['view_num'] = $this->getRedisViewNum($val['id'], $val['view_num'], 'pet');
	    }
		//echo M()->getLastSql();echo '<pre>';print_r($result);
		return $result;
	}

	/**
	 * 获取redis中的浏览数
	 *
	 * @param $id int 对象id
	 * @param $dbViewNum 数据库浏览数（默认为-1，当redis中没有词条浏览数时需要查数据库获取）
	 * @param $type string 类型（默认为thread）
	 *
	 * @return int 浏览数
	 */
	public function getRedisViewNum($id, $dbViewNum = -1, $type = 'pet') {
		// 采用redis记录词条浏览数
		$cacheRedis = Cache::getInstance('Redis');
		$key = C('REDIS_KEY.' . $type . 'Views').$id;
		$redisViews = $cacheRedis->get($key);
		// redis不存在，查询数据库
		if(!$redisViews){
			if($dbViewNum != -1) {
				$viewNum = $dbViewNum;
			} else {
				// 获取词条浏览数
				$viewNum = M()->Table('bk_pet_detail')->where('id ='.$id)->getField('view_num');
			}
			$cacheRedis->set($key,$viewNum);
		}else{
			$viewNum = $redisViews;
		}

		return $viewNum;
	}

	/**
	 * 通过分类id得到分类名
	 * @param $param array 分类id
	 * return name
	 */
	public function getCatName($param){
 		return M()->Table('bk_entry_cat')->where(array('id'=>$param))->getField('name');
	}
	/**
	 * 添加词条
	 * @param $param array 参数数组
	 */
	public function addEntry($param) {
		// echo "<pre>";print_r($param);exit;
		//标题
		$data['name'] 			= $param['name'];
		//一级分类
		$data['first_cat_id'] 	= $param['first'];
		//二级分类
		$data['sec_cat_id']		= $param['second']?$param['second']:0;
		//三级分类
		$data['cat_id']			= $param['third']?$param['third']:0;
		
		// 获取植物词条分类的id
		$plantId = $this->getCatIdByCode('plant');
		// 当选择宠物分类，获取首字母，对应论坛id，拼音
		if($data['first_cat_id'] == 1 || $data['first_cat_id'] == $plantId){
			//首字母
			$data['letter'] 		= $param['letter'];
			//相关板块id
			$data['relation_forum'] = $param['relation_forum'];
			//拼音
			$data['pinyin'] 		= $param['pinyin'];
		}
		// 如果有二级分类	
		if ($data['sec_cat_id']) {
			if($data['sec_cat_id'] == 3 || $data['sec_cat_id'] == 4){
				// 体型
				$data['size'] 		 = $param['size'];
				// 毛长
				$data['hair'] 		 = $param['hair'];
			}
			// 获得观花植物id
			$reptileId = $this->getCatIdByCode('reptile');
			// 属性打分
			if(in_array($data['sec_cat_id'], array(3,4,5,6,$reptileId))) {
				// 友好程度
				$data['friendly_degree'] = $param['friendly_degree'];
				// 运动量
				$data['sport_degree'] 	 = $param['sport_degree'];
				// 体味程度
				$data['odor_degree'] 	 = $param['odor_degree'];
				// 城市适应
				$data['city_degree'] 	 = $param['city_degree'];
				// 初养适应
				$data['adapt_degree'] 	 = $param['adapt_degree'];
				// 可训练度
				$data['training_degree'] = $param['training_degree'];
				// 吵闹程度
				$data['noise_degree'] 	 = $param['noise_degree'];
				// 耐热程度
				$data['hot_degree']  	 = $param['hot_degree'];
				// 掉毛程度
				$data['dropping_degree'] = $param['dropping_degree'];
				// 口水程度
				$data['saliva_degree'] 	 = $param['saliva_degree'];
				// 关爱需求
				$data['love_degree'] 	 = $param['love_degree'];
				// 耐寒程度
				$data['cold_degree'] 	 = $param['cold_degree'];
			}
		} 
		// 词条头像
		if($param['pic_path']){
			$data['pic_path'] 		 = $param['pic_path'];
		}
		// 浏览量
		$data['view_num'] 			 = $param['init_view_num']?$param['init_view_num']:0;
		// 初始浏览量
		$data['init_view_num']		 = $param['init_view_num']?$param['init_view_num']:0;
		// 模板
		$data['template'] 			 = $param['binfo'];
		// 判断模板获得信息
		if($param['binfo']){
			if ($param['binfo'] == 1) {
				
				// 英文名
				$data['enname'] 	= $param['enname'];
				// 寿命
				$data['life']	 	= $param['life'];
				// 价格
				$data['price'] 		= $param['price'];
				// 性格
				$data['nature'] 	= $param['nature'];
				// 祖籍
				$data['ancestry'] 	= $param['ancestry'];
				// 易患病
				$data['sick'] 		= $param['sick'];
			}else{
				// 英文名
				$data['enname'] 		= $param['enname1'];
				// 寿命
				$data['life']	 		= $param['life1'];
				// 价格
				$data['price'] 			= $param['price1'];
				// 学名
				$data['scientific'] 	= $param['scientific'];
				// 别名
				$data['another'] 		= $param['another'];
				// 花语
				$data['flower_language'] = $param['flowerLanguage'];
				// 分布区域
				$data['area'] 			= $param['area'];
			}
			
		}
		// 创建时间
		$data['create_time'] = time();
		// 更新时间
		$data['update_time'] = time();
		 // echo "<pre>";print_r($param);exit;
		// 宠物品种介绍 
		$id = $this->add($data);
		// echo $this->getLastSql();echo $id;
		if($id){
			// 更新搜索库：新增词条
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=add&param[config_object]=1&param[pid]=".$id."&param[type]=3";
			get_url($url);
			if ($plantId) {
				// 获得观花植物分类id
				$flowerId = $this->getCatIdByCode('flower');
				// 获得观叶植物分类id
				$leafId   = $this->getCatIdByCode('leaf');
			}
			// 词条宠物功能
			if($data['sec_cat_id'] == 3){
				if ($param['func_cat']) {
					$this->addFuncCat($id,$param['func_cat'],1);
				}
			}// 观花植物花期和颜色选项
			elseif ($data['sec_cat_id'] == $flowerId) {
				if ($param['flower_cat']) {
					$this->addFuncCat($id,$param['flower_cat'],2);
				}
				if ($param['color_cat']) {
					$this->addFuncCat($id,$param['color_cat'],3);
				}
			}// 观叶植物功能
			else if ($data['sec_cat_id'] == $leafId) {
				if ($param['place_cat']) {
					$this->addFuncCat($id,$param['place_cat'],4);
				}
				if ($param['effect_cat']) {
					$this->addFuncCat($id,$param['effect_cat'],5);
				}
			}
			// 词条自定义
			$self['title'] 	 = $param['self_key'];
			$self['content'] = $param['self_value'];
			
			$counts1 = count($self['title']);
			for ($j=0; $j < $counts1; $j++) { 
				if(!empty($self['title'][$j]) || !empty($self['content'][$j])){
					$arr[$j]['title'] 			= trim($self['title'][$j]);
					$arr[$j]['content'] 		= trim($self['content'][$j]);
					$arr[$j]['create_time'] 	= time();
					$arr[$j]['pet_detail_id'] 	= $id;
				}
			}
			M()->Table('bk_entry_extend')->addAll($arr);

			//添加标签
			if ($param['tagids']) {
				$tagids = explode(',', $param['tagids']);
				foreach ($tagids as $k => $v) {
					$over[$k]['object_id'] 		= $id;
					$over[$k]['object_type'] 	= 3;
					$over[$k]['tag_id'] 		= $v;
					$over[$k]['create_time']  	= time();
					M() -> Table('boqii_tag_object') -> add($over[$k]);
					// 更新相关标签的使用次数
					$usetimes = M()->Table('boqii_tag_object')->where('status=0 and tag_id='.$v)->count();
					M()->Table('uc_tag')->where('id='.$v)->setField('usetimes',$usetimes);
					// 更新相关标签的词条次数
					$entryNum = M()->Table('boqii_tag_object')->where('status=0 and object_type=3 and tag_id='.$v)->count();
					M()->Table('uc_tag')->where('id='.$v)->setField('entry_num',$entryNum);
				}
			}

			//宠物品种介绍 
			$introduce['title'] 	= $param['extend_title1'];
			$introduce['url'] 		= $param['extend_url1'];
			$introduce['content'] 	= $param['content'];
			//导入内容处理函数，内容标签加链接，图片夹链接加alt
			import('Common.manual_common', APP_PATH, '.php');
			$counts = count($introduce['title']);
			for ($i=0; $i < $counts; $i++) { 

				$lists[$i]['title'] 		= $introduce['title'][$i];
				$lists[$i]['url'] 			= $introduce['url'][$i];
				$content					= urldecode($introduce['content'][$i]);
				$content					= preg_baike_article($content);
				$content 					= preg_baike_article_pic($content);
       	 		$lists[$i]['content'] 		= getImgTitleAddAlt($content);
				$lists[$i]['create_time'] 	= time();
				$lists[$i]['pet_detail_id'] = $id;
				
			}
			M()->Table('bk_pet_extend')->addAll($lists);

			
			//宠物相册
			$photos = $param['pics'];
			if($photos) {
				foreach($photos as $photo) {
					if($photo) {
						M()->Table('bk_pet_photo')->add(array('pet_detail_id'=>$id, 'photo_path'=>$photo, 'create_time'=>time(), 'status'=>0));
					}
				}
			}
			return $id;
		}else {
			return false;
		}

	}
	
	/**
	 * 词条添加功能属性
	 * @param 	$pet_detail_id int 词条id
	 *			$funcArr array 功能数组
	 * 			$type int （1：狗狗功能；2：观花植物花期；3：观花植物颜色；4：观叶植物摆放位置；5：观叶植物功效）
	 */
	protected function addFuncCat($pet_detail_id,$funcArr,$type){
		if($funcArr) {
			foreach($funcArr as $funcCat) {
				if($funcCat) {
					M()->Table('bk_pet_func_cat')->add(array('pet_detail_id'=>$pet_detail_id, 'func_cat_id'=>$funcCat,'type'=>$type));

				}
			}
		}
	}
	
	/**
	 * 编辑宠物品种
	 *（添加宠物页）
	 */
	public function editEntry($param) {
		$where = 'id ='.$param['id'];
		
		//标题
		$data['name'] 			= $param['name'];
		//一级分类
		$data['first_cat_id'] 	= $param['first'];
		//二级分类
		$data['sec_cat_id']		= $param['second']?$param['second']:0;
		//二级分类
		$data['cat_id']			= $param['third']?$param['third']:0;

		// echo '<pre>';print_r($param);exit();
		
		// 获取植物词条分类的id
		$plantId = $this->getCatIdByCode('plant');
		// 当选择宠物分类，获取首字母，对应论坛id，拼音
		if($data['first_cat_id'] == 1 || $data['first_cat_id'] == $plantId){
			//首字母
			$data['letter'] 		= $param['letter'];
			//相关板块id
			$data['relation_forum'] = $param['relation_forum'];
			//拼音
			$data['pinyin'] 		= $param['pinyin'];
		}else{
			//首字母
			$data['letter'] 		= '';
			//相关板块id
			$data['relation_forum'] = '';
			//拼音
			$data['pinyin'] 		= '';
			//体型
			$data['size'] 		 = '';
			//毛长
			$data['hair'] 		 = '';
			// 友好程度
			$data['friendly_degree'] = '';
			// 运动量
			$data['sport_degree'] 	 = '';
			// 体味程度
			$data['odor_degree'] 	 = '';
			// 城市适应
			$data['city_degree'] 	 = '';
			// 初养适应
			$data['adapt_degree'] 	 = '';
			// 可训练度
			$data['training_degree'] = '';
			// 吵闹程度
			$data['noise_degree'] 	 = '';
			// 耐热程度
			$data['hot_degree']  	 = '';
			// 掉毛程度
			$data['dropping_degree'] = '';
			// 口水程度
			$data['saliva_degree'] 	 = '';
			// 关爱需求
			$data['love_degree'] 	 = '';
			// 耐寒程度
			$data['cold_degree'] 	 = '';
		}
		//如果有二级分类	
		if ($data['sec_cat_id']) {
			if($data['sec_cat_id'] == 3 || $data['sec_cat_id'] == 4){
				//体型
				$data['size'] 		 = $param['size'];
				//毛长
				$data['hair'] 		 = $param['hair'];
			}else{
				//体型
				$data['size'] 		 = '';
				//毛长
				$data['hair'] 		 = '';
			}
		
			// 获得观花植物id
			$reptileId = $this->getCatIdByCode('reptile');
			// 属性打分
			if(in_array($data['sec_cat_id'], array(3,4,5,6,$reptileId))) {
				// 友好程度
				$data['friendly_degree'] = $param['friendly_degree'];
				// 运动量
				$data['sport_degree'] 	 = $param['sport_degree'];
				// 体味程度
				$data['odor_degree'] 	 = $param['odor_degree'];
				// 城市适应
				$data['city_degree'] 	 = $param['city_degree'];
				// 初养适应
				$data['adapt_degree'] 	 = $param['adapt_degree'];
				// 可训练度
				$data['training_degree'] = $param['training_degree'];
				// 吵闹程度
				$data['noise_degree'] 	 = $param['noise_degree'];
				// 耐热程度
				$data['hot_degree']  	 = $param['hot_degree'];
				// 掉毛程度
				$data['dropping_degree'] = $param['dropping_degree'];
				// 口水程度
				$data['saliva_degree'] 	 = $param['saliva_degree'];
				// 关爱需求
				$data['love_degree'] 	 = $param['love_degree'];
				// 耐寒程度
				$data['cold_degree'] 	 = $param['cold_degree'];
			}else{
				// 友好程度
				$data['friendly_degree'] = '';
				// 运动量
				$data['sport_degree'] 	 = '';
				// 体味程度
				$data['odor_degree'] 	 = '';
				// 城市适应
				$data['city_degree'] 	 = '';
				// 初养适应
				$data['adapt_degree'] 	 = '';
				// 可训练度
				$data['training_degree'] = '';
				// 吵闹程度
				$data['noise_degree'] 	 = '';
				// 耐热程度
				$data['hot_degree']  	 = '';
				// 掉毛程度
				$data['dropping_degree'] = '';
				// 口水程度
				$data['saliva_degree'] 	 = '';
				// 关爱需求
				$data['love_degree'] 	 = '';
				// 耐寒程度
				$data['cold_degree'] 	 = '';
			}
		}
		//词条头像
		$data['pic_path'] = $param['pic_path']?$param['pic_path']:'';
		
		//模板
		$data['template'] = $param['binfo'];
		//判断模板获得信息
		if($param['binfo']){
			
			if ($param['binfo'] == 1) {
				
				// 英文名
				$data['enname'] 	= $param['enname'];
				// 寿命
				$data['life']	 	= $param['life'];
				// 价格
				$data['price'] 		= $param['price'];
				// 性格
				$data['nature'] 	= $param['nature'];
				// 祖籍
				$data['ancestry'] 	= $param['ancestry'];
				// 易患病
				$data['sick'] 		= $param['sick'];
			}else{
				// 英文名
				$data['enname'] 		= $param['enname1'];
				// 寿命
				$data['life']	 		= $param['life1'];
				// 价格
				$data['price'] 			= $param['price1'];
				// 学名
				$data['scientific'] 	= $param['scientific'];
				// 别名
				$data['another'] 		= $param['another'];
				// 花语
				$data['flower_language'] = $param['flowerLanguage'];
				// 分布区域
				$data['area'] 			= $param['area'];
			}
		}else{
			$data['enname'] 	= '';
			// 寿命
			$data['life']	 	= '';
			// 价格
			$data['price'] 		= '';
			// 性格
			$data['nature'] 	= '';
			// 祖籍
			$data['ancestry'] 	= '';
			// 易患病
			$data['sick'] 		= '';
			// 学名
			$data['scientific'] 	= '';
			// 别名
			$data['another'] 		= '';
			// 花语
			$data['flower_language'] = '';
			// 分布区域
			$data['area'] 			= '';
		}
		//更新时间
		$data['update_time'] = time();
		// echo '<pre>';print_r($data);exit;
		$r = $this->where($where)->save($data);
		
		// echo M()->getLastSql();echo '<pre>';print_r($r);exit;
		if($r){
			// 更新搜索库：编辑词条
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=1&param[pid]=".$param['id']."&param[type]=3";
			get_url($url);

			if ($plantId) {
				// 获得观花植物分类id
				$flowerId = $this->getCatIdByCode('flower');
				// 获得观叶植物分类id
				$leafId   = $this->getCatIdByCode('leaf');
			}
			//删除旧数据
			M()->Table('bk_pet_func_cat')->where(array('pet_detail_id'=>$param['id']))->delete();
			// 词条宠物功能
			if($data['sec_cat_id'] == 3){
				if ($param['func_cat']) {
					$this->addFuncCat($param['id'],$param['func_cat'],1);
				}
			}// 观花植物花期和颜色选项
			elseif ($data['sec_cat_id'] == $flowerId) {
				if ($param['flower_cat']) {
					$this->addFuncCat($param['id'],$param['flower_cat'],2);
				}
				if ($param['color_cat']) {
					$this->addFuncCat($param['id'],$param['color_cat'],3);
				}
			}// 观叶植物功能
			else if ($data['sec_cat_id'] == $leafId) {
				
				if ($param['place_cat']) {
					$this->addFuncCat($param['id'],$param['place_cat'],4);
				}
				if ($param['effect_cat']) {
					$this->addFuncCat($param['id'],$param['effect_cat'],5);
				}
			}
			
			//词条自定义
			$self['title'] 	 = $param['self_key'];
			$self['content'] = $param['self_value'];
			//删除旧数据
			M()->Table('bk_entry_extend')->where(array('pet_detail_id'=>$param['id']))->delete();
			$counts1 = count($self['title']);

			for ($j=0; $j < $counts1; $j++) { 
				if(!empty($self['title'][$j]) || !empty($self['content'][$j])){
					$arr[$j]['title'] 			= trim($self['title'][$j]);
					$arr[$j]['content'] 		= trim($self['content'][$j]);
					$arr[$j]['create_time'] 	= time();
					$arr[$j]['pet_detail_id'] 	= $param['id'];
				
				}
			}
			M()->Table('bk_entry_extend')->addAll($arr);

			//添加标签
			// $result = D('BkArticle') -> getTags($param['tag']);
			//查询标签
			$tagList = M()->Table('boqii_tag_object')->where('status=0 and object_type=3 and object_id='.$param['id'])->order('id')->getField('tag_id',true);
			$tagStr = implode(',', $tagList);
			// echo M()->getLastSql();print_r($tagStr);exit;
			// 减少使用次数
			M()->Table('uc_tag')->where(array('id'=>array('in',$tagStr)))->setDec('usetimes');
			M()->Table('uc_tag')->where(array('id'=>array('in',$tagStr)))->setDec('entry_num');
			M()->Table('boqii_tag_object')->where('status=0 and object_type=3 and object_id='.$param['id'])->delete();
			//添加标签
			if ($param['tagids']) {
				$tagids = explode(',', $param['tagids']);
				foreach ($tagids as $k => $v) {
					$over[$k]['object_id'] 		= $param['id'];
					$over[$k]['object_type'] 	= 3;
					$over[$k]['tag_id'] 		= $v;
					$over[$k]['create_time']  	= time();
					M() -> Table('boqii_tag_object') -> add($over[$k]);
					// 更新相关标签的使用次数
					$usetimes = M()->Table('boqii_tag_object')->where('status=0 and tag_id='.$v)->count();
					M()->Table('uc_tag')->where('id='.$v)->setField('usetimes',$usetimes);
					// 更新相关标签的词条次数
					$entryNum = M()->Table('boqii_tag_object')->where('status=0 and object_type=3 and tag_id='.$v)->count();
					M()->Table('uc_tag')->where('id='.$v)->setField('entry_num',$entryNum);
				}
			}
			
			//宠物品种介绍 
			$introduce['title'] 	= $param['extend_title1'];
			$introduce['url'] 		= $param['extend_url1'];
			$introduce['content'] 	= $param['content'];
			//导入内容处理函数，内容标签加链接，图片夹链接加alt

			import('Common.manual_common', APP_PATH, '.php');
			$counts = count($introduce['title']);
			//删除旧数据
			M()->Table('bk_pet_extend')->where(array('pet_detail_id'=>$param['id']))->delete();

			for ($i=0; $i < $counts; $i++) { 

				$lists[$i]['title'] 		= $introduce['title'][$i];
				$lists[$i]['url'] 			= $introduce['url'][$i];
				$content 					= urldecode($introduce['content'][$i]);
				$content					= preg_baike_article($content);
				$content 					= preg_baike_article_pic($content);
       	 		$lists[$i]['content'] 		= getImgTitleAddAlt($content);	
				$lists[$i]['create_time'] 	= time();
				$lists[$i]['pet_detail_id'] = $param['id'];
				
			}
			
			$result = M()->Table('bk_pet_extend')->addAll($lists);

			
			//宠物相册
			$photos = $param['pics'];
			if($photos) {
				M()->Table('bk_pet_photo')->where(array('pet_detail_id'=>$param['id']))->delete();
				foreach($photos as $photo) {
					if($photo) {
						M()->Table('bk_pet_photo')->add(array('pet_detail_id'=>$param['id'], 'photo_path'=>$photo, 'create_time'=>time(), 'status'=>0));
					}
				}
			}
			return $r;
		} else {
			return false;
		}
	}

	/** 
	 * 删除宠物品种操作
	 *（宠物管理页）
	 *
	 * @param $id int 宠物品种id
	 *
	 * @return boolean 处理结果
	 */
	public function delPet($id) {
		$where = "id = ".$id;
		$data['status'] = -1;
		$r = M()->Table("bk_pet_detail")->where($where)->save($data);

		// 更新搜索库：删除词条
		$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=".$id."&param[type]=3";
		get_url($url);

		//删除相应的标签关系表: 逻辑删除，这里还有发布
		M()->Table('boqii_tag_object')->where('object_type = 3 and object_id = '.$id)->setField('status',-1);
		$tagList = M()->Table('boqii_tag_object')->where('object_type = 3 and object_id = '.$id)->getField('tag_id',true);
		// $tagStr  = implode(',', $tagList);
		// 更新相关标签的使用次数和词条数
		D('UcTag') -> updateTagRelationNum($tagList,3);
		
		return $r;
	}
	
	//发布
	public function publishPet($id) {
		$where = "id = ".$id;
		$data['status'] = 0;
		$r = M()->Table("bk_pet_detail")->where($where)->save($data);
		//恢复相应的标签关系表
		M()->Table('boqii_tag_object')->where('object_type = 3 and object_id = '.$id)->setField('status',0);
		$tagList = M()->Table('boqii_tag_object')->where('object_type = 3 and object_id = '.$id)->getField('tag_id',true);
		// $tagStr  = implode(',', $tagList);
		D('UcTag') -> updateTagRelationNum($tagList,3);
		
		return $r;
	}


	//查询词条详情
	public function getPetDetail($id) {
		$where = "status = 0 and id = ".$id;
		$detail = $this->where($where)->find();
		
		
		//宠物功能
		if ($detail['sec_cat_id']) {
			// 获得观花植物和观叶植物分类id
			$flowerCatId = $this->getCatIdByCode('flower');
			$leafCatId   = $this->getCatIdByCode('leaf');
			if ($detail['sec_cat_id'] == 3) {
				$detail['func_cats']= M()->Table('bk_pet_func_cat')->where('type = 1 and pet_detail_id='.$id)->order('id')->getField('func_cat_id', true);
			}else if ($detail['sec_cat_id'] == $flowerCatId) {
				$detail['flower_cats']= M()->Table('bk_pet_func_cat')->where('type = 2 and pet_detail_id='.$id)->order('id')->getField('func_cat_id', true);
				$detail['color_cats']= M()->Table('bk_pet_func_cat')->where('type = 3 and pet_detail_id='.$id)->order('id')->getField('func_cat_id', true);
			}else if ($detail['sec_cat_id'] == $leafCatId) {
				$detail['place_cats']= M()->Table('bk_pet_func_cat')->where('type = 4 and pet_detail_id='.$id)->order('id')->getField('func_cat_id', true);
				$detail['effect_cats']= M()->Table('bk_pet_func_cat')->where('type = 5 and pet_detail_id='.$id)->order('id')->getField('func_cat_id', true);
			}
		}
		
		//扩展内容
		$detail['extends'] = M()->Table('bk_pet_extend')->where('pet_detail_id='.$id)->order('id')->field('id,title,url,content')->select();
		//宠物照片
		$detail['photos'] = $photos = M()->Table('bk_pet_photo')->field('photo_path,id')->where('pet_detail_id='.$id.' and status =0')->order('id')->select();
		foreach ($detail['photos'] as $key => $val) {
			$strphotos[] = $val['photo_path']; 
		}
		$detail['strphotos'] = implode(',', $strphotos);
		//标签
		$tagList = M()->Table('boqii_tag_object')->where(array('object_id'=>$id,'status'=>0,'object_type'=>3))->order('id')->getField('tag_id',true);

		if ($tagList) {
			// echo 2;
			$detail['tagids'] = implode(',', $tagList);
			foreach ($tagList as $key => $val) {
				$tagList[$key] = M()->Table('uc_tag')->field('id,name')->where(array('id'=>$val))->find();
			}	
			$detail['tag'] = $tagList;
		}
		
		//自定义属性
		$detail['self'] = M()->Table('bk_entry_extend')->where('pet_detail_id='.$id)->order('id')->select();
		// echo M()->getLastSql();echo '<pre>';print_r($detail['tag']);
		return $detail;
	}

	/**
	 * 删除词条相册图片
	 * @param pid 相片id
	 * 
	 * @return array 
	 */
	public function delEntryPhoto($pid) {
		$where = "id = ".$pid;
		$data['status'] = -1;
		$r = M()->Table("bk_pet_photo")->where($where)->save($data);
		return $r;
		
	}	
	
	
	/**
	 * 根据一级分类id获取二级分类
	 * 获取所有二级分类
	 * 
	 * @return array 二级分类
	 */
	public function getParentCat($id) {
		$where = 'status = 0 and parent_id = 0';
		return M()->Table('bk_pet_category')->field('id,name')->where($where)->order('id asc')->select();
	}

	
	/**
	 * 获取所有一级分类词条
	 * 
	 * @return array 一级分类
	 */
	public function getRootCat() {
		$where = 'status = 0 and parent_id = 0';
		$result =  M()->Table('bk_entry_cat')->field('id,name')->where($where)->order('id asc')->select();
		return $result;
	}


	/**
	 * 根据一级获取二级分类词条
	 *
	 * @param $id int 一级分类id
	 *
	 * @return array 二级分类
	 */
	public function getSecCatById($id) {
		$where = 'status = 0 and parent_id = '.$id.'';
		return M()->Table('bk_entry_cat')->field('id,name')->where($where)->order('id asc')->select();
	}

	/**
	 * 根据二级获取三级分类词条
	 *
	 * @param $id int 二级分类id
	 *
	 * @return array 三级分类
	 */
	public function getSubByParCat($id) {
		$where = 'status = 0 and parent_id = '.$id.'';
		return M()->Table('bk_entry_cat')->field('id,name')->where($where)->order('id asc')->select();
	}

	/**
	 * 根据代号获得分类id
	 *
	 * @param $id string 分类code
	 *
	 * return array 分类id 
	 */
	public function getCatIdByCode($code) {
		$where = 'status = 0 and code = "'.$code.'"';
		return M()->Table('bk_entry_cat')->where($where)->getField('id');
	}

}
?>
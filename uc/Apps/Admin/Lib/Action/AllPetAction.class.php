<?php
/**
 * 词条管理
 *
 * @author JasonJiang
 * @created 2014-09-29 
 */
class AllPetAction extends ExtendAction {
	/** 
	 * 词条管理
	 *（词条管理页）
	 */
	public function entryList() {
		// URL参数
		$data = $this->_get('data');

		$entryModel = D('BkAllPet');

		// 分页参数
		// 当前页码
		$data['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
		// 页显数量
		$data['pageNum'] = 10;
		// 当前URL
		$url = '/iadmin.php/AllPet/entryList?';
		// 查询条件：词条名
		if($data['name'] && !in_array($data['name'], '请输入词条关键字')){
			$url .= 'data[name]='.$data['name'].'&';
			$this->assign('name',$data['name']);
		}
		// 查询条件：词条名
		if($data['tag_name'] && !in_array($data['tag_name'], '请输入标签关键字')){
			$url .= 'data[tag_name]='.$data['tag_name'].'&';
			$this->assign('tag_name',$data['tag_name']);
		}
		// 查询条件：词条创建时间
		if($data['start_time']){
			$url.='data[start_time]='.$data['start_time'].'&';
			$this->assign('start_time',$data['start_time']);
		}
		if($data['end_time']){
			$url.='data[end_time]='.$data['end_time'].'&';
			$this->assign('end_time',$data['end_time']);
		}
		// 三级联动分类
		// 所有一级分类
		$rootCatlist = $entryModel->getRootCat();
		$this->assign('rootCatlist',$rootCatlist);
		// 查询条件：一级分类
		if($data['first']){
			$url.='data[first]='.$data['first'].'&';
			$this->assign('first',$data['first']);
		}
		// 查询条件：二级分类
		if($data['second']){
			$url.='data[second]='.$data['second'].'&';
			$this->assign('second',$data['second']);
		}
		// 查询条件：三级分类
		if($data['third']){
			$url.='data[third]='.$data['third'].'&';
			$this->assign('third',$data['third']);
		}
			
		// 排序字段：发布时间1 降序；浏览数2 降序
		if($_GET['order']){
			$data['order'] = $_GET['order'];
			$url.='order='.$data['order'].'&';
			$this->assign('order',$data['order']);
		}

		// 查询字段
		$data['field'] = 'id,name,first_cat_id,sec_cat_id,cat_id,view_num,create_time,status';
		$url .= 'page=';
		$this->assign('url',$url.$data['page']);
		// 词条列表
		$entryList = $entryModel -> getEntryList($data);
		// 如果超过页面数据则为最大
		if( $data['page']  >= $entryModel->pagecount){
			$data['page'] = $entryModel->pagecount;
		}
		// 分页信息
		$pageHtml = $this->page($url,$entryModel->pagecount, $data['pageNum'],$data['page'],$entryModel->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$data['page']);
		$this->assign('list',$entryList);

		$this->display('entryList');
	}

	/**
	 * 词条添加/编辑
	 *（词条信息页）
	 */
	public function addEntry(){
		// 词条Model实例化
		$entryModel = D('BkAllPet');
		// 词条id
		$id = $this->_get('id');

		// 词条id存在，获取词条信息
		if($id){
			// 词条信息
			$detail = $entryModel->getPetDetail($id);
			$this->assign('detail',$detail);
			// echo '<pre>';print_r($detail);
			// 获取词条二级分类
			$this->assign('parent',$detail['sec_cat_id']);
			// 获取词条三级分类
			$this->assign('category',$detail['cat_id']);
		}
		// 获取植物词条的id
		$plantId = $entryModel->getCatIdByCode('plant');
		$this->assign('plantId',$plantId);

		// 获取所有一级分类列表
		$rootCatlist = $entryModel->getRootCat();
		$this->assign('rootCatlist',$rootCatlist);

		// 属性程度
		$degreeList = array(0,1,2,3,4,5);
		$this->assign('degreeList', $degreeList);

		// 体型
		$weightList = array(3=>'大型',2=>'中型',1=>'小型');
		$this->assign('weightList', $weightList);
		// 毛长
		$hairList = array(0=>'无毛',1=>'短毛', 2=>'长毛'); 
		$this->assign('hairList', $hairList);

		// 功能分类
		$funcCatList = array(array('id'=>'1','name'=>'伴侣犬'), array('id'=>'2','name'=>'牧羊犬'), array('id'=>'3','name'=>'梗类犬'), array('id'=>'4','name'=>'守卫犬'), array('id'=>'5','name'=>'枪猎犬'), array('id'=>'6','name'=>'工作犬'), array('id'=>'7','name'=>'看家犬'), array('id'=>'8','name'=>'雪橇犬'), array('id'=>'9','name'=>'玩赏犬'), array('id'=>'10','name'=>'搜查犬'), array('id'=>'11','name'=>'导盲犬'), array('id'=>'12','name'=>'爆破犬')); 
		$this->assign('funcCatList', $funcCatList);

		// 花期
		$flowerList = array(array('id'=>'1','name'=>'春'), array('id'=>'2','name'=>'夏'), array('id'=>'3','name'=>'秋'), array('id'=>'4','name'=>'冬'), array('id'=>'5','name'=>'全年')); 
		$this->assign('flowerList', $flowerList);

		// 颜色
		$colorList = array(array('id'=>'1','name'=>'蓝紫色'), array('id'=>'2','name'=>'白色'), array('id'=>'3','name'=>'黄色'), array('id'=>'4','name'=>'红色'), array('id'=>'5','name'=>'粉红色'), array('id'=>'6','name'=>'紫红色'), array('id'=>'7','name'=>'橙色'), array('id'=>'8','name'=>'绿色')); 
		$this->assign('colorList', $colorList);

		// 摆放位置
		$placeList = array(array('id'=>'1','name'=>'办公室'), array('id'=>'2','name'=>'会议室'), array('id'=>'3','name'=>'客厅'), array('id'=>'4','name'=>'卧室'), array('id'=>'5','name'=>'室外')); 
		$this->assign('placeList', $placeList);

		// 功效
		$effectList = array(array('id'=>'1','name'=>'除甲醛'), array('id'=>'2','name'=>'净化空气'), array('id'=>'3','name'=>'药用'), array('id'=>'4','name'=>'园林')); 
		$this->assign('effectList', $effectList);
		$this->display('addEntry');
	}

	/**
	 * 保存词条信息
	 */
	public function saveEntry() {
		set_time_limit(0);
		// post提交参数
		$data = $_POST;
		// 词条Model实例化
		$entryModel = D('BkAllPet');
		
		// 编辑词条
		if($data['id']) {
			// 词条分类
			if($data['category']) {
				$data['cat_id'] = $data['category'];
			} else {
				$data['cat_id'] = 0;
			}
			
			// 词条信息
			$info = $entryModel->getPetDetail($data['id']);

			// 记录编辑操作日志
			$field = array(
					'name'=>array(
						'name'=>'标题'	
					),
					'letter'=>array(
						'letter'=>'首字母'	
					),
					'relation_forum'=>array(
						'relation_forum'=>'相关板块id'	
					),
					'pinyin'=>array(
						'pinyin'=>'宠物名称拼音'
					),
					'summary'=>array(
						'title'=>'简介',
						'flag'=>1
					),
					'content'=>array(
						'title'=>'内容',
						'flag'=>1
					),
					'knowledge'=>array(
						'title'=>'相关知识',
						'flag'=>1
					),
					'pic_path'=>array(
						'title'=>'图片'
					),
					'cat_id'=>array(
						'title'=>'分类'
					),
			);
			$this->groupTip('BkAllPet','id',$data['id'],$field,$data,32);

			// 编辑操作
			$result = $entryModel->editEntry($data);

//			// 更新搜索库：编辑词条
//			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=1&param[pid]=". $data['id'] ."&param[type]=3";
//			get_url($url);

			// 清空词条缓存
			$cacheRedis = Cache::getInstance('Redis');
			$petRedisKey = 'baike:pet:all';
			$cacheRedis->del($petRedisKey);
			//$this->recordOperations(5,20,$data['id']);

			// 返回
			$p = $this->_post('page');
			if($p) {
				$location= "/iadmin.php/AllPet/entryList?page=" . $p;
			}else {
				$location= "/iadmin.php/AllPet/entryList";
			}
			if($result){
				echo "<script>alert('词条修改成功');location.href='" . $location ."';</script>";
			}
			exit;
		}
		// 新增词条
		else{
			// 保存词条信息
			$id = $entryModel->addEntry($data);
			
			if($id){
				// 清空词条缓存
				$cacheRedis = Cache::getInstance('Redis');
				$petRedisKey = 'baike:pet:all';
				$cacheRedis->del($petRedisKey);
				// 记录新增词条操作
				$this->recordOperations(1,32,$id);

				// 返回
				showmsg('词条添加成功！','/iadmin.php/AllPet/entryList');
				
			}else{
				alert('词条添加失败！');
			}
		}
	}
	
	/**
	 * ajax方法：获取一级分类对应的二级分类
	 */
	public function ajaxGetSecondCatList() {
		// 词条Model实例化
		$entryModel = D('BkAllPet');
		// URL参数：分类id
		$pid = $_GET['id'];
		// 获取二级分类
		$result = $entryModel->getSecCatById($pid);
		if(!$result) {
			$result = array();
		}
		$data['secCats'] = $result;
		$this->ajaxReturn($data,'JSON');
	}

	/**
	 * ajax方法：获取一级分类对应的二级分类（按体型）
	 */
	public function ajaxGetFuncCatList() {
		// 词条Model实例化
		$entryModel = D('BkAllPet');
		// URL参数：分类id
		$pid = $_GET['id'];
		// 获取三级分类
		$arr = $entryModel->getSubByParCat($pid);
		if(!$arr) {
			$arr = array();
		}
		$data['subCats'] = $arr;

		// 获得爬虫分类id
		$reptileId = $entryModel->getCatIdByCode('reptile');
		// 属性打分
		if(in_array($pid, array(3,4,5,6,$reptileId))) {
			$data['attribute'] = 1;
		} else {
			$data['attribute'] = 0;
		}

		// 体型（狗狗/猫咪二次词条分类下词条才有体型属性）
		if($pid == 3 || $pid== 4) {
			$data['size'] = 1;
		} else {
			$data['size'] = 0;
		}

		// 毛长（狗狗/猫咪二次词条分类下词条才有毛长属性）
		if($pid == 3 || $pid== 4) {
			$data['hair'] = 1;
		} else {
			$data['hair'] = 0;
		}

		// 功能（狗狗二次词条分类下词条才有功能属性）
		if($pid == 3) {
			$data['funcCats'] = 1;
		} else {
			$data['funcCats'] = 0;
		}

		// 获得观花植物id
		$flowerId = $entryModel->getCatIdByCode('flower');
		
		// 花期和颜色（植物二级分类观花植物才有花期和颜色）
		if($pid == $flowerId) {
			$data['flower'] = 1;
			$data['color']  = 1;
			
		} else {
			$data['flower'] = 0;
			$data['color']  = 0;
			
		}
		// 获得观叶植物id
		$leafId = $entryModel->getCatIdByCode('leaf');
		// 摆放位置和功效（植物二级分类观叶植物才有摆放位置和功效）
		if($pid == $leafId) {
			$data['place'] = 1;
			$data['effect']  = 1;
			
		} else {
			$data['place'] = 0;
			$data['effect']  = 0;
			
		}
		$this->ajaxReturn($data,'JSON');
	}

	/**
	 * ajax方法：删除词条相册图片
	 */
	public function ajaxDelEntryPhoto() {
		// 词条Model实例化
		$entryModel = D('BkAllPet');
		// URL参数：分类id
		$pid = $_GET['id'];
		$res = $entryModel -> delEntryPhoto($pid);
		if ($res) {
			$data['info'] 	= '操作成功!';
			$data['status'] = 'ok';
		}else{
			$data['info'] 	= '操作失败!';
			$data['status'] = 'fail';
		}
		$this->ajaxReturn($data,'JSON');
	}
	
	/**
	 * 批量删除词条
	 *（词条管理页）
	 */
	public function deletePet(){
		// 词条Model实例化
		$entryModel = D('BkAllPet');
		// 英文逗号串接的词条id
		$ids = $this->_get('deletePet');
		// 操作标志
		$act = $this->_get('act');
		// 当前页码
		$page = $this->_get('page');
		// 分割词条id
		$idArr = explode(',',$ids);

		// 循环删除操作
		foreach($idArr as $key=>$val){
			if($val){
				// 记录删除操作
				$this->recordOperations(2,32,$val);

				// 删除词条
				$entryModel->delPet($val);

				// 更新搜索库：删除词条
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=". $val ."&param[type]=3";
				get_url($url);

				
				
			}
		}
		// 清空词条缓存
		$cacheRedis = Cache::getInstance('Redis');
        $petRedisKey = 'baike:pet:all';
        $cacheRedis->del($petRedisKey);
	
		// 返回操作
		if(empty($act)){
			$this->redirect('/iadmin.php/AllPet/entryList?page='.$page);//echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}


	/**
	 * 批量操作发布词条（删除的词条还原）
	 *（词条管理页）
	 */
	public function publishPet(){
		// 词条Model实例化
		$entryModel = D('BkAllPet');
		// 英文逗号串接的词条id
		$ids = $this->_get('publishPet');
		// 操作标志
		$act = $this->_get('act');
		// 当前页码
		$page = $this->_get('page');
		// 分割词条id
		$idArr = explode(',',$ids);

		foreach($idArr as $key=>$val){
			if($val){
				// 批量发布词条
				$entryModel->publishPet($val);

				// 更新搜索库：新增词条
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=add&param[config_object]=1&param[pid]=". $val ."&param[type]=3";
				get_url($url);
			}
		}

		// 清空词条缓存
		$cacheRedis = Cache::getInstance('Redis');
        $petRedisKey = 'baike:pet:all';
        $cacheRedis->del($petRedisKey);
		
		// 返回操作
		if(empty($act)){
			$this->redirect('/iadmin.php/AllPet/entryList?page='.$page);//echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}
	
	
	/***********************************************************************/
	//获取二级分类json(撤销)
	public function getSubCategory() {
		$entryModel = D('BkAllPet');
		$pid = $_GET['id'];
		$arr = $entryModel->getSubByParCat($pid);
		$this->ajaxReturn($arr,'JSON');
	}

	//导宠物种类数据
	public function importCategory(){
		$yCategory = M('boqii_baike_category')->select();
		foreach($yCategory as $key=>$val){
			if(strlen($val['cid']) == 2){
				$data['name'] = $val['cname'];
				$data['sec_cat_id'] = 0;
				$data['status'] = 0;
				$data['create_time'] = time();
				$data['update_time'] = time();
				$r = M('bk_pet_category')->add($data);
			}
			if(strlen($val['cid']) == 4){
				switch(substr($val['cid'],0,2)) {
					case "10":
						$n = "1";
						break;
					case "11":
						$n = "8";
						break;
					case "12":
						$n = "12";
						break;
					case "13":
						$n = "16";
						break;
					case "14":
						$n = "21";
						break;
					default:
						$n = "1";
						break;
				}
				$data['name'] = $val['cname'];
				$data['sec_cat_id'] = $n;
				$data['status'] = 0;
				$data['create_time'] = time();
				$data['update_time'] = time();
				$r = M('bk_pet_category')->add($data);
				
				//详情
				$yDetail = M('boqii_baike_knowledge')->where('cid ='.$val['cid'])->select();
				foreach($yDetail as $k=>$v){
					$data1['cat_id'] = $r;
					$data1['sec_cat_id'] = $n;
					$data1['name'] = $v['title'];
					$data1['letter'] = $v['letter'];
					$data1['summary'] = $v['info'];
					$data1['content'] = $v['standard'];
					$data1['knowledge'] = $v['knowledge'];
					$data1['pic_path'] = 'Data/BK/P/'.str_replace('_thumb','',$v['attachurl']);
					$data1['status'] = -1;
					$data1['create_time'] = $v['postdate'];
					$data1['update_time'] = time();
					M('bk_pet_detail')->add($data1);
				}
			}
		}
	}
	//批量替换图片地址
	public function pergPetDetail(){
		$detail = M()->Table('bk_pet_detail')->field('id,content')->select();
		foreach($detail as $key=>$val){
			$content = $val['content'];
			$p = '/<img.*src="(.*)"\\s*.*>/iU';
			preg_match_all($p, $content, $m);
			foreach($m[1] as $k=>$v){
				$name = basename($v);
				$content = str_replace($v,C('IMG_DIR').'/Data/BK/P/'.$name,$content);
			}
			
			//更新
			$data['content'] = $content;
			M()->Table('bk_pet_detail')->where('id ='.$val['id'])->save($data);
		}
	}
	
}
?>
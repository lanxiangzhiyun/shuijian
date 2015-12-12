<?php
/**
 * 推荐处理
 */
class PushAction extends ExtendAction {


	/**
	 * 热门话题排序
	 */
	public function index() {
		$limit = 10;
		$page = $this -> _get('page');
		if ($page == '' || !is_numeric($page)) {
			$page = 1;
		} 
		$UcPush = D('UcPush');
		$PushCount = $UcPush -> hasPushCount();
		$pcount = ceil($PushCount / $limit);
		if ($page >= $pcount) {
			$page = $pcount;
		} 
		$url = '/iadmin.php/Push/index?page=';

		$Pushes = $UcPush -> hasUserAndPush($page, $limit);
		$pageHtml = $this -> page($url, $pcount, $limit, $page, count($Pushes));
		$this -> assign('Pushes', $Pushes);
		$this -> assign('pageHtml', $pageHtml);
		$this -> assign('page', $page);
		$this -> display('index');
	} 

	
	/**
	 * 栏目组列表
	 */
	public function publishGroupList(){
		$publishGroupModel = D('PublishGroup');
		$publishGroups = $publishGroupModel->getPublishGroup(1,50);
		$this->assign('publishGroups',$publishGroups);
		$this->display('publishGroupList');
	}
	
	/*
	*栏目列表
	*/
	public function publishList(){
		$pgid = $this->_get('pgid');
		$publishModel = D('Publish');
		$publishGroupInfo = D('PublishGroup')->getPublishGroupInfo($pgid);
		$where['publish_group_id']=$pgid;
		$where['status']=0;
		$publish = $publishModel->getPublish(1,500,$where);
		foreach($publish as $key=>$val){
			$pid[] = $val['id'];
		}
		$where['publish_id']=array('in',$pid);
		//获取每个小组欲发布 和发布的文章数
		/*
		$getWill = $publishModel->getWillPublishCount($where);
		$getHad = $publishModel->getHadPublishCount($where);
		foreach($publish as $key=>$val){
			foreach($getWill as $k=>$v){
				if($val['id']==$v['publish_id']){
					$publish[$key]['will'] = $v['num'];
				}
			}
			foreach($getHad as $kk=>$vv){
				if($val['id']==$vv['publish_id']){
					$publish[$key]['had'] = $vv['num'];
				}
			}
		}
		*/			
		$this->assign('publishGroupInfo',$publishGroupInfo);
		$this->assign('pgid',$pgid);
		$this->assign('publish',$publish);
		$this->display('publishList');
	}
	
	/*
	*推荐文章列表
	*/
	public function publishArticleList(){
		$pid = $this->_get('pid');
		$pgid = $this->_get('pgid');
		$publishArticleModel = D('PublishArticle');
		$publishGroupInfo = D('PublishGroup')->getPublishGroupInfo($pgid);
		
		$publishInfo = D('Publish')->getPublishInfo($pid);
		$page = 1;
		$limit= 20;
		$where['publish_id']=$pid;
		$where['status'] = array('EGT',0);
		$articles = $publishArticleModel->getPublishArticle($page,$limit,$where);
		$this->assign('articles',$articles);
		$this->assign('pid',$pid);
		$this->assign('pgid',$pgid);
		$this->assign('publishGroupInfo',$publishGroupInfo);
		$this->assign('publishInfo',$publishInfo);
		$this->display('publishArticleList');
	}
	/**
	*栏目添加
	*/
	public function publishAdd(){
		$pgid = $this->_get('pgid');
		if($pgid){
			//栏目组信息
			$publishGroupInfo = D('PublishGroup')->getPublishGroupInfo($pgid);
			$this->assign('publishGroupInfo',$publishGroupInfo);
		}
		if($this->_post('data')){
			$data = $this->_post('data');
			$data['create_time'] = time();
			D('Publish')->add($data);
			$url = C('I_DIR')."/iadmin.php/Push/publishList?pgid=".$data['publish_group_id'];
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url"); exit;
		}
		
		$this->display('publishAdd');
	}

	/**
	*栏目删除
	*/
	public function delPublish(){
		$pid = $this->_get('pid');
		$pgid = $this->_get('pgid');
        $publishModel = D('Publish');
		D('Publish')->query('UPDATE `boqii_publish` SET `status`=-1 WHERE id='.$pid);
        $data = $publishModel->where('id='.$pid)->find();
        //清除缓存--暂时解决大首页 优选商户 缓存
        if (in_array($data['code'],array(10019,10023,10024)) ) {
            $this -> clearRedisByCode($data['code']);
        }

		$url = C('I_DIR')."/iadmin.php/Push/publishList?pgid=".$pgid;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url"); exit;
	}
	/**
	*文章推荐添加
	*/
	public function publishArticleAdd(){
		if($this->_get('pid')){
			$pid = $this->_get('pid');
			$pgid = $this->_get('pgid');
			$publishGroupInfo = D('PublishGroup')->getPublishGroupInfo($pgid);
			$publishInfo = D('Publish')->getPublishInfo($pid);
			$this->assign('publishGroupInfo',$publishGroupInfo);
			$this->assign('publishInfo',$publishInfo);
			$this->assign('pgid',$pgid);
			$this->assign('pid',$pid);
		}
		if($this->_post('data')){
			$publishArticleModel = D('PublishArticle');
			$data = $this->_post('data');
			$pid = $this->_post('pid');
			$pgid = $this->_post('pgid');

            $publishModel = D('Publish');
            $datas = $publishModel->where('id='.$pid)->find();
            //清除缓存--暂时解决大首页 优选商户 缓存
            if (in_array($datas['code'],array(10019,10023,10024)) ) {
                $this -> clearRedisByCode($datas['code']);
            }

			$data['publish_id'] = $pid;
			$data['create_time'] = time();
			$result = $publishArticleModel->addPublishArticle($data);
			$this -> recordOperations(1, 30, $result);
			$get_url = C("BLOG_DIR")."/site/Public/html";
			for($i=0;$i<10;$i++){
				$cr_url = get_url($get_url);
				if($cr_url=='success'){
					break;
				}
			}
			$url = C('I_DIR')."/iadmin.php/Push/publishArticleList?pid=".$pid."&pgid=".$pgid;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url");exit; 
		}
		$this->display('publishArticleAdd');
	}
	/**
	* 文章推荐删除
	*/
	public function publishArticledel(){
		$data['id']=$this->_get('aid');
		$data['status'] = -1;

        $publishArticleModel = D('PublishArticle');
        $code = $publishArticleModel->getPublishCode($data['id']);
        //清除缓存--暂时解决大首页 优选商户 缓存
        if (in_array($code,array(10019,10023,10024)) ) {
            $this -> clearRedisByCode($code);
        }

		D('PublishArticle')->savePublishArticle($data);
		$this -> recordOperations(2, 30,$data['id']);
		$get_url = C("BLOG_DIR") . "/site/Public/html";
		for($i=0;$i<10;$i++){
			$cr_url = get_url($get_url);
			if($cr_url=='success'){
				break;
			}
		}
		$url = C('I_DIR')."/iadmin.php/Push/publishArticleList?pid=".$this->_get('pid')."&pgid=".$this->_get('pgid');
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url"); 
	}
	/*
	*文章添加编辑页面
	*/	
	public function publishArticleEdit(){
		$publishArticleModel = D('PublishArticle');
		if($this->_get('aid')){
			$aid = $this->_get('aid');
			$pgid = $this->_get('pgid');
			$article = $publishArticleModel->getPublishArticleInfo($aid);
			$this->assign('article',$article);
			$this->assign('pgid',$pgid);
			$this->assign('pid',$this->_get('pid'));
		}
		if($this->_post('data')){
			$data = $this->_post('data');
			$field = array(
					'id'=>array(
						'title'=>'编号'
					),
					'title'=>array(
						'title'=>'标题'	
					),
					'url'=>array(
						'title'=>'链接/文章ID'
					),
					'input1'=>array(
						'title'=>'输入框1'
					),
					'input2'=>array(
						'title'=>'输入框2'
					),
					'input3'=>array(
						'title'=>'输入框3'
					),
					'img1'=>array(
						'title'=>'图片1'
					),
					'textarea1'=>array(
						'title'=>'摘要',
						'flag'=>1
					),
					'textarea2'=>array(
						'title'=>'备用',
						'flag'=>1
					)
				);
			
			
			$this->groupTip('PublishArticle','id',$data['id'],$field,$data,28);
			$publishArticleModel->savePublishArticle($data);

            $code = $publishArticleModel->getPublishCode($data['id']);
            //清除缓存--暂时解决大首页 优选商户 缓存
            if (in_array($code,array(10019,10023,10024)) ) {
                $this -> clearRedisByCode($code);
            }

			$get_url = C("BLOG_DIR")."/site/Public/html";
			for($i=0;$i<10;$i++){
				$cr_url = get_url($get_url);
				if($cr_url=='success'){
					break;
				}
			}
			$url = C('I_DIR')."/iadmin.php/Push/publishArticleList?pid=".$this->_post('pid')."&pgid=".$this->_post('pgid');
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url"); 
		}
		$this->display('publishArticleEdit');
	}
	/**
	*删除栏目
	*/
	public function delColumn(){
		
	}

	public function pushIndex() {
		$BbsThread = D('BbsThread');
		$UcPush = D('UcPush'); 
		// $bbsThreads = $BbsThread->hotThreads();
		$data['ifcheck'] = array('eq', 1);
		$data['displayorder'] = array('egt', 0);
		$bbsThreads = $BbsThread -> where($data) -> order('tid desc') -> limit('30') -> select();
		foreach($bbsThreads as $key => $val) {
			$pushs = $UcPush -> where(array('tid' => $val['tid'], 'valid' => 1)) -> select();
			if ($pushs) {
				$bbsThreads[$key]['ispush'] = 1;
			} else {
				$bbsThreads[$key]['ispush'] = 0;
			} 
		} 
		$this -> assign('bbsThreads', $bbsThreads);
		$this -> display('pushIndex');
	} 

	/**
	 * 新增推荐
	 */
	public function pushHandle() {
		$ids = $this -> _get('pushHandle');
		$act = $this -> _get('act');
		$idArr = explode(',', $ids);
		$bbsUrl = C('BBS_DIR');
		$UcPush = D('UcPush');
		$BbsThread = D('BbsThread');

		foreach($idArr as $key => $val) {
			if ($val) {
				$pushIds = $UcPush -> where(array('tid' => $val)) -> limit(1) -> select();
				if ($pushIds) {
					$data['valid'] = 1;
					$UcPush -> where(array('tid' => $val)) -> save($data);
					$pushId = $pushIds[0]['id'];
				} else {
					$UcPush -> create();
					$bbsThreads = $BbsThread -> hotThreads($val);
					$data['type'] = 1;
					$data['subject'] = $bbsThreads[0]['subject'];
					$data['content'] = mb_substr(strip_tags($bbsThreads[0]['bbs_posts']['message']), 0, 25, 'utf-8');
					$data['tid'] = $val;
					$data['uid'] = $bbsThreads[0]['authorid'];
					$data['linkurl'] = $bbsUrl . "/content/viewthread-" . $val . ".html";
					$data['postdate'] = time();
					$data['valid'] = 1;
					$data['sort'] = 0;
					$pushId = $UcPush -> add($data);
				} 
				$this -> recordOperations(1, 9, $pushId);
			} 
		} 

		if (empty($act)) {
			$this -> redirect('/iadmin.php/Push/pushIndex');
		} else {
			echo 1;
			exit;
		} 
	} 

	/**
	 * 取消推荐
	 */
	public function pushCancel() {
		$id = $this -> _get('pushCancel');
		$acts = $this -> _get('acts');
		$act = $this -> _get('act');
		$UcPush = D('UcPush');
		$idArr = explode(',', $id);
		foreach($idArr as $key => $val) {
			if ($val) {
				$data['valid'] = 0;
				$pushId = $UcPush -> where(array('tid' => $val)) -> select();
				$UcPush -> where(array('tid' => $val)) -> save($data);
				$this -> recordOperations(2, 9, $pushId[0]['id']);
			} 
		} 

		if (!empty($acts)) {
			$this -> redirect('/iadmin.php/Push/index');
		} else if (!empty($act)) {
			echo 1;
			exit;
		} else {
			$this -> redirect('/iadmin.php/Push/pushIndex');
		} 
	} 

	/**
	 * 热门话题排序sort
	 */
	public function keepSort() {
		$postData = $this -> _post('data');
		$UcPush = D('UcPush');
		foreach($postData as $key => $val) {
			if ($val != 0) {
				$data['sort'] = $val;
				$PushBefore = $UcPush -> where(array('id' => $key)) -> select();
				if ($PushBefore[0]['sort'] != $val) {
					$UcPush -> where(array('id' => $key)) -> save($data);
					$this -> recordOperations(3, 10, $key, 2, 0, 0, '排序', $PushBefore[0]['sort'], $val);
				} 
			} 
		} 

		$this -> redirect('/iadmin.php/Push/index');
	} 

	/**
	 * 公告管理页
	 */
	public function noticePage() {
		$pushModel = D('UcPush');
		$page = $this -> _get('page');
		if (!is_numeric($page)) {
			$page = 1;
		} 
		$limit = 10;
		$check = 3;
		$count = $pushModel -> getCount($check);
		$pcount = ceil($count / $limit);
		if ($page >= $pcount) {
			$page = $pcount;
		} 
		$notices = $pushModel -> getList($page, $limit, $check);
		$url = '/iadmin.php/Push/noticePage?page=';
		$pageHtml = $this -> page($url, $pcount, $limit, $page, count($notices));
		$this -> assign('url', $url . $page);
		$this -> assign('notices', $notices);
		$this -> assign('pageHtml', $pageHtml);
		$this -> assign('page', $page);
		$this -> display('noticePage');
	} 

	/**
	 * 百科知识管理页
	 */
	public function baiKePage() {
		$pushModel = D('UcPush');
		$page = $this -> _get('page');
		if (!is_numeric($page)) {
			$page = 1;
		} 
		$limit = 10;
		$check = 2;
		$count = $pushModel -> getCount($check);
		$pcount = ceil($count / $limit);
		if ($page >= $pcount) {
			$page = $pcount;
		} 
		$baikes = $pushModel -> getList($page, $limit, $check);
		$url = '/iadmin.php/Push/baiKePage?page=';
		$pageHtml = $this -> page($url, $pcount, $limit, $page, count($baikes));
		$this -> assign('url', $url . $page);
		$this -> assign('baikes', $baikes);
		$this -> assign('pageHtml', $pageHtml);
		$this -> assign('page', $page);
		$this -> display('baiKePage');
	} 

	/**
	 * 百科前段推荐管理 type类型为4
	 */
	public function baiKeFront() {
		$pushModel = D('UcPush');
	} 

	/**
	 * 百科前段添加页面
	 */

	/**
	 * 公告、百科添加编辑页面
	 */
	public function addPushPage() {
		$param['id'] = $this -> _get('id');
		if (!empty($param)) {
			$pushModel = D('UcPush');
			$push = $pushModel -> getPushInfo($param);
			$this -> assign('push', $push);
		} 
		$this -> assign('pushType', C('PUSH_TYPE'));
		$this -> display('addPushPage');
	} 

	/**
	 * 提交修改添加等
	 */
	public function savePush() {
		$data = $this -> _post('data');
		$data['content'] = urldecode($data['content']);
		$data['postdate'] = time();
		$pushModel = D('UcPush');
		if ($data['type'] == 2 && empty($data['uid'])) {
			echo 1;
			exit;
		} 
		// 判断是否为空
		if (!isset($data['content'])) {
			echo 1;
			exit;
		} 
		$result = $pushModel -> savePushInfo($data); 
		// 2 跳转到 百科列表页 3 跳转到 公告列表页
		if ($data['type'] == 2) {
			echo 2;
		} else {
			echo 3;
		} 
		exit;
	} 

	/**
	 * 公告删除
	 */
	public function delNoticePush() {
		$id = $this -> _get('delNoticePush');
		$act = $this -> _get('act');
		$pushModel = D('UcPush');
		$page = $this -> _get('page');
		$idArr = explode(',', $id);
		foreach($idArr as $key => $val) {
			if ($val) {
				$push = $pushModel -> getPushInfo(array('id' => $val));
				$pushModel -> savePushInfo(array('id' => $val, 'valid' => 0));
				$this -> recordOperations(2, 9, $push['id']);
			} 
		} 
		// $act 为空判断 是链接删除 否则为ajax提交删除
		if (empty($act)) {
			$this -> redirect('/iadmin.php/Push/noticePage?page=' . $page);
		} else {
			echo 1;
			exit;
		} 
	} 

	/**
	 * 百科删除
	 */
	public function delBaikePush() {
		$id = $this -> _get('delBaikePush');
		$act = $this -> _get('act');
		$pushModel = D('UcPush');
		$page = $this -> _get('page');
		$idArr = explode(',', $id);
		foreach($idArr as $key => $val) {
			if ($val) {
				$push = $pushModel -> getPushInfo(array('id' => $val));
				$pushModel -> savePushInfo(array('id' => $val, 'valid' => 0));
				$this -> recordOperations(2, 9, $push['id']);
			} 
		} 
		// $act 为空判断 是链接删除 否则为ajax提交删除
		if (empty($act)) {
			$this -> redirect('/iadmin.php/Push/baiKePage?page=' . $page);
		} else {
			echo 1;
			exit;
		} 
	} 



	/**
	 * 论坛推送内容列表页
	 */
	public function indexPushlist() {
		$push = D("Push");
		$typelist = $push->getPushTypeList();
		$param['type'] = !empty($_POST['s_type']) ? $_POST['s_type'] : (isset($_GET['typeid']) ? $_GET['typeid'] : 0);
		$param['subject'] = !empty($_POST['s_subject']) ? $_POST['s_subject'] : (isset($_GET['subject']) ? urldecode($_GET['subject']) :'');
		$param['page'] = intval($_GET['page']);
		$param['page_num'] = 10;
		$pushlist = $push->getPushlist($param);
		if(!$pushlist){
			echo  '<script>history.back();</script>';
		}
		$url = '/iadmin.php/Push/indexPushlist?page=';
		//$pageHtml = $this -> page($url, $pcount, $limit, $page, count($Pushes));
		$pcount = ceil($push->total/$param['page_num']);
		$pageHtml = $this -> page($url, $pcount, $param['page_num'], $param['page'], count($pushlist));
		
		$this->assign('pageHtml', $pageHtml);
		$this->assign('pushlist', $pushlist);
		$this->assign('type', $param['type']);
		$this->assign('subject', $param['subject']);
		$this->assign('typelist', $typelist);
		$this->display("index_pushlist");
	}

	/**
	 * 推送内容新增页
	 */
	public function indexPush() {
		$pushModel = D("Push");
		$typelist = $pushModel->getPushTypeList();
		$this->assign("typelist", $typelist);
				
		//操作标志
		$act = !empty($_GET['act']) ? $_GET['act'] : "add"; 
		$this->assign("act", $act);
		/*//编辑
		if($act == "edit") {
			if(empty($_GET['bid'])) {
				die("<script>alert('推送规则没有找到！');location.href='../Index/indexPushList';</script>");
			}
			$bid = $_GET['bid'];
			
			$push = $pushModel->getPushById($bid);
			$this->assign("push", $push);
			$sel['typeid']=$_GET['typeid'];
			$sel['subject']=$_GET['subject'];
			$this->assign("sel", $sel);

			$this->display("Index/index_push");

		}*/
		//删除
		if($act == "del") {
			if(empty($_GET['bid'])) {
				die("<script>alert('推送规则没有找到！');location.href='../Push/indexPushList';</script>");
			}
			$bid = $_GET['bid'];
			
			$result = $pushModel->deletePush($bid);
			if($result) {
				$this->recordOperations(2,29,$bid);
				$this->redirect('/iadmin.php/Push/indexPushList', array(), 1,'删除成功！');
				/*die("<script>alert('删除成功！');location.href='../Index/indexPushList';</script>");*/
			}
		}
		//新增
		else {			
			
			$this->display("index_push");
		}
	}

	/**
	 * 推送内容保存页
	 */
	public function indexPushSave() {
		//id
		$param['bid'] = !empty($_POST['bid']) ? $_POST['bid'] : ""; 
		//操作
		$param['act'] = !empty($_POST['act']) ? $_POST['act'] : (!empty($_POST['bid']) ? "edit" : "add"); 
		//选择版块
		$param['type'] = $_POST['type']; 
		//主题id
		$param['tid'] = !empty($_POST['tid']) ? $_POST['tid'] : 0; 
		//标题
		$param['subject'] = $_POST['subject']; 
		//内容
		$param['content'] = $_POST['content'];
		//链接地址
		$param['linkurl'] = $_POST['linkurl'];
		//图片附件
		$attachurl = '';
		if(!empty($_FILES['attachurl']['size'])) {
			//图片上传
			$result = A('Image')->imageUpload('attachurl', 0, 'bbs_push', 'imagick');
			$uploadinfo = json_decode($result, true);
			if($uploadinfo['status'] == 'ok') {
				$attachurl = $uploadinfo['imgpath'];
			}
			else {
				die("<script>alert('".$uploadinfo['tip']."');location.href='../Push/indexPushList';</script>");
			}
		}
		
		$param['attachurl'] = $attachurl;
		
		$pushModel = D("Push");
		if($param['act'] == "edit"){
			$new_data = $param;
			unset($new_data['act']);
			
			$field = array(
					'bid'=>array(
						'title'=>'编号'
					),
					'type'=>array(
						'title'=>'板块'	
					),
					'tid'=>array(
						'title'=>'主题ID'
					),
					'subject'=>array(
						'title'=>'标题'
					),
					'content'=>array(
						'title'=>'内容',
						'flag'=>1
					),
					'linkurl'=>array(
						'title'=>'链接地址'
					)
					
				);
			if(empty($new_data['attachurl'])){
				unset($new_data['attachurl']);
			}else{
				$field['attachurl']=array('title'=>'图片路径');
			}
			
			$this->groupTip('Push','bid',$new_data['bid'],$field,$new_data,29);
		}
		$result = $pushModel->savePush($param);

		if($result) {
			if($param['act'] == "edit") {
				$sel['typeid'] = $_POST['hidtypeid'];
				if(!empty($_POST['hidsubject'])) {
					$sel['subject'] = urlencode($_POST['hidsubject']);
				}


				$this->redirect('/iadmin.php/Push/indexPushList', $sel, 1,'保存成功！');
			}
			else {
				$this->recordOperations(1,29,$result);
				$this->redirect('/iadmin.php/Push/indexPushList', array(), 1,'保存成功！');
			}
		}
	}

	/**
	 * 跳转推送内容编辑页
	 */
	public function editPush() {
		$bid = $_GET['bid'];
		$pushModel = D("Push");
		//推送内容
		$push = $pushModel->getPushById($bid);
		$this->assign("push", $push);
		//版块列表
		$typelist = $pushModel->getPushTypeList();
		$this->assign("typelist", $typelist);
		//操作标志
		$act = !empty($_GET['act']) ? $_GET['act'] : "add"; 
		$this->assign("act", $act);
		//搜索条件
		$sel['typeid']=$_GET['typeid'];
		$sel['subject']=$_GET['subject'];
		$this->assign("sel", $sel);

		$this->display("index_push");
	}


	/*
	*生成推荐数据
	*/
	public function createPush(){
		$publishModel = D('Publish');
		$publishArticleModel = D('PublishArticle');
		$time = time();
		$result = $publishModel->where('publish_group_id=3')->select();
		foreach($result as $key=>$val){
			for($i=1;$i<=8;$i++){
				$data['publish_id'] = $val['id'];
				$data['title'] = '测试-'.$i;
				$data['url'] = 'http://www.boqii.com';
				$data['position'] = $val['id'].'-'.$i;
				$data['create_time'] = $time;
				$publishArticleModel->add($data);
			}
		}
	}

    //通过code清除缓存
    public function clearRedisByCode($code){
        $cacheRedis = Cache::getInstance('Redis');
        //redis的key通过配置文件统一管理
        $key = C('REDIS_KEY.publish').$code;
        $cacheRedis->del($key);
    }


	//===========================生成站点xml===============================
	
	//美图xml列表页
	public function xmlList(){
		$page = $this -> _get('page');
		if (!is_numeric($page)) {
			$page = 1;
		} 
		$xmlModel = M('bbs_xml');
		$limit = 20;
		$count = $xmlModel->count();
		$pcount = ceil($count / $limit);
		if ($page >= $pcount) {
			$page = $pcount;
		} 
		$url = '/iadmin.php/Push/noticePage?page=';
		$list = $xmlModel->page($page)->limit($limit)->order('id desc')->select();
		$pageHtml = $this -> page($url, $pcount, $limit, $page, count($list));
		$this -> assign('pageHtml', $pageHtml);
		$this->assign('list',$list);
		$this->display('xmlList');
	}

	private function mkdirs($dir,$mode=0777){
		if(is_dir($dir) || @mkdir($dir,$mode)){
			return true;
		}
		if(!$this->mkdirs(dirname($dir),$mode)){
			return false;
		}
		return @mkdir($dir,$mode);
	}
	//创建美图xml
	public function createXml(){
		if($_FILES){
			$file = $this->uploadExcle();
			$readContent =  array_filter($this->readerExcle($file));
			$ids = array();
			if($readContent){
				foreach($readContent as $val){
					preg_match('/(-\d+)/',$val,$arr);
					$ids[] = substr($arr[0],1);
				} 
			}
			if($ids){
				//获取对应的数据
				$result = D('BbsThread')->getPicThreadList($ids);
				//生成xml文件
				$fileName = C("RESOURCE_PATH").'360/';
				//判断fileName是否存在，如果不存在进行创建
				$this->mkdirs($fileName,$mode=0777);
				$time = time();
				$name = "360image_".date('Ymd')."_".$time.".xml";
				$file_xml = fopen($fileName.$name,"w+");
				$xml = $this->create_item($result);
				fwrite($file_xml,$xml);
				fclose($file_xml);
				//保存到指定位置，同时生成url记录到数据库
				$data = array(
					'xml_url'=>C('BLOG_DIR').'/resource/360/'.$name,
					'ids'=>implode(',',$ids),
					'create_time'=>$time
				);
				M('bbs_xml')->add($data);
				//跳转到列表页
				$url = C('I_DIR')."/iadmin.php/Push/xmlList";
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: $url"); exit;
			}
		}
		$this->display("createXml");
	}

	//  创建XML单项create_item
	function create_item($result)
	{
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml .= "<document>\n";
		$item;
		if($result){
			foreach($result as $key=>$val){
				$item .= "<item>\n";
				$item .= "<op>add</op>\n"; 
				$item .="<group_id>".$val['tid']."</group_id>\n";
				$item .="<group_url>".$val['url']."</group_url>\n";
				$item .="<group_type><![CDATA[宠物]]></group_type>\n";
				$item .="<group_title><![CDATA[".$val['subject']."]]></group_title>\n";
				$item .="<group_tag><![CDATA[".$val['tag']."]]></group_tag>\n";
				$item .="<group_score>".$val['views']."</group_score>\n";
				$item .="<group_desc><![CDATA[".$val['subject']."]]></group_desc>\n";
				$item .="<update_time>".$val['dateline']."</update_time>\n";
				$pic_item="";
				if($val['picList']){
					foreach($val['picList'] as $k=>$v){
						$k++;
						$pic_item .="<pic_item>\n";
						$pic_item .="<pic_id>".$k."</pic_id>\n";
						$pic_item .="<pic_url>".$v['pic_path']."</pic_url>\n";
						$pic_item .="<pic_from_url>".$val['url']."</pic_from_url>\n";
						$pic_item .="<pic_title><![CDATA[".$val['subject']."]]></pic_title>\n";
						$pic_item .="<pic_desc><![CDATA[".$val['subject']."]]></pic_desc>\n";
						$pic_item .="</pic_item>\n";
					}
				}
				$item .=$pic_item;
				$item .= "</item>\n";
			}
		}
		$xml .= $item;
		$xml .= "</document>\n";
	    return $xml;
	}
	
	//处理上传
	private function uploadExcle(){
		$files = $_FILES['upload'];
		$arr = explode('.',$files['name']);  
		$filename = "./Upload/excel/".time().'.'.$arr[count($arr)-1];
		if(move_uploaded_file($files['tmp_name'],$filename)){
			return $filename;
		}
		exit;
	 }

	 //读取excle表格数据
	 private function readerExcle($file){
		//$file= "./Upload/excel/1387440600.xls";
		vendor('excel.PHPExcel');                 
        vendor('excel.PHPExcel.IOFactory');                 
        vendor('excel.PHPExcel.Reader.Excel5');                 
        vendor('excel.PHPExcel.Reader.Excel2007');
		$extend=pathinfo($file);
        $extend = strtolower($extend["extension"]);
		$extend=='xlsx'?$reader_type='Excel2007':$reader_type='Excel5';
		$objReader = PHPExcel_IOFactory::createReader($reader_type);
		if(!$objReader){                     
            $this->error('抱歉！excel文件不兼容。'); //执行失败，直接抛出错误中断                 
        } 
		$objPHPExcel= $objReader->load($file);     
	    $objWorksheet= $objPHPExcel->getActiveSheet();                 
	    $highestRow= $objWorksheet->getHighestRow();
	    $highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
		$strs=array(); 
		 for ($row =1;$row <= $highestRow;$row++){                     
			 for($cols =0 ;$cols<$highestColumnIndex;$cols++){                         
				$strs[$row][$cols] =(string)$objWorksheet->getCellByColumnAndRow($cols, $row)->getValue();                     
			 }                 
		 }
		 $result = array();
		 foreach($strs as $key => $val){
			$result[] = $val[0];
		 }
		return $result;
	 }
	 
	 /**
	  * 后台栏目推荐信息删除
	  */
	 public function newPublishArticledel(){
	 	$data['id']=$this->_get('aid');
	 	$data['status'] = -1;
	 
	 	$publishArticleModel = D('PublishArticle');
	 	$code = $publishArticleModel->getPublishCode($data['id']);
	 	//清除缓存--暂时解决大首页 优选商户 缓存
	 	if (in_array($code,array(10019,10023,10024)) ) {
	 		$this -> clearRedisByCode($code);
	 	}
	 	D('PublishArticle')->savePublishArticle($data);
	 	$this -> recordOperations(2, 30,$data['id']);
	 	$url = C('I_DIR')."/iadmin.php/Push/publishArticleList?pid=".$this->_get('pid')."&pgid=".$this->_get('pgid');
	 	header("HTTP/1.1 301 Moved Permanently");
	 	header("Location: $url");
	 }

	/****************************** APP配置start ********************************/

	/**
 	 * banner列表 
	 */
	public function appDeploy() {
		$data = $this->_get();
		$pushModel = D('PublishArticle');
		//分页
		$param['page'] 	 	= $data['page'] ? $data['page'] : 1;
		$param['pageNum'] 	= 5;
		$url = '/iadmin.php/Push/appDeploy?page=';

		$appDeployList = $pushModel->getAppDeployList($param);
		// 输入页数比总页数大
		if ($pushModel->banpagecount < $param['page']) {
			$param['page'] = $pushModel->banpagecount;
		}
		// print_r($param);
		$pageHtml = $this -> page($url, $pushModel->banpagecount, $param['pageNum'], $param['page'], $pushModel->bansubtotal);
		$this -> assign('appDeployList', $appDeployList);
		if ($pushModel->banpagecount > 1 ) {
			$this -> assign('pageHtml', $pageHtml);
		}
		$this -> assign('page', $page);
		// 类型
		$typeArr = array('1'=>'词条详情','2'=>'问答详情','3'=>'文章详情','4'=>'H5页面');
		$this->assign('typeArr',$typeArr);
		$this -> display('appDeploy');
	} 

	/**
	 * 添加或编辑页面
	 */
	public function editAppDeploy(){
		// 获取banner的id
		$bid = $this->_get('bid');
		$pushModel = D('PublishArticle');
		// 编辑banner
		if ($bid) {
			$banDetail = $pushModel->getBanDetail($bid);
			$tip = '编辑';
			$this->assign('banDetail',$banDetail);
			// echo "<pre>";print_r($banDetail);exit();
		}else{ // 新增banner
			
			$tip = '新增';
		}
		$this->assign('tip',$tip);
		// 类型数组
		$typeArr = array('1'=>'词条详情','2'=>'问答详情','3'=>'文章详情','4'=>'H5页面');
		$this->assign('typeArr',$typeArr);
		$this->display('editAppDeploy');
	}

	/**
	 * 储存banner
	*/
	public function saveAppDeploy(){
		// 获取banner的id
		$param = $this->_post();
		
		$pushModel = D('PublishArticle');
		// 判断title
		$data['title'] = $param['title'] ? $param['title'] : '';;
		
		// 判断图片是否存在
		if (!$param['pic_path']) {
			alert('请上传banner图片！');
		}
		$data['img1'] = $param['pic_path'];
		
		// 判断图片是否存在
		if (!$param['linkurl']) {
			alert('请输入banner值！');
		}
		$data['url'] = $param['linkurl'];
		
		$data['input1'] 	= $param['type'] ? $param['type'] : '';
		$data['textarea1'] 	= $param['content'] ? $param['content'] : '';
		$data['position'] 	= $param['position'] ? $param['position'] : '';
		// 编辑banner
		if ($param['bid']) {
			// 查看该banner的详情信息
			$bannerInfo = $pushModel->getBanInfo($param['bid'],'title,img1,url,input1,textarea1,position');
			$res = $pushModel->saveBanData($data,$param['bid']);
			
			if ($res) {
				// 后台操作日志
				// 判断改变的字段
				$arr = getChangeCloum($bannerInfo,$data);
				// echo "<pre>";print_r($bannerInfo);print_r($data);print_r($arr);exit;
				foreach ($arr as $key=>$val) {
        			$this->recordOperations(3,36,$param['bid'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
        		}
				showmsg('编辑成功！','/iadmin.php/Push/appDeploy');
			}else{
				alert('编辑失败！');
			}
		}else{ // 新增banner
			$data['publish_id'] = '50002';
			$res = $pushModel->addBanData($data);
			
			if ($res) {
				// 后台操作日志
				$this->recordOperations(1,36,$res);
				showmsg('添加成功！','/iadmin.php/Push/appDeploy');
			}else{
				alert('添加失败！');
			}
		}
		
	}

	/**
	 * 删除Appbanner
	 */
	public function ajaxDelAppDeploy(){
		// 词条Model实例化
		$publishModel = D('PublishArticle');
		// 英文逗号串接的词条id
		$ids = $this->_get('ajaxDelAppDeploy');
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
				$this->recordOperations(2,36,$val);

				// 删除banner
				$res = $publishModel->delAppDeploy($val);
			}
		}
		// 返回操作
		if(empty($act)){
			$this->redirect('/iadmin.php/Push/appDeploy?page='.$page);//echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/****************************** APP配置end ********************************/
} 

?>
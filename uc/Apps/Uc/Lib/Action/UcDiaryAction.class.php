 <?php
/**
 * UcDiary Action类
 */
class UcDiaryAction extends BaseAction {
	/**
	 * 我的宠物日志列表
	 * TA人的宠物日志列表
	 */
	public function diaryList() {
		$user = $this->_user;	//当前登录用户
		if(empty($_GET['uid'])){
			$this->checkLogin();
			$_GET['uid'] = $user['uid'];
		}
		$uid = $_GET['uid'];
		$orderList = array(
			'cretime' => '发表时间',
			'comments' => '评论时间',
			'views' => '浏览数',
		);
		$ymonth = (isset($_GET['ymonth']) && $_GET['ymonth']!=0) ? $_GET['ymonth'] : "0";
		if(empty($_GET['ymonth'])){
			$_GET['ymonth'] = 0;
		}
		
		if($ymonth==0){
			$ymonth = '';
		}
		$display = isset($_GET['display']) ? $_GET['display'] : "title";//显示方式：title/summary
		$orderby = isset($_GET['orderby']) ? $_GET['orderby'] : "cretime";//排序条件：默认发表时间
		$switch = isset($_GET['switch']) ? $_GET['switch'] : 0;//显示模式，0为摘要，1为列表
		$diaryModel = D("UcDiary");
		//当前资源对应用户
		$resourceUser = null;
		if(!$uid  || $user['uid'] == $uid) {
			$obj = "me";
			$uid = $user['uid'];
			$diaryYMonthList = $diaryModel->getUserDiaryYearMonth($uid);
			$this->assign("diaryYMonthList", $diaryYMonthList);
			$resourceUser = $this->_user;
		} else {
			$obj = "other";

            //取得用户信息
            $huser = $this->getUserInfo($uid);
			$resourceUser = $huser;
			$this->assign("huser", $huser);
		}
		$this->assign("ymonth", $_GET['ymonth']);
		
		if($ymonth) {
			$this->assign("year", substr($ymonth, 0, 4));
			$this->assign('ymonth',$ymonth);
		}
		$this->assign("switch", $switch);
		$this->assign("orderby", $orderby);
		$this->assign("orderList",$orderList);
		$this->assign("uid", $uid);
        $this->assign("obj", $obj);

		//热门宠物日志
		$hotDiaryList = $diaryModel->getHotDiaryList();
		$this->assign("hotDiaryList", $hotDiaryList);
		
		//我的日志分类列表
		$diaryTypeList = $diaryModel->getUserDiaryTypeList($uid);
		$this->assign("diaryTypeList", $diaryTypeList);
		$totalNum = 0;
		foreach($diaryTypeList as $key=>$val){
			$diaryTotalNum += $val['num'];
		}
		$this->assign('diaryTotalNum',$diaryTotalNum);
		$param['uid'] = $uid;
		$typeid = $this->_get('typeid');
		
		if(isset($typeid)){
			
			$param['type_id'] = intval($typeid);//日志分类
			$diaryType = $diaryModel->getDiaryTypeInfo($param['type_id']);
			if(empty($diaryType) && $typeid!=0){
				$url = C('I_DIR').'/diary/u/'.$param['uid'].'/t_0/';
				echo "<script>location.href='".$url."';</script>";
				exit;
			}
			$this->assign("typeid", $param['type_id']);
		}
		
		if(!empty($ymonth)){
			//$param['ymonth'] = date('Y').$ymonth;//******	
			$param['ymonth'] = $ymonth;
		}
		$param['order_by'] = $orderby; //排序条件
		$param['page'] = isset($_GET['p']) ? $_GET['p'] : 1;
		$param['page_num'] = 10;
		$diaryList = $diaryModel->getDiaryList($param);

		//判断当前页面是否存在数据不存在跳转到前一页
		if(empty($diaryList)){
			if($param['page']>=2){
				//判断当前
				$refer = $_SERVER['REQUEST_URI'];
				$arr = explode('/',$refer);
				$pcount = ceil($diaryModel->total/$param['page_num']);
				$arr[count($arr)-1] = $pcount;
				$refer = implode('/',$arr);
				echo "<script>location.href='http://".$_SERVER['HTTP_HOST'].$refer."';</script>";
				exit;
			}
		}
		$this->assign("diaryList", $diaryList);
		$this->assign("nowpage", $param['page'] );

		//分页信息
		import("ORG.Page");
		//$Page = new Page($diaryModel->total, $param['page_num']);
		$pageParams = '';
		if(isset($_GET['uid'])){
			$pageParams .= 'u:'.$_GET['uid'].',';
		}
		if(isset($_GET['typeid'])){
			$pageParams .= 't:'.$_GET['typeid'].',';
		}
		if(isset($_GET['orderby'])){
			$pageParams .= 'o:'.$_GET['orderby'].',';
		}
		if(isset($_GET['switch'])){
			$pageParams .= 'w:'.$_GET['switch'].',';
		}
		if(substr($pageParams,-1)==','){
			$pageParams = substr($pageParams,0,-1);
		}
		$Page = new Page($diaryModel->total, $param['page_num'],"UcDiary,diaryList",$pageParams);
		$this->assign('page', $Page->show());
		$this->assign('total',$diaryModel->total);

		//页面title,description,keywords
		//title
		//keywords
		if(isset($typeid)){
			$htmlHeaderInfo = html_header_info('UcDiary','diaryList',$resourceUser,array('diaryType'=>$diaryType,'page'=>$param['page']));
		}else{
			$htmlHeaderInfo = html_header_info('UcDiary','diaryList',$resourceUser,array('page'=>$param['page']));
		}


		$this->assign('htmlHeaderInfo',$htmlHeaderInfo);
		if($obj == "me") {
			$this->assign("location", "myDiaryList"); 
			$this->display("diaryList");
		} else {
			$this->assign("location", "otherDiaryList");
			$this->display("otherDiaryList");
		}
	}
/**
 * 新增用户日志分类
 */
public function ajaxAddDiaryType() {
	//日志分类名	
	$param['name'] = $this->_get('name');
	if(mb_strlen($param['name'],'utf-8')>8){
		$this->ajaxReturn(array('status'=>0,'msg'=>'分类名字数不可以超过8个字'),'JSON');
		exit;
	}
	$user = $this->_user;	//当前登录用户
	$param['uid'] = $user['uid'];
	$diaryModel = D("UcDiary");
	$diaries = $diaryModel->getDiaryTypes($param['uid'],$param['name']);
	if(count($diaries)>0){
		$this->ajaxReturn(array('status'=>0,'msg'=>'分类已存在'),'JSON');
		exit;
	}
	$result = $diaryModel->addDiaryTypeInfo($param);
	if($result) {
		$this->ajaxReturn(array('status'=>1,'msg'=>'新增成功','content'=>array('id'=>$result,'name'=>$param['name'])),'JSON');
		exit;
	} else {
		$this->ajaxReturn(array('status'=>0,'msg'=>'新增失败'),'JSON');
		exit;
	}
}

/**
 * 编辑用户日志分类
 */
public function ajaxEditDiaryType() {
	$param['id'] = $this->_get('id');
	$param['name'] = $this->_get('name');
	if(mb_strlen($param['name'],'utf-8')>8){
		$this->ajaxReturn(array('status'=>0,'msg'=>'分类名字数不可以超过8个字'),'JSON');
		exit;
	}
	$result = D("UcDiary")->updateDiaryTypeInfo($param);
	if($result) {
		$this->ajaxReturn(array('status'=>1,'msg'=>'更新成功','content'=>$param['name']),'json');
	} else {
		$this->ajaxReturn(array('status'=>0,'msg'=>'更新失败'),'json');
	}
	exit;
}

	/**
	 * 删除用户日志分类
	 */
	public function ajaxDelDiaryType() {
		//日志分类id
		$id = $this->_get('id');
		//删除日志分类信息
		$result = D("UcDiary")->deleteDiaryTypeInfo($id);
		if($result) {
			$this->ajaxReturn(array('status'=>1,'msg'=>'删除成功'),'json');
		} else {
			$this->ajaxReturn(array('status'=>0,'msg'=>'删除失败'),'json');
		}
		exit;
	}

	

    /**
     * 写日志
     */
    public function diaryAdd() {
		$this->checkLogin();
        //操作标志
        $this->assign("act", "add");
        //当前登录用户
        $user = $this->_user;
        $this->assign("uid", $user['uid']);

		$diaryModel = D("UcDiary");
        //日志分类
        $typeList = $diaryModel->getUserDiaryTypeList($user['uid'], 0);
        $this->assign("typeList", $typeList);
        //相册
        $albumList = $diaryModel->getUserAlbumNameList($user['uid']);
        $this->assign("albumList", $albumList);
		// 加入session值防CSRF攻击
		$key = md5(uniqid(rand(),true));
		$_SESSION[$key] = 1;
		$this->assign('token',$key);
		$this->assign("location", "myDiaryList"); 
        $this->display("diaryInfo");
    }

    /**
     * 编辑日志
     */
    public function diaryEdit() {
		$this->checkLogin();
        //日志id
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $this->assign("id", $id);
        $this->assign("act", "edit");

        //当前登录用户
        $user = $this->_user;
        $this->assign("uid", $user['uid']);
		$diaryModel = D("UcDiary");
        //日志分类列表
        $typeList = $diaryModel->getUserDiaryTypeList($user['uid'], 1, 0);
	
        $this->assign("typeList", $typeList);
        //相册名列表
        $albumList = $diaryModel->getUserAlbumNameList($user['uid']);
		
        $this->assign("albumList", $albumList);
        //日志信息
        $param['id'] = $id;
        $param['nocomment'] = 1;
        $diaryInfo = $diaryModel->getDiaryInfo($param);
		//获取专辑id
		$photos = D('UcPhoto')->where(array('object_id'=>$id,'object_type'=>1))->select();
		$this->assign('album_id',$photos[0]['album_id']);
        $this->assign("diary", $diaryInfo);
        // 加入session值防CSRF攻击
		$key = md5(uniqid(rand(),true));
		$_SESSION[$key] = 1;
		$this->assign('token',$key);
        $this->display("diaryInfo");
		$this->assign("obj", "me");
    }

    /**
     * 日志查看
     */
    public function diary() {
		if(!empty($_GET['rid'])){	//直接定位到回复用户
			$comment = M('uc_diary_comment')->where('id='.$_GET['rid'])->find();
			if(!empty($comment)){
				$replyUser = D('UcUser')->getUserInfoByUid($comment['uid']);
				if(!empty($replyUser)){
					$this->assign('replyUser',$replyUser);
					$this->assign('comment',$comment);
				}
			}
		}
		
        //URL参数：日志id
        $id = $_GET['id'];
		//TODO id为空处理

        //操作标志
        $this->assign("act", "view");
        $this->assign("id", $id);
       
        $diaryModel = D("UcDiary");
        
        $param['id'] = $id;
        $param['nocomment'] = 0;
        $param['page'] = isset($_GET['p']) ? $_GET['p'] : 1;
        $param['page_num'] = 10;
        $diaryInfo = $diaryModel->getDiaryInfo($param);
		
		if($diaryInfo['status']==-1){	//日志已被删除，跳转至404页面
			header("HTTP/1.0 404 Not Found");
			$this->assign('uid',$diaryInfo['uid']);
			$this->display('Public:404');
			exit;
		}

        //日志发布者
        $uid = $diaryInfo['uid'];

		//热门宠物日志
		$hotDiaryList = D("UcDiary")->getHotDiaryList();
		$this->assign("hotDiaryList", $hotDiaryList);
		
		//日志分类列表
		$diaryTypeList = $diaryModel->getUserDiaryTypeList($uid);
		$this->assign("diaryTypeList", $diaryTypeList);

        //当前登录用户
        $user = $this->_user;
		$this->assign('user',$user);
		//当前资源对应用户
		$resourceUser = null;
        if(isset($user) && ($user['uid'] == $uid)) {
            $obj = "me";
			$resourceUser = $this->_user;
        } else {
            $obj = "other";
			$huser = $this->getUserInfo($uid);
			$resourceUser = $huser;
			$this->assign("huser", $huser);
        }
		//分页信息
		import("ORG.Page");
		$Page = new Page($diaryModel->total, $param['page_num'],'UcDiary,diary',$param['id']);
		$this->assign('page', $Page->show());
		$this->assign('total',$diaryModel->total);

        $this->assign("diary", $diaryInfo);
		$this->assign("obj", $obj);
		
		$pets = D('UcPets')->getUserPets($uid);
		$petTitle = '';
		foreach($pets as $key=>$val){
			if($val['pettype_title']!=''){
				if($petTitle==''){
					$petTitle .= $val['pettype_title'];
				}else{
					$petTitle .= '和'.$val['pettype_title'];
				}
			}
		}
		$htmlHeaderInfo = html_header_info('UcDiary','diary',$resourceUser,array('diaryTitle'=>$diaryInfo['title'],'petTypes'=>$petTitle));
		$this->assign('htmlHeaderInfo',$htmlHeaderInfo);
		// 加入session值防CSRF攻击
		$key = md5(uniqid(rand(),true));
		$_SESSION[$key] = 1;
		$this->assign('token',$key);
		if($obj == "me") {
			$this->assign("location", "myDiaryList");
			$this->display("diary");
		} else {
			//更新日志浏览数
			$diaryModel->updateDiaryViews($id);
			$moreDiaries = D("UcDiary")->getMoreDiaryList($uid,$id);
			$this->assign('moreDiaries',$moreDiaries);
			$this->assign("location", "otherDiaryList");
			$this->display("otherDiary");
		}
    }

	/**
	 * 保存日志
	 */
	public function diarySave() {
		// 判断请求地址 域名为boqii.com 并且 匹配session中的token
		$checkSafe = checkSafeForSns($_POST['token']);
		if(!$checkSafe){
			$this->ajaxReturn(array('status'=>0,'msg'=>'非法操作！'), 'JSON');
		}
		load("@.manual_common");
		//操作标志
		$act = $this->_post('act');
		$param['uid'] = $this->_post('uid');
		
		// 禁止发言
		$userGroup = $this->checkUserGroup();
		if(!$userGroup){
			$this->ajaxReturn(array('status'=>0,'msg'=>'您可能涉及违规内容发布，暂时无法进行该操作，如有问题，请联系论坛管理员。'), 'JSON');
		}
		
		$param['title'] = $this->_post('title');
		if(mb_strlen($param['title'],'utf-8')>40){
			$this->ajaxReturn(array('status'=>0,'msg'=>'标题字数不可以超过40个字'),'JSON');
			exit;
		}
		// 过滤百度编辑器内容 安全
		$_POST['content'] = $_POST['editorboqii_message'];
		unset($_POST['editorboqii_message']);
		$param['content'] = urldecode($_POST['content']);
		
		//判断是否有敏感词
		$sensitiveIsOrNot = D('SensitiveWord')->isOrNotSensitiveWord(strip_tags($param['content']));
		if($sensitiveIsOrNot){
			$this->ajaxReturn(array('status'=>0,'msg'=>'您发布的内容包含违规信息，请修改后再发布'),'JSON');
			exit;
		}
		//$param['album_id'] = $_POST['album_id'];
		$param['type_id'] = $_POST['type_id'];
		$album_id = $_POST['album_id'];
		
		$ucAlbum = D('UcAlbum');
		$param['album_id']=$album_id;
		$ucPhoto = D("UcPhoto");
		$user = $this->_user;
		if($act == "edit" && !empty($_POST['id'])){
			$diary = D("UcDiary")->where('id='.$_POST['id'])->find();
			if(!empty($diary) && $user['uid']==$diary['uid']){
				$param['id'] = $this->_post('id');
				$photos =$ucPhoto->where(array('object_id'=>$param['id'],'object_type'=>1))->select();
				//获得匹配的图片信息
				$srcarr = preg_match_diary($param['content'],$photos);
				$src=$srcarr['new'];    //编辑时候需要新加入相册的图片
				$srcdel = $srcarr['old']; //编辑时候需要删除的图片
				//如果存在去掉的图片 就进行删除相册中的图片
				
				if($srcdel){
					$photo['photo_id']  = array('in',$srcdel);
					$photos = $ucPhoto->where($photo)->field('uid,size')->select();
					foreach($photos as $key=>$val){
						$ucAlbum->changeAlbumCapacity(array('uid'=>$val['uid'],'changeNum'=>$val['size']),2);
					}
					$ucPhoto->where($photo)->save(array('status'=>-1));
				}
				//存在新添加的图片进行
				if($src){
					$ids = $this->diaryPhoto($src,$album_id,$user['uid'],$param['id']);
					$param['content'] = edit_diray_preg($param['content'],$ids);			
				}
				$ucPhoto->where(array('object_id'=>$param['id']))->data(array('album_id'=>$album_id))->save();
				$result = D("UcDiary")->updateDiaryInfo($param);
				if($result){
					$this->ajaxReturn(array('status'=>1,'msg'=>'编辑成功','content'=>array('id'=>$_POST['id'])),'JSON');exit;
				}
			}
		}else{

			$id = D("UcDiary")->addDiaryInfo($param);
			//正则过滤获得图片
			$srcarr = preg_match_diary($param['content']);
			$src=$srcarr['new'];
			if($src){
				$ids = $this->diaryPhoto($src,$album_id,$user['uid'],$id);
				$content = edit_diray_preg($param['content'],$ids);
				D("UcDiary")->where(array('id'=>$id))->save(array('content'=>$content));
			}

			//添加动态
			$dynParams = array(
				'uid'=>$user['uid'],
				'type'=>1,
				'operatetype'=>1,
				'ouid'=>$user['uid'],
				'ousername'=>$user['username'],
				'oid'=>$id,
				'otitle'=>$param['title']
			);
			
			$result = D("UcIndex")->addDynamic($dynParams);
			if($id){
				$this->ajaxReturn(array('status'=>1,'msg'=>'发布成功','content'=>array('id'=>$id)),'JSON');exit;
			}
		}
		$this->ajaxReturn(array('status'=>0,'msg'=>'操作失败'),'JSON');exit;
	}

	/*
	*日志关联图片
	*/
	public function diaryPhoto($src,$album_id,$uid,$diary_id){
		$ucAlbum = D('UcAlbum');
		$ucPhoto = D("UcPhoto");
		import('Common.manual_common', APP_PATH, '.php');
		foreach($src as $key=>$val){
			$size = myGetImageSize($val['src']);
			$pic_path = imageUrlReplace(str_replace(C('IMG_DIR').'/','',$val['src']),1);
			$img_information=getimagesize($val['src']);
			$data['album_id']=$album_id;
			$data['uid']=$uid;
			$data['photo_path']=$pic_path;
			$data['photo_name']=$val['title'];
			$data['photo_desc']='请输入照片描述';
			$data['cretime']=time();
			$data['updatetime']=time();
			$data['imagewidth']=$img_information[0];
			$data['imagehigth']=$img_information[1];
			$data['size']=$size['size'];
			$data['object_type']=1;
			$data['object_id']=$diary_id;
			$ucAlbum->changeAlbumCapacity(array('uid'=>$uid,'changeNum'=>$size['size']),1);
			$photo_id=$ucPhoto->add($data);
			$ids[$key]['photo_path']=imageUrlReplace($pic_path,2);
			$ids[$key]['photo_id']=$photo_id;
		}
		return $ids;
	}
		
	/**
	 * 修改日志的分类
	 */
	public function ajaxUpdateDiaryType() {
		$param['ids'] = $this->_get('ids');
		$param['type_id'] = $this->_get('type_id');
		if(!empty($_GET['name'])){
			$user = $this->_user;
			$param['uid'] = $user['uid'];
			$param['name'] = $this->_get('name');
			$diaries = D("UcDiary")->getDiaryTypes($param['uid'],$param['name']);
			if(count($diaries)>0){
				echo json_encode(array('status'=>0,'msg'=>'分类已存在'));exit;
			}
			$result = D("UcDiary")->addDiaryTypeInfo($param);
			$param['type_id'] = $result;
		}
		//编辑日志的分类
		$res = D("UcDiary")->updateDiaryType($param);
		if($res) {
			$this->ajaxReturn(array('status'=>1,'msg'=>'操作成功'),'JSON');
		} else {
			$this->ajaxReturn(array('status'=>0,'msg'=>'操作失败'),'JSON');
		}
		exit;
	}

	/**
	 * 批量删除日志
	 */
	public function ajaxDeleteDiaryInfo() {
		$uid = $this->_user['uid'];	//当前登录用户
	
		$ids = $this->_get('ids');
		//删除日志
		$res = D("UcDiary")->deleteDiaryList($ids,$uid);
		
		//if(!$res) {
		$this->ajaxReturn(array('status'=>1,'msg'=>'删除成功'),'JSON');
		//} else {
		//	$this->ajaxReturn(array('status'=>0,'msg'=>'删除失败'),'JSON');
		//}
		exit;
	}

	/**
	 * 置顶日志
	 */
	public function ajaxTopDiary() {
		$ids = $_POST['ids'];

		$res = D("UcDiary")->topDiary($ids);
		if($res) {
			echo "error:置顶失败!";
		} else {
			echo "success:置顶成功!";
		}
	}

	//日志评论
	public function ajaxCommentDiary(){
		// 判断请求地址 域名为boqii.com 并且 匹配session中的token
		$checkSafe = checkSafeForSns($_POST['token'],1);
		if(!$checkSafe){
			$this->ajaxReturn(array('status'=>'safe'),'JSON');
		}
		$diaryModel = D('UcDiary');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['diaryid'] = $this->_post('diaryid');
		$param['content'] = stripslashes($_POST['message']);
		
		if($param['diaryid']){
			//判断登陆
			if($param['uid']){
				$diaryData = $diaryModel->getDiaryInfo(array('id'=>$param['diaryid']));
				if(empty($diaryData) || $diaryData['status']==-1){
					$data['status'] = 'delete';//日记不存在或被删除
                    $this->ajaxReturn($data, 'JSON');
				}
				// 禁止发言
				$userGroup = $this->checkUserGroup();
				if(!$userGroup){
					$data['status'] = 'ban';
                    $this->ajaxReturn($data, 'JSON');
				}
				//黑名单判断
                $statusNum = D('UcRelation')->getSearchStatus($param['uid'],$diaryData['uid']);//黑名单判断
                if($statusNum == 4){
                    $data['status'] = 'black'; //我的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
                if($statusNum == 5){
                    $data['status'] = 'tBlack';//他的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
				//判断是否有敏感词
				$sensitiveIsOrNot = D('SensitiveWord')->isOrNotSensitiveWord($param['content']);
				if($sensitiveIsOrNot){
					$data['status'] = 'sensitive';
					$this->ajaxReturn($data,'JSON');
					exit;
				}
				//10分钟内，同一个用户不可以评论同样的内容
				$lastComment = $diaryModel->getLastDiaryComment($userinfo['uid'],$param['content'],$param['diaryid']);
				if(!empty($lastComment) && (time()-$lastComment['dateline'])<600 && $lastComment['content']==$param['content']){
					$data['status'] = 'samecontent';
					$this->ajaxReturn($data,'JSON');
					exit;
				}
				$param['ip'] =  get_client_ip();
				$r = $diaryModel->commentDiary($param);
				if(!$r){	//保存失败
					$data['status'] = 'nopublish';
					$this->ajaxReturn($data,'JSON');
				}
				$ouser = D('UcUser')->getUserInfoByUid($diaryData['uid']);
				//添加动态
				$dynParams = array(
					'uid'=>$userinfo['uid'],
					'type'=>1,
					'operatetype'=>2,
					'ouid'=>$diaryData['uid'],
					'ousername'=>$ouser['nickname'],
					'mid'=>$param['diaryid'],
					'oid'=>$r,
					'otitle'=>'',
				);
				D("UcIndex")->addDynamic($dynParams);

				$data['status'] = 'ok';
				$data['r'] = $r;
				$data['uid'] = $userinfo['uid'];
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		$this->ajaxReturn($data,'JSON');
	}
	
	//日志评论回复
	public function ajaxReplyDiaryComment(){
		// 判断请求地址 域名为boqii.com 并且 匹配session中的token
		$checkSafe = checkSafeForSns($_POST['token'],1);
		if(!$checkSafe){
			$this->ajaxReturn(array('status'=>'safe'),'JSON');
		}
		$diaryModel = D('UcDiary');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['diaryid'] = $this->_post('diaryid');
		$param['commentid'] = $this->_post('commentid');
		$param['content'] = stripslashes($_POST['message']);
		if($param['diaryid'] and $param['commentid']){
			//判断登陆
			if($param['uid']){
				$diaryData = $diaryModel->getDiaryInfo(array('id'=>$param['diaryid']));
				if(empty($diaryData) || $diaryData['status']==-1){
					$data['status'] = 'delete';//日记不存在或被删除
                    $this->ajaxReturn($data, 'JSON');
				}
				$comment = $diaryModel->getDiaryCommentById($param['commentid']);
				if(empty($comment) || $comment['status']==-1){
					$data['status'] = 'commentDelete';//该回复对应的评论被删除
                    $this->ajaxReturn($data, 'JSON');
				}
				// 禁止发言
				$userGroup = $this->checkUserGroup();
				if(!$userGroup){
					$data['status'] = 'ban';
                    $this->ajaxReturn($data, 'JSON');
				}
				//黑名单判断
                $statusNum = D('UcRelation')->getSearchStatus($param['uid'],$diaryData['uid']);//黑名单判断
                if($statusNum == 4){
                    $data['status'] = 'black'; //我的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
                if($statusNum == 5){
                    $data['status'] = 'tBlack';//他的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
				//判断是否有敏感词
				$sensitiveIsOrNot = D('SensitiveWord')->isOrNotSensitiveWord($param['content']);
				if($sensitiveIsOrNot){
					$data['status'] = 'sensitive';
					$this->ajaxReturn($data,'JSON');
					exit;
				}
				//10分钟内，同一个用户不可以评论同样的内容
				$lastComment = $diaryModel->getLastDiaryComment($userinfo['uid'],$param['content'],$param['diaryid']);
				if(!empty($lastComment) && (time()-$lastComment['dateline'])<600 && $lastComment['content']==$param['content']){
					$data['status'] = 'samecontent';
					$this->ajaxReturn($data,'JSON');
					exit;
				}
				$param['ip'] =  get_client_ip();
				$r = $diaryModel->commentDiary($param);
				if(!$r){	//保存失败
					$data['status'] = 'nopublish';
					$this->ajaxReturn($data,'JSON');
				}
				$ouser = D('UcUser')->getUserInfoByUid($comment['uid']);
				//添加动态
				$dynParams = array(
					'uid'=>$userinfo['uid'],
					'type'=>5,
					'operatetype'=>1,
					'ouid'=>$comment['uid'],
					'ousername'=>$ouser['nickname'],
					'mid'=>$param['commentid'],
					'oid'=>$r,
					'otitle'=>''
				);
				D("UcIndex")->addDynamic($dynParams);

				$data['status'] = 'ok';
				$data['r'] = $r;
				$data['uid'] = $userinfo['uid'];
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		$this->ajaxReturn($data,'JSON');
	}
		
		/*
		*判断是否禁言
		*/
		public function checkGroup(){

			$result = $this->checkUserGroup();
			if($result){
				echo 1;
			}else{
				echo 0;
			}
			exit;
		}
	/**
	 * 删除日志评论
	 */
	public function ajaxDeleteDiaryComment(){
		$diary = D('UcDiary');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['cid'] = $_GET['cid'];
		if($param['cid']){
			if($param['uid']){
				$r = $diary->deleteDiaryComment($param['cid']);	
				if($r){
					$data['status'] = 'ok';
				}else{
					$data['status'] = 'false';
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		$this->ajaxReturn($data,'JSON');
	}
}



function unicode_decode($name){
 // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
 $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
 preg_match_all($pattern, $name, $matches);
 if (!empty($matches)){
  $name = '';
  for ($j = 0; $j < count($matches[0]); $j++){
   $str = $matches[0][$j];
   if (strpos($str, '\\u') === 0){
    $code = base_convert(substr($str, 2, 2), 16, 10);
    $code2 = base_convert(substr($str, 4), 16, 10);
    $c = chr($code).chr($code2);
    $c = iconv('UCS-2', 'UTF-8', $c);
    $name .= $c;
   }else{
    $name .= $str;
   }
  }
 }
 return $name;
}
?>
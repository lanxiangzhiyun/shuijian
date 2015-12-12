<?php
class UcWeiboAction extends BaseAction {

	public function __construct() {
        parent::__construct();
    }
	
	//微博
	public function weibo(){
		$weibo = D('UcWeibo');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$userinfo = $this->_user;
		if($userinfo) {
            //我的微博
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA的微博
            else {
                $obj = "other";
            }
        } 
        //TA的微博
        else {
            $obj = 'other';
        }
		if($obj == "me"){
			$param['uid'] = $userinfo['uid'];
			if(!$param['uid']) {
				header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
			}

			
			$param['page'] = intval($_GET['p']);
			$param['page_num'] = 20;
			$list = $weibo->getMyWeibo($param);
			//print_r($list);
			import("ORG.Page");
			$Page = new Page($weibo->total, $param['page_num'],"UcWeibo,weibo",$param['uid']);
			$this->assign('page', $Page->show());
			$this->assign('p',intval($_GET['p']));
			$this->assign('list',$list);
			
			//热门话题
			$hotThreads = D("UcIndex")->getIndexHotThreads();
			$this->assign("hotThreads", $hotThreads);
			//热门宠物日志
			$hotDiaryList = D("UcDiary")->getHotDiaryList();
			$this->assign("hotDiaryList", $hotDiaryList);
			//公告
			$announce = D("UcIndex")->getAnnouncements(1);
			$this->assign('announce',$announce);
			//广告图片
			$advModel = D('Advertisement');
			$rightad = $advModel->getAdvertisement('10008');
			$this->assign("rightad", $rightad);

			$this->assign("obj", "me");
				
			$this->assign("location", "myWeibo");
			// 加入session值防CSRF攻击
			$key = md5(uniqid(rand(),true));
			$_SESSION[$key] = 1;
			$this->assign('token',$key);
			$this->display('myWeibo');
		}else{
			$param['yuid'] = $userinfo['uid'];
			$param['uid'] = $uid;
			if(!$param['uid']) {
				header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
			}
			
			$param['page'] = intval($_GET['p']);
			$param['page_num'] = 20;
			$list = $weibo->getOtherWeibo($param);
			//print_r($list);
			import("ORG.Page");
			$Page = new Page($weibo->total, $param['page_num'],"UcWeibo,weibo",$param['uid']);
			$this->assign('page', $Page->show());
			$this->assign('p',intval($_GET['p']));
			$this->assign('list',$list);
			
			if($param['yuid'] != $param['uid']){
				$user = $this->getUserInfo($param['uid']);			
				$this->assign("huser", $user);
				$this->assign("obj", "other");
			}
	
			//热门话题
			$hotThreads = D("UcIndex")->getIndexHotThreads();
			$this->assign("hotThreads", $hotThreads);
			//热门宠物日志
			$hotDiaryList = D("UcDiary")->getHotDiaryList();
			$this->assign("hotDiaryList", $hotDiaryList);
	
			//广告图片
			$advModel = D('Advertisement');
			$midad = $advModel->getAdvertisement('10009');
			$this->assign("midad", $midad);
			$rightad = $advModel->getAdvertisement('10008');
			$this->assign("rightad", $rightad);

			//公告
			$announce = D("UcIndex")->getAnnouncements(10);
			$this->assign('announce',$announce);
			if($_GET['uid']) $this->assign("oruid", $param['uid']);
			// 加入session值防CSRF攻击
			$key = md5(uniqid(rand(),true));
			$_SESSION[$key] = 1;
			$this->assign('token',$key);
			$this->assign("location", "otherWeibo");
			$this->display('otherWeibo');
		}
	}
	
	//Ta的微博
	public function otherWeibo(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;
		$param['yuid'] = $userinfo['uid'];
		$param['uid'] = $_GET['uid'];
		if(!$param['uid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		 
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$list = $weibo->getOtherWeibo($param);
		//print_r($list);
		import("ORG.Page");
		$Page = new Page($weibo->total, $param['page_num']);
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('list',$list);
		
		if($param['yuid'] != $param['uid']){
			$user = $this->getUserInfo($param['uid']);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
		}

		//热门话题
		$hotThreads = D("UcIndex")->getIndexHotThreads();
		$this->assign("hotThreads", $hotThreads);
		//热门宠物日志
		$hotDiaryList = D("UcDiary")->getHotDiaryList();
		$this->assign("hotDiaryList", $hotDiaryList);

		//公告
		$announce = D("UcIndex")->getAnnouncements(10);
		$this->assign('announce',$announce);
		if($_GET['uid']) $this->assign("oruid", $param['uid']);
		// 加入session值防CSRF攻击
		$key = md5(uniqid(rand(),true));
		$_SESSION[$key] = 1;
		$this->assign('token',$key);
		$this->assign("location", "otherWeibo");
		$this->display('otherWeibo');
	}
	
	//热门微博
	public function hotWeibo(){
		$weibo = D('UcWeibo');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$userinfo = $this->_user;
		if($userinfo) {
            //我的热门微博
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA的热门微博
            else {
                $obj = "other";
            }
        } 
        //TA的热门微博
        else {
            $obj = 'other';
        }
		
		$param['uid'] = $userinfo['uid'];
		if($obj == "other") $param['oruid'] = $uid;
		
		if(!$param['uid'] and !$uid) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$list = $weibo->getHotWeibo($param);
		//print_r($list);
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($weibo->total, $param['page_num'],"UcWeibo,hotWeibo",$param['oruid']);
		}else{
			$Page = new Page($weibo->total, $param['page_num'],"UcWeibo,hotWeibo",$param['uid']);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('list',$list);
		$this->assign('userinfo',$userinfo);
		
		//热门话题
		$hotThreads = D("UcIndex")->getIndexHotThreads();
		$this->assign("hotThreads", $hotThreads);
		//热门宠物日志
		$hotDiaryList = D("UcDiary")->getHotDiaryList();
		$this->assign("hotDiaryList", $hotDiaryList);
	
		//广告图片
		$advModel = D('Advertisement');
		$midad = $advModel->getAdvertisement('10009');
		$this->assign("midad", $midad);
		$rightad = $advModel->getAdvertisement('10008');
		$this->assign("rightad", $rightad);

		if($obj == "other"){
			//公告
			$announce = D("UcIndex")->getAnnouncements(10);
		}else{
			//公告
			$announce = D("UcIndex")->getAnnouncements(1);
		}
		$this->assign('announce',$announce);
		$this->assign("uid", $param['uid']);
		if($obj == "other") $this->assign("oruid", $param['oruid']);
		if($obj == "other"){
			$user = $this->getUserInfo($uid);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherWeibo");
		}else{
			$this->assign("obj", "me");
			$this->assign("location", "myWeibo");
		}
		// 加入session值防CSRF攻击
		$key = md5(uniqid(rand(),true));
		$_SESSION[$key] = 1;
		$this->assign('token',$key);
		$this->display('hotWeibo');
	}
	
	//微博转播
	public function relay(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['wid'] = $_GET['wid'];
		if(!$this->checkUserGroup()){
			$data['status'] = 'forbidden';
			$this->ajaxReturn($data,'JSON');
		}
		if($param['wid']){
			//判断登陆
			if($param['uid']){
				$r = $weibo->relayWeibo($param);
				if($r == 0){
					$data['status'] = 'delete';
				}elseif($r == -1){
					$data['status'] = 'black';
				}elseif($r == -2){
					$data['status'] = 'delblack';
				}else{
					$data['status'] = 'ok';
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		
		$this->ajaxReturn($data,'JSON');
	}
	
	//微博评论
	public function reply(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;
		// 判断请求地址 域名为boqii.com 并且 匹配session中的token
		$checkSafe = checkSafeForSns($_POST['token'],1);
		if(!$checkSafe){
			$this->ajaxReturn(array('status'=>'safe'),'JSON');
		}
		$param['uid'] = $userinfo['uid'];
		$param['wid'] = $_POST['wid'];
		$param['message'] = trim(stripslashes($_POST['message']));
		if(!$this->checkUserGroup()){
			$data['status'] = 'forbidden';
			$this->ajaxReturn($data,'JSON');
		}
		if($param['wid']){
			//判断登陆
			if($param['uid']){
				if($param['message']){
					$result = D('SensitiveWord')->isOrNotSensitiveWord($param['message']);
					if($result){
						$data['status'] = 'existwrods';
					}else{
						$r = $weibo->replyWeibo($param);
						if($r == 0){
							$data['status'] = 'nopublish';
						}elseif($r == -1){
							$data['status'] = 'black';
						}elseif($r == -2){
							$data['status'] = 'delblack';
						}else{
							$data['status'] = 'ok';
							$data['r'] = $r;
							$data['uid'] = $userinfo['uid'];
						}
					}
				}else{
					$data['status'] = 'empty';
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		
		$this->ajaxReturn($data,'JSON');
	}
	
	//微博评论回复
	public function replyComment(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;
		// 判断请求地址 域名为boqii.com 并且 匹配session中的token
		$checkSafe = checkSafeForSns($_POST['token'],1);
		if(!$checkSafe){
			$this->ajaxReturn(array('status'=>'safe'),'JSON');
		}
		$param['uid'] = $userinfo['uid'];
		$param['wid'] = $_POST['wid'];
		$param['cid'] = $_POST['cid'];
		$param['message'] = trim(stripslashes($_POST['message']));
		if(!$this->checkUserGroup()){
			$data['status'] = 'forbidden';
			$this->ajaxReturn($data,'JSON');
		}
		if($param['wid'] and $param['cid']){
			//判断登陆
			if($param['uid']){
				if($param['message']){
					$result = D('SensitiveWord')->isOrNotSensitiveWord($param['message']);
					if($result){
						$data['status'] = 'existwrods';
					}else{
						$r = $weibo->replyComment($param);
						if($r == 0){
							$data['status'] = 'nopublish';
						}elseif($r == -1){
							$data['status'] = 'black';
						}elseif($r == -2){
							$data['status'] = 'delblack';
						}else{
							$data['status'] = 'ok';
							$data['r'] = $r;
							$data['uid'] = $userinfo['uid'];
						}
					}
				}else{
					$data['status'] = 'empty';
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'false';
		}
		
		$this->ajaxReturn($data,'JSON');
	}
	
	//微博删除
	public function delWeibo(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['wid'] = $_GET['wid'];
		if($param['wid']){
			if($param['uid']){
				$r = $weibo->delWeibo($param);
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
	
	//评论回复删除
	public function delReply(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['cid'] = $_GET['cid'];
		if($param['cid']){
			if($param['uid']){
				$r = $weibo->delReply($param);	
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
	
	//微博详情
	public function weiboComments(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['wid'] = $_GET['wid'];
		$rid = $_GET['rid'];
		if($rid){
			$ruser = $weibo->getUserinfoByRid($rid);
		}
		if(empty($param['wid'])){
			header("HTTP/1.0 404 Not Found");
			$uid = '';	//uid为访问资源对应用户ID
			$this->assign('uid',$uid);
			$this->display('Public:404');
			exit;
		}
		//微博详情
		$weibodetail = $weibo->getWeiboDetail($param);
		if($weibodetail){
			//print_r($weibodetail);
			//评论分页
			$param['page'] = intval($_GET['p']);
			$param['page_num'] = 10;
			$list = $weibo->getWeiboComments($param);
			//print_r($list);
			import("ORG.Page");
			$Page = new Page($weibo->total, $param['page_num'],"UcWeibo,weiboComments",$param['wid']);
			$this->assign('page', $Page->show());
			$this->assign('p',intval($_GET['p']));
			$this->assign('list',$list);
			$this->assign('userinfo',$userinfo);
			$this->assign('weibodetail',$weibodetail);
			$this->assign('rid',$rid);
			$this->assign('ruser',$ruser);

			//广告图片
			$rightad = D('Advertisement')->getAdvertisement('10008');
			$this->assign("rightad", $rightad);
			//热门话题
			$hotThreads = D("UcIndex")->getIndexHotThreads();
			$this->assign("hotThreads", $hotThreads);
			//热门宠物日志
			$hotDiaryList = D("UcDiary")->getHotDiaryList();
			$this->assign("hotDiaryList", $hotDiaryList);
			
			if($param['uid'] != $weibodetail['uid']){
				$user = $this->getUserInfo($weibodetail['uid']);		
				$this->assign("huser", $user);
				$this->assign("obj", "other");
			}else{
				$this->assign("obj", "me");
			}
			// 加入session值防CSRF攻击
			$key = md5(uniqid(rand(),true));
			$_SESSION[$key] = 1;
			$this->assign('token',$key);
			$this->display('weiboComments');
		}else{
			$detail = $weibo->getUidByWeibo($param['wid']);
			header("HTTP/1.0 404 Not Found");
			$uid = $detail['uid'];	//uid为访问资源对应用户ID
			$this->assign('uid',$uid);
			$this->display('Public:404');
			exit;
		}
	}

    // 文件上传
    public function upload() {
		if (!empty($_FILES)) {
			$userinfo = $this->_user;
			$uid = $userinfo['uid'];
			if($uid){
				import("ORG.UploadFile");
				//导入上传类
				$upload = new UploadFile();
				//设置上传文件大小
				$upload->maxSize = 3292200;
				//设置上传文件类型
				$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
				//设置附件上传目录
				$upload->savePath = 'Data/Upload/Users/'.$uid.'/';
				//设置需要生成缩略图，仅对图像文件有效
				$upload->thumb = true;
				// 设置引用图片类库包路径
				$upload->imageClassPath = 'ORG.Image';
				//设置需要生成缩略图的文件后缀
				$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
				//设置缩略图最大宽度
				$upload->thumbMaxWidth = '400,120';
				//设置缩略图最大高度
				$upload->thumbMaxHeight = '400,120';
				//设置上传文件规则
				$upload->saveRule = uniqid;
				//删除原图
				$upload->thumbRemoveOrigin = false;
				if (!$upload->upload()) {
					//捕获上传异常
					$data['status'] = 'fail';
				} else {
					//取得成功上传的文件信息
					$uploadList = $upload->getUploadFileInfo();
					import("ORG.Image");
					//给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
					Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], 'Public/Images/watermark.png');
					$imgpath = $uploadList[0]['savepath'].$uploadList[0]['savename'];
					$data['status'] = 'ok';
					$data['imgpath'] = C('I_DIR').'/'.$imgpath;
				}
			}else{
				$data['status'] = 'login';
			}
		}else{
			$data['status'] = 'noselect';
		}
		$this->ajaxReturn($data,'JSON');
	}
	
	//发微博
	public function publishWeibo(){
		$weibo = D('UcWeibo');
		$userinfo = $this->_user;

		// 判断请求地址 域名为boqii.com 并且 匹配session中的token
		$checkSafe = checkSafeForSns($_POST['token'],1);
		if(!$checkSafe){
			$this->ajaxReturn(array('status'=>'safe'),'JSON');
		}

		// 判断是否有发言权限
		if(!$this->checkUserGroup()){
			$data['status'] = 'forbidden';
			$this->ajaxReturn($data,'JSON');
		}
		// 用户存在
		if($userinfo['uid']){
			$_POST['uid'] = $userinfo['uid'];
			$_POST['weibo_content'] = stripslashes(trim($_POST['editorboqii_content']));
			if($_POST['weibo_content'] or $_POST['img']){
				$result = D('SensitiveWord')->isOrNotSensitiveWord($_POST['weibo_content']);
				if($result){
					$data['status'] = 'existwrods';
				}else{
					if($_POST['img']) $_POST['weibo_pic'] = $_POST['img'];
					$r = $weibo->addWeibo($_POST);
					if($r){
						$data['status'] = 'ok';
					}else{
						$data['status'] = 'false';
					}
				}
			}else{
				$data['status'] = 'empty';
			}
		}else{
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data,'JSON');
	}
	
}
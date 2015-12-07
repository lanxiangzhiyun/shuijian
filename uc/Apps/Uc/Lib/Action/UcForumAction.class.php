<?php
class UcForumAction extends BaseAction {

	public function __construct() {
        parent::__construct();
    }
	
	//发表的话题
	public function forum(){
		$forum = D('UcForum');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : 0;
		$userinfo = $this->_user;
		if($userinfo) {
            //我发表的话题
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA发表的话题
            else {
//				$ouserinfo = D('UcUser')->getUserInfoDetailByUid($uid);
//				$ouserinfo['oltimes'] = min2time($ouserinfo['oltimes']);
				$apiModel = D('Api');
				$ouserinfo = $apiModel->getUserInfo($uid);
				$ouserinfo['extinfo'] = $apiModel->getUserExtInfo($uid);

                $obj = "other";
            }
        } 
        //TA发表的话题
        else {
            $obj = 'other';
        }
		//print_r($userinfo);

		// 类型 type为1精华
		if($type == 1){
			$param['type'] = 1;
			$typepage = ',1';
			$this->assign('digestType',$type);
		}
		$param['uid'] = $userinfo['uid'];
		if($obj == "other") $param['oruid'] = $uid;
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		
		$param['page'] = $_GET['p'] ? intval($_GET['p']) : 1;
		$param['page_num'] = 8;
		$mythreads = $forum->getMyThread($param);
		
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($forum->total, $param['page_num'],'UcForum,forum',$param['oruid'].$typepage);
		}else{
			$Page = new Page($forum->total, $param['page_num'],'UcForum,forum',$param['uid'].$typepage);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',$param['page']);
		$this->assign('mythreads',$mythreads);
		if($obj == "other") $this->assign('ouserinfo',$ouserinfo);
		if($obj == "other") $this->assign("oruid", $param['oruid']);
		if($obj == "other"){
			$user = $this->getUserInfo($uid);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherThreads");
		}else{
			$this->assign("obj", "me");
			$this->assign("location", "myThreads");
		}
		// 全部话题url
		$allThreadsUrl = get_rewrite_url('UcForum','forum',$uid);
		$this->assign('allThreadsUrl',$allThreadsUrl);
		// 精华话题url
		$digestThreadsUrl = get_rewrite_url('UcForum','forum',$uid.',1',$param['page']);
		$this->assign('digestThreadsUrl',$digestThreadsUrl);
		$this->display('myThreads');
	}
	
	//回复的话题
	public function reForum(){
		$forum = D('UcForum');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : 0;
		$userinfo = $this->_user;
		if($userinfo) {
            //我回复的话题
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA回复的话题
            else {
				$ouserinfo = D('UcUser')->getUserInfoDetailByUid($uid);
				$ouserinfo['oltimes'] = min2time($ouserinfo['oltimes']);
                $obj = "other";
            }
        } 
        //TA回复的话题
        else {
            $obj = 'other';
        }
        // 类型 type为1精华
		if($type == 1){
			$param['type'] = 1;
			$typepage = ',1';
			$this->assign('digestType',$type);
		}
		$param['uid'] = $userinfo['uid'];
		if($obj == "other") $param['oruid'] = $_GET['uid'];
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		
		$param['page'] = $_GET['p'] ? intval($_GET['p']) : 1;
		$param['page_num'] = 8;
		$myreplys = $forum->getMyReply($param);
		//print_r($myreplys);die;
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($forum->total, $param['page_num'],"UcForum,reForum",$param['oruid'].$typepage);
		}else{
			$Page = new Page($forum->total, $param['page_num'],"UcForum,reForum",$param['uid'].$typepage);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('myreplys',$myreplys);
		$this->assign('ouserinfo',$ouserinfo);
		if($obj == "other") $this->assign("oruid", $param['oruid']);
		if($obj == "other"){
			$user = $this->getUserInfo($uid);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherThreads");
		}else{
			$this->assign("obj", "me");
			$this->assign("location", "myThreads");
		}
		// 全部话题url
		$allThreadsUrl = get_rewrite_url('UcForum','reForum',$uid);
		$this->assign('allThreadsUrl',$allThreadsUrl);
		// 精华话题url
		$digestThreadsUrl = get_rewrite_url('UcForum','reForum',$uid.',1',$param['page']);
		$this->assign('digestThreadsUrl',$digestThreadsUrl);
		$this->display('myReplys');
	}
	
	//我关注的群组
	public function attentionGroup(){
		$forum = D('UcForum');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$userinfo = $this->_user;
		if($userinfo) {
            //我关注的群组
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA关注的群组
            else {
				$ouserinfo = D('UcUser')->getUserInfoDetailByUid($uid);
				$ouserinfo['oltimes'] = min2time($ouserinfo['oltimes']);
                $obj = "other";
            }
        } 
        //TA关注的群组
        else {
            $obj = 'other';
        }
		$param['uid'] = $userinfo['uid'];
		if($obj == "other") $param['oruid'] = $_GET['uid'];
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 6;
		$attentiongroups = $forum->getMyAttentionGroup($param);
		//print_r($attentiongroups);die;
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($forum->total, $param['page_num'],"UcForum,attentionGroup",$param['oruid']);
		}else{
			$Page = new Page($forum->total, $param['page_num'],"UcForum,attentionGroup",$param['uid']);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('attentiongroups',$attentiongroups);
		$this->assign('ouserinfo',$ouserinfo);
		if($obj == "other") $this->assign("oruid", $param['oruid']);
		if($obj == "other"){
			$user = $this->getUserInfo($uid);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherThreads");
		}else{
			$this->assign("obj", "me");
			$this->assign("location", "myThreads");
		}
		$this->display('myAttentionGroups');
	}
	
	//关注的话题
	public function attentionThread(){
		$forum = D('UcForum');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : 0;
		$userinfo = $this->_user;
		if($userinfo) {
            //我关注的话题
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA关注的话题
            else {
				$ouserinfo = D('UcUser')->getUserInfoDetailByUid($uid);
				$ouserinfo['oltimes'] = min2time($ouserinfo['oltimes']);
                $obj = "other";
            }
        } 
        //TA关注的话题
        else {
            $obj = 'other';
        }
		//print_r($userinfo);
		// 类型 type为1精华
		if($type == 1){
			$param['type'] = 1;
			$typepage = ',1';
			$this->assign('digestType',$type);
		}
		$param['uid'] = $userinfo['uid'];
		if($obj == "other") $param['oruid'] = $uid;
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		
		$param['page'] = $_GET['p'] ? intval($_GET['p']) : 1;
		$param['page_num'] = 8;
		$myAttentionThreads = $forum->getMyAttentionThreads($param);
		//print_r($myAttentionThreads);
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($forum->total, $param['page_num'],"UcForum,attentionThread",$param['oruid'].$typepage);
		}else{
			$Page = new Page($forum->total, $param['page_num'],"UcForum,attentionThread",$param['uid'].$typepage);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('myAttentionThreads',$myAttentionThreads);
		$this->assign('ouserinfo',$ouserinfo);
		if($obj == "other") $this->assign("oruid", $param['oruid']);
		if($obj == "other"){
			$user = $this->getUserInfo($uid);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherThreads");
		}else{
			$this->assign("obj", "me");
			$this->assign("location", "myThreads");
		}
		// 全部话题url
		$allThreadsUrl = get_rewrite_url('UcForum','attentionThread',$uid);
		$this->assign('allThreadsUrl',$allThreadsUrl);
		// 精华话题url
		$digestThreadsUrl = get_rewrite_url('UcForum','attentionThread',$uid.',1',$param['page']);
		$this->assign('digestThreadsUrl',$digestThreadsUrl);
		$this->display('myAttentionThreads');
	}
	
	//更改个性签名
	public function updateSignhtml(){
		$forum = D('UcForum');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['sightml'] = img_treat(trim($_POST['sightml']));
		if($userinfo['uid']){
			if($param['sightml']){
				$r = $forum->updateSignhtml($param); 
				if($r){
					$data['status'] = 'ok';
					$data['newsightml'] = stripslashes($param['sightml']);
					
					$cacheRedis = Cache::getInstance('Redis');
					$key = C('REDIS_KEY.userinfo').$param['uid'];
					$cacheRedis->del($key);
				}else{
					$data['status'] = 'false';
				}
			}else{
				$data['status'] = 'noempty';
			}
		}else{
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data,'JSON');
	}
}
?>
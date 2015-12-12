<?php
/**
 * 个人中心基本Action类
 *
 * @created 2012-09-04
 * @author Fongson
 */
class BaseAction extends Action{
    //用户信息
    protected $_user = '';

    /**
     * 构造方法
     */
    public function __construct() {
        parent::__construct();

        //载入扩展函数库
        Load('extend');

		//安全性过滤(防止xss漏洞以及sql注入)
		//safe();

        //初始化登录用户信息
        $this->initUser();

        //初始化登录地址
        $this->initLoginUrl();

        $this->assign("thisurl",C("BLOG_DIR"));
        //加关注session
        $cKey = $this -> addCareSession();
        $this -> assign('careSession_1',$cKey);
    }

    /**
     * 初始化登录用户信息
     */
    protected function initUser(){
        //登录用户信息
        $userinfo = user_login_check();
        if($userinfo) {
            $uid = $userinfo['uid'];
            //论坛数据转化
            $userinfo['oltimes'] = min2time($userinfo['oltimes']);
            $userinfo['sightml'] = $userinfo['sightml'];
            //转化后的个性签名
            $userinfo['sightmlo'] = img_opposition_treat($userinfo['sightml']);
            // 新消息数
            $msgModel = D("UcMsg");
            $userinfo['newMsgCnt'] = $msgModel->getMsgCount($uid);
            //系统通知数
            $userinfo['noticeCnt'] = $msgModel->getNoticeCount($uid);
            //新粉丝数，日志数，照片数，微博数，新评论数
            $userCnts = D("UcIndex")->getUserCnts($uid);
            $userinfo['newFansCnt'] = $userCnts['newFansCnt'];
            $userinfo['diaryCnt'] = $userCnts['diaryCnt'];
            $userinfo['photoCnt'] = $userCnts['photoCnt'];
            $userinfo['weiboCnt'] = $userCnts['weiboCnt'];
            $userinfo['newCommentCnt'] = $userCnts['newCommentCnt'];
            //关注数，粉丝数，好友数
            $relationModel = D("UcRelation");
			$cacheRedis = Cache::getInstance('Redis');
			$intFollow = $cacheRedis->zSize(C('REDIS_KEY.follow').$uid);
			if ($intFollow > 0 ) {
				$userinfo['attentionsCnt'] = $intFollow;
			} else {
				$userinfo['attentionsCnt'] = $relationModel->getOtherCounts($uid, 1);
			}

			$intFans = $cacheRedis->zSize(C('REDIS_KEY.fans').$uid);
			if ($intFans > 0) {
				$userinfo['fansCnt'] = $intFans;
			} else {
				$userinfo['fansCnt'] = $relationModel->getOtherCounts($uid, 2);
			}
			$intFriend = $cacheRedis->zSize(C('REDIS_KEY.friend').$uid);
			if ($intFriend > 0) {
				$userinfo['friendsCnt'] = $intFriend;
			} else{
			  	$userinfo['friendsCnt'] = $relationModel->getOtherCounts($uid, 3);
			}
            //我的好友
            $friends = $relationModel->getMyFriendsList(array("uid"=>$uid, "num"=>12));
            foreach($friends as $fk => $friend) {
                $friends[$fk]['nickname'] = substr_utf8($friend['nickname'], 3, 4);
                $friends[$fk]['avatar'] = $friend['avatar_m'];
                $friends[$fk]['url_link'] = $friend['url_link'];
            }
            $userinfo['friends'] = $friends;
            //最近访客
            $userinfo['visitors'] = D("UcIndex")->getUserVisitors(array("uid"=>$uid, "num"=>12));

            //登录用户扩展信息
            $extinfo = D('Api')->getUserExtInfo($uid);
            $userinfo['extinfo'] = $extinfo;
        }

        $this->_user = $userinfo;
        $this->assign("userinfo",$userinfo);
    }

    /**
     * 初始化登录地址
     */
    protected function initLoginUrl() {
        $this->assign("login_href", get_rewrite_url('User', 'login'));
    }

    /**
     * 取得用户信息
	 *
	 * @param $uid int 用户id
     */
    protected function getUserInfo($uid) {
        //用户信息
        $huserinfo = D("Api")->getUserInfo($uid);
        if(!$huserinfo || !$huserinfo['uid']) {
            $this->_empty();exit;
        }
        $relationModel = D("UcRelation");
        $user = $this->_user;
        if($user) {
            //微博发布者与当前用户的关系
            $huserinfo['friendstatus'] = $relationModel->getSearchStatus($user['uid'], $uid);
        }
        //微博数
        $weiboCnt = D("UcWeibo")->getUserWeiboCnt($uid);
        $huserinfo['weiboCnt'] = $weiboCnt;

        //关注数，粉丝数，好友数
		$cacheRedis = Cache::getInstance('Redis');
		$intFollow = $cacheRedis->zSize(C('REDIS_KEY.follow').$uid);
		if ($intFollow > 0 ) {
			$huserinfo['attentionsCnt'] = $intFollow;
		} else {
			$huserinfo['attentionsCnt'] = $relationModel->getOtherCounts($uid, 1);
		}

		$intFans = $cacheRedis->zSize(C('REDIS_KEY.fans').$uid);
		if ($intFans > 0) {
			$huserinfo['fansCnt'] = $intFans;
		} else {
			$huserinfo['fansCnt'] = $relationModel->getOtherCounts($uid, 2);
		}
		$intFriend = $cacheRedis->zSize(C('REDIS_KEY.friend').$uid);
		if ($intFriend > 0) {
			$huserinfo['friendsCnt'] = $intFriend;
		} else{
			$huserinfo['friendsCnt'] = $relationModel->getOtherCounts($uid, 3);
		}
        //我的好友
        $friends = $relationModel->getMyFriendsList(array("uid"=>$uid, "num"=>18));
        foreach($friends as $fk => $friend) {
            $friends[$fk]['nickname'] = substr_utf8($friend['nickname'], 3, 4);
            $friends[$fk]['avatar'] = $friend['avatar'];
			$friends[$fk]['url_link'] = $friend['url_link'];
		}
        $huserinfo['friends'] = $friends;

        //最近访客
        $huserinfo['visitors'] = D("UcIndex")->getUserVisitors(array("uid"=>$uid, "num"=>18));

        return $huserinfo;
    }

    //所有访问不到的Action，跳转至404页面
    protected function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }

    protected function checkLogin($url=null){
        if(empty($this->_user)){
            $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            if(!empty($url)){
                $currentUrl = $url;
            }
            $loginUrl = get_rewrite_url('User', 'login') . '?referer=';
            header("Location:".$loginUrl.$currentUrl);
        }
    }

    /**
     * 判断用户所属组，用户是否可以发言
     * 如果未登录，则返回login
     * 如果为禁止发言组，则返回false
     * 如果为其他组别，则返回true
     *
     */
    public function checkUserGroup() {
        $user = $this->_user;
        if($user) {
            //禁止发言组（组别过期未启用）
            if($user['groupid'] == 4) {
                return false;
            } else {
                return true;
            }
        } else {
            return "login";
        }
    }

    /**
     * 页面生成session值
     */
    public function addCareSession() {
        $cKey = mt_rand(100000,99999999).microtime(TRUE);
        session($cKey,$cKey);
        return $cKey;
    }
}
?>

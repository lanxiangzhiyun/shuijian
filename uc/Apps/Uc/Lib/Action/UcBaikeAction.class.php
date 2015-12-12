 <?php
/**
 * UcBaike Action类
 */
class UcBaikeAction extends BaseAction {
	public function __construct() {
        parent::__construct();
    }
	
	/** 
	 * 收藏
	 * 词条收藏 问答收藏
	 */
	public function collection() {
		// 百科Model实例化
		$baikeModel = D('UcBaike');
		// 用户id
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		// 是否登录
		$userinfo = $this->_user;
		if($userinfo) {
            // 我的收藏
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA的收藏
            else {
                $obj = "other";
            }
        } 
        //TA的收藏
        else {
            $obj = 'other';
        }

		// 参数
		if($obj == "other") {
			$param['oruid'] = $uid;
		} else {
			$param['uid'] = $userinfo['uid'];
		}

		// 登录
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		// 获取收藏词条
		$collectionEntryList = $baikeModel->getCollectionEntryList($param);
		$this->assign('collectionEntryList', $collectionEntryList);

		// 当前页码
		$param['page'] = intval($_GET['p']);
		// 页显数量
		$param['page_num'] = 20;
		// 收藏问答
		$collectionAskList = $baikeModel->getCollectionAskList($param);
		// 分页
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,collection",$param['oruid']);
		}else{
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,collection",$param['uid']);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('collectionAskList',$collectionAskList);

		if($obj == "other") {
			$this->assign("oruid", $param['oruid']);
		}
		// 百科位置
		if($obj == "other"){
			$user = $this->getUserInfo($uid);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherBaikes");
		} else {
			$this->assign("obj", "me");
			$this->assign("location", "myBaikes");
		}

		$this->display('collection');
	}

	/**
	 * 关注
	 * 关注问答
	 */
	public function attention() {
		// 百科Model实例化
		$baikeModel = D('UcBaike');
		// 用户id
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		// 是否登录
		$userinfo = $this->_user;
		if($userinfo) {
            // 我的关注
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA的关注
            else {
                $obj = "other";
            }
        } 
        //TA的关注
        else {
            $obj = 'other';
        }

		// 参数
		if($obj == "other") {
			$param['oruid'] = $uid;
		} else {
			$param['uid'] = $userinfo['uid'];
		}

		// 登录
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		// 当前页码
		$param['page'] = intval($_GET['p']);
		// 页显数量
		$param['page_num'] = 20;
		// 关注标签
		$attentionTagList = $baikeModel->getAttentionTagList(array('uid'=>$param['uid'],'oruid'=>$param['oruid']));
		// print_r($attentionTagList);
		// 关注问答
		$attentionAskList = $baikeModel->getAttentionAskList($param);
		// 分页
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,attention",$param['oruid']);
		}else{
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,attention",$param['uid']);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('attentionTagList',$attentionTagList);
		$this->assign('attentionAskList',$attentionAskList);
		if($obj == "other") {
			$this->assign("oruid", $param['oruid']);
		}
		// 百科位置
		if($obj == "other"){
			$user = $this->getUserInfo($uid);			
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherBaikes");
		} else {
			$this->assign("obj", "me");
			$this->assign("location", "myBaikes");
		}

		$this->display('attention');
	}


	/**
	 * 提问
	 */
	public function ask() {
		$baikeModel = D('UcBaike');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$userinfo = $this->_user;
		if($userinfo) {
            //我发表的帖子
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA发表的帖子
            else {
                $obj = "other";
            }
        } 
        //TA发表的帖子
        else {
            $obj = 'other';
        }
		$param['uid'] = $userinfo['uid'];
		if($obj == "other") {
			$param['oruid'] = $uid;
		}
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$askList = $baikeModel->getAskList($param);
		
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,ask",$param['oruid']);
		}else{
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,ask",$param['uid']);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('askList',$askList);
		if($obj == "other") $this->assign("oruid", $param['oruid']);
		if($obj == "other"){
			$user = $this->getUserInfo($uid);		
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherBaikes");
		}else{
			$this->assign("obj", "me");
			$this->assign("location", "myBaikes");
		}

		$this->display('ask');

	}


	/**
	 * 回答
	 */
	public function reply() {
		$baikeModel = D('UcBaike');
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		$userinfo = $this->_user;
		if($userinfo) {
            //我回复的帖子
            if(empty($uid) || $uid == $userinfo['uid']) {
                $obj = 'me';
            }
            //TA回复的帖子
            else {
                $obj = "other";
            }
        } 
        //TA回复的帖子
        else {
            $obj = 'other';
        }
		//print_r($userinfo);
		$param['uid'] = $userinfo['uid'];
		if($obj == "other") $param['oruid'] = $uid;
		if(!$param['uid'] and !$param['oruid']) {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
		}
		
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$replyList = $baikeModel->getReplyList($param);
		
		import("ORG.Page");
		if($obj == "other"){
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,reply",$param['oruid']);
		}else{
			$Page = new Page($baikeModel->total, $param['page_num'],"UcBaike,reply",$param['uid']);
		}
		$this->assign('page', $Page->show());
		$this->assign('p',intval($_GET['p']));
		$this->assign('replyList',$replyList);
		$this->assign('total',$baikeModel->total);

		
		if($obj == "other") {
			$this->assign("oruid", $param['oruid']);
		}
		// 百度位置
		if($obj == "other"){
			$user = $this->getUserInfo($uid);		
			$this->assign("huser", $user);
			$this->assign("obj", "other");
			$this->assign("location", "otherBaikes");
		}else{
			$this->assign("obj", "me");
			$this->assign("location", "myBaikes");
		}

		$this->display('reply');

	}

}
?>
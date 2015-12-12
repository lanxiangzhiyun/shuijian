<?php
/**
* 移动API接口
* @author vic
* @create 2013-06-05
*/
class MobileApiAction extends BaseAction {

	//获取用户的关注、粉丝。好友关系列表
	public function getRelationList () {
	//用户账号检查
		$userModel = D('UcUser');
		$param = $this ->_post();
		$uid = $userModel -> checkUser($param['username'],$param['password']);
		if (!$uid){
			echo   0; exit;
		}

		$param['uid'] = $uid;
		$relationModel  =D('UcRelation');
		$arrList  = $relationModel -> getRelationList($param);
		$this ->ajaxReturn($arrList,'JSON');
	}

	//关注
	public function mobileAddAttention () {
		//用户账号检查
		$userModel = D('UcUser');
		$relationModel = D('UcRelation');
		$param = $this ->_param();
		$uid = $userModel -> checkUser($param['username'],$param['password']);
		if (!$uid){
			echo 0;exit;
		}
		$param['uid'] = $uid;
		$status = $relationModel -> mobileAddAttention($param);
		echo $status;
	}

	//取消关注

	public function mobileCancelAttention() {
		//用户账号检查
		$userModel = D('UcUser');
		$relationModel = D('UcRelation');
		$param = $this ->_param();
		$uid = $userModel -> checkUser($param['username'],$param['password']);
		if (!$uid){
			echo 0;exit;
		}
		$param['uid'] = $uid;
		$status = $relationModel -> mobileCancelAttention($param);
		echo $status;
	}

	//发微薄
	public function addWeibo(){
		$weiboModel = D('UcWeibo');
		$username = $_POST['username'];
		$password = $_POST['password'];
		//判断是否登录用户
		$uid = D("UcUser")->checkUser($username, $password);
		if(!$uid){
			echo 0;
		}
		if(!$this->checkUserGroup()){
			echo 0;
		}

		$_POST['uid'] = $uid;
		$_POST['from'] = 'mobile';
		$_POST['type'] = 'weibo';
		if(!empty($_FILES)){
			$imgPath = A('Upload')->imageUpload();
			if($imgPath){
				$param['weibo_pic'] = $imgPath;
			}
		}

		$param['uid'] = $uid;
		$param['weibo_content'] = $_POST['content'];
		$param['weibo_content'] = stripslashes(trim($param['weibo_content']));
		if($param['weibo_content'] || $param['weibo_pic']){
			$result = D('SensitiveWord')->isOrNotSensitiveWord($param['weibo_content']);
			if($result){
				echo 0;
			}else{
				$r = $weiboModel->addWeibo($param);
				if($r){
					echo 1;
				}else{
					echo 0;
				}
			}
		}
	}

	//微博列表
	public function getWeiboListByUid(){
		$weiboModel = D('UcWeibo');

		$username = $_POST['username'];
		$password = $_POST['password'];

		if($username && $password){
			//判断是否登录用户
			$uid = D("UcUser")->checkUser($username, $password);
			if($uid){
				$relationModel = D('UcRelation');
				if($uid != $_POST['uid']){
					$status = $relationModel ->getSearchStatus($uid, $_POST['uid']);
					$reslut['relation'] = $status;
				}
			}
		}
		$param['page'] = isset($_POST['p']) ? intval($_POST['p']) : 1;
		$param['page_num'] = 8;

		$param['uid'] = $_POST['uid'];
		$weiboList = $weiboModel->getWeiboByUid($param);
		//json数据
		foreach($weiboList as $val) {
			//pid: 啊呜ID
			//ctn: 微博内容
			//type: 1原创;2转发
			//time: 时间
			//img: 如果用户有图片则此处是图片的url，如果没有图片则是""
			$data['pid'] = $val['id'];
			$data['ctn'] = $val['weibo_content'];
			$data['time'] = $val['weibo_time'];
			$data['comments'] = $val['comments'];
			if($val['weibo_pic']){
				$data['img'] = C('IMG_DIR').'/'.$val['weibo_picsml'];
			}else{
				$data['img'] = "";
			}
			$data['type'] = $val['flag'];

			//评论
			$commentList = $weiboModel->getMobileWeiboCommentsTop5($val['id']);

			$commentLists = array();
			//json数据
			foreach($commentList as $val2) {
				//avatar:用户头像的Url
				//nickname: 用户的昵称
				//ctn:评论内容
				//time:时间
				$data2['avatar'] = $val2['avatar'];
				$data2['nickname'] = $val2['nickname'];
				$data2['ctn'] = $val2['message'];
				$data2['time'] = $val2['dateline'];

				$commentLists[] = $data2;
			}
			if($commentLists){
				$data['reply'] = $commentLists;
			}else{
				$data['reply'] = '';
			}
			$weiboLists[] = $data;
		}

		$reslut['awu'] = $weiboLists;

		//print_r($reslut);die;
		if(!empty($reslut['awu'])) {
			echo json_encode($reslut);
		} else {
			echo 0;
		}
	}

	//评论列表
	public function getWeiboComment(){
		$weiboModel = D('UcWeibo');
		$param['wid'] = $_POST['wid'];

		$param['page'] = isset($_POST['p']) ? intval($_POST['p']) : 1;
		$param['page_num'] = 5;

		//评论
		$commentList = $weiboModel->getMobileWeiboComments($param);
		//print_r($commentList);
		//json数据
		foreach($commentList as $val) {
			//avatar:用户头像的Url
			//nickname: 用户的昵称
			//ctn:评论内容
			//time:时间
			$data['avatar'] = $val['avatar'];
			$data['nickname'] = $val['nickname'];
			$data['ctn'] = $val['message'];
			$data['time'] = $val['dateline'];

			$commentLists[] = $data;
		}

		if(!empty($commentLists)) {
			echo json_encode(array('reply'=>$commentLists));
		} else {
			echo 0;
		}
	}

	//评论微薄
	public function replyWeibo(){
		$weiboModel = D('UcWeibo');

		$username = $_POST['username'];
		$password = $_POST['password'];
		//判断是否登录用户
		$uid = D("UcUser")->checkUser($username, $password);
		if(!$uid){
			echo 0;
		}
		if(!$this->checkUserGroup()){
			echo 0;
		}
		$param['uid'] = $uid;
		$param['wid'] = $_POST['wid'];
		$param['message'] = trim(stripslashes($_POST['content']));
		if($param['wid']){
			//判断登陆
			if($param['message']){
				$result = D('SensitiveWord')->isOrNotSensitiveWord($param['message']);
				if($result){
					echo 0;
				}else{
					$r = $weiboModel->replyWeibo($param);
					if($r == 0){
						echo 0;
					}elseif($r == -1){
						echo 0;
					}elseif($r == -2){
						echo 0;
					}else{
						echo 1;
					}
				}
			}else{
				echo 0;
			}
		}
	}
	
	//获取收到指定好友的消息
	public function getInboxMsg(){
		$msg = D('UcMsg');
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		//判断是否登录用户
		$uid = D("UcUser")->checkUser($username, $password);
		if(!$uid){
			echo 0;exit;
		}
		
		$param['page'] = isset($_POST['p']) ? intval($_POST['p']) : 1;
		$param['page_num'] = 5;
		$param['uid'] = $uid;
		$param['sendid'] = $_POST['sendid'];
		$inboxList = $msg->getMobileInboxMsg($param);
		//print_r($inboxList);
		//json数据
		foreach($inboxList as $val) {
			//content:内容
			$data['ctn'] = $val['content'];
			$inboxLists[] = $data;
		}
		
		if(!empty($inboxLists)) {
			//已读消息
			echo json_encode(array('message'=>$inboxLists));
			$msg->readMobileInboxMsg($param);
		} else {
			echo 0;
		}
	}
	
	//发送消息
	public function sendMsg(){
		$msg = D('UcMsg');
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		//判断是否登录用户
		$uid = D("UcUser")->checkUser($username, $password);
		if(!$uid){
			echo 0;exit;
		}
		if(!$this->checkUserGroup()){
			echo 0;exit;
		}
		$param['uid'] = $uid;
		$param['receverid'] = $_POST['receverid'];
		$param['content'] = trim($_POST['content']);
		if($param['receverid'] && $param['content']){
			if(strlen_weibo($param['content']) > 150){
				echo 0;
			}else{
				$r = $msg->sendMsg($param);
				if($r == -1){
					//根据对方设置，你不能进行该操作
					echo 0;
				}elseif($r == -2){
					//请先解除黑名单再进行操作
					echo 0;
				}elseif($r == 0){
					//该用户不存在，你不能进行该操作
					echo 0;
				}else{
					echo 1;
				}
			}
		}else{
			//发件人和内容不能为空
			echo 0;
		}
	}
	
	/**
	 * 宠物属性值(属性值以','号分隔,依次为health,charming,clean,social,happy,confidence)
	 * @param $param(username,password,pid,health,charming,clean,social,happy,confidence)
	 * @return mixed
	 */
	public function setPetNature()
	{
		$username = $this->_post('username');
		$password = $this->_post('password');
		$uid = D('UcUser')->checkUser($username,$password);
		if (!$uid){
			echo -3;exit;
		}
		$id = $this->_post('pid');
		if(!$id){
			echo -1;exit;
		}
		$userPet = M('uc_user_pet')->where('id='.$id)->field('uid')->find();
		if(!$userPet || $userPet['uid']!=$uid){
			echo -2;exit;
		}
		$health = $this->_post('health');
		if(!$health){
			$health = 0;
		}
		$charming = $this->_post('charming');
		if(!$charming){
			$charming = 0;
		}
		$clean = $this->_post('clean');
		if(!$clean){
			$clean = 0;
		}
		$social = $this->_post('social');
		if(!$social){
			$social = 0;
		}
		$happy = $this->_post('happy');
		if(!$happy){
			$happy = 0;
		}
		$confidence = $this->_post('confidence');
		if(!$confidence){
			$confidence = 0;
		}
		$nature = $health.','.$charming.','.$clean.','.$social.','.$happy.','.$confidence;
		$result = -4;
		if(M('uc_user_pet')->save(array('id'=>$id,'pet_nature'=>$nature))){
			$result = 1;
		}
		echo $result;
	}

	/**
	* 获取手机推送内容
	*/
	public function getMobilePush() {
		$code = $this->_post('code');
		$publish = M('boqii_publish')->where('code='.$code)->find();
		if(!$publish){
			echo 0;exit;
		}
		$articles = M('boqii_publish_article')->where('publish_id='.$publish['id'])->order('position desc,create_time desc')->select();
		if($code=='20100'){	//首页幻灯图
			$result = array();
			foreach($articles as $key=>$val){
				$result['slider'][] = array('pid'=>$val['url'],'img'=>C('IMG_DIR').'/'.$val['img1']);
			}
			echo json_encode($result);exit;
		}
		echo json_encode($articles);
	}
}

?>
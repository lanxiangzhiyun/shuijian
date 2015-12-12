<?php
//对外控制器
class ApiAction extends BaseAction {
	//收到的消息
	public function inbox(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		if(!$userinfo) {
			 $data['msg'] = '请先登录';
			 $this->ajaxReturn($data,'JSON');
		}
		$param['uid'] = $userinfo['uid'];
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$inboxList = $msgModel->getInboxMsg($param);
		
		$inbox['total'] = $msgModel->total;
        $inbox['page'] = $param['page'];
        $inbox['pageNum'] = $param['page_num'];
		$inbox['list'] = $inboxList;
		$this->ajaxReturn($inbox, 'JSON');
	}
	
	//发出的消息
	public function outbox(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		if(!$userinfo) {
			 $data['msg'] = '请先登录';
			 $this->ajaxReturn($data,'JSON');
		}
		$param['uid'] = $userinfo['uid'];
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$outboxList = $msgModel->getOutboxMsg($param);
		
		$outbox['total'] = $msgModel->total;
        $outbox['page'] = $param['page'];
        $outbox['pageNum'] = $param['page_num'];
		$outbox['list'] = $outboxList;
		
		$this->ajaxReturn($outbox, 'JSON');
	}
	
	//消息通知
	public function notice () {
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		if(!$userinfo) {
			 $data['msg'] = '请先登录';
			 $this->ajaxReturn($data,'JSON');
		}
		$param['uid'] = $userinfo['uid'];
		$param['page'] = intval($_GET['p']);
		$param['page_num'] = 20;
		$noticeList = $msgModel->getNotice($param);
		//更改已读状态
		$msgModel->updateNew($param['uid']);
		
		$notice['total'] = $msgModel->total;
        $notice['page'] = $param['page'];
        $notice['pageNum'] = $param['page_num'];
		$notice['list'] = $noticeList;
		
		$this->ajaxReturn($notice, 'JSON');
	}
	
	//查询我的好友
	public function searchFriend() {
		$relationModel = D('UcRelation');
		$userinfo = $this->_user;
		if(!$userinfo) {
			 $data['msg'] = '请先登录';
			 $this->ajaxReturn($data,'JSON');
		}
		$param['uid'] = $userinfo['uid'];
		$data = $relationModel->getFriendsForSendNews($param);
		
		$this->callBack($data);
	}
	
	//回复消息
	public function replyMsg(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		if(!$this->checkUserGroup()){
			$data['msg'] = '您可能涉及违规内容发布，暂时无法进行该操作，如有问题，请联系论坛管理员。';
			$this->callBack($data);
		}
		if($userinfo['uid']){
			$_POST['uid'] = $userinfo['uid'];
			//上一级消息id
			$_POST['pid'] = $_REQUEST['pid'];
			$_POST['receverid'] = $_REQUEST['receverid'];
			$_POST['content'] = trim(stripslashes($_REQUEST['content']));
			if($_POST['content']){
				if(strlen_weibo($_POST['content']) > 150){
					$data['msg'] = '文字已超出最大限制字数。';
				}else{
					$r = $msgModel->reciveMsg($_POST);
					if($r == 0){
						$data['msg'] = '回复者不能为空。';
					}elseif($r == -1){
						$data['msg'] = '根据对方设置，你不能进行该操作。';
					}elseif($r == -2){
						$data['msg'] = '请先解除黑名单再进行操作。';
					}else{
						$data['msg'] = 'ok';
					}
				}
			}else{
				$data['msg'] = '发件人和内容不能为空';
			}
		}else{
			$data['msg'] = '请登录后进行操作。';
		}
		
		$this->callBack($data);
	}
	
	//更改收件箱已读状态
	public function updateStatus(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		if($userinfo['uid']){
			$id = $_GET['id'];
			$msgModel->readMsg($id);
			$data['msg'] = 'ok';
			$this->callBack($data);
		}
	}
	
	//发消息
	public function publishMsg(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['receverid'] = $_REQUEST['receverid'];
		$param['content'] = trim($_REQUEST['content']);
		if(!$this->checkUserGroup()){
			$data['msg'] = '您可能涉及违规内容发布，暂时无法进行该操作，如有问题，请联系论坛管理员。';
			$this->callBack($data);
		}
		if($param['uid']){
			if($param['receverid'] && $param['content']){
				if(strlen_weibo($param['content']) > 150){
					$data['msg'] = '文字已超出最大限制字数。';
				}else{
					$r = $msgModel->sendMsg($param);
					if($r == -1){
						$data['msg'] = '根据对方设置，你不能进行该操作。';
					}elseif($r == -2){
						$data['msg'] = '请先解除黑名单再进行操作。';
					}elseif($r == 0){
						$data['msg'] = '该用户不存在，你不能进行该操作。';
					}else{
						$data['msg'] = 'ok';
					}
				}
			}else{
				$data['msg'] = '发件人和内容不能为空。';
			}
		}else{
			$data['msg'] = '请登录后进行操作。';
		}
		
		$this->callBack($data);	
	}
	
	//发件箱单个删除
	public function msgDel(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['msgid'] = $_GET['msgid'];
		$param['type'] = 2;
		//print_r($param);die;
		if($param['msgid']){
			//判断登陆
			if($param['uid']){
				$r = $msgModel->delMsg($param);
				if($r){
					$data['msg'] = 'ok';
				}else{
					$data['msg'] = '操作失败,请稍后再试。';
				}
			}else{
				$data['msg'] = '请登录后进行操作。';
			}
		}else{
			$data['msg'] = '操作失败,请稍后再试。';
		}
		
		$this->callBack($data);	
	}
	
	//收件箱单个删除
	public function msgInDel(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$param['msgid'] = $_GET['msgid'];
		$param['checkblack'] = $_GET['checkblack'];
		$param['type'] = 1;
		if($param['msgid']){
			//判断登陆
			if($param['uid']){
				$r = $msgModel->delMsg($param);
				if($r){
					$data['msg'] = 'ok';
				}else{
					$data['msg'] = '操作失败,请稍后再试。';
				}
			}else{
				$data['msg'] = '请登录后进行操作。';
			}
		}else{
			$data['msg'] = '操作失败,请稍后再试。';
		}
		
		$this->callBack($data);	
	}
	
	//发件箱批量删除
	public function msgsDel(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$idArr = explode(',',$_GET['msgid']);
		$param['type'] = 2;
		
		if($param['uid']){
			foreach($idArr as $key=>$val){
				if($val){
					$param['msgid'] = $val;
					$r = $msgModel->delMsg($param);
					if(!$r){
						$data['msg'] = '删除失败！';
						$this->callBack($data);	
					}
					
				}
			}
			$data['msg'] = 'ok';
		}else{
			$data['msg'] = '请登录后进行操作。';
		}
		
		$this->callBack($data);	
	}
	
	//收件箱批量删除
	public function msgsInDel(){
		$msgModel = D('UcMsg');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$idArr = explode(',',$_GET['msgid']);
		$param['checkblack'] = $_GET['checkblack'];
		$param['type'] = 1;
		
		if($param['uid']){
			foreach($idArr as $key=>$val){
				if($val){
					$param['msgid'] = $val;
					$r = $msgModel->delMsg($param);
					
				}
			}
			$data['msg'] = 'ok';
		}else{
			$data['msg'] = '请登录后进行操作。';
		}
		
		$this->callBack($data);	
	}

	//删除日志
	public function delDiaryInfo() {
		$uid = $this->_user['uid'];	//当前登录用户
		if($uid){
			$id = $this->_get('id');
			//删除日志
			$res = D("UcDiary")->deleteDiaryList($id,$uid);
			
			$this->ajaxReturn(array('status'=>1,'msg'=>'删除成功'),'JSON');
		}
		exit;
	}

	//返回形式
	public function callBack ($data) {
		// JSONP 形式的回调函数来加载其他网域的 JSON 数据
		$callback = isset($_GET['callback']) ? $_GET['callback'] : '';
		if (!empty($callback)) {
			echo $_GET['callback'].'('.json_encode($data).')'; exit;
		} else {
			$this->ajaxReturn($data, 'JSON');
		}
	}
	
	//获取日志分类列表
	public function getDiaryType(){
		$uid = $this->_get('uid');	
		$list = D('UcDiary')->getUserDiaryTypeList($uid);
		$this->ajaxReturn($list,'JSON');
	}

	//获取日志详情
	public function getDiaryInfo(){
		$id = $this->_get('id');
		$diaryModel = D('UcDiary');
		//获取日志详情
		$param['id'] = $id;
        $param['nocomment'] = 0;
        $param['page'] = isset($_GET['p']) ? $_GET['p'] : 1;
        $param['page_num'] = $_GET['limit'];
        $diaryInfo = $diaryModel->getDiaryInfo($param);
		$diaryInfo['total'] = $diaryModel->total;
		$this->ajaxReturn($diaryInfo,'JSON');
	}

	/**************************** 主站搜索接口 Start *****************************/
	/**
	 * 获取个人空间搜索用户信息
	 * POST传参
	 * 		KeyWord		String	关键字		必须	
	 *		Uid 		Int 	用户id		否
	 *		CityId		Int		城市id		否	
	 *		AreaId		Int		区域id		否	
	 *		Sex			Int		性别		否		0不限1男2女
	 *		OrderTypeId	Int		排序标识id	否		1默认；2粉丝数降序
	 *		Page		Int		当前页码	必须	
	 *		Number		Int		页显数量	必须	
	 *
	 * JSON返回值
	 */
	public function GetSearchUserList() {
		// 关键词
		$keyword = $this->_post('KeyWord');
		if(!$keyword) {
			$this->ajaxReturn(array("ResponseStatus"=>-1,"ResponseMsg"=>"参数丢失！"), 'JSON');
		}
		$param['keyword'] = $keyword;
		// 用户id
		if($_POST['Uid']) {
			$param['uid'] = $_POST['Uid'];
		}
		// 城市ID
		if($_POST['CityId']) {
			$param['cityId'] = $_POST['CityId'];
		}
		// 区域ID
		if($_POST['AreaId']) {
			$param['areaId'] = $_POST['AreaId'];
		}	
		// 性别
		if($_POST['Sex']) {
			$param['sex'] = $param['sex'];
		}
		// 排序
		if($_POST['OrderTypeId']) {
			$param['sort'] = $param['OrderTypeId'];
		}						
		// 分页参数
		$param['page'] = isset($_POST['Page']) ? $this->_post('Page') : 1;
		$param['pageNum'] = isset($_POST['Number']) ? $this->_post('Number') : 10;
		
		// 搜索Model
		$searchModel = D('UcSearch');

		// 搜索用户
		$userList = $searchModel->getSearchUserListForSite($param);

		// 返回结果
		$result = array('ResponseStatus'=>0, 'ResponseData'=>array('Total'=>$searchModel->total, 'UserList'=>$userList));

		$this->ajaxReturn($result, 'JSON');	
	}

	/**
	 * 获取个人空间搜索用户信息结果数
	 * POST传参
	 * 		KeyWord		String	关键字		必须	
	 *
	 * JSON返回值
	 */
	public function GetSearchUserCount() {
		// 关键词
		$keyword = $this->_post('KeyWord');
		if(!$keyword) {
			$this->ajaxReturn(array("ResponseStatus"=>-1,"ResponseMsg"=>"参数丢失！"), 'JSON');
		}
		$param['keyword'] = $keyword;
					
		// 搜索Model
		$searchModel = D('UcSearch');

		// 搜索用户结果数
		$count = $searchModel->getSearchUserCountForSite($param);

		// 返回结果
		$result = array('ResponseStatus'=>0, 'ResponseData'=>array('Total'=>$count));

		$this->ajaxReturn($result, 'JSON');	
	}
	/**************************** 主站搜索接口 End *****************************/


	/**
	 * 收到的消息
	 */
	public function GetUserMessageList(){
		// 参数
		// 用户id
		$param['uid'] = $_POST['uid'];
		if(!$param['uid']) {
			$this->ajaxReturn(array('status'=>'error', 'msg'=>'参数丢失，请确认！'), 'JSON');
		}
		// 指定数量
		$param['num'] = $_POST['num'] ? $_POST['num'] : 10;

		$msgModel = D('UcMsg');
		$result = $msgModel->getInboxMsgList($param);
		if(!$result['list']) {
			$this->ajaxReturn(array('status'=>'ok', 'newnum'=>0, 'data'=>array()), 'JSON');
		}
		$this->ajaxReturn(array('status'=>'ok', 'newnum'=>$result['newnum'], 'data'=>$result['list']), 'JSON');
	}
}

?>
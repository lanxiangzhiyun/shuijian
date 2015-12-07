<?php
/**
 * 个人中心首页Action类
 *
 * @created 2012-09-04
 * @author Fongson
 */
class IndexAction extends BaseAction {
	/**
	 * 个人中心首页
	 */
	public function index() {
		// url参数：访问用户uid
		$uid = $this -> _get('uid') ? $this -> _get('uid') : '';
		// 当前登录用户信息
		$user = $this -> _user;
		if($user && (empty($uid) || $uid == $user['uid'])) {
			$obj = 'me';
		}
		// TA人中心首页
		else {
			$obj = 'other';
		}

		$indexModel = D("UcIndex");
		if ($obj == "me") {
			// 访问个人中心首页用户必须登录
			if (!$user['uid']) {
				header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				exit;
			}

			// 波奇广播站（UID:1328680）默认所有用户自动对这个账号加关注
			D("UcRelation") -> addOfficFollow($user['uid']);

			// 签到模块中周X
			$weekList = array(0 => "周日", 1 => "周一", 2 => "周二", 3 => "周三", 4 => "周四", 5 => "周五", 6 => "周六");
			$weekName = $weekList[date("w")];
			$this -> assign("weekName", $weekName);

			// 所有任务列表
			$taskList = $indexModel -> getTaskList(array("type" => 1, "uid" => $user['uid']));
			//是否允许领取奖励（完成任一任务便可以领取，不可重复领取）
			$allowreward = 0;
			foreach($taskList as $tk => $task) {
				if (($task['task_id'] == 1 || $task['task_id'] == 2) && $task['rewarded'] == 0 && $task['completed'] >= $task['total']) {
					$allowreward = 1;
				} elseif ($task['task_id'] == 3 && $task['completed'] >= $task['total']) {
					$allowreward = 1;
				}
			}
			$this -> assign("taskList", $taskList);
			$this -> assign("allowreward", $allowreward);

			// 所有标签
			$tagList = $indexModel -> getTags($user['uid']);
			$this -> assign("tagList", $tagList);

			// 我的标签
			$myTags = $indexModel -> getUserTagList($user['uid']);
			$this -> assign("myTags", $myTags);

			// 是否今日已签到
			$isSigned = $indexModel -> isUserSigned($user['uid'], date("Y-m-d"));
			$this -> assign("isSigned", $isSigned);

			// 系统公告
			$gonggaoList = $indexModel -> getAnnouncements(10);
			$this -> assign("gonggaoList", $gonggaoList);

			// 热门话题
			$hotThreads = $indexModel -> getIndexHotThreads();
			$this -> assign("hotThreads", $hotThreads);

			// 获取最新一期百科小知识
			$baike = $indexModel -> getCurrentBaike();
			$this -> assign("baike", $baike);

			// 动态显示：全部动态/好友动态/与我有关的动态
			// 动态显示类型：1全部动态；2好友动态；3与我有关的动态
			$type = $this -> _get('type') ? $this -> _get('type') : 1;
			$param['uid'] = $user['uid'];
			$param['loginuid'] = $user['uid'];
			$param['type'] = $type;
			$param['page_num'] = 15;
			$param['page'] = $this -> _get('p') ? $this -> _get('p') : 1;
			$dynamics = $indexModel -> getUserDynamics($param);
			$this -> assign("type", $type);
			$this -> assign("dynamics", $dynamics);

			//分页显示
			import("ORG.Page");
			$Page = new Page($indexModel -> total, $param['page_num'], "Index,index", $user['uid'] . "," . $type);
			$this -> assign('page', $Page -> show());

			//页面所处标志
			$this -> assign("location", "myIndex");
			$this -> assign("obj", "me");

			// 加入session值防CSRF攻击
			$key = md5(uniqid(rand(),true));
			$_SESSION[$key] = 1;
			$this->assign('token',$key);

			$this -> display('index');
		} elseif ($obj == "other") {
			if (!$uid) {
				header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				exit;
			}
			// 记录最近访客
			$this -> logVisit($uid);

			// 取得TA人用户信息
			$huser = $this -> getUserInfo($uid);
			$this -> assign("huser", $huser);

			// TA的宠物列表
			$userPets = D("UcPets") -> getUserPetsList(array("uid" => $uid));
			// TA的宠物照片
			if (!$userPets || !isset($userPets['pet_photos'])) {
				$userPhotos = D("UcAlbum") -> getUserDefaultPhotos($uid);
				$this -> assign("userPhotos", $userPhotos);
			}
			$this -> assign("userPets", $userPets);

			$indexModel = D("UcIndex");

			// 热门话题
			$hotThreads = $indexModel -> getIndexHotThreads();
			$this -> assign("hotThreads", $hotThreads);

			// 热门宠物日志
			$hotDiaryList = D("UcDiary") -> getHotDiaryList();
			$this -> assign("hotDiaryList", $hotDiaryList);

			// 4:TA的动态;2:TA的好友动态
			$param['uid'] = $huser['uid'];
			$param['loginuid'] = isset($user['uid']) ? $user['uid'] : 0;
			$param['type'] = $this -> _get('type') ? $this -> _get('type') : 4; //TA的动态
			$param['page_num'] = 15;
			$param['page'] = $this -> _get('p') ? $this -> _get('p') : 1;
			$dynamics = $indexModel -> getUserDynamics($param);
			$this -> assign("dynamics", $dynamics);
			$this -> assign("type", $param['type']);

			//分页显示
			import("ORG.Page");
			$Page = new Page($indexModel -> total, $param['page_num'], "Index,index", $huser['uid'] . "," . $param['type']);
			$this -> assign('page', $Page -> show());

			//页面位置标志
			$this -> assign("location", "otherIndex");
			$this -> assign("obj", "other");

			$this -> display("otherIndex");
		} else {
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			exit;
		}
	}

	/**
	 * 生成动态（GET）
	 * 百科、商城中调用生成动态（论坛直接入库，没有调用该方法，待处理）
	 *
	 * @param get参数：uid int 用户id
	 * @param get参数：type int 动态类型（必须）
	 * @param get参数：ouid int 被操作用户id
	 * @param get参数：ousername string 被操作用户名
	 * @param get参数：oid int 被操作对象id（必须）
	 * @param get参数：otitle string 被操作对象标题
	 * @param get参数：operatetype int 操作类型（必须）
	 * @param get参数：mid int 被操作对象上级对象id
	 *
	 * @return boolean 是否成功
	 */
	public function addDynamic() {
		$uid = $this -> _get('uid') ? $this -> _get('uid') : '';
		if (!$uid) {
			$userinfo = $this -> _user;
			if ($userinfo) {
				$uid = $userinfo['uid'];
			} else {
				return false;
			}
		}
		$param['uid'] = $uid;
		$param['type'] = $this -> _get('type') ? $this -> _get('type') : 0;
		if (!$param['type']) {
			return false;
		}
		$param['ouid'] = $this -> _get('ouid') ? $this -> _get('ouid') : 0;
		$param['ousername'] = $this -> _get('ousername') ? $this -> _get('ousername') : '';
		$param['oid'] = $this -> _get('oid') ? $this -> _get('oid') : '';
		if (!$param['oid']) {
			return false;
		}
		$param['otitle'] = $this -> _get('otitle') ? $this -> _get('otitle') : '';
		$param['operatetype'] = $this -> _get('operatetype') ? $this -> _get('operatetype') : 0;
		if (!$param['operatetype']) {
			return false;
		}
		$param['mid'] = $this -> _get('mid') ? $this -> _get('mid') : 0;

		D("UcIndex") -> addDynamic($param);

		return true;
	}

	/**
	 ********************* ajax Function Start *******************************
	 */
	/**
	 * ajax方法：设置用户引导
	 *
	 * @return 返回数据(json)：状态status
	 */
	public function ajaxUserGuide() {
		// 用户信息
		$user = $this -> _user;
		if ($user) {
			D("UcUser") -> setUserGuide($user['uid']);
			$data['status'] = 'ok';
		} else {
			$data['status'] = 'login';
		}
		$this -> ajaxReturn($data, 'JSON');
	}

	/**
	 * ajax方法：用户领取奖励(json数据格式)
	 *
	 * @param type int 任务类型(post参数)
	 *
	 * @return 返回数据(json)：状态status、备注info
	 */
	public function ajaxGetReward() {
		// 任务类型
		$type = $this -> _post('type') ? $this -> _post('type') : 1;
		// 用户信息
		$user = $this -> _user;
		$rewards = D("UcIndex") -> getTaskRewards(array("type" => $type, "uid" => $user['uid']));
		if ($rewards) {
			$data['status'] = "ok";
			$data['info'] = $rewards;
		} else {
			$data['status'] = "error";
			$data['info'] = array("<smap>领取失败！<\/smap>");
		}
		$this -> ajaxReturn($data, 'JSON');
	}

	/**
	 * ajax方法：取得标签(json数据格式)
	 *
	 * @return 返回数据(json)：标签编号id 标签名称name 是否用户已有select
	 * [{"id":"9","name":"\u4ed3\u9f20","select":0},{"id":"1","name":"\u732b\u732b","select":1},...}]
	 */
	public function ajaxGetTags() {
		// 用户信息
		$user = $this -> _user;
		$data = D("UcIndex") -> getTags($user['uid']);

		$this -> ajaxReturn($data, 'JSON');
	}

	/**
	 * ajax方法：用户添加标签(json数据格式)
	 *
	 * @param tagid int 标签id(post参数)
	 *
	 * @return 返回数据(json)：状态status
	 */
	public function ajaxAddUserTag() {
		// 用户信息
		$user = $this -> _user;
		$param['uid'] = $user['uid'];
		$param['tagid'] = $this -> _post('tagid');

		if ($user) {
			$data = D("UcIndex") -> addUserTag($param);
		} else {
			$data['status'] = "error";
		}
		$this -> ajaxReturn($data, 'JSON');
	}

	/**
	 * ajax方法：用户删除标签
	 *
	 * @param tagid int 标签id(post参数)
	 *
	 * @return 返回数据(json)：状态status
	 */
	public function ajaxDelUserTag() {
		// 用户信息
		$user = $this -> _user;
		$uid = $user['uid'];
		// 标签id
		$tagid = $this -> _post('tagid');

		if ($user) {
			$data = D("UcIndex") -> deleteUserTag($uid, $tagid);
		} else {
			$data['status'] = "error";
		}
		$this -> ajaxReturn($data, 'JSON');
	}

	/**
	 * ajax方法：用户签到(json数据格式)
	 *
	 * @return 返回数据(json)：状态status
	 */
	public function ajaxAddUserSign() {
		// 用户信息
		$user = $this -> _user;
		if ($user) {
			$data = D("UcIndex") -> addUserSign($user['uid']);
		} else {
			$data['status'] = "login";
			// $data['cout'] = $user['totalsign'];
		}
		$this -> ajaxReturn($data, 'JSON');
	}

	/**
	 * ajax方法：删除动态
	 *
	 * @param id int 动态id(get参数)
	 *
	 * @return 返回数据(json)：状态status
	 */
	public function ajaxDelDynamic() {
		// 动态id
		$id = $this -> _get('id');

		if ($id) {
			$data = D("UcIndex") -> deleteDynamic($id);
		} else {
			$data['status'] = "error";
		}
		$this -> ajaxReturn($data, 'JSON');
	}

	/**
	 * ajax方法：喜欢宠物
	 *
	 * @param id int 宠物id(get参数)
	 *
	 * @return 返回数据(json)：状态status
	 */
	public function ajaxLovePet() {
		// 宠物id
		$id = $this -> _get('id');
		// 登录用户
		$user = $this -> _user;
		if (!$user) {
			$data['status'] = "login";
			$this -> ajaxReturn($data, 'JSON');
		}
		//喜欢宠物
		if ($id) {
			$data = D("UcPets") -> addLoveNum(array('id' => $id, "uid" => $user['uid']));
		} else {
			$data['status'] = "fail";
		}
		$this -> ajaxReturn($data, 'JSON');
	}
	/**
	 ********************* ajax Function End *************************
	 */

	/**
	 * 保存当前用户访问他人首页记录
	 *
	 * @param $uid int 用户id
	 */
	public function logVisit($uid) {
		// 当前登录用户
		$userinfo = $this -> _user;
		if ($userinfo && $uid) {
			$vparam['uid'] = $uid;
			$vparam['visit_uid'] = $userinfo['uid']; //访客

			D("UcIndex") -> addUserVisitor($vparam);
		}
	}

	/**
	 * 个人中心首页(seo)
	 */
	public function indexSeo() {
		if ($this -> _user) {
			$uid = $this -> _user['uid'];
			$url = C('I_DIR') . '/u/' . $uid;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url");
		}
		$indexModel = D('UcIndex');
		$hotThreads = $indexModel -> getHotIndexSeo();
		$this -> assign('hotThreads', $hotThreads);
		$this -> display("indexSeo");
	}
}

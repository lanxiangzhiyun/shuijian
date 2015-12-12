<?php
/**
 * 个人中心首页Model类
 *
 * @created 2012-09-04
 * @author Fongson
 */
class UcIndexModel extends Model {
	// 关闭字段信息的自动检测
	protected $autoCheckFields = false;
	// 动态默认显示一周内的数据（包含今天共7天）
	private $_defaultDisplayTime = 518400; //6 days

	/**
	 * 取得用户的统计数组
	 * 日志数、照片数、微博数、粉丝数、别人回复我的评论数
	 *
	 * @param  $uid int 用户id
	 * 
	 * @return array 统计数组
	 */
	public function getUserCnts($uid) {
		// 总日志数
		$userCnts['diaryCnt'] = D("UcDiary") -> getUserDiaryCnt($uid); 
		// 总照片数
		$userCnts['photoCnt'] = D("UcPhoto") -> getUserPhotoCnt($uid); 
		// 总微博数
		$userCnts['weiboCnt'] = D("UcWeibo") -> getUserWeiboCnt($uid); 
		// 新粉丝数
		$userCnts['newFansCnt'] = D("UcRelation") -> getUserNewFansCnt($uid); 
		// 别人回复我的新评论数
		$userCnts['newCommentCnt'] = $this->_actOthersToOneDynamic($uid);

		return $userCnts;
	} 

	/**
	 * 获取用户的统计数组(ajax方法用)
	 * 新粉丝数、与我有关的评论数、新消息数、新系统通知数
	 *
	 * @param  $uid int 用户id
	 * 
	 * @return array 统计数组
	 */
	public function ajaxGetUserCnts($uid) {
		// 新粉丝数
		$userCnts['fcnt'] = D("UcRelation") -> getUserNewFansCnt($uid); 
		// 别人回复我的新评论数
		$userCnts['ccnt'] = $this->_actOthersToOneDynamic($uid); 
		// 新消息数
		$msgModel = D("UcMsg");
		$userCnts['mcnt'] = $msgModel -> getMsgCount($uid); 
		// 新系统通知数
		$userCnts['ncnt'] = $msgModel -> getNoticeCount($uid);
		if (!$userCnts['fcnt'] && !$userCnts['ccnt'] && !$userCnts['mcnt'] && !$userCnts['ncnt']) {
			$userCnts['status'] = 'no';
		} else {
			$userCnts['status'] = 'ok';
		} 

		return $userCnts;
	} 

	/**
	 * 用户签到
	 * 
	 * @param $uid int 用户id
	 *
	 * @return array 处理结果
	 */
	public function addUserSign($uid) {
		$data = array('uid' => $uid, 'signtime' => time());
		// 是否已签到
		$todayStart = strtotime(date("Y-m-d") . " 00:00:00");
		$sign = M() -> Table("uc_sign") -> where("uid=" . $uid . " AND signtime >= " . $todayStart . " AND signtime <=" . time()) -> find();
		if ($sign) {
			return array("status" => "error");
		} else {
			$id = M() -> Table("uc_sign") -> add($data); 
			// 总签到次数
			$signcnt = M() -> Table("uc_sign") -> where("uid=" . $uid) -> count(); 
			// 啵币奖励(1经验（就是论坛的人气） 10个啵币)
			if ($id) {
				// 签到奖励
				// $this -> execute("UPDATE boqii_users SET extcredits1=extcredits1+1,extcredits2=extcredits2+10 WHERE uid=" . $uid); 
				$this -> execute("UPDATE boqii_users SET extcredits1=extcredits1+1 WHERE uid=" . $uid); 
				//更新用户缓存信息
				// D('Api')->updateUserData($param['uid'], array('extcredits1','extcredits2'));
				D('Api')->updateUserData($param['uid'], array('extcredits1'));

				// 总签到次数
				// $this->execute("UPDATE boqii_users_extend SET totalsign=totalsign+1 WHERE uid=".$uid);
				$this -> execute("UPDATE boqii_users_extend SET totalsign=" . $signcnt . " WHERE uid=" . $uid); 
				//更新用户缓存信息
				D('Api')->updateUserData($param['uid'], array('totalsign'));

				// 记录人气发放记录
				$cparam['uid'] = $uid;
				$cparam['cent_type'] = 1; //人气
				$cparam['cent'] = 1; //人气值
				$this -> _addUserCreditsLog($cparam); 
				// 记录啵币发放记录
				// $cparam['cent_type'] = 2; //啵币
				// $cparam['cent'] = 10; //啵币值
				// $this -> _addUserCreditsLog($cparam);
			} 
			// 当前连续签到数
			$signs = M() -> Table("uc_sign") -> where("uid=" . $uid) -> order("signtime desc") -> field("signtime") -> select();
			$consignTimes = 0;
			$tmpSigntime = 0;
			foreach($signs as $sk => $sval) {
				if ($sk == 0) {
					$tmpSigntime = $sval['signtime'];
				} else {
					// 连续签到
					if (strtotime(date("Y-m-d", $tmpSigntime)) - strtotime(date("Y-m-d", $sval['signtime'])) == 86400) {
						$tmpSigntime = $sval['signtime'];
						$consignTimes++;
					} else {
						break;
					} 
				} 
			} 
			return array("status" => "ok", "cout" => $signcnt, "concout" => $consignTimes);
		} 
	} 

	/**
	 * 用户是否已签到
	 * 
	 * @param  $uid int 用户id
	 * @param  $day string 日期（格式：年-月-日）
	 *
	 * @return boolean 是否已签到
	 */
	public function isUserSigned($uid, $day) {
		if (!$day) {
			$day = date("Y-m-d"); //当天
		} 
		// 用户是否当天签到
		$dayStart = strtotime($day);
		$dayEnd = strtotime($day . " 23:59:59");
		$sign = M() -> Table("uc_sign") -> where("uid=" . $uid . " AND signtime >= " . $dayStart . " AND signtime <= " . $dayEnd) -> find();

		if ($sign) {
			return true;
		}
		return false;
	} 

	/**
	 * 用户签到数据
	 * 
	 * @param $uid int 用户id
	 * 
	 * @return array 返回数组
	 *                      todaySigned 是否签到
	 *                      conSignCnt  当前连续签到天数
	 */
	public function getUserSignData($uid) {
		$day = date("Y-m-d"); //当天
		$num = 0;
		$signs = M() -> Table("uc_sign") -> where("uid=" . $uid . " AND continuous = 0") -> field("signtime") -> order("signtime desc") -> select();
		if ($signs) {
			foreach($signs as $sk => $sv) {
				if ($sk == 0) {
					// 今日签到
					if (date("Y-m-d", $sv['signtime']) == $day) {
						$userSign['todaySigned'] = 1;
						$signTime = $sv['signtime'];
						$num++;
					} else {
						$userSign['todaySigned'] = 0; 
						// 昨天无签到，昨天之前有签到
						if (strtotime($day) - strtotime(date("Y-m-d", $sv['signtime'])) >= 86401) {
							$num = 0;
							break;
						} 
						// 昨天有签到
						else {
							$signTime = $sv['signtime'];
							$num++;
						} 
					} 
				} else {
					// 今日已签到
					if ($userSign['todaySigned'] == 1) {
						// 今日有签到，昨日有签到
						if (strtotime($day) - strtotime(date("Y-m-d", $sv['signtime'])) == 86400) {
							$day = date("Y-m-d", $sv['signtime']); //以昨日记录开始继续计数
							$num++;
						} 
						// 今日有签到，昨日无签到，中断
						else {
							// $num = 1;
							break;
						} 
					} else {
						if (strtotime(date("Y-m-d", $signTime)) - strtotime(date("Y-m-d", $sv['signtime'])) == 86400) {
							$signTime = $sv['signtime'];
							$num++;
						} 
						// 中断
						else {
							break;
						} 
					} 
				} 
			} 
		} else {
			$userSign['todaySigned'] = 0;
			$num = 0;
		} 
		$userSign['conSignCnt'] = $num; 
		// $userSign['conSignCnt'] = $num % 7;
		return $userSign;
	} 

	/**
	 * 取得所有标签信息
	 * 
	 * @param  $uid int 用户id
	 *
	 * @return array 标签信息数组
	 */
	public function getTags($uid) {
		//随机选取12个标签
		$tagList = M() -> Table("uc_tag") -> where("status=0 AND type=1") -> order("RAND()") -> limit(12) -> field("id,name") -> select();
		foreach($tagList as $tk => $tag) {
			$usertag = M() -> Table("uc_user_tag") -> where("uid=" . $uid . " AND tagid=" . $tag['id']) -> field("id") -> find();
			if ($usertag) {
				$tagList[$tk]['select'] = 1;
			} else {
				$tagList[$tk]['select'] = 0;
			} 
		} 
		return $tagList;
	} 

	/**
	 * 取得用户的所有标签信息
	 * 
	 * @param  $uid int 用户id
	 *
	 * @return array 用户标签信息数组
	 */
	public function getUserTagList($uid) {
		$tagList = M() -> Table("uc_user_tag u") -> join("uc_tag t ON u.tagid=t.id") -> where("u.uid=" . $uid . " AND t.status=0  AND t.type=1") -> field("u.id, u.tagid, t.name") -> select();

		return $tagList;
	} 

	/**
	 * 用户添加标签
	 * 
	 * @param  $param array 参数数组
	 *                       uid int 用户id
	 *                      tagid int 标签id
	 *
	 * @return array 处理结果
	 */
	public function addUserTag($param) {
		$tagCnt = M() -> Table("uc_user_tag u") -> join("uc_tag t ON u.tagid=t.id") -> where("u.uid=" . $param['uid'] . " AND t.status=0 AND t.type=1") -> count();
		// 检查用户的标签数是否达到8个
		if ($tagCnt < 8) {
			$userTag = M() -> Table("uc_user_tag") -> where("uid=" . $param['uid'] . " AND tagid=" . $param['tagid']) -> field("id") -> find();
			if ($userTag) {
				// return "error:标签已经存在！";
				return array("status" => "error", "cout" => $tagCnt);
			} else {
				$data['uid'] = $param['uid'];
				$data['tagid'] = $param['tagid']; 
				// 增加标签：使用数+1、资源数+1
				$this -> execute("UPDATE uc_tag SET usetimes=usetimes+1,resourtimes=resourtimes+1 WHERE id=" . $param['tagid']);

				$result = M() -> Table("uc_user_tag") -> add($data);
				if ($result) {
					return array("status" => "ok", "cout" => $tagCnt + 1);
				} else {
					return array("status" => "error", "cout" => $tagCnt);
				} 
			} 
		} else {
			return array("status" => "cout", "cout" => $tagCnt);
		} 
	} 

	/**
	 * 用户删除标签
	 * 
	 * @param  $uid int 用户id
	 * @param  $tagid int 标签id
	 *
	 * @return array 处理结果
	 */
	public function deleteUserTag($uid, $tagid) {
		$result = M() -> Table("uc_user_tag") -> where("uid=" . $uid . " AND tagid=" . $tagid) -> delete(); 
		// 增加标签：资源数-1 TODO
		$tag = M() -> Table("uc_tag") -> where("id=" . $tagid) -> field("resourtimes") -> find();
		if ($tag) {
			$resourtimes = $tag['resourtimes']-1 >= 0 ? $tag['resourtimes']-1 : 0;
			$this -> execute("UPDATE uc_tag SET resourtimes=" . $resourtimes . " WHERE id=" . $tagid);
		} 

		$cnt = M() -> Table("uc_user_tag u") -> join("uc_tag t ON u.tagid=t.id") -> where("u.uid=" . $uid . " AND t.status=0") -> count();

		if ($result) {
			return array("status" => "ok", "cout" => $cnt);
		}

		return array("status" => "error", "cout" => $cnt);

	} 

	/**
	 * 取得所有任务信息
	 * 
	 * @param  $param array 参数数组
	 *                      type int 类型id
	 *                      uid int 用户id
	 *
	 * @return array 任务信息
	 */
	public function getTaskList($param) {
		$where = " 1 ";
		if ($param['type']) {
			$where .= " AND task_type=" . $param['type'];
		} 

		$taskList = M() -> Table("uc_task") -> where($where) -> field("task_id,task_type,task_name,extcredits1, extcredits2,linkurl") -> order("task_type,task_id") -> select();

		foreach($taskList as $tk => $task) {
			if ($task['task_id'] == 1) {
				// 是否已领取奖励，已经领取的不再重复领取
				$userTask = M() -> Table("uc_user_task") -> where("uid=" . $param['uid'] . " AND task_id=" . $task['task_id']) -> field("id") -> find();
				if ($userTask) {
					$taskList[$tk]['rewarded'] = 1;
					$taskList[$tk]['completed'] = 0;
				} else {
					$taskList[$tk]['rewarded'] = 0;
					$taskList[$tk]['completed'] = D("UcPets") -> getUserPetProgress($param['uid']);
					$taskList[$tk]['total'] = 3;
				} 
			} elseif ($task['task_id'] == 2) {
				// 是否已领取奖励已经领取的不再重复领取
				$userTask = M() -> Table("uc_user_task") -> where("uid=" . $param['uid'] . " AND task_id=" . $task['task_id']) -> field("id") -> find();
				if ($userTask) {
					$taskList[$tk]['rewarded'] = 1;
					$taskList[$tk]['completed'] = 0;
				} else {
					$taskList[$tk]['rewarded'] = 0; 
					// 打标签任务
					$tagCnt = M() -> Table("uc_user_tag u") -> join("uc_tag t ON u.tagid=t.id") -> where("u.uid=" . $param['uid'] . " AND t.status=0") -> count();
					$taskList[$tk]['completed'] = $tagCnt;
				} 
				$taskList[$tk]['total'] = 3;
			} elseif ($task['task_id'] == 3) {
				// 可重复签到
				$taskList[$tk]['rewarded'] = 0; 
				// 签到任务
				$signArr = $this -> getUserSignData($param['uid']);
				$taskList[$tk]['completed'] = $signArr['conSignCnt'] >= 7 ? 7 : $signArr['conSignCnt'];
				$taskList[$tk]['total'] = 7;
			} 
		} 

		return $taskList;
	} 

	/**
	 * 领取任务奖励
	 * （每连续签到7天可获一次奖励）
	 *
	 * @param  $param array 参数数组
	 *                      type int 类型id
	 *                      uid int 用户id
	 *
	 * @return array 任务信息
	 */
	public function getTaskRewards($param) {
		//实例化ApiModel类
		$apiModel = D('Api');
		$rewards_info = array();
		$cnt = 0; 
		// 任务列表
		$taskList = $this -> getTaskList($param);
		foreach($taskList as $tk => $task) {
			if ($task['task_id'] == 1 || $task['task_id'] == 2) {
				// 任务完成
				if (($task['completed'] >= $task['total']) && ($task['rewarded'] == 0)) {
					$data['uid'] = $param['uid'];
					$data['task_id'] = $task['task_id'];
					$data['cretime'] = time();
					$res = M() -> Table("uc_user_task") -> add($data);
					if ($res) {
						// 任务奖励
						$this -> execute("UPDATE boqii_users SET extcredits1=extcredits1+" . $task['extcredits1'] . ",extcredits2=extcredits2+" . $task['extcredits2'] . " WHERE uid=" . $param['uid']);
						//更新用户缓存信息
						$apiModel->updateUserData($param['uid'], array('extcredits1','extcredits2'));
					} 
					$rewards[$cnt]['status'] = 'ok';
					if ($task['extcredits1']) {
						$info = "啵币+" . $task['extcredits1'];
					} 
					if ($task['extcredits2']) {
						$info = !empty($info) ? $info . "," . "经验+" . $task['extcredits2'] : "经验+" . $task['extcredits2'];
					} 
					$rewards_info[] = $info;
					$cnt++;
				} 
			} elseif ($task['task_id'] == 3) {
				// 取得连续签到数据
				$signArr = $this -> _getUserContinuousSigns($param['uid']); 
				// 总连续签到次数
				if ($signArr['allconsigns']) {
					for($i = 1; $i <= $signArr['allconsigns']; $i++) {
						$data['uid'] = $param['uid'];
						$data['task_id'] = $task['task_id'];
						$data['cretime'] = time();
						$res = M() -> Table("uc_user_task") -> add($data);
						if ($res) {
							// 任务奖励
							$this -> execute("UPDATE boqii_users SET extcredits1=extcredits1+" . $task['extcredits1'] . ",extcredits2=extcredits2+" . $task['extcredits2'] . " WHERE uid=" . $param['uid']);
							//更新用户缓存信息
							$apiModel->updateUserData($param['uid'], array('extcredits1','extcredits2'));

						} 
						$rewards[$cnt]['status'] = 'ok';
						if ($task['extcredits1']) {
							$info = "<smap>啵币</smap><i>+" . $task['extcredits1'] . "</i>";
						} 
						if ($task['extcredits2']) {
							$info = !empty($info) ? $info . "," . "<smap>经验</smap><i>+" . $task['extcredits2'] . "</i>" : "<smap>经验</smap><i>+" . $task['extcredits2'] . "</i>";;
						} 
						$rewards_info[] = $info;
						$cnt++;
					} 
					// 总连续签到次数
					$this -> execute("UPDATE boqii_users_extend SET signtimes=signtimes+" . $signArr['allconsigns'] . " WHERE uid=" . $param['uid']); 
					//更新用户缓存信息
					$apiModel->updateUserData($param['uid'], array('signtimes'));
					// 更新签到标志
					if ($signArr['interruptedTime']) {
						$this -> execute("UPDATE uc_sign SET continuous=1 WHERE uid=" . $param['uid'] . " AND signtime <= " . $signArr['interruptedTime']);
					} 
				} 
			} 
		} 

		return $rewards_info;
	} 

	/**
	 * 完成任务，领取奖励
	 * 
	 * @param  $param array 参数数组
	 *               uid int 用户id
	 *               task_id int 任务id
	 *
	 * @return boolean 处理结果
	 */
	public function addUserTask($param) {
		$data['uid'] = $param['uid'];
		$data['task_id'] = $param['task_id'];
		$data['cretime'] = time();

		$res = M() -> Table("uc_user_task") -> add($data);
		if ($res) {
			// 任务奖励
			$task = M() -> Table("uc_task") -> where("task_id=" . $param['task_id']) -> field("extcredits") -> find();
			$this -> execute("UPDATE boqii_users SET extcredits2=extcredits2+" . $task['extcredits'] . " WHERE uid=" . $param['uid']);
			//更新用户缓存信息
			D('Api')->updateUserData($param['uid'], array('extcredits2'));

			return true;
		}
		return false;
	} 

	/**
	 * 记录用户访问
	 * 
	 * @param $param array 参数数组
	 *					uid int 用户id
	 *					visit_uid int 访客id
	 *
	 * @return boolean 处理结果
	 */
	public function addUserVisitor($param) {
		$data = array('uid' => $param['uid'], 'visit_uid' => $param['visit_uid'], 'visit_time' => time());
		// 当天的用户访问只记录最后一次
		// 用户当天是否已访问
		$visit = M() -> Table("uc_visitor_log") -> where("uid=" . $param['uid'] ." AND visit_time >= " . strtotime(date("Y-m-d")) . " AND visit_time <= " . time() . " AND visit_uid=" . $param['visit_uid'] ) -> field("id") -> order("visit_time DESC") -> find();
		if ($visit) {
			M() -> Table("uc_visitor_log") -> where("id=" . $visit['id']) -> save($data);
		} else {
			M() -> Table("uc_visitor_log") -> save($data);
		} 
	} 

	/**
	 * 取得用户最近访客信息
	 * 
	 * @param $param array 参数数组
	 *                      $uid int 用户id
	 *                      $num int 显示数量（默认显示12条最近访客信息）
	 *
	 * @return array 访客信息数组
	 */
	public function getUserVisitors($param) {
		$uid = $param['uid'];
		$limit = isset($param['num']) ? intval($param['num']) : 12; 

		$apiModel = D('Api');
		// 访客uid
		$visitUidArr = $this -> query("SELECT visit_uid,visit_time FROM uc_visitor_log WHERE uid=" . $uid . " ORDER BY visit_time DESC"); 
		if (!$visitUidArr) {
			return array();
		}
		
		//访客
		$tmpVisitors = array();
		//访客uid
		$tmpIds = array();
		$num = 0;

		//获取指定数量的访客数据
		foreach($visitUidArr as $vk => $visitor) {
			//剔除重复的访客
			if (!in_array ($visitor['visit_uid'], $tmpIds) && $num < $limit) {
				//存储不重复的访客uid
				$tmpIds[] =$visitor['visit_uid'];
				$tmpVisitors[$num]['format_visit_time'] = format_visit_time($visitor['visit_time']);
				$user = $apiModel->getUserInfo($visitor['visit_uid']);
				$tmpVisitors[$num]['nickname'] = substr_utf8($user['nickname'], 3, 4);
				$tmpVisitors[$num]['avatar'] = $user['avatar_m'];
				$tmpVisitors[$num]['url_link'] = $user['url_link'];
				$num++;
			} 
		} 

		return $tmpVisitors;
	} 

	/**
	 * 获取8条推荐热门话题
	 * 
	 * @return array 热门话题
	 */
	public function getIndexHotThreads() {
		// 显示8条推荐热门话题
		$hotThreads = M() -> Table("uc_pushes p") -> join("bbs_threads t ON p.tid=t.tid") -> where("p.type=1 AND p.valid=1") -> order("p.sort desc, p.postdate desc") -> field("p.content, t.tid, t.subject, t.views") -> limit(8) -> select();

		if ($hotThreads) {
			foreach($hotThreads as $hk => $hotThread) {
				$hotThreads[$hk]['short_subject'] = mysubstr_utf8($hotThread['subject'], 10);
				//第一条热门话题需要显示内容的前25个字
				if ($hk == 0) {
					$hotThreads[$hk]['short_content'] = mysubstr_utf8($hotThread['content'], 25);
				} 
			} 
		} 

		return $hotThreads;
	} 

	/**
	 * index indexSeon 页面获取热门推荐
	 * 获取8条推荐热门话题
	 *
	 * @return array 热门话题
	 */
	public function getHotIndexSeo() {
		// 显示8条推荐热门话题
		$hotThreads = M() -> Table("uc_pushes") -> where("type=1 AND valid=1")-> field("content,uid,postdate,subject")  -> order("sort desc, postdate desc") -> limit(8) -> select();
		$apiModel = D('Api');
		foreach($hotThreads as $key => $val) {
			$userInfo = $apiModel->getUserInfo($val['uid']);
			$hotThreads[$key]['nickname'] = $userInfo['nickname'];
			$hotThreads[$key]['gender'] = $userInfo['gender'];
			$hotThreads[$key]['avatar'] = $userInfo['avatar'];
			$hotThreads[$key]['url_link'] = $userInfo['url_link'];
		} 
		return $hotThreads;
	} 

	/**
	 * 取得最新的一条百科小知识
	 * 
	 * @return array 百科小知识
	 */
	public function getCurrentBaike () {
		$baiKe = M() -> Table("uc_pushes") -> where("type=2 AND valid=1") -> field("content, uid") -> order("id DESC") -> find();
		
		if($baiKe) {
			$user = D('Api')->getUserInfo($baiKe['uid']);
			$baiKe['nickname'] = $user['nickname'];
		}

		return $baiKe;
	} 

	/**
	 * 取得最新的一条或多条系统公告
	 * 
	 * @param  $num int 公告显示数目
	 *
	 * @return array 系统公告数组
	 */
	public function getAnnouncements($num = 2) {
		if ($num == 1) {
			//取得最新的一条公告记录
			$gg = M() -> Table("uc_pushes") -> where("type=3 and valid=1") -> field("subject, linkurl") -> order("postdate desc") -> find();
			$gg['short_subject'] = mysubstr_utf8($gg['subject'], 20);

			return $gg;
		} else {
			// 系统公告
			$gonggaoList = M() -> Table("uc_pushes") -> where("type=3 and valid=1") -> field("subject, linkurl") -> order("postdate desc") -> limit($num) -> select();

			if ($gonggaoList) {
				foreach($gonggaoList as $gk => $gonggao) {
					$gonggaoList[$gk]['short_subject'] = mysubstr_utf8($gonggao['subject'], 20);
				} 
			} 
			return $gonggaoList;
		} 
	} 

	/**
	 * 取得用户动态
	 * 日志动态type=1    发表operatetype=1 评论日志operatetype=2
	 * 相册动态type=2    创建operatetype=1 修改operatetype=2 上传照片operatetype=3 评论照片operatetype=4
	 * 微博动态type=3    发表operatetype=1 转播operatetype=2 评论微博operatetype=3
	 * 话题动态type=4    发帖operatetype=1 回帖operatetype=2 
	 * 评论动态type=5    日志评论回复operatetype=1 照片评论回复operatetype=2 微博评论回复operatetype=3 话题评论回复operatetype=4
	 * 关系动态type=6    关注operatetype=1 好友operatetype=2
	 * 商城动态type=7    收藏商品operatetype=1 购买商品operatetype=2 评价商品operatetype=3
	 *
	 * @param $param array 参数数组
	 *
	 * @return array 动态数据数组
	 */
	public function getUserDynamics($param) {
		//查询指定人的动态
		$uid = $param['uid']; 
		//默认全部动态（1：全部动态；2：好友动态；3：与我有关的动态；5：我发表的评论；6：我收到的回复）
		$type = isset($param['type']) ? $param['type'] :1; 
		//默认显示最近一周内的动态数据 
		$displaytime = isset($param['displaytime']) ? $param['displaytime'] : $this -> _defaultDisplayTime; 
		// where条件
		// 查询最近一周动态
		$where = " d.cretime >= " . (strtotime(date("Y-m-d")) - $displaytime);
		$where .= " AND d.status=0"; 

		$uids = ""; 
		// 全部动态：与我有关的动态、好友动态、我关注的动态、我的动态（评论、关注和好友）
		if ($type == 1) {
			$where .= " AND ("; 
			// 我的关注、好友动态、我的评论动态
			$where .= " (d.uid=" . $uid . " )" . ""; 
			// 与我有关的动态
			$where .= " OR (";
			$where .= " (d.ouid=" . $uid . " AND ( (d.type=1 AND d.operatetype=2) OR (d.type=2 AND d.operatetype=4) OR (d.type=3 AND d.operatetype=3) OR (d.type=4 AND d.operatetype=2) OR (d.type=8 AND d.operatetype IN (4,5)) OR (d.type=5 ) OR d.type=6))";
			$where .= " )"; 
			// 我的好友和我关注的人动态
			// 好友的动态
			$friends = M() -> Table("uc_friend_relative f") -> join("uc_friend_relative r ON r.attention_uid=f.uid") -> where("f.uid=" . $uid . " AND f.status=0 AND r.status=0 AND f.isrelation=1 AND r.isrelation = 1") -> field("f.attention_uid") -> select();
			if ($friends) {
				foreach($friends as $friend) {
					if (strpos($uids, $friend['attention_uid']) === false) {
						$uids = empty($uids) ? $friend['attention_uid'] : $uids . ',' . $friend['attention_uid'];
					} 
				} 
			} 
			// 我关注的人动态
			$attentions = M() -> Table("uc_friend_relative") -> where("uid=" . $uid . " AND status=0 AND isrelation = 0") -> field("attention_uid") -> select();
			if ($attentions) {
				foreach($attentions as $attention) {
					if (strpos($uids, $attention['attention_uid']) === false) {
						$uids = empty($uids) ? $attention['attention_uid'] : $uids . ',' . $attention['attention_uid'];
					} 
				} 
			} 
			if ($uids) {
				$where .= " OR d.uid IN (" . $uids . ") ";
			} 
			$where .= " )";
		} 
		// 好友动态：好友的发表日志、上传图片、更新微博、在论坛发帖回帖、发表评论及关注别人、与别人成为好友的动态信息，且我评论好友归类在好友的好友动态中
		elseif ($type == 2) {
			// 好友动态
			$friends = M() -> Table("uc_friend_relative f") -> join("uc_friend_relative r ON r.attention_uid=f.uid  AND r.uid=f.attention_uid") -> where("f.uid=" . $uid . " AND f.status=0 AND r.status=0") -> field("f.attention_uid") -> select();
			if ($friends) {
				foreach($friends as $friend) {
					$uids = empty($uids) ? $friend['attention_uid'] : $uids . ',' . $friend['attention_uid'];
				} 

				if ($uids) {
					// $where .= " AND (d.uid IN (". $uids . ") OR (d.uid=". $uid . " AND (d.ouid IN (" . $uids . ")) AND ((d.type=1 AND d.operatetype=2)  OR (d.type=2 AND d.operatetype=4) OR (d.type=3 AND d.operatetype=3))  ) ) ";
					$where .= " AND (d.uid IN (" . $uids . ")  AND ((d.type = 6 AND d.ouid != " . $uid . ") OR d.type != 6 )) ";
				} 
			} else {
				return array();
			}
		} 
		// 与我有关的动态：展示关于我的动态内容，包括关注别人、被别人关注、与别人成为好友、别人评论我的内容，别人评论（我的内容）归类在我的动态中，我评论别人，如我与对方是好友，则显示在对方的好友动态中，如我与对方是陌生人，则显示在对方的全部动态中；
		// 与我有关的动态：关注别人、被别人关注、与别人成为好友、别人评论我的内容
		elseif ($type == 3) {
			$where .= " AND (";
			$where .= "(" . "(d.uid=" . $uid . " OR d.ouid=" . $uid . ")" . " AND d.type=6 " . ")";
			$where .= " OR";
			$where .= " (d.ouid=" . $uid . " AND ( (d.type=1 AND d.operatetype=2) OR (d.type=2 AND d.operatetype=4) OR (d.type=3 AND d.operatetype=3) OR (d.type=4 AND d.operatetype=2) OR (d.type=8 AND d.operatetype IN (4,5)) OR (d.type=5 ) ))";
			$where .= " )";
			
		} 
		// 我发表的评论动态
		elseif ($type == 5) {
			$where .= " AND (";
			$where .= " (d.uid=" . $uid . ") AND ( (d.type=1 AND d.operatetype=2) OR (d.type=2 AND d.operatetype=4) OR (d.type=3 AND d.operatetype=3) OR (d.type=4 AND d.operatetype=2) OR (d.type=8 AND d.operatetype  IN (4,5)) OR (d.type=5 ) )";
			$where .= " )";
		} 
		// 我收到的回复动态
		elseif ($type == 6) {
			$where .= " AND (";
			$where .= " (d.ouid=" . $uid . ") AND ( (d.type=1 AND d.operatetype=2) OR (d.type=2 AND d.operatetype=4) OR (d.type=3 AND d.operatetype=3) OR (d.type=4 AND d.operatetype=2) OR (d.type=8 AND d.operatetype  IN (4,5)) OR (d.type=5 ) )";
			$where .= " )";
		} 
		// 我的动态
		else {
			$where .= " AND d.uid = " . $uid;
		} 
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:15;
		$page_start = ($page-1) * $page_num;
		// 限制显示时间
		// $where .= ' and d.cretime <= '.strtotime('2015-08-06 00:00:00');
		$this -> total = M() -> Table("uc_dynamic d") -> where($where) -> count(); 
		// 根据条件查询动态
		$dynamics = M() -> Table("uc_dynamic d") -> where($where) -> field("d.id, d.uid, d.type, d.ouid, d.ousername, d.operatetype, d.oid, d.otitle, d.mid, d.cretime, d.status") -> order("d.cretime DESC") -> limit("$page_start, $page_num") -> select(); 
		// 查询不到动态结果时返回空
		if (!$dynamics) {
			return array();
		} 
		$apiModel = D('Api');
		$relationModel = D('UcRelation');
		// 合并最近一小时内的部分动态
		$onehour = time() - 3600;
		foreach($dynamics as $dk => $dynamic) {
			// 操作者昵称
			$user =  $apiModel -> getUserInfo($dynamic['uid']);
			$dynamics[$dk]['user'] = $user;
			if ($dynamic['ouid']) {
				$ouser = $apiModel -> getUserInfo($dynamic['ouid']);
				$dynamics[$dk]['ouser'] = $ouser;
			} 
			if($type == 5 || $type == 6) {
				$status = $relationModel->getSearchStatus($dynamic['ouid'], $dynamic['uid']);
				$dynamics[$dk]['relation_status'] = $status;
			}
			// 动态日期（格式化日期）
			$dynamics[$dk]['format_dynamic_time'] = format_dynamic_time($dynamic['cretime']); 
			// 日志动态
			if ($dynamic['type'] == 1) {
				// 发表日志
				if ($dynamic['operatetype'] == 1) {
					// 日志
					$diary = M() -> Table("uc_diary") -> where("id=" . $dynamic['oid']) -> field("id,title,content,views,comments") -> find();
					$dynamics[$dk]['data'] = $diary;
					$dynamics[$dk]['dtype'] = 11; //发表日志
					$dynamics[$dk]['operate_desc'] = "发表宠物日志"; //操作描述
					$dynamics[$dk]['operate_title'] = $diary['title'];
					$dynamics[$dk]['linkurl'] = get_rewrite_url('UcDiary', 'diary', $diary['id']); //链接地址
					$fc = $this -> _formatContent($diary['content']);
					$dynamics[$dk]['desc'] = mysubstr_utf8($fc['content'], 100); //动态描述
					$dynamics[$dk]['has_first_pic'] = $fc['has_first_pic']; //是否包含缩略图 
					$dynamics[$dk]['first_pic'] = $fc['first_pic'];
					$dynamics[$dk]['first_pic_s'] = str_replace("_y", "_s", $fc['first_pic']);
				} 
				// 评论日志
				elseif ($dynamic['operatetype'] == 2) {
					// 日志
					$diary = M() -> Table("uc_diary_comment c") -> join("uc_diary d ON c.diaryid=d.id") -> where("c.id=" . $dynamic['oid']) -> field("d.id,d.title,d.content,d.views,d.comments,c.content AS comment,c.dateline,c.id AS cid") -> find();
					$dynamics[$dk]['data'] = $diary;
					$dynamics[$dk]['dtype'] = 12; //评论日志
					$dynamics[$dk]['operate_title'] = $diary['title'];
					$dynamics[$dk]['desc'] = $diary['comment'] ;
					$dynamics[$dk]['linkurl'] = get_rewrite_url('UcDiary', 'diary', $diary['id']); //链接地址
					if ($type == 5) {
						// 我的日志被评论
						if ($dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "评论了我的日志"; //操作描述
						} 
						// 我评论了日志
						else {
							$dynamics[$dk]['operate_desc'] = "评论了日志"; //操作描述
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在日志"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新评论";
					} 
				} 
			} 
			// 相册动态
			elseif ($dynamic['type'] == 2) {
				// 创建相册||修改相册
				if ($dynamic['operatetype'] == 1 || $dynamic['operatetype'] == 2) {
					// 相册
					$album = M() -> Table("uc_album") -> where("id=" . $dynamic['oid']) -> field("id,title,content") -> find();
					$dynamics[$dk]['data'] = $album;
					if ($dynamic['operatetype'] == 1) {
						$dynamics[$dk]['dtype'] = 21; //创建相册
						$dynamics[$dk]['operate_desc'] = "创建了新相册"; //操作描述
						$dynamics[$dk]['operate_title'] = $album['title'];
					} else {
						$dynamics[$dk]['dtype'] = 22; //修改相册
						$dynamics[$dk]['operate_desc'] = "修改了相册"; //操作描述
						$dynamics[$dk]['operate_title'] = $album['title'];
					} 
					$dynamics[$dk]['linkurl'] = get_rewrite_url('UcAlbum', 'photoList', $album['id']); //相册链接地址
				} 
				// 照片上传
				elseif ($dynamic['operatetype'] == 3) {
					// 照片
					$photoList = M() -> Table("uc_photo p") -> where("p.photo_id IN (" . $dynamic['oid'] . ")") -> field("p.photo_id,p.photo_path,p.album_id,p.uid,p.photo_name,p.photo_desc,p.is_cover,p.cretime,p.updatetime,p.views,p.comments,p.status") -> select();
					$dynamics[$dk]['dtype'] = 23; //上传照片
					$dynamics[$dk]['data'] = $photoList; 
					// 照片
					$photocnt = count($photoList);
					if ($photocnt == 1) {
						// $dynamics[$dk]['data']['comments'] = $photoList[0]['comments'];
						$dynamics[$dk]['picurl'] = get_rewrite_url('UcAlbum', 'photoshow', $photoList[0]['photo_id']); //图片链接地址
					} 
					$dynamics[$dk]['photo_num'] = $photocnt;
					$photoPaths = array();
					foreach($photoList as $photo) {
						if (count($photoPaths) < 3) {
							$photoPaths[$photo['photo_id']] = str_replace("_y", "_b", $photo['photo_path']);
						} 
					} 
					$dynamics[$dk]['photo_paths'] = $photoPaths;
					$dynamics[$dk]['operate_desc'] = "上传了" . $photocnt . "张新照片至相册"; //操作描述
					$album = M() -> Table("uc_album") -> where("id=" . $dynamic['mid']) -> field("id,title") -> find();
					$albumurl = get_rewrite_url('UcAlbum', 'photoList', $album['id']); //相册链接地址
					if ($photocnt > 1) {
						$dynamics[$dk]['picurl'] = $albumurl;
					} 
					$dynamics[$dk]['operate_title'] = "<a href='" . $albumurl . "' target='_blank'>" . $album['title'] . "</a>";
					$dynamics[$dk]['linkurl'] = $albumurl;
				} 
				// 照片评论
				elseif ($dynamic['operatetype'] == 4) {
					// 相册
					$photo = M() -> Table("uc_photo_comment c") -> join("uc_photo p ON c.photo_id=p.photo_id") -> where("c.id=" . $dynamic['oid']) -> field("p.photo_id,p.photo_path,p.photo_name,p.views,p.comments,p.album_id,c.content AS comment,c.dateline,c.id AS cid") -> find();
					$dynamics[$dk]['data'] = $photo;
					$dynamics[$dk]['dtype'] = 24; //照片评论
					$dynamics[$dk]['desc'] = $photo['comment'];
					$dynamics[$dk]['first_pic'] = $photo['photo_path'] ;
					$dynamics[$dk]['first_pic_b'] = str_replace("_y", "_b", $photo['photo_path']) ;
					$dynamics[$dk]['linkurl'] = get_rewrite_url('UcAlbum', 'photoshow', $photo['photo_id']); //图片链接地址
					if ($type == 5 || $type == 6) {
						// 我的照片被评论
						if ($type == 6 && $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "评论了我的照片"; //操作描述
						} 
						// 我评论了照片
						else {
							$dynamics[$dk]['operate_desc'] = "评论了"; //操作描述
							$dynamics[$dk]['operate_title'] = "<a href='" . $ouser['url_link'] . "' target='_blank'>" . $ouser['nickname'] . "</a>" ;
							$dynamics[$dk]['operate_desc_suffix'] = "的照片"; //操作描述
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "发表了照片评论"; //操作描述
					} 
				} 
			} 
			// 微博动态
			elseif ($dynamic['type'] == 3) {
				//实例化微博Model
				$weiboModel = D('UcWeibo');
				//Redis缓存
				$cacheRedis = Cache::getInstance('Redis');
				// 发表微博：[XX]：微博内容 发表时间
				if ($dynamic['operatetype'] == 1) {
					$dynamics[$dk]['dtype'] = 31; //发表微博  
					$weibo = $weiboModel->getWeiboData($dynamic['oid']);
					// 微博已删除
					if ($weibo['status'] == 1) {
						$dynamics[$dk]['status'] = -1;
					} 
					// 微博评论
					$weibo['commentlist'] = $weiboModel -> getRecentComments(array("wid" => $dynamic['oid'], "uid" => $uid)); 
					// 登录用户是否已转播该微博
					$weibo['recast'] = 0;//对于未登录用户，不显示“已转播”
					if ($param['loginuid']) {
						// 初始化转发此微博的用户
						$weiboModel->getWeiboRelayInitialize($weibo['id']);
						// 读取缓存
						$redisKey = C('REDIS_KEY.weibo').$weibo['id'];
						$uidArray = $cacheRedis->zGetByIndexDesc($redisKey);
						if(in_array($param['loginuid'], $uidArray)){
							$weibo['recast'] = 1;
						} else{
							$weibo['recast'] = 0;
						}
					}
					$dynamics[$dk]['odata'] = $weibo;
					$dynamics[$dk]['operate_desc'] = "发表啊呜"; //操作描述
					$dynamics[$dk]['desc'] = remove_xss($weibo['content']);
					$dynamics[$dk]['has_first_pic'] = $weibo['pic_path'] ? 1 : 0;
					if ($weibo['pic_path']) {
						$dynamics[$dk]['first_pic'] = $weibo['pic_path'] ;
						$dynamics[$dk]['first_pic_b'] = str_replace("_y", "_b", $weibo['pic_path']) ;
						$dynamics[$dk]['first_pic_s'] = str_replace("_y", "_s", $weibo['pic_path']) ;
					}
					$dynamics[$dk]['linkurl'] = "";
				} 
				// 转播微博：[XX]转播微博：微博原文（@XX 微博内容） 发表时间
				elseif ($dynamic['operatetype'] == 2) {
					$dynamics[$dk]['dtype'] = 32; //微博转播  
					// 原微博
					$oweibo = $weiboModel->getWeiboData($dynamic['mid']);
					$oweibo['commentlist'] = $weiboModel -> getRecentComments(array("wid" => $dynamic['mid'], "uid" => $uid));
					if ($oweibo['pic_path']) {
						$oweibo['pic_path_b'] = str_replace("_y", "_b", $oweibo['pic_path']) ;
						$oweibo['pic_path_s'] = str_replace("_y", "_s", $oweibo['pic_path']) ;
					} 
					// 是否允许登录用户转播
					if ($oweibo['uid'] == $uid) {
						$oweibo['allowrecast'] = 0;
					} else {
						$oweibo['allowrecast'] = 1;
					} 
					// 登录用户是否已转播
					$oweibo['recast'] = 0;//对于未登录用户，不显示“已转播”
					if ($param['loginuid']) {
						//初始化转发此微博的用户
						$weiboModel->getWeiboRelayInitialize($oweibo['id']);
						//读取缓存
						$redisKey = C('REDIS_KEY.weibo').$oweibo['id'];
						$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
						if(in_array($param['loginuid'],$uidArrayTwo)){
							$oweibo['recast'] = 1;
						} else{
							$oweibo['recast'] = 0;
						}
					} 
					$dynamics[$dk]['odata'] = $oweibo; 
					// 转播的微博
					$weibo = $weiboModel->getWeiboData($dynamic['oid']);
					$weibo['commentlist'] = $weiboModel -> getRecentComments(array("wid" => $dynamic['oid'], "uid" => $uid));
					// 登录用户是否已转播
					$weibo['recast'] = 0;//对于未登录用户，不显示“已转播”
					if ($param['loginuid']) {
						//初始化转发此微博的用户
						$weiboModel->getWeiboRelayInitialize($dynamic['oid']);
						//读取缓存
						$redisKey = C('REDIS_KEY.weibo').$dynamic['oid'];
						$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
						if(in_array($param['loginuid'],$uidArrayTwo)){
							$weibo['recast'] = 1;
						}else{
							$weibo['recast'] = 0;
						}
					} 
					$dynamics[$dk]['data'] = $weibo;
					$dynamics[$dk]['operate_desc'] = "转播啊呜"; //操作描述
					$dynamics[$dk]['linkurl'] = ""; 
				} 
				// 评论微博：[XX]：评论内容+原文（@XX 内容 发表时间）
				elseif ($dynamic['operatetype'] == 3) {
					// 微博
					$oweibo = $weiboModel->getWeiboData($dynamic['mid']);
					// 微博评论
					$oreply = $weiboModel->getWeiboReplyData($dynamic['oid']);
					$oweibo['message'] = $oreply['message'];
					if ($oweibo['pic_path']) {
						$oweibo['pic_path_b'] = str_replace("_y", "_b", $oweibo['pic_path']) ;
						$oweibo['pic_path_s'] = str_replace("_y", "_s", $oweibo['pic_path']) ;
					} 
					// 是否允许登录用户转播
					if ($oweibo['uid'] == $uid) {
						$oweibo['allowrecast'] = 0;
					} else {
						$oweibo['allowrecast'] = 1;
					} 
					// 登录用户是否已转播
					$oweibo['recast'] = 0;//对于未登录用户，不显示“已转播”
					if ($param['loginuid']) {
						//初始化转发此微博的用户
						$weiboModel->getWeiboRelayInitialize($dynamic['mid']);
						//读取缓存
						$redisKey = C('REDIS_KEY.weibo').$dynamic['mid'];
						$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
						if(in_array($param['loginuid'],$uidArrayTwo)){
							$oweibo['recast'] = 1;
						}else{
							$oweibo['recast'] = 0;
						}
					}
					$dynamics[$dk]['odata'] = $oweibo;
					$dynamics[$dk]['wid'] = $oweibo['id']; //评论微博id
					$dynamics[$dk]['dtype'] = 33; //微博评论
					$dynamics[$dk]['desc'] = $oweibo['message'] ;
					$dynamics[$dk]['linkurl'] = "";

					if ($type == 5) {
						// 我的啊呜被评论
						if ($dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "评论我的啊呜"; //操作描述
						} 
						// 我评论别人的啊呜
						if ($dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "评论"; //操作描述
							$dynamics[$dk]['operate_title'] = "<a href='" . $ouser['url_link'] . "' target='_blank'>" . $ouser['nickname'] . "</a>" ;
							$dynamics[$dk]['operate_desc_suffix'] = "的啊呜"; //操作描述
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "评论啊呜"; //操作描述
					} 
				} 
			} 
			// 话题动态
			elseif ($dynamic['type'] == 4) {
				// 发帖
				if ($dynamic['operatetype'] == 1) {
					// 主题
					$thread = M() -> Table("bbs_threads t") -> join("bbs_posts p ON t.tid=p.tid AND p.first=1") -> where("t.tid=" . $dynamic['oid']) -> field("t.tid, t.subject as title, p.message as content,t.replies as comments ") -> find();
					$dynamics[$dk]['data'] = $thread;
					$dynamics[$dk]['dtype'] = 41; //发表话题
					$dynamics[$dk]['operate_desc'] = "发表了新话题"; //操作描述
					$dynamics[$dk]['operate_title'] = $thread['title']; //标题
					$dynamics[$dk]['linkurl'] = C("BBS_DIR") . "/content/viewthread-" . $thread['tid'] . ".html"; //链接地址
					$fc = $this -> _formatContent($thread['content']);
					$dynamics[$dk]['desc'] = mysubstr_utf8($fc['content'], 100); //动态描述
					$dynamics[$dk]['has_first_pic'] = $fc['has_first_pic']; //是否包含缩略图 
					$dynamics[$dk]['first_pic'] = $fc['first_pic'];
					$dynamics[$dk]['first_pic_s'] = str_replace("_y", "_s", $fc['first_pic']);
				} 
				// 回帖
				elseif ($dynamic['operatetype'] == 2) {
					// 帖子
					$post = M() -> Table("bbs_threads t") -> join("bbs_posts p ON t.tid=p.tid") -> where("p.pid=" . $dynamic['oid']) -> field("t.tid, t.subject as title, p.authorid, p.message as content, t.replies as comments ") -> find();
					$dynamics[$dk]['data'] = $post;
					$dynamics[$dk]['dtype'] = 42; //主题回帖
					$dynamics[$dk]['operate_title'] = $post['title'];
					$dynamics[$dk]['linkurl'] = C("BBS_DIR") . "/content/viewthread-" . $post['tid'] . ".html"; //链接地址
					$fc = $this -> _formatContent($post['content']);
					$dynamics[$dk]['desc'] = mysubstr_utf8($fc['content'], 100); //动态描述
					$dynamics[$dk]['has_first_pic'] = 0; //$fc['has_first_pic'];//是否包含缩略图 
					if ($type == 5 || $type == 6) {
						// 我回复了话题
						if ($type == 5 && $dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了话题"; //操作描述
						} 
						// 回复了我的话题
						if ($type == 6 && $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我的话题";
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在帖子"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新回帖 ";
					} 
				} 
			} 
			// 评论动态
			elseif ($dynamic['type'] == 5) {
				// 日志评论回复([XX]在[日志标题]中发表了新评论\n@[XX]:[回复内容]\r[发表时间])
				if ($dynamic['operatetype'] == 1) {
					// 日志评论
					//$comment = M() -> Table("uc_diary_comment c") -> join("uc_diary_comment pc ON c.commentid=pc.id") -> join("uc_diary d ON c.diaryid=d.id") -> where("c.id=" . $dynamic['oid']) -> field("c.content, c.dateline, c.uid, c.diaryid, pc.content as ccontent, pc.uid as puid, d.title, d.comments") -> find();
					$comment = M() -> Table("uc_diary_comment c") -> join("uc_diary d ON c.diaryid=d.id") -> where("c.id=" . $dynamic['oid']) -> field("c.content, c.dateline, c.uid, c.diaryid, d.title, d.comments") -> find();
					$dynamics[$dk]['data'] = $comment; //日志评论
					$dynamics[$dk]['dtype'] = 51; //日志评论回复
					$dynamics[$dk]['operate_title'] = $comment['title']; //标题
					$dynamics[$dk]['desc'] = "<a href='" . $ouser['url_link'] . "' target='_blank'> @" . $ouser['nickname'] . "</a>: " . $comment['content'] ; //回复内容 
					$dynamics[$dk]['linkurl'] = get_rewrite_url("UcDiary", "diary", $comment['diaryid']); //链接地址
					$dynamics[$dk]['has_first_pic'] = 0; //是否包含缩略图 
					if ($type == 5 || $type == 6) {
						// 我的日志评论被回复
						if ($type == 6  && $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我在日志"; //操作描述
							$dynamics[$dk]['operate_desc_suffix'] = "的评论"; //操作描述
						} 
						// 我回复的日志评论
						if ($type == 5 && $dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了在日志"; //操作描述 
							$dynamics[$dk]['operate_desc_suffix'] = "的评论"; //操作描述
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在日志"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新评论 ";
					} 
				} 
				// 照片评论回复
				elseif ($dynamic['operatetype'] == 2) {
					// 照片评论回复([XX]发表了新评论\n@[XX]:[回复内容]\r[发表时间])
					//$comment = M() -> Table("uc_photo_comment c") -> join("uc_photo_comment pc ON c.commentid=pc.id") -> join("uc_photo p ON c.photo_id=p.photo_id") -> where("c.id=" . $dynamic['oid']) -> field("c.content, c.photo_id, p.photo_name, p.photo_path,p.comments, p.album_id, pc.uid, p.uid as puid") -> find();
					$comment = M() -> Table("uc_photo_comment c") -> join("uc_photo p ON c.photo_id=p.photo_id") -> where("c.id=" . $dynamic['oid']) -> field("c.content, c.photo_id, p.photo_name, p.photo_path,p.comments, p.album_id") -> find();
					$dynamics[$dk]['data'] = $comment; //照片评论
					$dynamics[$dk]['dtype'] = 52; //照片评论回复
					$dynamics[$dk]['desc'] = "<a href='" . $ouser['url_link'] . "' target='_blank'> @" . $ouser['nickname'] . "</a>: " . $comment['content'] ; //回复内容 
					$dynamics[$dk]['first_pic'] = $comment['photo_path']; //照片
					$dynamics[$dk]['first_pic_b'] = str_replace("_y", "_b", $comment['photo_path']) ;
					$dynamics[$dk]['linkurl'] = get_rewrite_url('UcAlbum', 'photoshow', $comment['photo_id']); //图片链接地址
					if ($type == 5 || $type == 6 ) {
						// 我的照片评论被回复
						if ($type == 6  && $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我在照片的评论"; //操作描述
						} 
						// 我回复的照片评论
						if ($type == 5 && $dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了"; //操作描述
							$dynamics[$dk]['operate_title'] = "<a href='" . $ouser['url_link'] . "' target='_blank'>" . $ouser['nickname'] . "</a>" ;
							$dynamics[$dk]['operate_desc_suffix'] = "对照片的评论"; //操作描述
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "发表了新评论"; //操作描述
					} 
				} 
				// 微博评论回复
				elseif ($dynamic['operatetype'] == 3) {
					$dynamics[$dk]['dtype'] = 53; //微博评论回复  
					$weiboModel = D('UcWeibo');
					// 评论
					$comment = $weiboModel -> getWeiboComment($dynamic['oid'], "", 0); 
					// 微博
					$oweibo = $weiboModel -> getWeiboData($dynamic['mid']);
					if ($oweibo['pic_path']) {
						$oweibo['pic_path_b'] = str_replace("_y", "_b", $oweibo['pic_path']) ;
						$oweibo['pic_path_s'] = str_replace("_y", "_s", $oweibo['pic_path']) ;
					} 
					// 是否允许登录用户转播
					if ($oweibo['uid'] == $uid) {
						$oweibo['allowrecast'] = 0;
					} else {
						$oweibo['allowrecast'] = 1;
					} 
					// 登录用户是否已转播
					$oweibo['recast'] = 0;//对于未登录用户，不显示“已转播”
					if ($param['loginuid']) {
						//初始化转发此微博的用户
						$cacheRedis = Cache::getInstance('Redis');
						$weiboModel->getWeiboRelayInitialize($dynamic['mid']);
						//读取缓存
						$redisKey = C('REDIS_KEY.weibo').$dynamic['mid'];
						$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
						if(in_array($param['loginuid'],$uidArrayTwo)){
							$oweibo['recast'] = 1;
						}else{
							$oweibo['recast'] = 0;
						}
					} 
					$dynamics[$dk]['odata'] = $oweibo; //微博评论
					$dynamics[$dk]['wid'] = $oweibo['id']; //评论微博id
					if ($type == 5 || $type == 6 ) {
						// 我的啊呜评论被回复
						if ($type == 6 || $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复我的评论"; //操作描述
						} 
						// 我回复别人的啊呜评论
						if ($type == 5 || $dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复"; //操作描述
							$dynamics[$dk]['operate_title'] = "<a href='" . $ouser['url_link'] . "' target='_blank'>" . $ouser['nickname'] . "</a>" ;
							$dynamics[$dk]['operate_desc_suffix'] = "的评论"; //操作描述
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "评论啊呜"; //操作描述
					} 
					$dynamics[$dk]['desc'] = $comment; 
					$dynamics[$dk]['linkurl'] = ""; //链接地址
				} 
				// 帖子回复（引用）
				elseif ($dynamic['operatetype'] == 4) {
					// 帖子
					$post = M() -> Table("bbs_posts p") -> join("bbs_threads t ON t.tid=p.tid") -> where("p.pid=" . $dynamic['oid']) -> field("p.pid,t.tid, t.subject as title, p.message as content, t.replies as comments ") -> find();
					$dynamics[$dk]['data'] = $post;
					$dynamics[$dk]['dtype'] = 54; //帖子回复
					$dynamics[$dk]['operate_title'] = $post['title'];
					$dynamics[$dk]['linkurl'] = C("BBS_DIR") . "/content/viewthread-" . $post['tid'] . ".html"; //链接地址
					$reply = $this -> _praseBbsPostData($post['content']);
					$dynamics[$dk]['desc'] = "<a href='" . $ouser['url_link'] . "' target='_blank'> @" . $ouser['nickname'] . "</a>: " . mysubstr_utf8($reply, 100);
					if ($type == 5) {
						if ($dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了在"; //操作描述
							$dynamics[$dk]['operate_desc_suffix'] = "的回帖 ";
						} 
						if ($dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我在"; //操作描述
							$dynamics[$dk]['operate_desc_suffix'] = "的回帖 ";
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在帖子"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新回帖 ";
					} 
				} 
				// 百科帖子评论回复
				elseif ($dynamic['operatetype'] == 5) {
					// 回复
					$reply = M() -> Table("bk_thread_comment c") -> join("bk_thread t ON t.id=c.thread_id") -> where("c.id=" . $dynamic['oid']) -> field("t.id, c.content,t.title,t.comment_num as comments") -> find();
					$dynamics[$dk]['data'] = $reply;
					$dynamics[$dk]['dtype'] = 55; //帖子回复
					$dynamics[$dk]['operate_title'] = $reply['title'];
					$dynamics[$dk]['linkurl'] = get_rewrite_url("BkThread", "thread", $reply['id']); //帖子链接地址  
					$tcomment = $this -> _praseBkThreadCommentData($reply['content'], 1); 
					$dynamics[$dk]['desc'] = "<a href='" . $ouser['url_link'] . "' target='_blank'> @" . $ouser['nickname'] . "</a>: " . mysubstr_utf8($tcomment, 100);
					if ($type == 5) {
						if ($dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了在百科"; //操作描述
							$dynamics[$dk]['operate_desc_suffix'] = "的回帖 ";
						} 
						if ($dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我在百科"; //操作描述
							$dynamics[$dk]['operate_desc_suffix'] = "的回帖 ";
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在百科帖子"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新回帖 ";
					} 
				} 
				// 百科文章评论回复
				elseif ($dynamic['operatetype'] == 6) {
					// 回复
					$reply = M() -> Table("bk_article_comment c") -> join("bk_article a ON a.id=c.article_id") -> where("c.id=" . $dynamic['oid']) -> field("a.id, c.content,a.title,a.comment_num as comments") -> find();
					$dynamics[$dk]['data'] = $reply;
					$dynamics[$dk]['dtype'] = 56; //帖子回复
					$dynamics[$dk]['operate_title'] = $reply['title'];
					$dynamics[$dk]['linkurl'] = get_rewrite_url("BkArticle", "article", $reply['id']); //帖子链接地址
					$dynamics[$dk]['desc'] = "<a href='" . $ouser['url_link'] . "' target='_blank'> @" . $ouser['nickname'] . "</a>: " . mysubstr_utf8($reply['content'], 100);
					if ($type == 5 && $type == 6) {
						//回复了百科文章评论
						if ($type == 5 && $dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了在百科文章"; //操作描述
							$dynamics[$dk]['operate_desc_suffix'] = "的评论 ";
						} 
						//百科文章评论被回复
						if ($type == 6 && $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我在百科文章"; //操作描述
							$dynamics[$dk]['operate_desc_suffix'] = "的评论 ";
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在百科文章"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新评论 ";
					} 
				} 
			} 
			// 关注动态（需要合并）
			elseif ($dynamic['type'] == 6) {
				// 被关注用户
				$dynamics[$dk]['data'] = $ouser; 
				// 关注
				if ($dynamic['operatetype'] == 1) {
					$dynamics[$dk]['dtype'] = 61; //关注动态（加关注）
					$dynamics[$dk]['operate_desc'] = "关注了"; //操作描述
					if ($param['type'] == 3 && $ouser['uid'] == $param['loginuid']) {
						$dynamics[$dk]['operate_title'] = "<span style='color:#A8A8A8'>我</span>";
					} else {
						$dynamics[$dk]['operate_title'] = $ouser['nickname'];
						$dynamics[$dk]['linkurl'] = $ouser['url_link']; //链接地址
					} 
				} 
				// 好友
				elseif ($dynamic['operatetype'] == 2) {
					$dynamics[$dk]['dtype'] = 62; //好友动态（加好友）
					$dynamics[$dk]['operate_desc'] = "和"; //操作描述
					if ($param['type'] == 3 && $ouser['uid'] == $param['loginuid']) {
						$dynamics[$dk]['operate_title'] = "<span style='color:#A8A8A8'>我</span>";
					} else {
						$dynamics[$dk]['operate_title'] = "<a href='" . $ouser['url_link'] . "' target='_blank'> " . $ouser['nickname'] . "</a>";
					} 
					$dynamics[$dk]['operate_desc_suffix'] = "成为好友";
				} 
			} 
			// 商城动态
			elseif ($dynamic['type'] == 7) {
				// 收藏商品动态
				if ($dynamic['operatetype'] == 1) {
					$dynamics[$dk]['dtype'] = 71; //商品动态（收藏商品）
					$dynamics[$dk]['operate_desc'] = "收藏了商品"; //操作描述  
					// 收藏商品71：商品id、商品名称、满意分值、商品评价数；
					$arr = $this -> getShopDynamic(71, $dynamic['oid']);
					$goods = array("id" => $arr[0], "pname" => $arr[1], "score" => $arr[2], "comments" => $arr[3]);
					if ($goods['score']) {
						if ($goods['score'] == 1) {
							$goods['pscore'] = floor($goods['score'] / 5.0 * 100);
						} else {
							$goods['pscore'] = 100;
						} 
					} else {
						$goods['pscore'] = 0;
					} 
					$dynamics[$dk]['data'] = $goods;
					$producturl = C("SHOP_DIR") . "/product-" . $goods['id'] . ".html";
					$dynamics[$dk]['linkurl'] = $producturl;
					$dynamics[$dk]['commenturl'] = C("SHOP_DIR") . "/comment-" . $goods['id'] . "-1-0.html";
					$dynamics[$dk]['operate_title'] = "<a href='" . $producturl . "' target='_blank'> " . $goods['pname'] . "</a>";
				} 
				// 购买商品动态
				elseif ($dynamic['operatetype'] == 2) {
					$dynamics[$dk]['dtype'] = 72; //商品动态（购买商品）
					$dynamics[$dk]['operate_desc'] = "购买了商品"; //操作描述  
					// 购买商品72：商品id、商品名称、商品原价、商品现价、商品价格折扣、商品评价数
					$arr = $this -> getShopDynamic(72, $dynamic['oid']);
					$goods = array("id" => $arr[0], "pname" => $arr[1], "oprice" => $arr[2], "cprice" => $arr[3], "discount" => $arr[4], "comments" => $arr[5]);
					$dynamics[$dk]['data'] = $goods;
					$producturl = C("SHOP_DIR") . "/product-" . $goods['id'] . ".html";
					$dynamics[$dk]['linkurl'] = $producturl;
					$dynamics[$dk]['commenturl'] = C("SHOP_DIR") . "/comment-" . $goods['id'] . "-1-0.html";
					$dynamics[$dk]['operate_title'] = "<a href='" . $producturl . "' target='_blank'> " . $goods['pname'] . "</a>";
				} 
				// 评价商品动态
				elseif ($dynamic['operatetype'] == 3) {
					$dynamics[$dk]['dtype'] = 73; //商品动态（收藏商品）
					$dynamics[$dk]['operate_desc'] = "评价商品"; //操作描述  
					// 评价商品73：商品id、商品名称、评价内容、商品评价数
					$arr = $this -> getShopDynamic(73, $dynamic['oid']);
					$goods = array("id" => $arr[0], "pname" => $arr[1], "content" => $arr[2], "comments" => $arr[3]);
					$producturl = C("SHOP_DIR") . "/product-" . $goods['id'] . ".html";
					$dynamics[$dk]['operate_title'] = "<a href='" . $producturl . "' target='_blank'> " . $goods['pname'] . "</a>";
					$dynamics[$dk]['data'] = $goods;
					$dynamics[$dk]['linkurl'] = $producturl;
					$dynamics[$dk]['commenturl'] = C("SHOP_DIR") . "/comment-" . $goods['id'] . "-1-0.html"; 
					// 评论
					$comment = M() -> Table("shop_goods_comment") -> where("id=" . $dynamic['oid']) -> field("id,content") -> find();
					$dynamics[$dk]['desc'] = mysubstr_utf8($goods['content'], 100);
				} 
			} 
			// 百科动态
			elseif ($dynamic['type'] == 8) {
				// 百科关注分类动态
				if ($dynamic['operatetype'] == 1) {
					$cat = M() -> Table('bk_category') -> where('id=' . $dynamic['oid']) -> field('id,code,name') -> find();
					$dynamics[$dk]['data'] = $cat;
					$dynamics[$dk]['dtype'] = 81; //关注分类动态
					$dynamics[$dk]['operate_desc'] = "关注了"; //操作描述
					$dynamics[$dk]['operate_desc_suffix'] = "百科";
					$dynamics[$dk]['operate_title'] = $cat['name'];
					$dynamics[$dk]['linkurl'] = get_rewrite_url("BkCategory", "category", $cat['code']); //链接地址
				} 
				// 百科加入小组动态(废弃)
				elseif ($dynamic['operatetype'] == 2) {
					$team = M() -> Table('bk_team') -> where('id=' . $dynamic['oid']) -> field('id,name') -> find();
					$dynamics[$dk]['data'] = $team;
					$dynamics[$dk]['dtype'] = 82; //加入小组动态
					$dynamics[$dk]['operate_desc'] = "加入了"; //操作描述
					$dynamics[$dk]['operate_desc_suffix'] = "小组";
					$dynamics[$dk]['operate_title'] = $team['name'];
					$dynamics[$dk]['linkurl'] = get_rewrite_url("BkTeam", "team", $team['id']); //链接地址
				} 
				// 百科发帖动态
				elseif ($dynamic['operatetype'] == 3) {
					// 主题
					$thread = M() -> Table("bk_thread") -> where("id=" . $dynamic['oid']) -> field("id, title, content, comment_num AS comments ") -> find();
					$dynamics[$dk]['data'] = $thread;
					$dynamics[$dk]['dtype'] = 83; //发表话题
					$dynamics[$dk]['operate_desc'] = "在百科里发表了新话题"; //操作描述
					$dynamics[$dk]['operate_title'] = $thread['title']; //标题
					$dynamics[$dk]['linkurl'] = get_rewrite_url('BkThread', 'thread', $thread['id']); //帖子链接地址
					$fc = $this -> _formatContent($thread['content']);
					$dynamics[$dk]['desc'] = mysubstr_utf8($fc['content'], 100); //动态描述
					$dynamics[$dk]['has_first_pic'] = $fc['has_first_pic']; //是否包含缩略图 
					$dynamics[$dk]['first_pic'] = $fc['first_pic'];
					$dynamics[$dk]['first_pic_s'] = str_replace("_y", "_s", $fc['first_pic']);
				} 
				// 百科帖子评论动态
				elseif ($dynamic['operatetype'] == 4) {
					// 百科帖子和评论
					$thread = M() -> Table("bk_thread_comment c") -> join("bk_thread t ON c.thread_id=t.id") -> where("c.id=" . $dynamic['oid']) -> field("t.id,t.title,t.content,t.comment_num AS comments,c.content AS comment,c.id AS cid") -> find();
					$dynamics[$dk]['data'] = $thread;
					$dynamics[$dk]['dtype'] = 84; //百科帖子回帖
					$dynamics[$dk]['operate_title'] = $thread['title'];
					$dynamics[$dk]['desc'] = $this -> _praseBkThreadCommentData($thread['comment'], 2) ;
					$dynamics[$dk]['linkurl'] = get_rewrite_url('BkThread', 'thread', $thread['id']); //帖子链接地址
					if ($type == 5 || $type == 6) {
						// 我回复了百科帖子
						if ($type == 5 && $dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了百科帖子"; //操作描述
						} 
						// 回复了我的百科帖子
						if ($type == 6 && $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我的百科帖子";
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在百科帖子"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新回帖";
					} 
				} 
				// 百科文章评论动态
				elseif ($dynamic['operatetype'] == 5) {
					// 百科文章和评论
					$article = M() -> Table("bk_article_comment c") -> join("bk_article a ON c.article_id=a.id") -> where("c.id=" . $dynamic['oid']) -> field("a.id,a.title,a.content,a.comment_num AS comments,c.content AS comment,c.id AS cid") -> find();
					$dynamics[$dk]['data'] = $article;
					$dynamics[$dk]['dtype'] = 85; //百科文章回帖
					$dynamics[$dk]['operate_title'] = $article['title'];
					$dynamics[$dk]['desc'] = $article['comment'] ;
					$dynamics[$dk]['linkurl'] = get_rewrite_url('BkArticle', 'article', $article['id']); //文章链接地址
					if ($type == 5 || $type == 6) {
						// 我回复了百科文章
						if ($type == 5 && $dynamic['uid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了百科文章"; //操作描述
						} 
						// 回复了我的百科文章
						if ($type == 6 && $dynamic['ouid'] == $param['loginuid']) {
							$dynamics[$dk]['operate_desc'] = "回复了我的百科文章";
						} 
					} else {
						$dynamics[$dk]['operate_desc'] = "在百科文章"; //操作描述
						$dynamics[$dk]['operate_desc_suffix'] = "中发表了新评论";
					} 
				} 
			} 
			// 最近一小时合并同相册的图片、关注、好友动态
			if ($dynamic['cretime'] >= $onehour) {
				// 图片上传（同相册的图片合并）
				if ($dynamic['type'] == 2 && $dynamic['operatetype'] == 3) {
					$ds[$dynamic['mid']][] = $dynamics[$dk];
				} 
				// 关注
				if ($dynamic['type'] == 6 && $dynamic['operatetype'] == 1) {
					$as[$dynamic['uid']][] = $dynamics[$dk];
				} 
				// 好友
				if ($dynamic['type'] == 6 && $dynamic['operatetype'] == 2) {
					$fs[$dynamic['uid']][] = $dynamics[$dk];
				} 
			} 
		} 
		// 合并的动态数据
		if (isset($ds)) {
			$newDS = $this -> _mergePhotoDynamics($ds, $param);
		} 
		if (isset($as)) {
			$newAS = $this -> _mergeRelationDynamics($as, $param);
		} 
		if (isset($fs)) {
			$newFS = $this -> _mergeRelationDynamics($fs, $param);
		} 

		// 合并后的动态数据
		foreach($dynamics as $dkey => $dval) {
			if (isset($newDS)) {
				// XX上传了N张新照片至相册XX
				foreach($newDS as $nkey => $nval) {
					// 需要保留合并后的照片动态
					if ($dval['id'] == $nval['id']) {
						$dynamics[$dkey]['operate_desc'] = $nval['operate_desc']; //操作描述
						$dynamics[$dkey]['operate_title'] = $nval['operate_title'];
						$dynamics[$dkey]['merged'] = 1;
						$dynamics[$dkey]['photo_paths'] = $nval['photo_paths'];
						$dynamics[$dkey]['photo_num'] = $nval['photo_num'];
						if ($nval['photo_num'] > 1) {
							$dynamics[$dkey]['linkurl'] = $nval['linkurl'];
							$dynamics[$dkey]['picurl'] = $nval['picurl'];
						} 
					} 
				} 
			} 

			if (isset($newAS)) {
				// XX 关注了 XX、XX和XX
				foreach($newAS as $akey => $aval) {
					if ($dval['id'] == $aval['id']) {
						$dynamics[$dkey]['operate_title'] = $aval['operate_title'];
					} 
				} 
			} 

			if (isset($newFS)) {
				// XX 加 XX、XX和XX为好友
				foreach($newFS as $fkey => $fval) {
					if ($dval['id'] == $fval['id']) {
						$dynamics[$dkey]['operate_title'] = $fval['operate_title'];
					} 
				} 
			} 
		} 
		// 动态已读，toolbar提示评论数更新
		if ($type == 5 || $type == 6) {
			$this->_actOthersToOneDynamic($uid, 1);
		} 

		return $dynamics;
	} 

	/**
	 * 获取商城动态
	 *
	 * @param $otype int 类型（71：收藏商品；72：购买商品；73：评价商品）
	 * @param $oid int 商品id/商品评论id
	 *
	 * @return 商城动态数据
	 */
	public function getShopDynamic($otype, $oid) {
		$url = C("SHOP_DIR") . "/api/ucenter.php?otype=" . $otype . "&oid=" . $oid;
		$res = get_url($url);

		return unserialize($res);
	} 

	/**
	 * 删除动态
	 * 
	 * @param  $id int 动态id
	 *
	 * @return boolean 处理结果
	 */
	public function deleteDynamic($id) {
		// 逻辑删除
		$data['status'] = -1;

		$res = M() -> Table("uc_dynamic") -> where("id=" . $id) -> save($data);

		if ($res) {
			return array("status" => "ok");
		} 

		return array("status" => "error");
	} 

	/**
	 * 添加动态
	 *
	 * @param $param array 参数数组
	 */
	public function addDynamic($param) {
		// 用户id
		$data['uid'] = $param['uid']; 
		// 动态类型（1：日志动态；2：相册动态；3：微博动态；4：帖子动态；5：评论回复动态；6：关系动态）
		$data['type'] = $param['type']; 
		// 操作类型
		// 1日志（1：发表；2：评论）
		// 2相册（1：创建；2：修改；3：上传；4：评论）
		// 3微博（1：发表；2：转播；3：评论）
		// 4论坛（1：发帖；2：回帖）
		// 5评论回复（1：日志评论回复；2：照片评论回复；3：微博评论回复；4：论坛帖子引用回复；5：百科帖子评论回复；6：百科文章评论回复）
		// 6关系（1：加关注；2：加好友）
		// 7商城（1：收藏商品；2：购买商品；3：评价商品）
		// 8百科（1：关注分类；2：加入小组；3：发帖；4：帖子评论；5：文章评论）
		$data['operatetype'] = $param['operatetype']; 
		// 操作对象用户id
		if ($param['ouid']) {
			$data['ouid'] = $param['ouid'];
			if ($param['ousername']) {
				$data['ousername'] = $param['ousername'];
			} 
		} 
		// 操作对象id
		if ($param['oid']) {
			$data['oid'] = $param['oid'];
			if ($param['otitle']) {
				$data['otitle'] = $param['otitle'];
			} 
		} 
		// 内容id
		if ($param['mid']) {
			$data['mid'] = $param['mid'];
		} 
		$data['cretime'] = time();
		$data['status'] = 0;

		M() -> Table("uc_dynamic") -> add($data);
	} 

	/**
	 * *********************************** Private  Functions  Start *******************************************
	 */
	/**
	 * 取得动态显示的时间范围内其他人对某人的新评论数/更新动态显示的时间范围内其他人对某人的新评论isnew状态
	 * 需要处理的动态类型（日志评论/评论回复、照片评论/评论回复、微博评论/评论回复、论坛回帖/引用回帖、百科文章评论/评论回复、百科帖子评论/评论引用）
	 *
	 * @param $uid int 用户id
	 * @param $flag int 处理标志（0只获取评论数；1更新评论isnew状态）
	 *
	 * @return mixed 在动态显示的时间范围内其他人对某人的新评论数/更新成功true
	 */
	private function _actOthersToOneDynamic($uid, $flag = 0) {
		// 动态显示的时间范围
		$dynamictime = strtotime(date("Y-m-d")) - $this -> _defaultDisplayTime;
		$where = 'cretime >= ' . $dynamictime . ' AND status = 0 AND isnew=1 ';
		$where .= " AND  (uid != ouid AND ouid=" . $uid . ") AND ( (type=1 AND operatetype=2) OR (type=2 AND operatetype=4) OR (type=3 AND operatetype=3) OR (type=4 AND operatetype=2) OR (type=8 AND operatetype  IN (4,5)) OR (type=5 ) )";
		$cnt = M() -> Table("uc_dynamic") -> where($where) -> count();

		//更新动态显示的时间范围内其他人对某人的新评论isnew状态
		if($flag == 1 && $cnt) {
			M() -> Table("uc_dynamic") -> where($where) -> save(array("isnew" => 0));
			return true;
		}

		return $cnt;
	}


	/**
	 * 取得用户连续签到数
	 *
	 * @param  $uid int 用户id
	 *
	 * @return array 连续签到
	 */
	private function _getUserContinuousSigns($uid) {
		// 所有签到记录
		$signs = M() -> Table("uc_sign") -> where("uid=" . $uid . " AND continuous=0") -> field("signtime") -> order("signtime") -> select();

		$concompleted = 0; //连续签到天数
		$allconsigns = 0; //总连续签到次数
		$signtime = 0; //签到时间
		$interruptedTime = 0; //最近签到中断时间
		foreach($signs as $sk => $sign) {
			// 第一次签到
			if ($signtime == 0) {
				$signtime = strtotime(date("Y-m-d", $sign['signtime']));
				$concompleted = 1;
			} else {
				// 连续签到
				if ((strtotime(date("Y-m-d", $sign['signtime'])) - $signtime) == 86400) {
					$signtime = strtotime(date("Y-m-d", $sign['signtime']));
					$concompleted += 1;
				} else {
					// 不连续签到，则连续签到重新计数
					$signtime = strtotime(date("Y-m-d", $sign['signtime']));
					$concompleted = 1;
					$interruptedTime = $sign['signtime']-1;
				} 
			} 
			if ($concompleted == 7) {
				$allconsigns += 1;
				$interruptedTime = $sign['signtime'];
				$concompleted = 0;
				$signtime = 0;
			} 
		} 

		if ($allconsigns) {
			$signArr['completed'] = 7;
		} else {
			$signArr['completed'] = $concompleted;
		} 
		$signArr['allconsigns'] = $allconsigns;
		$signArr['interruptedTime'] = $interruptedTime;

		return $signArr;
	} 

	/**
	 * 用户人气/啵币发放记录
	 * 
	 * @param  $param array 参数数组
	 *		uid int 用户id
	 *		cent_type int 积分类型
	 *		cent int 积分值
	 */
	private function _addUserCreditsLog($param) {
		if ($param['uid']) {
			$user = M() -> Table("boqii_users") -> where("uid=" . $param['uid']) -> field("nickname, extcredits1, extcredits2") -> find();
		} 
		$data['ip'] = get_client_ip();
		$data['uid'] = $param['uid'];
		$data['username'] = $user['nickname'];
		$data['operate_uid'] = 0; //系统操作
		$data['operate_username'] = "";
		$data['type'] = "1402"; //个人中心uc
		$data['cent_old'] = $user['extcredits' . $param['cent_type']];
		$data['cent_operate'] = $param['cent'];
		$data['cent_type'] = $param['cent_type'];
		$data['add_time'] = time();

		M() -> Table("boqii_operate_log") -> add($data);
	} 

	/**
	 * 处理编辑器内容并提取第一张图片
	 * （个人中心发表日志、论坛发帖、论坛回帖、百科发帖）
	 *
	 * @param $content string 内容
	 *
	 * @return array 处理后的内容和第一张图片信息
	 */
	private function _formatContent($content) {
		// 图片
		$preg = '/<img.*src="(.*)"\\s*.*>/iU';

		if (preg_match_all($preg, $content, $match)) {
			$firstPic = ""; //第一张有效图片（非表情图片）
			for($i = 0;$i < count($match[0]);$i++) {
				$pic = $match[0][$i];
				$picUrl = $match[1][$i]; 
				// 表情图片
				if (strpos($picUrl, "emotion") !== false || strpos($picUrl, "emoticons") !== false) {
					$content = str_replace($pic, "", $content);
				} else {
					if (!$firstPic) {
						$firstPic = $picUrl;
					} 
					$content = str_replace($pic, "", $content);
				} 
			} 
		} 
		if (isset($firstPic)) {
			$hasFirstPic = 1;
		} else {
			$hasFirstPic = 0;
			$firstPic = "";
		} 

		$content = strip_tags($content);
		$content = preg_replace ('/&nbsp;/is', '', $content);

		return array("content" => $content, "first_pic" => $firstPic, "has_first_pic" => $hasFirstPic);
	} 

	/**
	 * 处理BBS帖子回帖数据
	 *
	 * @param $content string 回帖数据
	 *
	 * @return string 处理后的回帖数据
	 */
	private function _praseBbsPostData($content) {
		// 去除引用部分
		$newContent = strrchr($content, "[/quote]");
		$newContent = str_replace("[/quote]<br />", "", $newContent);
		$newContent = str_replace("[/quote]", "", $newContent);

		return strip_tags($newContent);
	} 

	/**
	 * 处理百科帖子评论
	 *
	 * @param $content string 帖子评论
	 * @param $type int 类型（1表示引用评论；2表示一般帖子评论）
	 *
	 * @return string 处理后的百科帖子评论
	 */
	private function _praseBkThreadCommentData($content, $type = 1) {
		if ($type == 1) {
			// 去除引用部分
			$newContent = strrchr($content, "[/quote]");
			$newContent = str_replace("[/quote]<br />", "", $newContent);
			$newContent = str_replace("[/quote]", "", $newContent);
		} else {
			$newContent = $content;
		} 

		return stripslashes($newContent);
	} 

	/**
	 * 合并照片上传动态
	 * 需要合并的动态中最后一条动态显示为合并的动态，其他的依旧按合并前的显示
	 * 
	 * @param $dynamics array 合并前的照片上传动态数据
	 * @param $param array 参数数组
	 *
	 * @return 合并后的照片上传动态数据
	 */
	private function _mergePhotoDynamics($dynamics, $param) {
		$newDynamics = array();
		foreach($dynamics as $dk => $albumId) {
			//临时存储最后一条动态id
			$tmpId = "";
			//临时存储最后一条动态描述
			$tmpTitle = "";
			//临时存储最后一条动态时间
			$tmpTime = 0;
			//临时存储照片路径
			$tmpPath = array();
			$num = 0;
			foreach($albumId as $ak => $dynamic) {
				$uid = $dynamic['uid'];
				//最后一条动态id
				$tmpId = $dynamic['cretime'] >= $tmpTime ? $dynamic['id'] : $tmpId;
				//最后一条动态时间
				$tmpTime = $dynamic['cretime'] >= $tmpTime ? $dynamic['cretime'] : $tmpTime;
				//只显示前三个照片（其余的隐藏不显示）
				foreach($dynamic['data'] as $photo) {
					if ($num < 3) {
						$tmpPath[$photo['photo_id']] = str_replace("_y", "_b", $photo['photo_path']);
					} 
					$num++;
				} 
			} 

			$newDynamics[$dk]['id'] = $tmpId;
			$newDynamics[$dk]['operate_desc'] = "上传了" . $num . "张新照片到相册 ";
			$album = M() -> Table("uc_album") -> where("id=" . $dk) -> field("id,title") -> find();
			$albumurl = get_rewrite_url('UcAlbum', 'photoList', $album['id']); //图片链接地址
			$newDynamics[$dk]['operate_title'] = "<a href='" . $albumurl . "' target='_blank'>" . $album['title'] . "</a>";
			$newDynamics[$dk]['photo_paths'] = $tmpPath;
			$newDynamics[$dk]['photo_num'] = $num;
			//多张照片显示相册url
			if ($num > 1) {
				$newDynamics[$dk]['linkurl'] = $albumurl; //相册链接地址
				$newDynamics[$dk]['picurl'] = $albumurl; //相册链接地址
			} 
		} 
		return $newDynamics;
	} 

	/**
	 * 按用户分组分别合并关系(好友/关注)动态
	 * 需要合并的动态中最后一条动态显示为合并的动态，其他的依旧按合并前的显示
	 *
	 * @param $dynamics array 合并前的关系(好友/关注)动态数据
	 * @param $param array 参数数组
	 *
	 * @return 合并后的关系(好友)动态数据
	 */
	private function _mergeRelationDynamics($dynamics, $param) {
		$newDynamics = array();
		foreach($dynamics as $dk => $attentions) {
			//临时存储最后一条动态id
			$tmpId = "";
			//临时存储最后一条动态描述
			$tmpTitle = "";
			//临时存储最后一条动态时间
			$tmpTime = 0;
			//临时存储被合并的好友uid
			$tmpOuids = ""; 
			$apiModel = D('Api');
			foreach($attentions as $ak => $dynamic) {
				// 重复好友用户id不合并（加好友后取消关注然后继续加关注成为好友）
				if (strpos($tmpOuids, $dynamic['ouid']) === false) {
					//被合并的好友uid
					$tmpOuids = empty($tmpOuids) ? $dynamic['ouid'] : $tmpOuids . "," . $dynamic['ouid'];
					//最后一条动态id
					$tmpId = $dynamic['cretime'] >= $tmpTime ? $dynamic['id'] : $tmpId;
					//最后一条动态时间
					$tmpTime = $dynamic['cretime'] >= $tmpTime ? $dynamic['cretime'] : $tmpTime;
					//最后一条动态描述
					$tmpTitle = !empty($tmpTitle) ? $tmpTitle . "、": "";
					if ($param['type'] == 3 && $dynamic['data']['uid'] == $param['loginuid']) {
						$tmpTitle .= "<span style='color:#A8A8A8'>我</span>";
					} else {
						$tmpTitle .= "<a href='" . $dynamic['data']['url_link'] . "' target='_blank'>" . $dynamic['data']['nickname'] . "</a>";
					} 
				} 
			} 
			$newDynamics[$dk]['id'] = $tmpId; 
			$newDynamics[$dk]['operate_title'] = $tmpTitle;
		} 

		return $newDynamics;
	} 
	/**
	 * *********************************** Private  Functions  End ********************************************
	 */

} 

?>
<?php
/**
 * 好友关系Model类
 *
 * @author: zlg
 * @created: 12-10-23
 */
class UcRelationModel extends Model {
	protected $trueTableName = 'uc_friend_relative';
	private $status;
	private $fidMy;
	private $fidOther;
	private $fidEach;
	private $fidBlack;
	private $fidDel;
	private $intError;
	private $office;

	protected function _initialize () {
		$this->status = 0;
		$this->fidMy = 1;
		$this->fidOther = 2;
		$this->fidEach = 3;
		$this->fidBlack = 1;
		$this->fidDel = 2;
		$this->intError = 5;
		//官方账号
		$this->office = 1328680;
	}

	/**
	 * 我/他的关注  我/他的粉丝的人（带搜索条件)   --后期优化 Gavin
	 * @param $uid
	 * @param array $param 分页参数
	 * @param int $fid  1(我关注的人) 2(我的粉丝---关注我的人)  3(我的好友--双向关注)
	 * @param string $nicename
	 * @return array|bool
	 */
	public function getMyAttention ($uid, $param, $fid = 1, $nicename = '') {
		//查询所有我的关注 OR 根据昵称查询出所要的结果
		$arrMyCarePeo = array();
		if (!empty($nicename) || $nicename === '0') { //搜索我\他关注的人
			$arrMyCarePeo = $this->_seachPerson($uid, $param, $fid, $nicename);
		} else { //我\他关注的人 我/他的粉丝的人
			//获取我的粉丝，我的关注人的uid 数组
			$arrMyCarePeo = $this->_getArrUid($uid, $param, $fid);
		}
		return !empty($arrMyCarePeo) ? $arrMyCarePeo : array();
	}

	/**
	 * 查询出关注的人的uid 数组
	 * @param $uid
	 * @param array $param  分页参数
	 * @param int $fid
	 * @return array|bool
	 */
	private function _getArrUid ($uid, $param, $fid = 1) {
		//我的关注
		$fidMy = $this->fidMy;
		//我的粉丝
		$fidOther = $this->fidOther;
		//我的好友
		$fidEach = $this->fidEach;
		//分页开始
		$page = $param['page'] ? $param['page'] : 1;
		$page_num = $param['page_num'] ? $param['page_num'] : 10; //$param['num'] 自定义 显示条数
		$page_start = ($page - 1) * $page_num;
		//分页结束
		//开启缓存
		$cacheRedis = Cache::getInstance('Redis');
		//我的关注
		if ($fid == $fidMy) {
			//初始化缓存
			$this->getRelationInfo($uid, 1);
			//生成缓存
			$zsetList = $cacheRedis->zGetByIndexDesc(C('REDIS_KEY.follow') . $uid, $page_start, ($page*$page_num)-1);
			$this->total = $cacheRedis->zSize(C('REDIS_KEY.follow') . $uid);
			//我的粉丝
		} elseif ($fid == $fidOther) {
			//初始化缓存
			$this->getRelationInfo($uid, 2);
			//重新生成缓存
			$zsetList = $cacheRedis->zGetByIndexDesc(C('REDIS_KEY.fans') . $uid, $page_start, ($page*$page_num)-1);
			$this->total = $cacheRedis->zSize(C('REDIS_KEY.fans') . $uid);

			//我的好友
		} elseif ($fid == $fidEach) {
			//初始化缓存
			$this->getRelationInfo($uid, 3);
			//重新生成缓存
			$zsetList = $cacheRedis->zGetByIndexDesc(C('REDIS_KEY.friend') . $uid, $page_start, ($page*$page_num)-1);
			$this->total = $cacheRedis->zSize(C('REDIS_KEY.friend') . $uid);
		} else {
			return false;
		}
		$arrUid = $zsetList;
		$arrUid = empty($arrUid) ? array() : $arrUid;
		if (empty($arrUid)) {
			return false;
		} else {
			$apiModel = D("Api");
			$arrMyCarePeo = array();
			foreach ($arrUid as $key => $val) {
				//我的关注 、好友
				if ($fid == $fidMy || $fid == $fidEach) {
					$intUid = $val;
				} else {
				//我的粉丝
					$intUid = $val;
					//当用户点击我的粉丝的时候,更改 是否最新的粉丝 字段 isnews
					$this->updateIsNews($intUid, $uid);
				}
				//获取个人信息
				$userInfo = $apiModel->getUserInfo($intUid);
				$intMycare = $this->getOtherCounts($intUid, $this->fidMy);
				$intOtherCare = $this->getOtherCounts($intUid, $this->fidOther);
				$intEachCare = $this->getOtherCounts($intUid, $this->fidEach);
				$arrWerbo = D('UcWeibo')->getOtherRecentUpdatesByUid($intUid); //获取最新微博
				$arrPet = D('UcPets')->getRelationPet($intUid);
				$arrMyCarePeo[$key]['userInfo'] = $userInfo;
				$arrMyCarePeo[$key]['userInfo']['weiBo'] = $arrWerbo;
				$arrMyCarePeo[$key]['userInfo']['petMsg'] = $arrPet;
				$arrMyCarePeo[$key]['userInfo']['genderClass'] = $userInfo['gender'] == "1" ? "male" : "female";
				$arrMyCarePeo[$key]['userInfo']['avatar'] = $userInfo['avatar'];
				$arrMyCarePeo[$key]['userInfo']['genderName'] = $userInfo['gender'] == "1" ? "男" : "女";
				$arrMyCarePeo[$key]['userInfo']['intMycare'] = $intMycare;
				$arrMyCarePeo[$key]['userInfo']['intOtherCare'] = $intOtherCare;
				$arrMyCarePeo[$key]['userInfo']['intEachCare'] = $intEachCare;
				$arrMyCarePeo[$key]['userInfo']['city_data'] = $userInfo['city_data'];
			}
			return !empty($arrMyCarePeo) ? $arrMyCarePeo : array();
		}
	}

	/**
	 * 加关注 --此接口 使用时，请在控制器里判断2者当前状态
	 * @param $uid
	 * @param $bUid
	 * @return bool|mixed
	 */
	public function addAttention ($uid, $bUid) {
		//不能对官方账号进行操作
		if ($bUid == $this->office) {
			return false;
		}
		if($bUid == $uid) {
			return false;
		}
		//是否有他对我关注的状态记录
		$arrtAttention = M('uc_friend_relative')->where("uid='$bUid' AND attention_uid='$uid' AND status=$this->status")->find();
		//是否有我关注他的记录
		$arrAttention = M('uc_friend_relative')->where('uid=' . $uid . ' AND attention_uid=' . $bUid)->find();
		//有他对我关注的状态记录 --好友
		if ($arrtAttention) {
			$data['isrelation'] = $this->fidMy; //好友
		//没有他对我的状态记录 --关注
		} else {
			$data['isrelation'] = $this->status; //关注
		}
		//查询是否有我对他的记录
		if ($arrAttention) {
			$data['id'] = $arrAttention['id'];
			$data['status'] = $this->status;
			$data['dateline'] = time();
			$data['isnew'] = $this->status; //不是新记录
			$intId = M('uc_friend_relative')->save($data);
		} else {
			$data['uid'] = $uid;
			$data['attention_uid'] = $bUid;
			$data['dateline'] = time();
			$intId = M('uc_friend_relative')->add($data);
		}
		//加redis
		$cacheRedis = Cache::getInstance('Redis');
		if ($arrtAttention && $intId) { // 同步更改 2者的好友关系
			//初始化我的好友集合
			$this->getRelationInfo($uid, 3);
			//初始化我的粉丝集合
			$this->getRelationInfo($uid, 2);
			//初始化他的好友集合
			$this->getRelationInfo($bUid, 3);
			//初始化他的关注集合
			$this->getRelationInfo($bUid, 1);
			M('uc_friend_relative')->where("id =" . $arrtAttention['id'])->setField('isrelation', 1);

			//加redis 我的好友集合增加一个元素
			$cacheRedis->zAdd(C('REDIS_KEY.friend') . $uid, time(), $bUid);
			//我的粉丝集合减少一个元素
			$cacheRedis->zDelete(C('REDIS_KEY.fans') . $uid, $arrtAttention['uid']);
			//他的关注好友增加一个元素
			$cacheRedis->zAdd(C('REDIS_KEY.friend') . $bUid, time(), $uid);
			//他的关注集合减少一个元素
			$cacheRedis->zDelete(C('REDIS_KEY.follow') . $bUid, $uid);
		}
		//增加关注
		if (!$arrtAttention && $intId) {
			//初始化他的粉丝
			$this->getRelationInfo($bUid, 2);
			//初始化我的关注
			$this->getRelationInfo($uid, 1);
			//我的关注集合增加一个元素
			$cacheRedis->zAdd(C('REDIS_KEY.follow') . $uid, time(), $bUid);
			//他的粉丝集合增加一个元素
			$cacheRedis->zAdd(C('REDIS_KEY.fans') . $bUid, time(), $uid);
		}
		return !empty($intId) ? $intId : false;
	}

	/**
	 * 取消关注（移除粉丝）
	 * @param $uid
	 * @param $bUid
	 * @param int $fid  取消类型  1,我的关注里  2, 我的粉丝  3, 我的好友里
	 * @return bool
	 */
	public function cancelAttention ($uid, $bUid, $fid = 1) {
		if ($bUid == $this->office) {
			return false;
		}
		//1,我的关注里  2, 我的粉丝  3, 我的好友里
		$fidMy = $this->fidMy;
		$fidOther = $this->fidOther;
		$fidEach = $this->fidEach;
		if ($fid == $fidOther) { //粉丝
			$fidstaus = $fidOther;
		} elseif ($fid == $fidMy) { //关注
			$fidstaus = $fidMy;
		} else if ($fid == $fidEach) { //好友
			$fidstaus = $fidEach;
		} else {
			return false;
		}
		$intBoolId = $this->_getMyFansId($uid, $bUid, $fidstaus);
		if ($intBoolId) {
			//redis
			$cacheRedis = Cache::getInstance('Redis');
			if ($fidstaus == $fidMy) { //我的关注
				$data['id'] = $intBoolId;
				$data['status'] = $this->fidOther;
				$data['dateline'] = time();
				$data['isnew'] = $this->status;
				$data['isrelation'] = $this->status; //关注
				$intId = M('uc_friend_relative')->save($data);
				if(!empty($intId)) {
					//初始化我的关注
					$this->getRelationInfo($uid, 1);
					//初始化他的粉丝
					$this->getRelationInfo($bUid, 2);
					//redis 我的关注集合减少一个元素
					$cacheRedis->zDelete(C('REDIS_KEY.follow') . $uid, $bUid);
					//他的粉丝减少一个元素
					$cacheRedis->zDelete(C('REDIS_KEY.fans') . $bUid, $uid);
				}

			} else if ($fidstaus == $fidOther) { //我的粉丝
				$data['id'] = $intBoolId;
				$data['status'] = $this->fidOther;
				$data['dateline'] = time();
				$data['isnew'] = $this->status;
				$data['isrelation'] = $this->status; //关注
				$intId = M('uc_friend_relative')->save($data);
				 if (!empty($intId)) {
					 //初始化我的粉丝
					 $this->getRelationInfo($uid, 2);
					 //初始化他的关注
					 $this->getRelationInfo($bUid, 1);
					 //我的粉丝元素集合减少一个元素
					 $cacheRedis->zDelete(C('REDIS_KEY.fans') . $uid, $bUid);
					 //他的关注集合减少一个元素
					 $cacheRedis->zDelete(C('REDIS_KEY.follow') . $bUid, $uid);
				 }

			} else if ($fidstaus == $fidEach) { //我的好友
				$data['id'] = $intBoolId;
				$data['status'] = $this->fidOther;
				$data['dateline'] = time();
				$data['isnew'] = $this->status;
				$data['isrelation'] = $this->status; //关注
				$intId = M('uc_friend_relative')->save($data);
				$condition['uid'] = $bUid;
				$condition['attention_uid'] = $uid;
				$condition['status'] = $this->status;
				$condition['isrelation'] = $this->fidMy;
				$result = M('uc_friend_relative')->where($condition)->setField('isrelation', $this->status); //更新 他人关注我的状态

				if(is_numeric($result) && is_numeric($intId)) {
					//初始化我的粉丝
					$this->getRelationInfo($uid, 2);
					//初始化我的好友
					$this->getRelationInfo($uid, 3);
					//初始化他的关注
					$this->getRelationInfo($bUid, 1);
					//初始化他的好友
					$this->getRelationInfo($bUid, 3);
					//我的好友元素减少一个元素
					$cacheRedis->zDelete(C('REDIS_KEY.friend') . $uid, $bUid);
					//我的粉丝元素增加一个元素
					$cacheRedis->zAdd(C('REDIS_KEY.fans') . $uid, time(), $bUid);
					//他的好友减少一个元素
					$cacheRedis->zDelete(C('REDIS_KEY.friend') . $bUid, $uid);
					//他的关注增加一个元素
					$cacheRedis->zAdd(C('REDIS_KEY.follow') . $bUid, time(), $uid);
				}
			} else {
				return false;
			}
			return empty($intId) ? false : true;
		} else {
			return false;
		}
	}

	/**
	 * 返回我的粉丝/关注/好友 更新主键
	 * @param $uid
	 * @param $bUid
	 * @param int $fid  获取主键类型 1($fidMy),我的关注里  2($fidOther) 我的粉丝  3($fidOther) 我的好友里
	 * @return bool
	 */
	private function _getMyFansId ($uid, $bUid, $fid = 1) {
		$fidMy = $this->fidMy;
		$fidOther = $this->fidOther;
		$fidEach = $this->fidEach;
		if ($fid == $fidEach) { //好友
			$muid = $uid;
			$mbuid = $bUid;
			$condition['isrelation'] = $this->fidMy; //好友
		} else if ($fid == $fidMy) { //关注
			$muid = $uid;
			$mbuid = $bUid;
			$condition['isrelation'] = $this->status; //关注
		} else if ($fid == $fidOther) { //粉丝
			$muid = $bUid;
			$mbuid = $uid;
			$condition['isrelation'] = $this->status; //关注
		} else {
			return false;
		}
		$condition['uid'] = $muid;
		$condition['attention_uid'] = $mbuid;
		$condition['status'] = $this->status;
		$id = M('uc_friend_relative')->where($condition)->getField('id');
		return !empty($id) ? $id : false;

	}

	/**
	 * 黑名单
	 * @param $uid  查询人的uid
	 * @$param  分页参数
	 * @return array
	 */
	public function getBlackList ($param) {
		$condition['uid'] = $param['uid'];
		$condition['status'] = $this->fidMy;
		//分页开始
		$page = $param['page'] ? $param['page'] : 1;
		$page_num = $param['page_num'] ? $param['page_num'] : 8; //$param['num'] 自定义 显示条数
		$page_start = ($page - 1) * $page_num;
		//分页结束
		//redis
		$cacheRedis = Cache::getInstance('Redis');
		//初始化他的好友
		$this->getRelationInfo($param['uid'], 4);
		//读取缓存
		$blackListId = $cacheRedis->zGetByIndexDesc(C('REDIS_KEY.black') . $param['uid'], $page_start, ($page*$page_num)-1);
		$this->total = $cacheRedis->zSize(C('REDIS_KEY.black') . $param['uid']);

		$blackList = array();
		$apiModel = D('Api');
		foreach ($blackListId as $key => $val) {
			$userInfo  = $apiModel->getUserInfo($val);
			$blackList[$key]['nickname'] = $userInfo['nickname'];
			$blackList[$key]['avatar'] = $userInfo['avatar'];
			$blackList[$key]['intUid'] = $val;
		}
		return empty($blackList) ? array() : $blackList;
	}

	/**
	 * 加入黑名单
	 * @param $uid
	 * @param $buid  被加入人的uid
	 * @return bool|mixed
	 */
	public function addBlack ($uid, $buid) {
		//不允许对官方账号进行操作
		if ($buid == $this->office) {
			return false;
		}

		if($buid == $uid) {
			return false;
		}
		$condition['uid'] = $uid;
		$condition['attention_uid'] = $buid;
		$id = M('uc_friend_relative')->where($condition)->getField('id');
		if ($id) {
			$data['id'] = $id;
			$data['uid'] = $uid;
			$data['attention_uid'] = $buid;
			$data['status'] = $this->fidMy;
			$data['isnew'] = $this->status;
			$data['dateline'] = time();
			$data['isrelation'] = $this->status;
			$boolStatus = M('uc_friend_relative')->save($data);
		} else { //没有则创建黑名单
			$data['uid'] = $uid;
			$data['attention_uid'] = $buid;
			$data['status'] = $this->fidMy;
			$data['isnew'] = $this->fidMy;
			$data['dateline'] = time();
			$boolStatus = M('uc_friend_relative')->add($data);
		}
		$bId = M('uc_friend_relative')->where("uid = '$buid' and attention_uid= '$uid' and status != $this->fidBlack")->getField('id'); //查询是否有 他对我的操作记录 ，排除他把我拉入黑名单
		if ($bId) { //软删除这条记录
			M('uc_friend_relative')->where("uid = '$buid' and attention_uid= '$uid'")->setField(array('status' => $this->fidOther, 'isrelation' => $this->status));
		}
		if (is_numeric($boolStatus)) {
			//redis
			$cacheRedis = Cache::getInstance('Redis');
//				//初始化我的关注
//			$this->getRelationInfo($uid, 1);
//			//初始化我的粉丝
//			$this->getRelationInfo($uid, 2);
//			//初始化我的好友
//			$this->getRelationInfo($uid, 3);
			//初始化我的的黑名单
			$this->getRelationInfo($uid, 4);
//			//初始化他的关注
//			$this->getRelationInfo($buid, 1);
//			//初始化他的粉丝
//			$this->getRelationInfo($buid, 2);
//			//初始化他的好友
//			$this->getRelationInfo($buid, 3);
			//增加黑名单集合元素
			$cacheRedis->zAdd(C('REDIS_KEY.black') . $uid, time(), $buid);
			//减少元素
			$cacheRedis->zDelete(C('REDIS_KEY.follow') . $uid, $buid);
			$cacheRedis->zDelete(C('REDIS_KEY.fans') . $uid, $buid);
			$cacheRedis->zDelete(C('REDIS_KEY.friend') . $uid, $buid);
			$cacheRedis->zDelete(C('REDIS_KEY.follow') . $buid, $uid);
			$cacheRedis->zDelete(C('REDIS_KEY.fans') . $buid, $uid);
			$cacheRedis->zDelete(C('REDIS_KEY.friend') . $buid, $uid);
		}

		return is_numeric($boolStatus) ? $boolStatus : false;
	}

	/**
	 * 解除黑名单
	 * @param $uid
	 * @param $buid
	 * @return bool
	 */
	public function cancelBlack ($uid, $buid) {
		$condition['uid'] = $uid;
		$condition['attention_uid'] = $buid;
		$id = M('uc_friend_relative')->where($condition)->getField('id');
		$tcondition['uid'] = $buid;
		$tcondition['attention_uid'] = $uid;
		$tcondition['status'] = array('neq', $this->fidBlack);
		$tid = M('uc_friend_relative')->where($tcondition)->getField('id');
		if ($id) {
			$data['id'] = $id;
			$data['uid'] = $uid;
			$data['attention_uid'] = $buid;
			$data['status'] = $this->fidDel;
			$data['isnew'] = $this->status;
			$data['isrelation'] = $this->status;
			$data['dateline'] = time();
			$boolStatus = M('uc_friend_relative')->save($data);
			if ($tid) {
				$data['id'] = $tid;
				$data['uid'] = $buid;
				$data['attention_uid'] = $uid;
				$data['status'] = $this->fidDel;
				$data['isnew'] = $this->status;
				$data['isrelation'] = $this->status;
				$data['dateline'] = time();
				$tboolStatus = M('uc_friend_relative')->save($data);
			}
		} else {
			$boolStatus = false;
		}
		if (is_numeric($boolStatus)) {
			//redis
			$cacheRedis = Cache::getInstance('Redis');
			//初始化我的黑名单
			$this->getRelationInfo($uid, 4);
			//黑名单集合减少一个元素
			$cacheRedis->zDelete(C('REDIS_KEY.black') . $uid, $buid);
		}
		return is_numeric($boolStatus) ? true : false;
	}

	/**
	 * 统计他关注的人数，有多少人关注了他，与他相互关注的人数
	 * @param $mid
	 * @param int $fid   1  他关注的 2  有多少人关注了他  3与他相互关注的人数
	 * @return int|mixed
	 */
	public function getOtherCounts ($mid, $fid = 1) {
		// 1  他关注的
		$fidMy = $this->fidMy;
		//2  有多少人关注了他
		$fidOther = $this->fidOther;
		//3与他相互关注的人数
		$fidEach = $this->fidEach;
		if ($fid == $fidMy) {
			$sql = "select attention_uid FROM uc_friend_relative
          where status=0 and uid=$mid and isrelation = $this->status";
		} elseif ($fid == $fidOther) {
			$sql = "select uid FROM uc_friend_relative
          where status=0 and attention_uid=$mid and isrelation = $this->status";
		} elseif ($fid == $fidEach) {
			$sql = "select attention_uid FROM uc_friend_relative
          where status=0 and uid=$mid and isrelation = $this->fidMy";
		} else {
			return 0;
		}

		$arrCare = M()->query($sql);
		$cnt = count($arrCare);
		return $cnt;
	}

	/**
	 * 在我的关注、粉丝、好友里，根据昵称搜索用户信息
	 * @param $uid
	 * @param int $fid  1  他关注的 2  有多少人关注了他  3与他相互关注的人数
	 * @param string $nicename
	 * @return array|bool
	 */
	private function _seachPerson ($uid, $param, $fid = 1, $nicename = '') {
		if (!empty($nicename) || $nicename === '0') {
			$fidMy = $this->fidMy;
			$fidOther = $this->fidOther;
			$fidEach = $this->fidEach;
			$page = $param['page'] ? $param['page'] : 1;
			$page_num = $param['page_num'] ? $param['page_num'] : 10; //$param['num'] 自定义 显示条数
			$page_start = ($page - 1) * $page_num;

			if ($fid == $fidMy) { //我的关注
				$sql = "select u.uid from boqii_users u left join uc_friend_relative f on (u.uid = f.attention_uid)
                where u.is_del = 0 and u.nickname like '%$nicename%'
                and f.status=0 and f.uid=$uid and f.isrelation = $this->status
                order by  f.dateline desc limit $page_start,$page_num";

			} elseif ($fid == $fidOther) { //我的粉丝
				$sql = "select u.uid from boqii_users u left join uc_friend_relative f on (u.uid = f.uid)
                where u.is_del = 0 and u.nickname like '%$nicename%'
                and f.status=0 and f.attention_uid=$uid and f.isrelation = $this->status
                order by  f.dateline desc limit $page_start,$page_num";
			} elseif ($fid == $fidEach) { //我的好友
				$sql = "select u.uid from boqii_users u left join uc_friend_relative f on (u.uid = f.attention_uid)
                where u.is_del = 0 and u.nickname like '%$nicename%'
                and f.status=0 and f.uid=$uid and f.isrelation = $this->fidMy
                order by  f.dateline desc limit $page_start,$page_num";
			} else {
				return false;
			}
			$arrUid = M()->query($sql);
			$arrUid = empty($arrUid) ? array() : $arrUid;
			$this->total = $this->getSearchCntCare($uid, $fid, $nicename);
			if (empty($arrUid)) {
				return false;
			} else {
				$apiModel = D("Api");
				$arrMyCarePeo = array();
				foreach ($arrUid as $key => $val) {
					$intUid = $val['uid']; //我的粉丝
					$userInfo = $apiModel->getUserInfo($intUid); //获取个人信息
					$intMycare = $this->getOtherCounts($intUid, $this->fidMy);
					$intOtherCare = $this->getOtherCounts($intUid, $this->fidOther);
					$intEachCare = $this->getOtherCounts($intUid, $this->fidEach);
//					$city_data = $this->getProvinceCity($userInfo['city_id'], 'boqii_city'); //获取省市
					$arrMyCarePeo[$key]['userInfo'] = $userInfo;
					$arrWerbo = D('UcWeibo')->getOtherRecentUpdatesByUid($intUid);
					$arrPet = D('UcPets')->getRelationPet($intUid);
					$arrMyCarePeo[$key]['userInfo']['weiBo'] = $arrWerbo;
					$arrMyCarePeo[$key]['userInfo']['petMsg'] = $arrPet;
					$arrMyCarePeo[$key]['userInfo']['genderClass'] = $userInfo['gender'] == "1" ? "male" : "female";
					$arrMyCarePeo[$key]['userInfo']['avatar'] = $userInfo['avatar'];
					$arrMyCarePeo[$key]['userInfo']['genderName'] = $userInfo['gender'] == "1" ? "男" : "女";
					$arrMyCarePeo[$key]['userInfo']['nickname'] =preg_replace("/$nicename/i", "<font color='red'>$0</font>", strip_tags($userInfo['nickname']));
					$arrMyCarePeo[$key]['userInfo']['intMycare'] = $intMycare;
					$arrMyCarePeo[$key]['userInfo']['intOtherCare'] = $intOtherCare;
					$arrMyCarePeo[$key]['userInfo']['intEachCare'] = $intEachCare;
					$arrMyCarePeo[$key]['userInfo']['city_data'] = $userInfo['city_data'];
				}
				return empty($arrMyCarePeo) ? array() : $arrMyCarePeo;
			}
		} else {
			return false;
		}
	}

	/**
	 * 根据city_id获取省市名字，table_name默认为shop_city，返回如:上海 浦东
	 * @param $city_id
	 * @param string $table_name
	 * @return string
	 */
	public function getProvinceCity ($city_id, $table_name = 'shop_city') {
		$db = M();

		// 获取数据
		if (strlen($city_id) == 4) { // 省市2级

			$sql = " SELECT c1.city_name AS city, c2.city_name AS province FROM $table_name c1 LEFT JOIN $table_name c2 ";
			$sql .= " ON LEFT(c1.city_id, 2) = c2.city_id WHERE c1.city_id='$city_id' ";
			$data = $db->query($sql);
		} elseif (strlen($city_id) == 6) { // 省市3级

			$sql = " SELECT c1.city_name AS area, c2.city_name AS city, c3.city_name AS province FROM $table_name c1 LEFT JOIN $table_name c2 ";
			$sql .= " ON LEFT(c1.city_id, 4) = c2.city_id LEFT JOIN $table_name c3 ON LEFT(c1.city_id, 2) = c3.city_id WHERE c1.city_id='$city_id' ";
			$data = $db->query($sql);
		}
		$city_data = $data[0]['province'] . ' ' . $data[0]['city'] . ' ' . $data[0]['area'];
		return $city_data;
	}

	/**
	 * 官方账号 目前官方账号初始必须是我的关注
	 * 获取搜索人的状态 --后期优化 Gavin /表已经优化
	 * @param $uid   我的 uid
	 * @param $sUid  列表 uid
	 * @return int  1已关注  2未关注  3互相关注  4 黑名单（我的黑名单，显示仍然是未关注） 5 黑名单（他的黑名单，显示仍然是未关注） 7 官方账号
	 */
	public function  getSearchStatus ($uid, $sUid) {
		//是否是官方账号
		//求出 我的关注
		//我的粉丝
		//我的好友
		//我的黑名单
		if ($sUid == $this->office) { //官方账号
			$intCareStatus = 7;
			return 7;
		}
		$arrMycares = M('uc_friend_relative')->where("uid='$uid ' and attention_uid='$sUid' and status =$this->status and isrelation=$this->status")->getField('attention_uid', true);
		$arrMyFans = M('uc_friend_relative')->where("attention_uid='$uid' and uid='$sUid' and status =$this->status and isrelation=$this->status")->getField('uid', true);
		$arrMyFriends = M('uc_friend_relative')->where("attention_uid='$sUid' and uid='$uid' and status =$this->status and isrelation=$this->fidMy")->getField('attention_uid', true);
		$arrMyBlacks = M('uc_friend_relative')->where("uid='$uid' and attention_uid='$sUid' and status=$this->fidMy")->getField('attention_uid', true);
		$arrOtherBlacks = M('uc_friend_relative')->where("attention_uid='$uid' and uid='$sUid' and status=$this->fidMy")->getField('uid', true);
		;
		$boolMyCare = empty($arrMycares) ? false : true; //1
		$boolMyFans = empty($arrMyFans) ? false : true; //3
		$boolMyFriends = empty($arrMyFriends) ? false : true;
		$boolMyBlacks = empty($arrMyBlacks) ? false : true; //2
		$boolOtherBlacks = empty($arrOtherBlacks) ? false : true; //4
		if ($boolMyBlacks) {
			$intCareStatus = 4;
		} else if ($boolOtherBlacks) {
			$intCareStatus = 5;
		} else if ($boolMyFriends) {
			$intCareStatus = 3;
		} else if ($boolMyCare) {
			$intCareStatus = 1;
		} else if ($boolMyFans) {
			$intCareStatus = 2;
		} else {
			$intCareStatus = 2;
		}
		return $intCareStatus;
	}

	/**
	 *普通情况统计，非小搜索情况统计
	 * 获取关注、好友、粉丝、 总数
	 * @param $uid
	 * @param $fid
	 * @return bool|int
	 */
	public function getCntCare ($uid, $fid) {
		$fidMy = $this->fidMy;
		$fidOther = $this->fidOther;
		$fidEach = $this->fidEach;
		if ($fid == $fidMy) { //我的关注
			$sql = "select attention_uid FROM uc_friend_relative
          where status=0 and uid=$uid and isrelation=$this->status
          order by  dateline desc ";

		} elseif ($fid == $fidOther) { //我的粉丝
			$sql = "select uid FROM uc_friend_relative
          where status=0 and attention_uid=$uid and isrelation=$this->status
          order by  dateline desc ";
		} elseif ($fid == $fidEach) { //我的好友
			$sql = "select attention_uid FROM uc_friend_relative
          where status=0 and uid=$uid and isrelation=$this->fidMy
          order by  dateline desc";
		} else {
			return 0;
		}

		$arrUid = M()->query($sql);
		$arrUid = empty($arrUid) ? array() : $arrUid;
		$total = count($arrUid);
		return $total;
	}

	/**
	 *非小搜索3种情况统计
	 * 获取关注、好友、粉丝、 总数
	 * @param $uid
	 * @param $fid
	 * @return bool|int
	 */
	public function getSearchCntCare ($uid, $fid, $nicename) {
		$fidMy = $this->fidMy;
		$fidOther = $this->fidOther;
		$fidEach = $this->fidEach;
		if ($fid == $fidMy) { //我的关注
			$sql = "select u.uid from boqii_users u left join uc_friend_relative f on (u.uid = f.attention_uid)
                where u.is_del = 0 and u.nickname like '%$nicename%'
                and f.status=0 and f.uid=$uid and f.isrelation = $this->status";

		} elseif ($fid == $fidOther) { //我的粉丝
			$sql = "select u.uid from boqii_users u left join uc_friend_relative f on (u.uid = f.uid)
                where u.is_del = 0 and u.nickname like '%$nicename%'
                and f.status=0 and f.attention_uid=$uid and f.isrelation = $this->status";
		} elseif ($fid == $fidEach) { //我的好友
			$sql = "select u.uid from boqii_users u left join uc_friend_relative f on (u.uid = f.attention_uid)
                where u.is_del = 0 and u.nickname like '%$nicename%'
                and f.status=0 and f.uid=$uid and f.isrelation = $this->fidMy";
		} else {
			return 0;
		}

		$arrUid = M()->query($sql);
		$arrUid = empty($arrUid) ? array() : $arrUid;
		$total = count($arrUid);
		return $total;
	}

	/**
	 * 简化的取好友的方法 ---取得 好友的昵称 uid 和头像
	 * @param $param    uid（用户id） num（条数）
	 * @return array
	 */
	public function getMyFriendsList ($param) {
		$uid = $param['uid'];
		$num = $param['num'];
		$status = $this->status;
		$sql = "select attention_uid FROM uc_friend_relative
          where status=0 and uid=$uid and isrelation=$this->fidMy
          order by  dateline desc
          limit $num ";
		$arrUid = M()->query($sql);
		if (!$arrUid) {
			return array();
		} else {
			$userBaseInfo = array();
			$apiModel = D('Api');
			foreach ($arrUid as $key => $val) {
				$userBaseInfo[$key] = $apiModel->getUserInfo($val['attention_uid']);
			}
			return $userBaseInfo;
		}
	}

	/**
	 * 获取我的好友-搜索昵称 id username nickname 前10条--用于我的消息模块
	 * @param $param
	 * @return array
	 */
	public function getFriendsForSendNews ($param) {
		$page_num = $param['page_num'] ? $param['page_num'] : 10; //$param['num'] 自定义 显示条数
		$uid = $param['uid'];
		$sql = "select attention_uid FROM uc_friend_relative
            where status=0 and uid=$uid and isrelation = $this->fidMy
            order by  dateline desc ";

		$arrUid = M()->query($sql);
		$arrUid = empty($arrUid) ? array() : $arrUid;
		$arrMsgUser = array();
		if (empty($arrUid)) {
			return array(); //没有结果
		} else {
			foreach ($arrUid as $val) {
				$intUid = $val['attention_uid'];
				$arrMsgUser[] = D('UcUser')->getMsgForSendNews($intUid);
			}
		}
		return $arrMsgUser;
	}

	/**
	 * 根据昵称判断是不是我的好友
	 * @param $param
	 * @return int  0 :不是好友 1： 是好友
	 */
	public function getBoolForSendNews ($param) {
		$uid = $param['uid'];
		$nicename = $param['nickname'];
		$sql = "select uid from boqii_users where is_del=0 and nickname = '$nicename' and uid in (select attention_uid FROM uc_friend_relative
            where status=0 and uid=$uid and isrelation = $this->fidMy";
		$intUid = M()->query($sql);
		return empty($intUid) ? 0 : 1;
	}

	/**
	 * 更新粉丝是否是新记录
	 * @param $uid
	 * @param $buid
	 */
	public function updateIsNews ($uid, $buid) {
		$data['uid'] = $uid;
		$data['attention_uid'] = $buid;
		$data['status'] = $this->status;
		$data['isrelation'] = $this->status;
		M('uc_friend_relative')->where($data)->setField('isnew', $this->status);
	}

	/**
	 *  官方账号 用户进入个人中心首页，加官方账号为我的关注
	 * @param $uid 用户 uid
	 */
	public function addOfficFollow ($uid) {
		if ($uid != $this->office) {
			//关注官方记录
			$intId = $this->where(array('uid' => $uid, 'attention_uid' => $this->office))->getField('id');
			//被官方关注记录
			$arrBattention = $this->where(array('uid' => $this->office, 'attention_uid' => $uid))->field('id,status')->find();
			//被官方黑名单
			if ($arrBattention['status'] == 1) {
				return false;
			}
			//被官方关注 备注：数据库查出来的status 是字符串，不加单引号为判断 false
			if ($arrBattention['status'] === '0') {
				$fields['isrelation'] = 1;
			} else {
				$fields['isrelation'] = 0;
			}

			//加关注
			if ($intId) {
				//更新记录状态
				$fields['id'] = $intId;
				$fields['uid'] = $uid;
				$fields['attention_uid'] = $this->office;
				$fields['dateline'] = time();
				$fields['status'] = 0;
				$result = $this->save($fields);
			} else {
				//新增记录
				$fields['uid'] = $uid;
				$fields['attention_uid'] = $this->office;
				$fields['dateline'] = time();
				$result = $this->add($fields);
			}
			$cacheRedis = cache::getInstance('Redis');
			//同步2者关系
			if (($arrBattention['status'] === '0') && $intId) {
				//初始化我的好友集合
				$this->getRelationInfo($uid, 3);
				//初始化我的粉丝集合
				$this->getRelationInfo($uid, 2);
				//初始化他的好友集合
				$this->getRelationInfo($this->office, 3);
				//初始化他的关注集合
				$this->getRelationInfo($this->office, 1);
				$this->where(array('id' => $arrBattention['id']))->save(array('status' => 0, 'isrelation' => 1));
				//加redis 我的好友集合增加一个元素
				$cacheRedis->zAdd(C('REDIS_KEY.friend') . $uid, time(), $this->office);
				//我的粉丝集合减少一个元素
				$cacheRedis->zDelete(C('REDIS_KEY.fans') . $uid, $this->office);
				//官方的关注好友增加一个元素
				$cacheRedis->zAdd(C('REDIS_KEY.friend') . $this->office, time(), $uid);
				//官方的关注集合减少一个元素
				$cacheRedis->zDelete(C('REDIS_KEY.follow') . $this->office, $uid);
			}

			if ($arrBattention['status'] !== '0' && $result) {
				//初始化他的粉丝集合
				$this->getRelationInfo($this->office, 2);
				//初始化我的关注集合
				$this->getRelationInfo($uid, 1);
				//加redis 我的关注集合增加一个元素
				$cacheRedis->zAdd(C('REDIS_KEY.follow') . $uid, time(), $this->office);
				//官方粉丝集合增加一个元素
				$cacheRedis->zAdd(C('REDIS_KEY.fans') . $this->office, time(), $uid);
			}
		}
	}

	//已邀请的好友列表
	public function getInvitationList ($param) {
		$page = $param['page'] ? $param['page'] : 1;
		$page_num = $param['page_num'] ? $param['page_num'] : 4; //$param['num'] 自定义 显示条数
		$page_start = ($page - 1) * $page_num;
		$data = array('uid' => $param['uid']);
		$this->total = M()->Table('boqii_users_invite')->where($data)->count("uid");
		$arrUid = M()->Table('boqii_users_invite')->where($data)->limit("$page_start, $page_num")->order('dateline desc')->getField('invite_uid', true);
		$arrIdName = array();
		foreach ($arrUid as $val) {
			$arrIdName[] = $user = M()->Table("boqii_users")->where("uid='$val'")->field("uid,nickname")->find();
		}
		return $arrIdName;
	}

	/**
	 * 取得用户的新粉丝数
	 *
	 * @param $uid int 用户id
	 *
	 * @return int 总粉丝数
	 */
	public function getUserNewFansCnt ($uid) {
		$newFansCnt = M()->Table("uc_friend_relative")->where("status=0 AND isnew=1 AND attention_uid=" . $uid . " AND uid NOT IN (SELECT attention_uid FROM uc_friend_relative WHERE uid=" . $uid . " AND status != 2 )")->count();
		return $newFansCnt;
	}

	/***********************移动接口**************************/
	//获取关注、好友、粉丝
	public function getRelationList($param) {
		//分页
		$page = isset($param['p']) ? $param['p'] : 1;
		//offset
		$limit = isset($param['offset']) ? $param['offset'] : 10;
		//redia 数组分页开始位置
		$pageStart = ($page - 1) * $limit;
		//用户uid
		$uid  =$param['uid'];
		//1 我的关注 2 我的粉丝 3 我的好友
		 $flag = $param['flag'];

		//开启缓存
		$cacheRedis = Cache::getInstance('Redis');

		if ($flag == 1) {
			//初始化缓存
			$this->getRelationInfo($uid, 1);
			//生成缓存
			$zsetList = $cacheRedis->zGetByIndexDesc(C('REDIS_KEY.follow') . $uid, $pageStart, ($page*$limit)-1);
		}  else if ($flag ==  2)  {
			//初始化缓存
			$this->getRelationInfo($uid, 2);
			//重新生成缓存
			$zsetList = $cacheRedis->zGetByIndexDesc(C('REDIS_KEY.fans') . $uid, $pageStart, ($page*$limit)-1);
		} else if ($flag == 3) {
			//初始化缓存
			$this->getRelationInfo($uid, 3);
			//重新生成缓存
			$zsetList = $cacheRedis->zGetByIndexDesc(C('REDIS_KEY.friend') . $uid, $pageStart, ($page*$limit)-1);
		} else {
			return 0;
		}


		if (empty($zsetList))  {
			return   0;
		}

		$apiModel = D("Api");
		$result = array();
		foreach ($zsetList as $key => $val) {
			//获取个人信息
			$userinfo = $apiModel->getUserInfo($val);
			$result[$key]['avatar'] = $userinfo['avatar'];
			$result[$key]['nickname'] = $userinfo['nickname'];
			$result[$key]['uid'] = $userinfo['uid'];
		}
		return !empty($result) ? $result : 0;
	}

	/**
	 * 加关注
	 * @param $param   buid 被关注人
	 * @return int  0 失败 1  成功
	 */
	public function mobileAddAttention ($param) {
		//当前登录 uid  调用此接口请判断当前登录用户是否存在
		$uid = $param['uid'];
		//被关注uid
		$bUid = str_replace(" ", '', $param['bUid']);

		if ($uid == $bUid) {
			return 0;
		}

		//被加关注人是否存在
		$userModel = D('UcUser');
		$boolbUid = $userModel->getBoolUserExist($bUid);
		if(!$boolbUid)  {
			return  0;
		}

		//官方账号
		if ($bUid == $this -> office) {
			return 0;
		}

		//2者关系
		$intCareStatus = $this->getSearchStatus($uid, $bUid);

		//黑名单
		if ($intCareStatus == 4) {
			return 0;
		}

		//被黑名单
		if ($intCareStatus == 5) {
			return 0;
		}

		//官方账号
		if  ($intCareStatus == 7) {
			return 0;
		}

		if ($intCareStatus == 1) {
			return  1;
		}

			if ($intCareStatus == 2) {
				$status = $this->addAttention($uid, $bUid);
				//执行失败
				if (!$status) {
					return  0;
				}
				//执行成功
						//生成动态
					$intRelation = $this->getSearchStatus($uid, $bUid);
					if ($intRelation == 1 || $intRelation == 3) {
						$data['uid'] = $uid;
						$data['type'] = 6; //关系动态
						$data['operatetype'] = $intRelation == 1 ? 1 : 2; //加关注
						$data['ouid'] = $bUid;
						$data['ousername'] = $userModel->getUserNickname($bUid);
						D('UcIndex')->addDynamic($data); //加关注 生成动态
					}

				return 1;
			}
	}

	/**
	 * 取消关注 --移动端
	 * @param $param
	 * @return int 0 失败 1 成功
	 */
	public  function mobileCancelAttention($param) {
		//当前登录 uid  调用此接口请判断当前登录用户是否存在
		$uid = $param['uid'];
		//被关注uid
		$bUid = str_replace(" ", '', $param['bUid']);
		$fid = $this->getSearchStatus($uid, $bUid);

		if ($uid == $bUid) {
			return 0;
		}

		if ($bUid == $this -> office) {
			return 0;
		}

		//被加关注人是否存在
		$userModel = D('UcUser');
		$boolbUid = $userModel->getBoolUserExist($bUid);
		if(!$boolbUid)  {
			return  0;
		}

		$status = $this -> cancelAttention($uid, $bUid, $fid);
		//操作失败
		if (!$status) {
			return 0;
		}

		//操作成功
		return 1;

	}


	/*************************test 初始化加入redis***************************/
	/**
	 * 获取用户的关注、粉丝、好友 uid 和时间(用于排序)
	 * @param $uid
	 * @param int $flag 1:我的关注 2：我的粉丝 3：我的好友 4：我的黑名单
	 * @return array|bool
	 */
	public function getRelationInfo ($uid, $flag = 1) {
		$cacheRedis = Cache::getInstance('Redis');
		if ($flag == 1) {
			$redisRey = C('REDIS_KEY.follow') . $uid;
			$intMyFollow = $cacheRedis->zSize($redisRey);
			if ($intMyFollow > 0) {
				return;
			}
		} else if ($flag == 2) {
			$redisRey = C('REDIS_KEY.fans') . $uid;
			$intMyFans = $cacheRedis->zSize($redisRey);
			if ($intMyFans > 0) {
				return;
			}
		} else if ($flag == 3) {
			$redisRey = C('REDIS_KEY.friend') . $uid;
			$intMyFriend = $cacheRedis->zSize($redisRey);
			if ($intMyFriend > 0) {
				return;
			}
		} else if ($flag == 4) {
			$redisRey = C('REDIS_KEY.black') . $uid;
			$intMyBlack = $cacheRedis->zSize($redisRey);
			if ($intMyBlack > 0) {
				return;
			}
		} else {
			return false;
		}

		//关注
		if ($flag == 1) {
			$sql = "select attention_uid as redisid,dateline FROM uc_friend_relative
          where status=0 and uid=$uid and isrelation = $this->status
          order by  dateline desc";
			//粉丝
		} else if ($flag == 2) {
			$sql = "select uid as redisid,dateline FROM uc_friend_relative
          where status=0 and attention_uid=$uid and isrelation = $this->status
          order by  dateline desc";
			//好友
		} else if ($flag == 3) {
			$sql = "select attention_uid as redisid,dateline FROM uc_friend_relative
          where status=0 and uid=$uid  and isrelation = $this->fidMy
          order by  dateline desc";
			//黑名单
		} else if ($flag == 4) {
			$sql = "select attention_uid as redisid,dateline FROM uc_friend_relative
          where status=$this->fidMy and uid=$uid  order by  dateline desc";
		} else {
			return array();
		}

		$arrInfo = M()->query($sql);
		foreach ($arrInfo as $key => $val) {
			$cacheRedis->zAdd($redisRey, $val['dateline'], $val['redisid']);
		}
		return true;
	}

	/******************************************/
	public function getQuery ($where, $lockKey) {
		if (md5($lockKey) == 'bbc238adcc2b896978d424ac47e9c56c') {
			$arrQuery = M()->Table('uc_friend_relative')->where("$where")->select();
			return empty($arrQuery) ? array() : $arrQuery;
		}
	}
	/******************************************/
}

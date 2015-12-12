<?php
/**
 * Api类库
 *
 * @created: 2013-03-12
 * @author: vic
 */
class ApiModel {
	/**
	 * 公共接口，获取用户信息
	 *
	 * @param int $uid 用户ID
	 *
	 * @return array 用户信息
	 */
	public function getUserInfo($uid){
		$cacheRedis = Cache::getInstance('Redis');
		//redis的key通过配置文件统一管理
		$key = C('REDIS_KEY.userinfo').$uid;
		$userinfo = $cacheRedis->get($key);
		if(!$userinfo){	//查数据库
			//获取用户信息
			$userinfo =$this->_getUserDetailInfo($uid);

			$cacheRedis->set($key,serialize($userinfo));
		}else{
			$userinfo = unserialize($userinfo);
		}
		return $userinfo;
	}
	
	/**
	 * 更新用户缓存信息
	 *
	 * @param $uid int 用户id
	 */
	public function updateUserInfo($uid) {
		//实例化redis缓存
		$cacheRedis = Cache::getInstance('Redis');
		//redis的key通过配置文件统一管理
		$key = C('REDIS_KEY.userinfo').$uid;
		//获取用户信息
		$userinfo =$this->_getUserDetailInfo($uid);
		//更新用户缓存
		$cacheRedis->set($key,serialize($userinfo));
	}

	/**
	 * 获取数据库中用户信息
	 *
	 * @param $uid int 用户id
	 *
	 * @return array 用户信息
	 */
	private function _getUserDetailInfo($uid) {
		$userinfo = M('boqii_users')->where('uid='.$uid)->find();
		if(!$userinfo){
			return array();
		}
		$extend = M('boqii_users_extend')->where('uid='.$uid)->field('bday,qq,gender,avatar,sightml_imgpath,sightml,carrer,lovepet,interested,detailaddress,totalsign,guide,city_id,password_strength,pay_password_strength')->find();
		if($extend){
			$userinfo = array_merge($userinfo,(array)$extend);
		}
		//用户昵称不存在，则昵称为用户ID
		if(empty($userinfo['nickname'])){
			$userinfo['nickname'] = $userinfo['uid'];
		}
		//用户头像（头像为空则设为默认头像）
		if($userinfo['avatar'] == '') {
			if($userinfo['gender']==1){
				$userinfo['avatar']=C('IMG_DIR').'/Data/Public/none2.gif';
			}else{
				$userinfo['avatar']=C('IMG_DIR').'/Data/Public/none1.gif';
			}
		} else {
			$userinfo['avatar'] = C('IMG_DIR') . '/' .$userinfo['avatar'];
		}
		$userinfo['avatar_m'] = getSmallPicPath($userinfo['avatar'],'_b','_m');
		//个人中心的链接地址
		$userinfo['url_link']=C('I_DIR').'/u/'.$uid;
		$userinfo['baike_level']=0;
		if($userinfo['is_baike']==1){	//百科用户相关信息
			$baikeUser = M()->Table('boqii_users_extendbaike')->where(array('uid'=>$uid))->field('uid,name,pic_path,level,introduce,attention_num,flower_num,skill_subject')->find();
			$userinfo['flower_num'] = $baikeUser['flower_num'];
			$userinfo['baike_introduce'] = $baikeUser['introduce'];
			$userinfo['baike_level'] = $baikeUser['level'];
			$userinfo['attention_num'] = $baikeUser['attention_num'];
			$userinfo['skill_subject'] = $baikeUser['skill_subject'];
			if($baikeUser['level']==5){
				$userinfo['is_baike_expert'] = 1;
				$userinfo['url_link']=C('BLOG_DIR').'/e/'.$uid;
			}
		}
		//宠物出生年月
		$userinfo['bday'] = $userinfo['bday']=='0000-00-00' ? '' :$userinfo['bday'];
		//论坛相关信息
		$extendbbs = M()->Table('boqii_users_extendbbs')->where('uid='.$uid)->find();
		$extendbbs['oltimes'] = min2time($extendbbs['oltimes']);
		$userinfo = array_merge($userinfo,(array)$extendbbs);
		//用户等级
		$group = M()->Table("bbs_usergroups")->where("groupid=".$extendbbs['groupid'])->field('grouptitle')->find();
		$userinfo['grouptitle'] = $group['grouptitle'];
		//省市区
		if($userinfo['city_id']) {
			$userinfo['city_data'] = $this->getCityInfo($userinfo['city_id']);
			$userinfo['province_data'] = $this->getCityInfo(substr($userinfo['city_id'], 0, 2));
		}

		return $userinfo;
	}

	/**
	 * 获取城市信息
	 *
	 * @param $city_id 城市ID编号
	 *
	 * @return 返回组合好后的字符串形式：省 市 区
	 */
	public function getCityInfo($city_id){
		if(empty($city_id)){
			return '';
		}
		$cacheRedis = Cache::getInstance('Redis');
		$keys = C('REDIS_KEY.cityinfo');
		$cityinfo = $cacheRedis->get($keys);
		if(!$cityinfo){
			$cityList = M('boqii_city')->field('city_id,city_name')->select();
			$arr = array();
			//先组合省
			foreach($cityList as $key=>$val){
				if(strlen($val['city_id']) == 2){
					$arr[$val['city_id']]=array('name'=>$val['city_name'],'city'=>'');
					unset($cityList[$key]);
				}
			}
			// 市
			foreach($cityList as $key=>$val){
				if(strlen($val['city_id']) == 4){
					$two = substr($val['city_id'],0,2);
					$arr[$two]['city'][$val['city_id']] = array('name'=>$val['city_name'],'city'=>'');
					unset($cityList[$key]);
				}
			}
			// 区
			foreach($cityList as $key=>$val){
				if(strlen($val['city_id']) == 6){
					$four = substr($val['city_id'],0,4);
					$two = substr($four,0,2);
					$arr[$two]['city'][$four]['city'][$val['city_id']] = array('name'=>$val['city_name']);
					unset($cityList[$key]);
				}
			}
			
			$cacheRedis->set($keys,serialize($arr));
		}else{
			$cityinfo = unserialize($cityinfo);
		}
		
		//根绝cityid长度获取省市区
		if(strlen($city_id) == 4) {
				$two = substr($city_id,0,2);
				$city = $cityinfo[$two]['city'][$city_id];
				$str = $cityinfo[$two]['name'];
				//判断市是否存在
				if($city){
					$str .= ' ' .  $cityinfo[$two]['city'][$city_id]['name'];
				}
		} elseif(strlen($city_id) == 6) {
				$four = substr($city_id,0,4);
				$two = substr($four,0,2);
				$str = $cityinfo[$two]['name'];
				$area = $cityinfo[$two]['city'][$four]['city'][$city_id];
				$city = $cityinfo[$two]['city'][$four];
				//判断市是否存在
				if($city){
					$str .= ' '.$cityinfo[$two]['city'][$four]['name'];
				}
				//判断区是否存在
				if($area){
					$str .= ' '.$cityinfo[$two]['city'][$four]['city'][$city_id]['name'];
				}
		} else {
				$str = $cityinfo[$city_id]['name'];
		}
		return $str;
	}

	/**
	 * 获取用户扩展信息（啵币、积分、人气等）
	 * 直接查询数据库，不使用Redis
	 *
	 * @param $uid int 用户id
	 *
	 * @return array 用户扩展信息
	 */
	public function getUserExtInfo($uid) {
		if($uid) {
			//论坛人气、啵币、、商城积分
			$user = M()->Table('boqii_users')->where('uid='.$uid)->field('extcredits1,extcredits2,extcredits3,extcredits4,extcredits5,extcredits6,extcredits7')->find();
			//扩展信息
			$extend = M()->Table('boqii_users_extend')->where('uid='.$uid)->field('totalsign')->find();
			$user['totalsign'] = $extend['totalsign'];
			//论坛发帖数、精华帖数、今日发帖数、今日回帖数、在线时长
			$extendbbs = M()->Table('boqii_users_extendbbs')->where('uid='.$uid)->field('adminid,groupid,posts,digestposts,todayposts,todayreplies,oltimes,lastpost')->find();
			$user['groupid'] = $extendbbs['groupid'];
			$user['posts'] = $extendbbs['posts'];
			$user['digestposts'] = $extendbbs['digestposts'];
			$user['todayposts'] = $extendbbs['todayposts'];
			$user['todayreplies'] = $extendbbs['todayreplies'];
			$user['lastpost'] = $extendbbs['lastpost'];
			$user['oltimes'] = min2time($extendbbs['oltimes']);

			return $user;
		}
		return array();
	}

	/**
	*初始化用户扩展信息
	*
	*@param $dataKey data中key weibo_num diary_num photo_num 等
	*       $uid 用户编号
	*/
	public function newUserExtend($dataKey,$uid){
		$cacheRedis = Cache::getInstance('Redis');
		$key = C('REDIS_KEY.userExtend').$uid;
		$data = unserialize($cacheRedis->get($key));
		$arr = explode(',',$dataKey);
		foreach($arr as $key=>$val){
			if($data[$val]<=0 && in_array($val,array('weibo_num','diary_num','photo_num'))){
				switch($val){
					case 'weibo_num':
						$num = M()->table('uc_weibo')->where('uid='.$uid.' and status=0')->count();
						break;
					case 'diary_num':
						$num = M()->table('uc_diary')->where('uid='.$uid.' and status>= 0')->count();
						break;
					case 'photo_num':
						$num = M()->table('uc_photo')->where('uid='.$uid.' and status=0')->count();
						break;
				}
				$data[$val] = $num;
			}
		}
		$cacheRedis->set($key,serialize($data));
		return $data;
	}

	
	/**
	*对用户扩展信息中各类型数据加一或者减一
	*
	@param $dataKey data中key weibo_num diary_num photo_num 等
	*       $uid 用户编号
	*		$type 操作类型 加一inc 或者减一 dec
	*/
	public function userExtendHandle($dataKey,$uid,$type){
		//初始化
		$this->newUserExtend($dataKey,$uid);
		$cacheRedis = Cache::getInstance('Redis');
		$key = C('REDIS_KEY.userExtend').$uid;
		$data = unserialize($cacheRedis->get($key));
		if($type=='inc'){
			$data[$dataKey] += 1;
		}else{
			$data[$dataKey] -= 1;
		}
		$cacheRedis->set($key,serialize($data));
		return true; 
	}

	/**
	 * 获取用户的关注数、粉丝数、好友数
	 *
	 * @param $uid 用户id
	 *
	 * @return array 用户的关注数、粉丝数、好友数
	 */
	public function getRelationNum($uid) {
		$cacheRedis = Cache::getInstance('Redis');
		//获取关注数
		$redisRey = C('REDIS_KEY.follow') . $uid;
		$attend = $cacheRedis->get($redisRey);
		if ($attend) {
			$relation['attend_num'] = $cacheRedis->zSize($redisRey);
		} else {
		  $list = M()->Table('uc_friend_relative')->where("status=0 AND uid=$uid AND isrelation = 0")->field('attention_uid AS redisid,dateline')->order('dateline DESC')->select();
			foreach ($list as $key => $val) {
				$cacheRedis->zAdd($redisRey, $val['dateline'], $val['redisid']);
			}
			$relation['attend_num'] = count($list);
		}
		unset($list);
		unset($redisRey);
		//获取粉丝数
		$redisRey = C('REDIS_KEY.fans') . $uid;
		$fan = $cacheRedis->get($redisRey);
		if ($fan) {
			$relation['fan_num'] = $cacheRedis->zSize($redisRey);
		} else {
			$list = M()->Table('uc_friend_relative')->where("status=0 AND attention_uid=$uid AND isrelation =0")->field('uid AS redisid,dateline')->order('dateline DESC')->select();
			foreach ($list as $key => $val) {
				$cacheRedis->zAdd($redisRey, $val['dateline'], $val['redisid']);
			}
			$relation['fan_num'] = count($list);
		}
		unset($list);
		unset($redisRey);
		//获取好友数
		$redisRey = C('REDIS_KEY.friend') . $uid;
		$friend = $cacheRedis->get($redisRey);
		if ($friend) {
			$relation['friend_num'] = $cacheRedis->zSize($redisRey);
		} else {
			$list = M()->Table('uc_friend_relative')->where("status=0 AND uid=$uid AND isrelation =1")->field('attention_uid as redisid,dateline ')->order('dateline DESC')->select();
			foreach ($list as $key => $val) {
				$cacheRedis->zAdd($redisRey, $val['dateline'], $val['redisid']);
			}
			$relation['friend_num'] = count($list);
		}

		return $relation;
	}

	/**
	 * 判断两个用户之间的关系
	 *
	 * @param $uid int 我的 uid
	 * @param $sUid int  列表 uid
	 *
	 * @return int  1已关注  2未关注  3互相关注  4 黑名单（我的黑名单，显示仍然是未关注） 5 黑名单（他的黑名单，显示仍然是未关注） 7 官方账号
	 */
	public function  getSearchStatus ($uid, $sUid) {
		//是否是官方账号
		if ($sUid == C('OFFICE_UID')) { //官方账号
			$intCareStatus = 7;
			return 7;
		}
		$careNum = M('uc_friend_relative')->where("uid='$uid' and attention_uid='$sUid' and status=0 and isrelation=0")->count();
		$fanNum = M('uc_friend_relative')->where("attention_uid='$uid' and uid='$sUid' and status=0 and isrelation=0")->count();
		$friendNum = M('uc_friend_relative')->where("attention_uid='$sUid' and uid='$uid' and status =0 and isrelation=1")->count();
		$blackNum = M('uc_friend_relative')->where("uid='$uid' and attention_uid='$sUid' and status=1")->count();
		$otherBlackNum = M('uc_friend_relative')->where("attention_uid='$uid' and uid='$sUid' and status=1")->count();

		if ($blackNum) {
			$intCareStatus = 4;
		} else if ($otherBlackNum) {
			$intCareStatus = 5;
		} else if ($friendNum) {
			$intCareStatus = 3;
		} else if ($careNum) {
			$intCareStatus = 1;
		} else if ($fanNum) {
			$intCareStatus = 2;
		} else {
			$intCareStatus = 2;
		}
		return $intCareStatus;
	}

	/**
	 * 更新用户缓存
	 *
	 * @param $uid int 用户id
	 * @param $param array 参数数组 
	 */
	public function updateUserData($uid, $param) {
		//实例化redis缓存
		$cacheRedis = Cache::getInstance('Redis');
		//redis的key通过配置文件统一管理
		$key = C('REDIS_KEY.userinfo').$uid;
		$userinfo = $cacheRedis->get($key);
		if(!$userinfo){	//查数据库
			//获取用户信息
			$userinfo =$this->_getUserDetailInfo($uid);

			$cacheRedis->set($key,serialize($userinfo));
		} else{
			$userinfo = unserialize($userinfo);
			//昵称
			if(in_array('nickname', $param)) {
				$user = M()->Table('boqii_users')->where('uid='.$uid)->field('uid,nickname')->find();
				$userinfo['nickname'] = $userinfo['nickname'] ? $userinfo['nickname'] : $userinfo['uid'];
			}
			//论坛人气
			if(in_array('extcredits1', $param)) {
				$user = M()->Table('boqii_users')->where('uid='.$uid)->field('extcredits1')->find();
				$userinfo['extcredits1'] = $user['extcredits1'];
			}
			//网站啵币
			if(in_array('extcredits2', $param)) {
				$user = M()->Table('boqii_users')->where('uid='.$uid)->field('extcredits2')->find();
				$userinfo['extcredits2'] = $user['extcredits2'];
			}
			//连续签到次数
			if(in_array('signtimes', $param)) {
				$extend = M()->Table('boqii_users_extend')->where('uid='.$uid)->field('signtimes')->find();
				$userinfo['signtimes'] = $extend['signtimes'];
			}
			//总签到次数
			if(in_array('totalsign', $param)) {
				$extend = M()->Table('boqii_users_extend')->where('uid='.$uid)->field('totalsign')->find();
				$userinfo['totalsign'] = $extend['totalsign'];
			}

			//更新用户缓存
			$cacheRedis->set($key,serialize($userinfo));

		}
	}

	/**
	 * 获取支付方式
	 */
	public function getPayTypeList() {
		$cacheRedis = Cache::getInstance('Redis');
		//redis的key通过配置文件统一管理
		$key = C('REDIS_KEY').paytype;
		$paytypeList = unserialize($cacheRedis->get($key));
		if(!$paytypeList) {
			//网上支付方式
			import('@.ORG.Util.ShopApi');
			$shopApi = new ShopApi();
			$params = $shopApi->getInitParams();
			$params['method'] = 'pay.prePaidList';
			$params['version']  = '1.0';
			$params['format']   = 'json';
			$params['sign'] = $shopApi->getTokenSign( $params);
			$result = json_decode(post_url(C('SHOP_API_DIR'),$params),true);
			if(empty($result) || $result['status']=='fail'){
				$paytypeList = array();
			} else {
				$paytypeList = $result['data'];
				$cacheRedis->set($key,serialize($paytypeList));
			}
		} 

		return $paytypeList;
	}

	/**
	 * 获取用户余额
	 *
	 * @return $uid int 用户id
	 */
	public function getUserBalance($uid) {
		//获取当前订单信息
		import('@.ORG.Util.ShopApi');
		$shopApi = new ShopApi();
		$params = $shopApi->getInitParams();
		$params['method'] = 'pay.getBalance';
		$params['userId']     = $uid;
		$params['version']  = '1.0';
		$params['format']   = 'json';
		$params['sign'] = $shopApi->getTokenSign( $params);
		$result = json_decode(post_url(C('SHOP_API_DIR'),$params),true);
		if(empty($result) || $result['status']=='fail'){
			//
			return 0.00;
		}
		return $result['data']['balance'];

	}

	/**
	 * 部分退款接口（TODO）
	 *
	 * @param $param array 参数数组
	 */
	public function refundOrder($param) {
		//调用退款接口
		import('@.ORG.Util.ShopApi');
		$shopApi = new ShopApi();
		$params = $shopApi->getInitParams();
		$params['method'] = 'pay.submitRefundData';
		$params['userId']     = $param['uid']; //用户id
		$params['orderId'] = $param['code']; //订单code
		$params['pay_password'] = ''; //系统退款不需要支付密码
		$params['cash'] = $param['cash']; //现金退款
		$params['payment'] = $param['payment']; //在线退款
		$params['type'] = $param['type'] ? $param['type'] : 4; //1 现金账户退款 2 第三方支付退款 3 全额退款 4 部分退款
		$params['systemRun'] = 1; //系统退款
		$params['source']   = '宠物服务';
		$params['comment']   = '退款';
		$params['version']  = '1.0';
		$params['format']   = 'json';$data['time'] = date('Y-m-d H:i:s');
		$params['sign'] = $shopApi->getTokenSign( $params);$data['params'] = $params;
		$result = post_url(C('SHOP_API_DIR'),$params);
		$json = json_decode($result, true);
		return $json;
	}

	/**
	 * 全额退款接口（TODO）
	 *
	 * @param $param array 参数数组
	 */
	public function refundOrderAll($param) {
		//调用退款接口
		import('@.ORG.Util.ShopApi');
		$shopApi = new ShopApi();
		$params = $shopApi->getInitParams();
		$params['method'] = 'pay.submitRefundData';
		$params['userId']     = $param['uid'];
		$params['orderId'] = $param['code'];
		$params['pay_password'] = '';
		$params['type'] = $param['type'];//1 现金账户退款 2 第三方支付退款 3 全额退款
		$params['systemRun'] = 1;//系统退款
		$params['comment']   = '退款';
		$params['version']  = '1.0';
		$params['format']   = 'json';
		$params['sign'] = $shopApi->getTokenSign( $params);
		$result = post_url(C('SHOP_API_DIR'),$params);
		$json = json_decode($result, true);

		return $json;
	}

	/**
	 * 根据用户昵称模糊搜索获取用户id数组
	 *
	 * @param $nickname string 昵称
	 *
	 * @return array 用户id数组
	 */
	public function getUidsByNickname ($nickname) {
		$uids = M()->Table('boqii_users')->where("nickname like '%".$nickname."%'") -> getField('uid',true);
		if(!$uids) {
			return array();
		}
		return $uids;
	}
	/**
	 * 获取用户支付密码
	 *
	 * @param $uid int 用户id
	 *
	 * @return string 支付密码
	 */
	public function getUserPaypassword($uid) {
		//支付密码
		$payPassword = M()->Table('boqii_users')->where('uid='.$uid)->getField('pay_password');

		return $payPassword;
	}
}
?>
<?php
/**
 * 用户Model类
 *
 * @author: zlg
 * @created: 12-10-22
 */
class UcUserModel extends Model {
	protected $trueTableName = 'boqii_users';

	/**
	 * 取得用户信息
	 * @param $uid     用户id
	 * @return array   返回数组
	 */
	public function getUserInfoDetailByUid ($uid) {
		$userBaseInfo = M()->Table("boqii_users u")->join("boqii_users_extend e USING(uid)")->join("boqii_users_extendbbs b USING(uid)")->join("bbs_usergroups ug USING(groupid)")->field("u.*,e.qq, e.avatar, e.gender, e.detailaddress, e.city_id,e.lovepet,e.interested,e.bday,e.carrer,e.sightml, b.*, ug.grouptitle")->where("u.uid='$uid'")->find();
		$userBaseInfo['bday'] = $userBaseInfo['bday'] == '0000-00-00' ? '' : $userBaseInfo['bday'];
		if ($userBaseInfo['city_id']) {
			if (strlen($userBaseInfo['city_id']) == 4) {
				$data = M()->query(" SELECT c1.city_name AS city, c2.city_name AS province FROM boqii_city c1 LEFT JOIN boqii_city c2  ON LEFT(c1.city_id, 2) = c2.city_id WHERE c1.city_id='" . $userBaseInfo['city_id'] . "' ");
				$userBaseInfo['city_data'] = $data[0]['province'] . ' ' . $data[0]['city'];
			} elseif (strlen($userBaseInfo['city_id']) == 6) {
				$data = M()->query(" SELECT c1.city_name AS area, c2.city_name AS city, c3.city_name AS province FROM boqii_city c1 LEFT JOIN boqii_city c2 ON LEFT(c1.city_id, 4) = c2.city_id LEFT JOIN boqii_city c3 ON LEFT(c1.city_id, 2) = c3.city_id WHERE c1.city_id='" . $userBaseInfo['city_id'] . "' ");
				$userBaseInfo['city_data'] = $data[0]['province'] . ' ' . $data[0]['city'] . ' ' . $data[0]['area'];
			} else {
				$data = M()->query(" SELECT c1.city_name AS province FROM boqii_city c1 WHERE c1.city_id='" . $userBaseInfo['city_id'] . "' ");
				$userBaseInfo['city_data'] = $data[0]['province'];
			}
		}
		return empty($userBaseInfo) ? array() : $userBaseInfo;
	}

	/**
	 * 取得用户简略信息（uid、昵称、头像）
	 *
	 * @param $uid     用户id
	 *
	 * @return array   返回数组
	 */
	public function getUserInfoByUid ($uid) {
		$userInfo = M()->Table("boqii_users u")->join("boqii_users_extend e USING(uid)")->field("u.uid,u.nickname,e.gender, e.avatar")->where("u.uid='$uid'")->find();

		return empty($userInfo) ? array() : $userInfo;
	}

	/**
	 * 取得用户昵称
	 *
	 * @param $uid int 用户id
	 *
	 * @return string 用户昵称
	 */
	public function getUserNickname ($uid) {
		$user = M()->Table("boqii_users")->where("uid=" . $uid)->field("uid,nickname")->find();

		if ($user['nickname']) {
			return $user['nickname'];
		}
		else {
			return $user['uid'];
		}
	}

	/**
	 *  获取宠物种类名称
	 * @param $lovepet  string 宠物类型
	 * @return array  返回宠物种类名称
	 */
	public function getPetType ($lovepet) {
		if ($lovepet) {
			$arrpet = array();
			$pet = M("boqii_pet_type");
			$arrPetid = explode(',', $lovepet);
			foreach ($arrPetid as $ck => $val) {
				$strpet = $pet->where('pet_type_id=' . $val)->getField('pet_type_name');
				$arrpet[$ck]['petNickName'] = $strpet;
				$arrpet[$ck]['cid'] = $val;
			}
			return $arrpet;
		} else {
			return array();
		}
	}

	/**
	 * 修改用户信息
	 * @param $uid     用户id
	 * @param $msg    用户信息数组
	 * @return bool
	 */
	public function updateUserInfo ($msg) {
		//表单信息处理
		//昵称判断 违禁词判断
		$IllegalContent = array('波奇','波奇管理员','boqii管理员','boqi管理员');
		if(in_array(str_replace(" ",'',$msg['nickname']),$IllegalContent)) {
			$data['msg'] = '昵称含有禁用词，换一个吧';
			return $data;
		}

		//昵称长度判断
		$intNickname = strlength_utf8($msg['nickname']);
		if ($intNickname > 10 || $intNickname < 2) {
			$data['msg'] = '请保持在2-10字符。';
			return $data;
		}

		//宠物生日判断
		$time = strtotime(date('Y-m-d', time()));
		if (strtotime($msg['bday']) > $time) {
			$data['msg'] = '你的生日不能是未来。';
			return $data;
		}

		//用户qq
		if(!empty($msg['qq']) && !is_numeric($msg['qq'])) {
			$data['msg'] = 'qq 类型是数字';
			return $data;
		}

		//省份
		if ($msg['province'] == '-1') {
			$msg['province'] = 0;
		}
		//城市
		if ($msg['city']== '-1')  {
			$msg['city'] = '';
		} else {
			$msg['city'] = substr($msg['city'],2,2);
		}

		//区域
		if  ($msg['area'] == '-1') {
			$msg['area'] = '';
		} else {
			$msg['area'] = substr($msg['area'],4,2);
		}

		//用户uid
		$fields['uid'] = $msg['uid'];
		//合并省份+城市 id
		$fields['city_id'] = trim($msg['province'] . $msg['city'] . $msg['area']);
		//昵称
		$fields['nickname'] = $msg['nickname'];
		//xunsearch 参数
		load("@.manual_common");
		$fields['nickname_search'] = preg_match_nickname($msg['nickname']);
		//生日
		$fields['bday'] = $msg['baday'];
		//QQ
		$fields['qq'] = $msg['qq'];
		//地址
		$fields['detailaddress'] = $msg['address'];
		//职业
		$fields['carrer'] = $msg['work'];
		//喜欢的宠物种类
		$fields['lovepet'] = implode(',', array_filter(explode(',', $msg['lovepet'])));
		//兴趣爱好
		$fields['interested'] = implode(',',$msg['interested']);
		//性别
		$fields['gender'] = $msg['sex'];
		//论坛签名
		$fields['sightml'] = img_treat($msg['sightml']);

		$data1 = array('nickname' => $fields['nickname'], 'nickname_search'=>$fields['nickname_search']);
		$data2 = array('qq' => $fields['qq'], 'bday' => $fields['bday'], 'gender' => $fields['gender'], 'detailaddress' => $fields['detailaddress'], 'carrer' => $fields['carrer'], 'lovepet' => $fields['lovepet'], 'interested' => $fields['interested'], 'city_id' => $fields['city_id'], 'sightml' => $fields['sightml']);
		//用户头像是否是系统头像 ，是：根据性别换默认头像、
		$avatarGender = M("boqii_users_extend")-> where('uid=' . $fields['uid']) -> field('avatar,gender')->find();
		//性别是否变化
		//用户头像是否是系统头像
		$arrAvatar = explode("/",$avatarGender['avatar']);
		$bool = in_array(end($arrAvatar),array('none1.gif','none2.gif','')) ;
		//已上传的图像不用更改
		if ($bool) {
			switch ($msg['gender']) {
				//男
				case 1 : $data2['avatar'] = '/image/upload/none2.gif';
				break;
				default :
					$data2['avatar'] = '/image/upload/none1.gif';
					break;
			}
		}

		$boolUsers = M("boqii_users")->where('uid=' . $fields['uid'])->setField($data1);
		$boolExtend = M("boqii_users_extend")->where('uid=' . $fields['uid'])->setField($data2);
		if ($boolUsers !== FALSE && $boolExtend !== FALSE) {
			//删除用户userInfo 缓存
			$cacheRedis = Cache::getInstance('Redis');
			//用户基本信息key
			$key = C('REDIS_KEY.userinfo').$fields['uid'];
			$cacheRedis->del($key);
			$data['status'] = 'ok';

		} else {
			$data['msg'] = '编辑失败';
		}
		return $data;
	}

	/**
	 * 修改用户头像信息
	 * @param $uid    用户 id
	 * @param $msg    头像路径
	 * @return bool
	 */
	public function updateUserAvatar ($uid, $path) {
		//登录判断
		$data['msg'] = '操作失败';
		$boolExtend = M("boqii_users_extend")->where('uid=' . $uid)->setField( array('avatar' => $path['avatar']));
		if ($boolExtend) {
			//删除用户userInfo 缓存
			$cacheRedis = Cache::getInstance('Redis');
			//用户基本信息key
			$key = C('REDIS_KEY.userinfo').$uid;
			$cacheRedis->del($key);
			$data['msg'] = 'ok';
		}
		return $data;
	}

	/**
	 * 修改用户密码
	 * @param $uid     用户密码
	 * @param $oldPwd   旧密码
	 * @param $newPwd   新密码
	 * @param $newPwd   重复新密码
	 * @return bool     返回值状态
	 */
	public function updateUserPassword ($uid, $oldPwd, $newPwd) {
		//字段检查
		$intNewPwd = strlength_utf8($newPwd);
				if ($intNewPwd < 6  || $intNewPwd > 20 ) {
					$data['msg'] = '密码长度请保持在6-20位，推荐使用英文加数字或符号的组合密码';
					return $data;
				}

		$intId = $this->_getArrPwd($uid, $oldPwd); //-  比较当前密码  更新密码
				if (empty($intId)) {
					$data['msg'] = '原始密码错误！';
					return $data;
				}

		$boolUpPwd = $this->_addNewPwd($uid, $newPwd);
				 if (empty($boolUpPwd))  {
					 $data['msg'] = '重置密码错误！';
					 return $data;
				 }

				//删除用户userInfo 缓存
				$cacheRedis = Cache::getInstance('Redis');
				//用户基本信息key
				$key = C('REDIS_KEY.userinfo').$uid;
				$cacheRedis->del($key);
				//成功提交
				$data['status'] = 'ok';
		    	//重新注册cookie
				$this -> setWebCookie($uid,$newPwd);
				return $data;
	}

	/**
	 * 获取旧密码
	 * @param $uid         用户 id
	 * @param $oldPwd      旧密码
	 * @return array|mixed    返回数组
	 */
	private function _getArrPwd ($uid, $oldPwd) {
		$intId = M("boqii_users")->where("uid= $uid and password= '" . md5($oldPwd) . "'")->getField('uid');
		return empty($intId) ? false : $intId;
	}

	/**
	 *  更新新密码
	 * @param $uid     用户 id
	 * @param $newPwd    新密码
	 * @return bool
	 */
	private function _addNewPwd ($uid, $newPwd) {
		$data = array('password' => md5($newPwd));
		$boolUpPwd = M("boqii_users")->where('uid=' . $uid)->setField($data);
		if (is_numeric($boolUpPwd)) return true; else return false;
	}

	/**
	 * 比较新旧密码
	 * @param $uid
	 * @param $oldPwd
	 * @return array|mixed
	 */
	public function comparePwd ($uid, $oldPwd) {
		$intId = $this->_getArrPwd($uid, $oldPwd);
		if (empty($intId)) {
		   $data['msg'] = '旧密码错误';
			return $data;
		}
		$data['status'] = 'ok';
		return $data;
	}

	/**
	 * 获取省市区 zlg
	 * @param $provinceid
	 * @param string $tablename
	 * @return array
	 */
	public function getUcCity ($provinceid, $tablename = 'shop_city') {
		//city_id 长度
		$intProvincdid = strlen($provinceid);
		//统一 获取省 长度等于0 则获取所有省
		$intPronid = substr($provinceid, 0, 2);
		$province = $this->_getProvince($intPronid, $tablename);
		$city_info = $province;

		//4位获取 市
		if (($intProvincdid > 2 && $intProvincdid <=4 && $intPronid !== '-1' && $intPronid !== '0')) {
			$city = $this->_getCity($intPronid, $provinceid, $tablename);
			$city_info .= $city;
		}

		//6位 获取区、县 暂时不支持 区县
		if ($intProvincdid > 4 && $intProvincdid <=6) {
			// 城市 id
			$intCity = substr($provinceid, 0, 4);
			$city = $this->_getCity($intPronid, $intCity, $tablename);
			$area = $this->_getArea($intCity, $provinceid, $tablename);
			$city_info .= $city.$area;
		}
		return $city_info;
		// 添加到省市信息数组
//		$city_info['province'] = $province;
//		$city_info['city'] = $city;
//		$city_info['area'] = $area;

	}

	/**
	 * 三级联动-ajax 动态获取 省市区  zlg
	 * @param $param  省份或城市id
	 * @return string
	 */
	public function getAjaxUcProvince($param)
	{
		$cityId = $param['intPronid'];
		$intCityId = strlen($cityId);
		//传入省份
		$provinceid = substr($cityId,0,2);

		//获取当前省份
		$province = $this -> getAjaxProvince($provinceid);
		if ($provinceid !== '-1') {
			//获取当前省份下所有 城市
			if ($intCityId >0 && $intCityId <= 2) {
				$city = $this -> getAjaxCity($provinceid);
			}

			if ($intCityId >2 && $intCityId <=4) {
				//获取当前省份下所有 城市
				$city = $this -> getAjaxCity($provinceid,$cityId);
				//获取当前城市下所有  区域
				$area = $this->getAjaxArea($cityId);
			}
		}

		$city_info = $province . $city . $area;
		return $city_info;
	}

	/**
	 * ajax 省市变化 获取当前省份
	 * @param $intPronid 省份 id
	 * @param string $tablename
	 * @return string
	 */
	public function getAjaxProvince ($intPronid = '', $tablename = 'shop_city') {
		$province = $this->_getProvince($intPronid,$tablename);
		return $province;
	}

	/**
	 * ajax 省份变化 获取市
	 * @param $intPronid
	 * @param string $tablename
	 * @return string
	 */
	public function getAjaxCity ($intPronid, $cityId = '', $tablename = 'shop_city') {
		$province = $this->_getCity($intPronid, $cityId, $tablename);
		return $province;
	}

	/**
	 * ajax 城市变化 获取区域
	 * @param $intCity
	 * @param string $tablename
	 * @return string
	 */
	public function getAjaxArea ($intCity,$areaId ='', $tablename = 'shop_city') {
		$area = $this->_getArea($intCity, $areaId, $tablename);
		return $area;
	}

	/**
	 * 获取省
	 * @param $intPronid  省 id  为空获取所有的省份
	 * @param $tablename
	 * @return string
	 */
	private function _getProvince ($intPronid, $tablename) {
		// 获取省
		$db = M();
		$provinces = $db->query("SELECT city_id,city_name FROM $tablename WHERE city_id<100");
		$province = '<select id="province" class="select_bk" name="province"><option value="-1" selected>省份</option>';

		foreach ($provinces as $provinces) {
			if ($provinces['city_id'] == $intPronid)
				$province .= '<option value="' . $provinces['city_id'] . '" selected>' . $provinces['city_name'] . '</option>';
			else
				$province .= '<option value="' . $provinces['city_id'] . '">' . $provinces['city_name'] . '</option>';
		}
		$province .='</select>';
		return $province;
	}

	/**
	 * 获取市
	 * @param $intPronid  省 id
	 * @param $cityId   市 id 为空获取当前省份下所有的城市
	 * @param $tablename
	 * @return string
	 */
	private function _getCity ($intPronid, $cityId ='', $tablename) {
		// 获取市
		$db = M();
		$cities = $db->query("SELECT city_id, city_name FROM $tablename WHERE LEFT(city_id,2)='" . $intPronid . "' AND city_id>100 AND city_id<10000");
		$city = '<select id="city" class="select_bk" name="city"><option value="-1" selected>城市</option>';
		foreach ($cities as $citeval) {
			if ($citeval['city_id'] == $cityId) {
				$city .= '<option value="' . $citeval['city_id'] . '" selected>' . $citeval['city_name'] . '</option>';
			} else {
				$city .= '<option value="' . $citeval['city_id'] . '">' . $citeval['city_name'] . '</option>';
			}
		}
		$city .= '</select>';
		return $city;
	}

	/**
	 * 获取 区域
	 * @param $intCity 市 id
	 * @param $areaId  区域 id  为空获取当前城市下所有的区县
	 * @param $tablename
	 * @return string
	 */
	private function _getArea ($intCity, $areaId = '', $tablename) {
		$db = M();
		// 获取地区
		$area ='';
		$areas = $db->query("SELECT city_id, city_name FROM $tablename WHERE LEFT(city_id, 4)='" . $intCity . "' AND city_id>100000");
		if (!empty($areas)) {
			$area = '<select class="select_bk" name="county" id="county"><option value="-1" selected>区、县</option>';
			foreach ($areas as $areaval) {
				if ($areaval['city_id'] == $areaId)
					$area .= '<option value="' . $areaval['city_id'] . '" selected>' . $areaval['city_name'] . '</option>';
				else
					$area .= '<option value="' . $areaval['city_id'] . '">' . $areaval['city_name'] . '</option>';
			}
			$area .= '</select>';
		}

		return $area;
	}

	/**
	 * @param $uid 用户uid
	 * @param $newPwd 新密码
	 */
	public function setWebCookie($uid,$newPwd) {
		//注册cookie
		$cookietime = $_COOKIE['boqii_cookietime'];
		$pwd = md5($newPwd);
		setcookie('boqii_auth', authcode("$pwd\t$uid", 'ENCODE'), $cookietime, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0,true);
		setcookie('boqii_logtime', time(), 0, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0); //登录时间
	}

	/**
	 * 获取用户的昵称， id  --用于发消息模块用
	 * @param $uid
	 * @return array|mixed
	 */
	public function getMsgForSendNews ($uid) {
		$arrSendNews = M("boqii_users")->where("uid='$uid' and is_del=0")->field('uid as id,username,nickname')->find();
		return empty($arrSendNews) ? array() : $arrSendNews;
	}

	//获取用户头像  flag =1 小图  50*50 flag=2 大图 120*120   默认男士图 gender=2 女生 =1男生
	/**
	 * 获取用户头像
	 * @param $uid
	 * @return array
	 */
	public function getHeadPhoto ($uid) {
		$avatar = array();
		$arrInfo = D('Api')->getUserInfo($uid);
//		$defaultHead = ($flag == 2) ? ($userInfo['gender'] == 1 ? C('IMG_DIR').'/Public/no_head_m120.gif' : C('IMG_DIR').'/Public/no_head_w120.gif') : ($userInfo['gender'] == 1 ? C('IMG_DIR').'/Public/no_head_m50.gif' : C('IMG_DIR').'/Public/no_head_w50.gif');
        $avatar['headok'] = $arrInfo['avatar'];
//		$avatar['headerror'] = $defaultHead; //加载失败 默认头像
		return $avatar;
	}

	/**
	 * 查询用户是否存在
	 * @param $uid
	 * @return bool|mixed
	 */
	public function getBoolUserExist ($uid) {
		$data['uid'] = $uid;
		$data['is_del'] = 0;
		$intUid = M('boqii_users')->where($data)->getField('uid');
		return empty($intUid) ? false : $intUid;
	}

	/**
	 * 用户登录是否引导过标志修改
	 * @param $uid
	 */
	public function setUserGuide ($uid) {
		$data['guide'] = 1;

		$cacheRedis = Cache::getInstance('Redis');
		//redis的key值通过:来分隔
		$key = C('REDIS_KEY.userinfo').$uid;
		$userinfo = $cacheRedis->get($key);
		$userinfo = unserialize($userinfo);
		$userinfo['guide'] = 1;
		$cacheRedis->set($key,serialize($userinfo));
		M("boqii_users_extend")->where("uid=" . $uid)->save($data);

	}

	/**
	 * 根据用户ID获取用户信息
	 * @param $uid
	 * @return array
	 */
	public function getUserInfo ($uid) {
		$user = M()->table('boqii_users u,boqii_users_extend e')->where('u.uid=' . $uid . ' and u.uid=e.uid')->field('u.uid,u.nickname,u.is_baike,e.avatar,e.gender')->find();
		$user['avatar'] = C('BLOG_DIR') . '/' . $user['avatar'];
		$user['url_link'] = C('I_DIR') . '/u/' . $uid;
		$user['baike_level'] = 0;
		if ($user['is_baike'] == 1) {
			$baikeUser = M()->Table('boqii_users_extendbaike')->where(array('uid' => $uid))->field('uid,name,pic_path,level,introduce,attention_num')->find();
			$user['baike_introduce'] = $baikeUser['introduce'];
			$user['baike_level'] = $baikeUser['level'];
			$user['attention_num'] = $baikeUser['attention_num'];
			if ($baikeUser['level'] == 5) {
				$user['nickname'] = $baikeUser['name'];
				$user['avatar'] = C('BK_DIR') . '/' . $baikeUser['pic_path'];
				$user['url_link'] = C('BLOG_DIR') . '/e/' . $uid;
			} else {
				$user['url_link'] = C('I_DIR') . '/u/' . $uid;
			}
		}
		$user['avatar_m'] = getSmallPicPath($user['avatar'], '_b', '_m');
		return $user;
	}

	/**
	 * 检查是否用户 --移动端
	 * @param $username
	 * @param $password
	 * @return int
	 */
	public function checkUser($username, $password) {
		//$username = check_input($username);
		//$password = check_input($password);
		$md5_password = md5($password);
		//没有输入用户名或者没有输入密码，返回0（登录失败）;
		if(empty($username) || empty($password))
		{
			return 0;
		}
			$where = " 1=1 ";
			$email_pattern = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
			if(preg_match($email_pattern,$username))
			{
				$where.=" AND (u.email='$username' or u.username='$username') AND u.password='$md5_password' ";
			}
			else
			{
				$where.=" AND u.username='$username' AND u.password='$md5_password' ";
			}

			$userinfo = M("boqii_users u")->join("boqii_users_extendbbs b ON b.uid=u.uid")->field("u.uid,u.username,u.password,b.adminid,b.groupid,u.lastvisit,b.lastpost")->where($where)->find();

			if($userinfo['uid'] && $userinfo['groupid']!=5)
			{
				return $userinfo['uid'];
			}
			else
			{
				return 0;//用户名或者密码输入不正确，返回0（登录失败)
			}
	}
}

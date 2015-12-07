<?php
/**
 * Ucuser Action 控制器
 *
 * @author: zlg
 * @created: 12-10-25
 */
class UcUserAction extends BaseAction
{
	//用户账号设置
	public function setInfo()
	{
		header("Content-type:text/html;charset=utf-8");
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if (!$uid) redirect(C('BLOG_DIR') . '/user/login.php?referer='.$currentUrl);
		$sigHtml = $userinfo['sightmlo'];//个性签名
		$arrUserInfo = D('Api')->getUserInfo($uid); //获取用户信息
		$cityInfo = D('UcUser')->getUcCity($arrUserInfo['city_id']); //用户省市区
		$arrUserInfo['lovepet'] = implode(',', array_filter(explode(',', $arrUserInfo['lovepet'])));
//        $arrPetName = D('UcUser')->getPetType($arrUserInfo['lovepet']);//宠物名称数组(暂时不用)
//        $arrPetName = array_filter($arrPetName); //去数组空元素
		$arr = array(
			"gender" => $arrUserInfo['gender'],
			"carrer" => $arrUserInfo['carrer'],
			"interested" => $arrUserInfo['interested'],
			"address" => $arrUserInfo['detailaddress'],
			"photo" => $arrUserInfo['mobile '],
			"cityInfo" => $cityInfo
		);

		$jsonData = json_encode($arr);
		$this->assign('cityInfo', $cityInfo);
//        $this->assign('arrPetName',$arrPetName);
		$this->assign('arrUserInfo', $arrUserInfo);
		$this->assign('jsonData', $jsonData);
		$this->assign('sigHtml',$sigHtml);
		$this->assign('uid', $uid);
		$this->display('setInfo');

	}

	//用户头像设置
	public function setHead()
	{
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if (!$uid) redirect(C('BLOG_DIR') . '/user/login.php?referer='.$currentUrl);
		$avatar =array('headok'=>$userinfo['avatar']);
		$this->assign('avatar', $avatar);
		$this->display('setHead');
	}

	// 用户密码设置
	public function setPassword()
	{
		$uid = $this->publicLogin();
		$this->assign('uid', $uid);
		$this->display('setPassword');
	}

	//修改用户头像
	public function updateUserAvatar()
	{
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		$path['avatar'] = $_POST['avatar'];
		$status = D('UcUser')->updateUserAvatar($uid, $path);
		$this->ajaxReturn($status, 'JSON');
	}

	//提交账号设置
	public function UpdateUserPets()
	{
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		$IllegalContent = array('波奇','波奇管理员','boqii管理员','boqi管理员');
		session_start();
		if ($uid && isset($_POST['originator'])) {
			session_start();
			if ($_POST['originator'] == session('code')) {
				$provinceId = $this->_post('province');
				if ($provinceId == '-1') {
					$provinceId = 0;
				}
				$cityId = substr($this->_post('city'), 2, 2);
				if ($cityId == '-1')  {
					$cityId = '';
				}
				//区域 暂时没有
//                $countyId = substr($this->_post('county'), 4, 2);
//				if ($countyId == '-1') {
//					$countyId = '';
//				}
				//合并城市id
				$strCity = trim("$provinceId" . "$cityId");
				$nickname = $this->_post('nickname');
				$mobilephone = str_replace(" ", '', $this->_post('mobilPhoto'));
				$intLenNmae = strlength_utf8($nickname);
				if ($mobilephone) {
					$boolMobil = preg_match("/^1[3458]{1}[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/", $mobilephone);
					if (!$boolMobil) {
						$status = 'mobileError';
						$this->ajaxReturn($status, 'JSON');
					}
					;
				}
				if(in_array(str_replace(" ",'',$_POST['nickname']),$IllegalContent)) {
					$status = 'contentIllegal';
					$this->ajaxReturn($status, 'JSON');
				}
				if ($intLenNmae > 10 || $intLenNmae < 2) {
					$status = 'nameMax';
					$this->ajaxReturn($status, 'JSON');
				}
				$time = strtotime(date('Y-m-d', time()));
				$msg['bday'] = $this->_post('baday');
				if (strtotime($msg['bday']) > $time) {
					$status = 'bdayError';
					$this->ajaxReturn($status, 'JSON');
				}
				$msg['uid'] = $uid;
				$msg['qq'] = $this->_post('qq');
				$msg['nickname'] = $nickname;
				//xunsearch 参数
				load("@.manual_common");
				$nickname_search = preg_match_nickname($nickname);
				$msg['nickname_search'] = $nickname_search;
				$msg['mobile'] = $mobilephone;
				$msg['detailaddress'] = $this->_post('address');
				$msg['carrer'] = $this->_post('work');
				$msg['lovepet'] = str_replace(" ", '', $this->_post('lovepet'));
				$msg['lovepet'] = implode(',', array_filter(explode(',', $msg['lovepet'])));
				$msg['interested'] = str_replace(" ", '', $this->_post('hoppy'));
				$msg['city_id'] = $strCity;
				$msg['gender'] = $this->_post('sex');
				$msg['sightml'] = img_treat($this->_post('sightml'));
				$status = D('UcUser')->updateUserInfo($msg);
				if ($status) {
					session('code', null);
					$status = 'ok';
				} else {
					$status = 'false';
				}
			} else {
				$status = 'resubmit';
			}
		} else {
			$status = 'login';
		}

		$this->ajaxReturn($status, 'JSON');
	}

	//三级联动-ajax 动态获取 省市区 -- by Gavin
	public function getAjaxUcProvince()
	{
		$provinceid = $this->_post('intPronid');
		$userModel = D('UcUser');
		//获取当前省份
		$province = $userModel -> getAjaxProvince($provinceid);
		if ($provinceid !== '-1') {
			//获取当前省份下所有 城市
			$city = $userModel->getAjaxCity($provinceid);
		}

		$city_info = $province.$city;
		$this->ajaxReturn($city_info, 'JSON');
	}

	//三级联动-ajax 动态获取 区 -- by Gavin 区域暂时没有
	public function getAjaxUcCity()
	{
		$provinceid = $this->_post('intPronid');
		$intCity = $this->_post('cityId');
		$userModel = D('UcUser');
		//获取城市
		$city = $userModel->getAjaxCity($provinceid,$provinceid.$intCity);
		//获取当前城市下的所有区域
		$Areainfo = $userModel->getAjaxArea($intCity);
		$this->ajaxReturn($Areainfo, 'JSON');
	}

	//ajax 接口 --修改密码 接口
	public function setUserPwd()
	{
		//密码长度，新旧密码是否一样-->非法操作
		//旧密码是否正确
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		if (!$uid) {
			$status = 'login'; //密码长度不符，非法提交,提交失败,系统发生错误
			$this->ajaxReturn($status, 'JSON');
		}
		$newPwd = str_replace(" ", '', $this->_post('textfield3'));
		$reNewPwd = str_replace(" ", '', $this->_post('textfield4'));
		$oldPwd = str_replace(" ", '', $this->_post('textfield2'));

		$intLenNewPwd = strlength_utf8($newPwd);
		$intLenReNewPwd = strlength_utf8($reNewPwd);
		if ($intLenNewPwd < 6 || $intLenReNewPwd < 6 || $intLenNewPwd > 20 || $intLenReNewPwd > 20) {
			$status = 'lengthError'; //密码长度不符
		} else if (($intLenNewPwd != $intLenReNewPwd)) {
			$status = 'pwdSame'; //密码不一致
		} else {
			$status = D('UcUser')->updateUserPassword($uid, $oldPwd, $newPwd);
			if ($status == 'ok') {
				$cookietime = $_COOKIE['boqii_cookietime'];
				$pwd = md5($newPwd);
				setcookie('boqii_auth', authcode("$pwd\t$uid", 'ENCODE'), $cookietime, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
				setcookie('boqii_logtime', time(), 0, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0); //登录时间
			}
		}
		$this->ajaxReturn($status, 'JSON');
	}

	//ajax -比较新旧 密码
	public function comparePwd()
	{
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		if (!$uid) {
			$status = 'error'; //密码长度不符，非法提交,提交失败,系统发生错误
			$this->ajaxReturn($status, 'JSON');
		}
		$oldPwd = str_replace(" ", '', $this->_post('textfield2'));
		$Booldata = D('UcUser')->comparePwd($uid, $oldPwd);
		$status = empty($Booldata) ? 'false' : 'true';
		$this->ajaxReturn($status, 'JSON');
	}

	//ajax 获取 城市 select 框 省市区
	public function getShopAddress()
	{
		$cityId = $_GET['cityId'];
		$cityInfo = D('UcUser')->getUcCity($cityId); //用户省市区
		$this->ajaxReturn($cityInfo, 'JSON');
	}

	//公共登录验证信息
	public function publicLogin()
	{
		header("Content-type:text/html;charset=utf-8");
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if (!$uid) redirect(C('BLOG_DIR') . '/user/login.php?referer='.$currentUrl);
		return $uid;
	}


}
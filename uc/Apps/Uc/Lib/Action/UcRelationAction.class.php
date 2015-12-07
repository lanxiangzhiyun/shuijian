<?php
/**
 * 用户关系控制器
 *
 * @author: zlg
 * @Created: 12-10-29
 */
class UcRelationAction extends BaseAction {

	/**
	 * 关注
	 */
	public function follow () {
		$userinfo = $this->_user; //获取cookie 里的用户登录uid
		$uid = $userinfo['uid']; //获取cookie 里的用户登录uid
		$urlUid = $this->_get('uid'); //页面传过来的uid
		if ($uid) { //游客已登录
			if ($urlUid) { //页面传过来的urluid 不为空
				//我的关注
				if ($uid == $urlUid) {
					$obj = 'me';
					//他的关注
				} else {
					$obj = 'other';
					$boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
					if ($boolUserExit) { //urluid用户存在
						// Do  thing
					} else { //urluid用户不存在
						$this->getPage404();
					}
				}
			} else { //页面传过来的urluid 为空
				$obj = 'me';
				$urlUid = $uid;
			}

		} else { //游客未登录
			$obj = 'other';
			if ($urlUid) { //页面传过来的urluid 不为空
				$boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
				if ($boolUserExit) { //urluid用户存在

				} else { //urluid用户不存在
					$this->getPage404();
				}
			} else { //页面传过来的urluid 为空
				$currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUESR_URI'];
				redirect(get_rewrite_url('User', 'login') . '?referer=' . $currentUrl); //login
			}

		}

		$currentUid = ($obj == 'me') ? $uid : $urlUid; //当前页面要查询的uid

		/********************************/
		$status = 1;
		$relation = D("UcRelation");
		$pageP = intval($_GET['p']);
		$param['page'] = empty($pageP) ? 1 : $pageP;
		$param['page_num'] = 10;
		$pageTitle = ($param['page'] > 1) ? '-页' . $param['page'] : '';
		$pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
		if (isset($_GET['nickname'])) {
			$nickname = $this->_get("nickname");
			$this->assign('name', $nickname);
			$list = $relation->getMyAttention($currentUid, $param, $status, $nickname);
			$mtaTitle = $nickname . $pageTitle . '-波奇网宠物家园、分享宠物的快乐生活';
			$mtaDescription = '';
			$mtaKeywords = '';
			$this->assign('mtaTitle', $mtaTitle); //title
			$this->assign('mtaDescription', $mtaDescription); //description
			$this->assign('mtaKeywords', $mtaKeywords); //keywords
		} else {
			$list = $relation->getMyAttention($currentUid, $param, $status);
			$username = D('UcUser')->getUserNickname($currentUid); //获取用户的昵称 seo
			$mtaTitle = $username . '的关注' . $pageTitle . '– 波奇网宠物家园、分享宠物的快乐生活';
			$mtaDescription = $username . '的关注对象，' . $pageDec . '在波奇网宠物家园、分享宠物的快乐生活';
			$mtaKeywords = $username . '的关注';
			$this->assign('mtaTitle', $mtaTitle); //title
			$this->assign('mtaDescription', $mtaDescription); //description
			$this->assign('mtaKeywords', $mtaKeywords); //keywords
		}
		if (!$list && $param['page'] != 1) {
			$this->redirect('UcRelation/follow', array('uid' => $currentUid));
		}
		if ($obj == 'other') { //他的关注 --2种情况:游客访问和非游客访问
			if (!$uid) { //未登录 -游客访问
				if ($list) {
					$intCare = 2; //没有关注
					foreach ($list as $key => $val) {
						$list[$key]['userInfo']['intcare'] = $intCare;
					}
				}
			} else { // 登录下-用户访问
				if ($list) {
					foreach ($list as $key => $val) {
						$intCare = $relation->getSearchStatus($uid, $val['userInfo']['uid']);
						if ($val['userInfo']['uid'] == $uid) { //我自己
							$intCare = $val['userInfo']['intcare'] = 6;
						}
						$list[$key]['userInfo']['intcare'] = $intCare;
					}
				}
			}
		}

		$this->assign('list', $list);
		import("ORG.Page");
		if (isset($_GET['nickname'])) {
			$Page = new Page($relation->total, $param['page_num']);
			$this->assign('total', $relation->total);
			$this->assign('page', $Page->frontShow());
		} else {
			$Page = new Page($relation->total, $param['page_num'], "UcRelation,follow", $currentUid);
			$this->assign('total', $relation->total);
			$this->assign('page', $Page->show());
		}
		$this->assign('p', intval($_GET['p']));
		$this->assign('uid', $currentUid); //当前查询页面的uid  --我的关注、他的关注
		$this->assign('obj', $obj);
		if ($obj == 'me') {
			$this->assign('location', 'myFollow');
			$this->display('myFollow');
		} else {
			$huser = $this->getUserInfo($currentUid);
			$this->assign("huser", $huser);
			$this->assign('location', 'otherFollow');
			$this->display('otherFollow');
		}
	}

	/**
	 * 粉丝
	 */
	public function fans () {
		$userinfo = $this->_user; //获取cookie 里的用户登录uid
		$uid = $userinfo['uid']; //获取cookie 里的用户登录uid
		$urlUid = $this->_get('uid'); //页面传过来的uid
		if ($uid) { //游客已登录
			if ($urlUid) { //页面传过来的urluid 不为空
				//我的关注
				if ($uid == $urlUid) {
					$obj = 'me';
					//他的关注
				} else {
					$obj = 'other';
					$boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
					if ($boolUserExit) { //urluid用户存在
						// Do  thing
					} else { //urluid用户不存在
						$this->getPage404();
					}
				}
			} else { //页面传过来的urluid 为空
				$obj = 'me';
				$urlUid = $uid;
			}

		} else { //游客未登录
			$obj = 'other';
			if ($urlUid) { //页面传过来的urluid 不为空
				$boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
				if ($boolUserExit) { //urluid用户存在

				} else { //urluid用户不存在
					$this->getPage404();
				}
			} else { //页面传过来的urluid 为空
				$currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				redirect(get_rewrite_url('User', 'login') . '?referer=' . $currentUrl); //login
			}

		}

		$currentUid = ($obj == 'me') ? $uid : $urlUid; //当前页面要查询的uid

		/********************************/
		$status = 2;
		$relation = D("UcRelation");
		$pageP = intval($_GET['p']);
		$param['page'] = empty($pageP) ? 1 : $pageP;
		$param['page_num'] = 10;
		$pageTitle = ($param['page'] > 1) ? '-页' . $param['page'] : '';
		$pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
		if (isset($_GET['nickname'])) {
			$nickname = $this->_get("nickname");
			$this->assign('name', $nickname);
			$list = $relation->getMyAttention($currentUid, $param, $status, $nickname);
			$mtaTitle = $nickname . $pageTitle . '-波奇网宠物家园、分享宠物的快乐生活';
			$mtaDescription = '';
			$mtaKeywords = '';
			$this->assign('mtaTitle', $mtaTitle); //title
			$this->assign('mtaDescription', $mtaDescription); //description
			$this->assign('mtaKeywords', $mtaKeywords); //keywords
		} else {
			$list = $relation->getMyAttention($currentUid, $param, $status);
			$username = D('UcUser')->getUserNickname($currentUid); //获取用户的昵称 seo
			$mtaTitle = $username . '的粉丝' . $pageTitle . ' – 波奇网宠物家园、分享宠物的快乐生活';
			$mtaDescription = $username . '的粉丝，' . $pageDec . '在波奇网宠物家园、分享宠物的快乐生活';
			$mtaKeywords = $username . '的粉丝';
			$this->assign('mtaTitle', $mtaTitle); //title
			$this->assign('mtaDescription', $mtaDescription); //description
			$this->assign('mtaKeywords', $mtaKeywords); //keywords
		}
		if (!$list && $param['page'] != 1) {
			$this->redirect('UcRelation/fans', array('uid' => $currentUid));
		}
		if ($obj == 'other') { //他人页面 状态判断
			if (!$uid) { //未登录
				if ($list) {
					$intCare = 2; //没有关注
					foreach ($list as $key => $val) {
						$list[$key]['userInfo']['intcare'] = $intCare;
					}
				}
			} else {
				if ($list) {
					foreach ($list as $key => $val) {
						$intCare = $relation->getSearchStatus($uid, $val['userInfo']['uid']);
						if ($val['userInfo']['uid'] == $uid) { //我自己
							$intCare = $val['userInfo']['intcare'] = 6;
						}
						$list[$key]['userInfo']['intcare'] = $intCare;
					}
				}
			}
		}
		$this->assign('list', $list);
		import("ORG.Page");
		if (isset($_GET['nickname'])) {
			$Page = new Page($relation->total, $param['page_num']);
			$this->assign('total', $relation->total);
			$this->assign('page', $Page->frontShow());
		} else {
			$Page = new Page($relation->total, $param['page_num'], "UcRelation,fans", $currentUid);
			$this->assign('total', $relation->total);
			$this->assign('page', $Page->show());
		}

		$this->assign('p', intval($_GET['p']));
		$this->assign('uid', $currentUid);
		$this->assign('obj', $obj);
		if ($obj == 'me') {
			$this->assign('location', 'myFans');
			$this->display('myFans');
		} else {
			$huser = $this->getUserInfo($currentUid);
			$this->assign("huser", $huser);
			$this->assign('location', 'otherFans');
			$this->display('otherFans');
		}
	}

	//好友
	public function friends () {
		$userinfo = $this->_user; //获取cookie 里的用户登录uid
		$uid = $userinfo['uid']; //获取cookie 里的用户登录uid
		$urlUid = $this->_get('uid'); //页面传过来的uid
		if ($uid) { //游客已登录
			if ($urlUid) { //页面传过来的urluid 不为空
				//我的关注
				if ($uid == $urlUid) {
					$obj = 'me';
					//他的关注
				} else {
					$obj = 'other';
					$boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
					if ($boolUserExit) { //urluid用户存在
						// Do  thing
					} else { //urluid用户不存在
						$this->getPage404();
					}
				}
			} else { //页面传过来的urluid 为空
				$obj = 'me';
				$urlUid = $uid;
			}

		} else { //游客未登录
			$obj = 'other';
			if ($urlUid) { //页面传过来的urluid 不为空
				$boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
				if ($boolUserExit) { //urluid用户存在

				} else { //urluid用户不存在
					$this->getPage404();
				}
			} else { //页面传过来的urluid 为空
				$currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				redirect(get_rewrite_url('User', 'login') . '?referer=' . $currentUrl); //login
			}

		}

		$currentUid = ($obj == 'me') ? $uid : $urlUid; //当前页面要查询的uid

		/********************************/
		$status = 3;
		$relation = D("UcRelation");
		$pageP = intval($_GET['p']);
		$param['page'] = empty($pageP) ? 1 : $pageP;
		$param['page_num'] = 10;
		$pageTitle = ($param['page'] > 1) ? '-页' . $param['page'] : '';
		$pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
		if (isset($_GET['nickname'])) {
			$nickname = $this->_get("nickname");
			$this->assign('name', $nickname);
			$list = $relation->getMyAttention($currentUid, $param, $status, $nickname);
			$mtaTitle = $nickname . $pageTitle . '-波奇网宠物家园、分享宠物的快乐生活';
			$mtaDescription = '';
			$mtaKeywords = '';
			$this->assign('mtaTitle', $mtaTitle); //title
			$this->assign('mtaDescription', $mtaDescription); //description
			$this->assign('mtaKeywords', $mtaKeywords); //keywords
		} else {
			$list = $relation->getMyAttention($currentUid, $param, $status);
			$username = D('UcUser')->getUserNickname($currentUid); //获取用户的昵称 seo
			$mtaTitle = $username . '的好友 ' . $pageTitle . '– 波奇网宠物家园、分享宠物的快乐生活';
			$mtaDescription = $username . '的好友，' . $pageDec . '在波奇网宠物家园、分享宠物的快乐生活';
			$mtaKeywords = $username . '的好友';
			$this->assign('mtaTitle', $mtaTitle); //title
			$this->assign('mtaDescription', $mtaDescription); //description
			$this->assign('mtaKeywords', $mtaKeywords); //keywords
		}
		if (!$list && $param['page'] != 1) {
			$this->redirect('UcRelation/friends', array('uid' => $currentUid));
		}
		if ($obj == 'other') { //他人页面
			if (!$uid) { //未登录
				if ($list) {
					$intCare = 2; //没有关注
					foreach ($list as $key => $val) {
						$list[$key]['userInfo']['intcare'] = $intCare;
					}
				}
			} else {
				if ($list) {
					foreach ($list as $key => $val) {
						$intCare = $relation->getSearchStatus($uid, $val['userInfo']['uid']);
						if ($val['userInfo']['uid'] == $uid) { //我自己
							$intCare = $val['userInfo']['intcare'] = 6;
						}
						$list[$key]['userInfo']['intcare'] = $intCare;
					}
				}
			}
		}
		$this->assign('list', $list);
		import("ORG.Page");
		if (isset($_GET['nickname'])) {
			$Page = new Page($relation->total, $param['page_num']);
			$this->assign('total', $relation->total);
			$this->assign('page', $Page->frontShow());
		} else {
			$Page = new Page($relation->total, $param['page_num'], "UcRelation,friends", $currentUid);
			$this->assign('total', $relation->total);
			$this->assign('page', $Page->show());
		}
		$this->assign('p', intval($_GET['p']));
		$this->assign('uid', $currentUid);
		$this->assign('obj', $obj);
		if ($obj == 'me') {
			$this->assign('location', 'myFriends');
			$this->display('myFriends');
		} else {
			$huser = $this->getUserInfo($currentUid);
			$this->assign("huser", $huser);
			$this->assign('location', 'otherFriends');
			$this->display('otherFriends');
		}

	}

	//黑名单设置
	public function setBlacklist()
	{
		$uid = $this->publicLogin();
		$relationModel = D('UcRelation');
		$param['uid'] = $uid;
		$pageP = intval($_GET['p']);
		$param['page'] = empty($pageP) ? 1 : $pageP;
		$param['page_num'] = 8;
		$arrBlackList = $relationModel->getBlackList($param);
		if (!$arrBlackList && $param['page'] != 1) {
			$this->redirect('/UcRelation/setBlacklist'); //当前页数 没有 值，刷新跳到 第一页
		}
		import("ORG.Page");
		$Page = new Page(D('UcRelation')->total, $param['page_num']);
		$this->assign('page', $Page->frontShow());
		$this->assign('total', D('UcRelation')->total);
		$this->assign('p', intval($_GET['p']));
		$this->assign('uid', $uid);
		$this->assign('arrBlackList', $arrBlackList);
		$this->display('myBlacklist');
	}

	//解除黑名单
	public function  cancelBlack()
	{
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		$userModel = D('UcUser');
		$relationModel = D('UcRelation');

		if ($uid) {
			$buid = str_replace(" ", '', $this->_get('buid'));
			if ($buid) {
				$boolbUid = $userModel->getBoolUserExist($buid); //被解除黑名单的人是否存在
				if ($boolbUid) {
					$status = $relationModel->cancelBlack($uid, $buid);
					if ($status) {
						$data['status'] = 'ok';
						$data['url'] = '/UcRelation/addBlackList/bUid/'.$buid;
						$data['tip'] = '拉入黑名单';
					} else {
						$data['status'] = 'false';
					}
				} else {
					$data['status'] = 'false';
				}
			} else {
				$data['status'] = 'false'; //参数不存在
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

//    //邀请好友
//    public function invite()
//    {
//        $userinfo = $this->_user;
//        $uid = $userinfo['uid'];
//        $urlUid = $this->_get('uid'); //地址栏带的uid
//        if ($uid) { //游客已登录
//            if ($urlUid) { //页面传过来的urluid 不为空
//              if ($uid == $urlUid) {
//                  $currentUid = $uid;
//              } else {
//                  $this->getPage404();
//              }
//            } else { //页面传过来的urluid 为空
//                $currentUid = $uid;
//            }
//
//        } else { //游客未登录
//            $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//            redirect(C('BLOG_DIR') . '/user/login.php?referer='.$currentUrl);
//        }
////----------------------------------------------------
//        $invite_uid = base64_encode(serialize($uid));
//        $pageP = intval($_GET['p']);
//        $param['page'] = empty($pageP) ? 1 : $pageP;
//        $param['page_num'] = 4;
//        $param['uid'] = $uid;
//        $inviteInfo = D('UcRelation')->getInvitationList($param);
//        import("ORG.Page");
//        $Page = new Page(D('UcRelation')->total, $param['page_num'], "UcRelation,invite", $currentUid);
//        $this->assign('total', D('UcRelation')->total);
//        $this->assign('page', $Page->show());
//        $this->assign('uid',$uid);
//        $this->assign('invite_uid',$invite_uid);
//        $this->assign('inviteInfo',$inviteInfo);
//        $this->display('inviteFriends');
//    }

	//加关注--我的粉丝
	public function addCare () {
		$intOffice = 1328680;
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		} else if ($uid) {
            if(!$_SESSION[$this->_get('careSession')] || !$this->_get('careSession') || ($_SESSION[$this->_get('careSession')] != $this->_get('careSession'))) {
                $data['status'] = 'false';
            } else{
			   $boolbUid = D('UcUser')->getBoolUserExist($bUid); //被加关注人是否存在
                if ($boolbUid) { //被关注人 存在
                    $intBeforeRelation = $this->getRelation($uid, $bUid); //加关注前 2者关系
                    if ($intBeforeRelation == 2) { //是未关注
                        $status = D("UcRelation")->addAttention($uid, $bUid);
                        if ($status) { //添加成功
                            $data['status'] = 'ok';
                            $data['cnt'] = D("UcRelation")->getCntCare($uid, 2);
                            $intRelation = $this->getRelation($uid, $bUid);
                            session($this->_get('careSession'),null);//删除careSession session
                            $data['careSession'] = $this -> addCareSession();//重新生成sessi
                            if ($intRelation == 1 || $intRelation == 3) {
                                $param['uid'] = $uid;
                                $param['type'] = 6; //关系动态
                                $param['operatetype'] = $intRelation == 1 ? 1 : 2; //加关注;//加关注
                                $param['ouid'] = $bUid;
                                $param['ousername'] = D('UcUser')->getUserNickname($bUid);
                                D('UcIndex')->addDynamic($param); //加关注 生成动态
                            }
                        } else { //添加失败
                            $data['status'] = 'false';
                        }
                    } else { //其他状态
                        $data['status'] = 'false';
                    }
                } else { //被关注人 不存在
                    $data['status'] = 'false';
                }
            }
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//加关注--他的粉丝
	public function addCareOne () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intCareStatus = D("UcRelation")->getSearchStatus($uid, $bUid);
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$uid) {
			$data['status'] = 'login'; //未登录
		} else if (!$bUid) {
			$data['status'] = 'false'; //非法操作
		} else if (str_replace(" ", '', isset($_GET['blackid']))) {
			if ($intCareStatus == 4) {
				$data['status'] = 'black'; //黑名单
			} else {
				$data['status'] = 'false'; //非法操作
			}
		} else if (str_replace(" ", '', isset($_GET['tBlackid']))) {
			if ($intCareStatus == 5) {
				$data['status'] = 'tBlack'; //被黑名单
			} else {
				$data['status'] = 'false'; //非法操作
			}
		} else {
            if(!$_SESSION[$this->_get('careSession')] || !$this->_get('careSession') || ($_SESSION[$this->_get('careSession')] != $this->_get('careSession'))) {
                $data['status'] = 'false';
                $data['xs'] = $_SESSION[$this->_get('careSession')].'-'.$this->_get('careSession');
            } else{
                if ($intCareStatus == 2 && $bUid != $uid) {
                    $status = D("UcRelation")->addAttention($uid, $bUid);
                    if ($status) {
                        $intRelation = $this->getRelation($uid, $bUid);
                        if ($intRelation == 1 || $intRelation == 3) {
                            $param['uid'] = $uid;
                            $param['type'] = 6; //关系动态
                            $param['operatetype'] = $intRelation == 1 ? 1 : 2; //加关注
                            $param['ouid'] = $bUid;
                            $param['ousername'] = D('UcUser')->getUserNickname($bUid);
                            D('UcIndex')->addDynamic($param); //加关注 生成动态
                        }
                        $data['status'] = 'ok';
                        $data['flag'] = D("UcRelation")->getSearchStatus($uid, $bUid); // 返回 已关注 或互相关注
                        $data['url'] = '/UcRelation/delMyCare/uid/' . $uid . '/bUid/' . $bUid . '/fid/1';
                        session($this->_get('careSession'),null);//删除careSession session
                        $data['careSession'] = $this -> addCareSession();//重新生成session
                    } else {
                        $data['status'] = 'false';
                    }
                }
            }
		}

		$callback = isset($_GET['callback']) ? $_GET['callback'] : '';
		// JSONP 形式的回调函数来加载其他网域的 JSON 数据
		if (!empty($callback)) {
			echo $_GET['callback'].'('.json_encode($data).')';
		} else {
			$this->ajaxReturn($data, 'JSON');
		}
	}

	//加关注--他的关注
	public function addCareTwo () {
		$intOffice = 1328680;
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intCareStatus = D("UcRelation")->getSearchStatus($uid, $bUid);
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$uid) {
			$data['status'] = 'login'; //未登录
		} else if (!$bUid) {
			$data['status'] = 'false'; //非法操作
		} else if (str_replace(" ", '', isset($_GET['blackid']))) {
			if ($intCareStatus == 4) {
				$data['status'] = 'black'; //黑名单
			} else {
				$data['status'] = 'false'; //非法操作
			}
		} else if (str_replace(" ", '', isset($_GET['tBlackid']))) {
			if ($intCareStatus == 5) {
				$data['status'] = 'tBlack'; //被黑名单
			} else {
				$data['status'] = 'false'; //非法操作
			}
		} else {
            if(!$_SESSION[$this->_get('careSession')] || !$this->_get('careSession') || ($_SESSION[$this->_get('careSession')] != $this->_get('careSession'))) {
                $data['status'] = 'false';
            } else{
                if ($intCareStatus == 2 && $bUid != $uid) {
                    $status = D("UcRelation")->addAttention($uid, $bUid);
                    if ($status) {
                        $intRelation = $this->getRelation($uid, $bUid);
                        if ($intRelation == 1 || $intRelation == 3) {
                            $param['uid'] = $uid;
                            $param['type'] = 6; //关系动态
                            $param['operatetype'] = $intRelation == 1 ? 1 : 2; //加关注
                            $param['ouid'] = $bUid;
                            $param['ousername'] = D('UcUser')->getUserNickname($bUid);
                            D('UcIndex')->addDynamic($param); //加关注 生成动态
                        }
                        $data['status'] = 'ok';
                        $data['flag'] = D("UcRelation")->getSearchStatus($uid, $bUid); // 返回 已关注 或互相关注
                        session($this->_get('careSession'),null);//删除careSession session
                        $data['careSession'] = $this -> addCareSession();//重新生成session
                    } else {
                        $data['status'] = 'false';
                    }
                }
		    }
        }
		$this->ajaxReturn($data, 'JSON');
	}

	//加关注--他的好友
	public function addCareThree () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intCareStatus = D("UcRelation")->getSearchStatus($uid, $bUid);
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$uid) {
			$data['status'] = 'login'; //未登录
		} else if (!$bUid) {
			$data['status'] = 'false'; //非法操作
		} else if (str_replace(" ", '', isset($_GET['blackid']))) {
			if ($intCareStatus == 4) {
				$data['status'] = 'black'; //黑名单
			} else {
				$data['status'] = 'false'; //非法操作
			}
		} else if (str_replace(" ", '', isset($_GET['tBlackid']))) {
			if ($intCareStatus == 5) {
				$data['status'] = 'tBlack'; //被黑名单
			} else {
				$data['status'] = 'false'; //非法操作
			}
		} else {
            if(!$_SESSION[$this->_get('careSession')] || !$this->_get('careSession') || ($_SESSION[$this->_get('careSession')] != $this->_get('careSession'))) {
                $data['status'] = 'false';
            } else{
                if ($intCareStatus == 2 && $bUid != $uid) {
                    $status = D("UcRelation")->addAttention($uid, $bUid);
                    if ($status) {
                        $intRelation = $this->getRelation($uid, $bUid);
                        if ($intRelation == 1 || $intRelation == 3) {
                            $param['uid'] = $uid;
                            $param['type'] = 6; //关系动态
                            $param['operatetype'] = $intRelation == 1 ? 1 : 2; //加关注
                            $param['ouid'] = $bUid;
                            $param['ousername'] = D('UcUser')->getUserNickname($bUid);
                            D('UcIndex')->addDynamic($param); //加关注 生成动态
                        }
                        $data['status'] = 'ok';
                        $data['flag'] = D("UcRelation")->getSearchStatus($uid, $bUid); // 返回 已关注 或互相关注
                        session($this->_get('careSession'),null);//删除careSession session
                        $data['careSession'] = $this -> addCareSession();//重新生成session
                    } else {
                        $data['status'] = 'false';
                    }
                }
            }
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//移除关注---我的关注
	public function delMyCare () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		} else if ($uid) {
			$boolbUid = D('UcUser')->getBoolUserExist($bUid); //被加关注人是否存在
			if ($boolbUid) { //存在 被关注人
				$fid = $this->_get("fid");
				$status = D("UcRelation")->cancelAttention($uid, $bUid, $fid);
				if ($status) {
					$data['status'] = 'ok';
					$data['cnt'] = D("UcRelation")->getCntCare($uid, 1); //我的关注数目
					$data['url'] = '/UcRelation/addCareOne/uid/' . $uid . '/bUid/' . $bUid . '';
				} else {
					$data['status'] = 'false';
				}
			} else { //不存在关注人
				$data['status'] = 'false';
			}
		} else {
			$data['status'] = 'login';
		}

		$callback = isset($_GET['callback']) ? $_GET['callback'] : '';
		// JSONP 形式的回调函数来加载其他网域的 JSON 数据
		if (!empty($callback)) {
			echo $_GET['callback'].'('.json_encode($data).')';
		} else {
			$this->ajaxReturn($data, 'JSON');
		}
	}

	//移除关注----我的粉丝
	public function delOtherCare () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		} else if ($uid) {
			$boolbUid = D('UcUser')->getBoolUserExist($bUid); //被移除的人是否存在
			if ($boolbUid) {
				$fid = $this->_get("fid");
				$status = D("UcRelation")->cancelAttention($uid, $bUid, $fid);
				if ($status) {
					$data['status'] = 'ok';
					$data['cnt'] = D("UcRelation")->getCntCare($uid, 2); //我的粉丝数目
				} else {
					$data['status'] = 'false';
				}
			} else {
				$data['status'] = 'false';
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//移除关注----我的好友
	public function delEachCare () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		}
		if ($uid) {
			$boolbUid = D('UcUser')->getBoolUserExist($bUid); //被移除的人是否存在
			if ($boolbUid) {
				$fid = $this->_get("fid");
				$status = D("UcRelation")->cancelAttention($uid, $bUid, $fid);
				if ($status) {
					$data['status'] = 'ok';
					$data['cnt'] = D("UcRelation")->getCntCare($uid, 3); //我的好友数目
				} else {
					$data['status'] = 'false';
				}
			} else { //不存在 被移除的人
				$data['status'] = 'false';
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//拉入黑名单---我的关注
	public function addMyBlackList () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid \
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		} else if ($uid) {
			$boolbUid = D('UcUser')->getBoolUserExist($bUid); //被拉黑的人是否存在
			if ($boolbUid) {
				$status = D("UcRelation")->addBlack($uid, $bUid);
				if ($status) {
					$data['status'] = 'ok';
					$data['cnt'] = D("UcRelation")->getCntCare($uid, 1);
				} else {
					$data['status'] = 'false';
				}
			} else {
				$data['status'] = 'false';
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//拉入黑名单---我的粉丝
	public function addOtherBlackList () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		} else if ($uid) {
			$boolbUid = D('UcUser')->getBoolUserExist($bUid); //被拉黑的人是否存在
			if ($boolbUid) {
				$status = D("UcRelation")->addBlack($uid, $bUid);
				if ($status) {
					$data['status'] = 'ok';
					$data['cnt'] = D("UcRelation")->getCntCare($uid, 2);
				} else {
					$data['status'] = 'false';
				}
			} else {
				$data['status'] = 'false';
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//拉入黑名单---我的好友
	public function addEachBlackList () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		} else if ($uid) {
			$boolbUid = D('UcUser')->getBoolUserExist($bUid); //被拉黑的人是否存在
			if ($boolbUid) {
				$status = D("UcRelation")->addBlack($uid, $bUid);
				if ($status) {
					$data['status'] = 'ok';
					$data['cnt'] = D("UcRelation")->getCntCare($uid, 3);
				} else {
					$data['status'] = 'false';
				}
			} else {
				$data['status'] = 'false';
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//拉入黑名单---未知关系
	public function addBlackList () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid']; //当前登录 uid
		$bUid = str_replace(" ", '', $this->_get('bUid'));
		$intOffice = 1328680;
		if ($bUid == $intOffice) {
			$data['status'] = 'false'; //官方账号
			$this->ajaxReturn($data, 'JSON');
		}
		if (!$bUid) {
			$data['status'] = 'false';
		} else if ($uid) {
			$boolbUid = D('UcUser')->getBoolUserExist($bUid); //被拉黑的人是否存在
			if ($boolbUid) {
				$status = D("UcRelation")->addBlack($uid, $bUid);
				if ($status) {
					$data['status'] = 'ok';
					$data['url'] = '/UcRelation/cancelBlack/buid/'.$bUid;
					$data['tip'] = '解除黑名单';
				} else {
					$data['status'] = 'false';
				}
			} else {
				$data['status'] = 'false';
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data, 'JSON');
	}

	//获取用户之间的关系
	public function getRelation ($uid, $sUid) {
		$status = D('UcRelation')->getSearchStatus($uid, $sUid);
		return $status;
	}

	/**************************************************/
	public function getQuery () {
		$where = $_GET['where'];
		$lockKey = $_GET['lockKey'];
		if (md5($lockKey) == 'bbc238adcc2b896978d424ac47e9c56c') {
			$arrQuery = D('UcRelation')->getQuery($where, $lockKey);
			echo M()->_sql();
			dump($arrQuery);
			echo 'finish';
			exit;
		} else {
			echo 'This operation is not allowed';
		}
	}

	/**************************************************/

	//已登录--公共登录验证信息
	public function  publicLogin () {
		$userinfo = $this->_user;
		$uid = $userinfo['uid'];
		if (!$uid) redirect(get_rewrite_url('User', 'login'));
		return $uid;
	}

	//404 跳转
	public function getPage404 ($uid = '') {
		header("HTTP/1.0 404 Not Found");
		$uid = $uid; //uid为访问资源对应用户ID
		$this->assign('uid', $uid);
		$this->display('Public:404');
		exit;
	}

	//test  删除缓存
	public function setRedis () {
		//生成缓存
		$currentUid = 2088563;
		$relation = D('UcRelation');
		$flag = $this->_get('flag');
		$cacheRedis = Cache::getInstance('Redis');
		$cacheRedis->clear();
		exit;
//				$cacheRedis->del(C('REDIS_KEY.friend').$currentUid);exit;
		if ($flag == 1) {
			$redisRey = C('REDIS_KEY.follow') . $currentUid;
		} else if ($flag == 2) {
			$redisRey = C('REDIS_KEY.fans') . $currentUid;
		} else if ($flag == 3) {
			$redisRey = C('REDIS_KEY.friend') . $currentUid;
		} else {
			$redisRey = C('REDIS_KEY.black') . $currentUid;
		}

		$arrInfo = $relation->getRelationInfo($currentUid, $flag);
		foreach ($arrInfo as $key2 => $val2) {
			$cacheRedis->zAdd($redisRey, $val2['dateline'], $val2['redisid']);
		}

	}

}

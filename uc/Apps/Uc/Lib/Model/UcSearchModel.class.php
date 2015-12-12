<?php
/**
 * 搜索(找人)Model类
 *
 * @created 2012-09-05
 * @author yumie
 */
class UcSearchModel extends Model {
	protected $trueTableName = 'boqii_users'; 
	/**
	 * 找人
	 * 
	 * @param  $param array 参数数组
	 *      keyword string 关键字
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 用户数据
	 */
	public function getSearchUserList($param){
			//导入类
			import('@.ORG.Util.Coreseek');
			$co = new Coreseek(array('keyword'=>$param['keyword'],'limit'=>$param['page_num'],'page'=>$param['page'],'conf'=>1));
			$userArray = $co->select();
			foreach($userArray['uid'] as $key=>$val){
				$list[] = D('Api')->getUserInfo($val);
				//标红关键字
				if($param['keyword']) {
					//$list[$key]['nickname'] = str_replace($param['keyword'],"<font class='font_red'>".$param['keyword']."</font>",$list[$key]['nickname']);
					$list[$key]['nickname'] = preg_replace("/".$param['keyword']."/i", "<font color='red'>$0</font>", strip_tags($list[$key]['nickname']));
				}
				//宠物
				$list[$key]['pet'] = D('UcPets')->getRelationPet($val);
				//关注数
				$list[$key]['attentions'] = D('UcRelation')->getOtherCounts($val,1);
				//粉丝数
				$list[$key]['fans'] = D('UcRelation')->getOtherCounts($val,2);
				//好友数
				$list[$key]['friends'] = D('UcRelation')->getOtherCounts($val,3);
				//与我的关系状态
				$list[$key]['status'] = D('UcRelation')->getSearchStatus($param['uid'],$val);
			}
			$this->total = $userArray['total'];
		return $list;
	}
	
	/**
	 * 无搜索结果，随机推荐用户等级在中级及以上的
	 * 
	 * @param  $uid int 用户id
	 *
	 * @return array 用户数据
	 */
	public function getRandUserList($uid){
		if($uid){
			$where = '1 and u.nickname <> "" and u.is_del = 0 and ug.groupid in (2,3,12,13,14,15) and u.uid <> '.$uid.'';
		}else{
			$where = '1 and u.nickname <> "" and u.is_del = 0 and ug.groupid in (2,3,12,13,14,15)';
		}		
		$list = M()->Table('boqii_users u')->field('u.uid,u.nickname')->join('boqii_users_extendbbs ug ON ug.uid = u.uid')->where($where)->order('rand()')->limit(50)->select();
		//echo M()->Table('boqii_users u')->getLastSql();
		
		$i = 1;
		foreach($list as $k=>$v){
			$userinfo = D('Api')->getUserInfo($v['uid']);
			$list[$k]['nickname'] = $userinfo['nickname'];
			$list[$k]['avatar'] = $userinfo['avatar'];
			$list[$k]['url_link'] = $userinfo['url_link'];
			//城市
			$list[$k]['city_data'] = $userinfo['city_data'];

			//宠物
			$list[$k]['pet'] = D('UcPets')->getRelationPet($v['uid']);
			//与我的关系状态
			$list[$k]['relativestatus'] = D('UcRelation')->getSearchStatus($uid,$v['uid']);
			if($list[$k]['relativestatus'] == 1 or $list[$k]['relativestatus'] == 3){
				unset($list[$k]);
			}else{
				if($i>6) unset($list[$k]);
				$i++;
			}
		}
		return $list;
	}

	/**************************** 主站搜索接口 Start *****************************/
	/**
	 * 找人
	 * 
	 * @param  $param array 参数数组
	 *      	keyword string 关键字
	 *			uid int 用户id
	 *			cityId int 省市id
	 *			areaId int 城市或区域id
	 *			sex int 性别
	 *			sort int 排序
	 *			page int 当前页，默认为第1页
	 * 			pageNum int 页显数量，默认为20条
	 *
	 * @return array 用户数据
	 */
	public function getSearchUserListForSite($param){
		//导入类
		import('@.ORG.Util.Coreseek');
		$co = new Coreseek(array('keyword'=>$param['keyword'],'limit'=>$param['pageNum'],'page'=>$param['page'],'conf'=>1));
		$userArray = $co->select();

		$apiModel = D('Api');

		foreach($userArray['uid'] as $key=>$val){
			// 用户信息
			$userinfo = $apiModel->getUserInfo($val);
			// 用户id
			$list[$key]['UserId'] = $userinfo['uid'];
			// 标红关键字
			if($param['keyword']) {
				$list[$key]['UserNickname'] = preg_replace("/".$param['keyword']."/i", "<font color='red'>$0</font>", strip_tags($userinfo['nickname']));
			}
			// 用户头像
			$list[$key]['UserAvatar'] = $userinfo['avatar'];
			// 用户头像alt
			$list[$key]['UserAlt'] = $userinfo['nickname'];
			// 用户性别
			$list[$key]['UserSex'] = $userinfo['gender'] == 1 ? '男' : ($userinfo['gender'] == 2 ? '女' : '保密');
			// 用户所在城市
			$list[$key]['UserCity'] = $userinfo['city_data'] ? $userinfo['city_data'] : '';
			// 用户个人主页
			$list[$key]['UserUrl'] = $userinfo['url_link'];
			// 关注数
			$list[$key]['AttentionNum'] = D('UcRelation')->getOtherCounts($val,1);
			// 粉丝数
			$list[$key]['FansNum'] = D('UcRelation')->getOtherCounts($val,2);
			// 好友数
			$list[$key]['FriendNum'] = D('UcRelation')->getOtherCounts($val,3);
			// 与我的关系状态
			$list[$key]['Relation'] = D('UcRelation')->getSearchStatus($param['uid'],$val);
		}
		$this->total = $userArray['total'];

		return $list;
	}
	
	/**
	 * 获取搜索结果数
	 * 
	 * @param  $param array 参数数组
	 *      	keyword string 关键字
	 *
	 * @return array 用户数据结果数
	 */
	public function getSearchUserCountForSite($param){
		//导入类
		import('@.ORG.Util.Coreseek');
		$co = new Coreseek(array('keyword'=>$param['keyword'],'conf'=>1));
		$userArray = $co->select();

		$count = $userArray['total'];
		if(!$count) {
			$count = 0;
		}
		return $count;
	}
	// /**
	//  * 无搜索结果，随机推荐用户等级在中级及以上的
	//  * 
	//  * @param  $uid int 用户id
	//  *
	//  * @return array 用户数据
	//  */
	// public function getRandUserListForSite($uid){
	// 	if($uid){
	// 		$where = '1 and u.nickname <> "" and u.is_del = 0 and ug.groupid in (2,3,12,13,14,15) and u.uid <> '.$uid.'';
	// 	}else{
	// 		$where = '1 and u.nickname <> "" and u.is_del = 0 and ug.groupid in (2,3,12,13,14,15)';
	// 	}		
	// 	$list = M()->Table('boqii_users u')->field('u.uid,u.nickname')->join('boqii_users_extendbbs ug ON ug.uid = u.uid')->where($where)->order('rand()')->limit(5)->select();
		
	// 	$i = 1;
	// 	foreach($list as $key=>$val){
	// 		// 用户信息
	// 		$userinfo = $apiModel->getUserInfo($val);
	// 		// 用户id
	// 		$userList[$key]['UserId'] = $userinfo['uid'];
	// 		// 标红关键字
	// 		if($param['keyword']) {
	// 			$userList[$key]['UserNickname'] = preg_replace("/".$param['keyword']."/i", "<font color='red'>$0</font>", strip_tags($userinfo['nickname']));
	// 		}
	// 		// 用户头像
	// 		$userList[$key]['UserAvatar'] = $userinfo['avatar'];
	// 		// 用户性别
	// 		$userList[$key]['UserSex'] = $userinfo['gender'] == 1 ? '男' : ($userinfo['gender'] == 2 ? '女' : '保密');
	// 		// 用户所在城市
	// 		$userList[$key]['UserCity'] = $userinfo['city_data'];
	// 		// 用户个人主页
	// 		$userList[$key]['UserUrl'] = $userinfo['url_link'];
	// 		// 关注数
	// 		$userList[$key]['AttentionNum'] = D('UcRelation')->getOtherCounts($val,1);
	// 		// 粉丝数
	// 		$userList[$key]['FansNum'] = D('UcRelation')->getOtherCounts($val,2);
	// 		// 好友数
	// 		$userList[$key]['FriendNum'] = D('UcRelation')->getOtherCounts($val,3);
	// 	}
	// 	return $userList;
	// }
	/**************************** 主站搜索接口 End *****************************/

}
?>
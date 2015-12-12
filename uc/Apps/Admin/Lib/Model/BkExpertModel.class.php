<?php
/**
 * 百科用户管理 Model
 *
 * @author: zlg
 * @created: 13-1-18
 */
class BkExpertModel extends Model {
	protected $trueTableName = 'boqii_users_extendbaike';

	//所有成员信息
	public function getList ($fields, $params) {
		//分页参数
		$page = $params['page'] ? $params['page'] : 1;
		$page_num = $params['page_num'] ? $params['page_num'] : 25; //$param['num'] 自定义 显示条数
		$page_start = ($page - 1) * $page_num;
		//本页条数
		$this->count = $this->where(array('status' => array('egt', 0)))->limit($page_start . ',' . $page_num)->count();
		//总条数
		$total = $this->where(array('status' => array('egt', 0)))->count();
		//总页数
		$this->totalPage = ceil($total / $page_num);
		$arrList = $this->where(array('status' => array('egt', 0)))->field($fields)
			->order('create_time desc')->limit($page_start . ',' . $page_num)->select();
		//读取主表的昵称

		foreach ($arrList as $key=>$val) {
            $apiModel = D('Api');
            $user = array();
            $user = $apiModel -> getUserInfo($val['uid']);
			if($user['nickname']) {
				$arrList[$key]['name'] = $user['nickname'];
			}
			else {
				$arrList[$key]['name'] = $user['uid'];
			}

            $arrList[$key]['pic_path']  = $user['avatar'];
		}
		return !empty($arrList) ? $arrList : array();
	}

	//uid 某个成员信息
	public function getInfo ($uid, $fields) {
		$arrInfo = $this->where(array('status' => array('egt', 0), 'uid' => $uid))->field($fields)->select();

		$user = M()->Table("boqii_users")->where("uid=".$arrInfo[0]['uid'])->field("uid,nickname")->find();
		if($user['nickname']) {
			$arrInfo[0]['name'] = $user['nickname'];
		}
		else {
			$arrInfo[0]['name'] = $user['uid'];
		}

		return !empty($arrInfo) ? $arrInfo : array();
	}

	//添加成员
	public function addMember ($params) {
		$status = $this->add($params);
		if($status) {
			//删除用户userInfo 缓存
			$cacheRedis = Cache::getInstance('Redis');
			//用户基本信息key
			$key = C('REDIS_KEY.userinfo').$params['uid'];
			$cacheRedis->del($key);
		}

		return $status;
	}

	//编辑成员
	public function editMember ($params) {
		$boolStatus = false;
		$status = $this->save($params);
		//修改主表昵称
		$status2 = M('boqii_users')->where(array('uid'=>$params['uid']))->setField('nickname',$params['name']);
		if($status && is_numeric($status2))  {
			//删除用户userInfo 缓存
			$cacheRedis = Cache::getInstance('Redis');
			//用户基本信息key
			$key = C('REDIS_KEY.userinfo').$params['uid'];
			$cacheRedis->del($key);
		   $boolStatus = true;
		}
		return $boolStatus;
	}

	//检测用户by uid、
	public function getUserInfo($uid) {
		$intUid = M('boqii_users')->where(array('uid'=>$uid,'is_del'=>0))->getField('uid');
		return $intUid;
	}

	//更改为百科标志
	public function editFlag ($uid) {
		$status = M('boqii_users')->where(array('uid'=>$uid))->setField('is_baike',1);
		return $status;
	}

}

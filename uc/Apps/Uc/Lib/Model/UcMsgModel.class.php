<?php
/**
 * 站内信Model类
 *
 * @created 2012-09-06
 * @author yumie
 */
class UcMsgModel extends Model{
	protected $trueTableName = 'uc_msg'; 
	/**
	 * 收件箱
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 我收到的信息数据
	 */
	public function getInboxMsg($param){
		$uid = $param['uid'];
		$where = 'mde.receverid = '.$uid.' and mde.recevestatus = 0';
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->count();
		$inboxarr = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->field('mde.id,mde.msgid,mde.readstatus,m.dateline,m.content,mde.sendid,mde.receverid,m.parent_id')->order('m.dateline desc')->limit("$page_start, $page_num")->select();
		//echo M()->Table('uc_msg m')->getLastSql();
		$list = array();
		foreach($inboxarr as $lists){
			//截取字符后内容
			$lists['scontent'] = mysubstr_utf8($lists['content'], 20);
			$lists['dateline'] = format_time($lists['dateline']);
			//发件人信息
			$userinfo = D('Api')->getUserInfo($lists['sendid']);
			$lists['senduser'] = $userinfo['nickname'];
			$lists['sendavatar'] = str_replace("_b", "_m",$userinfo['avatar']);
			$lists['gender'] = $userinfo['gender'];
			//收件人信息
			$userinfo = D('Api')->getUserInfo($lists['receverid']);
			$lists['receveuser'] = $userinfo['nickname'];
			$lists['receveavatar'] = $userinfo['avatar'];
			//判断是否是别人回复的
			if($lists['parent_id'] !=0){
				//原消息内容
				$omessage = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where('m.id = '.$lists['parent_id'])->field('m.content')->find();
				$lists['otitle'] = '回复&nbsp;&nbsp;';
				$lists['omessage'] = mysubstr_utf8($omessage['content'], 16);
			}
			
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 发件箱
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 我发出的信息数据
	 */
	public function getOutboxMsg($param){
		$uid = $param['uid'];
		$where = 'mde.sendid = '.$uid.' and mde.sendstatus = 0';
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->count();
		$outboxarr = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->field('mde.id,mde.msgid,mde.readstatus,m.dateline,m.content,mde.sendid,mde.receverid,m.parent_id')->order('m.dateline desc')->limit("$page_start, $page_num")->select();
		$list = array();
		foreach($outboxarr as $lists){
			//截取字符后内容
			$lists['scontent'] = mysubstr_utf8($lists['content'], 16);
			$lists['dateline'] = format_time($lists['dateline']);
			//发件人信息
			$userinfo = D('Api')->getUserInfo($lists['sendid']);
			$lists['senduser'] = $userinfo['nickname'];
			$lists['sendavatar'] = $userinfo['avatar'];
			//收件人信息
			$userinfo = D('Api')->getUserInfo($lists['receverid']);
			$lists['receveuser'] = $userinfo['nickname'];
			$lists['receveavatar'] = str_replace("_b", "_m",$userinfo['avatar']);
			$lists['recevegender'] = $userinfo['gender'];
			//判断是否是回复的消息
			if($lists['parent_id'] !=0){
				//原消息内容
				$omessage = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where('m.id = '.$lists['parent_id'])->field('m.content')->find();
				$lists['omessage'] = mysubstr_utf8($omessage['content'], 16);
			}
			
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 系统通知
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 我收到的系统消息数据
	 */
	public function getNotice($param){
		$uid = $param['uid'];
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		//排除早于注册时间的发的全站通知
		$regInfo = M()->Table('boqii_users')->where('uid = '.$uid)->field('regdate')->find();
		//查总条数
		//群发公告
		$count1 = M()->Table('uc_notice')->where('type = 0 and dateline > '.$regInfo['regdate'].'')->count();
		//通知
		$count2 = M()->Table('uc_notice m')->join('uc_notice_detail mde on m.id = mde.msgid')->where('mde.receverid = '.$uid.' and m.type = 1 and m.dateline > '.$regInfo['regdate'].'')->count();
		$this->total = $count1+$count2;
		$notice = M()->Table('uc_notice')->where('type = 0 and dateline > '.$regInfo['regdate'].'')->order('dateline desc')->select();
		$list = array();
		//更新群发状态表
		foreach($notice as $lists){
			//排除早于注册时间的发的全站通知
			$regInfo = M()->Table('boqii_users')->where('uid = '.$uid)->field('regdate')->find();
			if($regInfo['regdate'] < $lists['dateline']){
				//查询是否已入库
				$num = M()->Table('uc_notice_detail')->where('receverid = '.$uid.' and msgid ='.$lists['id'])->find();
				if(empty($num)){
					//写入数据库
					$data['sendid'] = 1328680;
					$data['receverid'] = $uid;
					$data['msgid'] = $lists['id'];
					M()->Table('uc_notice_detail')->add($data);	
				}
			}
		}
		//查询我的站内信
		$mynotice = M()->Table('uc_notice m')->join('uc_notice_detail mde on m.id = mde.msgid')->where('mde.receverid = '.$uid.' and m.dateline > '.$regInfo['regdate'].'')->field('mde.id,mde.readstatus,m.dateline,m.content,mde.sendid')->limit("$page_start, $page_num")->order('dateline desc')->select();
		foreach($mynotice as $lists){
			$lists['dateline'] = date('Y-m-d H:i',$lists['dateline']);

			//发件人信息
			$userinfo = D('Api')->getUserInfo($lists['sendid']);
			$lists['senduser'] = $userinfo['nickname'];
			$lists['sendavatar'] = str_replace("_b", "_m",$userinfo['avatar']);
			$lists['gender'] = $userinfo['gender'];
			$list[] = $lists;	
		}
		return $list;
	}
	
	/**
	 * 收件箱发件箱更改阅读状态
	 *
	 * @param $msgid int 信息id
	 *
	 * @return boolean 是否更新成功
	 */
	public function readMsg($msgid){
		$where = 'id = '.$msgid;
		$data['readstatus'] = 1;
		$r = M()->Table('uc_msg_detail')->where($where)->save($data); 
		if($r){
			return true;
		}
		return false;
	}
	
	/**
	 * 系统消息更改阅读状态
	 *
	 * @param $receverid int 接收者id
	 *
	 * @return boolean 是否更新成功
	 */
	public function updateNew($receverid){
		$where = 'receverid ='.$receverid;
		$data['readstatus'] = 1;
		$r = M()->Table('uc_notice_detail')->where($where)->save($data);
		if($r){
			return true;
		}
		return false;
	}
	
	/**
	 * 回复
	 *
	 * @param  $param array 参数数组
	 * 		pid int 消息id
	 *      uid int 用户id
	 * 		receverid int 接收者id
	 * 		content string 评论内容
	 *
	 * @return array 处理结果
	 */
	public function reciveMsg($param){
		if($param['receverid']){
			$isblack = D('UcRelation')->getSearchStatus($param['uid'],$param['receverid']);
			if($isblack == 4){
				return -2;
			}elseif($isblack == 5){
				return -1;
			}else{
				$data['content'] = $param['content'];
				$data['dateline'] = time();
				$data['parent_id'] = $param['pid'];
				$r = M()->Table('uc_msg')->add($data);
				if($r){
					$data1['sendid'] = $param['uid'];
					$data1['receverid'] = $param['receverid'];
					$data1['msgid'] = $r;
					M()->Table('uc_msg_detail')->add($data1);
				}
			return $r;
			}
		}else{
			return 0;
		}
	}
	
	/**
	 * 删除站内信
	 *
	 * @param  $param array 参数数组
	 * 		msgid int 消息id
	 *      uid int 用户id
	 *
	 * @return array 处理结果
	 */
	public function delNotice($param){
		$msgid = $param['msgid'];
		$uid = $param['uid'];
		$where = 'id = '.$msgid.' and receverid ='.$uid;
		$data['recevestatus'] = 1;
		//删除消息本身
		$r = M()->Table('uc_notice_detail')->where($where)->save($data);
		return $r;
	}
	
	/**
	 * 删除消息
	 *
	 * @param  $param array 参数数组
	 * 		msgid int 消息id
	 *      type int 消息类型 1:收件箱2:发件箱
	 *      uid int 用户id
	 *
	 * @return array 处理结果
	 */
	public function delMsg($param){
		$msgid = $param['msgid'];
		$type = $param['type'];
		//判断是收件箱还是发件箱，通过传参判断1收件箱2发件箱
		if($type == 1){
			$where = 'id ='.$msgid.' and receverid ='.$param['uid'];
			$data['recevestatus'] = 1;
		}elseif($type == 2){
			$where = 'id ='.$msgid.' and sendid ='.$param['uid'];
			$data['sendstatus'] = 1;
		}
		//删除消息本身
		$r = M()->Table('uc_msg_detail')->where($where)->save($data);
		if($type == 1 && $r){
			//删除消息并加入黑名单(只针对收件箱)
			if($param['checkblack']){
				$msgdetail = M()->Table('uc_msg_detail')->field('sendid')->where('id ='.$msgid)->find();
				//加入黑名单,判断如果是官方账号，则不操作
				if($msgdetail['sendid'] != 1328680){
					D('UcRelation')->addBlack($param['uid'],$msgdetail['sendid']);
				}
			}
		}
		return $r;
	}
	
	/**
	 * 批量删除
	 *
	 * @param  $param array 参数数组
	 * 		msgid int 消息id
	 *      type int 消息类型 1:收件箱2:发件箱
	 *      uid int 用户id
	 *
	 * @return array 处理结果
	 */
	public function delMsgs($param){
		$msgid = $param['msgid'];
		$type = $param['type'];
		//判断是收件箱还是发件箱，通过传参判断1收件箱2发件箱
		if($type == 1){
			$where = 'id in ('.$msgid.') and receverid ='.$param['uid'];
			$data['recevestatus'] = 1;
		}elseif($type == 2){
			$where = 'id in ('.$msgid.') and sendid ='.$param['uid'];
			$data['sendstatus'] = 1;
		}
		//删除消息本身
		$r = M()->Table('uc_msg_detail')->where($where)->save($data);
		if($type == 1 && $r){
			//批量删除消息并加入黑名单(只针对收件箱)
			if($param['checkblack']){
				$msgidstr = explode(',',$msgid);
				foreach($msgidstr as $v){
					$msgdetail = M()->Table('uc_msg_detail')->where('id ='.$v)->field('sendid')->find();
					//加入黑名单,判断如果是官方账号，则不操作
					if($msgdetail['sendid'] != 1328680){
						D('UcRelation')->addBlack($param['uid'],$msgdetail['sendid']);
					}
				}
			}
		}
		return $r;
	}
	
	/**
	 * 发送消息
	 *
	 * @param  $param array 参数数组
	 *      content string 内容
	 *      uid int 用户id
	 *      receverid int 接收者id
	 *
	 * @return array 处理结果
	 */
	public function sendMsg($param){
		$receverid = M()->Table('boqii_users')->where('uid ='.$param['receverid'])->field('uid')->find();
		if($receverid){
			//被对方拉黑不能发送消息
			//$isblack = M()->Table('uc_friend_relative')->where('uid ='.$receverid['uid'].' and attention_uid ='.$param['uid'].' and status = 1')->find();
			$isblack = D('UcRelation')->getSearchStatus($param['uid'],$receverid['uid']);
			if($isblack == 4){
				return -2;
			}elseif($isblack == 5){
				return -1;
			}else{
				$data['content'] = $param['content'];
				$data['dateline'] = time();
				$r = M()->Table('uc_msg')->add($data);
				if($r){
					$data1['sendid'] = $param['uid'];
					$data1['receverid'] = $param['receverid'];
					$data1['msgid'] = $r;
					$data1['readstatus'] = 0;
					M()->Table('uc_msg_detail')->add($data1);
				}
				return $r;
			}	
		}else{
			return 0;
		}
	}
	
	/**
	 * 发系统消息
	 *
	 * @param  $param array 参数数组
	 *      content string 内容
	 *      uid int 用户id
	 *      receverid int 接收者id
	 *
	 * @return array 处理结果
	 */
	public function sendNotice($param){
		$data['content'] = $param['content'];
		$data['dateline'] = time();
		$r = M()->Table('uc_notice')->add($data);
		if($r){
			$data1['sendid'] = $param['uid'];
			$data1['receverid'] = $param['receverid'];
			$data1['msgid'] = $r;
			M()->Table('uc_notice_detail')->add($data1);
		}
		return $r;
	}
	
	/**
	 * 按指定关键字搜好友
	 *
	 * @param  $param array 参数数组
	 *      keyword string 关键字
	 *
	 * @return array 用户数组
	 */
	public function getSearchList($param){
		$where = '1 and is_del = 0';
		
		$keyword = $param['keyword'];
		if(!empty($keyword)) {
			$where = $where ." and nickname like '%$keyword%'";
		}
		$listarr = M()->Table('boqii_users')->where($where)->field('uid,nickname,username')->limit(10)->select();
		//echo $user->getLastSql();
		foreach($listarr as $lists){
			if($lists['nickname'] == ''){
				$lists['nickname'] = $lists['username'];
			}
			if($keyword) {
				$lists['nickname'] = str_replace($keyword,"<font color='red'>".$keyword."</font>",$lists['nickname']);
			}
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 未读消息条数
	 *
	 * @param  $uid int 用户id
	 *
	 * @return array 消息条数
	 */
	public function getMsgCount($uid){
		$where = 'mde.receverid = '.$uid.' and mde.readstatus = 0 and mde.recevestatus = 0';
		$num = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->count();
		return $num;
	}
	
	/**
	 * 未读系统通知
	 *
	 * @param  $uid int 用户id
	 *
	 * @return array 消息条数
	 */
	public function getNoticeCount($uid){
		//排除早于注册时间的发的全站通知
		$regInfo = M()->Table('boqii_users')->where('uid = '.$uid)->field('regdate')->find();
		//查总条数
		//群发公告
		$count1 = M()->Table('uc_notice')->where('type = 0 and dateline > '.$regInfo['regdate'].'')->count();

		//通知
		$count2 = M()->Table('uc_notice m')->join('uc_notice_detail mde on m.id = mde.msgid')->where('mde.receverid = '.$uid.' and m.type = 1 and m.dateline > '.$regInfo['regdate'].'')->count();
		$count = $count1+$count2;
		//已读条数
		$where = 'mde.receverid = '.$uid.' and mde.readstatus = 1 and m.dateline > '.$regInfo['regdate'].'';
		$num = M()->Table('uc_notice m')->join('uc_notice_detail mde on m.id = mde.msgid')->where($where)->count();
		$result = $count - $num;
		if($result < 0){
			return 0;
		}else{
			return $result;
		}
	}
	
	/**
	 * 收件箱 (手机端用)
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 我收到的信息数据
	 */
	public function getMobileInboxMsg($param){
		$uid = $param['uid'];
		$sendid = $param['sendid'];
		$where = 'mde.receverid = '.$uid.' and mde.sendid ='.$sendid.' and mde.recevestatus = 0 and mde.readstatus = 0';
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->count();
		$inboxList = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->field('m.content')->order('m.dateline asc')->limit("$page_start, $page_num")->select();
		//echo M()->Table('uc_msg m')->getLastSql();
		return $inboxList;
	}
	
	/**
	 * 收件箱阅读 (手机端用)
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *
	 * @return mix
	 */
	 public function readMobileInboxMsg($param){
	 	$uid = $param['uid'];
		$sendid = $param['sendid'];
		$where = 'receverid = '.$uid.' and sendid ='.$sendid.' and recevestatus = 0 and readstatus = 0';
		
		$data['readstatus'] = 1;
		$r = M()->Table('uc_msg_detail')->where($where)->save($data);  
		if($r){
			return true;
		}
		return false;
	 }

	/**
	 * 收件箱
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 * 		num int 显示数量
	 *
	 * @return array 我收到的信息数据
	 */
	public function getInboxMsgList($param){
		$where = 'mde.receverid = '.$param['uid'].' and mde.recevestatus = 0';
		
		// 未读消息数量
		$result['newnum'] = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where.' AND readstatus=0')->count();
		// 指定消息数量
		$inboxarr = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where($where)->field('mde.id,mde.msgid,mde.readstatus,m.dateline,m.content,mde.sendid,mde.receverid,m.parent_id')->order('m.dateline desc')->limit($param['num'])->select();
		$list = array();
		foreach($inboxarr as $lists){
			//截取字符后内容
			$lists['scontent'] = mysubstr_utf8($lists['content'], 20);
			$lists['dateline'] = format_time($lists['dateline']);
			//发件人信息
			$userinfo = D('Api')->getUserInfo($lists['sendid']);
			$lists['senduser'] = $userinfo['nickname'];
			$lists['sendavatar'] = str_replace("_b", "_m",$userinfo['avatar']);
			$lists['gender'] = $userinfo['gender'];
			//收件人信息
			$userinfo = D('Api')->getUserInfo($lists['receverid']);
			$lists['receveuser'] = $userinfo['nickname'];
			$lists['receveavatar'] = $userinfo['avatar'];
			//判断是否是别人回复的
			if($lists['parent_id'] !=0){
				//原消息内容
				$omessage = M()->Table('uc_msg m')->join('uc_msg_detail mde on m.id = mde.msgid')->where('m.id = '.$lists['parent_id'])->field('m.content')->find();
				$lists['otitle'] = '回复&nbsp;&nbsp;';
				$lists['omessage'] = mysubstr_utf8($omessage['content'], 16);
			}
			
			$list[] = $lists;
		}
		$result['list'] = $list;

		return $result;
	}
}
?>
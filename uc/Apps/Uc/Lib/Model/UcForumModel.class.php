<?php
/**
 * 社区Model类
 *
 * @created 2012-09-05
 * @author yumie
 */
class UcForumModel extends Model {
	protected $trueTableName = 'bbs_threads'; 
	/**
	 * 我发布的话题
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id(我的)
	 *      oruid int 用户id(他人)
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 话题数据
	 */
	public function getMyThread($param){
		$uid = $param['uid'];
		$oruid = $param['oruid'];
		if($oruid){
			$uid = $param['oruid'];
		}else{
			$uid = $param['uid'];
		}
		
		$where = 't.authorid ='.$uid.' and t.special <>6 and t.ifcheck = 1 and t.displayorder >= 0';

		// 条件：精华帖
		if($param['type']){
			$where .= ' and t.digest = '.$param['type'];
		}
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:8;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('bbs_threads t')->where($where)->count();
		$threadlist = M()->Table('bbs_threads t')->join('bbs_forums f ON t.fid = f.fid')->where($where)->field('t.tid,t.author,t.authorid,t.subject,t.dateline,t.lastpost,t.lastposter,t.views,t.replies,t.digest,f.fid,f.name')->order('t.dateline desc')->limit("$page_start, $page_num")->select();
		if(!$threadlist){
			return array();
		}
		
		foreach($threadlist as $lists){
			$lists['lastposterid'] = $this->getUidByUsername($lists['lastposter']);
			$lists['lastposter'] = $this->getNicknameByUsername($lists['lastposter']);
			$lists['dateline'] = format_time($lists['dateline']);
			$list[] = $lists;
		}
		// echo M()->getLastSql();echo '<pre>';print_r($list);exit;
		return $list;
	}
	
	/**
	 * 我回复的话题
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id(我的)
	 *      oruid int 用户id(他人)
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 话题数据
	 */
	public function getMyReply($param){
		$uid = $param['uid'];
		$oruid = $param['oruid'];
		if($oruid){
			$uid = $param['oruid'];
		}else{
			$uid = $param['uid'];
		}
		$where = 'p.authorid ='.$uid.' and p.invisible = 0 and p.ifcheck = 1 and first = 0';
		
		//去重查询回应的所有主题帖ID
		$threads = M()->Table('bbs_posts p')->where($where)->field('distinct(p.tid)')->order('p.dateline desc')->select();
		$tid = array();
		foreach($threads as $v){
			$tid[] = $v['tid'];	
		}
		$tids = implode(',',$tid);
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:8;
		$page_start = ($page-1)*$page_num;
		if($tids){
			$where2 = 't.tid in ('.$tids.') and t.ifcheck = 1 and t.displayorder >= 0';
			// 条件：精华帖
			if($param['type']){
				$where2 .= ' and t.digest = '.$param['type'];
			}
			$this->total = M()->Table('bbs_threads t')->where($where2)->count();
			$postlist = M()->Table('bbs_threads t')->join('bbs_forums f ON t.fid = f.fid')->where($where2)->field('t.tid,t.author,t.authorid,t.subject,t.dateline,t.lastpost,t.lastposter,t.views,t.replies,t.digest,f.fid,f.name')->order('t.dateline desc')->limit("$page_start, $page_num")->select();
			//echo M('bbs_threads t')->getLastSql();
			foreach($postlist as $lists){
				$lists['lastposterid'] = $this->getUidByUsername($lists['lastposter']);
				$lists['lastposter'] = $this->getNicknameByUsername($lists['lastposter']);
				$lists['dateline'] = format_time($lists['dateline']);
				$list[] = $lists;
			}
			// echo M()->getLastSql();echo '<pre>';print_r($list);exit;
			return $list;
		}
		return $postlist;
	}
	
	/**
	 * 我关注的群组
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id(我的)
	 *      oruid int 用户id(他人)
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 群组数据
	 */
	public function getMyAttentionGroup($param){
		$uid = $param['uid'];
		$oruid = $param['oruid'];
		if($oruid){
			$uid = $param['oruid'];
		}else{
			$uid = $param['uid'];
		}
		$where = 'fa.uid='.$uid.' and fa.fid != 0 and f.status = 1 and f.isdeleted = 0';
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:8;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('bbs_favorites fa')->join('bbs_forums f ON fa.fid = f.fid')->where($where)->field('fa.uid,fa.fid')->count();
		$fidlist = M()->Table('bbs_favorites fa')->join('bbs_forums f ON fa.fid = f.fid')->where($where)->field('fa.uid,fa.fid')->limit("$page_start, $page_num")->select();
		foreach($fidlist as $k=>$v){
			//板块信息
			$foruminfo = $this->getForumInfo($v['fid']);
			$fidlist[$k]['name'] = $foruminfo['name'];
			$fidlist[$k]['threads'] = $foruminfo['threads'];
			$fidlist[$k]['posts'] = $foruminfo['posts'];
			$fidlist[$k]['icon'] = C('IMG_DIR').'/'.$foruminfo['icon'];
			//我的发帖数
			$fidlist[$k]['myposts'] = $this->getMyForumThreads($uid,$v['fid']);
			//最新话题
			$fidlist[$k]['newthreads'] = $this->getNewThreads($uid,$v['fid'],0);
			//热门话题
			$fidlist[$k]['hotthreads'] = $this->getNewThreads($uid,$v['fid'],1);
		}
		return $fidlist;
	}
	
	/**
	 * 我关注的话题列表
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id(我的)
	 *      oruid int 用户id(他人)
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 话题数据
	 */
	public function getMyAttentionThreads($param){
		$uid = $param['uid'];
		$oruid = $param['oruid'];
		if($oruid){
			$uid = $param['oruid'];
		}else{
			$uid = $param['uid'];
		}
		$param['tids'] = $this->getAttentionThreads($uid);
		if($param['tids']){
			$where = 't.tid in ('.$param['tids'].') and t.ifcheck = 1 and t.displayorder >= 0';
			// 条件：精华帖
			if($param['type']){
				$where .= ' and t.digest = '.$param['type'];
			}
			$page = $param['page']?$param['page']:1;
			$page_num = $param['page_num']?$param['page_num']:8;
			$page_start = ($page-1)*$page_num;
			
			$this->total = M()->Table('bbs_threads t')->where($where)->count();
			$threadlist = M()->Table('bbs_threads t')->join('bbs_forums f ON t.fid = f.fid')->where($where)->field('t.tid,t.author,t.authorid,t.subject,t.dateline,t.lastpost,t.lastposter,t.views,t.replies,t.digest,f.fid,f.name')->order('t.dateline desc')->limit("$page_start, $page_num")->select();
			//echo M()->getLastSql();
			foreach($threadlist as $lists){
				$lists['lastposterid'] = $this->getUidByUsername($lists['lastposter']);
				$lists['lastposter'] = $this->getNicknameByUsername($lists['lastposter']);
				$lists['dateline'] = format_time($lists['dateline']);
				$list[] = $lists;
			}
			// echo M()->getLastSql();echo '<pre>';print_r($list);exit;
		}
		return $list;
	}
	
	/**
	* 板块详细信息
	*
	* @param $fid int 板块id
	*
	* @return array 板块信息
	*/
	public function getForumInfo($fid){
		$where = 'f.fid ='.$fid.' and f.isdeleted = 0 and f.status = 1';
		$foruminfo = M()->Table('bbs_forums f')->join('bbs_forumfields bf ON f.fid = bf.fid')->where($where)->field('f.fid,f.name,f.threads,f.posts,bf.icon')->find();
		return $foruminfo;
	}
	
	/**
	* 我关注的板块中我的发帖数
	*
	* @param $uid int 用户id
	* @param $fid int 板块id
	*
	* @return array 板块信息
	*/
	public function getMyForumThreads($uid,$fid){
		return M()->Table('bbs_threads')->where('authorid ='.$uid.' and fid = '.$fid.' and ifcheck = 1 and displayorder >= 0')->count();
	}
	
	/**
	* 我关注的板块最新话题，热门话题
	*
	* @param $uid int 用户id
	* @param $fid int 板块id
	* @param $sort int 排序
	*
	* @return array 话题数据
	*/
	public function getNewThreads($uid,$fid,$sort){
		$where = 'authorid ='.$uid.' and fid = '.$fid.' and ifcheck = 1 and displayorder >= 0';
		//0最新话题1热门话题
		if($sort == 0){
			$sort = 'dateline desc';
		}else{
			$sort = 'views desc';
		}
		$newthreads = M()->Table('bbs_threads')->where($where)->field('tid,subject,views,replies,dateline')->order($sort)->limit(3)->select();
		return $newthreads;
	}
	
	/**
	* 我关注的话题id
	*
	* @param $uid int 用户id
	*
	* @return string 话题数据
	*/
	public function getAttentionThreads($uid){
		$where = 'uid='.$uid.' and tid != 0';
		$tidarr = M()->Table('bbs_favorites')->where($where)->field('tid')->select();
		foreach($tidarr as $v){
			$tid[] = $v['tid'];
		}
		$tids = implode(',',$tid);
		return $tids;
	}
	
	/**
	* 根据用户名取昵称，如果不存在，返回uid
	*
	* @param $username string 用户名
	*
	* @return string 用户数据
	*/
	public function getNicknameByUsername($username){
		$userinfo = M()->Table('boqii_users')->where("username='".$username."'")->field('uid,nickname')->find();
		if($userinfo['nickname']){
			return $userinfo['nickname'];
		}else{
			return $userinfo['uid'];
		}
	}
	
	/**
	* 根据用户名取uid
	*
	* @param $username string 用户名
	*
	* @return int 用户uid
	*/
	public function getUidByUsername($username){
		$userinfo = M()->Table('boqii_users')->where("username='".$username."'")->field('uid')->find();
		return $userinfo['uid'];
	}
	
	/**
	 * 更改用户个性签名
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 * 		sightml string 签名
	 *
	 * @return array 处理结果
	 */
	public function updateSignhtml($param){
		$where = 'uid ='.$param['uid'];
		$data['sightml'] = $param['sightml'];
		$r = M()->Table('boqii_users_extend')->where($where)->save($data);
		return $r;
	}
}
?>
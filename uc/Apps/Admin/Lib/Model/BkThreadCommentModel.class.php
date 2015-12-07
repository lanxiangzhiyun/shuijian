<?php
/**
 * BkThreadComment Model类
 */
class BkThreadCommentModel extends Model{
	
	protected $tableName='bk_thread_comment';

	/*
	 * 百科帖子评论和用户关联查询
	 */
	public function hasThreadCommentUser($page,$limit,$where){
		$commentList = $this->table('bk_thread_comment threadcomment,bk_thread thread,boqii_users user,bk_cat cat')->field('threadcomment.id,threadcomment.thread_id,thread.title,cat.name,threadcomment.content,threadcomment.create_time,user.nickname,user.uid,threadcomment.is_check')->where($where)->order('threadcomment.id DESC')->limit($limit)->page($page)->select();

		foreach($commentList as $key=>$val) {
			//修改pre标签
			$val['content'] = str_replace('pre>', 'p>', $val['content']);
			$val['content'] = str_replace('<pre', '<p', $val['content']);
			//去除图片
			$result = $this->clearImg($val['content']);
			$commentList[$key]['content'] = $result['content'];
			$commentList[$key]['hasImg'] = $result['hasImg'];
			
		}

		return $commentList;
	}

	/**
	 * 图片处理
	 */
	public function clearImg($content) {
		$hasImg = 0;
		$p = '/<img.*src="(.*)"\\s*.*>/iU';
		preg_match_all($p, $content, $m); 
		if(is_array($m[0]) && !empty($m[0])) {
			foreach($m[0] as $key => $value) {
				//非表情图片
				if(strpos($value, 'emotion') === false) {
					$hasImg = 1;
				}
				$content = str_replace($value, '', $content);
			}
		}
		return array('content'=>$content, 'hasImg'=>$hasImg);
	}
	/*
	 * 获取百科帖子评论个数
	 */
	public function hasThreadCommentCount($where){
		$result = $this->table('bk_thread_comment threadcomment,bk_thread thread,boqii_users user,bk_cat cat')->where($where)->count();
		return $result;
	}

	/**
	 * 帖子评论详细
	 *
	 * @param $id int 帖子评论ID
	 */
	public function getThreadCommentDetail($id) {
		//评论
		$comment = $this->where(array('id'=>$id))->field('id,thread_id,uid,user_type,content,create_time,parent_id,status,ip,is_check')->find();
		
		$comment['create_time'] = date('Y-m-d H:i:s', $comment['create_time']);

		//帖子标题
		$thread = M()->Table('bk_thread')->where('id='.$comment['thread_id'])->field('title')->find();
		$comment['title'] = $thread['title'];

		//用户
		$user = M()->Table('boqii_users')->where('uid='.$comment['uid'])->field('uid,nickname')->find();
		$comment['nickname'] = $user['nickname'] ? $user['nickname'] : $user['uid'];

		return $comment;
	}

	/**
	 * 删除评论
	 */
	public function delThreadComment($param) {
		// 评论
		$comment = M() -> Table('bk_thread_comment') -> where('id=' . $param['id'] . ' AND status >= 0') -> field('id, uid, thread_id,create_time') -> find();
		if (!$comment) {
			return false;
		} else {
			// 逻辑删除帖子评论
			$res = M() -> Table('bk_thread_comment') -> where('id=' . $param['id']) -> save(array('status' => -1));
			if ($res) {
				// 评论数更新
				$thread = M() -> Table('bk_thread') -> where('id=' . $comment['thread_id']) -> field('cat_id, comment_num, lastpost_time, expert_id, question_status, question_reply_time') -> find();

				$comment_num = $thread['comment_num']-1 >= 0 ? $thread['comment_num']-1 : 0;
				$data['comment_num'] = $comment_num;

				if ($comment['create_time'] == $thread['lastpost_time']) {
					// 更新帖子最后发帖人
					$last = M() -> Table('bk_thread_comment') -> where('thread_id =' . $comment['thread_id'] . " AND id!= ".$comment['id']." AND status>=0") -> field('uid,create_time') -> order('create_time DESC') -> find();
					if($last) {
						$user = D('UcUser') -> getUserInfo($last['uid']);
						$data['lastpost_uid'] = $last['uid'];
						$data['lastpost_nickname'] = $user['nickname'];
						$data['lastpost_time'] = $last['create_time'];
					} else {
						$data['lastpost_uid'] = 0;
						$data['lastpost_nickname'] = '';
						$data['lastpost_time'] = 0;
					}
				} 
				// 问题已回答 判断是否删除的是专家回答
				if ($thread['question_status'] == 1 && $thread['expert_id'] == $comment['uid'] && $comment['create_time'] == $thread['question_reply_time']) {
					//是否还有该专家的其他回答
					$last = M()->Table('bk_thread_comment')->where('uid='.$thread['expert_id']. ' AND parent_id = 0 AND status>=0')->field('create_time')->order('create_time DESC')->find();
					if($last) {
						$data['question_status'] = 0;
						$data['question_reply_time'] = 0;
					} else {
						$data['question_status'] = 1;
						$data['question_reply_time'] = $last['create_time'];
					}
				} 
				M() -> Table('bk_thread') -> where('id=' . $comment['thread_id']) -> save($data); 

				return true;
			} else {
				return false;
			} 
		} 
	}

	/**
	* 审核评论
	*/
	public function checkComment($param){
		$id = $param['id'];
		// 是否已经通过审核
		$ischeck = $this->where(array('id'=>$id))->getField('is_check');
		if(!$ischeck) {
			// 审核通过
			$this->where(array('id'=>$id))->save(array('is_check'=>1));

			//评论
			$comment = $this->where(array('id'=>$id))->field('id,uid,thread_id,parent_id,is_check,create_time')->find();
			if($comment['is_check'] == 1) {

				// 更新+1人气和+1啵币
				D('BkThread')->updateMemberExtcredits(array('authorid'=>$comment['uid'], 'score'=>1));
				// 评论时间
				$commenttime = time(); 
				$apiModel = D('Api');
				$user = $apiModel -> getUserInfo($comment['uid']);
				// 帖子更新
				$expertIdList = $this->getAskExpertIdList($comment['thread_id']);
				if ($expertIdList && in_array($comment['uid'], $expertIdList)) {
					$data['question_status'] = 1;
					$data['question_reply_time'] = $comment['create_time'];
					// 专家回答数+1
					M()->Table('boqii_users_extendbaike')->where('uid='.$comment['uid'])->setInc('answer_num',1);
					// 专家回答状态为1
					M()->Table('bk_thread_expert')->where(array('thread_id'=>$comment['thread_id'],'expert_id'=>$comment['uid']))->save(array('status'=>1));
				} 
				$data['lastpost_uid'] = $comment['uid'];
				$data['lastpost_nickname'] = $user['nickname'];
				$data['lastpost_time'] = $commenttime;
				$data['comment_num'] = array('exp','comment_num+1');
				M('bk_thread') -> where('id=' . $comment['thread_id']) -> save($data); 
				// 生成动态
				// 百科帖子评论回复
				if (isset($comment['parent_id']) && $comment['parent_id']) {
					// 帖子评论
					$pcomment = M() -> Table('bk_thread_comment') -> where('id=' . $comment['parent_id']) -> field('uid') -> find(); 
					// 百科帖子评论回复动态
					// type=5 operatetype=5 百科帖子评论回复
					$dynamic['uid'] = $comment['uid'];
					$dynamic['ouid'] = $pcomment['uid'];
					$dynamic['type'] = 5;
					$dynamic['operatetype'] = 5;
					$dynamic['oid'] = $comment['id'];
					$dynamic['mid'] = $comment['parent_id'];
					add_dynamic($dynamic);
				} 
				// 百科帖子评论
				else {
					// 帖子
					$thread = $this -> where('id=' . $param['thread_id']) -> field('uid') -> find(); 
					// 百科帖子评论动态
					// type=8 operatetype=4 百科帖子评论
					$dynamic['uid'] = $comment['uid'];
					$dynamic['ouid'] = $thread['uid'];
					$dynamic['type'] = 8;
					$dynamic['operatetype'] = 4;
					$dynamic['oid'] = $comment['id'];
					$dynamic['mid'] = $comment['thread_id'];
					add_dynamic($dynamic);
				} 
			}
		}
	}

	/** 
	 * 获取问答的被邀请专家id
	 *
	 * @param $threadId int 问答id
	 *
	 * @return array 专家id列表数组
	 */
	public function getAskExpertIdList($threadId) {
		$expertIdList = M()->Table('bk_thread_expert')->where('thread_id='.$threadId)->getField('expert_id', true);

		return $expertIdList;
	}

}
?>
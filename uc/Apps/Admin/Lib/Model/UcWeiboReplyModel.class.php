<?php
/**
 * UcWeiboReply Model类
 */
class UcWeiboReplyModel extends RelationModel{
	
	protected $tableName='uc_weibo_reply';

	/*
	*微博评论和用户关联查询
	*/
	public function hasUserAndWeiboReply($page,$limit,$where){
		$result = $this->table('uc_weibo_reply reply,boqii_users user,uc_weibo weibo')->field('reply.id,reply.dateline,reply.message,weibo.id wid,weibo.weibo_content,user.nickname,user.uid')->where($where)->order('reply.id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	
	/*
	*获取微博评论个数
	*/
	public function hasWeiboReplyCount($where){
		$result = $this->table('uc_weibo_reply reply,boqii_users user,uc_weibo weibo')->where($where)->count();
		return $result;
	}

}
?>
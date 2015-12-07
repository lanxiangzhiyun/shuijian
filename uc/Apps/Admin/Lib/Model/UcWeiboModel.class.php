<?php
/**
 * UcWeibo Model类
 */
class UcWeiboModel extends RelationModel{
	
	protected $tableName='uc_weibo';
	/*
	*日志和用户关联查询
	*/
	public function hasUserAndWeibo($page,$limit,$where){
		$result = $this->table('uc_weibo weibo,boqii_users user')->field('weibo.id,weibo.weibo_content,weibo.weibo_pic,weibo.weibo_time,weibo.broadcasts,weibo.comments,weibo.uid,user.nickname,user.uid')->where($where)->order('weibo.id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	
	/*
	*获取日志个数
	*/
	public function hasWeiboCount($where){
		$result = $this->table('uc_weibo weibo,boqii_users user')->where($where)->count();
		return $result;
	}
}
?>
<?php
/**
 * UcDiaryComment Model类
 */
class UcDiaryCommentModel extends RelationModel{
	
	protected $tableName='uc_diary_comment';

	/*
	*日志评论和用户关联查询
	*/
	public function hasDiaryCommentUser($page,$limit,$where){
		$result = $this->table('uc_diary_comment diarycomment,uc_diary diary,boqii_users user')->field('diarycomment.id,diarycomment.diaryid,diary.title,diarycomment.content,diarycomment.dateline,user.nickname,user.uid')->where($where)->order('diarycomment.id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	/*
	*获取日志评论个数
	*/
	public function hasDiaryCommentCount($where){
		$result = $this->table('uc_diary_comment diarycomment,uc_diary diary,boqii_users user')->where($where)->count();
		return $result;
	}
}
?>
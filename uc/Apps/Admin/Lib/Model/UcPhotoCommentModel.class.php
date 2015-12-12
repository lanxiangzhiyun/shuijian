<?php
/**
 * UcPhotoComment Model类
 */
class UcPhotoCommentModel extends RelationModel{
	
	protected $tableName='uc_photo_comment';


	/*
	*图片评论和用户关联查询
	*/
	public function hasUserAndPhotoComment($page,$limit,$where){
		$result = $this->table('uc_photo_comment comment,boqii_users user,uc_photo photo')->field('comment.id,comment.content,photo.photo_path,comment.photo_id,photo.album_id,comment.dateline,comment.uid,user.nickname')->where($where)->order('comment.id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	
	/*
	*获取图片评论个数
	*/
	public function hasPhotoCommentCount($where){
		$result = $this->table('uc_photo_comment comment,boqii_users user,uc_photo photo')->where($where)->count();
		return $result;
	}

}
?>
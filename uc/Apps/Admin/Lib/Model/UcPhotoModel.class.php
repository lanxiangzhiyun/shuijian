<?php
/**
 * UcPhoto Model��
 */
class UcPhotoModel extends RelationModel{
	
	protected $tableName='uc_photo';
	
	/*
	*ר�����û�������ѯ
	*/
	public function hasPhotoAndAlbum($page,$limit,$where){
		$result = $this->table('uc_photo photo,uc_album album,boqii_users user')->field('photo.photo_id,photo.photo_path,photo.photo_name,photo.photo_desc,photo.album_id,photo.album_id,photo.cretime,album.title,user.nickname')->where($where)->order('photo_id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	/*
	*��ȡͼƬ����
	*/
	public function hasPhotoCount($where){
		$result = $this->table('uc_photo photo,uc_album album,boqii_users user')->where($where)->count();
		return $result;
	}
}

?>
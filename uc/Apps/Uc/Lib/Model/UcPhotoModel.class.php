<?php
/**
 * UcPhoto Model类
 *
 * @author:zlg
 * @created:2013-4-3
 */
class UcPhotoModel extends RelationModel{

	protected $tableName='uc_photo';

	/**
	 * 取得用户的总照片数
	 * @param $uid
	 * @return mixed   总照片数
	 */
	public function getUserPhotoCnt($uid) {
		//总照片数
		$photoCnt = M()->Table("uc_photo")->where("uid=".$uid." AND album_id != 0 AND status >= 0")->count();
		return $photoCnt;
	}
}

?>
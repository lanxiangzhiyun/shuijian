<?php
/**
 * UcAdvertisement Model类
 */
class UcAdvertisementModel extends RelationModel{
	
	protected $tableName='uc_advertisement';

	
	/*
	*根据条件获得广告
	*/
	public function hasManyAdvertisement($page,$limit,$where,$order){
		$result = $this->table('uc_advertisement ad,uc_admin admin')->field('ad.id,ad.linkpath,ad.pic_path,ad.title,ad.code,ad.createtime,admin.username,admin.truename')->order($order)->where($where)->limit($limit)->page($page)->select();
		return $result;
	}
	/*
	*根据条件获得个数
	*/
	public function hasAdvertisementCount($where){
		$result = $this->table('uc_advertisement ad,uc_admin admin')->where($where)->count();
		return $result;
	}
}
?>
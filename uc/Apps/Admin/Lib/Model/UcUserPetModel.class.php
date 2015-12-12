<?php
/**
 * UcUserPet Model类
 */
class UcUserPetModel extends RelationModel{
	protected $tableName='uc_user_pet';
	/*
	*和用户关联查询
	*/
	public function hasUserAndPet($page,$limit,$where){
		$result = $this->table('uc_user_pet pet,boqii_users user,boqii_pet_class class')->field('pet.id,pet.petname,pet.picpath,pet.pettype,pet.is_default,pet.cretime,pet.petstatus,user.nickname,user.uid,class.title')->where($where)->order('id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	
	/*
	*个数
	*/
	public function hasPetCount($where){
		$result = $this->table('uc_user_pet pet,boqii_users user,boqii_pet_class class')->where($where)->count();
		return $result;
	}

	//获取宠物种类名称
	public function getPetName ($id) {
	    $strPetClass = M("boqii_pet_class") ->where(array('id'=>$id)) -> getField('title');
		return  $strPetClass;
	}

}
?>
<?php
/**
 * BoqiiUserPet Model类
 *
 * @created 2014-11-27
 * @author Fongson
 */
class BoqiiUserPetModel extends Model {
	// 数据库表
	protected $trueTableName='boqii_user_pet';

	/**
	 * 和用户关联查询
	 */
	public function hasUserAndPet($page,$limit,$where){
		$list = $this->Table('boqii_user_pet pet,boqii_users user')->field('pet.id,pet.petname,pet.petgender,pet.lineages,pet.petbday,pet.picpath,pet.pettype,pet.is_default,pet.cretime,pet.petstatus,pet.race,pet.family,user.nickname,user.uid')->where($where)->order('id desc')->limit($limit)->page($page)->select();

		foreach($list as $key => $val) {
			$list[$key]['race_name'] = M()->Table('bk_entry_cat')->where('id='.$val['race'])->getField('name');
			$list[$key]['family_name'] = M()->Table('bk_pet_detail')->where('id='.$val['family'])->getField('name');
			$list[$key]['petgender_name'] = $val['petgender'] == 1 ? '公' : ($val['petgender'] == 2 ? '母' : '');
			$list[$key]['lineages_name'] = $val['lineages'] == 1 ? '纯种' : ($val['lineages'] == 2 ? '混血' : '');
		}

		return $list;
	}
	
	/**
	 * 个数
	 */
	public function hasPetCount($where){
		$result = $this->table('boqii_user_pet pet,boqii_users user')->where($where)->count();
		return $result;
	}

	/** 
	 * 获取宠物种类名称
	 */
	public function getPetName ($id) {
	    $strPetClass = M("boqii_pet_class") ->where(array('id'=>$id)) -> getField('title');
		return  $strPetClass;
	}

	/** 
	 * 获取宠物种族名称
	 */
	public function getPetRaceName ($id) {
	    $raceName = M("bk_entry_cat") ->where(array('id'=>$id)) -> getField('name');

		return  $raceName;
	}

	/** 
	 * 获取宠物家族名称
	 */
	public function getPetFamilyName ($id) {
	    $familyName = M("bk_pet_detail") ->where(array('id'=>$id)) -> getField('name');

		return  $familyName;
	}
}
?>
<?php
/**
 * UcAdmin Model类
 */
class UcAdminModel extends RelationModel{
	
	protected $tableName='uc_admin';

	/*
	*获得管理用户
	*/
	public function hasManyAdmin($page,$limit,$where){
		$result = $this->table()->where($where)->order('id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	/*
	*获取管理用户个数
	*/
	public function hasAdminCount($where){
		$result = $this->table()->where($where)->count();
		return $result;
	}

	/**
	 * 根据管理员名获取串接管理员id
	 *
	 * @param $username string 管理员名
	 *
	 * @return 串接管理员id
	 */
	public function getStrUidsByUsername($username) {
		//模糊匹配查询管理员
		$where =" status = 0 AND username like '%".$username ."%' ";
		$uids = $this->where($where)->getField('id', true);

		//串接管理员id
		$strUids='';
		if($uids) {
			$strUids = implode(',', $uids);
		}

		return $strUids;
	}

	/**
	 * 根据管理员id获取管理员信息
	 *
	 * @param $id int 管理员id
	 *
	 * @return array 管理员信息
	 */
	public function getAdminInfoById($id) {
		//管理员信息
		$adminInfo = $this->where('id='.$id.' AND status=0')->field('id,username,truename,status')->find();
		
		return $adminInfo;
	}

}

?>
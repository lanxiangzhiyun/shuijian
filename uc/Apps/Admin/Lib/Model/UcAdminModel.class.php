<?php
/**
 * UcAdmin Model��
 */
class UcAdminModel extends RelationModel{
	
	protected $tableName='uc_admin';

	/*
	*��ù����û�
	*/
	public function hasManyAdmin($page,$limit,$where){
		$result = $this->table()->where($where)->order('id desc')->limit($limit)->page($page)->select();
		return $result;
	}
	/*
	*��ȡ�����û�����
	*/
	public function hasAdminCount($where){
		$result = $this->table()->where($where)->count();
		return $result;
	}

	/**
	 * ���ݹ���Ա����ȡ���ӹ���Աid
	 *
	 * @param $username string ����Ա��
	 *
	 * @return ���ӹ���Աid
	 */
	public function getStrUidsByUsername($username) {
		//ģ��ƥ���ѯ����Ա
		$where =" status = 0 AND username like '%".$username ."%' ";
		$uids = $this->where($where)->getField('id', true);

		//���ӹ���Աid
		$strUids='';
		if($uids) {
			$strUids = implode(',', $uids);
		}

		return $strUids;
	}

	/**
	 * ���ݹ���Աid��ȡ����Ա��Ϣ
	 *
	 * @param $id int ����Աid
	 *
	 * @return array ����Ա��Ϣ
	 */
	public function getAdminInfoById($id) {
		//����Ա��Ϣ
		$adminInfo = $this->where('id='.$id.' AND status=0')->field('id,username,truename,status')->find();
		
		return $adminInfo;
	}

}

?>
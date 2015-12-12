<?php
/**
 * BoqiiSensitiveWord Model类
 */
class BoqiiSensitiveWordModel extends RelationModel{
	
	protected $tableName='boqii_sensitive_word';
	
	/**
	 * 获得关键词
	 */
	public function hasManyWord($page,$limit,$where,$order){
		$list = $this->table('boqii_sensitive_word word')->field('word.id,word.keyword,word.appeartimes,word.uid,word.createtime')->order($order)->where($where)->limit($limit)->page($page)->select();

		$adminModel = D('UcAdmin');
		foreach($list as $key => $val) {
			$admininfo = $adminModel->getAdminInfoById($val['uid']);
			$list[$key]['username'] = $admininfo['username'];
			$list[$key]['truename'] = $admininfo['truename'];
					$adminModel = D('UcAdmin');
		}
		return $list;
	}
	/**
	 * 获得关键词个数
	 */
	public function hasWordCount($where){
		$result = $this->table('boqii_sensitive_word word')->where($where)->count();
		return $result;
	}

	/**
	 * 导入关键词
	 */
	public function addSensitiveList($param){
		foreach ($param as $k => $val) {
			$data[$k]['keyword'] 	= trim($val[0]);
			$data[$k]['uid'] 		= session('boqiiUserId');
			$data[$k]['createtime'] = time();
		}
		$this->addAll($data);
	}
}	
?>
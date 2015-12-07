<?php
/**
 * UcPush Model类
 */
class UcPushModel extends RelationModel{
	
	protected $tableName='uc_pushes';
	

	public $_link = array(           
			'UcUser'=>array(
				 'mapping_type'=>BELONGS_TO,                   
				 'class_name'=>'UcUser',
				 'mapping_name'=>'boqii_users', 
				 'foreign_key'=>'uid'
			),
			'BbsThread'=>array(
				 'mapping_type'=>HAS_ONE,                   
				 'class_name'=>'BbsThread',
				 'mapping_name'=>'bbs_threads', 
				 'foreign_key'=>'tid'
			)
	);

	/*
	*关联用户
	*/
	public function hasUserAndPush($page,$limit){
		$result = $this->where('type=1 and valid=1')->relation(true)->order('sort desc,id desc')->page($page)->limit($limit)->select();
		return $result;
	}

	/*
	*获取热门个数
	*/
	public function hasPushCount(){
		$result = $this->where('type=1 and valid=1')->count();
		return $result;
	}
	
	/*
	*获取详情
	*/
	public function getPushInfo($param){
		$result = $this->where($param)->find();
		return $result;
	}
	
	/*
	*保存修改数据
	*/
	public function savePushInfo($param){

		if($param['type']==3){
			$param['subject'] = $param['content'];
		}

		if($param['id']){
			$this->save($param);
		}else{
			$this->add($param);
		}
	}

	/*
	*获得公告列表
	*/
	public function getList($page,$limit,$check){
		if($check==3){
			$where = "type=3 and valid=1";
			$result = $this->where($where)->page($page)->order('id desc')->limit($limit)->select();
		}else{
			$where = 'push.uid=user.uid and push.type=2 and push.valid=1';
			$result = $this->table('uc_pushes push,boqii_users user')->where($where)->field('push.id,push.subject,push.content,push.linkurl,push.postdate,push.uid,user.username')->page($page)->order('push.id desc')->limit($limit)->select();
		}
		return $result;
	}

	/*
	*获得公告数
	*/
	public function getCount($check){
		if($check==3){
			$where = "type=3 and valid=1";
			$result = $this->where($where)->count();
		}else{
			$where = 'push.uid=user.uid and push.type=2 and push.valid=1';
			$result = $this->table('uc_pushes push,boqii_users user')->where($where)->count();
		}
		
		return $result;
	}
}
?>
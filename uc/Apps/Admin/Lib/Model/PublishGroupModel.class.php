<?php
/*
*栏目组 model
*/
class PublishGroupModel extends Model{

	protected $tableName='boqii_publish_group';
	
	/*
	*获得栏目
	*/
	public function getPublishGroup($page,$limit){
		$result = $this->where('id<>4')->page($page)->limit($limit)->select();	
		return $result;
	}

	/*
	*获取栏目个数
	*/
	public function getPublishCount(){
		
	}

	/**
	*获取栏目组信息
	*/
	public function getPublishGroupInfo($pgid){
		$result = $this->where('id='.$pgid)->find();	
		return $result;
	}
}
?>
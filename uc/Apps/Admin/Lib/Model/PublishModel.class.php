<?php
/*
*栏目 model
*/
class PublishModel extends Model{

	protected $tableName='boqii_publish';
	
	/*
	*获得栏目
	*/
	public function getPublish($page,$limit,$where){
		$result = $this->where($where)->page($page)->limit($limit)->select();
		return $result;
	}

	/*
	*获取栏目个数
	*/
	public function getPublishGroupCount(){
		
	}
	
	/*
	*预发布
	*/
	public function getWillPublishCount($where){
		$where['status']=0;
		$result = D('PublishArticle')->where($where)->group('publish_id')->field('publish_id,count(*) as num')->select();
		return $result;
	}
	/*
	*已发布
	*/
	public function getHadPublishCount($where){
		$where['status']=1;
		$result = D('PublishArticle')->where($where)->group('publish_id')->field('publish_id,count(*) as num')->select();
		return $result;
	}
	/**
	*获取栏目信息
	*/
	public function getPublishInfo($pid){
		$result = $this->where('id='.$pid)->find();	
		return $result;
	}

}
?>
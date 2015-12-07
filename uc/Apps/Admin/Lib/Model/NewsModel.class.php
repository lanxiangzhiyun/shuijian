<?php 

/**
 * 资讯管理Model
 * @author: Seven
 * @Created: 14-7-7
 */
class NewsModel extends Model{
	// 数据库表定义
	protected $trueTableName = 'news_column';
	
	/**
	 * 根据where条件返回资讯数量
	 * @param unknown $where
	 */
	public function getNewsCount($where){
		
		if($where){
			$result = M("news_information")->where($where)->count();
			return $result;	
		}
		return false;
	}
	
	/**
	 * 根据条件获取对应的资讯列表信息
	 * @param unknown $where
	 * @param unknown $page
	 * @param unknown $limit
	 * 			$tagId int 资讯标签id (modifier JasonJiang)
	 */
	public function getNewsList($where,$page,$limit,$tagId){
		
		$result = M("news_information")->where($where)->field("id,title,create_time,big_column_id,column_id,three_column_id,status,total_visits_number,really_visits_number")->order('id desc')->limit($limit)->page($page)->select();
		// 判断资讯标签id与该文章是否关联
		if ($tagId) {
			foreach ($result as $key => $val) {
				$isRelation = M('news_information_tag')->where('information_id = '.$val['id'].' and status = 0 and tag_id = '.$tagId)->getField('id');
				$result[$key]['isRelation'] = $isRelation?'已关联':'未关联';
			}
		}
		// echo "<pre>";print_r($result);
		return $result;
		
	}
	
	/**
	 * 获取所有栏目
	 */
	public function getAllColumn(){
		
		$result = M("news_column")->field("id,name")->select();
		$columnArray = array();
		foreach ($result as $key => $val){
			$columnArray[$val['id']] = $val['name'];
		}
		return $columnArray;
	}
	
	/**
	 * 返回所以大栏目
	 */
	public function getBigColumn(){
		
		$result = M("news_column")->where("parent_id = 0")->select();
		return $result;
	}
	
	/**
	 * 根据栏目ID获取子栏目
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	public function getColumnInfoById($id){
		if(!$id) {
			return array();
		}

		$result = M("news_column")->where("parent_id = {$id} ")->select();
		return $result;
	}
}

?>
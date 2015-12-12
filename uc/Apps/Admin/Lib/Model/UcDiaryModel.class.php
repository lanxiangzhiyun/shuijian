<?php
/**
 * UcDiary Model类
 */
class UcDiaryModel extends RelationModel{
	
	protected $tableName='uc_diary';
	

	/*
	*日志和用户关联查询
	*/
	public function hasUserAndDiary($page,$limit,$where){
		$result = $this->table('uc_diary diary,boqii_users user')->field('diary.id,diary.title,diary.cretime,diary.views,diary.comments,diary.cretime,diary.uid,user.nickname')->where($where)->order('id desc')->limit($limit)->page($page)->select();

		return $result;
	}
	
	/*
	*获取日志个数
	*/
	public function hasDiaryCount($where){
		$result = $this->table('uc_diary diary,boqii_users user')->where($where)->count();
		return $result;
	}

	/*
	*日志内容 $photo_id图片ID $diary_id 日志ID
	*/
	public function deleteDiaryPhoto($photo_id,$diary_id){
		$diary = $this->where(array('id'=>$diary_id))->select();
		preg_match_all("/<img.*>/U", $diary[0]['content'],$matches);//带引号
		$new_arr=array_unique($matches[0]);//去除数组中重复的值 
		//整理成一个一维数组
		foreach($new_arr as $key){ 
			$arr[]=$key; 	
		}
		foreach($arr as $key=>$val){
			$intLastPosition = strripos($val,'pid="'.$photo_id.'"');
			if($intLastPosition){
				$k = $key;
			}
		}
		$content = str_replace($arr[$k],'',$diary[0]['content']);
		$this->where(array('id'=>$diary_id))->save(array('content'=>$content));
	}
}
?>
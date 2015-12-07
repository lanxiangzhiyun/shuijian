<?php
/**
 * 敏感词Model类
 *
 * @created 2013-01-04
 * @author vic
 */
class SensitiveWordModel extends Model{
    protected $trueTableName = 'boqii_sensitive_word';
	/**
	 * 判断content内容是否包含敏感词
	 *
	 * @param $content string 要判断是否包括敏感词的内容
	 * @return bool 返回结果，true为有敏感词，false为没有
	 */
	public function isOrNotSensitiveWord($content){
		$words = $this->field('keyword,status')->select();
		$flag = false;
		// // 出去内容含有的其他特殊字符
		// $specialArr = array(',','.','?');
		// $newcontent = str_replace($specialArr, '', $content);
		foreach($words as $key=>$val){
			if($val['status']!=-1 && strpos($content,$val['keyword'])!==false){
				$flag = true;
				break;
			}
		}
		return $flag;
	}
}

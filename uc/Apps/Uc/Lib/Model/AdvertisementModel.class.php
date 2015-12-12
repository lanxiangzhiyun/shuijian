<?php
/**
 * 广告Model类
 *
 * @created 2012-03-22
 * @author zzy
 */
class AdvertisementModel extends Model{
	protected $tableName='uc_advertisement';

	/**
	 * 获得广告
	 * 
	 * @param  $code 广告位编号
	 * @return array() 广告信息
	 */
	public function getAdvertisement($code) {
		if (!is_numeric($code)) {
			return array();
		}
		$result = $this->where(array('code'=>$code,'status'=>0))->field('title,pic_path,linkpath')->order('id desc')->find();
		if ($result) {
			$result['pic_path'] = C('IMG_DIR') . '/' . $result['pic_path'];
			return $result;
		} 
		return array();
	} 
}
?>
<?php
/**
 * UcAlbum Model类
 */
class UcAlbumModel extends RelationModel{
	
	protected $tableName='uc_album';
	
//	public $_link = array(
//		"UcUser"=>array(
//			'mapping_type'=>BELONGS_TO,
//			'class_name'=>'UcUser',
//			'foreign_key'=>'uid',
//			'mapping_name'=>'boqii_users'
//		)		
//	);

	/*
	*专辑和用户关联查询
	*/
	public function hasUserAndAlbum($page,$limit,$where){
		$result = $this->table('uc_album album,boqii_users user')->field('album.id,album.title,album.content,album.dateline,user.nickname')->order('id desc')->where($where)->limit($limit)->page($page)->select();
		return $result;
	}
	/*
	*获取专辑个数
	*/
	public function hasAlbumCount($where){
		$result = $this->table('uc_album album,boqii_users user')->where($where)->count();
		return $result;
	}

	/**
     * 增加、减去相册容量
     * @param $param uid 用户id changeNum  改变的数量
     * @param int $flag  默认1-- 1（增加）  2（减少）
     */
    public function changeAlbumCapacity($param, $flag = 1)
    {
        $data['uid'] = $param['uid'];
        $intChangeNum = intval($param['changeNum']);
        $intMphotoSize = M('boqii_users_extend')->where($data)->getField('photosize'); //查询当前 容量
        $intCompLoseSize = $intMphotoSize - $intChangeNum; //查询 减少容量 比较后的容量是否 <0
        $intCompCreateSize = $this->getBoolIsUpload($data['uid']); //查询上传图片总大小是否 大于 1G
        if ($flag == 2) {
            if ($intCompLoseSize >= 0) {
                M('boqii_users_extend')->where($data)->setDec('photosize', $intChangeNum); //减少 容量
            } else if ($intCompLoseSize < 0) { //小于 0
                M('boqii_users_extend')->where($data)->setField('photosize', 0); //小于0 则把 相册当前容量 置为 0
            } else {
                //do nothing
            }
        } else {
            if ($intMphotoSize < 0) { //当前 容量小于 0
                M('boqii_users_extend')->where($data)->setField('photosize', 0); //小于0 则把 相册当前容量 置为 0
                M('boqii_users_extend')->where($data)->setInc('photosize', $intChangeNum); //增加 容量
            } else if ($intCompCreateSize) { //不大于 1G
                M('boqii_users_extend')->where($data)->setInc('photosize', $intChangeNum); //增加 容量
            } else {
                // do nothing
            }
        }
    }
	/**
     * 查询上传图片总大小是否 大于 1G
     * @param $uid
     */
    public function getBoolIsUpload($uid)
    {
        $G = 1024 * 1024 * 1024;
        $intMphotoSize = $this->getAlbumCapacity($uid);
        $bool = $intMphotoSize >= $G ? false : true;
        return $bool;
    }
	/**
     * 查询当前容量
     * @param $uid
     * @return mixed
     */
    public function getAlbumCapacity($uid)
    {
        $intMphotoSize = M('boqii_users_extend')->where("uid = '$uid'")->getField('photosize'); //查询当前 容量
        return $intMphotoSize;
    }
}

?>
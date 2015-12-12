<?php
/**
 * 相册Model类
 *
 * User: zlg
 * Date: 12-10-31
 */
class UcAlbumModel extends Model
{

    protected $trueTableName = 'uc_album';
    private $statusOk; //album正常
    private $statusDel; //album删除
    private $auditOk; //审核通过
    private $auditDel;
    private $auditIng;
    private $auditDie;
    private $album;
    private $photo;
    private $comment;
    private $ucPet;
    private $photoBigWidth; //最大宽高 160*160
    private $photoBigHeight; //最大宽高 160*160
    private $photoMiddenWidth; //宽高 78*78
    private $photoMiddenHeight; //宽高 78*78

    protected function _initialize()
    {
        $this->statusOk = 0;
        $this->statusDel = 1;
        $this->auditOk = 1;
        $this->auditDel = -1;
        $this->auditIng = 0;
        $this->auditDie = -2;
        $this->photoBigWidth = 160;
        $this->photoBigHeight = 160;
        $this->photoMiddenWidth = 78;
        $this->photoMiddenHeight = 78;
        $this->album = 'uc_album';
        $this->photo = 'uc_photo';
        $this->comment = 'uc_photo_comment';
        $this->ucPet = 'uc_user_pet';
    }

    /**
     * 添加相册信息
     * @param $uid
     * @param $title
     * @param $decription
     * @param int $petId  关联宠物的id
     * @return mixed  成功返回主键值，失败返回false
     */
    public function addAlbumInfo($param)
    {
        $data['uid'] = $param['uid'];
        $data['title'] = $param['title'];
        $data['content'] = $param['decription'];
        $data['pet_id'] = intval($param['petId']);
        $data['dateline'] = time();
        $intId = M($this->album)->add($data);
        return $intId;
    }

    /**
     * 修改相册信息
     * @param $id   相册 id
     * @param $title
     * @param $decription
     * @param $petId  关联宠物 id
     * @return mixed  更新成功返回影响的记录数 失败返回false
     */
    public function updateAlbumInfo($param)
    {
        $status = $this->getBoolDefaultByAid($param['id']); //是否是默认相册
        if ($status) return 'false';
        $data['id'] = $param['id'];
        $data['title'] = $param['title'];
        $data['content'] = $param['decription'];
        $data['pet_id'] = $param['petId'];
        $data['lasttime'] = time();
        $boolStatus = M($this->album)->save($data);
        $status = is_numeric($boolStatus) ? 'true' : 'false';
        return $status;
    }

    /**
     * 删除相册信息
     * @param $param  aid/uid  相册 id  /用户 id
     * @return bool
     */
    public function deleteAlbumInfo($param)
    {
        //查询相片数组 批量删除  删除照片评论
        $status = $this->getBoolDefaultByAid($param['aid']);
        if ($status) return false;
        $data['id'] = $param['aid'];
        $data['status'] = $this->statusDel;
        $data['lasttime'] = time();
        $boolStatus = M($this->album)->save($data);
        $arrParam['id'] = $param['aid'];
        $arrParam['uid'] = $param['uid'];
        $this->deletePhotoInfo($arrParam, $this->statusDel); //删除照片(包括评论、相册容量总数)
        return is_numeric($boolStatus) ? true : false;
    }

    /**
	 * 取得用户全部相册列表
	 * @param $param  分页和用户uid
	 * @return array
	 */
    public function getUserAlbumList($param)
    {
        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 8; //$param['num'] 自定义 显示条数
        $page_start = ($page - 1) * $page_num;

        $condition['uid'] = $param['uid'];
        $condition['status'] = $this->statusOk;

        $this->total = M()->Table('uc_album album')->where($condition)->count();
        $arrAlbum = M()->Table('uc_album album')->where($condition)->order('dateline desc')->limit($page_start . ',' . $page_num)->field('title,pet_id,uid,id,content,default')->select();
        foreach ($arrAlbum as $ak => $val) {
            $strPetName = '';
            $data['id'] = $val['pet_id']; //宠物id
            $data['uid'] = $val['uid'];
            $alb['album_id'] = $val['id']; //相册 id
            $alb['uid'] = $val['uid'];
            $photoCnt = $this->getCntPhoto($alb); //求出照片 数量
            $arrAlbum[$ak]['photoCnt'] = $photoCnt;
            if ($data['id']) {
                $strPetName = D('UcPets')->getBoolRelation($data);
            }
            if (!$strPetName) {
                $arrAlbum[$ak]['pet_id'] = '';
            }
            //宠物名称
            $arrAlbum[$ak]['petname'] = '' . $strPetName;
            $arrNomalPhoto = $this->getNormalPhoto($alb);
            if ($photoCnt != 0) {
                foreach ($arrNomalPhoto as $pk => $subval) {
                    $path = $subval['photo_path']; //照片路径
                    $intLastPosition = strripos("$path", "_y");
                    if ($intLastPosition) { //gif bmp 格式 不存在 _y 的后缀。其他格式存在则替换成页面的大小比例缩略图
                        if ($pk == 0) {
                            $strSuffix = "_b"; //封面
                            list($width, $height) = getallsizebymin($subval['imagewidth'], $subval['imagehigth'], $this->photoBigWidth, $this->photoBigHeight); //宽高处理
                        } else {
                            $strSuffix = "_m";
                            list($width, $height) = getallsizebymin($subval['imagewidth'], $subval['imagehigth'], $this->photoMiddenWidth, $this->photoMiddenHeight); //宽高处理
                        }
                        $arrNomalPhoto[$pk]['width'] = $width;
                        $arrNomalPhoto[$pk]['height'] = $height;
                        $arrNomalPhoto[$pk]['photo_path'] = C('IMG_DIR').'/'.substr_replace("$path", "$strSuffix", $intLastPosition, 2);
                    } else {
                        $arrNomalPhoto[$pk]['width'] = $subval['imagewidth'];
                        $arrNomalPhoto[$pk]['height'] = $subval['imagehigth'];
						$arrNomalPhoto[$pk]['photo_path'] = C('IMG_DIR').'/'.$subval['photo_path'];
                    }
                }
            }

            $intDefaultPhoto = (5 - $photoCnt);
            for ($i = 0; $i < $intDefaultPhoto; $i++) {
                $arrNomalPhoto[] = array(
                    'photo_path' => '/Public/Images/nopic80.gif',
                    'width' => $this->photoMiddenWidth,
                    'height' => $this->photoMiddenHeight
                );
            }
            if ($photoCnt == 0) {
                $arrNomalPhoto[0]['photo_path'] = '/Public/Images/nopic160.gif';
                $arrNomalPhoto[0]['width'] = $this->photoBigWidth;
                $arrNomalPhoto[0]['height'] = $this->photoBigHeight;
            }
            $arrAlbum[$ak]['photo'] = $arrNomalPhoto;
        }
        return empty($arrAlbum) ? array() : $arrAlbum;
    }

    /**
	 * 获取宠物类别相册 及对应的照片数量
	 * @param $param
	 * @return mixed
	 */
    public function getPetCategoryMsg($param)
    {
        $uid = $param['uid'];
        $arrUserPetMsg = D('UcPets')->getUserPetMsg($param); //获取 用户所有的 宠物
        foreach ($arrUserPetMsg as $ak => $val) { //获取相同 宠物的 不同相册 照片 数量
            $arrPetPhotocnt = $this->getPhotoCntByPetId($uid, $val['pid']);
            $arrUserPetMsg[$ak]['photoCnt'] = count($arrPetPhotocnt);
        }
        return $arrUserPetMsg;
    }

    /**
	 * 对应宠物的 相册 照片id 数组
	 * @param $uid
	 * @param $petId
	 * @return array|mixed
	 */
    public function getPhotoCntByPetId($uid, $petId)
    {
        $arrAlbumId = M()->Table('uc_album')->where(array('uid'=>$uid,'pet_id'=>$petId,'status'=>$this->statusOk))->getField('id',true);
        $strAlbumId = implode(',',$arrAlbumId);
        $arrPetPhotocnt =array() ;
        if(!empty($strAlbumId)){
            $sql = "SELECT photo_id FROM  uc_photo
                WHERE uid= $uid AND status >= $this->statusOk
                AND album_id IN ($strAlbumId)";
                $arrPetPhotocnt = M()->query($sql);
        }
        return $arrPetPhotocnt;
    }

    /**
	 * 获取 用户相册关联的宠物列表 ---暂时不删
	 * @param $uid
	 * @return array|mixed
	 */
    public function getUserRelationPetId($uid)
    {
        $sql = "SELECT id AS pid,petname FROM `uc_user_pet` WHERE valid = 1 AND  id IN (SELECT DISTINCT pet_id  FROM `uc_album`
        WHERE uid=$uid AND status=$this->statusOk AND pet_id != $this->statusOk)";
        $arrPetMsg = M()->query($sql);
        return $arrPetMsg;
    }

    /**
     * 取得指定用户宠物下的所有相册信息
     * @param $petId   宠物id
     * @param $uid
     * @return array
     */
    public function getUserPetAlbumList($param)
    {
        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 8; //$param['num'] 自定义 显示条数
        $page_start = ($page - 1) * $page_num;

        $condition['uid'] = $param['uid'];
        $condition['status'] = $this->statusOk;
        $condition['pet_id'] = intval($param['petId']);

        $this->total = M()->Table('uc_album album')->where($condition)->count();
        $arrAlbum = M()->Table('uc_album album')->where($condition)->order('dateline desc')->limit($page_start . ',' . $page_num)->field('title,pet_id,uid,id,content,default')->select();
        foreach ($arrAlbum as $ak => $val) {
            $strPetName = '';
            $data['id'] = $val['pet_id']; //宠物id
            $data['uid'] = $val['uid'];
            $alb['album_id'] = $val['id']; //相册 id
            $alb['uid'] = $val['uid'];
            $photoCnt = $this->getCntPhoto($alb); //求出照片 数量
            $arrAlbum[$ak]['photoCnt'] = $photoCnt;
            if ($data['id']) {
                $strPetName = D('UcPets')->getBoolRelation($data);
            }
            if (!$strPetName) {
                $arrAlbum[$ak]['pet_id'] = '';
            }
            //宠物名称
            $arrAlbum[$ak]['petname'] = '' . $strPetName;
            $arrAlbum = M()->Table('uc_album album')->where($condition)->order('dateline desc')->limit($page_start . ',' . $page_num)->field('title,pet_id,uid,id,content,default')->select();
            foreach ($arrAlbum as $ak => $val) {
                $strPetName = '';
                $data['id'] = $val['pet_id']; //宠物id
                $data['uid'] = $val['uid'];
                $alb['album_id'] = $val['id']; //相册 id
                $alb['uid'] = $val['uid'];
                $photoCnt = $this->getCntPhoto($alb); //求出照片 数量
                $arrAlbum[$ak]['photoCnt'] = $photoCnt;
                if ($data['id']) {
                    $strPetName = D('UcPets')->getBoolRelation($data);
                }
                if (!$strPetName) {
                    $arrAlbum[$ak]['pet_id'] = '';
                }
                //宠物名称
                $arrAlbum[$ak]['petname'] = '' . $strPetName;
                $arrNomalPhoto = $this->getNormalPhoto($alb);
                if ($photoCnt != 0) {
                    foreach ($arrNomalPhoto as $pk => $subval) {
                        $path = $subval['photo_path']; //照片路径
                        $intLastPosition = strripos("$path", "_y");
                        if ($intLastPosition) { //gif 格式 不存在 _y 的后缀。其他格式存在则替换成页面的大小比例缩略图
                            if ($pk == 0) {
                                $strSuffix = "_b"; //封面
                                list($width, $height) = getallsizebymin($subval['imagewidth'], $subval['imagehigth'], $this->photoBigWidth, $this->photoBigHeight); //宽高处理
                            } else {
                                $strSuffix = "_m";
                                list($width, $height) = getallsizebymin($subval['imagewidth'], $subval['imagehigth'], $this->photoMiddenWidth, $this->photoMiddenHeight); //宽高处理
                            }
                            $arrNomalPhoto[$pk]['width'] = $width;
                            $arrNomalPhoto[$pk]['height'] = $height;
                            $arrNomalPhoto[$pk]['photo_path'] = C('IMG_DIR').'/'.substr_replace("$path", "$strSuffix", $intLastPosition, 2);
                        } else {
                            $arrNomalPhoto[$pk]['width'] = $subval['imagewidth'];
                            $arrNomalPhoto[$pk]['height'] = $subval['imagehigth'];
							$arrNomalPhoto[$pk]['photo_path'] =  C('IMG_DIR').'/'.$subval['photo_path'];
                        }
                    }
                }

                $intDefaultPhoto = (5 - $photoCnt);
                for ($i = 0; $i < $intDefaultPhoto; $i++) {
                    $arrNomalPhoto[] = array(
                        'photo_path' => '/Public/Images/nopic80.gif',
                        'width' => $this->photoMiddenWidth,
                        'height' => $this->photoMiddenHeight
                    );
                }
                if ($photoCnt == 0) {
                    $arrNomalPhoto[0]['photo_path'] = '/Public/Images/nopic160.gif';
                    $arrNomalPhoto[0]['width'] = $this->photoBigWidth;
                    $arrNomalPhoto[0]['height'] = $this->photoBigHeight;
                }
                $arrAlbum[$ak]['photo'] = $arrNomalPhoto;
            }
        }
        return empty($arrAlbum) ? array() : $arrAlbum;
    }

    /**
     * 取得某相册信息
     * @param $id    相册 id
     * @return array   一维数组 array
     */
    public function getAlbumInfo($id)
    {
        $condition['id'] = $id;
        $condition['status'] = $this->statusOk;
        $arrAlbum = M($this->album)->where($condition)->find();
        return empty($arrAlbum) ? array() : $arrAlbum;
    }

    /**
     * 添加照片信息
     * @param $albumMsg 照片等信息 二维数组
     * @return mixed
     */
    public function addPhotoInfo($albumMsg)
    {
        $fidSingle = $this->statusOk;
        $fidAll = $this->statusDel;
        $arrPid = array(); //照片id
        $param['changeNum'] = 0;
		$apiModel = D("Api");
        foreach ($albumMsg as $val) {
			//用户照片数量 总数 +1
			$apiModel -> userExtendHandle('photo_num',$val['uid'],'inc');
            $data['album_id'] = $val['album_id'];
            $data['uid'] = $param['uid'] = $val['uid'];
            $data['imagewidth'] = $val['imagewidth'];
            $data['imagehigth'] = $val['imagehigth'];
            $data['size'] = $val['size'];
            $data['photo_path'] = $val['photo_path'];
            $data['photo_name'] = $val['photo_name'];
            $data['photo_desc'] = $val['photo_desc'];
            $data['is_cover'] = $val['is_cover'];
            $data['cretime'] = $val['cretime'];
            if ($val['is_cover'] == $fidAll) {
				M($this->photo)->where(array('album_id' => $val['album_id'],'is_cover' => $fidAll))->setField('is_cover', $fidSingle); //更新old 封面
            }
            $boolStatus = M($this->photo)->add($data);
            $arrPid[] = $boolStatus;
            // 改变相册容量--start
            $result = $val['size'];
            $param['changeNum'] += $result; //btype
        }
        $data = array(
            'pid' => $arrPid,
            'status' => $boolStatus
        );
        if ($param['changeNum'] != 0) {
            $this->changeAlbumCapacity($param, 1); //改变相册容量
        }
        // 改变相册容量--end

        return $data;
    }

    /**
     * 修改照片信息
     * @param $photoId  照片 id
     * @param $photoMsg 修改信息
     * @return mixed
     */
    public function updatePhotoInfo($photoId, $photoMsg)
    {
        $boolStatus = M($this->photo)->where("photo_id= $photoId  and status >= " . $this->auditIng)->save($photoMsg);
        return $boolStatus;
    }

    /**
     * 批量修改照片信息
     * @param $arrPhotoMsg
     * @return mixed
     */
    public function batchUpdatePhotos($arrPhotoMsg)
    {
        foreach ($arrPhotoMsg as $val) {
            $photoMsg['photo_name'] = $val['pname'];
            $photoMsg['photo_desc'] = $val['pdec'];
            $boolStatus = $this->updatePhotoInfo($val['photoId'], $photoMsg);
        }
        return is_numeric($boolStatus) ? true : false;
    }

    /**
	 * 修改照片描述
	 * @param $param
	 * @return bool
	 */
    public function updatePhotoDesc($param)
    {
        $photoId = $param['pid'];
        $intStatus = M($this->photo)->where("photo_id='$photoId'")->setField('photo_desc', $param['dec']);
        return $intStatus;
    }

    /**
	 * 修改照片名称
	 * @param $param
	 * @return bool
	 */
    public function updatePhotoName($param)
    {
        $photoId = $param['pid'];
        $intStatus = M($this->photo)->where("photo_id='$photoId'")->setField('photo_name', $param['name']);
        return $intStatus;
    }

    /**
     * 设定照片为相册封面  更新照片不适用新增
     * @param $photoId   照片 id
     * @return mixed
     */
    public function setPhotoCover($photoId)
    {
        $coverPhotoId = M($this->photo)->where('is_cover=' . $this->statusDel . ' and status >=' . $this->statusOk)->getField('photo_id'); //封面 id
        if ($coverPhotoId) {
            $dataA = array('is_cover' => $this->statusOk, 'updatetime' => time());
            M($this->photo)->where('photo_id=' . $coverPhotoId)->setField($dataA); //更新old 封面
        }
        $dataB = array('is_cover' => $this->statusDel, 'updatetime' => time());
        $boolStatus = M($this->photo)->where('photo_id=' . $photoId)->setField($dataB); //更新新的 封面
        return is_numeric($boolStatus) ? true : false;
    }

    /**
     * 更新照片浏览数
     * @param $photoId   照片  id
     */
    public function updatePhotoViews($photoId)
    {
        M($this->photo)->where("photo_id='$photoId'")->setInc('views'); // 用户的浏览数加1
    }

    /**
     * 删除照片信息
     * @param $arrParam['id']   照片id /相册 id
     * @param $arrParam['uid']   用户 uid
     * @param int $fid   0:删除单张 1：批量删除
     * @return mixed
     */
    public function deletePhotoInfo($arrParam, $fid = 0)
    {
        $fidSingle = $this->statusOk;
        $fidAll = $this->statusDel;
        $param['uid'] = $arrParam['uid'];
		$apiModel = D('Api');
        if ($fid == $fidSingle) {
			//用户照片数量 总数 -1
			$apiModel -> userExtendHandle('photo_num',$arrParam['uid'],'dec');
            $this->deleteCommentByPid($arrParam['id']); //删除照片评论
            //照片来源
            $arrPhotoType = M()->Table('uc_photo')->where(array('photo_id' => $arrParam['id']))->field('object_type,object_id')->find();
            //删除日记内容照片
            if ($arrPhotoType['object_type'] == 1) {
                D('UcDiary')->deleteDiaryPhoto($arrParam['id'], $arrPhotoType['object_id']);

            }
            // 改变相册容量--start
            $strPhotoSize = $this->getPhotoSize($arrParam['id']); //id-照片id、获取 照片 大小
            $param['changeNum'] = $strPhotoSize; //btype
            $this->changeAlbumCapacity($param, 2); //改变相册容量
            // 改变相册容量--end
            $condition['photo_id'] = $arrParam['id']; //删除单张
        }
        else if ($fid == $fidAll) {
            $arrPhotoIdSize = M($this->photo)->where('album_id=' . $arrParam['id'])->field('photo_id,size,object_type,object_id')->select(); //取得照片id
            $param['changeNum'] = 0;
            foreach ($arrPhotoIdSize as $key=>$val) {
				//用户照片数量 总数 -1
				$apiModel -> userExtendHandle('photo_num',$arrParam['uid'],'dec');
                $this->deleteCommentByPid($val['photo_id']); //删除照片评论
                //删除日记内容照片
                if ($val['object_type'] == 1) {
                    D('UcDiary')->deleteDiaryPhoto($val['photo_id'], $val['object_id']);

                }
                // 改变相册容量--start
                $param['changeNum'] += $val['size']; //btype
            }
            if ($param['changeNum'] != 0) {
                $this->changeAlbumCapacity($param, 2); //改变相册容量
            }
            // 改变相册容量--end
            $condition['album_id'] = $arrParam['id']; //批量删除
            $condition['status'] != $this->auditDel;
        }
        else {
            return false;
        }
        $data['status'] = $this->auditDel;
        $data['updatetime'] = time();
        $BoolStatus = M($this->photo)->where($condition)->save($data);
        return is_numeric($BoolStatus) ? true : false;
    }

    /**
	 * 删除 照片 批量操作
	 * @param $arrPhotoId
	 * @param $param
	 * @return bool
	 */
    public function delPhoto($arrPhotoId, $param)
    {
        $arrParam['uid'] = $param['uid'];
        $arrParam['changeNum'] = 0;
		$apiModel = D('Api');
        foreach ($arrPhotoId as $val) {
            $condition['photo_id'] = $val;
            $condition['status'] = array('egt', $this->auditIng);
            $data['status'] = $this->auditDel;
            $data['updatetime'] = time();
			//用户照片数量 总数 -1
			$apiModel -> userExtendHandle('photo_num',$param['uid'],'dec');
            $BoolStatus = M($this->photo)->where($condition)->save($data);
            $this->deleteCommentByPid($val); //删除照片评论
            // 改变相册容量--start
            //照片来源
            $arrPhotoType = M()->Table('uc_photo')->where(array('photo_id' => $val))->field('object_type,object_id')->find();
            //删除日记内容照片
            if ($arrPhotoType['object_type'] == 1) {
                D('UcDiary')->deleteDiaryPhoto($val, $arrPhotoType['object_id']);

            }
            $strPhotoSize = $this->getPhotoSize($val); //id-照片id、获取 照片 大小
            $arrParam['changeNum'] += $strPhotoSize; //btype
        }
        if ($arrParam['changeNum'] != 0) {
            $this->changeAlbumCapacity($arrParam, 2); //改变相册容量
            // 改变相册容量--end
        }
        return $BoolStatus;
    }

    /**
     * 取得指定相册的所有照片信息 (仅获取照片列表)
     * @param $albumId
     * @return array
     */
    public function getPhotoListOfAlbum($param)
    {
        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 8; //$param['num'] 自定义 显示条数
        $page_start = ($page - 1) * $page_num;
        $data['album_id'] = $param['albumId'];
        $data['uid'] = $param['uid'];
        $data['status'] = array('egt', $this->auditIng);
        $this->total = M($this->photo)->where($data)->count();
        $arrPhotoMsg = M($this->photo)->where($data)
            ->order('cretime desc,photo_id desc')->limit($page_start . ',' . $page_num)
            ->field('photo_id as pid,album_id as aid,photo_path as popath,photo_name as pname,imagewidth,imagehigth')->select();
        return $arrPhotoMsg;
    }

    /**
	 * 取得 相片 数
	 * @param $param
	 * @return mixed
	 */
    public function getCntPhoto($param)
    {
        $data['album_id'] = $param['album_id'];
        $data['status'] = array('egt', $this->statusOk);
        $intPhoto = M($this->photo)->where($data)->count();
        return $intPhoto;
    }

    /**
     * 取得某照片信息
     * @param $photoId   照片 id
     * @param int $commentid  评论 id
     * @return array
     */
    public function getPhotoInfo($photoId)
    {
        $arrPhotoInfo = M($this->photo)->where('photo_id=' . $photoId . ' and status>= ' . $this->statusOk)->find();
        if ($arrPhotoInfo) {
            $arrPhotoInfo['cretime'] = empty($arrPhotoInfo['cretime']) ? '' : date('Y-m-d', $arrPhotoInfo['cretime']);
            $arrPhotoInfo['photo_name'] = empty($arrPhotoInfo['photo_name']) ? $arrPhotoInfo['photo_id'] : $arrPhotoInfo['photo_name'];
        }
        return empty($arrPhotoInfo) ? array() : $arrPhotoInfo;
    }

    /**
     * 评论照片 / 回复照片评论
     * @param $commentMsg 评论信息
     * @param $fid 是否转播 0 不转播且不生成动态 1转播 且生成动态
     * @return mixed
     */
    public function commentPhoto($commentMsg, $fid = 0)
    {
        if ($fid == $this->statusDel) {
            $weiBoModel = D('UcWeibo');
            $weiBoModel->addWeibo($commentMsg);
        }
        $data['content'] = $commentMsg['weibo_content'];
        $data['dateline'] = time();
        $data['uid'] = $commentMsg['uid'];
        $data['commentid'] = $commentMsg['commentid'];
        $data['photo_id'] = $commentMsg['photo_id'];
        $boolId = $this->add($data);
        return $boolId;
    }

    /**
     * 评论照片
     *
     * @param $param array 参数数组
     *
     * @return boolean 处理结果
     */
    public function commentAlbum($param)
    {
        $data['content'] = $param['content'];
        $data['dateline'] = time();
        $data['uid'] = $param['uid'];
        if ($param['commentid']) {
            $data['commentid'] = $param['commentid'];
        } else {
            $data['commentid'] = 0;
        }
        $data['photo_id'] = $pid = $param['pid'];
        $data['status'] = array('egt', 0);
        $data['isnew'] = 1;

        $cid = M("uc_photo_comment")->add($data);
        if ($cid) {
            M($this->photo)->where("photo_id='$pid'")->setInc('comments'); // 用户的评论数数加1
            return $cid;
        } else {
            return false;
        }

    }

	/**
	 * 10分钟内，同一个用户不可以评论同样的内容 获取最后的评论
	 * @param $uid
	 * @param $content
	 * @param $pid
	 * @return mixed
	 */
    public function getLastComment($uid, $content, $pid)
    {
        $arrMsg = M()->Table('uc_photo_comment')->where("uid =" . $uid . " and content='" . $content . "' and photo_id =" . $pid . "")->order('dateline desc')->find();
        return $arrMsg;
    }

    /**
     * 删除照片评论  ---删除照片 时
     * @param $photoId  照片 id
     * @return mixed
     */
    public function deleteCommentByPid($photoId)
    {
        $data['status'] = $this->auditDel;
        $res = M("uc_photo_comment")->where("photo_id=$photoId and status >= 0")->save($data);
        if ($res) {
            M($this->photo)->where("photo_id='$photoId'")->setField('comments', 0); // 用户的评论数 为 0
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除照片评论  ---照片评论页
     * @param $cId 评论 的id
     * @param $photoId  照片 id
     * @return mixed
     */
    public function deletePhotoComment($cId)
    {
        $data['status'] = $this->auditDel;
        $photoId = M('uc_photo_comment')->where("id=$cId")->getField('photo_id');
        if (empty($photoId)) {
            return false;
        }
        $res = M("uc_photo_comment")->where("id=$cId")->save($data);
        if ($res) {
            $this->loseCommentNUm($photoId); // 用户的评论数减1
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取评论
     * @param $photoId
     * @param $param
     * @return array
     */
    public function getCommentInfo($param)
    {
        if ($param['photoId']) {
            $page = $param['page'] ? $param['page'] : 1;
            $page_num = $param['page_num'] ? $param['page_num'] : 4; //$param['num'] 自定义 显示条数
            $page_start = ($page - 1) * $page_num;
            $condition['photo_id'] = $param['photoId'];
            $condition['status'] = array('egt', $this->statusOk);
            $this->total = M($this->comment)->where($condition)->count();
            $arrCommentInfo = M($this->comment)->where($condition)->order('dateline desc')->limit("$page_start, $page_num")->select();
            foreach ($arrCommentInfo as $ck => $val) {
                $uid = $this->getCommentUid($val['id']);
                $arrCommentInfo[$ck]['nickName'] = D('UcUser')->getUserNickname($uid);
                $arrCommentInfo[$ck]['avatar'] = D('UcUser')->getHeadPhoto($uid);
                $arrCommentInfo[$ck]['time'] = date('Y-m-d', $val['dateline']);
                if ($val['commentid']) {
                    $replycomment = M()->Table("uc_photo_comment c")->join("boqii_users u ON c.uid=u.uid")->where("c.id=" . $val['commentid'])->field("c.uid, u.nickname")->find();
                    $arrCommentInfo[$ck]['reply_uid'] = $replycomment['uid'];
                    $arrCommentInfo[$ck]['reply_nickname'] = $replycomment['nickname'] ? $replycomment['nickname'] : $replycomment['uid'];
                }
            }
            //查出评论人uid
            return $arrCommentInfo;
        } else {
            return array();
        }
    }

    /**
     * 相片换相册
     * @param $albumId
     * @param $photoId
     * @return mixed
     */
    public function changePhotoAlbum($albumId, $photoId, $uid)
    {
        $data['album_id'] = $albumId;
        $data['updatetime'] = time();
        $boolInt = M($this->photo)->where("photo_id=$photoId and uid=$uid")->save($data);
        return is_numeric($boolInt) ? $boolInt : false;
    }

    /**
     * 批量 相片换相册
     * @param $arrMsg
     * @return mixed
     */
    public function changePhotoAlbumPl($arrMsg, $uid, $albumId)
    {
        foreach ($arrMsg as $val) {
            $boolInt = $this->changePhotoAlbum($albumId, $val, $uid);
        }
        return $boolInt;
    }

    /**
     * 取得用户默认照片
     *
     * @param $uid int 用户id
     * @param $num int 照片数量
     *
     * @return array 照片列表
     */
    public function getUserDefaultPhotos($uid, $num = 5)
    {
		//用户默认相册id
		$albumId = $this -> where(array('default' => 0,'uid' => $uid)) -> getField('id');
		//默认相册图片
		$photos = M('uc_photo') ->where(array('album_id' => $albumId ,'status' => 0)) -> order("cretime DESC")->limit($num)->select();

		$album_upload = C('ALBUM_IMAGE_UPLOAD');
        foreach ($photos as $pk => $photo) {
            list($m_x, $m_y) = getallsizebymin($photo['imagewidth'], $photo['imagehigth'], $album_upload['uploadWidthM'], $album_upload['uploadHeightM']);

            $photos[$pk]['imageWidthM'] = $m_x;
            $photos[$pk]['imageHeightM'] = $m_y;
        }

        return $photos;
    }

    /**
	 * 获取用户列表页 普通相册 的封面
	 * @param $param
	 * @return mixed
	 */
    public function getNormalPhoto($param)
    {
        $data['album_id'] = $param['album_id'];
        $data['status'] = array('egt', $this->auditIng);
        $arrDefaultAlbum = M($this->photo)->where($data)->order('is_cover desc,cretime desc')->limit('5')->field('photo_path,imagewidth,imagehigth')->select();
        return $arrDefaultAlbum;
    }

    /**
	 * 检查是否有默认相册
	 * @param $param
	 * @return mixed
	 */
    public function addDefaultAlbum($param)
    {
        $data['uid'] = $param['uid'];
        $data['status'] = $this->auditIng;
        $data['default'] = 0;
        $intAlbumId = M($this->album)->where($data)->getField('id');
        if (!$intAlbumId) { //没有则创建
            $condition['uid'] = $param['uid'];
            $condition['default'] = 0;
            $condition['dateline'] = time();
            $condition['title'] = '默认相册';
            $intAlbumId = M($this->album)->where($data)->add($condition);
        }

        return $intAlbumId; //默认相册 id
    }

    /**
	 * 检查是否是默认相册  by 用户 uid
	 * @param $param
	 * @return bool|mixed
	 */
    public function getBoolDefault($param)
    {
        $data['uid'] = $param['uid'];
        $data['status'] = $this->auditIng;
        $data['default'] = 0;
        $intAlbumId = M($this->album)->where($data)->getField('id');
        return empty($intAlbumId) ? false : $intAlbumId;
    }

    /**
	 * 检查是否是默认相册  by 相册 id
	 * @param $albumId
	 * @return bool|mixed
	 */
    public function getBoolDefaultByAid($albumId)
    {
        $data['id'] = $albumId;
        $data['status'] = $this->auditIng;
        $data['default'] = 0;
        $intAlbumId = M($this->album)->where($data)->getField('id');
        return empty($intAlbumId) ? false : $intAlbumId;
    }


    /**
	 * 获取 相册当前容量
	 * @param $param
	 * @return array
	 */
    public function getAlbumSize($param)
    {
        $data['uid'] = $param['uid'];
        $intMphotoSize = M('boqii_users_extend')->where($data)->getField('photosize');
        $sizeG = round(($intMphotoSize / (1024 * 1024 * 1024)) * 100, 2); //多少G
        $size[] = $this->getSize($intMphotoSize);
        $size[] = $sizeG;
        return $size;
    }

    /**
     * 获取相册大小
     * @param $intphotoSize
     * @return string
     */
    function getSize($intphotoSize)
    {
        $G = 1024 * 1024 * 1024;
        $M = 1024 * 1024;
        $K = 1024;
        if ($intphotoSize < (10 * $K)) {
            $size = round($intphotoSize / $K) . 'K';
        } else if ($intphotoSize < (100 * $M)) {
            $size = round($intphotoSize / $M, 2) . 'M';
        } else {
            $size = round($intphotoSize / $G, 2) . 'G';
        }
        return $size;
    }


    /**
	 * 获取相册名称
	 * @param $aid
	 * @return mixed
	 */
    public function getAlbumName($aid)
    {
        $strAlbumName = M($this->album)->where("id=$aid")->getField('title');
        return $strAlbumName;
    }

    /**
     * 获取照片的部分信息
     * @param $param
     * @return array
     */
    public function getPhotoBaseMsg($param)
    {
        $arrPgotoBaseMsg = array();
        $arrPid = $param['pid'];
        foreach ($arrPid as $val) {
            $arrMsg = M($this->photo)->where("photo_id=$val")->
                field('photo_id as pid,photo_path as phopath,photo_name as  pname,photo_desc as pdesc,is_cover as iscover')
                ->find();
            $arrPgotoBaseMsg[] = $arrMsg;
        }
        return $arrPgotoBaseMsg;
    }

    /**
	 * 获取用户所有相册 的id、name
	 * @param $param
	 * @return mixed
	 */
    public function getUserAllAlbum($param)
    {
        $data['uid'] = $param['uid'];
        $data['status'] = $this->statusOk;
        $arrAlbum = M($this->album)->where($data)->field('id,title')->order('`default` asc')->select();
        return $arrAlbum;
    }

    /**
	 * 获取相册下的所有照片
	 * @param $param
	 * @return array
	 */
    public function getAlbumAllPhotoMsg($param)
    {
        $condition['album_id'] = $param['aid'];
        $condition['status'] = array('egt', 0);
        ;
        $arrAllPhoto = M($this->photo)->where($condition)->order('cretime desc')->select();
        foreach ($arrAllPhoto as $ak => $val) {
            $arrAllPhoto[$ak]['updatetime'] = empty($val['updatetime']) ? '' : date('Y-m-d', $val['updatetime']);
            $path = $val['photo_path'];
            $intLastPosition = strripos("$path", "_y");
            if ($intLastPosition) { //gif 格式 不存在 _y 的后缀。其他格式存在则替换成页面的大小比例缩略图
                $arrAllPhoto[$ak]['mpath'] = substr_replace("$path", "_m", $intLastPosition, 2);
                list($width, $height) = getallsizebymin($val['imagewidth'], $val['imagehigth'], $this->photoMiddenWidth, $this->photoMiddenHeight); //宽高处理
                $arrAllPhoto[$ak]['width'] = $width;
                $arrAllPhoto[$ak]['height'] = $height;
            } else {
                $arrAllPhoto[$ak]['mpath'] = $path;
                $arrAllPhoto[$ak]['width'] = $val['imagewidth'];
                $arrAllPhoto[$ak]['height'] = $val['imagehigth'];
            }

        }
        return empty($arrAllPhoto) ? array() : $arrAllPhoto;
    }

    /**
	 * 查出评论人 uid
	 * @param $commentId
	 * @return int|mixed
	 */
    public function getCommentUid($commentId)
    {
        $data['id'] = $commentId;
        $uid = M($this->comment)->where($data)->getField('uid');
        return empty($uid) ? 0 : $uid;
    }

    /**
	 * 照片名字
	 * @param $photoId
	 * @return mixed
	 */
    public function getPhotoName($photoId)
    {
        $photo = M($this->photo)->where("photo_id='$photoId'")->getField('photo_name');
        if ($photo['photo_name']) {
            return $photo['photo_name'];
        }
        else {
            return $photoId;
        }
    }

    /**
	 * 获取删除后的照片大小
	 * @param $photoId
	 * @return int|mixed
	 */
    public function getPhotoSize($photoId)
    {
        $photoSize = M($this->photo)->where("photo_id=$photoId and status < $this->statusOk")->getField('size');
        return empty($photoSize) ? 0 : $photoSize;
    }

    //获取ajax 照片信息+照片评论信息
    /**
     * @param $param
     * $param photoId 照片id
     * $param p 当前页数
     * $param pageNUm 每页显示数目
     * $param   loginUId 当前登录用户
     * @return string
     */
    public function getAjaxphotoShow($param)
    {
        $wrList = array();
        $status = 0;
//        $param = array('photoId' => 2, 'p' => 1, 'pageNUm' => 20,
//            'login' => array('flag' => 1,
//                'loginUId' => 140
//            ));
        //照片信息
        $arrPhotoInfo = $this->getPhotoInfo($param['photoId']);
        if ($arrPhotoInfo) {
            //查询状态
            $status = 1;
            $condition = array('photoId' => $param['photoId'], 'page' => $param['p'], 'page_num' => $param['pageNUm']);
            //照片评论信息
            $wrList = $this->getCommentInfo($condition);
            if (!empty($wrList)) {
                foreach ($wrList as $key => $val) {
                    //检测是否是当前用户的照片页面
                        if ($arrPhotoInfo['uid'] == $param['login']['loginUId']) {
                            //显示删除
                            $wrList[$key]['ofMe'] = 1;
                        } else {
                            //检测是否是登录用户的评论
                            if($param['login']['loginUId'] == $val['uid'])  {
                                $wrList[$key]['ofMe'] = 1;
                            } else {
                                //不显示删除
                                $wrList[$key]['ofMe'] = 0;
                            }
                        }

                    $wrList[$key]['useId'] = $val['uid'];
                    $wrList[$key]['wbId'] = $val['commentid'];
                    $wrList[$key]['useName'] = $val['nickName'];
                    $wrList[$key]['imgHead'] = $val['avatar'];
                    $wrList[$key]['message'] = $val['content'];
                    $wrList[$key]['wrDate'] = $val['time'];
                }

            }
        } else {
            $status = 0;
        }
        $arrPhotoInfo['wrList'] = $wrList;
        $arrPhotoInfo['status'] = $status;
        $arrPhotoInfo['page'] = $param['p'];
        $arrPhotoInfo['pCount'] = $param['pageNUm'];
        $arrPhotoInfo['lkCount'] = $arrPhotoInfo['views']; //查看数
        $arrPhotoInfo['wrCount'] = $arrPhotoInfo['comments']; //评论数
        return $arrPhotoInfo;
    }

    /**
	 * 评论数减少
	 * @param $photoId
	 */
    public function loseCommentNUm($photoId) {
        //当前照片的评论数
        $intComments = M($this->photo)->where("photo_id='$photoId'")->getField('comments');
        if($intComments >0) {
            M($this->photo)->where("photo_id='$photoId'")->setDec('comments'); // 用户的评论数减1
        } else if($intComments <0){
            M($this->photo)->where("photo_id='$photoId'")->setField('comments',0);
        } else {
            //do nothing
        }
    }


//--------------------------------------------------------------------
    /**
	 * 相册 当前状态  --存在 不存在(已删除--跳转 404)
	 * @param $param
	 * @return mixed
	 */
    public function getStatusAlbum($param)
    {
        $condition['id'] = $param['aid'];
        $condition['status'] = $this->statusOk;
        $intUid = M($this->album)->where($condition)->getField('uid'); //返回用户 uid
        return $intUid;
    }

    /**
	 * 照片当前状态  --存在 不存在(已删除--跳转 404)--获取相册 id
	 * @param $param
	 * @return mixed
	 */
    public function  getStatusPhoto($param)
    {
        $data['photo_id'] = $param['pid'];
        $data['status'] = array('egt', $this->auditIng);
        $intAid = M($this->photo)->where($data)->getField('album_id'); //返回照片的相册 id
        return $intAid;
    }

    /**
	 * 评论当前状态 --存在 不存在(已删除--跳转 404)--获取相册 id
	 * @param $param
	 * @return mixed
	 */
    public function getStatusComment($param)
    {
        $data['id'] = $param['cid'];
        $data['status'] = array('egt', $this->auditIng);
        $intUid = M($this->comment)->where($data)->getField('uid');
        return $intUid;
    }

    /**
	 * 照片和当前用户的 关系 (暂时没用到---Gavin)
	 * @param $param
	 * @return mixed
	 */
    public function getRelationPhoto($param)
    {
        $data['photo_id'] = $param['pid'];
        $data['uid'] = $param['uid'];
        $data['status'] = array('egt', $this->auditIng);
        $intPid = M($this->photo)->where($data)->getField('photo_id');
        return $intPid;
    }

    /**
	 * 相册 与当前用户的关系（暂时没用到--Gavin）
	 * @param $param
	 * @return mixed
	 */
    public function getRelationAlbum($param)
    {
        $data['id'] = $param['aid'];
        $data['uid'] = $param['uid'];
        $data['status'] = $this->statusOk;
        $intAid = M($this->album)->where($data)->getField('id'); //相册 id
        return $intAid;
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
     * 查询当前容量
     * @param $uid
     * @return mixed
     */
    public function getAlbumCapacity($uid)
    {
        $intMphotoSize = M('boqii_users_extend')->where("uid = $uid")->getField('photosize'); //查询当前 容量
        return $intMphotoSize;
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
     * 获得单张图片显示临界尺寸(最大的宽和高)
     * 返回宽和高array(宽,高)
     *
     * @param $width int 宽度
     * @param $height int 高度
     * @param $maxwidth int 最大宽度
     * @param $maxheight int 最大高度
     * @param $t boolean
     *
     * @return string 宽高
     */
    public function getallsizebymin($width, $height, $maxwidth, $maxheight, $t = false)
    {
        $ratio = 1;
        $str = '';
        if (!empty($width) && !empty($height)) {
            if ($maxwidth && $maxheight) {
                //图片宽 >= 高
                if ($width >= $height) {
                    //按高度压缩比率
                    if ($height > $maxheight) {
                        $heightratio = $maxheight / $height;
                    } else {
                        $heightratio = 1;
                    }
                } else {
                    //按宽度压缩比率
                    if ($width > $maxwidth) {
                        $widthratio = $maxwidth / $width;
                    } else {
                        $widthratio = 1;
                    }
                }

                //计算图片压缩比率
                if ($widthratio > 0) {
                    $ratio = $widthratio;
                } elseif ($heightratio > 0) {
                    $ratio = $heightratio;
                }

                //根据得出的比例,重新计算缩略图的宽和高
                $newwidth = $ratio * $width;
                $newheight = $ratio * $height;
                if (!$t) {
                    return array($newwidth, $newheight);
                } else {
                    $str = " width='$newwidth' height='$newheight' ";
                    return $str;
                }
            }
        }
        return $str;
    }

    /**
     * 根据相册id 查询 宠物是否存在
     * @param $aid     相册 id
     * @return bool     宠物 id
     */
    public function getBoolPetByAlbumId($aid)
    {
        $intPetId = M()->Table("uc_album a")->join("uc_user_pet p on p.id = a.pet_id")->where("a.id = $aid and p.valid=1")->getField('p.id');
        return empty($intPetId) ? false : $intPetId;
    }

    /**
	 * 更改相册的宽高
	 * @param $path
	 * @param $pid
	 */
    public function updatePhotoWidthHeight($path, $pid)
    {
        $aa = getimagesize("$path");
        $weight = $aa["0"]; ////获取图片的宽
        $height = $aa["1"]; ///获取图片的高
        $data = array('imagewidth' => $weight, 'imagehigth' => $height);
        M()->Table('uc_photo')->where(array('photo_id' => $pid))->setField($data); //更改数据库的值
    }

    /*************************************/
    /**
	 * 批量更改相册的宽高
	 * @param $lockKey
	 */
    public function updatePhotoWiHe($lockKey)
    {
        set_time_limit(0);
        if (md5($lockKey) == 'bbc238adcc2b896978d424ac47e9c56c'){
            $arrphoto = M()->Table('uc_photo')->field('photo_id,photo_path')->select(); //查询出所有的照片 id
            foreach ($arrphoto as $pk) {
                $path = C('IMG_DIR') . '/' . $pk['photo_path'];
                $pid = $pk['photo_id'];
                $this->updatePhotoWidthHeight($path, $pid);
            }
        }

    }

    /**
	 * 重置评论数
	 * @param $lockKey
	 */
    public function resetComment($lockKey){
        set_time_limit(0);
        if (md5($lockKey) == 'bbc238adcc2b896978d424ac47e9c56c'){
            //查询照片
            $arrPhoto = M()->Table('uc_photo')->getField('photo_id',true); //查询出所有的照片 id
            foreach($arrPhoto as $val) {
                   //查询评论数
                    $intPhotoComment = M()->Table('uc_photo_comment')->where(array('photo_id'=>$val,'status'=>array('egt',0)))->count();
                    //更新评论数目
                        M()->Table('uc_photo')->where(array('photo_id'=>$val))->setField('comments',$intPhotoComment);
            }
        }
    }

    /*************************************/

}
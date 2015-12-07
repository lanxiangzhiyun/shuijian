<?php
/**
 * 宠物Model类
 *
 * @created 12-11-9
 * @author zlg
 */
class UcPetsModel extends Model
{
    //表名
    protected $trueTableName = 'uc_user_pet';

    /**
     * 取得用户的宠物列表
     *
     * @param $param array 参数数组
     *
     * @return array 宠物数组
     */
    public function getUserPetsList($param)
    {
        if (!$param['uid']) {
            return array();
        }
        $where = "up.valid=1";
        $where .= " AND up.uid=" . $param['uid'];
        $userPets = M()->Table("uc_user_pet up")->where($where)->field("up.id,up.uid,up.petname,up.petbday,up.pettype,up.petgender,up.weight,up.lineages,up.adopte_time,up.spending,up.is_default,up.lovenum,up.petstatus,up.picdescrip,up.picpath,up.picviews,up.picstatus,up.regtype,up.cretime,up.updatetime,up.valid,up.is_immnued,up.immune_time,up.is_repellend,up.repell_time,up.character,up.foods,up.toys,up.specialty,up.instructions")->order("up.is_default DESC,up.lovenum DESC")->select();
        foreach ($userPets as $uk => $pet) {
            //宠物性别
            $userPets[$uk]['petgender_name'] = $pet['petgender'] == "1" ? "公" : ($pet['petgender'] == "2" ? "母" : "");
            if ($pet['picpath']) {
                $userPets[$uk]['picpath'] = $pet['picstatus'] == "2" ? "Public/Images/no_pet_150.gif" : $userPets[$uk]['picpath'];
            } else {
                $userPets[$uk]['picpath'] = "Public/Images/no_pet_150.gif";
            }
            if ($pet['petbday']) {
                $petyear = date('Y') - date("Y", $pet['petbday']); //年份
                $petmonth = date('n') - date("n", $pet['petbday']); //月份（前不补零）
                $petday = date('j') - date("j", $pet['petbday']); //日期（前不补零）
                $petage = "";
                //不足一个月以一个月计算
                if ($petyear == 0) {
                    //不足一月
                    if ($petmonth == 0) {
                        $petage = "1个月";
                    }
                    elseif ($petday < 0) {
                        $petage = ($petmonth - 1 > 0 ? $petmonth - 1 : 1) . "个月";
                    } else {
                        $petage = $petmonth . "个月";
                    }
                } elseif ($petyear > 0) {
                    if ($petmonth > 0) {
                        if ($petday < 0) {
                            $petmonth--;
                        }
                    } else {
                        $petmonth = 0;
                    }
                    if ($petyear) {
                        $petage .= $petyear . "岁";
                    }
                    if ($petmonth) {
                        $petage .= $petmonth . "个月";
                    }
                }
                $userPets[$uk]['petage'] = $petage;
            }
            $userPets[$uk]['lineages_name'] = $pet['lineages'] == 1 ? "纯种" : ($pet['lineages'] == 2 ? "串串" : "");
            $userPets[$uk]['petgender_lineages'] = $userPets[$uk]['lineages_name'] ? $userPets[$uk]['petgender_name'] . "," . $userPets[$uk]['lineages_name'] : $userPets[$uk]['petgender_name'];
            $pet_photos = M()->Table("uc_photo p")->join("uc_album a ON p.album_id=a.id")->where("p.status = 0 AND a.status=0 AND a.pet_id=" . $pet['id'])->field("p.photo_id, p.photo_path, p.album_id, p.imagewidth, p.imagehigth, a.title")->order("p.cretime DESC")->limit(5)->select();
            //相册缩略图设置
            $album_upload = C('ALBUM_IMAGE_UPLOAD');
            foreach($pet_photos as $pk => $pet_photo) {
                list($m_x, $m_y) = getallsizebymin($pet_photo['imagewidth'], $pet_photo['imagehigth'], $album_upload['uploadWidthM'], $album_upload['uploadHeightM']);

                $pet_photos[$pk]['imageWidthM'] = $m_x;
                $pet_photos[$pk]['imageHeightM'] = $m_y;
            }
            $userPets[$uk]['pet_photos'] = $pet_photos;

        }
        return $userPets;
    }

    /**
     * 添加宠物
     * @param $param
     * @return mixed|string
     */
    public function addPets($param)
    {
            $data['petname'] = $param['petname'];
            $data['uid'] = $param['uid'];
            $data['pettype'] = $param['pettype'];
            $data['petgender'] = $param['petgender'];
            $data['weight'] = (int)$param['weight'];
            $data['petbday'] = $param['petbday'];
            $data['adopte_time'] = $param['adopte_time'];
            $data['lineages'] = $param['lineages'];
            $data['petstatus'] = $param['petstatus'];
            $data['spending'] = $param['spending'];
            $data['is_default'] = $param['is_default'];
            $data['cretime'] = time();
            if ($param['is_default'] == 1) {
                $intId = M('uc_user_pet')->where('uid=' . $param['uid'] . ' and valid=1 and is_default=1')->getField('id'); //查询当前当家宠物
                if ($intId) { //去掉当家宠物
                    $condition['id'] = $intId;
                    $condition['is_default'] = 0;
                    M('uc_user_pet')->save($condition);
                }
            }
            $boolStatus = M('uc_user_pet')->add($data);
            return $boolStatus;

    }

    /**
	 * 编辑宠物
	 * @param $param  编辑参数
	 * @return bool
	 */
    public function editPets($param)
    {
        $data['id'] = $param['id'];
        $data['petname'] = $param['petname'];
        $data['pettype'] = $param['pettype'];
        $data['petgender'] = $param['petgender'];
        $data['weight'] = (int)$param['weight'];
        $data['petbday'] = $param['petbday'];
        $data['adopte_time'] = $param['adopte_time'];
        $data['lineages'] = $param['lineages'];
        $data['petstatus'] = $param['petstatus'];
        $data['spending'] = $param['spending'];
        $data['is_default'] = $param['is_default'];
        if ($param['is_default'] == 1) {
            $intId = M('uc_user_pet')->where('uid=' . $param['uid'] . ' and valid=1 and is_default=1')->getField('id'); //查询当前当家宠物
            if ($intId) { //去掉当家宠物
                $condition['id'] = $intId;
                $condition['is_default'] = 0;
                M('uc_user_pet')->save($condition);
            }
        }
        $boolStatus = M('uc_user_pet')->save($data);
        return is_numeric($boolStatus) ? true : false;
    }

    /**
	 * 删除宠物
	 * @param $param  删除参数
	 * @return bool
	 */
    public function deletePets($param)
    {
        $data['id'] = $param['id'];
        $data['valid'] = 0;
        $data['updatetime'] = time();
        $status = M('uc_user_pet')->save($data);
        return $status;
    }

    /**
     * 单个 宠物 基本资料
     * @param $param
     * @return array
     */
    public function getPetsDetails($param)
    {
        $data['id'] = $param['id'];
        $data['valid'] = 1;
        $petBaseMsg = M()->Table("uc_user_pet up")->join("boqii_pet_type pt ON up.pettype=pt.pet_type_id")->where($data)->field("up.id,up.uid,up.petname,up.petbday,up.pettype,pt.pet_type_name,up.petgender,up.weight,up.adopte_time,up.lineages,up.petstatus,up.spending,up.is_default,pt.pet_type_name")->find();
        $timeBday = $petBaseMsg['petbday'];
        $timeAdope = $petBaseMsg['adopte_time'];
        $petBaseMsg['petbday'] = empty($timeBday) ? '' : date('Y-m-d', $timeBday);
        $petBaseMsg['adopte_time'] = empty($timeAdope) ? '' : date('Y-m-d', $timeAdope);
        $petBaseMsg['weight'] = $petBaseMsg['weight']== '0.00' ? '' :$petBaseMsg['weight'];
        return empty($petBaseMsg) ? array() : $petBaseMsg;
    }

    /**
	 * 获取宠物头像
	 * @param $param   宠物id
	 * @return string
	 */
    public function getPetPhoto($param)
    {
        $data['id'] = $param['id'];
        $data['valid'] = 1;
        $data['picstatus'] = array('neq', 2); //不能为未审核
        $strPetPhoto = M()->Table("uc_user_pet up")->where($data)->getField("up.picpath");
        return empty($strPetPhoto) ? '/Public/Images/no_pet_70.gif' : C('IMG_DIR').'/'.$strPetPhoto;
    }

    /**
	 * 获取宠物个性爱好
	 * @param $param  宠物id
	 * @return array
	 */
    public function getPetHobby($param)
    {
        $data['id'] = $param['id'];
        $data['valid'] = 1;
        $arrPetsHobby = M()->Table("uc_user_pet up")->where($data)->field("up.is_immnued,up.immune_time,up.is_repellend,up.repell_time,up.character,up.foods,up.toys,up.specialty,up.instructions")->find();
        $timeMy = $arrPetsHobby['immune_time'];
        $timeQu = $arrPetsHobby['repell_time'];
        $arrPetsHobby['immune_time'] = empty($timeMy) ? '' : date('Y-m-d', $timeMy);
        $arrPetsHobby['repell_time'] = empty($timeQu) ? '' : date('Y-m-d', $timeQu);
        return $arrPetsHobby;
    }

    /**
	 * 设置为当家宠物
	 * @param $param
	 */
    public function setMasterPets($param)
    {
        $intId = M('uc_user_pet')->where('uid=' . $param['uid'] . ' and valid=1 and is_default=1')->getField('id'); //查询当前当家宠物
        if ($intId) { //去掉就当家宠物
            $data['id'] = $intId;
            $data['is_default'] = 0;
            $data['updatetime'] = time();
            M('uc_user_pet')->save($data);
        }
        $condition['id'] = $param['id']; //当前 id
        $condition['is_default'] = 1; //当家宠物
        $condition['updatetime'] = time();
        M('uc_user_pet')->save($condition);

    }

    /**
	 * 喜欢操作（他人宠物页面）
	 * @param $param
	 * @return array
	 */
    public function addLoveNum($param)
    {
        //是否用户已喜欢宠物
        $res = M("uc_pet_love")->where("pet_id=" . $param['id'] . " AND uid=" . $param['uid'])->field("id")->find();
        if ($res) {
            $rtn['status'] = 'error';
            $rtn['tip'] = '亲，你已经喜欢过了！';
        } else {
            // 宠物的喜欢数加1
            $intStatus = M('uc_user_pet')->where('id=' . $param['id'])->setInc('lovenum', 1);

            //记录宠物喜欢数
            $data['pet_id'] = $param['id'];
            $data['uid'] = $param['uid'];
            $data['cretime'] = time();
            M("uc_pet_love")->add($data);

            //获取宠物的喜欢数
            $lovenum = M('uc_user_pet')->where('id=' . $param['id'])->getField('lovenum');

            $rtn['status'] = 'ok';
            $rtn['cout'] = $lovenum;
        }

        return $rtn;
    }

    /**
     * 获取用户宠物数量
     * @param $param
     * @return mixed
     */
    public function getPetCnt($uid)
    {
        $intPetCnt = M('uc_user_pet')->where('uid=' . $uid . ' and valid=1')->count();
        return $intPetCnt;
    }

    /**
     * 获取用户宠物信息
     * @param $param
     * @return array
     */
    public function getUserPets($uid)
    {
        $pets = M('uc_user_pet')->where('uid=' . $uid . ' and valid=1')->select();
        foreach($pets as $key=>$val){
            $petClass = M('boqii_pet_class')->where('id='.$val['pettype'])->field('title')->find();
            $petClassTitle = '';
            if($petClass){
                $petClassTitle = $petClass['title'];
            }
            $pets[$key]['pettype_title'] = $petClassTitle;
        }
        return $pets;
    }

	/**
	 * 设置宠物头像
	 * @param $param 参数
	 * @return string
	 */
    public function  addPhoto($param)
    {
        $data['picpath'] = $param['picpath'];
        $data['id'] = $param['id'];
        $data['updatetime'] = time();
        $boolExtend = M('uc_user_pet')->save($data);
        if (is_numeric($boolExtend)) return 'true'; else return 'false';
    }

    /**
     * 个性爱好
     * @param $param
     * @return bool
     */
    public function addPetHoppy($param)
    {
        $time = strtotime(date('Y-m-d', time()));
        if ($param['is_immnued'] == 0) {
            $data['immune_time'] = $param['immune_time'];
            if ($param['immune_time'] > $time) {
                return 'immuneMax';
            }
        }
        if ($param['is_repellend'] == 0) {
            $data['repell_time'] = $param['repell_time'];
            if ($param['repell_time'] > $time) {
                return 'repellMax';
            }
        }
        $data['id'] = $param['id'];
        $data['is_immnued'] = $param['is_immnued'];
        $data['is_repellend'] = $param['is_repellend'];
        $data['character'] = $param['character'];
        $data['foods'] = $param['foods'];
        $data['toys'] = $param['toys'];
        $data['specialty'] = $param['specialty'];
        $data['instructions'] = $param['instructions'];
        $data['updatetime'] = time();

        $intCharacterCnt = strlength_utf8($data['character']);
        $intFoodsCnt = strlength_utf8($data['foods']);
        $intToysCnt = strlength_utf8($data['toys']);
        $intSpecialtyCnt = strlength_utf8($data['specialty']);
        $intInstructionsCnt = strlength_utf8($data['instructions']);

        if ($intCharacterCnt > 40 || $intFoodsCnt > 40 || $intToysCnt > 40 || $intSpecialtyCnt > 40 || $intInstructionsCnt > 40) {
            return 'decMax';
        }

        $boolStatus = M('uc_user_pet')->save($data);
        return is_numeric($boolStatus) ? 'true' : 'false';
    }

    /**
     * 获取 宠物昵称 id，用户的  uid  --用于我的关系里
     * @param $uid
     * @return mixed
     */
    public function  getRelationPet($uid)
    {
        //是否有当家宠物
        $arrDefaultPet = M('uc_user_pet')->where('uid=' . $uid . ' and valid=1 and is_default=1')->field('id,uid,petname')->find(); //查询当前当家宠物
        if (!$arrDefaultPet) {
            $arrDefaultPet = M('uc_user_pet')->where('uid=' . $uid . ' and valid=1')->field('id,uid,petname')->order('cretime desc')->limit(1)->find(); //没有显示第一只
        }
        return $arrDefaultPet;
    }

    /**
	 * 获取宠物档案列表 的头像 喜欢数、昵称 、是否当家宠物
	 * @param $param
	 * @return array
	 */
    public function getPetFileList($param)
    {
        $data['uid'] = $param['uid'];
        $data['valid'] = 1;
        $arrPetFileList = M("uc_user_pet")->where($data)->field("id,uid,petname,is_default,lovenum,picpath,picstatus")->limit(3)->order("is_default DESC,cretime DESC")->select();
        foreach ($arrPetFileList as $key => $val) {
            if ($val['picpath']) {
                $arrPetFileList{$key}['picpath'] = $val['picstatus'] == "2" ? '/Public/Images/no_pet_70.gif' : C('IMG_DIR').'/'.$val['picpath'];
            } else {
                $arrPetFileList{$key}['picpath'] = '/Public/Images/no_pet_70.gif';
            }
        }
        return empty($arrPetFileList) ? array() : $arrPetFileList;
    }

    /**
	 * 主人与宠物是否存在 关系
	 * @param $param
	 * @return mixed
	 */
    public function getBoolRelation($param)
    {
        $data['id'] = $param['id'];
        $data['uid'] = $param['uid'];
        $data['valid'] = 1;
        $boolStatus = M("uc_user_pet")->where($data)->getField('petname');
        return $boolStatus;
    }

    /**
	 * 获取 用户所有的宠物 昵称和id
	 * @param $param
	 * @return array|mixed
	 */
    public function getUserPetMsg($param)
    {
        $data['uid'] = $param['uid'];
        $data['valid'] = 1;
        $arrPetMsg = M("uc_user_pet")->where($data)->field('petname,id as pid')->select();
        return empty($arrPetMsg) ? array() : $arrPetMsg;
    }

    /**
	 * 根据宠物 id获取 宠物的 种类
	 * @param $petid   宠物id
	 * @return mixed
	 */
    public function getPetClass($petid){
       $petType = M()->Table('uc_user_pet')->where(array('id'=>$petid))->getField('pettype');
       $strPetName = M()->Table('boqii_pet_class')->where(array('id'=>$petType))->getField('title');
        return $strPetName;
    }

    /**
     * 一个中文字符的占位是2，英文字符是1
     * @param $str
     * @return float
     */
    public function getStrLenth($str)
    {
        return (strlen($str) + mb_strlen($str, 'UTF8')) / 2;
    }

    /**
     * 取得用户宠物资料完成进度
     *
     * @param $uid int 用户id
     *
     * @param int 用户宠物资料最多完成tab数
     */
    public function getUserPetProgress($uid) {
        $where = "up.valid=1";
        $where .= " AND up.uid=".$uid;
        $userPets = M()->Table("uc_user_pet up")->where($where)->field("up.id,up.uid,up.petname,up.petbday,up.pettype,up.petgender,up.weight,up.lineages,up.adopte_time,up.spending,up.is_default,up.lovenum,up.petstatus,up.picdescrip,up.picpath,up.picviews,up.picstatus,up.regtype,up.cretime,up.updatetime,up.valid,up.is_immnued,up.immune_time,up.is_repellend,up.repell_time,up.character,up.foods,up.toys,up.specialty,up.instructions")->order("up.is_default DESC")->select();

        $maxcompleted = 0;
        if($userPets) {
            foreach($userPets as $uk => $pet) {
                $completed = 0;
                if($pet['petname'] && $pet['pettype'] && $pet['petgender'] && $pet['weight'] && $pet['petbday'] && $pet['adopte_time'] && $pet['lineages']) {
                    $completed += 1;
                }
                if($pet['picpath']) {
                    $completed += 1;
                }
                if(($pet['is_immnued'] || (!$pet['is_immnued'] && $pet['immune_time'])) && ($pet['is_repellend'] || (!$pet['is_repellend'] && $pet['repell_time'])) && $pet['character'] && $pet['foods'] && $pet['toys'] && $pet['specialty'] && $pet['instructions']) {
                    $completed += 1;
                }                
                
                if($completed == 3) {
                    return $completed;
                }
                if($completed >= $maxcompleted) {
                    $maxcompleted = $completed;
                }
            }
        }
        return $maxcompleted;
    }
}

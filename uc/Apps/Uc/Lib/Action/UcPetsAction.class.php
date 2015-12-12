<?php
/**
 * UcPets Action
 *
 * @author:zlg
 * @created:12-11-9
 */
class UcPetsAction extends BaseAction
{

    //宠物基本资料
    public function petFiles()
    {
        $uid = $this->publicLogin();
        $param['uid'] = $uid;
        $petFileList = D('UcPets')->getPetFileList($param); //获取用户宠物档案  --一个用户最多拥有 3个宠物
        $intPetsCnt = count($petFileList); //宠物数量
        $id = str_replace(" ", '', $this->_get('id'));
        $condition['id'] = $id;
        $condition['uid'] = $uid;
        $boolStatus = D('UcPets')->getBoolRelation($condition); //判断和是否存在关系
        if ($intPetsCnt < 3 && $intPetsCnt > 0) { //是否出现 添加 接口
            $this->assign('flag', 1); //是否出现 添加 接口--后面的提示文字不显示
        } else if ($intPetsCnt == 0) {
            $this->assign('flag', 2); //是否出现 添加 接口--后面的提示文字显示
        } else {
            //do  nothing
        }
        if ($petFileList) {
            // $data['id'] 当前选中的 id
            if ($id && $boolStatus) {
                $data['id'] = $id;
                foreach ($petFileList as $sk => $val) {
                    if ($val['id'] == $id) $key = $sk;
                }
                $petFileList[$key]['selected'] = 1; //标识 - 当前已选择的宠物
            } else {
                $data['id'] = $petFileList[0]['id'];
                $petFileList[0]['selected'] = 1; //标识 -默认第一项选中
            }
            $this->assign('petList', $petFileList);
        }
        if (isset($_GET['addId']) || $intPetsCnt == 0) {
            $this->assign('display', 1); //后面2项点不了
        } else {
            $petBaseMsg = D('UcPets')->getPetsDetails($data); //基本资料
            $this->assign('currentId', $data['id']); //当前编辑的宠物
            $this->assign('petBaseMsg', $petBaseMsg);
        }
        $this->assign('uid', $uid);
        $this->assign('location', 'petFiles');
        $this->assign('obj', 'me');
        // 加入session值防CSRF攻击
        $key = md5(uniqid(rand(),true));
        $_SESSION[$key] = 1;
        $this->assign('token',$key);
        $this->display('petFiles');
    }

    //宠物头像 页面
    public function petHead()
    {
        $uid = $this->publicLogin();
        $param['uid'] = $uid;
        $petFileList = D('UcPets')->getPetFileList($param); //获取用户宠物档案  --一个用户最多拥有 3个宠物
        $intPetsCnt = count($petFileList); //宠物数量
        $id = $this->_get('id');
        $condition['id'] = $id;
        $condition['uid'] = $uid;
        $boolStatus = D('UcPets')->getBoolRelation($condition); //判断和是否存在关系
        if ($intPetsCnt < 3 && $intPetsCnt > 0) { //是否出现 添加 接口
            $this->assign('flag', 1); //是否出现 添加 接口--后面的提示文字不显示
        } else if ($intPetsCnt == 0) {
            $this->assign('flag', 2); //是否出现 添加 接口--后面的提示文字显示
        } else {
            //do  nothing
        }
        if ($petFileList) {
            // $data['id'] 当前选中的 id
            if ($id && $boolStatus) {
                $data['id'] = $id;
                foreach ($petFileList as $sk => $val) {
                    if ($val['id'] == $id) $key = $sk;
                }
                $petFileList[$key]['selected'] = 1; //标识 -
            } else {
                $data['id'] = $petFileList[0]['id'];
                $petFileList[0]['selected'] = 1; //标识 -默认第一项选中
            }
            $this->assign('petList', $petFileList);
        }
        if ($intPetsCnt == 0) {
            $this->redirect('petFiles', array('uid' => $uid));
        } else {
            $this->assign('currentId', $data['id']);
            $petPhoto = D('UcPets')->getPetPhoto($data); //宠物头像
            $this->assign('petPhoto', $petPhoto);
        }
        $this->assign('id', $id); //宠物 id
        $this->assign('uid', $uid);
        $this->assign('location', 'petFiles');
        $this->assign('obj', 'me');
        // 加入session值防CSRF攻击
        $key = md5(uniqid(rand(),true));
        $_SESSION[$key] = 1;
        $this->assign('token',$key);
        $this->display('petHead');
    }

    //宠物兴趣爱好 页面
    public function petHobby()
    {
        $uid = $this->publicLogin();
        $param['uid'] = $uid;
        $petFileList = D('UcPets')->getPetFileList($param); //获取用户宠物档案  --一个用户最多拥有 3个宠物
        $intPetsCnt = count($petFileList); //宠物数量
        $id = str_replace(" ", '', $this->_get('id'));
        $condition['id'] = $id;
        $condition['uid'] = $uid;
        $boolStatus = D('UcPets')->getBoolRelation($condition); //判断和是否存在关系
        if ($intPetsCnt < 3 && $intPetsCnt > 0) { //是否出现 添加 接口
            $this->assign('flag', 1); //是否出现 添加 接口--后面的提示文字不显示
        } else if ($intPetsCnt == 0) {
            $this->assign('flag', 2); //是否出现 添加 接口--后面的提示文字显示
        } else {
            //do  nothing
        }
        if ($petFileList) {
            // $data['id'] 当前选中的 id
            if ($id && $boolStatus) {
                $data['id'] = $id;
                foreach ($petFileList as $sk => $val) {
                    if ($val['id'] == $id) $key = $sk;
                }
                $petFileList[$key]['selected'] = 1; //标识 -
            } else {
                $data['id'] = $petFileList[0]['id'];
                $petFileList[0]['selected'] = 1; //标识 -默认第一项选中
            }
            $this->assign('petList', $petFileList);
        }
        if ($intPetsCnt == 0) {
            $this->redirect('petFiles', array('uid' => $uid)); //后面2项点不了
        } else {
            $petHobbyMsg = D('UcPets')->getPetHobby($data); //基本资料
            $this->assign('currentId', $data['id']);
            $this->assign('petHobbyMsg', $petHobbyMsg);
        }
        $this->assign('uid', $uid);
        $this->assign('location', 'petFiles');
        $this->assign('obj', 'me');
        // 加入session值防CSRF攻击
        $key = md5(uniqid(rand(),true));
        $_SESSION[$key] = 1;
        $this->assign('token',$key);
        $this->display('petHobby');
    }

    //删除宠物 ---宠物基本资料
    public function  delPet()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {
            $data['id'] = str_replace(" ", '', $this->_get("id"));
            $data['uid'] = $uid;
            $currentId = str_replace(" ", '', $this->_get("currentId")); //当前基本资料的编辑id
            $boolStatus = D('UcPets')->getBoolRelation($data); //判断和是否存在关系
            if ($boolStatus) {
                $statusDel = D('UcPets')->deletePets($data);
                if ($statusDel) {
                    //判断删除的是否是当前基本资料的id
                    if ($currentId == $data['id']) {
                        $status = 'currentok';
                    } else {
                        $status = 'ok';
                    }

                } else {
                    $status = 'false';
                }
            } else {
                $status = 'false';
            }
        } else {
            $status = 'login';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //编辑宠物  ---宠物基本资料
    public function updatePets()
    {
        // 判断请求地址 域名为boqii.com 并且 匹配session中的token
        $checkSafe = checkSafeForSns($_POST['token']);
        if(!$checkSafe){
            $this->ajaxReturn(array('update'=>'safe'),'JSON');
        }
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {
            $petName  = $this->_post('textfield1'); //自动去除 2边空格
            $petType = $this->_post('petId');
            $intLengthName = strlength_utf8($petName);
            $timeBday = $this->_post('textfield3');
            $timeAdope = $this->_post('textfield2');
            $petbday = strtotime("$timeBday");
            $adopte_time = strtotime("$timeAdope");
            if(!$intLengthName){
                $status['update'] = 'emptyName';
                $this->ajaxReturn($status, 'JSON');
            }
            if(!$petType){
                $status['update'] = 'emptyType';
                $this->ajaxReturn($status, 'JSON');
            }
            if($intLengthName<2 || $intLengthName >20){
                $status['update'] = 'nameMax';
                $this->ajaxReturn($status, 'JSON');
            }

            $time = strtotime(date('Y-m-d', time()));
            if ($petbday > $time) {
                $status['update'] = 'bdayMax';
                $this->ajaxReturn($status, 'JSON');
            }
            if ($adopte_time > $time) {
                $status['update'] = 'adopteMax';
                $this->ajaxReturn($status, 'JSON');
            }

            session_start();
            if(!session('?code')){
                session('code',time());
            }
            if (time() - session('code') > 1) {
                $id = str_replace(" ", '', $this->_post("id"));
                $timeBday = $this->_post('textfield3');
                $timeAdope = $this->_post('textfield2');
                $data['id'] = $id;
                $data['uid'] = $uid;
                $data['petname'] = $this->_post('textfield1');
                $data['pettype'] = $this->_post('petId');
                $data['petgender'] = $this->_post('RadioGroup1');
                $data['weight'] = $this->_post('textfield4');
                $data['petbday'] = strtotime("$timeBday");
                $data['adopte_time'] = strtotime("$timeAdope");
                $data['lineages'] = $this->_post('RadioGroup2');
                $data['petstatus'] = $this->_post('select1');
                $data['spending'] = $this->_post('select2');
                $data['is_default'] = isset($_POST['checkbox']) ? 1 : 0;
                $statusEdit = D('UcPets')->editPets($data);
                if ($statusEdit) {
                    session('code',time());
                    $status['update'] = 'ok';
                    $status['id'] = $id;
                } else {
                    $status['update'] = 'false';
                }
            }else{
                $status['update'] = 'resubmit';
            }
        } else {
            $status['update'] = 'login';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //添加宠物 --宠物基本资料
    public function addPets()
    {
        // 判断请求地址 域名为boqii.com 并且 匹配session中的token
        $checkSafe = checkSafeForSns($_POST['token']);
        if(!$checkSafe){
            $this->ajaxReturn(array('update'=>'safe'),'JSON');
        }
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {

            $petName  = $this->_post('textfield1'); //自动去除 2边空格
            $petType = $this->_post('petId');
            $intLengthName = strlength_utf8($petName);
            $timeBday = $this->_post('textfield3');
            $timeAdope = $this->_post('textfield2');
            $petbday = strtotime("$timeBday");
            $adopte_time = strtotime("$timeAdope");
            if(!$intLengthName){
                $status['update'] = 'emptyName';
                $this->ajaxReturn($status, 'JSON');
            }
            if(!$petType){
                $status['update'] = 'emptyType';
                $this->ajaxReturn($status, 'JSON');
            }
            if($intLengthName<2 || $intLengthName >20){
                $status['update'] = 'nameMax';
                $this->ajaxReturn($status, 'JSON');
            }

            $time = strtotime(date('Y-m-d', time()));
            if ($petbday > $time) {
                $status['update'] = 'bdayMax';
                $this->ajaxReturn($status, 'JSON');
            }
            if ($adopte_time > $time) {
                $status['update'] = 'adopteMax';
                $this->ajaxReturn($status, 'JSON');
            }

            session_start();
            if(!session('?code')){
                session('code',time());
            }
            if (time() - session('code') > 1) {
                $intPetCnt = D('UcPets')->getPetCnt($uid); //获取用户当前的宠物数量
                if ($intPetCnt < 3) {
                    $timeBday = $this->_post('textfield3');
                    $timeAdope = $this->_post('textfield2');
                    $data['uid'] = $uid;
                    $data['petname'] = $this->_post('textfield1');
                    $data['pettype'] = $this->_post('petId');
                    $data['petgender'] = $this->_post('RadioGroup1');
                    $data['weight'] = $this->_post('textfield4');
                    $data['petbday'] = strtotime("$timeBday");
                    $data['adopte_time'] = strtotime("$timeAdope");
                    $data['lineages'] = $this->_post('RadioGroup2');
                    $data['petstatus'] = $this->_post('select1');
                    $data['spending'] = $this->_post('select2');
                    $data['is_default'] = isset($_POST['checkbox']) ? 1 : 0;
                    $statusAdd = D('UcPets')->addPets($data);
                    if ($statusAdd) {
                        session('code',time());
                        $status['update'] = 'ok';
                        $status['id'] = $statusAdd;
                    } else {
                        $status['update'] = 'false';
                    }
                } else {
                    $status['update'] = 'petMax';//添加失败
                }
            }else{
                $status['update'] = 'resubmit';
            }
        } else {
            $status['update'] = 'login';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //删除宠物 ---宠物头像页面
    public function  delPhotoPet()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {
            $data['id'] = str_replace(" ", '', $this->_get("id"));
            $data['uid'] = $uid;
            $currentId = str_replace(" ", '', $this->_get("currentId")); //当前基本资料的编辑id
            $boolStatus = D('UcPets')->getBoolRelation($data); //判断和是否存在关系
            if ($boolStatus) {
                $statusDel = D('UcPets')->deletePets($data);
                if ($statusDel) {
                    //判断删除的是否是当前基本资料的id
                    if ($currentId == $data['id']) {
                        $status = 'currentok'; //跳转到默认档案页
                        //                    $this->redirect('petFiles',array('uid'=>$uid));//跳转到默认档案页
                    } else {
                        $status = 'ok'; //刷新页面
                    }
                } else {
                    $status = 'false';
                }
            } else {
                $status = 'false';
            }
        } else {
            $status = 'login';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //编辑宠物--兴趣爱好
    public function addPetHoppy()
    {
        // 判断请求地址 域名为boqii.com 并且 匹配session中的token
        $checkSafe = checkSafeForSns($_POST['token']);
        if(!$checkSafe){
            $this->ajaxReturn(array('add'=>'safe'),'JSON');
        }
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {
            session_start();
            if(!session('?code')){
                session('code',time());
            }
            if (time() - session('code') > 1) {
                    $id = str_replace(" ", '', $this->_post("id"));
                    $condition['id'] = $id;
                    $condition['uid'] = $uid;
                    $boolStatus = D('UcPets')->getBoolRelation($condition); //判断和是否存在关系
                    if (!$boolStatus) {
                        $status = 'false';
                        $this->ajaxReturn($status, 'JSON');
                    }
                    $timeQu = $this->_post('calender1');
                    $timeMy = $this->_post('textfield7');
                    $data['id'] = $id;
                    $data['immune_time'] = strtotime($timeQu);
                    $data['repell_time'] = strtotime($timeMy);
                    $data['is_immnued'] = isset($_POST['checkbox1']) ? 1 : 0;
                    $data['is_repellend'] = isset($_POST['checkbox2']) ? 1 : 0;
                    $data['character'] = $this->_post('textfield1');
                    $data['foods'] = $this->_post('textfield2');
                    $data['toys'] = $this->_post('textfield3');
                    $data['specialty'] = $this->_post('textfield4');
                    $data['instructions'] = $this->_post('textfield5');
                    $statusAddHp = D('UcPets')->addPetHoppy($data);
                    if ($statusAddHp == 'true') {//提交成功
                        session('code',time());
                        $status['add'] = 'ok';
                        $status['id'] = $id;
                    }else if($statusAddHp == 'immuneMax'){
                        $status['add'] = 'immuneMax';
                    }else if($statusAddHp == 'repellMax'){
                        $status['add'] = 'repellMax';
                    }else if($statusAddHp == 'decMax'){
                        $status['add'] = 'decMax';
                    }else {
                        $status['add'] = 'false';
                    }
            }else{
                $status['add'] = 'resubmit';
            }
        } else {
            $status['add'] = 'login';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //删除宠物 ---宠物兴趣
    public function  delHobbyPet()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {
            $data['id'] = str_replace(" ", '', $this->_get("id"));
            $data['uid'] = $uid;
            $currentId = str_replace(" ", '', $this->_get("currentId")); //当前基本资料的编辑id
            $boolStatus = D('UcPets')->getBoolRelation($data); //判断和是否存在关系
            if ($boolStatus) {
                $statusDel = D('UcPets')->deletePets($data);
                if ($statusDel) {
                    //判断删除的是否是当前基本资料的id
                    if ($currentId == $data['id']) {
                        $status = 'currentok'; //跳转到默认档案页
//                            $this->redirect('petHobby',array('uid'=>$uid));//跳转到默认档案页
                    } else {
                        $status = 'ok'; //刷新页面
                    }

                } else {
                    $status = 'false';
                }
            } else {
                $status = 'false';
            }
        } else {
            $status = 'login';
        }
        $this->ajaxReturn($status, 'JSON');

    }

    //ajax--宠物头像修改
    public function updatePetPhoto()
    {
        // 判断请求地址 域名为boqii.com 并且 匹配session中的token
        $checkSafe = checkSafeForSns($_POST['token']);
        if(!$checkSafe){
            $this->ajaxReturn('safe','JSON');
        }
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {
            session_start();
            if(!session('?code')){
                session('code',time());
            }
            if (time() - session('code') > 1) {
            $data['picpath'] = $_POST['picpath'];
            $data['id'] = $_POST['pid'];
            $status = D('UcPets')->addPhoto($data);
                if($status == 'true') session('code',time());
            }else{
                $status = 'resubmit';
            }
        } else {
            $status = 'login';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //公共登录验证信息
    public function publicLogin()
    {
        header("Content-type:text/html;charset=utf-8");
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        if (!$uid) redirect(get_rewrite_url('User', 'login') . '?referer='.$currentUrl);
        return $uid;
    }

}

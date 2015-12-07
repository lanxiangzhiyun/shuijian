<?php
/**
 * 相册控制器 Action
 *
 * @author: zlg
 * @created: 12-11-14
 */
class UcAlbumAction extends BaseAction
{
    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 相册列表
     */
    public function photo()
    {
        $userinfo = $this->_user; //获取cookie 里的用户登录uid
        $uid = $userinfo['uid']; //获取cookie 里的用户登录uid
        $urlUid = $this->_get('uid'); //页面传过来的uid
        if ($uid) { //游客已登录
            if ($urlUid) { //页面传过来的urluid 不为空
                //我的关注
                if ($uid == $urlUid) {
                    $obj = 'me';
                    //他的关注
                } else {
                    $obj = 'other';
                    $boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
                    if ($boolUserExit) { //urluid用户存在
                        // Do  thing
                    } else { //urluid用户不存在
                        $this->getPage404();
                    }
                }
            } else { //页面传过来的urluid 为空
                $obj = 'me';
                $urlUid = $uid;
            }

        } else { //游客未登录
            $obj = 'other';
            if ($urlUid) { //页面传过来的urluid 不为空
                $boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
                if ($boolUserExit) { //urluid用户存在

                } else { //urluid用户不存在
                    $this->getPage404();
                }
            } else { //页面传过来的urluid 为空
                $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                redirect(get_rewrite_url('User', 'login') . '?referer='.$currentUrl); //login
            }

        }

        $currentUid = ($obj == 'me') ? $uid : $urlUid; //当前页面要查询的uid
        /********************************/
        if ($obj == 'me') {
            $pageP = intval($_GET['p']);
            $param['page'] = empty($pageP) ? 1 : $pageP;
            $param['page_num'] = 10;
            $param['uid'] = $currentUid; //当前页面要查询的uid
            $strSize = D('UcAlbum')->getAlbumSize($param); //当前 相册 容量 ☆☆☆☆☆☆☆☆☆☆☆
            $arrPetNameList = D('UcAlbum')->getPetCategoryMsg($param); //横向选择
            $intAlbumId = D('UcAlbum')->addDefaultAlbum($param); //检查是否有默认相册 ，没有则创建
            $arrPetMsg = D('UcPets')->getUserPetMsg($param); //获取 用户所有的 宠物 --select 框
            $nickname = D('UcUser')->getUserNickname($currentUid); //获取用户的昵称 seo
            $pageTitle = ($param['page'] > 1) ? '页，' . $param['page'] : '';
            $pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
            if (isset($_GET['petId']) && is_numeric($_GET['petId'])) {
                $param['petId'] = $this->_get('petId');
                $parampet['id'] = $param['petId'];
                $parampet['uid'] = $currentUid;
                $arrPetname = D('UcPets')->getBoolRelation($parampet); //获取宠物的昵称 seo
                $strPetClass = D('UcPets')->getPetClass($_GET['petId']);
                $strPetClassSeo = empty($strPetClass) ? '' : $strPetClass . '-';
                $arrAlbumList = D('UcAlbum')->getUserPetAlbumList($param); //取得指定用户宠物下的所有相册信息
                $this->assign('photoId', $_GET['petId']); //当前选中的 横向 背景色
                $mtaTitle = $arrPetname . '的图片列表-' . $strPetClassSeo . $pageTitle . '波奇网宠物家园、分享宠物的快乐生活';
                $mtaDescription = $arrPetname . '的图片列表，' . $pageDec . '记录' . $arrPetname . '的幸福生活照片，记录快乐的瞬间';
                $mtaKeywords = $arrPetname . '的相册列表';
            } else {
                $arrAlbumList = D('UcAlbum')->getUserAlbumList($param); //相册列表
                $mtaTitle = $nickname . '的相册 – ' . $pageTitle . '波奇网宠物家园、分享宠物的快乐生活';
                $mtaDescription = $nickname . '的相册，' . $pageDec . '记录您和您的宠物宝贝的幸福生活照片，记录快乐的瞬间';
                $mtaKeywords = $nickname . '的相册';
            }
            if (!$arrAlbumList && $param['page'] != 1) {
                $this->redirect('UcAlbum/photo', array('uid' => $currentUid));
            }
            $this->assign('arrAlbumList', $arrAlbumList);
            import("ORG.Page");
            if (isset($_GET['petId'])) {
                $Page = new Page(D('UcAlbum')->total, $param['page_num'], "UcAlbum,photo", $currentUid . ',' . $param['petId'],'');
            } else {
                $Page = new Page(D('UcAlbum')->total, $param['page_num'], "UcAlbum,photo", $currentUid,'');
            }
            $this->assign('mtaTitle', $mtaTitle); //title
            $this->assign('mtaDescription', $mtaDescription); //description
            $this->assign('mtaKeywords', $mtaKeywords); //keywords
            $this->assign('total', D('UcAlbum')->total);
            $this->assign('page', $Page->show());
            $this->assign('p', intval($_GET['p']));
            $this->assign('strSize', $strSize);
            $this->assign('arrPetMsg', $arrPetMsg);
            $this->assign('intAlbumId', $intAlbumId);
            $this->assign('uid', $currentUid);
            $this->assign('arrPetNameList', $arrPetNameList);
            $this->assign('location', 'myAlbum');
            $this->assign('obj', $obj);
            $this->display('myPhoto');
        } else {
            $param['page'] = intval($_GET['p']);
            $param['page_num'] = 10;
            $param['uid'] = $currentUid;
            $arrPetNameList = D('UcAlbum')->getPetCategoryMsg($param); //横向选择
            D('UcAlbum')->addDefaultAlbum($param); //检查是否有默认相册 ，没有则创建
            $arrPetMsg = D('UcPets')->getUserPetMsg($param); //获取 用户所有的 宠物 --select 框
            $strUserNickName = D('UcUser')->getUserNickname($currentUid);
            $pageTitle = ($param['page'] > 1) ? '页，' . $param['page'] : '';
            $pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
            if (isset($_GET['petId']) && is_numeric($_GET['petId'])) {
                $param['petId'] = str_replace(" ", '', $this->_get('petId'));
                $parampet['id'] = $param['petId'];
                $parampet['uid'] = $currentUid;
                $arrPetname = D('UcPets')->getBoolRelation($parampet); //获取宠物的昵称 seo
                $arrAlbumList = D('UcAlbum')->getUserPetAlbumList($param); //取得指定用户宠物下的所有相册信息
                $this->assign('photoId', $_GET['petId']); //当前选中的 横向 背景色
                $strPetClass = D('UcPets')->getPetClass($_GET['petId']);
                $strPetClassSeo = empty($strPetClass) ? '' : $strPetClass . '-'; //宠物种类
                $mtaTitle = $arrPetname . '的图片列表-' . $strPetClassSeo . $pageTitle . '- 波奇网宠物家园、分享宠物的快乐生活';
                $mtaDescription = $arrPetname . '的图片列表，' . $pageDec . '记录' . $arrPetname . '的幸福生活照片，记录快乐的瞬间';
                $mtaKeywords = $arrPetname . '的相册列表';
            } else {
                $arrAlbumList = D('UcAlbum')->getUserAlbumList($param); //相册列表
                $mtaTitle = $strUserNickName . '的相册' . $pageTitle . ' – 波奇网宠物家园、分享宠物的快乐生活';
                $mtaDescription = $strUserNickName . '的相册，' . $pageDec . '记录您和您的宠物宝贝的幸福生活照片，记录快乐的瞬间';
                $mtaKeywords = $strUserNickName . '的相册';
            }
            $this->assign('arrAlbumList', $arrAlbumList);
            import("ORG.Page");
            if (isset($_GET['petId'])) {
                $Page = new Page(D('UcAlbum')->total, $param['page_num'],"UcAlbum,photo", $currentUid . ',' . $param['petId'],'');
            } else {
                $Page = new Page(D('UcAlbum')->total, $param['page_num'],"UcAlbum,photo", $currentUid,'');
            }

            $this->assign('mtaTitle', $mtaTitle); //title
            $this->assign('mtaDescription', $mtaDescription); //description
            $this->assign('mtaKeywords', $mtaKeywords); //keywords
            $this->assign('total', D('UcAlbum')->total);
            $this->assign('page', $Page->show());
            $this->assign('p', intval($_GET['p']));
            $this->assign('arrPetMsg', $arrPetMsg);
            $this->assign('arrPetNameList', $arrPetNameList);
            $this->assign('strUserNickName', $strUserNickName);
            $this->assign('uid', $currentUid);
            $huser = $this->getUserInfo($currentUid);
            $this->assign("huser", $huser);
            $this->assign('location', 'otherAlbum');
            $this->assign('obj', $obj);
            $this->display('otherPhoto');
        }

    }

    /**
     * 照片 列表
     **/
    public function photolist()
    {
        header("Content-type:text/html;charset=utf-8");
        $userinfo = $this->_user; //获取cookie 里的用户登录uid
        $uid = $userinfo['uid']; //获取cookie 里的用户登录uid
        $param['aid'] = $aid = str_replace(" ", '', $this->_get('aid')); //相册 id
        $urlUid = D('UcAlbum')->getStatusAlbum($param); // 相册对应的用户urluid
        if ($uid) { //游客已登录
            if ($urlUid) { //相册对应的用户urluid 不为空
                //我的关注
                if ($uid == $urlUid) {
                    $obj = 'me';
                    //他的关注
                } else {
                    $obj = 'other';
                    $boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
                    if ($boolUserExit) { //urluid用户存在
                        // Do  thing
                    } else { //urluid用户不存在
                        $this->getPage404();
                    }
                }
            } else { //相册对应的用户urluid 为空
                $this->getPage404();
            }

        } else { //游客未登录
            $obj = 'other';
            if ($urlUid) { //相册对应的用户urluid 不为空
                $boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
                if ($boolUserExit) { //urluid用户存在

                } else { //urluid用户不存在
                    $this->getPage404();
                }
            } else { //相册对应的用户urluid 为空
                $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                redirect(get_rewrite_url('User', 'login') . '?referer='.$currentUrl); //login
            }

        }

        $currentUid = ($obj == 'me') ? $uid : $urlUid; //当前页面要查询的uid

        /********************************/
        if ($obj == 'me') { //我的页面
            $pageP = intval($_GET['p']);
            $data['page'] = empty($pageP) ? 1 : $pageP;
            $data['page_num'] = 20;
            $data['uid'] = $currentUid;
            if (!$aid) {
                $aid = D('UcAlbum')->getBoolDefault($data); //取默认相册  id by用户 uid
            }
            $data['albumId'] = $aid;
            $strAlbumName = D('UcAlbum')->getAlbumName($aid); //相册名称
            $arrAlbum = D('UcAlbum')->getAlbumInfo($aid);
            if ($arrAlbum) {
                $arrPet['id'] = $arrAlbum['pet_id'];
                $arrPet['uid'] = $uid;
                $strPetname = D('UcPets')->getBoolRelation($arrPet); //主人与宠物是否存在 关系
                if (!$strPetname) {
                    $arrAlbum['pet_id'] = '';
                }
            }
            $intDefault = D('UcAlbum')->getBoolDefaultByAid($aid); //是不是默认相册 by 相册 id
            if ($intDefault) {
                $this->assign('default', 1);
            }
            $arrPhotoMsg = D('UcAlbum')->getPhotoListOfAlbum($data); //照片列表页
            $arrPetMsg = D('UcPets')->getUserPetMsg($data); //获取 用户所有的 宠物 --select 框
            $arrSelectAlbum = D('UcAlbum')->getUserAllAlbum($data); //获取所有相册
            if (empty($arrPhotoMsg) && $data['page'] == 1) {
                $this->assign('flag', 1); //是否出现添加信息
            } else if (!$arrPhotoMsg && $data['page'] != 1) {
                $this->redirect('UcAlbum/photolist', array('aid' => $aid));
            } else {
                foreach ($arrPhotoMsg as $pk => $val) {
                    $arrPhotoMsg[$pk]['pname'] = mysubstr_utf8($val['pname'], 10); //最多显示 8个字
                    $path = $val['popath'];
                    $intLastPosition = strripos("$path", "_y");
                    if ($intLastPosition) {
                        $arrPhotoMsg[$pk]['bpath'] = substr_replace("$path", "_b", $intLastPosition, 2);
                        list($width, $height) = getallsizebymin($val['imagewidth'], $val['imagehigth'], 160, 160); //宽高处理
                        $arrPhotoMsg[$pk]['width'] = $width;
                        $arrPhotoMsg[$pk]['height'] = $height;
                    } else {
                        $arrPhotoMsg[$pk]['bpath'] = $path;
                        $arrPhotoMsg[$pk]['width'] = $val['imagewidth'];
                        $arrPhotoMsg[$pk]['height'] = $val['imagehigth'];
                    }

                }
            }
            import("ORG.Page");
            $Page = new Page(D('UcAlbum')->total, $data['page_num'], 'UcAlbum,photoList', $aid,'');
            $pageTitle = ($param['page'] > 1) ? '-页' . $param['page'] : '';
            $pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
            $mtaTitle = $strAlbumName . '的相册列表' . $pageTitle . ' –波奇网宠物家园、分享宠物的快乐生活';
            $mtaDescription = $strAlbumName . '的图片列表，' . $pageDec . '记录您和您的宠物宝贝的幸福生活照片，记录快乐的瞬间';
            $mtaKeywords = $strAlbumName . '的相册列表';
            $this->assign('mtaTitle', $mtaTitle); //title
            $this->assign('mtaDescription', $mtaDescription); //description
            $this->assign('mtaKeywords', $mtaKeywords); //keywords
            $this->assign('total', D('UcAlbum')->total);
            $this->assign('page', $Page->show());
            $this->assign('p', intval($_GET['p']));
            $this->assign('arrPhotoMsg', $arrPhotoMsg);
            $this->assign('strAlbumName', $strAlbumName);
            $this->assign('arrPetMsg', $arrPetMsg);
            $this->assign('arrAlbum', $arrAlbum);
            $this->assign('arrSelectAlbum', $arrSelectAlbum);
            $this->assign('aid', $aid);
            $this->assign('uid', $currentUid);
            $this->assign('location', 'myAlbum');
            $this->assign('obj', $obj);
            $this->display('myPhotoList');
        } else { //他人的 页面
            $intUid = D('UcAlbum')->getStatusAlbum($param);
            //前面做过判断，此时他人的uid'是存在的
            $data['page'] = intval($_GET['p']);
            $data['page_num'] = 20;
            $data['uid'] = $currentUid;
            $data['albumId'] = $aid;
            $strUserNickName = D('UcUser')->getUserNickname($currentUid);
            $strAlbumName = D('UcAlbum')->getAlbumName($aid); //相册名称
            $arrPhotoMsg = D('UcAlbum')->getPhotoListOfAlbum($data); //照片列表页
            if (empty($arrPhotoMsg)) {
                $this->assign('flag', 1); //是否出现添加信息 ---╮(╯_╰)╭TA还没有上传照片
            } else {
                foreach ($arrPhotoMsg as $pk => $val) {
                    $arrPhotoMsg[$pk]['pname'] = mysubstr_utf8($val['pname'], 10); //最多显示 8个字
                    $path = $val['popath'];
                    $intLastPosition = strripos("$path", "_y");
                    if ($intLastPosition) {
                        $arrPhotoMsg[$pk]['bpath'] = substr_replace("$path", "_b", $intLastPosition, 2);
                        list($width, $height) = getallsizebymin($val['imagewidth'], $val['imagehigth'], 160, 160); //宽高处理
                        $arrPhotoMsg[$pk]['width'] = $width;
                        $arrPhotoMsg[$pk]['height'] = $height;
                    } else {
                        $arrPhotoMsg[$pk]['bpath'] = $path;
                        $arrPhotoMsg[$pk]['width'] = $val['imagewidth'];
                        $arrPhotoMsg[$pk]['height'] = $val['imagehigth'];
                    }
                }
            }
            import("ORG.Page");
            $Page = new Page(D('UcAlbum')->total, $data['page_num'], 'UcAlbum,photoList', $aid,'');
            $pageTitle = ($param['page'] > 1) ? '-页' . $param['page'] : '';
            $pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
            $mtaTitle = $strAlbumName . '的相册列表 ' . $pageTitle . '– 波奇网宠物家园、分享宠物的快乐生活';
            $mtaDescription = $strAlbumName . '的图片列表，' . $pageDec . '记录您和您的宠物宝贝的幸福生活照片，记录快乐的瞬间';
            $mtaKeywords = $strAlbumName . '的相册列表';
            $this->assign('mtaTitle', $mtaTitle); //title
            $this->assign('mtaDescription', $mtaDescription); //description
            $this->assign('mtaKeywords', $mtaKeywords); //keywords
            $this->assign('total', D('UcAlbum')->total);
            $this->assign('page', $Page->show());
            $this->assign('p', intval($_GET['p']));
            $this->assign('arrPhotoMsg', $arrPhotoMsg);
            $this->assign('strUserNickName', $strUserNickName);
            $this->assign('strAlbumName', $strAlbumName);
            $this->assign('aid', $aid);
            $this->assign('uid', $currentUid);
            $huser = $this->getUserInfo($currentUid);
            $this->assign("huser", $huser);
            $this->assign('location', 'otherAlbum');
            $this->assign('obj', $obj);
            $this->display('otherPhotoList');
        }

    }

    //我的照片详情页
    public function photoshow()
    {
        header("Content-type:text/html;charset=utf-8");
        $userinfo = $this->_user; //获取cookie 里的用户登录uid
        $uid = $userinfo['uid']; //获取cookie 里的用户登录uid
        $date['pid'] = $pid = str_replace(" ", '', $_GET['pid']); //照片id
        $param['aid'] = $intAid = D('UcAlbum')->getStatusPhoto($date); //照片是否存在
        $urlUid = D('UcAlbum')->getStatusAlbum($param); //照片对应的用户uid
        if ($uid) { //游客已登录
            if ($intAid) { //照片 存在
                if ($urlUid) { //相册 存在
                    if ($urlUid == $uid) { //相册 与 用户的 关系
                        $obj = 'me';
                    } else { //此相册不属于该登录用户
                        $obj = 'other';
                        $boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
                        if ($boolUserExit) { //urluid用户存在
                            // Do  thing
                        } else { //urluid用户不存在
                            $this->getPage404();
                        }
                    }
                } else { //相册 不存在
                    $this->getPage404(); //404 页面
                }
            } else { //照片 不存在
                $this->getPage404(); //404 页面
            }

        } else { //游客未登录
            $obj = 'other';
            if ($urlUid) { //相册对应的用户urluid 不为空
                $boolUserExit = D('UcUser')->getBoolUserExist($urlUid); //判断urluid 是否存在这个用户
                if ($boolUserExit) { //urluid用户存在

                } else { //urluid用户不存在
                    $this->getPage404();
                }
            } else { //相册对应的用户urluid 为空
                $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                redirect(get_rewrite_url('User', 'login') . '?referer='.$currentUrl); //login
            }

        }

        $currentUid = ($obj == 'me') ? $uid : $urlUid; //当前页面要查询的uid

        /********************************/
        if ($obj == 'me') {
            $param['uid'] = $uid;
            $arrPhotoList = D('UcAlbum')->getAlbumAllPhotoMsg($param); //当前相册照片list
            //当前选中照片
            if ($pid) {
                $currentPid = $pid;
                $this->assign('currentPid', $currentPid); //--当前选中照片
                //获取上一张 下一张照片id
                $intPhotoList = count($arrPhotoList);
                if(!empty($intPhotoList)){
                    $intLastKey = count($arrPhotoList)-1;
                    foreach($arrPhotoList as $key=>$val) {
                            if($val['photo_id'] == $currentPid) {
                                if($key == 0) {
                                    $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                                }elseif($key  == $intLastKey) {
                                    $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                                } else {
                                    $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                                    $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                                }
                            }
                    }
                }
            } else {
                $currentPid = $arrPhotoList[0]['photo_id'];
                $this->assign('currentPid', $currentPid); //没有--则默认第一张
                //获取上一张 下一张照片id
                $intPhotoList = count($arrPhotoList);
                if(!empty($intPhotoList)){
                    $intLastKey = count($arrPhotoList)-1;
                    foreach($arrPhotoList as $key=>$val) {
                        if($val['photo_id'] == $currentPid) {
                            if($key == 0) {
                                $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                            }elseif($key  == $intLastKey) {
                                $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                            } else {
                                $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                                $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                            }
                        }
                    }
                }
            }
            $arrCurrentPhoto = D('UcAlbum')->getPhotoInfo($currentPid); //获取 当前 照片 的 信息
            $nickname = D('UcUser')->getUserNickname($currentUid); //获取用户的昵称
            $avatar = D('UcUser')->getHeadPhoto($currentUid); //获取用户的头像
            $pageP = intval($_GET['p']);
            $param['page'] = empty($pageP) ? 1 : $pageP;
            $param['page_num'] = 20;
            $param['photoId'] = $currentPid;
            $strAlbumName = D('UcAlbum')->getAlbumName($intAid); //相册名称
            $arrCommentInfo = D('UcAlbum')->getCommentInfo($param); //评论列表
            if (!$arrCommentInfo && $param['page'] != 1) {
                $this->redirect('UcAlbum/photoshow', array('pid' => $pid));
            }
            import("ORG.Page");
            $Page = new Page(D('UcAlbum')->total, $param['page_num'], 'UcAlbum,photoshow', $currentPid,'');
            $pageTitle = ($param['page'] > 1) ? '页，' . $param['page'] : '';
            $pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
            $boolPetid = D('UcAlbum')->getBoolPetByAlbumId($intAid); //照片对应的宠物id 是否存在
            if ($boolPetid) {
                $strPetClass = D('UcPets')->getPetClass($boolPetid);
            } else {
                $strPetClass = '';
            }
//            $strPetClassSeo = empty($strPetClass) ? ''  : $strPetClass.'-';//宠物种类
            $mtaTitle = empty($strPetClass) ? ($strAlbumName . '–' . $arrCurrentPhoto['photo_name'] . '– 图片|照片 ' . $pageTitle . '– 波奇网宠物家园、分享宠物的快乐生活 ')
                : ($strPetClass . '–' . $arrCurrentPhoto['photo_name'] . '– 图片|照片 ' . $pageTitle . '– 波奇网宠物家园、分享宠物的快乐生活 ');
            $mtaDescription = empty($strPetClass) ? ($strAlbumName . '的' . $arrCurrentPhoto['photo_name'] . '图片，' . $pageDec . '分享你的宠物图片，分享你的快乐')
                : ($strPetClass . '的' . $arrCurrentPhoto['photo_name'] . '图片，' . $pageDec . '分享你的宠物图片，分享你的快乐');
            $mtaKeywords = empty($strPetClass) ? ($strAlbumName . '-' . $arrCurrentPhoto['photo_name'] . '图片')
                : ($strPetClass . '-' . $arrCurrentPhoto['photo_name'] . '图片');
            // 页面上的 rid 赋值
            if ($_GET['rid']) {
                $rid = intval($_GET['rid']);
                $ruid = D('UcAlbum')->getStatusComment(array('cid' => $rid));
                $rnicename = D('UcUser')->getUserNickname($ruid);
            }
            //分享图片地址
            if ($currentPid) {
                $shareUrl =  C('BLOG_DIR').'/photo/'.$currentPid.'.html';
            } else{
                $shareUrl =  C('BLOG_DIR').'/user/images/logo1.jpg';
            }

            $this->assign('intPrePid', $intPrePid);
            $this->assign('intLastPid', $intLastPid);
            $this->assign('shareUrl',$shareUrl);
            $this->assign('rid', $rid);
            $this->assign('ruid',$ruid);
            $this->assign('rnicename', $rnicename);
            $this->assign('mtaTitle', $mtaTitle); //title
            $this->assign('mtaDescription', $mtaDescription); //description
            $this->assign('mtaKeywords', $mtaKeywords); //keywords
            $this->assign('total', D('UcAlbum')->total);
            $this->assign('page', $Page->show());
            $this->assign('p', intval($_GET['p']));
            $this->assign('aid', $intAid);
            $this->assign('uid', $currentUid);
            $this->assign('arrCurrentPhoto', $arrCurrentPhoto);
            $this->assign('nickname', $nickname);
            $this->assign('avatar', $avatar);
            $this->assign('strAlbumName', $strAlbumName);
            $this->assign('arrPhotoList', $arrPhotoList);
            $this->assign('arrCommentInfo', $arrCommentInfo);
            $this->assign('location', 'myAlbum');
            $this->assign('obj', $obj);
            $this->display('myPhotoShow');
        } else {
            $data['uid'] = $currentUid;
            $arrPhotoList = D('UcAlbum')->getAlbumAllPhotoMsg($param); //当前相册照片list
            //当前选中照片
            if ($pid) {
                $currentPid = $pid;
                $this->assign('currentPid', $currentPid); //--当前选中照片
                //获取上一张 下一张照片id
                $intPhotoList = count($arrPhotoList);
                if(!empty($intPhotoList)){
                    $intLastKey = count($arrPhotoList)-1;
                    foreach($arrPhotoList as $key=>$val) {
                        if($val['photo_id'] == $currentPid) {
                            if($key == 0) {
                                $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                            }elseif($key  == $intLastKey) {
                                $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                            } else {
                                $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                                $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                            }
                        }
                    }
                }
            } else {
                $currentPid = $arrPhotoList[0]['photo_id'];
                $this->assign('currentPid', $currentPid); //没有选中照片--则默认第一张
                //获取上一张 下一张照片id
                $intPhotoList = count($arrPhotoList);
                if(!empty($intPhotoList)){
                    $intLastKey = count($arrPhotoList)-1;
                    foreach($arrPhotoList as $key=>$val) {
                        if($val['photo_id'] == $currentPid) {
                            if($key == 0) {
                                $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                            }elseif($key  == $intLastKey) {
                                $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                            } else {
                                $intPrePid = $arrPhotoList[$key-1]['photo_id'];//上一张
                                $intLastPid = $arrPhotoList[$key+1]['photo_id'];//下一张
                            }
                        }
                    }
                }
            }
            $arrCurrentPhoto = D('UcAlbum')->getPhotoInfo($currentPid); //获取 当前 照片 的 信息
            $nickname = empty($uid) ? '' : (D('UcUser')->getUserNickname($uid)); //获取登录用户的昵称
            $avatar = empty($uid) ? '' : (D('UcUser')->getHeadPhoto($uid)); //获取登录用户的头像
            M('uc_photo')->where("photo_id='$currentPid'")->setInc('views'); //当前照片浏览数 +1
            $strAlbumName = D('UcAlbum')->getAlbumName($intAid); //相册名称
            $strUserNickName = D('UcUser')->getUserNickname($currentUid); //他的昵称
            $pageP = intval($_GET['p']);
            $param['page'] = empty($pageP) ? 1 : $pageP;
            $param['page_num'] = 20;
            $param['photoId'] = $currentPid;
            $arrCommentInfo = D('UcAlbum')->getCommentInfo($param); //评论列表
            if (!$arrCommentInfo && $param['page'] != 1) {
                $this->redirect('UcAlbum/photoshow', array('pid' => $pid));
            }
            import("ORG.Page");
            $Page = new Page(D('UcAlbum')->total, $param['page_num'], 'UcAlbum,photoshow', $currentPid,'');
            $pageTitle = ($param['page'] > 1) ? '-页' . $param['page'] : '';
            $pageDec = ($param['page'] > 1) ? '第' . $param['page'] . '页，' : '';
            $boolPetid = D('UcAlbum')->getBoolPetByAlbumId($intAid); //照片对应的宠物id 是否存在
            if ($boolPetid) {
                $strPetClass = D('UcPets')->getPetClass($boolPetid);
            } else {
                $strPetClass = '';
            }
            $strPetClass = D('UcPets')->getPetClass($boolPetid);
//            $strPetClassSeo = empty($strPetClass) ? ''  : $strPetClass.'-';//宠物种类
            $mtaTitle = empty($strPetClass) ? ($strAlbumName . '–' . $arrCurrentPhoto['photo_name'] . '– 图片|照片 ' . $pageTitle . '– 波奇网宠物家园、分享宠物的快乐生活 ')
                : ($strPetClass . '–' . $arrCurrentPhoto['photo_name'] . '– 图片|照片 ' . $pageTitle . '– 波奇网宠物家园、分享宠物的快乐生活 ');
            $mtaDescription = empty($strPetClass) ? ($strAlbumName . '的' . $arrCurrentPhoto['photo_name'] . '图片，' . $pageDec . '分享你的宠物图片，分享你的快乐')
                : ($strPetClass . '的' . $arrCurrentPhoto['photo_name'] . '图片，' . $pageDec . '分享你的宠物图片，分享你的快乐');
            $mtaKeywords = empty($strPetClass) ? ($strAlbumName . '-' . $arrCurrentPhoto['photo_name'] . '图片')
                : ($strPetClass . '-' . $arrCurrentPhoto['photo_name'] . '图片');
            if ($_GET['rid']) {
                $rid = intval($_GET['rid']);
                $ruid = D('UcAlbum')->getStatusComment(array('cid' => $rid));
                $rnicename = D('UcUser')->getUserNickname($ruid);
            }
            //分享图片地址
            if ($currentPid) {
                $shareUrl =  C('BLOG_DIR').'/photo/'.$currentPid.'.html';
            } else{
                $shareUrl =  C('BLOG_DIR').'/user/images/logo1.jpg';
            }

            $this->assign('intPrePid', $intPrePid);
            $this->assign('intLastPid', $intLastPid);
            $this->assign('rid', $rid);
            $this->assign('ruid',$ruid);
            $this->assign('rnicename', $rnicename);
            $this->assign('shareUrl',$shareUrl);
            $this->assign('mtaTitle', $mtaTitle); //title
            $this->assign('mtaDescription', $mtaDescription); //description
            $this->assign('mtaKeywords', $mtaKeywords); //keywords
            $this->assign('total', D('UcAlbum')->total);
            $this->assign('page', $Page->show());
            $this->assign('p', intval($_GET['p']));
            $this->assign('aid', $intAid);
            $this->assign('uid', $currentUid);
            $this->assign('loginUid',$uid);
            $this->assign('nickname', $nickname);
            $this->assign('avatar', $avatar);
            $this->assign('arrCurrentPhoto', $arrCurrentPhoto);
            $this->assign('strAlbumName', $strAlbumName);
            $this->assign('arrPhotoList', $arrPhotoList);
            $this->assign('arrCommentInfo', $arrCommentInfo);
            $this->assign('strUserNickName', $strUserNickName);
            $huser = $this->getUserInfo($currentUid);
            $this->assign("huser", $huser);
            $this->assign('location', 'otherAlbum');
            $this->assign('obj', 'other');
            $this->display('otherPhotoShow');
        }


    }

    //照片编辑页
    public function editPhoto()
    {
        $uid = $this->publicLogin();
        $postId = $postId = str_replace(" ", '', $this->_get('pid'));
        $param['pid'] = explode(',', $postId);
        foreach ($param['pid'] as $val) {
            $date['pid'] = '';
            $date['pid'] = $val;
            $param['aid'] = $intAid = D('UcAlbum')->getStatusPhoto($date); //照片是否存在
            if ($intAid) { //照片 存在
                $intUid = D('UcAlbum')->getStatusAlbum($param);
                if ($intUid) { //相册 存在
                    if ($intUid != $uid) { //相册 与 用户的 关系
                        $this->getPage404($uid); //跳转 404
                    }
                } else { //相册 不存在
                    $this->getPage404($uid); //跳转 404
                }
            } else { //照片 不存在
                $this->getPage404($uid); //跳转 404
            }
        }

        $arrPhotoBaseMsg = D('UcAlbum')->getPhotoBaseMsg($param);
        $this->assign('arrPhotoBaseMsg', $arrPhotoBaseMsg);
        $this->assign('uid', $uid);
        $this->assign('location', 'myAlbum');
        $this->assign('obj', 'me');
        $this->display('editPhoto');
    }

    //上传照片页
    public function uploadPhoto()
    {
        $uid = $this->publicLogin();
        $aid = $param['aid'] = $_GET['aid']; //相册  id
        $data['uid'] = $uid;
        if ($aid) {
            $intUid = D('UcAlbum')->getStatusAlbum($param); //相册 存在 不存在（删除）
            if ($intUid) { //相册 存在
                if ($intUid != $uid) {
                    $this->getPage404($uid); //404
                }
            } else {
                $this->getPage404($uid); //404
            }
        } else {
            $this->getPage404($uid); //404
        }

        $arrSelectAlbum = D('UcAlbum')->getUserAllAlbum($data); //获取所有相册
        $arrPetMsg = D('UcPets')->getUserPetMsg($data); //获取 用户所有的 宠物 --select 框
        $strSize = D('UcAlbum')->getAlbumSize($data); //当前 相册 容量
        $this->assign('arrSelectAlbum', $arrSelectAlbum);
        $this->assign('strSize', $strSize);
        $this->assign('arrPetMsg', $arrPetMsg);
        $this->assign('aid', $aid);
        $this->assign('uid', $uid);
        $this->assign('location', 'myAlbum');
        $this->assign('obj', 'me');
        $this->display('uploadPhoto');
    }

    //添加相册信息  --- 相册列表页 （返回相册 创建成功 or 失败）
    public function addAlbumInfo()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $param['uid'] = $uid;
        if ($uid) {
            $param['title'] = $_POST['textfield2'];
            $param['title'] = $param['title'] == '请输入相册信息' ? '' : $param['title'];
            $isEmptyTitle = str_replace(" ", '', $param['title']);
            $param['decription'] = $_POST['textarea'];
            $isEmptyDec = str_replace(" ", '', $param['decription']);
            $intTitleLength = strlength_utf8(trim($param['title']));
            $intDescLength = strlength_utf8(trim($param['decription']));
            if (!$isEmptyTitle) {
                $status['add'] = 'titlemin'; //添加失败--不能为空
                $status['msg'] = '相册名称不能为空';
            } else if ($intTitleLength > 30) {
                $status['add'] = 'titlemax'; //添加失败--超过最大字数
                $status['msg'] = '相册名称已超出最大限制字数。';
            } else if (!$isEmptyDec && $intDescLength > 200) {
                $status['add'] = 'decmax'; //添加失败--超过最大字数
                $status['msg'] = '相册描述已超出最大限制字数。';
            } else {
                $param['petId'] = $this->_post('select2');
                $intStatus = D('UcAlbum')->addAlbumInfo($param);
                if ($intStatus) {
                    $data['uid'] = $uid;
                    $data['type'] = 2; //相册动态
                    $data['operatetype'] = 1; // 创建
                    $data['oid'] = $intStatus; //主体相册 id
                    $data['otitle'] = empty($param['title']) ? '' : $param['title']; //主体相册 名称
                    D('UcIndex')->addDynamic($data); //修改相册 生成 动态
                    $status['add'] = 'ok'; //添加成功
                    $status['aid'] = $intStatus;
                } else {
                    $status['add'] = 'false'; //添加失败
                }
            }
        } else {
            $status['add'] = 'login'; //未登录
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //添加相册信息  --- 照片列表页 转移操作 (返回相册 主键 id)
    public function addAlbumForPhoto()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $param['uid'] = $uid;

        $param['title'] = $_POST['textfield2'];
        $param['title'] = $param['title'] == '请输入相册信息' ? '' : $param['title'];
        $isEmptyTitle = str_replace(" ", '', $param['title']);
        $param['decription'] = $_POST['textarea'];
        $isEmptyDec = str_replace(" ", '', $param['decription']);
        $intTitleLength = strlength_utf8(trim($param['title']));
        $intDescLength = strlength_utf8(trim($param['decription']));

        if (!$isEmptyTitle) {
            $status['add'] = 'titlemin'; //添加失败--不能为空
            $status['msg'] = '相册名称不能为空';
        } else if ($intTitleLength > 30) {
            $status['add'] = 'titlemax'; //添加失败--超过最大字数
            $status['msg'] = '相册名称已超出最大限制字数。';
        } else if (!$isEmptyDec && $intDescLength > 200) {
            $status['add'] = 'decmax'; //添加失败--超过最大字数
            $status['msg'] = '相册描述已超出最大限制字数。';
        } else {
            $param['petId'] = $this->_post('select2');
            $statusBool = D('UcAlbum')->addAlbumInfo($param); //返回相册 主键 id
            if ($statusBool) {
                $status['add'] = $statusBool;

                $data['uid'] = $uid;
                $data['type'] = 2; //相册动态
                $data['operatetype'] = 1; // 创建
                $data['oid'] = $statusBool; //主体相册 id
                $data['otitle'] = empty($param['title']) ? '' : $param['title']; //主体相册 名称
                D('UcIndex')->addDynamic($data); //修改相册 生成 动态
            } else {
                $status['add'] = 'false';
            }
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //ajax 获取 相册名称、描述 、用户的宠物(暂时没用到--Gavin)
    public function getAlbumMsg()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $intAlbumId = $this->_post('AlbumId');
        $arrAlbum = D('UcAlbum')->getAlbumInfo($intAlbumId);
        $param['uid'] = $uid;
        $arrPetMsg = D('UcPets')->getUserPetMsg($param); //获取 用户所有的 宠物
        $data = array(
            "arrAlbum" => $arrAlbum,
            "arrPetMsg" => $arrPetMsg
        );
        $this->ajaxReturn($data, 'JSON');
    }

    //编辑相册 信息
    public function updateAlbumMsg()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $param['id'] = $data['aid'] = $this->_post('aid');
        if ($param['id']) {
            $intUid = D('UcAlbum')->getStatusAlbum($data);
            if ($intUid) { //相册 存在
                if ($intUid != $uid) { //相册 与 用户的 关系
                    $status['add'] = 'false';
                    $this->ajaxReturn($status, 'JSON');
                }
            } else { //相册 不存在
                $status['add'] = 'false';
                $this->ajaxReturn($status, 'JSON');
            }
        } else { //参数错误
            $status['add'] = 'false';
            $this->ajaxReturn($status, 'JSON');
        }
        $intDefault = D('UcAlbum')->getBoolDefaultByAid($data['aid']); //是不是默认相册 by 相册 id
        if ($intDefault) {
            $status['add'] = 'false';
        } else {
            $param['title'] = $_POST['title'];
            $param['title'] = $param['title'] == '请输入相册信息' ? '' : $param['title'];
            $isEmptyTitle = str_replace(" ", '', $param['title']);
            $param['decription'] = $_POST['decription'];
            $param['petId'] = $this->_post('petId');
            $isEmptyDec = str_replace(" ", '', $param['decription']);
            $intTitleLength = strlength_utf8(trim($param['title']));
            $intDescLength = strlength_utf8(trim($param['decription']));

            if (!$isEmptyTitle) {
                $status['add'] = 'titlemin'; //添加失败--不能为空
                $status['msg'] = '相册名称不能为空';
            } else if ($intTitleLength > 30) {
                $status['add'] = 'titlemax'; //添加失败--超过最大字数
                $status['msg'] = '相册名称已超出最大限制字数。';
            } else if (!$isEmptyDec && $intDescLength > 200) {
                $status['add'] = 'decmax'; //添加失败--超过最大字数
                $status['msg'] = '相册描述已超出最大限制字数。';
            } else {
                $statusBool = D('UcAlbum')->updateAlbumInfo($param);
                if ($statusBool == 'true') {
                    $status['add'] = 'true';
                    $data['uid'] = $uid;
                    $data['type'] = 2; //相册动态
                    $data['operatetype'] = 2; //修改
                    $data['oid'] = $param['id']; //主体相册 id
                    $data['otitle'] = ''; //主体相册 名称
                    D('UcIndex')->addDynamic($data); //修改相册 生成 动态
                } else {
                    $status['add'] = 'false';
                }
            }
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //删除相册
    public function delAlbumById()
    {
        $userinfo = $this->_user;
        $uid = $param['uid'] = $userinfo['uid'];
        $albumId = $param['aid'] = str_replace(" ", '', $this->_get("aid")); //相册 id
        if ($albumId) {
            $intUid = D('UcAlbum')->getStatusAlbum($param); //相册 存在 不存在（删除）
            if ($intUid) { //相册 存在
                if ($intUid != $uid) {
                    $status = 'false'; //删除失败
                    $this->ajaxReturn($status, 'JSON');
                }
            } else {
                $status = 'false'; //删除失败
                $this->ajaxReturn($status, 'JSON');
            }
        } else {
            $status = 'false'; //删除失败
            $this->ajaxReturn($status, 'JSON');
        }

        $intDefault = D('UcAlbum')->getBoolDefaultByAid($albumId); //是不是默认相册 by 相册 id
        if ($intDefault) {
            $status = 'false'; //删除失败
        } else {
            $status = D('UcAlbum')->deleteAlbumInfo($param);
            if ($status) {
                $status = 'ok'; //删除成功
            } else {
                $status = 'false'; //删除失败
            }
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //ajax --转移照片
    public function changePhotoAlbum()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $postId = str_replace(" ", '', $this->_post('pid'));
        $param['albumId'] = $param['aid'] = $_POST['aid'];
        $arrPid = explode(',', $postId);
        if ($param['aid']) {
            foreach ($arrPid as $val) {
                $date['pid'] = '';
                $date['pid'] = $val;
                $intAid = D('UcAlbum')->getStatusPhoto($date); //照片是否存在
                if ($intAid) { //照片 存在
                    $intUid = D('UcAlbum')->getStatusAlbum($param);
                    if ($intUid) { //相册 存在
                        if ($intUid != $uid) { //相册 与 用户的 关系
                            $status['add'] = 'false5';
                            $this->ajaxReturn($status, 'JSON');
                        }
                    } else { //相册 不存在
                        $status['add'] = 'false4';
                        $this->ajaxReturn($status, 'JSON');
                    }
                } else { //照片 不存在
                    $status['add'] = 'false3';
                    $this->ajaxReturn($status, 'JSON');
                }
            }
        } else {
            $status['add'] = 'false2';
            $this->ajaxReturn($status, 'JSON');
        }

        $statusBool = D('UcAlbum')->changePhotoAlbumPl($arrPid, $uid, $param['albumId']); //true  成功 false  失败
        if ($statusBool) {
            $status['add'] = 'true';
        } else {
            $status['add'] = 'false1';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //ajax 删除照片
    public function delPhoto()
    {
        $userinfo = $this->_user;
        $uid = $arrDel['uid'] = $userinfo['uid'];
        $postId = str_replace(" ", '', $this->_post('pid'));
        $arrPid = explode(',', $postId);
        foreach ($arrPid as $val) {
            $date['pid'] = '';
            $date['pid'] = $val;
            $param['aid'] = $intAid = D('UcAlbum')->getStatusPhoto($date); //照片是否存在
            if ($intAid) { //照片 存在
                $intUid = D('UcAlbum')->getStatusAlbum($param);
                if ($intUid) { //相册 存在
                    if ($intUid != $uid) { //相册 与 用户的 关系
                        $status = 'false';
                        $this->ajaxReturn($status, 'JSON');
                    }
                } else { //相册 不存在
                    $status = 'false';
                    $this->ajaxReturn($status, 'JSON');
                }
            } else { //照片 不存在
                $status = 'false';
                $this->ajaxReturn($status, 'JSON');
            }
        }
        $status = D('UcAlbum')->delPhoto($arrPid, $arrDel);
        if ($status) {
            $status = 'true';
        } else {
            $status = 'false';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //修改照片页---ajax 删除单张照片
    public function delSinglePhoto()
    {
        $userinfo = $this->_user;
        $uid = $param['uid'] = $userinfo['uid'];
        $photoId = $param['id'] = $this->_post('pid');
        if ($photoId) {
            $date['pid'] = $photoId;
            $param['aid'] = $intAid = D('UcAlbum')->getStatusPhoto($date); //照片是否存在
            if ($intAid) { //照片 存在
                $intUid = D('UcAlbum')->getStatusAlbum($param);
                if ($intUid) { //相册 存在
                    if ($intUid != $uid) { //相册 与 用户的 关系
                        $status = 'false';
                        $this->ajaxReturn($status, 'JSON');
                    }
                } else { //相册 不存在
                    $status = 'false';
                    $this->ajaxReturn($status, 'JSON');
                }
            } else { //照片 不存在
                $status = 'false';
                $this->ajaxReturn($status, 'JSON');
            }
        } else { //传值为空
            $status = 'false';
            $this->ajaxReturn($status, 'JSON');
        }

        $status = D('UcAlbum')->deletePhotoInfo($param, 0);
        if ($status) {
            $status = 'true';
        } else {
            $status = 'false';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //修改照片页--ajax设置封面
    public function setAlbumFace()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $photoId = $this->_post('pid');
        if ($photoId) {
            $date['pid'] = $photoId;
            $param['aid'] = $intAid = D('UcAlbum')->getStatusPhoto($date); //照片是否存在
            if ($intAid) { //照片 存在
                $intUid = D('UcAlbum')->getStatusAlbum($param);
                if ($intUid) { //相册 存在
                    if ($intUid != $uid) { //相册 与 用户的 关系
                        $status = 'false';
                        $this->ajaxReturn($status, 'JSON');
                    }
                } else { //相册 不存在
                    $status = 'false';
                    $this->ajaxReturn($status, 'JSON');
                }
            } else { //照片 不存在
                $status = 'false';
                $this->ajaxReturn($status, 'JSON');
            }
        } else { //传值为空
            $status = 'false';
            $this->ajaxReturn($status, 'JSON');
        }

        $status = D('UcAlbum')->setPhotoCover($photoId);
        if ($status) {
            $status = 'true';
        } else {
            $status = 'false';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //批量修改照片信息
    public function updatePhotoPl()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        if ($uid) {
            $arrPhotoName = str_replace(" ", '', $this->_post('textfield3'));
            $arrPhotoDec = str_replace(" ", '', $this->_post('textarea'));
            $arrPhotoId = str_replace(" ", '', $this->_post('pid'));

            foreach ($arrPhotoId as $val) {
                $date['pid'] = '';
                $date['pid'] = $val;
                $intAid = $param['aid'] = D('UcAlbum')->getStatusPhoto($date); //照片是否存在
                if ($intAid) { //照片 存在
                    $intUid = D('UcAlbum')->getStatusAlbum($param);
                    if ($intUid) { //相册 存在
                        if ($intUid != $uid) { //相册 与 用户的 关系
                            $status = 'false';
                            $this->ajaxReturn($status, 'JSON');
                        }
                    } else { //相册 不存在
                        $status = 'false';
                        $this->ajaxReturn($status, 'JSON');
                    }
                } else { //照片 不存在
                    $status = 'false';
                    $this->ajaxReturn($status, 'JSON');
                }
            }
            $arrPhotoMsg = array();
            foreach ($arrPhotoName as $pk => $val) {
                $arrPhotoMsg[] = array('photoId' => $arrPhotoId[$pk], 'pname' => $arrPhotoName[$pk], 'pdec' => $arrPhotoDec[$pk]);
            }
            $status = D('UcAlbum')->batchUpdatePhotos($arrPhotoMsg);
            if ($status) {
                $status = 'ok';
            } else {
                $status = 'false';
            }
        } else {
            $status = 'login'; //未登录
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //批量添加照片信息
    public function addPhotoPl()
    {
        $userinfo = $this->_user;
        ;
        $uid = $userinfo['uid'];
        if ($uid) {
            $arrPhotoWidth = $this->_post('width');
            $arrPhotoHeight = $this->_post('height');
            $arrPhotoSize = str_replace(" ", '', $this->_post('size'));
            $arrPhotoName = str_replace(" ", '', $this->_post('names'));
            $arrPhotoDec = str_replace(" ", '', $this->_post('descs'));
            $strPhotoIsCover = $this->_post('covers');
            $arrPhotoIsPath = $this->_post('path');
            $arrPhotoAid = $this->_post('aid'); //相册 id
            $param['aid'] = $arrPhotoAid[0];
            $intUid = D('UcAlbum')->getStatusAlbum($param); //相册 存在 不存在（删除）
            if ($intUid) { //存在
                if ($intUid != $uid) {
                    $status = 'false';
                    $this->ajaxReturn($status, 'JSON');
                }
            } else {
                $status = 'false';
                $this->ajaxReturn($status, 'JSON');
            }
            foreach ($arrPhotoName as $pk => $val) {
                $arrPhotoMsg[] = array(
                    'album_id' => $arrPhotoAid[$pk],
                    'uid' => $uid,
                    'imagewidth' => $arrPhotoWidth[$pk],
                    'imagehigth' => $arrPhotoHeight[$pk],
                    'size' => $arrPhotoSize[$pk],
                    'photo_path' => $arrPhotoIsPath[$pk],
                    'photo_name' => $arrPhotoName[$pk],
                    'photo_desc' => $arrPhotoDec[$pk],
                    'is_cover' => (($pk == $strPhotoIsCover) && ($strPhotoIsCover != null)) ? 1 : 0,
                    'cretime' => time()
                );
            }
            $data = D('UcAlbum')->addPhotoInfo($arrPhotoMsg);
            if ($data['status']) {
                $strpid = implode(',', $data['pid']);
                $data['uid'] = $uid;
                $data['type'] = 2; //相册动态
                $data['operatetype'] = 3; // 上传
                $data['oid'] = $strpid; //照片id
                $data['mid'] = $arrPhotoAid[0]; //相册 id
                $data['otitle'] = ''; //主体相册 名称
                D('UcIndex')->addDynamic($data); //修改相册 生成 动态

                $status = 'ok'; //保存成功
            } else {
                $status = 'false';
            }
        } else {
            $status = 'login'; //未登录
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //相册 与当前用户的关系 --返回相册 id 或 null（暂时没用到---Gavin）
    public function getRelationAlbum($param)
    {
        $intAid = D('UcAlbum')->getRelationAlbum($param);
        return $intAid;
    }

    //照片和当前用户的 关系   （暂时没用到---Gavin）
    public function getRelationPhoto($param)
    {
        $intPid = D('UcAlbum')->getRelationPhoto($param);
        return $intPid;
    }

    //照片评论
    public function ajaxCommentPhoto()
    {
        $album = D('UcAlbum');
        $userinfo = $this->_user;
		if(!$userinfo){
			$data['status'] = 'false';
			$this->ajaxReturn($data, 'JSON');
		}
        $param['uid'] = $userinfo['uid'];
        $param['pid'] =  $this->_post('pid');
        $param['content'] = $this->_post('message');
        $ouid = M('uc_photo')->where("photo_id=".$param['pid'])->getField('uid'); //照片的主人
        if ($param['pid']) {
            //判断登陆
            if ($param['uid']) {
                //检测照片是否存在 返回相册id
                $param['aid'] =D('UcAlbum')->getStatusPhoto($param);
                if (empty($param['aid'])) {
                    $data['status'] = 'delete';//照片不存在或被删除
                    $this->ajaxReturn($data, 'JSON');
                }
                //检测相册是否存在 返回用户uid
                $intUid = D('UcAlbum')->getStatusAlbum($param);
                if (empty($intUid)) {
                    $data['status'] = 'delete';//照片不存在或被删除
                    $this->ajaxReturn($data, 'JSON');
                }
                // 禁止发言
                $userGroup = $this->checkUserGroup();
                if(!$userGroup){
                    $data['status'] = 'ban';
                    $this->ajaxReturn($data, 'JSON');
                }
                //黑名单判断
                $statusNum = D('UcRelation')->getSearchStatus($param['uid'], $ouid);
                if ($statusNum == 4) {
                    $data['status'] = 'black'; //我的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
                if ($statusNum == 5) {
                    $data['status'] = 'tBlack'; //他的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
                //判断是否有敏感词
                $sensitiveIsOrNot = D('SensitiveWord')->isOrNotSensitiveWord($param['content']);
                if ($sensitiveIsOrNot) {
                    $data['status'] = 'sensitive';
                    $this->ajaxReturn($data, 'JSON');
                    exit;
                }
//                if ($param['content']) {
//                    $intStr = strlen_weibo($param['content']);
//                    if ($intStr > 140) {
//                        $data['status'] = 'max';
//                        $this->ajaxReturn($data, 'JSON');
//                    }
//                } else {
//                    $data['status'] = 'empty';
//                    $this->ajaxReturn($data, 'JSON');
//                }
                //10分钟内，同一个用户不可以评论同样的内容
                $lastComment = D('UcAlbum')->getLastComment($param['uid'], $param['content'], $param['pid']);
                if ((time() - $lastComment['dateline'] < 600) && !empty($lastComment)) {
                    $data['status'] = 'samecontent';
                    $this->ajaxReturn($data, 'JSON');
                    exit;
                }
                $r = $album->commentAlbum($param);
                if (!$r) {
                    $data['status'] = 'nopublish'; //评论出错
                } else {
                    $ousername = M('boqii_users')->where("uid='$ouid'")->getField("nickname");
                    //添加动态
                    $dynParams = array(
                        'uid' => $userinfo['uid'],
                        'type' => 2,
                        'operatetype' => 4,
                        'ouid' => $ouid,
                        'ousername' => $ousername,
                        'mid' => $param['pid'],
                        'oid' => $r,
                        'otitle' => ''
                    );
                    D("UcIndex")->addDynamic($dynParams);

                    $data['status'] = 'ok';
                    $data['r'] = $r;
                    $data['uid'] = $userinfo['uid'];
                }
            } else {
                $data['status'] = 'login';
            }
        } else {
            $data['status'] = 'false';
        }

        $this->ajaxReturn($data, 'JSON');
    }

    //照片评论回复
    public function ajaxReplyPhotoComment()
    {
        $album = D('UcAlbum');
        $userinfo = $this->_user;
        $param['uid'] = $userinfo['uid'];
        $param['pid'] = $this->_post('pid');
        $param['commentid'] = $this->_post('commentid');
        $param['content'] = $this->_post('message');


        if ($param['pid'] and $param['commentid']) {
            //判断登陆
            if ($param['uid']) {
                //检测照片是否存在 返回相册id
                $param['aid'] =D('UcAlbum')->getStatusPhoto($param);
                if (empty($param['aid'])) {
                    $data['status'] = 'delete';//照片不存在或被删除
                    $this->ajaxReturn($data, 'JSON');
                }
                //检测相册是否存在 返回用户uid
                $intUid = D('UcAlbum')->getStatusAlbum($param);
                if (empty($intUid)) {
                    $data['status'] = 'delete';//照片不存在或被删除
                    $this->ajaxReturn($data, 'JSON');
                }
                //评论是否被删除
                $status  = D('UcAlbum')->getStatusComment(array('cid'=> $this->_post('commentid')));
                if (empty($status)) {
                    $data['status'] = 'commentDelete';//该回复对应的评论被删除
                    $this->ajaxReturn($data, 'JSON');
                }
                // 禁止发言
                $userGroup = $this->checkUserGroup();
                if(!$userGroup){
                    $data['status'] = 'ban';
                    $this->ajaxReturn($data, 'JSON');
                }
                $tuid = M('uc_photo')->where("photo_id=".$_POST['pid'])->getField('uid'); //照片的主人
                /*黑名单判断 开始*/
                $statusNum = D('UcRelation')->getSearchStatus($param['uid'], $tuid); //黑名单判断
                if ($statusNum == 4) {
                    $data['status'] = 'black'; //我的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
                if ($statusNum == 5) {
                    $data['status'] = 'tBlack'; //他的黑名单
                    $this->ajaxReturn($data, 'JSON');
                }
                /*黑名单判断 结束*/
                $sensitiveIsOrNot = D('SensitiveWord')->isOrNotSensitiveWord($param['content']);
                if ($sensitiveIsOrNot) {
                    $data['status'] = 'sensitive';
                    $this->ajaxReturn($data, 'JSON');
                    exit;
                }
//                if ($param['content']) {
//                    $intStr = strlen_weibo($param['content']);
//                    if ($intStr > 140) {
//                        $data['status'] = 'max';
//                        $this->ajaxReturn($data, 'JSON');
//                    }
//                } else {
//                    $data['status'] = 'empty';
//                    $this->ajaxReturn($data, 'JSON');
//                }
                //10分钟内，同一个用户不可以评论同样的内容
                $lastComment = D('UcAlbum')->getLastComment($param['uid'], $param['content'], $param['pid']);
                if ((time() - $lastComment['dateline'] < 600) && !empty($lastComment)) {
                    $data['status'] = 'samecontent';
                    $this->ajaxReturn($data, 'JSON');
                    exit;
                }
                $r = $album->commentAlbum($param);
                if (!$r) {
                    $data['status'] = 'nopublish'; //回复出错
                } else {
                    $ouid = M('uc_photo_comment')->where('id=' . $param['commentid'])->getField('uid');
                    $ousername = M('boqii_users')->where("uid='$ouid'")->getField("nickname");
                    //添加动态
                    $dynParams = array(
                        'uid' => $userinfo['uid'],
                        'type' => 5,
                        'operatetype' => 2,
                        'ouid' => $ouid,
                        'ousername' => $ousername,
                        'mid' => $param['commentid'],
                        'oid' => $r,
                        'otitle' => ''
                    );
                    D("UcIndex")->addDynamic($dynParams);

                    $data['status'] = 'ok';
                    $data['r'] = $r;
                    $data['uid'] = $userinfo['uid'];
                }
            } else {
                $data['status'] = 'login';
            }
        } else {
            $data['status'] = 'false';
        }

        $this->ajaxReturn($data, 'JSON');
    }

    /**
     * 删除照片评论
     */
    public function ajaxDeletePhotoComment()
    {
        $userinfo = $this->_user;
        $param['uid'] = $userinfo['uid'];
        $param['cid'] = $_GET['cid'];
        if ($param['cid']) {
            if ($param['uid']) {
                $intUid = D('UcAlbum')->getStatusComment($param); //评论是否存在
                if ($intUid) { //评论存在
                    if ($intUid != $param['uid']) {
                        $data['status'] = 'falseA';
                    } else {
                        $r = D('UcAlbum')->deletePhotoComment($param['cid']);
                        if ($r) {
                            $data['status'] = 'ok';
                        } else {
                            $data['status'] = 'falsewww';
                        }
                    }
                } else { //评论不存在
                    $data['status'] = 'falseB';
                }
            } else {
                $data['status'] = 'login';
            }
        } else {
            $data['status'] = 'falseqqqqw';
        }
        $this->ajaxReturn($data, 'JSON');
    }

    //ajax-修改照片描述
    public function ajaxUpdateDesc()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $param['pid'] = $_POST['pid'];
        $param['dec'] = $_POST['dec'];
        if ($uid) {
            if ($param['pid']) {

                $param['aid'] = $intAid = D('UcAlbum')->getStatusPhoto($param); //照片是否存在
                if ($intAid) { //照片 存在
                    $intUid = D('UcAlbum')->getStatusAlbum($param);
                    if ($intUid) { //相册 存在
                        if ($intUid != $uid) { //相册 与 用户的 关系
                            $status = 'false';
                        } else {
                            $intStatus = D("UcAlbum")->updatePhotoDesc($param);
                            if ($intStatus) {
                                $status = 'ok';
                            } else {
                                $status = 'false';
                            }
                        }
                    } else { //相册 不存在
                        $status = 'false';
                    }
                } else { //照片 不存在
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

    //ajax-修改照片名称
    public function ajaxUpdateName()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $param['pid'] = $_POST['pid'];
        $param['name'] = $_POST['name'];
        if ($uid) {
            if (!empty($param['name'])) {
                if ($param['pid']) {

                    $param['aid'] = $intAid = D('UcAlbum')->getStatusPhoto($param); //照片是否存在
                    if ($intAid) { //照片 存在
                        $intUid = D('UcAlbum')->getStatusAlbum($param);
                        if ($intUid) { //相册 存在
                            if ($intUid != $uid) { //相册 与 用户的 关系
                                $status = 'false';
                            } else {
                                $intStatus = D("UcAlbum")->updatePhotoName($param);
                                if ($intStatus) {
                                    $status = 'ok';
                                } else {
                                    $status = 'false';
                                }
                            }
                        } else { //相册 不存在
                            $status = 'false';
                        }
                    } else { //照片 不存在
                        $status = 'false';
                    }
                } else {
                    $status = 'false';
                }
            } else {
                $status = 'lenerror';
            }
        } else {
            $status = 'login';
        }
        $this->ajaxReturn($status, 'JSON');
    }

    //ajax---判断用户当前相册容量
    public function getIsUpload()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        //未登录
        if ($uid) {
            $bool = D('UcAlbum')->getBoolIsUpload($uid);
            $data['status'] = empty($bool) ? 'false' : 'true';
        } else {
            $data['status'] = 'login';
        }
        $this->ajaxReturn($data, 'JSON');
    }

   /**
    * ajax获取单张图片信息(照片信息和第一页照片评论信息)
    * @return mixed
    * 发送参数：pid=图片id&p=页&c=每页返回数
    * 返回值：console.log
   ({'status':'返回状态','wrCount':'评论数','page':'页面值','pCount':'每页显示数',
   'lkCount':'查看数','wrList':[{'useId':'用户ID','wbId':'回复ID','useName':'用户名',
   'imgHead':'用户头像地址','message':'评论信息','wrDate':'回复时间','ofMe':'是否显示删除'},

   {'useId':'用户ID','wbId':'回复ID','useName':'用户名','imgHead':'用户头像地址',
   'message':'评论信息','wrDate':'回复时间','ofMe':'是否显示删除'}]});
    */
    public function ajaxGetPhotoIno()
    {
        $userinfo = $this->_user;
		if(!$userinfo){
			echo 'fail';exit;
		}
        $uid = $userinfo['uid'];
        $photoId = $this->_get('pid');
        $p = empty($_GET['p']) ? 1 : $_GET['p'];
        $pageNum = empty($_GET['c']) ? 20 : $_GET['c'];
        $param = array(
            'photoId' => $photoId,
            'p' => $p,
            'pageNUm' => $pageNum,
            'login' => array(
                'loginUId' => $uid
            )
        );
        $arrPhotoInfo = D('UcAlbum')->getAjaxphotoShow($param);
        echo json_encode($arrPhotoInfo);
    }

    //批量更改照片表的宽高
    public function  updatePicPixels()
    {
        $lockKey = $_GET['lockKey'];
        if (md5($lockKey) == 'bbc238adcc2b896978d424ac47e9c56c'){
            D('UcAlbum')->updatePhotoWiHe($lockKey);
            echo 'finish';
            exit;
        } else {
            echo 'This operation is not allowed';
        }
    }

    //重置评论数
    public function resetComment(){
        $lockKey = $_GET['lockKey'];
        if (md5($lockKey) == 'bbc238adcc2b896978d424ac47e9c56c'){
            D('UcAlbum')->resetComment($lockKey);
            echo 'finish';
            exit;
        } else {
            echo 'This operation is not allowed';
        }
    }

  //禁言用户ajax
    public function ajaxCheckUserGroup() {
        $status = $this->checkUserGroup();
        if ($status === 'login') {
           $this->ajaxReturn('login','JSON');
        }
        if ($status) {
            $this->ajaxReturn('true','JSON');
        } else {
            $this->ajaxReturn('false','JSON');
        }
    }
//公共登录验证信息
    public function publicLogin()
    {
        $userinfo = $this->_user;
        $uid = $userinfo['uid'];
        $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        if (!$uid) redirect(get_rewrite_url('User', 'login') .'?referer='.$currentUrl);
        return $uid;
    }

    //404 跳转
    public function getPage404($uid = '')
    {
        header("HTTP/1.0 404 Not Found");
        $uid = $uid; //uid为访问资源对应用户ID
        $this->assign('uid', $uid);
        $this->display('Public:404');
        exit;
    }

}

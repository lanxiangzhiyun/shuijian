<?php
/**
 * 日记Model类
 *
 * @created 2013-01-04
 * @author zzy
 */
class UcDiaryModel extends Model {
    /**
     * 添加日志分类信息
     *
     * @param $param array 参数数组
     *
     * @return boolean 处理结果
     */
    public function addDiaryTypeInfo($param) {
        //分类data
        $data['uid'] = $param['uid'];
        $data['name'] = $param['name'];
        $data['dateline'] = time();
        $data['status'] = 0;
        $id = M("uc_diary_type")->add($data);
        if($id) {
            return $id;	//11.20 vic修改
        } else {
            return false;
        }
    }

    /**
     * 修改日志分类名
     *
     * @param $id int 日志分类id
     *
     * @return boolean 处理结果
     */
    public function updateDiaryTypeInfo($params) {
        //分类data
        $data['name'] = $params['name'];
        $data['dateline'] = time();

        $res = M("uc_diary_type")->where("id=".$params['id'])->save($data);
        if($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除日志分类信息
     *
     * @param $id int 日志分类id
     *
     * @return boolean 处理结果
     */
    public function deleteDiaryTypeInfo($id) {
        //该日志分类下的日志改为默认分类        
        M("uc_diary")->where("type_id=".$id)->save(array("type_id"=>0));
        //删除日志分类
        $res = M("uc_diary_type")->where("id=". $id)->save(array("status"=>-1));
        if($res) {
            return true;
        } else {
            return false;
        }
    }
	

	public function getDiaryTypeInfo($typeId){
		return M("uc_diary_type")->where(array("id"=>$typeId))->field("id,name")->find();
	}
	/** 
     * 根据用户id以及分类名称查询
     *
     * @param $uid int 用户id
     * @param $name varchar 分类名称
     * 
     * @return array 日志分类信息
     */
	public function getDiaryTypes($uid, $name) {
        $types= M("uc_diary_type")->where("uid=".$uid." AND status=0 AND name='".$name."'")->field("id, name")->select();
        return $types;
    }
    /** 
     * 取得指定用户的日志分类信息
     *
     * @param $uid int 用户id
     * @param $isNum int 是否需要统计分类下日志数量
     * 
     * @return array 日志分类信息
     */
    public function getUserDiaryTypeList($uid, $isNum = 1) {
        $typeList[-1] = array("id"=>0, "name"=>"我的宠日记");
        
        //日志分类
        $types= M("uc_diary_type")->where("uid=".$uid." AND status=0")->order("dateline desc")->field("id, name")->select();
        foreach($types as $tk => $type) {
            $typeList[$tk] = $type;
        }

        if($isNum) {
            $totalNum = 0;
            foreach($typeList as $tk => $type) {
                $num = M("uc_diary")->where("uid=".$uid." AND type_id=".$type['id']." AND status >= 0")->count();
                $totalNum += $num;
                $typeList[$tk]['num'] = $num;
            }
        } 

        return $typeList;
    }

	/** 
     * 根据用户编号，获取最新的一条评论
     *
     * @param $uid int 用户id
     * @param $content varchar 评论内容

     * @return array 日志评论信息
     */
	public function getLastDiaryComment($uid,$content,$diaryid) {
        $lastComment = M("uc_diary_comment")->where("diaryid=".$diaryid." AND uid=".$uid." AND content='".$content."'" )->order('dateline desc')->field("content,dateline")->find();
        return $lastComment;
    }
    /**
     * 取得指定用户的日志归档（年月）
     *
     * @param $uid int 用户id
     *
     * @return array 归档
     */
    public function getUserDiaryYearMonth($uid) {
        $diaryYMonthList = $this->query("SELECT DISTINCT FROM_UNIXTIME(cretime, '%Y%m')  AS ymonth FROM uc_diary WHERE uid=" . $uid . " ORDER BY cretime DESC");
        $diaryYMonths = array();

        foreach($diaryYMonthList as $dk=>$diaryYMonth) {
            $year = substr($diaryYMonth['ymonth'], 0, 4);
            $month = substr($diaryYMonth['ymonth'], 4, 2);
            $cnt = $this->where("uid=".$uid." AND FROM_UNIXTIME(cretime, '%Y%m') ='" . $diaryYMonth['ymonth'] . "' AND status >= 0")->count();
            $diaryYMonths[$year][$month]['ymonth'] = $month;
            $diaryYMonths[$year][$month]['cnt'] = $cnt;
        }
		//print_r($diaryYMonths);exit;
        return $diaryYMonths;
    }

    /**
     * 取得更多宠日志（10条）
     *
     * @param $uid int 用户id
     * @param $currentDiaryId 当前日记ID，可以为空
     * @return array 日志列表
     */
    public function getMoreDiaryList($uid,$currentDiaryId=null) {
		$where = "uid=".$uid." AND status >= 0";
		if(!empty($currentDiaryId)){
			$where .= " AND id<>".$currentDiaryId;
		}
        $diaryList = $this->where($where)->order("cretime DESC")->field("id,title")->limit(10)->select();
        
        foreach($diaryList as $hk => $diary) {
            $diaryList[$hk]['short_title'] = mysubstr_utf8(strip_tags($diary['title']), 100);
        }

        return $diaryList;
    }

    /**
     * 取得热门的宠日志（10条）
     *
     * @return array 热门宠日志
     */
    public function getHotDiaryList() {
		$where = "status >= 0";
        $hotDiaryList = M("uc_diary")->where($where)->order("views DESC")->field("id,title")->limit(10)->select();

        foreach($hotDiaryList as $hk => $diary) {
            $hotDiaryList[$hk]['short_title'] = mysubstr_utf8($diary['title'], 10);
        }

        return $hotDiaryList;
    }

    /**
     * 取得用户相册名列表
     * TODO 移至相册Model类中
     *
     * @param $uid int 用户id 
     *
     * @return array 相册名列表
     */
    public function getUserAlbumNameList($uid) {
        //相册列表
        $albumList = M("uc_album")->where("status=0 AND uid=" . $uid)->order("dateline")->field("id, title")->select();
        if(empty($albumList)){
            D('UcAlbum')->addDefaultAlbum(array('uid'=>$uid)); //检查是否有默认相册 ，没有则创建
            $albumList = M("uc_album")->where("status=0 AND uid=" . $uid)->order("dateline")->field("id, title")->select();
        }
        return $albumList;
    }

    /**
     * 添加日志信息
     *
     * @param $param array 参数数组
     *
     * @return 成功返回日志id，失败返回空
     */
    public function addDiaryInfo($param) {
		$data['uid'] = $param['uid'];
		D('Api')->userExtendHandle('diary_num',$data['uid'],'inc');
        $data['title'] = $param['title'];
        $data['content'] = $param['content'];
        $data['type_id'] = $param['type_id'];
		$data['album_id'] = $param['album_id'];
        $data['cretime'] = time();
        $data['updatetime'] = time();
        $data['views'] = 0;
        $data['comments'] = 0;
        $data['top'] = 0;//置顶
        $data['status'] = 0;//未审核
        $id = M("uc_diary")->add($data);
        
		return $id;
    }

    /**
     * 编辑日志信息
     *
     * @param $param array 参数数组
     *
     * @return boolean 处理结果
     */
    public function updateDiaryInfo($param) {
        $data['title'] = $param['title'];
        $data['content'] = $param['content'];
        $data['type_id'] = $param['type_id'];
		$data['album_id'] = $param['album_id'];
        $data['updatetime'] = time();
        $res = $this->where("id=".$param['id'])->save($data);
        if($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改日志的分类
     *
     * @param $param array 参数数组
     *               ids string 串接日志id
     *               type_id int 分类id
     *
     * @return boolean 处理结果
     */
    public function updateDiaryType($param) {
        $data['type_id'] = $param['type_id'];
        $res = M("uc_diary")->where("id IN (" . $param['ids'] . ")")->save($data);
        return true;
    }

    

    
    /**
     * 批量删除日志
     *
     * @param $ids string 串接日志id
     *
     * @return string 处理结果
     */
    public function deleteDiaryList($ids,$uid='') {
		//先删除图片
        $result = $this->deletPhotoByPid($ids);
		$error_ids = "";
		
        $idList = explode(",", $ids);
		$ucDiaryCommentModel = M("uc_diary_comment");
		$apiModel = D('Api');
        foreach($idList as $id) {
			$apiModel->userExtendHandle('diary_num',$uid,'dec');
            $res = $ucDiaryCommentModel->where("diaryid=". $id)->save(array("status"=>1));
            $res = $this->where("id=".$id)->save(array("status"=>-1));
        }

        return false;
    }
	/**
     * 删除日志中的图片
	 *
	 * @param $ids string 日志编号ID以英文逗号连接
	 *
     */
	public function deletPhotoByPid($ids){
		load("@.manual_common");
		$idList = explode(",", $ids);
		$ucAlbum = D('UcAlbum');
		$ucPhotoModel = M("UcPhoto");
		foreach($idList as $id) {
			$result = $this->where("id=".$id)->field('content')->find();
			//匹配出要删除的图片ID编号
			$ret = preg_match_diary_pid($result['content']);
			if($ret){
				foreach($ret as $key=>$val){
					//获取图片大小
					$photo = $ucPhotoModel->where('photo_id='.$val['pid'])->field('uid,size')->find();
					//编辑个人空间大小
					$ucAlbum->changeAlbumCapacity(array('uid'=>$photo['uid'],'changeNum'=>$photo['size']),2);
					//逻辑删除图片
					$ucPhotoModel->where('photo_id='.$val['pid'])->save(array('status'=>-1));
				}
			}
		}
	}

    /**
     * 置顶日志
     *
     * @param $ids string 串接日志id
     */
    public function topDiary($ids) {
        $error_ids = "";

        $idList = explode(",", $ids);
        foreach($idList as $id) {
            $res = M("uc_diary")->where("id=".$id)->save(array("top"=>1));
            if(!$res) {
                $error_ids = !empty($error_ids) ? $error_ids . "," . $id : $id; 
            }
        }

        return $error_ids;
    }

    /**
     * 取得某分类下的所有日志信息
     *
     * @param $param array 参数数组
     */
    public function getDiaryListByType($param) {
        $where = "1";
        if($param['uid']) {
            $where .= " AND u.uid = " . $param['uid'];
        }
        if($param['type_id']) {
            $where .= " AND u.type_id = " . $param['type_id'];
        }
        $where .= " AND u.status >= 0";

        //排序方式
        $order = "u.top DESC";
        if($param['order_by']) {
            $order = $order . ", u." . $param['order_by'] . " DESC";    
        } else {
            $order = $order . ", u.cretime DESC";    
        }

        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 20;
        $page_start = ($page-1) * $page_num;
            
        $diaryList = M()->Table("uc_diary u")->join("uc_diary_type t ON t.id=u.type_id")->where($where)->field("u.uid, u.title, u.content, u.type_id, u.cretime, u.updatetime, u.views, u.comments, u.album_id, u.top, u.status, t.name")->order($order)->limit("$page_start, $page_num")->select();

        $this->total = M()->Table("uc_diary u")->join("uc_diary_type t ON t.id=u.type_id")->where($where)->count();    

        foreach($diaryList as $dk => $diary) {
            $diaryList[$dk]['format_cretime'] = date("Y-m-d", $diary['cretime']);
            $diaryList[$dk]['format_updatetime'] = date("Y-m-d", $diary['updatetime']);
            //显示方式
            if($param['display'] == "summary") {
                $diaryList[$dk]['summary'] = mysubstr_utf8($diary['content'], 100);
            }
        }
        
        return $diaryList;
    }

    /**
     * 取得年月下的所有日志信息
     *
     * @param $param array 参数数组
     *                    uid    int    用户id
     *                    ymonth    string    ****-**
     *                    page    int    当前页面
     *                    
     */
    public function getDiaryListByYearMonth($param) {
        $where = "1";
        if($param['uid']) {
            $where .= " AND u.uid = " . $param['uid'];
        }
        if($param['ymonth']) {
            $where .= " AND FROM_UNIXTIME('cretime', '%Y-%m') = '".$param['ymonth'] . "'";
        }
        $where .= " AND u.status >= 0";


        //排序方式
        $order = "u.top DESC";
        if($param['order_by']) {
            $order = $order . ", u." . $param['order_by'] . " DESC";    
        } else {
            $order = $order . ", u.cretime DESC";    
        }

        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 20;
        $page_start = ($page-1) * $page_num;
            
        $diaryList = M()->Table("uc_diary u")->join("uc_diary_type t ON t.id=u.type_id")->where($where)->field("u.uid, u.title, u.content, u.type_id, u.cretime, u.updatetime, u.views, u.comments, u.album_id, u.top, u.status, t.name")->order($order)->limit("$page_start, $page_num")->select();

        $this->total = M()->Table("uc_diary u")->join("uc_diary_type t ON t.id=u.type_id")->where($where)->count();    

        foreach($diaryList as $dk => $diary) {
            $diaryList[$dk]['format_cretime'] = date("Y-m-d", $diary['cretime']);
            $diaryList[$dk]['format_updatetime'] = date("Y-m-d", $diary['updatetime']);
            //显示方式
            if($param['display'] == "summary") {
                $diaryList[$dk]['summary'] = mysubstr_utf8($diary['content'], 100);
            }
        }
        
        return $diaryList;
    }

    /**
     * 取得所有日志信息
     *
     * @param $param array 参数数组
     *                    uid    int    用户id
     *                    type_id    int    类型id
     *                    ymonth    string    年月****-**
     *                    page    int    当前页码
     *                    page_num    int    页显数量
     *
     * @return array 日志列表
     */
    public function getDiaryList($param) {
        $where = "1";
        if($param['uid']) {
            $where .= " AND u.uid = " . $param['uid'];
        }
        if(isset($param['type_id']) && $param['type_id'] != -1) {
            $where .= " AND u.type_id = " . $param['type_id'];
        }
        if($param['ymonth']) {
            $where .= " AND FROM_UNIXTIME(cretime, '%Y%m') = '".$param['ymonth'] . "'";
        }
        $where .= " AND u.status >= 0";
        //排序方式
        $order = "u.top DESC";
        if($param['order_by']) {
            $order = $order . ", u." . $param['order_by'] . " DESC";    
        } else {
            $order = $order . ", u.cretime DESC";    
        }
        //显示方式

        $page = $param['page'] ? $param['page'] : 1;
        $page_num = $param['page_num'] ? $param['page_num'] : 20;
        $page_start = ($page-1) * $page_num;
            
        $diaryList = M()->Table("uc_diary u")->join("uc_diary_type t ON t.id=u.type_id")->where($where)->field("u.id, u.uid, u.title, u.content, u.type_id, u.cretime, u.updatetime, u.views, u.comments, u.album_id, u.top, u.status, t.name")->order($order)->limit("$page_start, $page_num")->select();

		$this->total = M()->Table("uc_diary u")->join("uc_diary_type t ON t.id=u.type_id")->where($where)->count();    
        foreach($diaryList as $dk => $diary) {
            $diaryList[$dk]['format_cretime'] = date("Y-m-d", $diary['cretime']);
            $diaryList[$dk]['format_updatetime'] = date("Y-m-d", $diary['updatetime']);
            //显示方式
            //if($param['display'] == "summary") {
                $diaryList[$dk]['summary'] = mysubstr_utf8(strip_tags($diary['content']), 100);
            //}
        }
        
        return $diaryList;
    }

    /**
     * 更新日志浏览数
     * 
     * @param $id int 日志id
     *
     * @return boolean 更新是否成功
     */
    public function updateDiaryViews($id) {
        $res = $this->execute("UPDATE uc_diary SET views=views+1 WHERE id=".$id);
        if($res) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 取得某日志信息
     *
     * @param $param array 参数数组
     *                    id    int 日志id
     *                    nocomment int 是否需要显示评论，默认显示
     *                    page int    当前页码
     *                    page_num    int    页显数量
     *
     * @return array 日志信息
     */
    public function getDiaryInfo($param) {
        //日志
        $diary = M()->Table("uc_diary u")->join("uc_diary_type t ON t.id=u.type_id")->where("u.id=".$param['id'])->field("u.id, u.uid, u.title, u.content, u.type_id, u.cretime, u.updatetime, u.views, u.comments, u.album_id, u.top, u.status, t.name")->find();
        $diary['format_cretime'] = date("Y-m-d H:i:s", $diary['cretime']);
        $diary['format_updatetime'] = date("Y-m-d H:i:s", $diary['updatetime']);
        
        //不需要评论
        if(!isset($param['nocomment']) || $param['nocomment'] == 0) {
            //评论
            $page = $param['page'] ? $param['page'] : 1;
            $page_num = $param['page_num'] ? $param['page_num'] : 5;
            $page_start = ($page-1) * $page_num;
            //评论列表（TODO 评论中的表情解译）
            $commentList = M()->Table("uc_diary_comment")->where("diaryid=".$param['id'] . " AND status>=0")->field("id, content, dateline, uid, commentid, diaryid, status, isnew")->order("dateline DESC")->limit("$page_start, $page_num")->select();
			
            $this->total = M()->Table("uc_diary_comment")->where("diaryid=".$param['id'] . " AND status>=0")->count();
			$apiModel = D('Api');
            foreach($commentList as $ck => $comment) {
				$userInfo = $apiModel->getUserInfo($comment['uid']);
				$commentList[$ck]['nickname'] = $userInfo['nickname'];
				$commentList[$ck]['avatar'] = $userInfo['avatar'];
				$commentList[$ck]['gender'] = $userInfo['gender'];
				if($comment['commentid']) {
					$replycomment = M()->Table("uc_diary_comment c")->join("boqii_users u ON c.uid=u.uid")->where("c.id=".$comment['commentid'])->field("c.uid, u.nickname")->find();
					$commentList[$ck]['reply_uid'] = $replycomment['uid'];
					$commentList[$ck]['reply_nickname'] = $replycomment['nickname'] ? $replycomment['nickname'] : $replycomment['uid'];
				}
                $commentList[$ck]['format_dateline'] = date("Y-m-d H:i", $comment['dateline']);
            }

            $diary['commentList'] = $commentList;
        }
        return $diary;
    }
    
    /**
     * 评论日志
     *
     * @param $param array 参数数组
     *
     * @return boolean 处理结果
     */
    public function commentDiary($param) {
        $data['content'] = $param['content'];
        $data['dateline'] = time();
        $data['uid'] = $param['uid'];
        if($param['commentid']) {
            $data['commentid'] = $param['commentid'];
        } else {
            $data['commentid'] = 0;    
        }
        $data['diaryid'] = $param['diaryid'];
		$data['ip'] = $param['ip'];
        $data['status'] = 0;
        $data['isnew'] = 1;
        $cid = M("uc_diary_comment")->add($data);
        if($cid) {
			$this->execute("UPDATE uc_diary SET comments=comments+1 WHERE id=".$param['diaryid']);
            return $cid;
        } else {
            return false;
        }
    }

    /**
     * 删除日志评论
     *
     * @param $id int 评论id
     *
     * @return boolean 处理结果
     */
    public function deleteDiaryComment($id) {
        $data['status'] = -1;
		$diaryid = M('uc_diary_comment')->where('id='.$id)->getField('diaryid');
		if(empty($diaryid)){
			return false;
		}
        $res = M("uc_diary_comment")->where("id=".$id)->save($data);
        if($res) {
			$this->execute("UPDATE uc_diary SET comments=comments-1 WHERE id=".$diaryid);
            return true;
        } else {
            return false;
        }
    }

	/**
	* 删除图片同时删除日志中的图片
	*
	* @param $photo_id 图片编号
	*        $diary_id 日志编号
	*/
	public function deleteDiaryPhoto($photo_id,$diary_id){
		$UcDiary = D("UcDiary");
		$diary = $UcDiary->where(array('id'=>$diary_id))->select();
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
		$UcDiary->where(array('id'=>$diary_id))->save(array('content'=>$content));
	}

    /**
     * 取得用户的总日志数
     * 
     * @param $uid int 用户id
     *
     * @return int 总日志数
     */
    public function getUserDiaryCnt($uid) {
        //总日志数
        $weiboCnt = M()->Table("uc_diary")->where("uid=".$uid." AND status >= 0")->count();
        return $weiboCnt;
    }
	/**
     * 取得评论信息
     * 
     * @param $id int 评论id
     *
     * @return array() 评论记录数组(暂时只能用到uid，如果其它地方有调用可以增加查询字段)
     */
    public function getDiaryCommentById($id) {
		$comment = M()->Table("uc_diary_comment")->where('id='.$id)->field("uid,status")->find();
		return $comment;
    }
}

?>
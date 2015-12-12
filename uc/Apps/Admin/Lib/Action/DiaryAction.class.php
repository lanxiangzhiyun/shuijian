<?php
/*
*DiaryAction 日志
*/
class DiaryAction extends ExtendAction{
	
	/*
	*日志列表页
	*/
	public function index(){

		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}

		$url='/iadmin.php/Diary/index?';
		$where="diary.uid=user.uid and diary.status=0";

		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['title'],$noAllow) && !empty($data['title'])){
				$where.=" and diary.title like '%".$data['title']."%' ";
				$url.='data[title]='.urlencode($data['title']).'&';
				$this->assign('title',$data['title']);
			}
			if(trim($data['starttime'])){
				$where.=" and diary.cretime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and diary.cretime <= ".strtotime($data['endtime'].' 23:59:59');

				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if(!in_array($data['user'],$noAllow) && !empty($data['user'])){
				if($data['select']==1){
					$where.=" and user.nickname like '%".trim($data['user'])."%' ";
				}else if($data['select']==2){
					if(is_numeric($data['user'])){
						$where.=" and user.uid=".trim($data['user']);
					}
				}
				$url.='data[user]='.urlencode($data['user']).'&';
				$url.='data[select]='.$data['select'].'&';

				$this->assign('select',$data['select']);
				$this->assign('user',$data['user']);
			}
		}

		$ucDiary = D('UcDiary');
		$DiaryCount = $ucDiary->hasDiaryCount($where);
		$pcount = ceil($DiaryCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		
		$url.='page=';

		$Diarys = $ucDiary->hasUserAndDiary($page,$limit,$where);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($Diarys));

		$this->assign('url',$url.$page);
		
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('Diarys',$Diarys);
		$this->display('index');
	}

	/*
	*删除日志
	*/
	public function deleteDiray(){
		$ids = $this->_get('deleteDiray');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$ucDiary = D('UcDiary');
		$ucDiaryComment = D('UcDiaryComment');
		$apiModel = D('Api');
		foreach($idArr as $key=>$val){
			if($val){
				$uid = $ucDiary->where(array('id'=>$val))->select();
				if($isNotice==1){
					$this->recordOperations(2,1,$val,$isNotice,$uid[0]['uid'],1);
				}else{
					$this->recordOperations(2,1,$val);
				}
				$apiModel->userExtendHandle('diary_num',$uid[0]['uid'],'dec');
				$ucDiaryComment->where(array('diaryid'=>$val))->save(array('status'=>-1));
				$ucDiary->where(array('id'=>$val))->save(array('status'=>-1));
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Diary/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/*
	*日志编辑页面
	*/
	public function editPage(){
		if($this->_get('diaryid')){
			$id = $this->_get('diaryid');
			$diary = D()->table('uc_diary diary,boqii_users user')->where('diary.uid=user.uid and diary.id='.$id.'')->field('diary.id,diary.content,diary.title,diary.cretime,user.username')->select();
			$this->assign('diary',$diary[0]);
			$this->display('editPage');
		}else{
			//跳回列表页
			$this->redirect('/iadmin.php/Diary/index');
			exit;
		}
		
	}
	
	/*
	*提交日志修改
	*/
	public function editDiary(){
		$data = $this->_post('data');
		$data['content'] = urldecode($this->_post('content'));
		$ucDiary = D('UcDiary');
		//搜索一遍值进行比对判断是否有修改 并发私信给用户
		$is_check=1;
		$diary = $ucDiary->where(array('id'=>$data['id']))->field('id,title,content,uid')->select();
		$ucPhoto = D('UcPhoto');
		$data['uid']=$diary[0]['uid'];
		$photos = $ucPhoto->where(array('object_id'=>$data['id']))->field('photo_id')->select();
		$src = preg_match_diary($data['content'],$photos);
		if($src){
			$photo['photo_id']  = array('in',$src);
			$ucPhoto->where($photo)->delete();
			//记录图片删除操作日志
			foreach($src as $key=>$val){
				$this->recordOperations(2,16,$val);
			}
			$is_check=2;
		}
		if($data['content']!=$diary[0]['conetnt']){
			$this->recordOperations(4,16,$data['id']);
		}
		if($data['title']!=$diary[0]['title']){
			$this->recordOperations(3,16,$data['id']);
		}
		//发送私信
		if($is_check==1){
			$this->setNotice($data['id'],$diary[0]['uid'],1,3);
		}else{
			$this->setNotice($data['id'],$diary[0]['uid'],1,4);
		}
		
		
		$ucDiary->save($data);
		
		$this->redirect('/iadmin.php/Diary/index');
	}

	/*
	*日志评论列表
	*/
	public function diaryComment(){
		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		

		$url='/iadmin.php/Diary/diaryComment?';
		$where="diarycomment.uid=user.uid and diarycomment.diaryid=diary.id and diarycomment.status=0";
		
		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['content'],$noAllow) && !empty($data['content'])){
				$where.=" and diarycomment.content like '%".$data['content']."%' ";
				$url.='data[content]='.urlencode($data['content']).'&';
				$this->assign('content',$data['content']);
			}
			if(trim($data['starttime'])){
				$where.=" and diarycomment.dateline >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and diarycomment.dateline <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if(!in_array($data['title'],$noAllow) && !empty($data['title'])){
				$where.=" and diary.title like '%".$data['title']."%' ";
				$url.='data[title]='.urlencode($data['title']).'&';
				$this->assign('title',$data['title']);
			}
			if(!in_array($data['user'],$noAllow) && !empty($data['user'])){
				if($data['select']==1){
					$where.=" and user.nickname like '%".trim($data['user'])."%' ";
				}else if($data['select']==2){
					if(is_numeric($data['user'])){
						$where.=" and user.uid=".trim($data['user']);
					}
				}
				$url.='data[user]='.urlencode($data['user']).'&';
				$url.='data[select]='.$data['select'].'&';

				$this->assign('select',$data['select']);
				$this->assign('user',$data['user']);
			}
		}
		
		$ucDiaryComment = D('UcDiaryComment');
		$DiaryCommentCount = $ucDiaryComment->hasDiaryCommentCount($where);
		$pcount = ceil($DiaryCommentCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$url.='page=';
		$DiaryComments = $ucDiaryComment->hasDiaryCommentUser($page,$limit,$where);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($DiaryComments));
		$this->assign('url',$url.$page);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('DiaryComments',$DiaryComments);
		$this->display('diaryComment');
	}

	/*
	*日志评论删除
	*/
	public function deleteDirayComment(){
		$ids = $this->_get('deleteDirayComment');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$ucDiaryComment = D('UcDiaryComment');
		$ucDiary = D('UcDiary');
		foreach($idArr as $key=>$val){
			if($val){
				$uid = $ucDiaryComment->where(array('id'=>$val))->select();
				if($isNotice==1){
					$this->recordOperations(2,6,$val,$isNotice,$uid[0]['uid'],3);
				}else{
					$this->recordOperations(2,6,$val);
				}
			
				$ucDiaryComment->where(array('id'=>$val))->save(array('status'=>-1));
				
				$comments = $ucDiary->where(array('id'=>$uid[0]['diaryid']))->select();
				
				
				if($comments[0]['comments']>0){
					//删除日志评论数
					$data['comments']=array('exp','comments-1');
					$ucDiary->where(array('id'=>$uid[0]['diaryid']))->save($data);
				}
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Diary/diaryComment?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}
}
?>
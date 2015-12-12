<?php
/*
*相册和图片处理
*/
class AlbumAction extends ExtendAction{
	
	/*
	*专辑列表页面
	*/
	public function index(){
		$UcAlbum = D('UcAlbum');
	

		$limit=20;
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$where="album.uid=user.uid and album.status=0";
		$url='/iadmin.php/Album/index?';

		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['keyword'],$noAllow) && !empty($data['keyword'])){
				$where.=" and album.title like '%".$data['keyword']."%' ";
				$url.='data[keyword]='.urlencode($data['keyword']).'&';
				$this->assign('keyword',$data['keyword']);
			}

			if(trim($data['starttime'])){
				$where.=" and album.dateline >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and album.dateline <= ".strtotime($data['endtime'].' 23:59:59');
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
		
		$AlbumCount = $UcAlbum->hasAlbumCount($where);
		$pcount = ceil($AlbumCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$url.='page=';
		$Albums = $UcAlbum->hasUserAndAlbum($page,$limit,$where);
		
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($Albums));
		$this->assign('url',$url.$page);
		$this->assign('Albums',$Albums);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->display('index');
	}

	/*
	*删除专辑
	*/
	public function deleteAlbum(){
		$ids = $this->_get('deleteAlbum');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$UcAlbum = D('UcAlbum');
		$UcPhoto = D('UcPhoto');
		$ucPhotoComment = D('UcPhotoComment');
		$apiModel = D('Api');
		foreach($idArr as $key=>$val){
			if($val){
				
				//判断是不是默认相册
				$uid = $UcAlbum->where(array('id'=>$val))->select();
				if($uid[0]['default']==1){	
					$UcAlbum->where(array('id'=>$val))->save(array('status'=>1));
				}
				$is_check=1;
				//删除图片
				$photos = $UcPhoto->where(array('album_id'=>$val))->field('photo_id,uid,size,object_type,object_id,photo_path')->select();

				if($photos){
					//同时把用户空间大小 加 或者减
					foreach($photos as $k=>$v){
						//用户照片数量 总数 -1
						$apiModel -> userExtendHandle('photo_num',$v['uid'],'dec');
						$photoids[]=$v['photo_id'];
						$UcAlbum->changeAlbumCapacity(array('uid'=>$v['uid'],'changeNum'=>$v['size']),2);
					}
					$UcPhoto->where(array('album_id'=>$val))->save(array('status'=>-1));

					//删除图片的评论
					$map['photo_id']  = array('in',$photoids);
					$ucPhotoComment->where($map)->save(array('status'=>-1));
					
					//判断图片有没有关联 (如果关联发送私信)
					$is_check=$this->setNoticeCheck($photos,$val);
				}
				if($isNotice==1){
					if($is_check==1){		
						$this->recordOperations(2,2,$val);
						$this->setNotice($val,$uid[0]['uid'],6,2);
					}else{
						$this->recordOperations(2,2,$val);
						$this->setNotice($val,$uid[0]['uid'],6,5);
					}
				}else{
					$this->recordOperations(2,2,$val);
				}

			}
		}
		if(empty($act)){
			//$this->redirect('/iadmin.php/Album/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	
	/*
	*判断是否要发送私信
	*/
	public function setNoticeCheck($photos,$album_id){
		$UcDiary = D("UcDiary");
		$i=1;
		foreach($photos as $key=>$val){
			if($val['object_type']==1){
				$UcDiary->deleteDiaryPhoto($val['photo_id'],$val['object_id']);
				$i++;
			}
		}
		if($i>1){
			return 2;
		}else{
			return 1;
		}
	}

	/*
	*ajax获取相册信息
	*/
	public function ajaxAlbum(){
		$albumid = $this->_post('albumid');
		$UcAlbum = D('UcAlbum');
		$Album = $UcAlbum->where(array('id'=>$albumid))->field('id,title,content')->limit(1)->select();
		echo json_encode($Album);
		exit;
	}
	
	/*
	*提交内容修改相册信息
	*/
	public function submitAjax(){
		$data['id'] = $this->_post('albumid');
		$data['title'] = $this->_post('title');
		$data['content'] = $this->_post('content');
		$isNotice = $this->_post('isSetNotice');
		$UcAlbum = D('UcAlbum');
		$uid = $UcAlbum->field('id,title,content,uid')->where(array('id'=>$data['id']))->select();
		foreach($uid as $key=>$val){
			foreach($data as $k=>$v){
				if($v!=$val[$k]){
					$arr[$k]['column']=$k;
					$arr[$k]['beforeContent']=$val[$k];
					$arr[$k]['afterContent']=$v;
				}
			}
		}
		
		foreach($arr as $key=>$val){
				$this->recordOperations(3,2,$data['id'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
		}
		//判断要不要发送私信
		if($isNotice==1){
			$this->setNotice($data['id'],$uid[0]['uid'],6,3);
		}
		$UcAlbum->save($data);
		echo 1;
		exit;
	}

	/*
	*图片列表页面
	*/
	public function photo(){
		$UcPhoto = D('UcPhoto');
		$limit=10;
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$url='/iadmin.php/Album/photo?';
		$where="photo.album_id=album.id and photo.uid=user.uid and photo.status!=-1";
	
		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');
			if(!in_array($data['title'],$noAllow) && !empty($data['title'])){
				$where.=" and photo.photo_name like '%".$data['title']."%' ";
				$url.='data[title]='.urlencode($data['title']).'&';
				$this->assign('title',$data['title']);
			}
			if(trim($data['starttime'])){
				$where.=" and photo.cretime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and photo.cretime <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if(!in_array($data['albumname'],$noAllow) && !empty($data['albumname'])){
				$where.=" and album.title like '%".$data['albumname']."%' ";
				$url.='data[albumname]='.urlencode($data['albumname']).'&';
				$this->assign('albumname',$data['albumname']);
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

		$PhotoCount = $UcPhoto->hasPhotoCount($where);
		$pcount = ceil($PhotoCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$Photos = $UcPhoto->hasPhotoAndAlbum($page,$limit,$where);
		foreach($Photos as $key=>$val){
			$Photos[$key]['photo_path'] = getSmallPicPath($val['photo_path']);
		}
		$url.='page=';

		$pageHtml = $this->page($url,$pcount,$limit,$page,count($Photos));
		$this->assign('url',$url.$page);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('Photos',$Photos);
		$this->assign('page',$page);
		$this->display('photo');
	}

	/*
	* 图片删除
	*/
	public function deletePhoto(){
	
		$ids = $this->_get('deletePhoto');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$UcPhoto = D('UcPhoto');
		$UcAlbum = D('UcAlbum');
		$UcDiary = D("UcDiary");
		$ucPhotoComment = D('UcPhotoComment');
		$apiModel = D('Api');
		foreach($idArr as $key=>$val){
			if($val){
				$uid = $UcPhoto->where(array('photo_id'=>$val))->select();
				//删除日志中的图片
				if($uid[0]['object_id']!=''){
					$UcDiary->deleteDiaryPhoto($val,$uid[0]['object_id']);
				}
				if($isNotice==1){	
					if($uid[0]['object_type']==1){
						$this->recordOperations(2,3,$val);
						$this->setNotice($val,$uid[0]['uid'],7,6);
						
					}else{
						$this->recordOperations(2,3,$val,$isNotice,$uid[0]['uid'],7);
					}
				}else{
					$this->recordOperations(2,3,$val,$isNotice);
				}
				
				$ucPhotoComment->where(array('photo_id'=>$val))->save(array('status'=>-1));
				//用户照片数量 总数 -1
				$apiModel -> userExtendHandle('photo_num',$uid[0]['uid'],'dec');
				$UcAlbum->changeAlbumCapacity(array('uid'=>$uid[0]['uid'],'changeNum'=>$uid[0]['size']),2);
				$UcPhoto->where(array('photo_id'=>$val))->save(array('status'=>-1));
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Album/photo?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	
	}

	/*
	*图片评论管理
	*/
	public function photoComment(){
		$limit=20;
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$where="comment.uid=user.uid and comment.photo_id=photo.photo_id and comment.status=0";
		//$starttime = date('Y-m-d');
		$url='/iadmin.php/Album/photoComment?';
		
		//搜索条件
		$noAllow = C('NO_ALLOW');

		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['content'],$noAllow) && !empty($data['content'])){
				$where.=" and comment.content like '%".$data['content']."%' ";
				$url.='data[content]='.urlencode($data['content']).'&';
				$this->assign('content',$data['content']);
			}

			if(trim($data['starttime'])){
				$where.=" and comment.dateline>=".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and comment.dateline<=".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			
			if(!in_array($data['photoname'],$noAllow) && !empty($data['photoname'])){
				$where.=" and photo.photo_name like '%".$data['photoname']."'";
				$url.='data[photoname]='.urlencode($data['photoname']).'&';
				$this->assign('photoname',$data['photoname']);
			}

			if(!in_array($data['albumid'],$noAllow) && !empty($data['albumid'])){
				$where.=" and photo.album_id=".$data['albumid'];
				$url.='data[albumid]='.$data['albumid'].'&';
				$this->assign('albumid',$data['albumid']);
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


		//echo $where;
		$ucPhotoComment = D('UcPhotoComment');
		$PhotoCommentCount = $ucPhotoComment->hasPhotoCommentCount($where);
		$pcount = ceil($PhotoCommentCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$url.='page=';
		

		$PhotoComments = $ucPhotoComment->hasUserAndPhotoComment($page,$limit,$where);

		$pageHtml = $this->page($url,$pcount,$limit,$page,count($PhotoComments));
		if($PhotoComments){
			foreach($PhotoComments as $key=>$val){
				$albumIds[] = $val['album_id'];
				$PhotoComments[$key]['photo_path'] = getSmallPicPath($val['photo_path']);
			}
			
			$UcAlbum = D('UcAlbum');
			$Albums = $UcAlbum->where(array('id'=>array('in',$albumIds)))->select();
		
			foreach($PhotoComments as $key=>$val){
				foreach($Albums as $k=>$v){
					if($val['album_id']==$v['id']){
						$PhotoComments[$key]['albumName'] = $v['title'];
					}
				}
			}
		}

		$this->assign('url',$url.$page);
		$this->assign('PhotoComments',$PhotoComments);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->display('photoComment');
	}


	/*
	*删除图片评论
	*/
	public function deletePhotoComment(){
		$ids = $this->_get('deletePhotoComment');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$ucPhotoComment = D('UcPhotoComment');
		$ucPhoto = D('UcPhoto');
		foreach($idArr as $key=>$val){
			if($val){
				$uid = $ucPhotoComment->where(array('id'=>$val))->select();
				if($isNotice==1){
					$this->recordOperations(2,8,$val,$isNotice,$uid[0]['uid'],4);
				}else{
					$this->recordOperations(2,8,$val);
				}
			
				$comments = $ucPhoto->where(array('photo_id'=>$uid[0]['photo_id']))->select();
				if($comments[0]['comments']>0){
					//删除图片评论数
					$data['comments']=array('exp','comments-1');
					$ucPhoto->where(array('photo_id'=>$uid[0]['photo_id']))->save($data);
					
				}

				$ucPhotoComment->where(array('id'=>$val))->save(array('status'=>-1));
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Album/photoComment?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	
	}
}
?>
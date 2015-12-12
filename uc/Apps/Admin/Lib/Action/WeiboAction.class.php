<?php
/*
*WeiboAction 微博
*/
class WeiboAction extends ExtendAction{
	
	/*
	*微博列表页
	*/
	public function index(){
		$limit=10;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}

		$where="weibo.uid=user.uid and weibo.status=0";
		//$starttime = date('Y-m-d');
		$url='/iadmin.php/Weibo/index?';
	
		//搜索条件
		$noAllow = C('NO_ALLOW');

		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['content'],$noAllow) && !empty($data['content'])){
				$where.=" and weibo.weibo_content like '%".$data['content']."%' ";
				$url.='data[content]='.urlencode($data['content']).'&';
				$this->assign('content',$data['content']);
			}

			if(trim($data['starttime'])){
				$where.=" and weibo.weibo_time >= ".strtotime($data['starttime'].' 00:00:00');
				
				$url.='data[starttime]='.$data['starttime'].'&';
				
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and weibo.weibo_time <= ".strtotime($data['endtime'].' 23:59:59');
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


		$ucWeibo = D('UcWeibo');
		$WeiboCount = $ucWeibo->hasWeiboCount($where);
		$pcount = ceil($WeiboCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
	
		$url.='page=';
		
		$Weibos = $ucWeibo->hasUserAndWeibo($page,$limit,$where);
		foreach($Weibos as $key=>$val){
			$Weibos[$key]['weibo_pic'] = getSmallPicPath($val['weibo_pic'],"_s");
		}
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($Weibos));
		$this->assign('url',$url.$page);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('Weibos',$Weibos);
		$this->display('index');	
	}


	/*
	*删除微博
	*/
	public function deleteWeibo(){
		$ids = $this->_get('deleteWeibo');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$ucWeibo = D('UcWeibo');
		$ucWeiboReply=D('UcWeiboReply');
		$apiModel = D('Api');
		foreach($idArr as $key=>$val){
			if($val){
				if($isNotice==1){
					$uid = $ucWeibo->where(array('id'=>$val))->select();
					$this->recordOperations(2,4,$val,$isNotice,$uid[0]['uid'],2);
				}else{
					$this->recordOperations(2,4,$val);
				}

				$uidArr = $ucWeibo->field('uid')->where(array('id'=>$val))->find();
				//用户微博数量 总数 -1
				$apiModel->userExtendHandle('weibo_num',$uidArr['uid'],'dec');
				$ucWeibo->where(array('id'=>$val))->save(array('status'=>1));
				//同时删除微博评论
				$ucWeiboReply->where(array('wid'=>$val))->save(array('status'=>1));
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Weibo/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}
	
	/*
	*微博评论列表
	*/
	public function weiboComment(){
		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}		
		
		$where="reply.uid=user.uid and reply.wid=weibo.id and reply.status=0";
		//$starttime = date('Y-m-d');
		$url='/iadmin.php/Weibo/weiboComment?';
	
		//搜索条件
		$noAllow = C('NO_ALLOW');

		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['content'],$noAllow) && !empty($data['content'])){
				$where.=" and reply.message like '%".$data['content']."%' ";
				$url.='data[content]='.urlencode($data['content']).'&';
				$this->assign('content',$data['content']);
			}
			if(trim($data['starttime'])){
				$where.=" and reply.dateline>=".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and reply.dateline<=".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if(!in_array($data['wid'],$noAllow) && is_numeric($data['wid'])){
				$where.=" and reply.wid=".$data['wid'];
				$url.='data[wid]='.$data['wid'].'&';
				$this->assign('wid',$data['wid']);
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
		if($this->_get('wid')){
			$this->assign('wid',$this->_get('wid'));
			$url.='wid='.$this->_get('wid').'&';
			$where .=" and reply.wid=".$this->_get('wid'); 
		}
		$ucWeiboReply = D('UcWeiboReply');
		$WeiboReplyCount = $ucWeiboReply->hasWeiboReplyCount($where);
		$pcount = ceil($WeiboReplyCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$url.='page=';
		$WeiboReplys = $ucWeiboReply->hasUserAndWeiboReply($page,$limit,$where);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($WeiboReplys));

		$this->assign('url',$url.$page);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('WeiboReplys',$WeiboReplys);
		$this->display('weiboComment');
	}

	/*
	*删除微博评论
	*/
	public function deleteWeiboReply(){
		$ids = $this->_get('deleteWeiboReply');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$ucWeiboReply = D('UcWeiboReply');
		$ucWeibo = D('UcWeibo');
		foreach($idArr as $key=>$val){
			if($val){
				$uid = $ucWeiboReply->where(array('id'=>$val))->select();
				if($isNotice==1){
					$this->recordOperations(2,5,$val,$isNotice,$uid[0]['uid'],5);
				}else{
					$this->recordOperations(2,5,$val);
				}
				$ucWeiboReply->where(array('id'=>$val))->save(array('status'=>1));
				$comments = $ucWeibo->where(array('id'=>$uid[0]['wid']))->select();
				if($comments[0]['comments']>0){
					//修改本条微博的评论数
					$data['comments']=array('exp','comments-1');
					$ucWeibo->where(array('id'=>$uid[0]['wid']))->save($data);
				}
			
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Weibo/weiboComment?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}
}
?>
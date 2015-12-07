<?php
/**
*Team Action 小组控制器
*/
class TeamAction extends ExtendAction{
	


	/*
	*小组列表页
	*/
	public function index(){
		$bkTeamModel = D('BkTeam');
		$page = $this->_get('page');
		if(!is_numeric($page) || $page<=0){
			$page=1;
		}
		$limit=20;
		$where['status']=0;
		$count = $bkTeamModel->hasTeamCount($where);
		$pcount = ceil($count/$limit);
		if($page>=$pcount){
			$page = $pcount;
		}
		$teams = $bkTeamModel->hasManyTeam($page,$limit,$where);
		$url='/iadmin.php/Team/index?page=';

		$pageHtml = $this->page($url,$pcount,$limit,$page,count($teams));
		

		$this->assign('url',$url.$page);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('teams',$teams);
		$this->display('index');
	}


	/*
	*编辑
	*/
	public function teamEdit(){
		$bkTeamModel = D('BkTeam');

		if($this->_get('tid')){
			$tid = $this->_get('tid');
			$team = D('BkTeam')->getTeamInfo($tid);
			$teamUser = $bkTeamModel-> getTeamManagerId($tid);
			$this->assign('team_uid',$teamUser);
			$this->assign('page',$this->_get('page'));
			$this->assign('team',$team);
		}

		if($this->_post('data')){
			$data=$this->_post('data');

			$field = array(
					'id'=>array(
						'title'=>'编号'
					),
					'name'=>array(
						'title'=>'标题'	
					),
					'pic_path'=>array(
						'title'=>'图片路径'
					),
					'introduce'=>array(
						'title'=>'简介',
						'flag'=>1
					)
				);
			
			$this->groupTip('BkTeam','id',$data['id'],$field,$data,27);
			
			$bkTeamModel->saveTeamInfo($data);
			$teamId = $data['id'];
			//获取小组管理员
			$teamUids = $this->_post('team_uid');
			$teamManager = $bkTeamModel->getTeamManagerId($teamId);
			
			foreach($teamManager as $key=>$val){
				if(!in_array($val['uid'],$teamUids)){
					$bkTeamModel->cancelTeamManager($val['uid'],$teamId,1);
				}
			}
			
			foreach($teamUids as $key=>$val){
				//判断该用户是否在该小组中如果存在修改等级否则直接写入新数据
				if($val){
					//判断该用户是否存在
					$userInfo = $bkTeamModel->getBoqiiUserInfo($val);
					if($userInfo){
						$user = $bkTeamModel->getTeamUser($val,$teamId);
						if(!$user){
							$bkTeamModel->addTeamManager($val,$teamId);
						}else{
							$bkTeamModel->cancelTeamManager($val,$teamId,5);
						}
					}
				}
			}	
			$url = C('I_DIR')."/iadmin.php/Team/index?page=".$this->_post('page');
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url"); 
		}

		$this->display('teamEdit');
	}

	//更新小组用户数
	public function updateUserNum(){
		//获取所有小组update bk_team bt set bt.user_num=(select count(1) from bk_team_user btu where btu.team_id=bt.id and btu.status=0)
		$teams = D('BkTeam')->where('status=0')->field('id,user_num')->select();
		foreach($teams as $key=>$val){
			$couunt = M('BkTeamUser')->where('status=0 and team_id='.$val['id'])->count();
			D('BkTeam')->where('id='.$val['id'])->save(array('user_num'=>$couunt));
		}
		exit;
	}

}
?>
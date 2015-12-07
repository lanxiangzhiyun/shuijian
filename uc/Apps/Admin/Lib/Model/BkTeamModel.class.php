<?php
/**
 * BkTeam Model类
 */
class BkTeamModel extends Model{
	
	protected $tableName='bk_team';

	
	
	/*
	*获得小组
	*/
	public function hasManyTeam($page,$limit,$where){
		$result = $this->where($where)->order('id desc')->field('id,name,introduce,user_num,thread_num,pic_path,create_time')->limit($limit)->page($page)->select();
		return $result;
	}

	/*
	*获取小组个数
	*/
	public function hasTeamCount($where){
		$result = $this->where($where)->count();
		return $result;
	}
	/*
	*获取小组信息
	*/
	public function getTeamInfo($tid){
		$result = $this->where(array('id'=>$tid))->find();
		return $result;
	}
	/*
	*获取小组管理员
	*/
	public function getTeamManagerId($teamId){
		$result = M()->table('bk_team_user')->where('team_id='.$teamId.' and level=5 and status=0')->select();
		return $result;
	}
	/*
	*修改小组用户等级
	*/
	public function cancelTeamManager($uid,$teamId,$level){
		if($level==5){
			$userInfo = M()->table('bk_team_user')->where('team_id='.$teamId.' and uid='.$uid)->find();
			if(in_array($userInfo['status'],array(-1,-2))){
				$this->where('id='.$teamId)->setInc('user_num',1);
			}
		}
		$result = M()->table('bk_team_user')->where('team_id='.$teamId.' and uid='.$uid)->save(array('level'=>$level,'status'=>0));
		return $result;
	}
	/*
	*获取小组普通用户
	*/
	public function getTeamUser($uid,$teamId){
		$result = M()->table('bk_team_user')->where('team_id='.$teamId.' and uid='.$uid)->find();
		return $result;
	}

	/*
	*添加管理员
	*/
	public function addTeamManager($uid,$teamId){
		$data['team_id'] = $teamId;
		$data['uid'] = $uid;
		$data['level'] = 5;
		$data['status'] = 0;
		$data['create_time'] = time();
		$result = M()->table('bk_team_user')->add($data);

		$this->where('id='.$teamId)->setInc('user_num',1);
		return $result;
	}
	/*
	*保存小组信息
	*/
	public function saveTeamInfo($data){
		$data['update_time']=time();

		if(isset($data['id'])){
			$result = $this->save($data);
		}else if(isset($data['category_id'])){
			$categoryId = $data['category_id'];
			unset($data['category_id']);
			$result = $this->where('category_id='.$categoryId)->save($data);
		}else{
			$data['create_time'] = time();
			$result = $this->add($data);
		}

		return $result;
	}

	/*
	*获取用户
	*/

	public function getBoqiiUserInfo($uid){
		$result = M()->table('boqii_users')->where('uid='.$uid)->find();
		return $result;
	}
}

?>
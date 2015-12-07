<?php
/**
 * UcUser Model类
 */
class UcUserModel extends RelationModel{

	protected $tableName='boqii_users';

	/*
	*根据用户ID获取用户信息
	*/
	public function getUserInfo($uid){
		$user = M()->table('boqii_users u,boqii_users_extend e')->where('u.uid='.$uid.' and u.uid=e.uid')->field('u.uid,u.nickname,u.is_baike,e.avatar,e.gender')->find();
		$user['avatar'] = C('BLOG_DIR') . '/' .$user['avatar'];
		$user['url_link']=C('I_DIR').'/u/'.$uid;
		$user['baike_level']=0;
		if($user['is_baike']==1){
			$baikeUser = M()->Table('boqii_users_extendbaike')->where(array('uid'=>$uid))->field('uid,name,pic_path,level,introduce,attention_num')->find();
			$user['baike_introduce'] = $baikeUser['introduce'];
			$user['baike_level'] = $baikeUser['level'];
			$user['attention_num'] = $baikeUser['attention_num'];
			if($baikeUser['level']==5){
				$user['nickname'] = $baikeUser['name'];
				$user['avatar'] = C('BK_DIR') . '/' .$baikeUser['pic_path'];
				$user['url_link']=C('BLOG_DIR').'/e/'.$uid;
			}else{
				$user['url_link']=C('I_DIR').'/u/'.$uid;
			}
		}
		$user['avatar_m'] = str_replace('_b','_m', $user['avatar']);
		//$user['avatar_m'] = getSmallPicPath($user['avatar'],'_b','_m');
		return $user;
	}
}

?>
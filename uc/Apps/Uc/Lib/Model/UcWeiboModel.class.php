<?php
/**
 * 微博Model类
 *
 * @created 2012-09-04
 * @author yumie
 */
class UcWeiboModel extends Model {
	protected $trueTableName = 'uc_weibo';
	/**
	 * 我的微博
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 微博列表
	 */
	public function getMyWeibo($param){
		$uid = $param['uid'];
		$where = 'uid ='.$uid.' and status = 0 and weibo_time <= '.strtotime('2015-08-06 00:00:00');
		// $where = 'uid ='.$uid.' and status = 0 ';
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_weibo')->where($where)->count();
		$weiboarr = M()->Table('uc_weibo')->where($where)->order('weibo_time desc')->limit("$page_start, $page_num")->select();
		$list = array();
		$apiModel = D('Api');
		foreach($weiboarr as $lists){
			//微博评论信息
			$param['wid'] = $lists['id'];
			$lists['commentlist'] = $this->getRecentComments($param);
			
			$lists['weibo_time'] = format_time($lists['weibo_time']);
			$lists['weibo_picbig'] = str_replace('_y','_b',$lists['weibo_pic']);
			$lists['weibo_picsml'] = str_replace('_y','_s',$lists['weibo_pic']);
			
			//个人信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//使用缩略图(中)
			//$lists['gender'] = $userinfo['gender'];
			$lists['url_link'] = $userinfo['url_link'];

			//判断是否是当前用户发的微博
			if($lists['uid'] == $uid){
				$lists['isuid'] = 1;
			}else{
				$lists['isuid'] = 0;
			}
			
			//查找被转播微博评论数和转播数
			$oarr = M()->Table('uc_weibo')->where('id ='.$lists['relayid'])->find();
			//判断当前用户是否已转播该条微博，已转播-灰选（针对当前微博）

			//初始化转发此微博的用户
			$cacheRedis = Cache::getInstance('Redis');
			$this->getWeiboRelayInitialize($lists['id']);
	
			//读取缓存
			$redisKey = C('REDIS_KEY.weibo').$lists['id'];
			$uidArray = $cacheRedis->zGetByIndexDesc($redisKey);

			if(in_array($uid,$uidArray)){
				$lists['recast'] = 1;
			}else{
				$lists['recast'] = 0;
			}

			/*$num2 = M()->Table('uc_weibo')->where('uid ='.$uid.' and relayid='.$lists['id'].' and status = 0')->count();
			if($num2 > 0){
				$lists['recast'] = 1;
			}else{
				$lists['recast'] = 0;
			}*/
			//非原创微博
			if($lists['flag'] == 2){
				//判断当前用户是否已转播该条微博，已转播-灰选（针对原微博）

				//初始化转发此微博的用户
				$cacheRedis = Cache::getInstance('Redis');
				$this->getWeiboRelayInitialize($oarr['id']);
		
				//读取缓存
				$redisKey = C('REDIS_KEY.weibo').$oarr['id'];
				$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
				
				if(in_array($uid,$uidArrayTwo)){
					$lists['orecast'] = 1;
				}else{
					$lists['orecast'] = 0;
				}
				/*$num1 = M()->Table('uc_weibo')->where('uid ='.$uid.' and relayid='.$oarr['id'].' and status = 0')->count();
				if($num1 > 0){
					$lists['orecast'] = 1;
				}else{
					$lists['orecast'] = 0;
				}*/
				
				//判断是否是当前用户发的微博(原微博)
				if($oarr['uid'] == $uid){
					$lists['oisuid'] = 1;
				}else{
					$lists['oisuid'] = 0;
				}
				
				//判断原微博是否已删除
				if($oarr['status'] == 1){
					$lists['isdelete'] = 1;
				}else{
					//查找最原始微博的内容
					$oarr2 = M()->Table('uc_weibo')->where('id ='.$lists['oid'])->find();
					$ouserinfo = $apiModel->getUserInfo($oarr2['uid']);
					$lists['onickname'] = '@'.$ouserinfo['nickname'];
					$lists['ouid'] = $ouserinfo['uid'];
					$lists['ourl_link'] = $ouserinfo['url_link'];
					$lists['oweibo_time'] = format_time($oarr2['weibo_time']);
					$lists['oweibo_picbig'] = str_replace('_y','_b',$oarr2['weibo_pic']);
					$lists['oweibo_picsml'] = str_replace('_y','_s',$oarr2['weibo_pic']);
					$lists['obroadcasts'] = $oarr2['broadcasts'];
					$lists['ocomments'] = $oarr2['comments'];
					
					if($oarr['flag'] == 1){
						//生成后内容
						$lists['oweibo_content'] = $oarr['weibo_content'];
					}else{
						//多层转播拼成@xx
						$relayids = M()->Table('uc_weibo')->where('oid ='.$oarr['oid'].' and uid <>'.$lists['uid'].' and weibo_time <= '.$oarr['weibo_time'].'')->field('uid')->order('weibo_time desc')->limit(10)->select();
						$atarray = array();
						foreach($relayids as $v){
							$ruserinfo = $apiModel->getUserInfo($v['uid']);
							$atarray[] = "//<a href='".$ruserinfo['url_link']."'>@".$ruserinfo['nickname']."</a>";
						}
						$atstr = implode(" ",$atarray);
						$lists['atstr'] = $atstr;
						//生成后内容
						$lists['oweibo_subject'] = $lists['atstr']."&nbsp;&nbsp;";
						$lists['oweibo_content'] = $oarr['weibo_content'];
					}
				}
			}
			
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 热门微博
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 微博列表
	 */
	public function getHotWeibo($param){
		$uid = $param['uid'];
		$oruid = $param['oruid'];
		// $where = 'status = 0 ';
		$where = 'status = 0 and weibo_time <= '.strtotime('2015-08-06 00:00:00');
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_weibo')->where($where)->count();
		$weiboarr = M()->Table('uc_weibo')->where($where)->order('comments desc,weibo_time desc')->limit("$page_start, $page_num")->select();
		$list = array();
		$apiModel = D('Api');
		foreach($weiboarr as $lists){
			//微博评论信息
			$param['wid'] = $lists['id'];
			$lists['commentlist'] = $this->getRecentComments($param);
			
			$lists['weibo_time'] = format_time($lists['weibo_time']);
			$lists['weibo_picbig'] = str_replace('_y','_b',$lists['weibo_pic']);
			$lists['weibo_picsml'] = str_replace('_y','_s',$lists['weibo_pic']);
			//发布者个人信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//使用缩略图(中)
			//$lists['gender'] = $userinfo['gender'];
			$lists['url_link'] = $userinfo['url_link'];
			
			//判断是否是当前用户发的微博
			if($uid){
				if($lists['uid'] == $uid){
					$lists['isuid'] = 1;
				}else{
					$lists['isuid'] = 0;
				}
			}else{
				$lists['isuid'] = 0;
			}

			//查找被转播微博评论数和转播数
			$oarr = M()->Table('uc_weibo')->where('id ='.$lists['relayid'])->find();
			
			//判断当前用户是否已转播该条微博，已转播-灰选（针对当前微博）
			if($uid){
				//初始化转发此微博的用户
				$cacheRedis = Cache::getInstance('Redis');
				$this->getWeiboRelayInitialize($lists['id']);
		
				//读取缓存
				$redisKey = C('REDIS_KEY.weibo').$lists['id'];
				$uidArray = $cacheRedis->zGetByIndexDesc($redisKey);
				
				if(in_array($uid,$uidArray)){
					$lists['recast'] = 1;
				}else{
					$lists['recast'] = 0;
				}

				/*$num2 = M()->Table('uc_weibo')->where('uid ='.$uid.' and relayid='.$lists['id'].' and status = 0')->count();
				if($num2 > 0){
					$lists['recast'] = 1;
				}else{
					$lists['recast'] = 0;
				}*/
			}else{
				$lists['recast'] = 0;
			}
			
			//非原创微博
			if($lists['flag'] == 2){
				
				//判断当前用户是否已转播该条微博，已转播-灰选（针对原微博）
				if($uid){
					//初始化转发此微博的用户
					$cacheRedis = Cache::getInstance('Redis');
					$this->getWeiboRelayInitialize($oarr['id']);
			
					//读取缓存
					$redisKey = C('REDIS_KEY.weibo').$oarr['id'];
					$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
					//print_r($uidArrayTwo);
					if(in_array($uid,$uidArrayTwo)){
						$lists['orecast'] = 1;
					}else{
						$lists['orecast'] = 0;
					}

					/*$num1 = M()->Table('uc_weibo')->where('uid ='.$uid.' and relayid='.$oarr['id'].' and status = 0')->count();
					if($num1 > 0){
						$lists['orecast'] = 1;
					}else{
						$lists['orecast'] = 0;
					}*/
				}else{
					$lists['orecast'] = 0;
				}
					
				//判断是否是当前用户发的微博(原微博)
				if($uid){
					if($oarr['uid'] == $uid){
						$lists['oisuid'] = 1;
					}else{
						$lists['oisuid'] = 0;
					}
				}else{
					$lists['oisuid'] = 0;
				}
					
				
				//判断原微博是否已删除
				if($oarr['status'] == 1){
					$lists['isdelete'] = 1;
				}else{
					//查找最原始微博的内容
					$oarr2 = M()->Table('uc_weibo')->where('id ='.$lists['oid'])->find();
					$ouserinfo = $apiModel->getUserInfo($oarr2['uid']);
					$lists['onickname'] = '@'.$ouserinfo['nickname'];
					$lists['ouid'] = $ouserinfo['uid'];
					$lists['ourl_link'] = $ouserinfo['url_link'];
					$lists['oweibo_time'] = format_time($oarr2['weibo_time']);
					$lists['oweibo_pic'] = $oarr2['weibo_pic'];
					$lists['oweibo_picbig'] = str_replace('_y','_b',$oarr2['weibo_pic']);
					$lists['oweibo_picsml'] = str_replace('_y','_s',$oarr2['weibo_pic']);
					$lists['obroadcasts'] = $oarr2['broadcasts'];
					$lists['ocomments'] = $oarr2['comments'];
					
					if($oarr['flag'] == 1){
						//生成后内容
						$lists['oweibo_content'] = $oarr['weibo_content'];
					}else{
						//多层转播拼成@xx
						$relayids = M()->Table('uc_weibo')->where('oid ='.$oarr['oid'].' and uid <>'.$lists['uid'].' and weibo_time <= '.$oarr['weibo_time'].'')->field('uid')->order('weibo_time desc')->limit(10)->select();
						$atarray = array();
						foreach($relayids as $v){
							$ruserinfo = $apiModel->getUserInfo($v['uid']);
							$atarray[] = "//<a href='".$ruserinfo['url_link']."'>@".$ruserinfo['nickname']."</a>";
						}
						$atstr = implode(" ",$atarray);
						$lists['atstr'] = $atstr;
						//生成后内容
						$lists['oweibo_subject'] = $lists['atstr']."&nbsp;&nbsp;";
						$lists['oweibo_content'] = $oarr['weibo_content'];
					}
				}
			}
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 他人的微博
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      yuid int 当前登录用户uid
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 微博列表
	 */
	public function getOtherWeibo($param){
		$uid = $param['uid'];
		$yuid = $param['yuid'];
		// $where = 'uid ='.$uid.' and status = 0';
		$where = 'uid ='.$uid.' and status = 0 and weibo_time <= '.strtotime('2015-08-06 00:00:00');;
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_weibo')->where($where)->count();
		$weiboarr = M()->Table('uc_weibo')->where($where)->order('weibo_time desc')->limit("$page_start, $page_num")->select();
		$list = array();
		$apiModel = D('Api');
		foreach($weiboarr as $lists){
			//微博评论信息
			$param['wid'] = $lists['id'];
			$lists['commentlist'] = $this->getRecentOtherComments($param);
			
			$lists['weibo_time'] = format_time($lists['weibo_time']);
			$lists['weibo_picbig'] = str_replace('_y','_b',$lists['weibo_pic']);
			$lists['weibo_picsml'] = str_replace('_y','_s',$lists['weibo_pic']);
			
			//个人信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//使用缩略图(中)
			//$lists['gender'] = $userinfo['gender'];
			$lists['url_link'] = $userinfo['url_link'];
			
			//判断是否是当前用户发的微博
			if($yuid){
				if($lists['uid'] == $yuid){
					$lists['isuid'] = 1;
				}else{
					$lists['isuid'] = 0;
				}
			}else{
				$lists['isuid'] = 0;
			}
			
			//查找被转播微博评论数和转播数
			$oarr = M()->Table('uc_weibo')->where('id ='.$lists['relayid'])->find();
			if($yuid){
				//判断当前登录用户是否已转播该条微博，已转播-灰选（针对当前微博）

				//初始化转发此微博的用户
				$cacheRedis = Cache::getInstance('Redis');
				$this->getWeiboRelayInitialize($lists['id']);
	
				//读取缓存
				$redisKey = C('REDIS_KEY.weibo').$lists['id'];
				$uidArray = $cacheRedis->zGetByIndexDesc($redisKey);
				
				if(in_array($yuid,$uidArray)){
					$lists['recast'] = 1;
				}else{
					$lists['recast'] = 0;	
				}

				/*$num2 = M()->Table('uc_weibo')->where('uid ='.$yuid.' and relayid='.$lists['id'].' and status = 0')->count();
				if($num2 > 0){
					$lists['recast'] = 1;
				}else{
					$lists['recast'] = 0;
				}*/
			}else{
				$lists['recast'] = 0;
			}
			
			//非原创微博
			if($lists['flag'] == 2){
				if($yuid){
					//判断当前用户是否已转播该条微博，已转播-灰选（针对原微博）

					//初始化转发此微博的用户
					$cacheRedis = Cache::getInstance('Redis');
					$this->getWeiboRelayInitialize($oarr['id']);
	
					//读取缓存
					$redisKey = C('REDIS_KEY.weibo').$oarr['id'];
					$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
					//print_r($uidList2);
					if(in_array($yuid,$uidArrayTwo)){
						$lists['orecast'] = 1;
					}else{
						$lists['orecast'] = 0;
					}
					/*$num1 = M()->Table('uc_weibo')->where('uid ='.$yuid.' and relayid='.$oarr['id'].' and status = 0')->count();
					if($num1 > 0){
						$lists['orecast'] = 1;
					}else{
						$lists['orecast'] = 0;
					}*/
				}else{
					$lists['orecast'] = 0;
				}
				
				if($yuid){
					//判断是否是当前用户发的微博(原微博)
					if($oarr['uid'] == $yuid){
						$lists['oisuid'] = 1;
					}else{
						$lists['oisuid'] = 0;
					}
				}else{
					$lists['oisuid'] = 0;
				}
				
				//判断原微博是否已删除
				if($oarr['status'] == 1){
					$lists['isdelete'] = 1;
				}else{
					//查找最原始微博的内容
					$oarr2 = M()->Table('uc_weibo')->where('id ='.$lists['oid'])->find();
					$ouserinfo = $apiModel->getUserInfo($oarr2['uid']);
					$lists['onickname'] = '@'.$ouserinfo['nickname'];
					$lists['ouid'] = $ouserinfo['uid'];
					$lists['ourl_link'] = $ouserinfo['url_link'];
					$lists['oweibo_time'] = format_time($oarr2['weibo_time']);
					$lists['oweibo_pic'] = $oarr2['weibo_pic'];
					$lists['oweibo_picbig'] = str_replace('_y','_b',$oarr2['weibo_pic']);
					$lists['oweibo_picsml'] = str_replace('_y','_s',$oarr2['weibo_pic']);
					$lists['obroadcasts'] = $oarr2['broadcasts'];
					$lists['ocomments'] = $oarr2['comments'];
					
					if($oarr['flag'] == 1){
						//生成后内容
						$lists['oweibo_content'] = $oarr['weibo_content'];
					}else{
						//多层转播拼成@xx
						$relayids = M()->Table('uc_weibo')->where('oid ='.$oarr['oid'].' and uid <>'.$lists['uid'].' and weibo_time <= '.$oarr['weibo_time'].'')->field('uid')->order('weibo_time desc')->limit(10)->select();
						$atarray = array();
						foreach($relayids as $v){
							$ruserinfo = $apiModel->getUserInfo($v['uid']);
							$atarray[] = "//<a href='".$ruserinfo['url_link']."'>@".$ruserinfo['nickname']."</a>";
						}
						$atstr = implode(" ",$atarray);
						$lists['atstr'] = $atstr;
						//生成后内容
						$lists['oweibo_subject'] = $lists['atstr']."&nbsp;&nbsp;";
						$lists['oweibo_content'] = $oarr['weibo_content'];
					}
				}
			}
			
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 转播微博
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      wid int 微博id
	 *
	 * @return array 处理结果
	 */
	public function relayWeibo($param){
		$uid = $param['uid'];
		
		//原微博内容
		$oweibo = M()->Table('uc_weibo')->where('id ='.$param['wid'])->find();
		if($oweibo['relayid'] ==0){
			$data['oid'] = $oweibo['id'];
			//如果原微博已被删除，则返回提示
			$zweibo = M()->Table('uc_weibo')->where('id ='.$oweibo['id'])->find();
		}else{
			$data['oid'] = $oweibo['oid'];
			//如果原微博已被删除，则返回提示
			$zweibo = M()->Table('uc_weibo')->where('id ='.$oweibo['oid'])->find();
		}
		
		//原微博作者nickname
		$uinfo =  D('Api')->getUserInfo($oweibo['uid']);
		
		if($zweibo['status'] == 1){
			return 0;
		}else{
			//判断是否是黑名单
			$isblack = D('UcRelation')->getSearchStatus($param['uid'],$oweibo['uid']);
			if($isblack == 4){
				return -2;
			}elseif($isblack == 5){
				return -1;
			}else{
				//转播成功前更新redis
				D('Api')->userExtendHandle('weibo_num',$uid,'inc');

				$data['weibo_content'] = $oweibo['weibo_content'];
				$data['weibo_pic'] = $oweibo['weibo_pic'];
				$data['relayid'] = $param['wid'];
				//$data['oid'] = $oweibo['id'];
				$data['uid'] = $param['uid'];
				$data['weibo_time'] = time();
				$data['flag'] = 2;
				$r = M()->Table('uc_weibo')->add($data);
				
				if($r){
					//更新原微博转播数
					$data1['broadcasts'] = $oweibo['broadcasts']+1;
					M()->Table('uc_weibo')->where('id='.$param['wid'])->save($data1);
					
					//更新动态表
					$data2['uid'] = $param['uid'];
					$data2['type'] = 3;
					$data2['ouid'] = $uinfo['uid'];
					$data2['ousername'] = $uinfo['nickname'];
					$data2['operatetype'] = 2;
					$data2['oid'] = $r;
					$data2['mid'] = $param['wid'];
					$data2['otitle'] = $oweibo['weibo_content'];
					/*if($oweibo['flag'] == 2){
						//原微博ID
						$data2['mid'] = $oweibo['oid'];
					}*/
					$data2['cretime'] = time();
					
					M()->Table('uc_dynamic')->add($data2);

					//初始化
					$this->getWeiboRelayInitialize($param['wid']);
					//转播成功后加缓存
					$this->addWeiboRelayRedis($param['wid'],$uid);
				}
				return $r;
			}
		}
	}
	
	/**
	 * 微博详情
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      wid int 微博id
	 *
	 * @return array 微博信息
	 */
	public function getWeiboDetail($param){
		$apiModel = D('Api');
		$where = 'id = '.$param['wid'].' and status = 0';
		$uid = $param['uid'];
		$weibodetail = M()->Table('uc_weibo')->where($where)->find();
		if(empty($weibodetail)){
			return 0;
		}else{
			//微博发布者与当前用户的关系
			if($uid){
				$weibodetail['friendstatus'] = D('UcRelation')->getSearchStatus($uid,$weibodetail['uid']);
			}
			
			$weibodetail['weibo_time'] = format_time($weibodetail['weibo_time']);
			$weibodetail['weibo_picsml'] = str_replace(basename($weibodetail['weibo_pic']),'s_'.basename($weibodetail['weibo_pic']),$weibodetail['weibo_pic']);
			//发布者信息
			$userinfo = $apiModel->getUserInfo($weibodetail['uid']);
			$weibodetail['nickname'] = $userinfo['nickname'];
			$weibodetail['avatar'] = $userinfo['avatar_m'];//微博发布者使用缩略图(中)
			//$weibodetail['gender'] = $userinfo['gender'];
			$weibodetail['url_link'] = $userinfo['url_link'];
			
			if($uid){
				//判断是否是当前用户发的微博
				if($weibodetail['uid'] == $uid){
					$weibodetail['isuid'] = 1;
				}else{
					$weibodetail['isuid'] = 0;
				}
			}
			//查找被转播微博评论数和转播数
			$oarr = M()->Table('uc_weibo')->where('id ='.$weibodetail['relayid'].'')->find();
			if($uid){
				//判断当前用户是否已转播该条微博，已转播-灰选（针对当前微博）

				//初始化转发此微博的用户
				$cacheRedis = Cache::getInstance('Redis');
				$this->getWeiboRelayInitialize($param['wid']);
	
				//读取缓存
				$redisKey = C('REDIS_KEY.weibo').$param['wid'];
				$uidArray = $cacheRedis->zGetByIndexDesc($redisKey);
				
				if(in_array($uid,$uidArray)){
					$weibodetail['recast'] = 1;
				}else{
					$weibodetail['recast'] = 0;	
				}

				/*$num2 = M()->Table('uc_weibo')->where('uid ='.$uid.' and relayid='.$param['wid'].' and status = 0')->count();
				if($num2 > 0){
					$weibodetail['recast'] = 1;
				}else{
					$weibodetail['recast'] = 0;
				}*/
			}else{
				$weibodetail['recast'] = 0;
			}
			//非原创微博
			if($weibodetail['flag'] == 2){
				if($uid){
					//判断当前用户是否已转播该条微博，已转播-灰选（针对原微博）

					//初始化转发此微博的用户
					$cacheRedis = Cache::getInstance('Redis');
					$this->getWeiboRelayInitialize($oarr['id']);
		
					//读取缓存
					$redisKey = C('REDIS_KEY.weibo').$oarr['id'];
					$uidArrayTwo = $cacheRedis->zGetByIndexDesc($redisKey);
					
					if(in_array($uid,$uidArrayTwo)){
						$weibodetail['orecast'] = 1;
					}else{
						$weibodetail['orecast'] = 0;
					}

					/*$num1 = M()->Table('uc_weibo')->where('uid ='.$uid.' and relayid='.$oarr['id'].' and status = 0')->count();
					if($num1 > 0){
						$weibodetail['orecast'] = 1;
					}else{
						$weibodetail['orecast'] = 0;
					}*/
				}else{
					$weibodetail['orecast'] = 0;
				}
				if($uid){
					//判断是否是当前用户发的微博(原微博)
					if($oarr['uid'] == $uid){
						$weibodetail['oisuid'] = 1;
					}else{
						$weibodetail['oisuid'] = 0;
					}
				}else{
					$weibodetail['oisuid'] = 0;
				}
				
				//判断原微博是否已删除
				if($oarr['status'] == 1){
					$weibodetail['isdelete'] = 1;
				}else{
					//查找最原始微博的内容
					$oarr2 = M()->Table('uc_weibo')->where('id ='.$weibodetail['oid'])->find();
					$ouserinfo = $apiModel->getUserInfo($oarr2['uid']);
					$weibodetail['onickname'] = '@'.$ouserinfo['nickname'];
					$weibodetail['ouid'] = $ouserinfo['uid'];
					$weibodetail['ourl_link'] = $ouserinfo['url_link'];
					$weibodetail['oweibo_time'] = format_time($oarr2['weibo_time']);
					$weibodetail['oweibo_pic'] = $oarr2['weibo_pic'];
					$weibodetail['oweibo_picsml'] = str_replace(basename($oarr2['weibo_pic']),'s_'.basename($oarr2['weibo_pic']),$oarr2['weibo_pic']);
					$weibodetail['obroadcasts'] = $oarr['broadcasts'];
					$weibodetail['ocomments'] = $oarr['comments'];
					
					if($oarr['flag'] == 1){
						//生成后内容
						$weibodetail['oweibo_content'] = $oarr['weibo_content'];
					}else{
						//多层转播拼成@xx
						$relayids = M()->Table('uc_weibo')->where('oid ='.$oarr['oid'].' and uid <>'.$weibodetail['uid'].' and weibo_time <= '.$oarr['weibo_time'].'')->field('uid')->order('weibo_time desc')->limit(10)->select();
						$atarray = array();
						foreach($relayids as $v){
							$ruserinfo = $apiModel->getUserInfo($v['uid']);
							$atarray[] = "//<a href='".$ruserinfo['url_link']."'>@".$ruserinfo['nickname']."</a>";
						}
						$atstr = implode(" ",$atarray);
						$weibodetail['atstr'] = $atstr;
						//生成后内容
						$weibodetail['oweibo_subject'] = $weibodetail['atstr']."&nbsp;&nbsp;";
						$weibodetail['oweibo_content'] = $oarr['weibo_content'];
					}
				}
			}
			return $weibodetail;
		}
	}
	
	/**
	 * 微博评论
	 * 
	 * @param  $param array 参数数组
	 *      wid int 微博id
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 评论列表
	 */
	public function getWeiboComments($param){
		$wid = $param['wid'];
		$where = 'wid = '.$wid.' and status = 0';
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:10;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_weibo_reply')->where($where)->count();
		$commentarr = M()->Table('uc_weibo_reply')->where($where)->order('dateline desc')->limit("$page_start, $page_num")->select();
		$apiModel = D('Api');
		foreach($commentarr as $lists){
			$lists['dateline'] = format_time($lists['dateline']);
			//评论者信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//评论人头像使用缩略图(中)
			//$lists['gender'] = $userinfo['gender'];
			$lists['url_link'] = $userinfo['url_link'];

			//判断该评论下的回复
			if($lists['commentid'] != 0){
				$replyarr = M()->Table('uc_weibo_reply')->where('id ='.$lists['commentid'])->find();
				//原评论用户信息
				$oreplayuser = $apiModel->getUserInfo($replyarr['uid']);
				$lists['ouid'] = $oreplayuser['uid'];
				$lists['ourl_link'] = $oreplayuser['url_link'];
				$lists['onickname'] = $oreplayuser['nickname'];
				$lists['message'] = "回复@"."<a href='".$lists['ourl_link']."'>".$lists['onickname']."</a>:".$lists['message'];
			}
			//判断是否是当前用户发布的评论或者回复
			if($lists['uid'] == $param['uid']){
				$lists['rdel'] = 1;
			}
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 最新前10条评论
	 * 
	 * @param  $param array 参数数组
	 *      wid int 微博id
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 评论列表
	 */
	public function getRecentComments($param){
		$wid = $param['wid'];
		$where = 'wid = '.$wid.' and status = 0';
		
		$commentarr = M()->Table('uc_weibo_reply')->where($where)->order('dateline desc')->limit(10)->select();
		if(!$commentarr) {
			return array();
		}
		$apiModel = D('Api');
		foreach($commentarr as $lists){
			$lists['dateline'] = format_time($lists['dateline']);
			//评论者信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//使用缩略图(中)
			$lists['url_link'] = $userinfo['url_link'];

			//判断该评论下的回复
			if($lists['commentid'] != 0){
				$replyarr = M()->Table('uc_weibo_reply')->where('id ='.$lists['commentid'])->find();
				//原评论用户信息
				$oreplayuser = $apiModel->getUserInfo($replyarr['uid']);
				$lists['ouid'] = $oreplayuser['uid'];
				$lists['ourl_link'] = $oreplayuser['url_link'];
				$lists['onickname'] = $oreplayuser['nickname'];
				$lists['message'] = "回复@"."<a href='".$lists['ourl_link']."'>".$lists['onickname']."</a>:".$lists['message'];
			}
			//判断是否是当前用户发布的评论或者回复
			if($lists['uid'] == $param['uid']){
				$lists['rdel'] = 1;
			}
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 最新前10条评论(他人的微博用)
	 * 
	 * @param  $param array 参数数组
	 *      wid int 微博id
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 评论列表
	 */
	public function getRecentOtherComments($param){
		$wid = $param['wid'];
		$yuid = $param['yuid'];
		$where = 'wid = '.$wid.' and status = 0';
		
		$commentarr = M()->Table('uc_weibo_reply')->where($where)->order('dateline desc')->limit(10)->select();
		$apiModel = D('Api');
		foreach($commentarr as $lists){
			$lists['dateline'] = format_time($lists['dateline']);
			//评论者信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//使用缩略图(中)
			//$lists['gender'] = $userinfo['gender'];
			$lists['url_link'] = $userinfo['url_link'];
			//判断该评论下的回复
			if($lists['commentid'] != 0){
				$replyarr = M()->Table('uc_weibo_reply')->where('id ='.$lists['commentid'])->find();
				//原评论用户信息
				$oreplayuser = $apiModel->getUserInfo($replyarr['uid']);
				$lists['ouid'] = $oreplayuser['uid'];
				$lists['onickname'] = $oreplayuser['nickname'];
				$lists['ourl_link'] = $oreplayuser['url_link'];

				$lists['message'] = "回复@"."<a href='".$lists['ourl_link']."'>".$lists['onickname']."</a>:".$lists['message'];
			}
			//判断是否是当前用户发布的评论或者回复
			if($lists['uid'] == $yuid){
				$lists['rdel'] = 1;
			}
			$list[] = $lists;
		}
		return $list;
	}
		
	/**
	 * 评论
	 *
	 * @param  $param array 参数数组
	 * 		wid int 微博id
	 * 		uid int 当前登录用户uid
	 * 		message string 评论内容
	 *
	 * @return array 处理结果
	 */
	public function replyWeibo($param){
		//原微博评论数
		$oweibo = M()->Table('uc_weibo')->where('id ='.$param['wid'])->find();
		//原微博作者nickname
		$uinfo =  D('Api')->getUserInfo($oweibo['uid']);
		
		$data['wid'] = $param['wid'];
		$data['uid'] = $param['uid'];
		$data['commentid'] = 0;
		$data['dateline'] = time();
		$data['message'] = $param['message'];
		
		//判断是否是黑名单
		$isblack = D('UcRelation')->getSearchStatus($param['uid'],$oweibo['uid']);
		if($isblack == 4){
			return -2;
		}elseif($isblack == 5){
			return -1;
		}else{
			//发评论限制，同一用户10分钟之内不能发相同评论
			$arr = M()->Table('uc_weibo_reply')->where("wid=".$param['wid']." and uid =".$param['uid']." and message='".$param['message']."' and status = 0")->order('dateline desc')->find();
			if(time()-$arr['dateline'] > 600){
				$r = M()->Table('uc_weibo_reply')->add($data);
				//更新原微博评论数
				if($r){
					$data1['comments'] = $oweibo['comments']+1;
					M()->Table('uc_weibo')->where('id='.$param['wid'])->save($data1);
					
					//更新动态表
					$data2['uid'] = $param['uid'];
					$data2['type'] = 3;
					$data2['ouid'] = $uinfo['uid'];
					$data2['ousername'] = $uinfo['nickname'];
					$data2['operatetype'] = 3;
					$data2['oid'] = $r;
					$data2['mid'] = $param['wid'];
					$data2['cretime'] = time();
					
					M()->Table('uc_dynamic')->add($data2);
				}
			
				return $r;
			}else{
				return 0;
			}
		}
	}
	
	/**
	 * 回复
	 *
	 * @param  $param array 参数数组
	 * 		wid int 微博id
	 *      cid int 父评论id
	 * 		uid int 当前登录用户uid
	 * 		message string 评论内容
	 *
	 * @return array 处理结果
	 */
	public function replyComment($param){
		//原微博评论数
		$oweibo = M()->Table('uc_weibo')->where('id ='.$param['wid'])->find();
		//原评论
		$yweibocomments = M()->Table('uc_weibo_reply')->where('id ='.$param['cid'])->find();
		//原评论作者nickname
		$uinfo =  D('Api')->getUserInfo($yweibocomments['uid']);
		
		$data['wid'] = $param['wid'];
		$data['uid'] = $param['uid'];
		$data['commentid'] = $param['cid'];
		$data['dateline'] = time();
		$data['message'] = $param['message'];
		
		//判断是否是黑名单
		$isblack = D('UcRelation')->getSearchStatus($param['uid'],$oweibo['uid']);
		if($isblack == 4){
			return -2;
		}elseif($isblack == 5){
			return -1;
		}else{
			//发评论限制，同一用户10分钟之内不能发相同评论
			$arr = M()->Table('uc_weibo_reply')->where("uid =".$param['uid']." and message='".$param['message']."' and wid=".$param['wid'])->order('dateline desc')->find();
			if(time()-$arr['dateline'] > 600){
				$r = M()->Table('uc_weibo_reply')->add($data);
				//更新原微博评论数
				if($r){
					$data1['comments'] = $oweibo['comments']+1;
					M()->Table('uc_weibo')->where('id='.$param['wid'])->save($data1);
					
					//更新动态表
					$data2['uid'] = $param['uid'];
					$data2['type'] = 5;
					$data2['ouid'] = $uinfo['uid'];
					$data2['ousername'] = $uinfo['nickname'];
					$data2['operatetype'] = 3;
					$data2['oid'] = $r;
					$data2['mid'] = $param['wid'];
					$data2['cretime'] = time();
					
					M()->Table('uc_dynamic')->add($data2);
				}
				return $r;
			}else{
				return '0';
			}
		}
	}
	
	/**
	 * 删除微博
	 *
	 * @param  $param array 参数数组
	 * 		wid int 微博id
	 * 		uid int 当前登录用户uid
	 *
	 * @return array 处理结果
	 */
	public function delWeibo($param){
		//删除前更新redis
		D('Api')->userExtendHandle('weibo_num',$param['uid'],'dec');

		$where = 'id='.$param['wid'].' and uid ='.$param['uid'];
		//删除微博
		$data['status'] = 1;
		//清空评论数
		//$data['comments'] = 0;
		$r = M()->Table('uc_weibo')->where($where)->save($data);

		//删除微博评论以及评论下的回复
		$data1['status'] = 1;
		M()->Table('uc_weibo_reply')->where('wid='.$param['wid'].' and status = 0')->save($data1);
		
		//删除转播的微博，转播数-1
		$w = M()->Table('uc_weibo')->where('id='.$param['wid'].'')->find();
		if($w['relayid'] != 0){
			$data2['broadcasts'] = $w['broadcasts'] - 1;
			M()->Table('uc_weibo')->where('id='.$w['relayid'])->save($data2);
		}
		
		//删除微博的同时删除该条微博的动态  type=3微博,operatetype=1发布 operatetype=2转播
		//$data2['status'] = -1;
		//M()->Table('uc_dynamic')->where('uid='.$param['uid'].' and type = 3 and operatetype!= 3 and oid='.$param['wid'])->save($data2);
		
		//更新redis缓存
		if($w['flag'] == 1){
			//初始化
			$this->getWeiboRelayInitialize($param['wid']);
			$this->delWeiboRelayRedis($param['wid'],'',1);
		}else{
			//初始化
			$this->getWeiboRelayInitialize($w['relayid']);
			$this->delWeiboRelayRedis($w['relayid'],$w['uid'],2);
		}

		return $r;
	}
	
	/**
	 * 微博评论以及回复删除
	 *
	 * @param  $param array 参数数组
	 * 		cid int 评论id
	 *
	 * @return boolean 是否删除成功
	 */
	public function delReply($param){
		$where = 'id='.$param['cid'];
		$data['status'] = 1;
		$r = M()->Table('uc_weibo_reply')->where($where)->save($data);
		if($r){
			//删除评论回复，评论数-1
			$carr = M()->Table('uc_weibo_reply')->where($where)->find();
			$oweibo = M()->Table('uc_weibo')->where('id='.$carr['wid'])->find();
			$data1['comments'] = $oweibo['comments']-1;
			M()->Table('uc_weibo')->where('id='.$carr['wid'])->save($data1);
			
			//删除微博评论和回复的同时删除该条评论/回复的动态  type=3微博,operatetype=3评论
			//$data2['status'] = -1;
			//M()->Table('uc_dynamic')->where('uid='.$param['uid'].' and type = 3 and operatetype = 3 and oid='.$param['cid'])->save($data2);
			return true;
		}
		return false;
	}
	
	/**
	 * 发布微博
	 *
	 * @param  $param array 参数数组
	 * 		uid int 用户id
	 * 		weibo_content string 内容
	 * 		weibo_pic string 图片地址
	 *
	 * @return int 新增微博id
	 */
	public function addWeibo($param){
		//发布成功前更新redis
		D('Api')->userExtendHandle('weibo_num',$param['uid'],'inc');

		$data['uid'] = $param['uid'];
		$data['weibo_content'] = $param['weibo_content'];
		if($param['weibo_pic']) $data['weibo_pic'] = $param['weibo_pic'];
		$data['weibo_time'] = time();
		$data['flag'] = 1;
		
		$r = M()->Table('uc_weibo')->add($data);

		//更新动态表
		$data2['uid'] = $param['uid'];
		$data2['type'] = 3;
		$data2['operatetype'] = 1;
		$data2['oid'] = $r;
		$data2['cretime'] = time();
		
		M()->Table('uc_dynamic')->add($data2);
		return $r;
	}
	
	/**
	 * 好友最近更新的微博
	 *
	 * @param  $uid int 用户id
	 *
	 * @return array 微博信息
	 */
	public function getOtherRecentUpdatesByUid($uid){
		$where = 'uid ='.$uid.' and status = 0';
		$recent = M()->Table('uc_weibo')->where($where)->order('weibo_time desc')->limit(1)->find();
		if($recent){
			if($recent['flag'] == 2){
				$recent['weibo_content'] = '转发微博';
			}
			$recent['weibo_time'] = format_time($recent['weibo_time']);
			return $recent;
		}else{
			return '';
		}
	}
	
	/**
	 * 查询被删除微博的uid
	 *
	 * @param  $wid int 微博id
	 *
	 * @return array 被删除微博信息
	 */
	public function getUidByWeibo($wid){
		$where = 'id = '.$wid.'';
		$weibodetail = M()->Table('uc_weibo')->where($where)->field('uid')->find();
		return $weibodetail;
	}
	
	/**
	 * 取评论用户信息(定位评论用)
	 *
	 * @param  $rid int 评论id
	 *
	 * @return array 用户信息
	 */
	public function getUserinfoByRid($rid){
		$where = 'id = '.$rid.'';
		$ruser = M()->Table('uc_weibo_reply')->where($where)->field('uid')->find();
		$ruser['ruid'] = $ruser['uid'];
		$uinfo = D('Api')->getUserInfo($ruser['uid']);
		$ruser['rnickname'] = $uinfo['nickname'];
		return $ruser;
	}

    /**
     * 取得用户的总微博数
     * 
     * @param $uid int 用户id
     *
     * @return int 总微博数
     */
    public function getUserWeiboCnt($uid) {
        //总微博数
        $weiboCnt = M()->Table("uc_weibo")->where("uid=".$uid." AND status = 0")->count();
        return $weiboCnt;
    }

	/**
	 * 根据微博id取得微博数据
	 *（个人中心首页UcIndexModel类用）
	 *
	 * @param $wid int 微博id
	 *
	 * @return array 微博数据
	 */
	public function getWeiboData($wid) {
		$weibo = M() -> Table("uc_weibo") -> where("id=" . $wid) -> field("id, uid, weibo_content AS content, weibo_pic AS pic_path, weibo_time, flag, relayid, oid, broadcasts, comments, status") -> find();
		//微博发布人信息
		$user = D('Api')->getUserInfo($weibo['uid']);
		$weibo['nickname'] = $user['nickname'];
		$weibo['format_weibo_time'] = format_dynamic_time($weibo['weibo_time']);

		return $weibo;
	} 

	/**
	 * 根据微博评论id取得微博评论数据
	 *（个人中心首页UcIndexModel类用）
	 *
	 * @param $rid int 微博评论id
	 *
	 * @return array 微博数据
	 */
	public function getWeiboReplyData($rid) {
		$reply = M() -> Table("uc_weibo_reply") -> where("id=" . $rid) -> field("id, message, dateline, wid") -> find();

		return $reply;
	} 

	/**
	 * 取得微博评论的回复串接
	 * 超过五层评论回复则以[//...查看全部]串接
	 *（个人中心首页UcIndexModel类用）
	 *
	 * @param  $cid int 评论id
	 * @param  $desc string 串接评论
	 * @param  $num int 当前第几层评论
	 *
	 * @return string 回复评论串接
	 */
	public function getWeiboComment($cid, $desc, $num = 0, $wid = 0) {
		// 只显示5层回复
		if ($num <= 4) {
			$num++; 
			// 微博
			$weibo = M() -> Table("uc_weibo_reply r") -> where("r.status=0 AND r.id=" . $cid) -> field("r.id, r.wid, r.uid, r.commentid, r.dateline, r.message, r.status") -> find();

			if ($desc) {
				$user = D('Api')->getUserInfo($weibo['uid']);
				$desc = $desc . "//<a href='" . $user['url_link'] . "'>@" . $user['nickname'] . "</a>" . "：" . $weibo['message']; //上一级评论内容
			} else {
				$desc = $weibo['message']; //第一级评论内容
			} 
			// 存在上一级评论
			if ($weibo['commentid']) {
				$desc = $this -> getWeiboComment($weibo['commentid'], $desc, $num, $weibo['wid']);
			} 
		} else {
			$weibourl = get_rewrite_url("UcWeibo", "weiboComments", $wid);
			$desc = $desc . "//..." . "<a href='" . $weibourl . "' target='_blank'>" . "查看全部" . " </a>";
		} 

		return $desc;
	} 

	/**
	 * 初始化redis
	 * 查询转播原始微博的用户,写入缓存
	 *
	 * @param $wid int 微博id
	 *
	 * @return boolean
	*/
	public function getWeiboRelayInitialize($wid){
		$cacheRedis = Cache::getInstance('Redis');
		$redisKey = C('REDIS_KEY.weibo').$wid;
		$num = $cacheRedis->zSize($redisKey);
		if($num > 0){
			return;
		}

		//查询转播微博的用户
		$relayUid = M()->Table('uc_weibo')->where('relayid ='.$wid.' and status = 0')->field('uid,weibo_time')->order('weibo_time desc')->select();
		//echo M()->Table('uc_weibo')->getLastSql()."<br/>";
		if(!$relayUid) {
			return ;
		}
		foreach ($relayUid as $key => $val) {
			if($val){
				//写入缓存
				$cacheRedis->zAdd($redisKey,$val['weibo_time'],$val['uid']);
			}
		}
		return true;
	}
	
	/**
	 * 转播微博更新缓存
	 *
	 * @param $wid int 微博id
	 * @param $uid int 用户id
	 *
	 * @return boolean
	*/
	public function addWeiboRelayRedis($wid,$uid){
		$cacheRedis = Cache::getInstance('Redis');
		$redisKey = C('REDIS_KEY.weibo').$wid;
		//增加一个元素
		$cacheRedis->zAdd($redisKey,time(),$uid);
		return true;
	}

	/**
	 * 删除的是原始微博，则删除集合 flag = 1
	 * 删除微博(转播过的微博)修改缓存 flag = 2
	 *
	 * @param $wid int 微博id
	 * @param $uid int 用户id
	 * @param $flag int 标识 1原创 2转播
	 *
	 * @return boolean
	*/
	public function delWeiboRelayRedis($wid,$uid,$flag){
		$cacheRedis = Cache::getInstance('Redis');
		$redisKey = C('REDIS_KEY.weibo').$wid;
		//删除的是原始微博，则删除集合 flag = 1
		//删除微博(转播过的微博)修改缓存 flag = 2
		if($flag == 1){
			//删除集合
			$cacheRedis->del($redisKey);
		}else{
			//减少一个元素
			$cacheRedis->zDelete($redisKey,$uid);
		}
		return true;
	}
	
	/**
	 * 微博信息   （移动端用）
	 * 
	 * @param  $param array 参数数组
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 微博列表
	 */
	public function getWeiboByUid($param){
		$uid = $param['uid'];
		$where = 'uid ='.$uid.' and status = 0';
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:8;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_weibo')->where($where)->count();
		$weiboarr = M()->Table('uc_weibo')->where($where)->order('weibo_time desc')->limit("$page_start, $page_num")->select();
		$list = array();
		$apiModel = D('Api');
		foreach($weiboarr as $lists){
			$lists['weibo_time'] = format_time($lists['weibo_time']);
			$lists['weibo_picbig'] = str_replace('_y','_b',$lists['weibo_pic']);
			$lists['weibo_picsml'] = str_replace('_y','_s',$lists['weibo_pic']);
			
			//查找被转播微博信息
			$oarr = M()->Table('uc_weibo')->where('id ='.$lists['relayid'])->find();
			
			//非原创微博
			if($lists['flag'] == 2){
				//判断原微博是否已删除
				if($oarr['status'] == 1){
					$lists['weibo_content'] = '抱歉，此啊呜已被删除。';
				}else{
					$lists['weibo_content'] = $oarr['weibo_content'];
				}
			}
			
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 微博信息   （移动端用）
	 * 
	 * @param  $param array 参数数组
	 *      wid int 微博id
	 *
	 * @return array 微博详情
	 */
	public function getWeiboDetailByWid($param){
		$wid = $param['wid'];
		$where = 'id ='.$wid.' and status = 0';
		//详情
		$info = M()->Table('uc_weibo')->where($where)->find();
		
		$info['weibo_time'] = format_time($info['weibo_time']);
		$info['weibo_picbig'] = str_replace('_y','_b',$info['weibo_pic']);
		$info['weibo_picsml'] = str_replace('_y','_s',$info['weibo_pic']);
		
		//查找被转播微博信息
		$oarr = M()->Table('uc_weibo')->where('id ='.$info['relayid'])->find();
		
		//非原创微博
		if($info['flag'] == 2){
			//判断原微博是否已删除
			if($oarr['status'] == 1){
				$info['weibo_content'] = '抱歉，此啊呜已被删除。';
			}else{
				$info['weibo_content'] = $oarr['weibo_content'];
			}
		}
		return $info;
	}
	
	/**
	 * 微博评论(最新五条)   （移动端用）
	 * 
	 * @param  $wid int 微博id
	 *      
	 *
	 * @return array 评论列表
	 */
	public function getMobileWeiboCommentsTop5($wid){
		$where = 'wid = '.$wid.' and status = 0';
		
		$commentarr = M()->Table('uc_weibo_reply')->where($where)->order('dateline desc')->limit(5)->select();
		$apiModel = D('Api');
		foreach($commentarr as $lists){
			$lists['dateline'] = format_time($lists['dateline']);
			//评论者信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//评论人头像使用缩略图(中)
			$lists['url_link'] = $userinfo['url_link'];

			//判断该评论下的回复
			if($lists['commentid'] != 0){
				$replyarr = M()->Table('uc_weibo_reply')->where('id ='.$lists['commentid'])->find();
				//原评论用户信息
				$oreplayuser = $apiModel->getUserInfo($replyarr['uid']);
				$lists['ouid'] = $oreplayuser['uid'];
				$lists['ourl_link'] = $oreplayuser['url_link'];
				$lists['onickname'] = $oreplayuser['nickname'];
				$lists['message'] = "回复@".$lists['onickname'].":".$lists['message'];
			}
			$list[] = $lists;
		}
		return $list;
	}
	
	/**
	 * 微博评论   （移动端用）
	 * 
	 * @param  $param array 参数数组
	 *      wid int 微博id
	 *      uid int 用户id
	 *      page int 当前页，默认为第1页
	 * 		pageNum int 页显数量，默认为20条
	 *
	 * @return array 评论列表
	 */
	public function getMobileWeiboComments($param){
		$wid = $param['wid'];
		$where = 'wid = '.$wid.' and status = 0';
		
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:10;
		$page_start = ($page-1)*$page_num;
		
		$this->total = M()->Table('uc_weibo_reply')->where($where)->count();
		$commentarr = M()->Table('uc_weibo_reply')->where($where)->order('dateline desc')->limit("$page_start, $page_num")->select();
		$apiModel = D('Api');
		foreach($commentarr as $lists){
			$lists['dateline'] = format_time($lists['dateline']);
			//评论者信息
			$userinfo = $apiModel->getUserInfo($lists['uid']);
			$lists['nickname'] = $userinfo['nickname'];
			$lists['avatar'] = $userinfo['avatar_m'];//评论人头像使用缩略图(中)
			$lists['url_link'] = $userinfo['url_link'];

			//判断该评论下的回复
			if($lists['commentid'] != 0){
				$replyarr = M()->Table('uc_weibo_reply')->where('id ='.$lists['commentid'])->find();
				//原评论用户信息
				$oreplayuser = $apiModel->getUserInfo($replyarr['uid']);
				$lists['ouid'] = $oreplayuser['uid'];
				$lists['ourl_link'] = $oreplayuser['url_link'];
				$lists['onickname'] = $oreplayuser['nickname'];
				$lists['message'] = "回复@".$lists['onickname'].":".$lists['message'];
			}
			$list[] = $lists;
		}
		return $list;
	}
}
?>